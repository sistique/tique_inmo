function iniciarFiltrosLista(catalogo) {
    var campos = $('.filtros-avanzados input[data-tipo], .filtros-avanzados select[data-tipo]');
    var storageKey = catalogo + '.filtros:' + window.location.pathname + ':' +
        $('.filtros-avanzados').data('usuario-id');
    var filtro_aplicado = false;

    function error_almacenamiento(error) {
        console.error('Error al guardar o recuperar los filtros de ' + catalogo + ':', error);
        alert('No fue posible guardar o recuperar los filtros de ' + catalogo + ' en este navegador.');
    }

    function sincronizar_exportacion() {
        campos.each(function () {
            var hidden = document.getElementById('hidden_' + this.id);
            if (hidden) {
                var valor = $(this).val();
                hidden.value = Array.isArray(valor) ? valor.join(',') : (valor || '');
            }
        });
    }

    try {
        var filtros_guardados = localStorage.getItem(storageKey);
        if (filtros_guardados !== null) {
            var filtros = JSON.parse(filtros_guardados);
            campos.each(function () {
                if (Object.prototype.hasOwnProperty.call(filtros, this.id)) {
                    $(this).val(filtros[this.id]);
                }
            });
            filtro_aplicado = true;
        }
    } catch (error) {
        error_almacenamiento(error);
    }
    sincronizar_exportacion();

    $(document).ready(function () {
        var table_inm_prospecto = $('.datatable').DataTable();
        var filtroTimer = null;

        function guardar_filtros() {
            var filtros = {};
            campos.each(function () {
                filtros[this.id] = $(this).val();
            });
            try {
                localStorage.setItem(storageKey, JSON.stringify(filtros));
            } catch (error) {
                error_almacenamiento(error);
            }
        }

        function verificar_filtros() {
            var tiene_valor = filtro_aplicado;
            campos.each(function () {
                var valor = $(this).val();
                if (Array.isArray(valor) ? valor.length > 0 : valor && valor.trim() !== '') {
                    tiene_valor = true;
                    return false;
                }
            });

            $('#limpiar').prop('disabled', !tiene_valor);
        }

        campos.on('input change', function () {
            sincronizar_exportacion();
            verificar_filtros();
        });

        $('#filtrar').on('click', function () {
            clearTimeout(filtroTimer);
            sincronizar_exportacion();
            guardar_filtros();
            filtro_aplicado = true;
            verificar_filtros();
            $('#filtrar').prop('disabled', true);

            table_inm_prospecto.ajax.reload(function () {
                $('#filtrar').prop('disabled', false);
            });
        });

        $('#limpiar').on('click', function () {
            clearTimeout(filtroTimer);
            campos.filter('input').val('');
            campos.filter('select').val(null).trigger('change');
            clearTimeout(filtroTimer);
            sincronizar_exportacion();

            try {
                localStorage.removeItem(storageKey);
            } catch (error) {
                error_almacenamiento(error);
            }

            if (filtro_aplicado) {
                filtro_aplicado = false;
                table_inm_prospecto.ajax.reload();
            }
            verificar_filtros();
        });

        $(document).on('keydown', '.filtros-avanzados', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (!$('#filtrar').prop('disabled')) {
                    $('#filtrar').click();
                }
            }
        });

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
                allowClear: true,
                width: '100%'
            });

        verificar_filtros();
    });
}
