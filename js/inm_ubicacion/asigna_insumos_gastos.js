let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let sl_inm_factura_compra_id = $("#inm_factura_compra_id");
let sl_inm_detalle_factura_compra_id = $("#inm_detalle_factura_compra_id");


let txt_cantidad_detalle = $("#cantidad_detalle");
let txt_valor_unitario = $("#valor_unitario");
let txt_subtotal = $("#subtotal");
let txt_retenido = $("#retenido");
let txt_trasladado = $("#trasladado");
let txt_total = $("#total_con_impuesto");

let detalles_cache = [];

sl_inm_factura_compra_id.change(function () {
    let inm_factura_compra_id = $(this).val();

    if (inm_factura_compra_id === "-1" || inm_factura_compra_id === "" ) {
        sl_inm_detalle_factura_compra_id.empty();
        integra_new_option('#inm_detalle_factura_compra_id', 'Seleccione una opción', '-1');

        sl_inm_detalle_factura_compra_id.val('-1');
        sl_inm_detalle_factura_compra_id.selectpicker('refresh');

        txt_cantidad_detalle.val("");
        txt_valor_unitario.val("");
        txt_subtotal.val("");
        txt_retenido.val("");
        txt_trasladado.val("");
        txt_total.val("");

        return;
    }

    let url = "index.php?seccion=inm_factura_compra&ws=1&accion=get_detalles&inm_factura_compra_id=" + inm_factura_compra_id +
        "&session_id=" + session_id;

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            filtros: {
                "inm_factura_compra.id": inm_factura_compra_id,
                "inm_detalle_factura_compra.asignado_completo": 'inactivo'
            }
        },
    }).done(function (data) {
        detalles_cache = data.registros || [];

        sl_inm_detalle_factura_compra_id.empty();
        integra_new_option('#inm_detalle_factura_compra_id', 'Seleccione una opción', '-1');

        $.each(detalles_cache, function (index, producto) {
            integra_new_option('#inm_detalle_factura_compra_id',
                producto.inm_producto_descripcion,
                producto.inm_detalle_factura_compra_id);
        });

        sl_inm_detalle_factura_compra_id.val('-1');
        sl_inm_detalle_factura_compra_id.selectpicker('refresh');
    }).fail(function () {
        alert('Error al ejecutar');
    });
});

sl_inm_detalle_factura_compra_id.change(function () {
    let id = $(this).val();

    // Limpiar campos
    txt_cantidad_detalle.val("");
    txt_valor_unitario.val("");
    txt_subtotal.val("");
    txt_retenido.val("");
    txt_trasladado.val("");
    txt_total.val("");

    if (id === "-1") return;

    // 🔍 Buscar el detalle seleccionado en el cache
    let detalle = detalles_cache.find(d => d.inm_detalle_factura_compra_id == id);

    if (detalle) {
        txt_cantidad_detalle.val(detalle.inm_detalle_factura_compra_cantidad);
        txt_valor_unitario.val(detalle.inm_detalle_factura_compra_valor_unitario);
        txt_subtotal.val(detalle.inm_detalle_factura_compra_subtotal);
        txt_retenido.val(detalle.inm_detalle_factura_compra_retenido);
        txt_trasladado.val(detalle.inm_detalle_factura_compra_trasladado);
        txt_total.val(detalle.inm_detalle_factura_compra_total);
    }
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