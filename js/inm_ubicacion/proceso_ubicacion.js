let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let pestana_general_actual = getParameterByName('pestana_general_actual');
let pestana_actual = getParameterByName('pestana_actual');

/***** Pestañas *****/
function cambiarPestannaGeneral(pestannas,pestanna,pentannascontenido) {
    pestanna_act = document.getElementById(pestanna.id);
    listaPestannas = document.getElementById(pestannas.id);

    cpestanna = document.getElementById('c'+pestanna.id);
    listacPestannas = document.getElementById('contenido'+pestannas.id);

    i=0;
    while (typeof listacPestannas.getElementsByClassName('contengeneral')[i] != 'undefined'){
        $(document).ready(function(){
            $(listacPestannas.getElementsByClassName('contengeneral')[i]).css('display','none');
            $(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
        });
        i += 1;
    }

    $(document).ready(function(){
        $(cpestanna).css('display','block');
        $(pestanna_act).css('background','#0f7ad5');

        const liActivo = pentannascontenido.querySelector('li[data-pestana="true"]');

        /*** URL PESTAÑA ACTUAL ***/
        const url = new URL(window.location.href);
        url.searchParams.set("pestana_general_actual", pestanna.id);

        window.history.pushState({}, '', url);

        if(liActivo !== null){
            url.searchParams.set("pestana_actual", liActivo.id);

            window.history.pushState({}, '', url);
        }else{
            if(pentannascontenido.id === 'pestanasubicacion'){
                pestana_actual = 'pestanaubicacion1';
                cambiarPestanna_inicialubicacion(pentannascontenido);
            }
        }
    });
}

function cambiarPestannaGeneral_inicial(pestannas) {
    let pestanna_ini = 'pestanageneral1';
    if(pestana_general_actual !== ''){
        pestanna_ini = pestana_general_actual;
    }

    pestanna_act = document.getElementById(pestanna_ini);
    listaPestannas = document.getElementById(pestannas.id);

    cpestanna = document.getElementById('c'+pestanna_ini);
    listacPestannas = document.getElementById('contenido'+pestannas.id);

    i=0;
    while (typeof listacPestannas.getElementsByClassName('contengeneral')[i] != 'undefined'){
        $(document).ready(function(){
            $(listacPestannas.getElementsByClassName('contengeneral')[i]).css('display','none');
            $(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
        });
        i += 1;
    }

    $(document).ready(function(){
        $(cpestanna).css('display','block');
        $(pestanna_act).css('background','#0f7ad5');

        /*** URL PESTAÑA ACTUAL ***/
        const url = new URL(window.location.href);
        url.searchParams.set("pestana_general_actual", pestanna_ini);

        window.history.pushState({}, '', url);
    });
}

function cambiarPestanna(pestannas,pestanna) {
    pestanna_act = document.getElementById(pestanna.id);
    listaPestannas = document.getElementById(pestannas.id);

    cpestanna = document.getElementById('c'+pestanna.id);
    listacPestannas = document.getElementById('contenido'+pestannas.id);

    i=0;
    while (typeof listacPestannas.getElementsByClassName('conten')[i] != 'undefined'){
        $(document).ready(function(){
            $(listacPestannas.getElementsByClassName('conten')[i]).css('display','none');
            $(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
            $(listaPestannas.getElementsByTagName('li')[i]).attr('data-pestana','');
        });
        i += 1;
    }

    $(document).ready(function(){
        $(cpestanna).css('display','block');
        $(pestanna_act).css('background','#0f7ad5');
        $(pestanna_act).attr('data-pestana', 'true');

        /*** URL PESTAÑA ACTUAL ***/
        const url = new URL(window.location.href);
        url.searchParams.set("pestana_actual", pestanna.id);

        window.history.pushState({}, '', url);
    });
}

function cambiarPestanna_inicialubicacion(pestannas) {
    let pestanna_ini = 'pestanaubicacion1';
    if(pestana_actual !== ''){
        pestanna_ini = pestana_actual;
    }

    pestanna_act = document.getElementById(pestanna_ini);
    listaPestannas = document.getElementById(pestannas.id);

    cpestanna = document.getElementById('c'+pestanna_ini);
    listacPestannas = document.getElementById('contenido'+pestannas.id);

    i=0;
    while (typeof listacPestannas.getElementsByClassName('conten')[i] != 'undefined'){
        $(document).ready(function(){
            $(listacPestannas.getElementsByClassName('conten')[i]).css('display','none');
            $(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
            $(listaPestannas.getElementsByTagName('li')[i]).attr('data-pestana','');
        });
        i += 1;
    }

    $(document).ready(function(){
        $(cpestanna).css('display','block');
        $(pestanna_act).css('background','#0f7ad5');
        $(pestanna_act).attr('data-pestana', 'true');

        /*** URL PESTAÑA ACTUAL ***/
        const url = new URL(window.location.href);
        url.searchParams.set("pestana_actual", pestanna_ini);

        window.history.pushState({}, '', url);
    });
}

function cambiarPestanna_inicial(pestannas,pestanna) {
    const str = pestanna.id;
    const valor_pestana = parseInt(str.replace("pestana", ""));

    pestanna_act = document.getElementById(pestanna.id);
    listaPestannas = document.getElementById(pestannas.id);

    cpestanna = document.getElementById('c'+pestanna.id);
    listacPestannas = document.getElementById('contenido'+pestannas.id);

    i=0;
    while (typeof listacPestannas.getElementsByClassName('conten')[i] != 'undefined'){
        $(document).ready(function(){
            $(listacPestannas.getElementsByClassName('conten')[i]).css('display','none');
            $(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
            $(listaPestannas.getElementsByTagName('li')[i]).attr('data-pestana','');
        });
        i += 1;
    }

    for (i = valor_pestana; i >= 0; i--){
        $(document).ready(function(){
            let a = $(listaPestannas.getElementsByTagName('li')[i]).find('a');  // obtiene el <a> dentro del <li>
            a.css('pointer-events', 'auto');
            $(listaPestannas.getElementsByTagName('li')[i]).css('cursor','auto');
        });
    }

    $(document).ready(function(){
        $(cpestanna).css('display','block');
        $(pestanna_act).css('background','#0f7ad5');
        $(pestanna_act).attr('data-pestana', 'true');
    });
}

function valor_inicial() {
    let Pestannas = document.getElementById("pestanas");
    $.ajax({
        type: "POST",
        data: {id: registro_id},
        url: 'index.php?seccion=inm_ubicacion&accion=get_etapa_actual&ws=1&session_id=' + session_id,
        success: function (data_r) {
            let result = {};
            result.id = data_r;

            cambiarPestanna_inicial(Pestannas, result);
        },
        error: function () {
            alert("No se ha podido obtener la información");
        }
    });
}

/***** Solicitud de Recurso *****/

let sl_inm_tipo_gasto_id = $("#inm_tipo_gasto_id");
let cont_cont_cheque = $("#cont_cheque");
let cont_cont_transfer = $("#cont_transfer");
let cont_cont_efectivo = $("#cont_efectivo");
sl_inm_tipo_gasto_id.change(function(){
    inm_tipo_gasto_id = $(this).val();

    if(inm_tipo_gasto_id === "1"){
        cont_cont_cheque.show();
        cont_cont_transfer.hide();
        cont_cont_efectivo.hide();
    }else if(inm_tipo_gasto_id === "2"){
        cont_cont_transfer.show();
        cont_cont_cheque.hide();
        cont_cont_efectivo.hide();
    }else if(inm_tipo_gasto_id === "3"){
        cont_cont_efectivo.show();
        cont_cont_cheque.hide();
        cont_cont_transfer.hide();
    }else{
        cont_cont_cheque.hide();
        cont_cont_transfer.hide();
        cont_cont_efectivo.hide();
    }
});

/***** Emision de Recurso *****/

let cont_cont_cheque_emi = $("#cont_cheque_emi");
let cont_cont_transfer_emi = $("#cont_transfer_emi");
let cont_cont_efectivo_emi = $("#cont_efectivo_emi");
let txt_nombre_beneficiario = $("#nombre_beneficiario_emision");
let sl_inm_tipo_cheque_id = $("#inm_tipo_cheque_sl_id");
let sl_bn_cuenta_id = $("#bn_cuenta_sl_id");
let txt_numero_cheque = $("#numero_cheque");
let txt_monto_emision = $("#monto_emision");
let txt_transferencia = $("#transferencia");
let txt_monto_transferencia = $("#monto_transferencia_emision");
let txt_efectivo = $("#efectivo_emision");
let txt_inm_tipo_gasto_sl_id = $("input[name='inm_tipo_gasto_sl_id']");
let txt_registro_ajustar_id = $("input[name='registro_ajustar_id']");

$(".checkbox_reg").on("change", function() {
    // Desmarca todos los demás checkboxes
    $(".checkbox_reg").not(this).prop("checked", false);
    let movimiento = '';
    // Si está marcado, obtén su valor
    if ($(this).is(":checked")) {
        let valorSeleccionado = $(this).val();
        movimiento = $(this).data("movimiento");

        if(movimiento === 'cheque'){
            cont_cont_cheque_emi.show();
            cont_cont_transfer_emi.hide();
            cont_cont_efectivo_emi.hide();

            txt_nombre_beneficiario.val($(this).data("nombre_beneficiario"));

            sl_inm_tipo_cheque_id.val(String($(this).data("inm_tipo_cheque_id")));
            sl_inm_tipo_cheque_id.selectpicker('refresh');

            sl_bn_cuenta_id.val(String($(this).data("bn_cuenta_id")));
            sl_bn_cuenta_id.selectpicker('refresh');

            txt_numero_cheque.val($(this).data("numero_cheque"));
            txt_monto_emision.val($(this).data("monto"));

            txt_inm_tipo_gasto_sl_id.val($(this).data("inm_tipo_gasto_id"));
            txt_registro_ajustar_id.val(valorSeleccionado);
        }else if(movimiento === 'transferencia'){
            cont_cont_cheque_emi.hide();
            cont_cont_transfer_emi.show();
            cont_cont_efectivo_emi.hide();

            txt_nombre_beneficiario.val($(this).data("nombre_beneficiario"));

            txt_transferencia.val($(this).data("transferencia"));
            txt_monto_transferencia.val($(this).data("monto_transferencia"));

            txt_inm_tipo_gasto_sl_id.val($(this).data("inm_tipo_gasto_id"));
            txt_registro_ajustar_id.val(valorSeleccionado);
        }else if(movimiento === 'efectivo'){
            cont_cont_cheque_emi.hide();
            cont_cont_transfer_emi.hide();
            cont_cont_efectivo_emi.show();

            txt_nombre_beneficiario.val($(this).data("nombre_beneficiario"));
            txt_efectivo.val($(this).data("monto"));

            txt_inm_tipo_gasto_sl_id.val($(this).data("inm_tipo_gasto_id"));
            txt_registro_ajustar_id.val(valorSeleccionado);
        }else{
            cont_cont_cheque_emi.hide();
            cont_cont_transfer_emi.hide();
            cont_cont_efectivo_emi.hide();
            txt_inm_tipo_gasto_sl_id.val("");
            txt_registro_ajustar_id.val("");
        }
        console.log("Cheque seleccionado: " + valorSeleccionado + " " + movimiento);
    } else {
        cont_cont_cheque_emi.hide();
        cont_cont_transfer_emi.hide();
        cont_cont_efectivo_emi.hide();
        txt_inm_tipo_gasto_sl_id.val("");
        txt_registro_ajustar_id.val("");
        console.log("Ningún cheque seleccionado");
    }
});

/***** Modifica ******/

let sl_dp_pais_id = $("#dp_pais_id");
let sl_dp_estado_id = $("#dp_estado_id");
let sl_dp_municipio_id = $("#dp_municipio_id");
let sl_dp_cp_id = $("#dp_cp_id");
let sl_dp_colonia_postal_id = $("#dp_colonia_postal_id");

let dp_pais_id = -1;
let dp_estado_id = -1;
let dp_municipio_id = -1;
let dp_cp_id = -1;
let dp_colonia_postal_id = -1;
let sl_conyuge_dp_estado_id = $("#conyuge_dp_estado_id");

let numero_exterior = $("#numero_exterior");
let numero_interior = $("#numero_interior");
let manzana = $("#manzana");
let lote = $("#lote");
let cuenta_predial = $("#cuenta_predial");


let nombre_ct = $("#nombre");
let apellido_paterno_ct = $("#apellido_paterno");
let apellido_materno_ct = $("#apellido_materno");
let razon_social_ct = $("#razon_social");

let nombre = '' || nombre_ct.val();
let apellido_paterno = '' || apellido_paterno_ct.val();
let apellido_materno = '' || apellido_materno_ct.val();
let razon_social = '' || nombre_ct.val()+' '+apellido_paterno_ct.val()+' '+apellido_materno_ct.val();

nombre_ct.change(function() {
    limpia_txt($(this));
    nombre = $(this).val().trim();
    razon_social = nombre+' '+apellido_paterno+' '+apellido_materno;
    razon_social_ct.val(razon_social.trim());

});
apellido_paterno_ct.change(function() {
    limpia_txt($(this));
    apellido_paterno = $(this).val().trim();
    razon_social = nombre+' '+apellido_paterno+' '+apellido_materno;
    razon_social_ct.val(razon_social.trim());
});
apellido_materno_ct.change(function() {
    limpia_txt($(this));
    apellido_materno = $(this).val().trim();
    razon_social = nombre+' '+apellido_paterno+' '+apellido_materno;
    razon_social_ct.val(razon_social.trim());
});

numero_exterior.change(function(){
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value)
});

numero_interior.change(function(){
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value)
});

manzana.change(function(){
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value)
});
lote.change(function(){
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value)
});

cuenta_predial.change(function(){
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value)
});


