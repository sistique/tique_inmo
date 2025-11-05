let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let sl_com_producto_id = $("#com_producto_id");
let txt_cuenta_predial = $("#cuenta_predial");
let txt_descripcion_factura = $("#descripcion_factura");


sl_com_producto_id.change(function(){
    let selected = $(this).find('option:selected');

    let descripcion_factura = selected.data('com_producto_descripcion');
    let aplica_predial = selected.data('com_producto_aplica_predial');

    txt_descripcion_factura.val(descripcion_factura);

    txt_cuenta_predial.prop( "disabled", true );
    if(aplica_predial === 'activo'){
        txt_cuenta_predial.prop( "disabled", false );
    }

});










