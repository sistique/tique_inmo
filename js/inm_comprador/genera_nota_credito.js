let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let sl_com_producto_id = $("#com_producto_id");
let txt_descripcion_nota_credito = $("#descripcion_nota_credito");


sl_com_producto_id.change(function(){
    let selected = $(this).find('option:selected');

    let descripcion_nota_credito = selected.data('com_producto_descripcion');

    txt_descripcion_nota_credito.val(descripcion_nota_credito);
});