sl_dp_pais_id.change(function(){
    dp_pais_id = $(this).val();
    dp_asigna_estados(dp_pais_id);
});

sl_dp_estado_id.change(function(){
    dp_estado_id = $(this).val();
    dp_asigna_municipios(dp_estado_id);
});

sl_dp_municipio_id.change(function(){
    dp_municipio_id = sl_dp_municipio_id.val();
    dp_asigna_cps(dp_municipio_id);
});

sl_dp_cp_id.change(function(){
    dp_cp_id = sl_dp_cp_id.val();
    dp_asigna_colonias_postales(dp_cp_id);
});

sl_dp_colonia_postal_id.change(function(){
    dp_colonia_postal_id = sl_dp_colonia_postal_id.val();
    dp_asigna_calles_pertenece(dp_colonia_postal_id);
});

sl_conyuge_dp_estado_id.change(function () {
    conyuge_dp_estado_id = $(this).val();
    dp_asigna_municipios_conyuge(conyuge_dp_estado_id, '', '#conyuge_dp_municipio_id');
});

function limpia_txt(container){
    let value = container.val().trim();
    value = value.toUpperCase();
    value = value.replace('  ',' ');
    value = value.replace('  ',' ');
    value = value.replace('  ',' ');
    value = value.replace('  ',' ');
    container.val(value);
}

