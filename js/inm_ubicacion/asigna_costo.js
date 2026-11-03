let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

$(document).ready(function() {
    $('.fila-presupuesto').on('click', function (e) {
        if ($(e.target).closest('[class*="acciones-presupuesto-"], .cont-mod-presupuesto, [class*="cont-mod-presupuesto-btns-"]').length) {
            return;
        }
        let id = $(this).attr('data-id');
        $(`#detalle-${id}`).toggle();
    });

    function formatoMonto(valor) {
        return (parseFloat(valor) || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function validarCampo(selector) {
        return $(selector).val().trim();
    }

    $('.btn-guardar-pago').on('click', function () {
        let contenedor = $(this).closest('.form-inline-pago');
        let id = contenedor.attr('data-id');
        let concepto_id = contenedor.attr('data-concepto-id');

        let url_alta_prd = get_url("inm_costo", "inserta_costo_bd", {registro_id: registro_id});

        let registro = {
            inm_presupuesto_costo_id: id,
            inm_ubicacion_id: registro_id, // o el selector correspondiente a tu ubicación
            inm_concepto_id: concepto_id,
            gt_proveedor_id: contenedor.find(`[name="gt_proveedor_id"], select[id*="gt_proveedor_id"]`).val(),
            monto: validarCampo(`#monto\\[${id}\\]`),
            cat_sat_forma_pago_id: contenedor.find(`select[name*="forma_pago"], select[id*="cat_sat_forma_pago_id"]`).val(),
            fecha_pago: validarCampo(`#fecha_pago\\[${id}\\]`),
            referencia: validarCampo(`#referencia\\[${id}\\]`),
        };

        $.ajax({
            url: url_alta_prd,
            type: 'POST',
            data: registro,
            success: function (response) {
                if (!response || response.error || !response.inm_pago_costo_monto) {
                    console.error('Error en la respuesta:', response);
                    return;
                }

                let nuevoTr = $(`
                    <tr class="form-pago" data-id="${response.inm_pago_costo_id}"  data-presupuesto-id="${id}">
                        <td>
                            <div class="texto-gasto-${response.inm_pago_costo_id}">
                                ${response.gt_proveedor_razon_social}
                            </div>
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_gt_proveedor_id}
                            </div>
                        </td>
                        <td>
                            <div class="texto-gasto-${response.inm_pago_costo_id}">
                                $${formatoMonto(response.inm_pago_costo_monto)}
                            </div>
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_monto}
                            </div>
                        </td>
                        <td>
                            <div class="texto-gasto-${response.inm_pago_costo_id}">
                                ${response.cat_sat_forma_pago_descripcion}
                            </div>
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_cat_sat_forma_pago_id}
                            </div>
                        </td>
                        <td>
                            <div class="texto-gasto-${response.inm_pago_costo_id}">
                                ${response.inm_pago_costo_fecha_pago}
                            </div>
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_fecha_pago}
                            </div>
                        </td>       
                        <td>
                            <div class="texto-gasto-${response.inm_pago_costo_id}">
                                ${response.inm_pago_costo_referencia}
                            </div>
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_referencia}
                            </div>
                        </td>
                        <td>
                            <div class="cont-buttons-${response.inm_pago_costo_id}">
                                ${response.modifica} ${response.elimina_bd}
                            </div>
                            <div class="cont-buttons-gasto cont-buttons-up${response.inm_pago_costo_id}">
                                <button type="button" class="btn btn-info btn-sm btn-g-p" title="Modifica">✓</button>
                                <button type="button" class="btn btn-info btn-sm btn-c-p" title="Cancelar">x</button>
                            </div>
                        </td>
                    </tr>
                `);

                contenedor.before(nuevoTr);

                nuevoTr.find('select.selectpicker').selectpicker();

                nuevoTr.find('.bootstrap-select > button.dropdown-toggle')
                    .removeClass('btn-default')
                    .addClass('selectpicker-primary');

                // Actualizar el acumulado del "Total Gastado" en la fila principal
                let filaPrincipal = $(`tr.fila-presupuesto[data-id="${id}"]`);
                let elementoTotal = filaPrincipal.find('.total-gastado');
                let totalActual = parseFloat(elementoTotal.text().replace(/[^0-9.-]+/g, "")) || 0;
                let nuevoTotal = totalActual + parseFloat(response.inm_pago_costo_monto);
                elementoTotal.text(`$${nuevoTotal.toFixed(2)}`);

                let tipo = filaPrincipal.data('tipo');
                let spanTipo = $(`tr.fila-tipo-concepto[data-tipo="${tipo}"] .total-gastado-tipo`);
                let gastadoTipo = parseFloat(spanTipo.text().replace(/[^0-9.-]+/g, "")) || 0;
                spanTipo.text((gastadoTipo + parseFloat(response.inm_pago_costo_monto)).toFixed(2));

                contenedor.find(`[name="gt_proveedor_id[${id}]"]`).val('-1').selectpicker('refresh');
                contenedor.find(`[name="cat_sat_forma_pago_id[${id}]"]`).val('-1').selectpicker('refresh');

                contenedor.find(`#monto\\[${id}\\]`).val('');
                contenedor.find(`#referencia\\[${id}\\]`).val('');
                contenedor.find(`#fecha_pago\\[${id}\\]`).val('');
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    });

    $(document).on('click', '.btn-cancelar-pago', function () {
        let contenedor = $(this).closest('.form-inline-pago');
        let id = contenedor.attr('data-id');

        contenedor.find(`[name="gt_proveedor_id[${id}]"]`).val('-1').selectpicker('refresh');
        contenedor.find(`[name="cat_sat_forma_pago_id[${id}]"]`).val('-1').selectpicker('refresh');

        contenedor.find(`#monto\\[${id}\\]`).val('');
        contenedor.find(`#fecha_pago\\[${id}\\]`).val('');
        contenedor.find(`#referencia\\[${id}\\]`).val('');
    });

    $(document).on('click', '.modifica_pago_costo', function () {

        let contenedor = $(this).closest('.form-pago');
        let id = contenedor.attr('data-id');

        $(".form-inline-pago").show();

        let presupuesto_id = contenedor.attr('data-presupuesto-id');

        $(`.form-alta-costo-${presupuesto_id}`).hide();

        $('.cont-mod-gasto').hide();
        $('.cont-buttons-gasto').hide();
        $('[class*="texto-gasto-"]').show();
        $('[class*="cont-buttons-"]').not('[class*="cont-buttons-gasto"]').show();

        contenedor.find('.cont-mod-gasto').show();
        contenedor.find('.cont-buttons-gasto').show();

        contenedor.find(`.texto-gasto-${id}`).hide();
        contenedor.find(`.cont-buttons-${id}`).hide();

        contenedor.find('input, select, textarea').each(function () {
            $(this).data('valor-original', $(this).val());
        });

    });


    $(document).on('click', '.btn-g-p', function () {

        let contenedor = $(this).closest('.form-pago');
        let id = contenedor.attr('data-id');

        let monto = contenedor.find('[name="monto"]').val();
        let fecha = contenedor.find('[name="fecha_pago"]').val();
        let referencia = contenedor.find('[name="referencia"]').val();
        let gt_proveedor_id = contenedor.find(`[name="gt_proveedor_id[${id}]"]`).val();
        let cat_sat_forma_pago_id = contenedor.find(`[name="cat_sat_forma_pago_id[${id}]"]`).val();

        let datos = {
            id: id,
            gt_proveedor_id: gt_proveedor_id,
            cat_sat_forma_pago_id: cat_sat_forma_pago_id,
            monto: monto,
            fecha_pago: fecha,
            referencia: referencia
        };

        console.log(datos);

        let url_modifica_prd = get_url("inm_costo", "modifica_costo_bd", {registro_id: registro_id});

        $.ajax({
            url: url_modifica_prd,
            type: 'POST',
            data: datos,
            success: function (response) {
                if (!response || response.error) {
                    console.error('Error en la respuesta:', response);
                    return;
                }

                let montoInput = contenedor.find('[name="monto"]');
                let montoAnterior = parseFloat(montoInput.data('valor-original')) || 0;
                let montoNuevo = parseFloat(monto) || 0;

                let textoProveedor = contenedor.find(`[name="gt_proveedor_id[${id}]"] option:selected`).text().trim();
                let textoForma = contenedor.find(`[name="cat_sat_forma_pago_id[${id}]"] option:selected`).text().trim();

                let textos = contenedor.find(`div.texto-gasto-${id}`);
                textos.eq(0).text(textoProveedor);
                textos.eq(1).text(`$${formatoMonto(montoNuevo)}`);
                textos.eq(2).text(textoForma);
                textos.eq(3).text(fecha);
                textos.eq(4).text(referencia);

                // Los valores guardados pasan a ser el nuevo "original" para cancelar
                contenedor.find('input, select, textarea').each(function () {
                    $(this).data('valor-original', $(this).val());
                });

                let diferencia = montoNuevo - montoAnterior;
                let presupuesto_id = contenedor.attr('data-presupuesto-id');
                let filaPrincipal = $(`tr.fila-presupuesto[data-id="${presupuesto_id}"]`);
                let elementoTotal = filaPrincipal.find('.total-gastado');
                let totalActual = parseFloat(elementoTotal.text().replace(/[^0-9.-]+/g, "")) || 0;
                elementoTotal.text(`$${(totalActual + diferencia).toFixed(2)}`);

                let tipo = filaPrincipal.data('tipo');
                let spanTipo = $(`tr.fila-tipo-concepto[data-tipo="${tipo}"] .total-gastado-tipo`);
                let gastadoTipo = parseFloat(spanTipo.text().replace(/[^0-9.-]+/g, "")) || 0;
                spanTipo.text((gastadoTipo + diferencia).toFixed(2));

                contenedor.find('.cont-mod-gasto').hide();
                contenedor.find('.cont-buttons-gasto').hide();
                contenedor.find(`.texto-gasto-${id}`).show();
                contenedor.find(`.cont-buttons-${id}`).show();
                $(`.form-alta-costo-${presupuesto_id}`).show();
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
            }
        });
    });


    $(document).on('click', '.modifica_presupuesto, .cont-mod-presupuesto', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', '.modifica_presupuesto', function (e) {
        e.preventDefault();
        let fila = $(this).closest('tr.fila-presupuesto');
        let id = fila.attr('data-id');

        $('tr.fila-presupuesto').not(fila).each(function () {
            let otra = $(this);
            let otro_id = otra.attr('data-id');
            let cont = otra.find(`.cont-mod-presupuesto-${otro_id}`);
            cont.find('input').val(cont.attr('data-valor-original'));
            cont.hide();
            otra.find(`.cont-mod-presupuesto-btns-${otro_id}`).hide();
            otra.find(`.texto-presupuesto-${otro_id}`).show();
            otra.find(`.acciones-presupuesto-${otro_id}`).show();
        });

        fila.find(`.texto-presupuesto-${id}`).hide();
        fila.find(`.acciones-presupuesto-${id}`).hide();
        fila.find(`.cont-mod-presupuesto-btns-${id}`).show();
        fila.find(`.cont-mod-presupuesto-${id}`).show().find('input').focus();
    });

    $(document).on('click', '.btn-c-presupuesto', function () {
        let fila = $(this).closest('tr.fila-presupuesto');
        let id = fila.attr('data-id');
        let cont = fila.find(`.cont-mod-presupuesto-${id}`);
        cont.find('input').val(cont.attr('data-valor-original'));
        cont.hide();
        fila.find(`.texto-presupuesto-${id}`).show();
        fila.find(`.acciones-presupuesto-${id}`).show();
        fila.find(`.cont-mod-presupuesto-btns-${id}`).hide();
    });

    $(document).on('click', '.btn-g-presupuesto', function () {
        let fila = $(this).closest('tr.fila-presupuesto');
        let id = fila.attr('data-id');
        let cont = fila.find(`.cont-mod-presupuesto-${id}`);
        let montoNuevo = parseFloat(cont.find('input').val());
        let montoAnterior = parseFloat(cont.attr('data-valor-original')) || 0;

        if (isNaN(montoNuevo) || montoNuevo < 0) {
            alert('Ingrese un monto válido');
            return;
        }

        let url_modifica_pre = get_url("inm_costo", "modifica_presupuesto_bd", {registro_id: registro_id});

        $.ajax({
            url: url_modifica_pre,
            type: 'POST',
            data: {id: id, monto_presupuesto: montoNuevo},
            success: function (response) {
                if (!response || response.error) {
                    console.error('Error en la respuesta:', response);
                    return;
                }

                fila.find('.monto-presupuesto').text(formatoMonto(montoNuevo));
                cont.attr('data-valor-original', montoNuevo);

                let tipo = fila.data('tipo');
                let spanTipo = $(`tr.fila-tipo-concepto[data-tipo="${tipo}"] .total-presupuesto-tipo`);
                let totalTipo = parseFloat(spanTipo.text().replace(/[^0-9.-]+/g, "")) || 0;
                spanTipo.text(formatoMonto(totalTipo + montoNuevo - montoAnterior));

                cont.hide();
                fila.find(`.texto-presupuesto-${id}`).show();
                fila.find(`.acciones-presupuesto-${id}`).show();
        fila.find(`.cont-mod-presupuesto-btns-${id}`).hide();
            },
            error: function (xhr) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
            }
        });
    });

    $(document).on('click', '.btn-c-p', function () {
        let contenedor = $(this).closest('.form-pago');
        let id = contenedor.attr('data-id');

        let presupuesto_id = contenedor.attr('data-presupuesto-id');
        $(`.form-alta-costo-${presupuesto_id}`).show();

        contenedor.find('input, select, textarea').each(function () {

            let valorOriginal = $(this).data('valor-original');

            if (valorOriginal !== undefined) {
                $(this).val(valorOriginal);
            }

        });


        contenedor.find('select').each(function () {
            $(this).selectpicker();

            $(this).removeClass('btn-default').addClass('selectpicker-primary');

            let valorOriginal = $(this).data('valor-original');
            if (valorOriginal !== undefined) {
                $(this).val(valorOriginal).selectpicker('refresh');
            }
        });

        contenedor.find('.cont-mod-gasto').hide();
        contenedor.find('.cont-buttons-gasto').hide();

        contenedor.find(`.texto-gasto-${id}`).show();
        contenedor.find(`.cont-buttons-${id}`).show();

    });
});



/*
document.addEventListener('click', function(e) {
    // 1. Lógica para desplegar / ocultar el acordeón
    if (e.target.classList.contains('btn-toggle-detalle') || e.target.closest('.btn-toggle-detalle')) {
        const btn = e.target.closest('.btn-toggle-detalle');
        const targetSelector = btn.getAttribute('data-target');
        const filaDetalle = document.querySelector(targetSelector);

        if (filaDetalle) {
            if (filaDetalle.style.display === 'none') {
                filaDetalle.style.display = 'table-row';
                btn.textContent = '▲ Ocultar';
            } else {
                filaDetalle.style.display = 'none';
                btn.textContent = '▼ Desplegar pagos';
            }
        }
    }

    // 2. Lógica para guardar el pago de forma asíncrona (AJAX)
    if (e.target.classList.contains('btn-guardar-pago') || e.target.closest('.btn-guardar-pago')) {
        const btnGuardar = e.target.closest('.btn-guardar-pago');
        const contenedor = btnGuardar.closest('.form-inline-pago');
        const presupuestoId = contenedor.getAttribute('data-id');

        const proveedorId = contenedor.querySelector('.input-proveedor').value;
        const monto = parseFloat(contenedor.querySelector('.input-monto').value) || 0;
        const formaPago = contenedor.querySelector('.input-forma-pago').value;
        const fecha = contenedor.querySelector('.input-fecha').value;
        const referencia = contenedor.querySelector('.input-referencia').value;

        if (monto <= 0 || !fecha) {
            alert('Por favor ingresa un monto válido y la fecha de pago.');
            return;
        }

        const formData = new URLSearchParams();
        formData.append('inm_presupuesto_costo_id', presupuestoId);
        formData.append('gt_proveedor_id', proveedorId);
        formData.append('monto', monto);
        formData.append('cat_sat_forma_pago_id', formaPago);
        formData.append('fecha_pago', fecha);
        formData.append('referencia', referencia);

        fetch('guardar_gasto.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Agregar el pago visualmente a la lista desplegada
                    const listaPagos = document.getElementById(`pagos-${presupuestoId}`);
                    const nuevoItem = document.createElement('li');
                    nuevoItem.textContent = `$${monto.toFixed(2)} (Ref: ${referencia || 'S/N'} - Fecha: ${fecha})`;
                    listaPagos.appendChild(nuevoItem);

                    // Actualizar el acumulado en la fila principal
                    const filaPrincipal = document.querySelector(`tr[data-presupuesto-id="${presupuestoId}"]`) || contenedor.closest('tr').previousElementSibling;
                    // Si prefieres buscarlo de forma directa:
                    const elementoTotal = contenedor.closest('table').querySelector(`tr[data-presupuesto-id="${presupuestoId}"] .total-gastado`) // O ajustando la ruta visual:

                    // Limpiar inputs
                    contenedor.querySelector('.input-monto').value = '';
                    contenedor.querySelector('.input-referencia').value = '';
                    contenedor.querySelector('.input-fecha').value = '';

                    alert('Pago registrado correctamente.');
                } else {
                    alert('Error al guardar: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    }
});*/