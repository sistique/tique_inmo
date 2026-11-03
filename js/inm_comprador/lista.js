$(document).ready(function () {
    var table_inm_comprador = $('.datatable').DataTable();
    var filtro_aplicado = false;

    $('#limpiar').prop('disabled', true);

    function verificar_filtros() {
        var tiene_valor = false;

        $('.filtros-avanzados input').each(function () {
            if ($(this).val().trim() !== '') {
                tiene_valor = true;
                return false;
            }
        });

        $(".filtros-avanzados select").each(function () {
            if ($(this).val() !== '') {
                tiene_valor = true;
                return false;
            }
        });

        $('#limpiar').prop('disabled', !tiene_valor);
    }

    $('.filtros-avanzados input').on('input', function () {
        verificar_filtros();
    });

    $('.filtros-avanzados input').on('change', function () {
        $('.filtros-avanzados input').each(function () {
            if ($(this).val().trim() !== '') {
                $('#hidden_' + $(this).attr('id')).val($(this).val());
            }
        });
    });

    $('.filtros-avanzados select').on('change', function () {
        $('.filtros-avanzados select').each(function () {
            if ($(this).val() !== '') {
                $('#hidden_' + $(this).attr('id')).val($(this).val());
            }
        });
    });

    $('#filtrar').on('click', function () {
        $('#filtrar').prop('disabled', true);
        table_inm_comprador.ajax.reload(function () {
            $('#filtrar').prop('disabled', false);
            $('#limpiar').prop('disabled', false);
            filtro_aplicado = true;
        });
    });

    $('#limpiar').on('click', function () {
        $('.filtros-avanzados input').val('');
        $('.filtros-avanzados select').val('').trigger('change');;
        $('.filtros-avanzados li').remove();
        $('#limpiar').prop('disabled', true);

        if (filtro_aplicado) {
            table_inm_comprador.ajax.reload();
            filtro_aplicado = false;
        }
    });

    $(document).on('keydown', '.filtros-avanzados', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (!$('#filtrar').prop('disabled')) {
                $('#filtrar').click();
            }
        }
    });

    let filtroTimer = null;

    function dispararFiltradoDebounced() {
        clearTimeout(filtroTimer);
        filtroTimer = setTimeout(() => {
            if (!$('#filtrar').prop('disabled')) $('#filtrar').click();
        }, 400);
    }

    $('.basic-multiple')
        .on('change', function () {
            dispararFiltradoDebounced();
        })
        .select2({
            closeOnSelect: false,
            allowClear: true
        });

    $('.basic-multiple').select2({
        closeOnSelect: false,
        allowClear: true,
        width: '100%'
    });

    // Panel de ubicaciones (split view) visible solo con status DETENIDO
    const STATUS_DETENIDO = 'DETENIDO';
    const PCT_MIN = 30;
    const PCT_MAX = 70;
    const ANCHO_DIVISOR = 14;
    const ANCHO_ESTRECHO = 1350;
    let pct_compradores = 70;
    let table_ubicaciones = null;
    let $split = $('#split-view');
    let $panel_compradores = $('#panel-compradores');
    let $panel_ubicaciones = $('#panel-ubicaciones');

    function marca_panel_estrecho() {
        $panel_compradores.toggleClass('panel-estrecho', $panel_compradores.outerWidth() <= ANCHO_ESTRECHO);
    }

    function ajustar_tablas() {
        marca_panel_estrecho();
        table_inm_comprador.columns.adjust();
        if (table_inm_comprador.responsive) {
            table_inm_comprador.responsive.recalc();
        }
        if (table_ubicaciones) {
            table_ubicaciones.columns.adjust();
        }
    }

    function aplicar_anchos() {
        if (!$split.hasClass('con-ubicaciones')) {
            $panel_compradores.css('width', '');
            $panel_ubicaciones.css('width', '');
            return;
        }
        $panel_compradores.css('width', `calc(${pct_compradores}% - ${ANCHO_DIVISOR / 2}px)`);
        $panel_ubicaciones.css('width', `calc(${100 - pct_compradores}% - ${ANCHO_DIVISOR / 2}px)`);
    }

    function init_tabla_ubicaciones() {
        if (table_ubicaciones) {
            return;
        }
        table_ubicaciones = $('#tabla-ubicaciones').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ordering: false,
            pageLength: 10,
            ajax: {
                url: get_url('inm_ubicacion', 'get_data', {}),
                error: function (jqXHR) {
                    console.error('Error al cargar ubicaciones:', jqXHR.responseText);
                }
            },
            columns: [
                {data: 'inm_ubicacion_id'},
                {data: 'inm_ubicacion_ubicacion', defaultContent: ''},
                {
                    data: null,
                    render: function (data, type, row) {
                        let partes = [];
                        if (row.dp_cp_descripcion) partes.push('CP ' + row.dp_cp_descripcion);
                        if (row.inm_ubicacion_manzana) partes.push('Mz ' + row.inm_ubicacion_manzana);
                        if (row.inm_ubicacion_lote) partes.push('Lt ' + row.inm_ubicacion_lote);
                        if (row.inm_ubicacion_etapa) partes.push('Etapa ' + row.inm_ubicacion_etapa);
                        return partes.join(', ');
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return row.modifica || '';
                    }
                }
            ]
        });
    }

    function actualiza_panel_ubicaciones() {
        let valores = $('#inm_status_comprador').val() || [];
        if (!Array.isArray(valores)) {
            valores = [valores];
        }
        let mostrar = valores.includes(STATUS_DETENIDO);

        if (mostrar === $split.hasClass('con-ubicaciones')) {
            $('.split-view .split-panel').css('overflow', '');
            return;
        }

        $('.split-view .split-panel').css('overflow', 'auto');

        $split.toggleClass('con-ubicaciones', mostrar);
        aplicar_anchos();
        if (mostrar) {
            init_tabla_ubicaciones();
        }
        ajustar_tablas();
    }

    $('#inm_status_comprador').on('change', actualiza_panel_ubicaciones);
    actualiza_panel_ubicaciones();
    marca_panel_estrecho();

    $('#split-divisor').on('mousedown', function (e) {
        e.preventDefault();
        let $divisor = $(this).addClass('arrastrando');
        let ancho_total = $split.width();
        let izquierda = $split.offset().left;

        $('body').css({cursor: 'col-resize', userSelect: 'none'});

        $(document).on('mousemove.split', function (ev) {
            let pct = ((ev.pageX - izquierda) / ancho_total) * 100;
            pct_compradores = Math.min(PCT_MAX, Math.max(PCT_MIN, pct));
            aplicar_anchos();
            marca_panel_estrecho();
        });

        $(document).on('mouseup.split', function () {
            $(document).off('.split');
            $divisor.removeClass('arrastrando');
            $('body').css({cursor: '', userSelect: ''});
            ajustar_tablas();
        });
    });

    $(window).on('resize', function () {
        marca_panel_estrecho();
        if ($split.hasClass('con-ubicaciones')) {
            ajustar_tablas();
        }
    });

});
