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
        type: 'GET',
        url: url,
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