function dp_asigna_calles_pertenece(dp_colonia_postal_id = '',dp_calle_pertenece_id = ''){

    let sl_dp_calle_pertenece_id = $("#dp_calle_pertenece_id");

    let url = "index.php?seccion=dp_calle_pertenece&ws=1&accion=get_calle_pertenece&dp_colonia_postal_id="+dp_colonia_postal_id+"&session_id="+session_id;
    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        sl_dp_calle_pertenece_id.empty();
        integra_new_option("#dp_calle_pertenece_id",'Seleccione una calle','-1');
        $.each(data.registros, function( index, dp_calle_pertenece ) {
            integra_new_option("#dp_calle_pertenece_id",dp_calle_pertenece.dp_calle_descripcion,dp_calle_pertenece.dp_calle_pertenece_id);
        });
        sl_dp_calle_pertenece_id.val(dp_calle_pertenece_id);
        sl_dp_calle_pertenece_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
}
function dp_asigna_colonias_postales(dp_cp_id = '',dp_colonia_postal_id = ''){

    let sl_dp_colonia_postal_id = $("#dp_colonia_postal_id");

    let url = "index.php?seccion=dp_colonia_postal&ws=1&accion=get_colonia_postal&dp_cp_id="+dp_cp_id+"&session_id="+session_id;
    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        sl_dp_colonia_postal_id.empty();
        integra_new_option("#dp_colonia_postal_id",'Seleccione una colonia','-1');
        $.each(data.registros, function( index, dp_colonia_postal ) {
            integra_new_option("#dp_colonia_postal_id",dp_colonia_postal.dp_colonia_descripcion,dp_colonia_postal.dp_colonia_postal_id);
        });
        sl_dp_colonia_postal_id.val(dp_colonia_postal_id);
        sl_dp_colonia_postal_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
}
function dp_asigna_cps(dp_municipio_id = '',dp_cp_id = ''){

    let sl_dp_cp_id = $("#dp_cp_id");

    let url = "index.php?seccion=dp_cp&ws=1&accion=get_cp&dp_municipio_id="+dp_municipio_id+"&session_id="+session_id;
    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        sl_dp_cp_id.empty();
        integra_new_option("#dp_cp_id",'Seleccione un cp','-1');
        $.each(data.registros, function( index, dp_cp ) {
            integra_new_option("#dp_cp_id",dp_cp.dp_cp_descripcion,dp_cp.dp_cp_id);
        });
        sl_dp_cp_id.val(dp_cp_id);
        sl_dp_cp_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
}

