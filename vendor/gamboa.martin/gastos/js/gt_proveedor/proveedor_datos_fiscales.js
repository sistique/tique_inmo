let telefono_mx_regex = new RegExp('^[1-9]{1}[0-9]{9}$');

let txt_telefono_1 = $("input[name=telefono_1]");
let txt_telefono_2 = $("input[name=telefono_2]");
let txt_telefono_3 = $("input[name=telefono_3]");


let telefono_1_error = $(".label-error-telefono-1");
let telefono_2_error = $(".label-error-telefono-2");
let telefono_3_error = $(".label-error-telefono-3");



let telefono_1_valido = false;
let telefono_2_valido = true;
let telefono_3_valido = true;


let btn_alta = $(".btn");

function valida_telefono_requerido(){
    let telefono = $(txt_telefono_1).val();
    telefono_1_valido = false;
    let regex_val = telefono_mx_regex.test(telefono);
    let n_car = telefono.length;

    if(n_car > 0 && regex_val){
        telefono_1_valido = true;
    }

    if(!telefono_1_valido){
        telefono_1_error.show();
    } else {
        telefono_1_error.hide();
    }
}

function valida_telefono_2(){
    let telefono = $(txt_telefono_2).val();
    telefono_2_valido = false;
    let regex_val = telefono_mx_regex.test(telefono);
    let n_car = telefono.length;

    if(n_car === 0 || regex_val){
        telefono_2_valido = true;
    }

    if(!telefono_2_valido){
        telefono_2_error.show();
    } else {
        telefono_2_error.hide();
    }
}

function valida_telefono_3(){
    let telefono = $(txt_telefono_3).val();
    telefono_3_valido = false;
    let regex_val = telefono_mx_regex.test(telefono);
    let n_car = telefono.length;

    if(n_car === 0 || regex_val){
        telefono_3_valido = true;
    }

    if(!telefono_3_valido){
        telefono_3_error.show();
    } else {
        telefono_3_error.hide();
    }
}

telefono_1_error.hide();
txt_telefono_1.keyup(function () {
    valida_telefono_requerido()
});

telefono_2_error.hide();
txt_telefono_2.keyup(function () {
    valida_telefono_2();

});

telefono_3_error.hide();
txt_telefono_3.keyup(function () {
    valida_telefono_3();
});

btn_alta.on('click', function(  ){
    valida_telefono_requerido();
    if(!telefono_1_valido){
        telefono_1_error.show();
        txt_telefono_1.focus();
        window.scrollTo(0, 500);
        return false;
    }
    valida_telefono_2();
    if(!telefono_2_valido){
        telefono_2_error.show();
        txt_telefono_2.focus();
        window.scrollTo(0, 500);
        return false;
    }
    valida_telefono_3();
    if(!telefono_3_valido){
        telefono_3_error.show();
        txt_telefono_3.focus();
        window.scrollTo(0, 500);
        return false;
    }
});