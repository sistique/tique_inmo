function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

let pagina_web_regex = new RegExp('http(s)?:\\/\\/(([a-z])+.)+([a-z])+');
let telefono_mx_regex = new RegExp('^[1-9]{1}[0-9]{9}$');

let txt_pagina_web = $("input[name=pagina_web]");
let txt_telefono_1 = $("input[name=telefono_1]");
let txt_telefono_2 = $("input[name=telefono_2]");
let txt_telefono_3 = $("input[name=telefono_3]");


let pagina_web_error = $(".label-error-url");
let telefono_1_error = $(".label-error-telefono-1");
let telefono_2_error = $(".label-error-telefono-2");
let telefono_3_error = $(".label-error-telefono-3");



let pagina_web_valido = false;
let telefono_1_valido = false;
let telefono_2_valido = true;
let telefono_3_valido = true;


let btn_alta = $(".btn");

pagina_web_error.hide();
txt_pagina_web.change(function () {
    let url = $(this).val();
    pagina_web_valido = false;
    let regex_val = pagina_web_regex.test(url);
    let n_car = url.length;

    if(n_car > 0 && regex_val){
        pagina_web_valido = true;
    }

    if(!pagina_web_valido){
        pagina_web_error.show();
    } else {
        pagina_web_error.hide();
    }
});

telefono_1_error.hide();
txt_telefono_1.keyup(function () {

    let telefono = $(this).val();
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
});

txt_telefono_1.on('keydown',function (e) {
    tecla = e.key;
    var valoresAceptados = /^[0-9]+$/;
    if(!tecla.match(valoresAceptados)){
        if(e.keyCode != 8){
            if(e.keyCode != 13){
                return false;
            }
        }
    }
});

telefono_2_error.hide();
txt_telefono_2.keyup(function () {
    let telefono = $(this).val();
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
});
txt_telefono_2.on('keydown',function (e) {
    tecla = e.key;
    var valoresAceptados = /^[0-9]+$/;
    if(!tecla.match(valoresAceptados)){
        if(e.keyCode != 8){
            if(e.keyCode != 13){
                return false;
            }
        }
    }
});

telefono_3_error.hide();
txt_telefono_3.keyup(function () {
    let telefono = $(this).val();
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
});
txt_telefono_3.on('keydown',function (e) {
    tecla = e.key;
    var valoresAceptados = /^[0-9]+$/;
    if(!tecla.match(valoresAceptados)){
        if(e.keyCode != 8){
            if(e.keyCode != 13){
                return false;
            }
        }
    }
});

btn_alta.on('click', function(  ){
    if(!pagina_web_valido){
        pagina_web_error.show();
        txt_pagina_web.focus();
        window.scrollTo(txt_pagina_web.positionX, txt_pagina_web.positionY);
        return false;
    }
    if(!telefono_1_valido){
        telefono_1_error.show();
        txt_telefono_1.focus();
        window.scrollTo(0, 500);
        return false;
    }
    if(!telefono_2_valido){
        telefono_2_error.show();
        txt_telefono_2.focus();
        window.scrollTo(0, 500);
        return false;
    }
    if(!telefono_3_valido){
        telefono_3_error.show();
        txt_telefono_3.focus();
        window.scrollTo(0, 500);
        return false;
    }
});