function dp_asigna_estados(dp_pais_id = '',dp_estado_id = ''){

    let sl_dp_estado_id = $("#dp_estado_id");

    let url = "index.php?seccion=dp_estado&ws=1&accion=get_estado&dp_pais_id="+dp_pais_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        sl_dp_estado_id.empty();
        integra_new_option("#dp_estado_id",'Seleccione un estado','-1');

        $.each(data.registros, function( index, dp_estado ) {
            integra_new_option("#dp_estado_id",dp_estado.dp_estado_descripcion,dp_estado.dp_estado_id);
        });
        sl_dp_estado_id.val(dp_estado_id);
        sl_dp_estado_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log("The following error occured: "+ textStatus +" "+ errorThrown);
    });

}

function dp_asigna_municipios(dp_estado_id = '',dp_municipio_id = ''){

    let sl_dp_municipio_id = $("#dp_municipio_id");

    let url = "index.php?seccion=dp_municipio&ws=1&accion=get_municipio&dp_estado_id="+dp_estado_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        sl_dp_municipio_id.empty();

        integra_new_option("#dp_municipio_id",'Seleccione un municipio','-1');

        $.each(data.registros, function( index, dp_municipio ) {
            integra_new_option("#dp_municipio_id",dp_municipio.dp_municipio_descripcion,dp_municipio.dp_municipio_id);
        });
        sl_dp_municipio_id.val(dp_municipio_id);
        sl_dp_municipio_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log("The following error occured: "+ textStatus +" "+ errorThrown);
    });

}

