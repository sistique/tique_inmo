$(document).ready(function () {
    var table_inm_prospecto = $('.datatable').DataTable();
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
        console.log($(this).val());
        $('.filtros-avanzados select').each(function () {
            if ($(this).val() !== '') {
                $('#hidden_' + $(this).attr('id')).val($(this).val());
            }
        });
    });

    $('#filtrar').on('click', function () {
        $('#filtrar').prop('disabled', true);
        table_inm_prospecto.ajax.reload(function () {
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
            table_inm_prospecto.ajax.reload();
            filtro_aplicado = false;
        }
    });

    $('.basic-multiple').select2();


});
