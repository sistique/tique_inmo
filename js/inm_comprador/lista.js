iniciarFiltrosLista('inm_comprador');

$(document).ready(function () {
    var table_inm_comprador = $('.datatable').DataTable();
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
                data: function (d) {
                    d.data = {inm_ubicacion_tiene_comprador_activo: 0};
                },
                error: function (jqXHR) {
                    console.error('Error al cargar ubicaciones:', jqXHR.responseText);
                }
            },
            columns: [
                {data: 'inm_ubicacion_id'},
                {data: 'inm_ubicacion_ubicacion', defaultContent: ''},
                {data: 'inm_ubicacion_estimacion_avaluo', defaultContent: ''},
                {data: 'inm_ubicacion_monto_presupuesto_total', defaultContent: ''},
                {data: 'inm_ubicacion_monto_total_pagado', defaultContent: ''},
                {
                    data: null,
                    render: function (data, type, row) {
                        return row.proceso_ubicacion || '';
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
        let mostrar = $panel_ubicaciones.length > 0 && valores.includes(STATUS_DETENIDO);

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