function dp_asigna_municipios_conyuge(dp_estado_id = '', dp_municipio_id = '', selector = "#dp_municipio_id") {

    let sl_dp_municipio_id = $(selector);

    let url = "index.php?seccion=dp_municipio&ws=1&accion=get_municipio&dp_estado_id=" + dp_estado_id + "&session_id=" + session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function (data) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        sl_dp_municipio_id.empty();

        integra_new_option(selector, 'Seleccione un municipio', '-1');

        $.each(data.registros, function (index, dp_municipio) {
            integra_new_option(selector, dp_municipio.dp_municipio_descripcion, dp_municipio.dp_municipio_id);
        });
        sl_dp_municipio_id.val(dp_municipio_id);
        sl_dp_municipio_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown) { // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log("The following error occured: " + textStatus + " " + errorThrown);
    });

}

let apartado_1 = $("#apartado_1");
let apartado_2 = $("#apartado_2");
let apartado_3 = $("#apartado_3");
let apartado_4 = $("#apartado_4");
let apartado_5 = $("#apartado_5");
let apartado_6 = $("#apartado_6");

let collapse_a1 = $("#collapse_a1");
let collapse_a2 = $("#collapse_a2");
let collapse_a3 = $("#collapse_a3");
let collapse_a4 = $("#collapse_a4");
let collapse_a5 = $("#collapse_a5");
let collapse_a6 = $("#collapse_a6");

