let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let url_prd = get_url("inm_factura_compra", "get_detalles", {registro_id: registro_id});

$('#asignar').on('click', function () {
    let filtro_inm_detalle_factura = {
        filtro: {},
        extra_join: []
    };

    if ($(this).val() && ($(this).val() !== -1 || $(this).val() !== 0)) {
        filtro_inm_detalle_factura.filtro['inm_factura_compra_id'] = $(this).val();
    }

    $.ajax({
        url: url_prd,
        type: 'POST',
        data: {filtros: filtro_inm_detalle_factura},
        success: function (data) {
            let tbody = $('.inm_detalle_factura_compra tbody');
            tbody.empty();

            data.forEach(function (detalle) {
                let tr = $('<tr>');
                let checkbox = $('<input name="producto" type="checkbox" class="producto-checkbox">')
                    .val(detalle.inm_detalle_factura_compra_id);

                tr.append($('<td>').append(checkbox));
                tr.append($('<td>').text(detalle.inm_detalle_factura_compra_id));
                tr.append($('<td>').text(detalle.inm_detalle_factura_compra_descripcion));
                tbody.append(tr);
            });

        },
        error: function () {
            console.log('error');
        }
    });
});