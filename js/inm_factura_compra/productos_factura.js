let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let sl_inm_producto_id = $("#inm_producto_id");
let sl_cat_sat_unidad_id = $("#cat_sat_unidad_id");

sl_inm_producto_id.change(function () {
    let inm_producto_id = $(this).val();
    let url = "index.php?seccion=inm_producto&ws=1&accion=get_unidad&inm_producto_id=" + inm_producto_id +
        "&session_id=" + session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function (data) {  // Función que se ejecuta si todo ha ido bien
        sl_cat_sat_unidad_id.empty();

        integra_new_option('#cat_sat_unidad_id', 'Selecciona una opcion', '-1');
        integra_new_option('#cat_sat_unidad_id', data.cat_sat_unidad_descripcion, data.cat_sat_unidad_id);

        sl_cat_sat_unidad_id.val(data.cat_sat_unidad_id);
        sl_cat_sat_unidad_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown) { // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
});

let subtotal = 0;
let iva = 0;
let total = 0;

let cantidad_ct = $("#cantidad");
let valor_unitario_ct = $("#valor_unitario");
let subtotal_ct = $("#subtotal");
let iva_ct = $("#iva");
let total_ct = $("#total");

function calcularTotales() {
    let cantidad = parseFloat(cantidad_ct.val()) || 0;
    let valor_unitario = parseFloat(valor_unitario_ct.val()) || 0;

    subtotal = cantidad * valor_unitario;
    iva = subtotal * 0.16;
    total = subtotal + iva;

    subtotal_ct.val(subtotal.toFixed(2));
    iva_ct.val(iva.toFixed(2));
    total_ct.val(total.toFixed(2));
}

cantidad_ct.change(calcularTotales);
valor_unitario_ct.change(calcularTotales);
