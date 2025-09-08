let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let url_prd = get_url("inm_factura_compra", "get_detalles", {registro_id: registro_id});

$('#inm_factura_compra_id').change(function(){
    let tbody = $('.inm_detalle_factura_compra tbody');
    tbody.empty();

    let filtro_inm_detalle_factura = {
        filtro: {},
        extra_join: []
    };

    let factura_id = $(this).val();

    if (factura_id && factura_id !== '-1' && factura_id !== '0' && factura_id !== '') {
        filtro_inm_detalle_factura.filtro['inm_factura_compra_id'] = factura_id;

        $.ajax({
            url: url_prd,
            type: 'POST',
            data: {filtros: filtro_inm_detalle_factura},
            success: function (data) {
                if (Array.isArray(data)) {
                    data.forEach(function (detalle) {
                        let tr = $('<tr>');
                        let checkbox = $('<input name="inm_detalle_factura_compra_id[]" type="checkbox" class="producto-checkbox">')
                            .val(detalle.inm_detalle_factura_compra_id);

                        tr.append($('<td>').append(checkbox));
                        tr.append($('<td>').text(detalle.inm_detalle_factura_compra_descripcion));
                        tr.append($('<td>').text(detalle.inm_detalle_factura_compra_cantidad));
                        tr.append($('<td>').text(detalle.inm_detalle_factura_compra_valor_unitario));
                        tr.append($('<td>').text(detalle.inm_detalle_factura_compra_subtotal));
                        tr.append($('<td>').text(detalle.inm_detalle_factura_compra_trasladado));
                        tr.append($('<td>').text(detalle.inm_detalle_factura_compra_retenido));
                        tr.append($('<td>').text(detalle.inm_detalle_factura_compra_total));
                        tbody.append(tr);
                    });
                } else {
                    console.log("Respuesta inesperada:", data);
                }
            },
            error: function () {
                console.log('Error en la petición AJAX');
            }
        });
    }
});