let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let sl_inm_factura_compra_id = $("#inm_factura_compra_id");
let sl_inm_detalle_factura_compra_id = $("#inm_detalle_factura_compra_id");

sl_inm_factura_compra_id.change(function () {
    let inm_factura_compra_id = $(this).val();
    let url = "index.php?seccion=inm_factura_compra&ws=1&accion=get_detalles&inm_factura_compra_id=" + inm_factura_compra_id +
        "&session_id=" + session_id;

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            filtros: {
                "inm_factura_compra.id": inm_factura_compra_id
            }
        },
    }).done(function (data) {  // Función que se ejecuta si todo ha ido bien
        sl_inm_detalle_factura_compra_id.empty();

        integra_new_option('#inm_detalle_factura_compra_id','Seleccione una opcion','-1');
        $.each(data.registros, function( index, producto ) {
            integra_new_option('#inm_detalle_factura_compra_id',producto.inm_producto_descripcion,
                producto.inm_detalle_factura_compra_id);
        });

        sl_inm_detalle_factura_compra_id.val('-1');
        sl_inm_detalle_factura_compra_id.selectpicker('refresh');

    }).fail(function (jqXHR, textStatus, errorThrown) { // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
});


let txt_cantidad_detalle = $("#cantidad_detalle");
let txt_valor_unitario = $("#valor_unitario");
let txt_subtotal = $("#subtotal");
let txt_retenido = $("#retenido");
let txt_trasladado = $("#trasladado");
let txt_total = $("#total_con_impuesto");

sl_inm_detalle_factura_compra_id.change(function () {
    txt_cantidad_detalle.val("");
    txt_valor_unitario.val("");
    txt_subtotal.val("");
    txt_retenido.val("");
    txt_trasladado.val("");
    txt_total.val("");

    let inm_detalle_factura_compra_id = $(this).val();
    let url = "index.php?seccion=inm_factura_compra&ws=1&accion=get_detalles&inm_detalle_factura_compra_id="
        + inm_detalle_factura_compra_id + "&session_id=" + session_id;

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            filtros: {
                "inm_detalle_factura_compra.id": inm_detalle_factura_compra_id
            }
        },
    }).done(function (data) {  // Función que se ejecuta si todo ha ido bien

        txt_cantidad_detalle.val(data.registros[0].inm_detalle_factura_compra_cantidad);
        txt_valor_unitario.val(data.registros[0].inm_detalle_factura_compra_valor_unitario);
        txt_subtotal.val(data.registros[0].inm_detalle_factura_compra_subtotal);
        txt_retenido.val(data.registros[0].inm_detalle_factura_compra_retenido);
        txt_trasladado.val(data.registros[0].inm_detalle_factura_compra_trasladado);
        txt_total.val(data.registros[0].inm_detalle_factura_compra_total);
    }).fail(function (jqXHR, textStatus, errorThrown) { // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
});

let txt_cantidad_consumo = $("#cantidad_consumo");
txt_cantidad_consumo.on('input change', function () {
    let cantidad_consumo = parseFloat($(this).val()) || 0;
    let cantidad_detalle = parseFloat(txt_cantidad_detalle.val()) || 0;

    if (cantidad_consumo > cantidad_detalle) {
        alert("La cantidad de consumo no puede ser mayor que la del detalle");
        $(this).val(cantidad_detalle);
    }
});