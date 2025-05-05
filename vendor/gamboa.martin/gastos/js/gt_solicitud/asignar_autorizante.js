
let autorizante = document.getElementById("gt_autorizante_id");

let btn_alta = $(".btn-asignar");

let autorizante_duplicado_error = $(".label-error-autorizante");


autorizante_duplicado_error.hide();

btn_alta.on('click', function(  ){
    if(id_autorizantes.includes(parseInt(autorizante.value))){
        autorizante_duplicado_error.show();
        return false;
    }
});