apartado_1.show();
apartado_2.show();
apartado_3.show();
apartado_4.show();
apartado_5.show();
apartado_6.hide();

collapse_a1.click(function() {
    apartado_1.toggle();

});
collapse_a2.click(function() {
    apartado_2.toggle();

});
collapse_a3.click(function() {
    apartado_3.toggle();

});
collapse_a4.click(function() {
    apartado_4.toggle();

});
collapse_a5.click(function() {
    apartado_5.toggle();

});

let todo_aculto = true;

$("#collapse_all").click(function() {
    if(todo_aculto){
        apartado_1.hide();
        apartado_2.hide();
        apartado_3.hide();
        apartado_4.hide();
        apartado_5.hide();
        todo_aculto = false;
    }
    else{
        apartado_1.show();
        apartado_2.show();
        apartado_3.show();
        apartado_4.show();
        apartado_5.show();
        todo_aculto = true;
    }
});

let sl_inm_tipo_credito_id = $("#inm_tipo_credito_id");

function inicializa_conyuge(){
    tipo_credito_id = sl_inm_tipo_credito_id.val();

    $.ajax({
        type: "POST",
        data: {'id':tipo_credito_id},
        url: 'index.php?seccion=inm_tipo_credito&accion=get_tipo_credito&ws=1&session_id='+session_id,
        success: function(data_r) {
            if(data_r.inm_tipo_credito_muestra_conyuge === "activo"){
                apartado_6.toggle();
                collapse_a6.off("click").click(function () {
                    apartado_6.toggle();
                });
            }else{
                apartado_6.hide();

                collapse_a6.off("click");
            }
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });
}

sl_inm_tipo_credito_id.change(function () {
    tipo_credito_id = $(this).val();

    $.ajax({
        type: "POST",
        data: {'id':tipo_credito_id},
        url: 'index.php?seccion=inm_tipo_credito&accion=get_tipo_credito&ws=1&session_id='+session_id,
        success: function(data_r) {
            if(data_r.inm_tipo_credito_muestra_conyuge === "activo"){
                apartado_6.toggle();

                collapse_a6.off("click").click(function () {
                    apartado_6.toggle();
                });
            }else{
                apartado_6.hide();

                collapse_a6.off("click");
            }
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });
});

/***** Modal Documentos *****/

var modal = document.getElementById("myModal");
var closeBtn = document.getElementById("closeModalBtn");
let inm_doc_ubicacion_id = '';
$(document).on("click", "a[title='Vista Previa']", function (event) {
    event.preventDefault();
    var url = $(this).attr("href");

    var loaderOverlay = $('<div class="loader-overlay"><div class="loader"></div></div>');
    $('body').append(loaderOverlay);

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            var tempDiv = $("<div>").html(data);
            var inputdoc = tempDiv.find('[name="inm_doc_ubicacion_id"]');
            var viewContent = tempDiv.find(".view");
            inm_doc_ubicacion_id = inputdoc.val();

            /*$("#myModal .content").html(inputdoc);
            $("#myModal .content").html(viewContent);*/
            $("#myModal .content").html('');
            $("#myModal .content").append(inputdoc);
            $("#myModal .content").append(viewContent);
            modal.showModal();
            loaderOverlay.remove();
        },
        error: function () {
            $("#myModal .content").html("<p>Error al cargar el contenido.</p>");
            modal.showModal();
            loaderOverlay.remove();
        }
    });
});

