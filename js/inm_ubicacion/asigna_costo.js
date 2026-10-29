let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

$(document).ready(function() {
    $('.fila-presupuesto').on('click', function () {
        let id = $(this).attr('data-id');
        $(`#detalle-${id}`).toggle();
    });

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
                    <tr class="form-pago" data-id="${response.inm_pago_costo_id}">
                        <td>
                            ${response.gt_proveedor_razon_social}
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_gt_proveedor_id}
                            </div>
                        </td>
                        <td>
                            $${parseFloat(response.inm_pago_costo_monto).toFixed(2)}
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_monto}
                            </div>
                        </td>
                        <td>
                            ${response.cat_sat_forma_pago_descripcion}
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_cat_sat_forma_pago_id}
                            </div>
                        </td>
                        <td>
                            ${response.inm_pago_costo_fecha_pago}
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_fecha_pago}
                            </div>
                        </td>       
                        <td>
                            ${response.inm_pago_costo_referencia}
                            <div class="cont-mod-gasto mod-gasto-${response.inm_pago_costo_id}">
                                ${response.campo_referencia}
                            </div>
                        </td>
                        <td>${response.modifica} ${response.elimina_bd}</td>
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

        $(`.mod-gasto-${id}`).toggle();

        contenedor.find(`[name="gt_proveedor_id[${id}]"]`).val();
        contenedor.find(`[name="cat_sat_forma_pago_id[${id}]"]`).val();
        console.log('Modificar pago con ID:', id);
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