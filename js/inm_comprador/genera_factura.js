let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let sl_com_producto_id = $("#com_producto_id");
let txt_cuenta_predial = $("#cuenta_predial");


sl_com_producto_id.change(function(){
    let selected = $(this).find('option:selected');

    let aplica_predial = selected.data('com_producto_aplica_predial');

    txt_cuenta_predial.prop( "disabled", true );
    if(aplica_predial === 'activo'){
        txt_cuenta_predial.prop( "disabled", false );
    }

});