closeBtn.onclick = function () {
    $("#myModal .content").empty();
    modal.close();

    $.ajax({
        type: "POST",
        data: {id:inm_doc_ubicacion_id},
        url: 'index.php?seccion=inm_doc_ubicacion&accion=elimina_temporal&ws=1&session_id='+session_id,
        success: function(data_r) {
            console.log(data_r);
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });
}

modal.addEventListener('click', function (event) {
    if (event.target === modal) {
        $("#myModal .content").empty();
        modal.close();

        $.ajax({
            type: "POST",
            data: {id:inm_doc_ubicacion_id},
            url: 'index.php?seccion=inm_doc_ubicacion&accion=elimina_temporal&ws=1&session_id='+session_id,
            success: function(data_r) {
                console.log(data_r);
            },
            error: function() {
                alert("No se ha podido obtener la información");
            }
        });
    }
});

/***** Documentos *****/

const columns_tipos_documentos = [
    {
        title: "Tipo documento",
        data: "doc_tipo_documento_descripcion"
    },
    {
        title: "Descarga",
        data: "descarga"
    },
    {
        title: "Vista previa",
        data: "vista_previa"
    },
    {
        title: "ZIP",
        data: "descarga_zip"
    },
    {
        title: "Elimina",
        data: "elimina_bd"
    }
];

const options = {paging: false, info: false, searching: false}

const table_tipos_documentos = table('inm_ubicacion', columns_tipos_documentos, [], [], function () {
    }, true,
    "tipos_documentos", {registro_id: registro_id,pestana_general_actual: pestana_general_actual,
        pestana_actual:pestana_actual}, options);

/***** Fotografias*****/

$(".elimina_img").on("click", function() {
    let inm_doc_ubicacion_id = $(this).data('inm_doc_ubicacion_id');

    $.ajax({
        type: "POST",
        data: {id:inm_doc_ubicacion_id},
        url: 'index.php?seccion=inm_doc_ubicacion&accion=elimina_temporal&ws=1&session_id='+session_id,
        success: function(data_r) {
            $.ajax({
                type: "POST",
                data: {id:inm_doc_ubicacion_id},
                url: 'index.php?seccion=inm_doc_ubicacion&accion=elimina_bd&ws=1&registro_id='+inm_doc_ubicacion_id+'&session_id='+session_id,
                success: function(data_r) {
                    console.log(data_r);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert("No se ha podido obtener la información");

                    console.error("❌ Error en AJAX");
                    console.error("Estado: " + textStatus); // timeout, error, abort, etc.
                    console.error("Código HTTP: " + jqXHR.status); // 404, 500, etc.
                    console.error("Texto del error: " + errorThrown); // Internal Server Error, Not Found, etc.
                    console.error("Respuesta del servidor: " + jqXHR.responseText); // HTML o JSON de respuesta de error

                    alert("Ocurrió un error: " + errorThrown);
                }
            });
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });

    $(this).closest(".contenedor_img").remove();

});

