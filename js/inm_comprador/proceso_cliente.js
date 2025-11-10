let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let pestana_general_actual = getParameterByName('pestana_general_actual');
let pestana_actual = getParameterByName('pestana_actual');
function cambiarPestannaGeneralCl(pestannas,pestanna,pentannascontenido) {
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
            if(pentannascontenido.id === 'pestanascliente'){
                pestana_actual = 'pestanacliente1';
                cambiarPestanna_inicialcliente(pentannascontenido);
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

function cambiarPestanna_inicialcliente(pestannas) {
    let pestanna_ini = 'pestanacliente1';
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
        url: 'index.php?seccion=inm_comprador&accion=get_etapa_actual&ws=1&session_id=' + session_id,
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

/***** Modifica *****/

let sl_dp_pais_id = $("#dp_pais_id");
let sl_dp_estado_id = $("#dp_estado_id");
let sl_conyuge_dp_estado_id = $("#conyuge_dp_estado_id");
let sl_conyuge_dp_municipio_id = $("#conyuge_dp_municipio_id");
let sl_dp_municipio_id = $("#dp_municipio_id");
let sl_dp_cp_id = $("#dp_cp_id");
let sl_dp_colonia_postal_id = $("#dp_colonia_postal_id");
let sl_inm_plazo_credito_sc_id = $("#inm_plazo_credito_sc_id");
let sl_inm_tipo_discapacidad_id = $("#inm_tipo_discapacidad_id");
let sl_inm_persona_discapacidad_id = $("#inm_persona_discapacidad_id");

let in_descuento_pension_alimenticia_dh = $("#descuento_pension_alimenticia_dh");
let in_descuento_pension_alimenticia_fc = $("#descuento_pension_alimenticia_fc");
let in_monto_credito_solicitado_dh = $("#monto_credito_solicitado_dh");
let in_monto_ahorro_voluntario = $("#monto_ahorro_voluntario");

let dp_pais_id = -1;
let dp_estado_id = -1;
let dp_municipio_id = -1;
let dp_cp_id = -1;
let dp_colonia_postal_id = -1;

let chk_es_segundo_credito = $(".es_segundo_credito");
let chk_con_discapacidad = $(".con_discapacidad");

let nombre_empresa_patron_ct = $("#nombre_empresa_patron");
let nrp_nep_ct = $("#nrp_nep");
let curp_ct = $("#curp");
let rfc_ct = $("#rfc");
let apellido_paterno_ct = $("#apellido_paterno");
let apellido_materno_ct = $("#apellido_materno");
let nombre_ct = $("#nombre");

let numero_exterior_ct = $("#numero_exterior");
let numero_interior_ct = $("#numero_interior");
let lada_com_ct = $("#lada_com");
let lada_nep_ct = $("#lada_nep");
let numero_nep_ct = $("#numero_nep");
let extension_nep_ct = $("#extension_nep");
let nss_ct = $("#nss");
let numero_com_ct = $("#numero_com");
let cel_com_ct = $("#cel_com");
let correo_com_ct = $("#correo_com");
let conyuge_nombre_ct = $(".conyuge_nombre");
let conyuge_apellido_paterno_ct = $(".conyuge_apellido_paterno");
let conyuge_apellido_materno_ct = $(".conyuge_apellido_materno");
let conyuge_curp_ct = $(".conyuge_curp");
let conyuge_rfc_ct = $(".conyuge_rfc");
let conyuge_telefono_casa_ct = $(".conyuge_telefono_casa");
let conyuge_telefono_celular_ct = $(".conyuge_telefono_celular");

let inm_co_acreditado_nss = $("#inm_co_acreditado_nss");
let inm_co_acreditado_curp = $("#inm_co_acreditado_curp");
let inm_co_acreditado_rfc = $("#inm_co_acreditado_rfc");
let inm_co_acreditado_apellido_paterno = $("#inm_co_acreditado_apellido_paterno");
let inm_co_acreditado_apellido_materno = $("#inm_co_acreditado_apellido_materno");
let inm_co_acreditado_nombre = $("#inm_co_acreditado_nombre");
let inm_co_acreditado_lada = $("#inm_co_acreditado_lada");
let inm_co_acreditado_numero = $("#inm_co_acreditado_numero");
let inm_co_acreditado_celular = $("#inm_co_acreditado_celular");
let inm_co_acreditado_correo = $("#inm_co_acreditado_correo");
let inm_co_acreditado_nombre_empresa_patron = $("#inm_co_acreditado_nombre_empresa_patron");
let inm_co_acreditado_nrp = $("#inm_co_acreditado_nrp");
let inm_co_acreditado_lada_nep = $("#inm_co_acreditado_lada_nep");
let inm_co_acreditado_numero_nep = $("#inm_co_acreditado_numero_nep");

let inm_referencia_apellido_paterno_1 = $("#inm_referencia_apellido_paterno_1");
let inm_referencia_apellido_materno_1 = $("#inm_referencia_apellido_materno_1");
let inm_referencia_nombre_1 = $("#inm_referencia_nombre_1");
let inm_referencia_lada_1 = $("#inm_referencia_lada_1");
let inm_referencia_numero_1 = $("#inm_referencia_numero_1");
let inm_referencia_celular_1 = $("#inm_referencia_celular_1");
let inm_referencia_numero_dom_1 = $("#inm_referencia_numero_dom_1");


let inm_referencia_apellido_paterno_2 = $("#inm_referencia_apellido_paterno_2");
let inm_referencia_apellido_materno_2 = $("#inm_referencia_apellido_materno_2");
let inm_referencia_nombre_2 = $("#inm_referencia_nombre_2");
let inm_referencia_lada_2 = $("#inm_referencia_lada_2");
let inm_referencia_numero_2 = $("#inm_referencia_numero_2");
let inm_referencia_celular_2 = $("#inm_referencia_celular_2");
let inm_referencia_numero_dom_2 = $("#inm_referencia_numero_dom_2");


let edit_ref_1 = $("#edit_ref_1");
let edit_ref_2 = $("#edit_ref_2");

let sl_referencia_dp_estado_id = $("#referencia_dp_estado_id");
let sl_referencia_dp_municipio_id = $("#referencia_dp_municipio_id");
let sl_referencia_dp_cp_id = $("#referencia_dp_cp_id");
let sl_referencia_dp_colonia_postal_id = $("#referencia_dp_colonia_postal_id");
let sl_referencia_dp_calle_pertenece_id = $("#referencia_dp_calle_pertenece_id");


sl_referencia_dp_estado_id.change(function(){
    let referencia_dp_estado_id = $(this).val();
    dp_asigna_municipios(referencia_dp_estado_id,'','#referencia_dp_municipio_id');
});

sl_referencia_dp_municipio_id.change(function(){
    let referencia_dp_municipio_id = $(this).val();
    dp_asigna_cps(referencia_dp_municipio_id,'','#referencia_dp_cp_id');
});

sl_referencia_dp_cp_id.change(function(){
    let referencia_dp_cp_id = $(this).val();
    dp_asigna_colonias_postales(referencia_dp_cp_id,'','#referencia_dp_colonia_postal_id');
});

sl_referencia_dp_colonia_postal_id.change(function(){
    let referencia_dp_colonia_postal_id = $(this).val();
    dp_asigna_calles_pertenece(referencia_dp_colonia_postal_id,'','#referencia_dp_calle_pertenece_id');
});

function habilita_ref_1(){
    inm_referencia_apellido_paterno_1.prop('disabled',false);
    inm_referencia_apellido_materno_1.prop('disabled',false);
    inm_referencia_nombre_1.prop('disabled',false);
    inm_referencia_lada_1.prop('disabled',false);
    inm_referencia_numero_1.prop('disabled',false);
    inm_referencia_celular_1.prop('disabled',false);
    inm_referencia_numero_dom_1.prop('disabled',false);
    $("#inm_referencia_dp_pais_id_1").prop('disabled',false);
    $("#inm_referencia_dp_pais_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_estado_id_1").prop('disabled',false);
    $("#inm_referencia_dp_estado_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_municipio_id_1").prop('disabled',false);
    $("#inm_referencia_dp_municipio_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_cp_id_1").prop('disabled',false);
    $("#inm_referencia_dp_cp_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_colonia_postal_id_1").prop('disabled',false);
    $("#inm_referencia_dp_colonia_postal_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_calle_pertenece_id_1").prop('disabled',false);
    $("#inm_referencia_dp_calle_pertenece_id_1").selectpicker('refresh');

    edit_ref_1.removeClass('btn-success');
    edit_ref_1.addClass('btn-warning');

    edit_ref_1.empty();
    edit_ref_1.html('Cancela');
}

function habilita_ref_2(){
    inm_referencia_apellido_paterno_2.prop('disabled',false);
    inm_referencia_apellido_materno_2.prop('disabled',false);
    inm_referencia_nombre_2.prop('disabled',false);
    inm_referencia_lada_2.prop('disabled',false);
    inm_referencia_numero_2.prop('disabled',false);
    inm_referencia_celular_2.prop('disabled',false);
    inm_referencia_numero_dom_2.prop('disabled',false);
    $("#inm_referencia_dp_pais_id_2").prop('disabled',false);
    $("#inm_referencia_dp_pais_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_estado_id_2").prop('disabled',false);
    $("#inm_referencia_dp_estado_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_municipio_id_2").prop('disabled',false);
    $("#inm_referencia_dp_municipio_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_cp_id_2").prop('disabled',false);
    $("#inm_referencia_dp_cp_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_colonia_postal_id_2").prop('disabled',false);
    $("#inm_referencia_dp_colonia_postal_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_calle_pertenece_id_2").prop('disabled',false);
    $("#inm_referencia_dp_calle_pertenece_id_2").selectpicker('refresh');

    edit_ref_2.removeClass('btn-success');
    edit_ref_2.addClass('btn-warning');

    edit_ref_2.empty();
    edit_ref_2.html('Cancela');
}

function deshabilita_ref_1(){
    inm_referencia_apellido_paterno_1.prop('disabled',true);
    inm_referencia_apellido_materno_1.prop('disabled',true);
    inm_referencia_nombre_1.prop('disabled',true);
    inm_referencia_lada_1.prop('disabled',true);
    inm_referencia_numero_1.prop('disabled',true);
    inm_referencia_celular_1.prop('disabled',true);
    inm_referencia_numero_dom_1.prop('disabled',true);
    $("#inm_referencia_dp_pais_id_1").prop('disabled',true);
    $("#inm_referencia_dp_pais_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_estado_id_1").prop('disabled',true);
    $("#inm_referencia_dp_estado_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_municipio_id_1").prop('disabled',true);
    $("#inm_referencia_dp_municipio_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_cp_id_1").prop('disabled',true);
    $("#inm_referencia_dp_cp_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_colonia_postal_id_1").prop('disabled',true);
    $("#inm_referencia_dp_colonia_postal_id_1").selectpicker('refresh');

    $("#inm_referencia_dp_calle_pertenece_id_1").prop('disabled',true);
    $("#inm_referencia_dp_calle_pertenece_id_1").selectpicker('refresh');

    edit_ref_1.removeClass('btn-warning');
    edit_ref_1.addClass('btn-success');
    edit_ref_1.empty();
    edit_ref_1.html('Edita');
}

function deshabilita_ref_2(){
    inm_referencia_apellido_paterno_2.prop('disabled',true);
    inm_referencia_apellido_materno_2.prop('disabled',true);
    inm_referencia_nombre_2.prop('disabled',true);
    inm_referencia_lada_2.prop('disabled',true);
    inm_referencia_numero_2.prop('disabled',true);
    inm_referencia_celular_2.prop('disabled',true);
    inm_referencia_numero_dom_2.prop('disabled',true);
    $("#inm_referencia_dp_pais_id_2").prop('disabled',true);
    $("#inm_referencia_dp_pais_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_estado_id_2").prop('disabled',true);
    $("#inm_referencia_dp_estado_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_municipio_id_2").prop('disabled',true);
    $("#inm_referencia_dp_municipio_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_cp_id_2").prop('disabled',true);
    $("#inm_referencia_dp_cp_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_colonia_postal_id_2").prop('disabled',true);
    $("#inm_referencia_dp_colonia_postal_id_2").selectpicker('refresh');

    $("#inm_referencia_dp_calle_pertenece_id_2").prop('disabled',true);
    $("#inm_referencia_dp_calle_pertenece_id_2").selectpicker('refresh');

    edit_ref_2.removeClass('btn-warning');
    edit_ref_2.addClass('btn-success');
    edit_ref_2.empty();
    edit_ref_2.html('Edita');
}

let ref_1_habilitado = false

edit_ref_1.click(function() {
    if(!ref_1_habilitado) {
        habilita_ref_1();
        ref_1_habilitado = true;
    }
    else{
        deshabilita_ref_1();
        ref_1_habilitado = false;
    }

});

let ref_2_habilitado = false

edit_ref_2.click(function() {
    if(!ref_2_habilitado) {
        habilita_ref_2();
        ref_2_habilitado = true;
    }
    else{
        deshabilita_ref_2();
        ref_2_habilitado = false;
    }

});

conyuge_nombre_ct.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});
conyuge_apellido_paterno_ct.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});
conyuge_apellido_materno_ct.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});

conyuge_curp_ct.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});

conyuge_rfc_ct.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});

conyuge_telefono_casa_ct.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});

conyuge_telefono_celular_ct.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});
inm_referencia_apellido_paterno_1.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});
inm_referencia_apellido_materno_1.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});
inm_referencia_nombre_1.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});
inm_referencia_lada_1.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});
inm_referencia_numero_1.change(function() {
    let value = $(this).val().trim().toUpperCase();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

inm_referencia_celular_1.change(function() {
    let value = $(this).val().trim().toUpperCase();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

inm_referencia_numero_dom_1.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});



inm_referencia_apellido_paterno_2.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});
inm_referencia_apellido_materno_2.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});
inm_referencia_nombre_2.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});
inm_referencia_lada_2.change(function() {
    let value = $(this).val().trim().toUpperCase();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});
inm_referencia_numero_2.change(function() {
    let value = $(this).val().trim().toUpperCase();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

inm_referencia_celular_2.change(function() {
    let value = $(this).val().trim().toUpperCase();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

inm_referencia_numero_dom_2.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);

});

let apartado_1 = $("#apartado_1");
let apartado_2 = $("#apartado_2");
let apartado_3 = $("#apartado_3");
let apartado_4 = $("#apartado_4");
let apartado_5 = $("#apartado_5");
let apartado_6 = $("#apartado_6");
let apartado_7 = $("#apartado_7");
let apartado_8 = $("#apartado_8");
let apartado_9 = $("#apartado_9");
let apartado_10 = $("#apartado_10");
let apartado_11 = $("#apartado_11");
let apartado_12 = $("#apartado_12");

let collapse_a1 = $("#collapse_a1");
let collapse_a2 = $("#collapse_a2");
let collapse_a3 = $("#collapse_a3");
let collapse_a4 = $("#collapse_a4");
let collapse_a5 = $("#collapse_a5");
let collapse_a6 = $("#collapse_a6");
let collapse_a7 = $("#collapse_a7");
let collapse_a8 = $("#collapse_a8");
let collapse_a9 = $("#collapse_a9");
let collapse_a10 = $("#collapse_a10");
let collapse_a11 = $("#collapse_a11");
let collapse_a12 = $("#collapse_a12");
let btn_modifica = $("#btn_modifica");

apartado_1.show();
apartado_2.show();
apartado_3.show();
apartado_4.show();
apartado_5.show();
apartado_6.hide();
apartado_7.hide();
apartado_8.show();
apartado_9.show();
apartado_10.hide();
apartado_11.show();
apartado_12.show();

in_descuento_pension_alimenticia_dh.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

in_descuento_pension_alimenticia_fc.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

in_monto_credito_solicitado_dh.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

in_monto_ahorro_voluntario.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

lada_nep_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

numero_nep_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

extension_nep_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});


nss_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

curp_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});


rfc_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

apellido_paterno_ct.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

apellido_materno_ct.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

nombre_ct.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

numero_exterior_ct.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

numero_interior_ct.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);

});

lada_com_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

numero_com_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

cel_com_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);

});

correo_com_ct.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.toLowerCase();
    $(this).val(value);
});

inm_co_acreditado_nss.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);
});

inm_co_acreditado_curp.change(function() {
    let value = $(this).val().trim().toUpperCase();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);
});

inm_co_acreditado_rfc.change(function() {
    let value = $(this).val().trim().toUpperCase();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);
});

inm_co_acreditado_apellido_paterno.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});

inm_co_acreditado_apellido_materno.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});

inm_co_acreditado_nombre.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});

inm_co_acreditado_lada.change(function() {
    let value = $(this).val().trim();
    $(this).val(value);
});

inm_co_acreditado_numero.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);
});

inm_co_acreditado_celular.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);
});

inm_co_acreditado_correo.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.toLowerCase();
    $(this).val(value);
});

inm_co_acreditado_nombre_empresa_patron.change(function() {
    let value = $(this).val().trim().toUpperCase();
    $(this).val(value);
});

inm_co_acreditado_nrp.change(function() {
    let value = $(this).val().trim().toUpperCase();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);
});

inm_co_acreditado_lada_nep.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);
});

inm_co_acreditado_numero_nep.change(function() {
    let value = $(this).val().trim();
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    value = value.replace(' ','');
    $(this).val(value);
});



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
collapse_a8.click(function() {
    apartado_8.toggle();

});

collapse_a9.click(function() {
    apartado_9.toggle();

});

collapse_a10.click(function() {
    apartado_10.toggle();

});

collapse_a11.click(function() {
    apartado_11.toggle();

});

collapse_a12.click(function() {
    apartado_12.toggle();

});


let todo_aculto = true;

$("#collapse_all").click(function() {
    if(todo_aculto){
        apartado_1.hide();
        apartado_2.hide();
        apartado_3.hide();
        apartado_4.hide();
        apartado_5.hide();
        apartado_6.hide();
        apartado_7.hide();
        apartado_8.hide();
        apartado_9.hide();
        apartado_10.hide();
        apartado_11.hide();
        apartado_12.hide();
        todo_aculto = false;
    }
    else{
        apartado_1.show();
        apartado_2.show();
        apartado_3.show();
        apartado_4.show();
        apartado_5.show();
        apartado_6.show();
        apartado_7.show();
        apartado_8.show();
        apartado_9.show();
        apartado_10.show();
        apartado_11.show();
        apartado_12.show();
        todo_aculto = true;
    }

});


let sl_inm_tipo_credito_id = $("#inm_tipo_credito_id");
let sl_inm_attr_tipo_credito_id = $("#inm_attr_tipo_credito_id");

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

                apartado_7.toggle();
                collapse_a7.off("click").click(function () {
                    apartado_7.toggle();
                });
            }else{
                apartado_6.hide();
                collapse_a6.off("click");

                apartado_7.hide();
                collapse_a7.off("click");
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
        url: 'index.php?seccion=inm_tipo_credito&accion=get_attr_tipos_credito&ws=1&session_id='+session_id,
        success: function(data_r) {
            sl_inm_attr_tipo_credito_id.empty();
            integra_new_option('#inm_attr_tipo_credito_id','Seleccione una tipo credito','-1');
            $.each(data_r.registros, function( index, tipo_credito ) {
                integra_new_option('#inm_attr_tipo_credito_id',tipo_credito.inm_attr_tipo_credito_descripcion,
                    tipo_credito.inm_attr_tipo_credito_id);
            });
            sl_inm_attr_tipo_credito_id.val('-1');
            sl_inm_attr_tipo_credito_id.selectpicker('refresh');
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });

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

                apartado_7.toggle();
                collapse_a7.off("click").click(function () {
                    apartado_7.toggle();
                });
            }else{
                apartado_6.hide();
                collapse_a6.off("click");

                apartado_7.hide();
                collapse_a7.off("click");
            }
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });
});

let sl_adm_estado_civil_id = $("#adm_estado_civil_id");
let sl_inm_estado_civil_id = $("#inm_estado_civil_id");

function inicializa_estado_civil(){
    estado_civil_id = sl_adm_estado_civil_id.val();

    $.ajax({
        type: "POST",
        data: {'id':estado_civil_id},
        url: 'index.php?seccion=inm_estado_civil&accion=get_estado_civil&ws=1&session_id='+session_id,
        success: function(data_r) {
            if(data_r.adm_estado_civil_descripcion === "CASADO"){
                apartado_10.toggle();
                collapse_a10.off("click").click(function () {
                    apartado_10.toggle();
                });
            }else{
                apartado_10.hide();
                collapse_a10.off("click");
            }
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });
}
sl_adm_estado_civil_id.change(function () {
    estado_civil_id = $(this).val();

    $.ajax({
        type: "POST",
        data: {'id':estado_civil_id},
        url: 'index.php?seccion=inm_estado_civil&accion=get_estados_civiles&ws=1&session_id='+session_id,
        success: function(data_r) {
            sl_inm_estado_civil_id.empty();
            integra_new_option('#inm_estado_civil_id','Seleccione una estado civil','-1');
            $.each(data_r.registros, function( index, estado_civil ) {
                integra_new_option('#inm_estado_civil_id',estado_civil.inm_estado_civil_descripcion,
                    estado_civil.inm_estado_civil_id);
            });
            sl_inm_estado_civil_id.val('-1');
            sl_inm_estado_civil_id.selectpicker('refresh');
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });

    $.ajax({
        type: "POST",
        data: {'id':estado_civil_id},
        url: 'index.php?seccion=inm_estado_civil&accion=get_estado_civil&ws=1&session_id='+session_id,
        success: function(data_r) {
            if(data_r.adm_estado_civil_descripcion === "CASADO"){
                apartado_10.toggle();
                collapse_a10.off("click").click(function () {
                    apartado_10.toggle();
                });
            }else{
                apartado_10.hide();
                collapse_a10.off("click");
            }
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });
});


apellido_paterno_ct.change(function(){

    let apellido_paterno = $(this).val();
    apellido_paterno = apellido_paterno.toUpperCase()
    apellido_paterno_ct.val(apellido_paterno);

});

nombre_ct.change(function(){

    let nombre = $(this).val();
    nombre = nombre.toUpperCase()
    nombre_ct.val(nombre);

});

apellido_materno_ct.change(function(){

    let apellido_materno = $(this).val();
    apellido_materno = apellido_materno.toUpperCase()
    apellido_materno_ct.val(apellido_materno);

});

nombre_empresa_patron_ct.change(function(){

    let nombre_empresa_patron = $(this).val();
    nombre_empresa_patron = nombre_empresa_patron.toUpperCase().trim();
    nombre_empresa_patron_ct.val(nombre_empresa_patron);

});

rfc_ct.change(function(){

    let rfc = $(this).val();
    rfc = rfc.toUpperCase()
    rfc_ct.val(rfc);

});

nrp_nep_ct.change(function(){

    let nrp_nep = $(this).val();
    nrp_nep = nrp_nep.toUpperCase().trim();
    nrp_nep = nrp_nep.replace(' ','');
    nrp_nep = nrp_nep.replace(' ','');
    nrp_nep = nrp_nep.replace(' ','');
    nrp_nep = nrp_nep.replace(' ','');
    nrp_nep_ct.val(nrp_nep);

});

curp_ct.change(function(){

    let curp = $(this).val();
    curp = curp.toUpperCase()
    curp = curp.replace(' ','');
    curp = curp.replace(' ','');
    curp = curp.replace(' ','');
    curp_ct.val(curp);

});


chk_es_segundo_credito.change(function(){
    let es_segundo_credito = $(this).val();

    if(es_segundo_credito === 'SI'){
        sl_inm_plazo_credito_sc_id.prop('disabled',false);
    }
    else{
        sl_inm_plazo_credito_sc_id.val(7);
        sl_inm_plazo_credito_sc_id.prop('disabled',true);
    }
    sl_inm_plazo_credito_sc_id.selectpicker('refresh');
});

chk_con_discapacidad.change(function(){
    let con_discapacidad = $(this).val();
    if(con_discapacidad === 'SI'){
        sl_inm_tipo_discapacidad_id.prop('disabled',false);
        sl_inm_persona_discapacidad_id.prop('disabled',false);
    }
    else{
        sl_inm_tipo_discapacidad_id.val(5);
        sl_inm_tipo_discapacidad_id.prop('disabled',true);

        sl_inm_persona_discapacidad_id.val(6);
        sl_inm_persona_discapacidad_id.prop('disabled',true);
    }
    sl_inm_tipo_discapacidad_id.selectpicker('refresh');
    sl_inm_persona_discapacidad_id.selectpicker('refresh');
});
$("#conyuge_dp_estado_id").change(function(){
    conyuge_dp_estado_id = $(this).val();
    dp_asigna_municipios(conyuge_dp_estado_id,'',"#conyuge_dp_municipio_id");
});

$("#inm_referencia_dp_pais_id_1").change(function(){
    dp_pais_id = $(this).val();
    dp_asigna_estados(dp_pais_id,'',"#inm_referencia_dp_estado_id_1");
});

$("#inm_referencia_dp_estado_id_1").change(function(){
    dp_estado_id = $(this).val();
    dp_asigna_municipios(dp_estado_id,'',"#inm_referencia_dp_municipio_id_1");
});

$("#inm_referencia_dp_municipio_id_1").change(function(){
    dp_municipio_id = $(this).val();
    dp_asigna_cps(dp_municipio_id,'',"#inm_referencia_dp_cp_id_1");
});

$("#inm_referencia_dp_cp_id_1").change(function(){
    dp_cp_id = $(this).val();
    dp_asigna_colonias_postales(dp_cp_id,'','#inm_referencia_dp_colonia_postal_id_1',);
});

$("#inm_referencia_dp_colonia_postal_id_1").change(function(){
    dp_colonia_postal_id = $(this).val();
    dp_asigna_calles_pertenece(dp_colonia_postal_id,'',"#inm_referencia_dp_calle_pertenece_id_1");
});





$("#inm_referencia_dp_pais_id_2").change(function(){
    dp_pais_id = $(this).val();
    dp_asigna_estados(dp_pais_id,'',"#inm_referencia_dp_estado_id_2");
});

$("#inm_referencia_dp_estado_id_2").change(function(){
    dp_estado_id = $(this).val();
    dp_asigna_municipios(dp_estado_id,'',"#inm_referencia_dp_municipio_id_2");
});

$("#inm_referencia_dp_municipio_id_2").change(function(){
    dp_municipio_id = $(this).val();
    dp_asigna_cps(dp_municipio_id,'',"#inm_referencia_dp_cp_id_2");
});

$("#inm_referencia_dp_cp_id_2").change(function(){
    dp_cp_id = $(this).val();
    dp_asigna_colonias_postales(dp_cp_id,'','#inm_referencia_dp_colonia_postal_id_2',);
});

$("#inm_referencia_dp_colonia_postal_id_2").change(function(){
    dp_colonia_postal_id = $(this).val();
    dp_asigna_calles_pertenece(dp_colonia_postal_id,'',"#inm_referencia_dp_calle_pertenece_id_2");
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

function dp_asigna_calles_pertenece(dp_colonia_postal_id = '',dp_calle_pertenece_id = '', name_selector_dependiente='#dp_calle_pertenece_id'){

    let sl_dp_calle_pertenece_id = $(name_selector_dependiente);

    let url = "index.php?seccion=dp_calle_pertenece&ws=1&accion=get_calle_pertenece&dp_colonia_postal_id="+dp_colonia_postal_id+"&session_id="+session_id;
    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        sl_dp_calle_pertenece_id.empty();
        integra_new_option(name_selector_dependiente,'Seleccione una calle','-1');
        $.each(data.registros, function( index, dp_calle_pertenece ) {
            integra_new_option(name_selector_dependiente,dp_calle_pertenece.dp_calle_descripcion,dp_calle_pertenece.dp_calle_pertenece_id);
        });
        sl_dp_calle_pertenece_id.val(dp_calle_pertenece_id);
        sl_dp_calle_pertenece_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
}
function dp_asigna_colonias_postales(dp_cp_id = '',dp_colonia_postal_id = '',name_selector_dependiente='#dp_colonia_postal_id'){

    let sl_dp_colonia_postal_id = $(name_selector_dependiente);

    let url = "index.php?seccion=dp_colonia_postal&ws=1&accion=get_colonia_postal&dp_cp_id="+dp_cp_id+"&session_id="+session_id;
    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        sl_dp_colonia_postal_id.empty();
        integra_new_option(name_selector_dependiente,'Seleccione una colonia','-1');
        $.each(data.registros, function( index, dp_colonia_postal ) {
            integra_new_option(name_selector_dependiente,dp_colonia_postal.dp_colonia_descripcion,dp_colonia_postal.dp_colonia_postal_id);
        });
        sl_dp_colonia_postal_id.val(dp_colonia_postal_id);
        sl_dp_colonia_postal_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
}
function dp_asigna_cps(dp_municipio_id = '',dp_cp_id = '', name_selector_dependiente='#dp_cp_id'){

    let sl_dp_cp_id = $(name_selector_dependiente);

    let url = "index.php?seccion=dp_cp&ws=1&accion=get_cp&dp_municipio_id="+dp_municipio_id+"&session_id="+session_id;
    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        sl_dp_cp_id.empty();
        integra_new_option(name_selector_dependiente,'Seleccione un cp','-1');
        $.each(data.registros, function( index, dp_cp ) {
            integra_new_option(name_selector_dependiente,dp_cp.dp_cp_descripcion,dp_cp.dp_cp_id);
        });
        sl_dp_cp_id.val(dp_cp_id);
        sl_dp_cp_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
}

function dp_asigna_estados(dp_pais_id = '',dp_estado_id = '', name_selector_dependiente = "#dp_estado_id"){

    let sl_dp_estado_id = $(name_selector_dependiente);

    let url = "index.php?seccion=dp_estado&ws=1&accion=get_estado&dp_pais_id="+dp_pais_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        sl_dp_estado_id.empty();
        integra_new_option(name_selector_dependiente,'Seleccione un estado','-1');

        $.each(data.registros, function( index, dp_estado ) {
            integra_new_option(name_selector_dependiente,dp_estado.dp_estado_descripcion,dp_estado.dp_estado_id);
        });
        sl_dp_estado_id.val(dp_estado_id);
        sl_dp_estado_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log("The following error occured: "+ textStatus +" "+ errorThrown);
    });

}

function dp_asigna_municipios(dp_estado_id = '',dp_municipio_id = '', name_selector_dependiente='#dp_municipio_id'){

    let sl_dp_municipio_id = $(name_selector_dependiente);

    let url = "index.php?seccion=dp_municipio&ws=1&accion=get_municipio&dp_estado_id="+dp_estado_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        sl_dp_municipio_id.empty();

        integra_new_option(name_selector_dependiente,'Seleccione un municipio','-1');

        $.each(data.registros, function( index, dp_municipio ) {
            integra_new_option(name_selector_dependiente,dp_municipio.dp_municipio_descripcion,dp_municipio.dp_municipio_id);
        });
        sl_dp_municipio_id.val(dp_municipio_id);
        sl_dp_municipio_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log("The following error occured: "+ textStatus +" "+ errorThrown);
    });

}

/***** Modal Documentos *****/

var modal = document.getElementById("myModal");
var closeBtn = document.getElementById("closeModalBtn");
let inm_doc_comprador_id = '';
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
            var inputdoc = tempDiv.find('[name="inm_doc_comprador_id"]');
            var viewContent = tempDiv.find(".view");
            inm_doc_comprador_id = inputdoc.val();

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
        data: {id:inm_doc_comprador_id},
        url: 'index.php?seccion=inm_doc_comprador&accion=elimina_temporal&ws=1&session_id='+session_id,
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
            data: {id:inm_doc_comprador_id},
            url: 'index.php?seccion=inm_doc_comprador&accion=elimina_temporal&ws=1&session_id='+session_id,
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

const table_tipos_documentos = table('inm_comprador', columns_tipos_documentos, [], [], function () {
    }, true,
    "tipos_documentos", {registro_id: registro_id,pestana_general_actual: pestana_general_actual,
        pestana_actual:pestana_actual}, options);

/***** Etapa Cobrador *****/

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

/***** Etapa Por Firmar *****/
let pago_propio_peculio = $("#pago_propio_peculio");
let pago_precio_compra_venta = $("#pago_precio_compra_venta");
let pago_parcial_precio_compra_venta = $("#pago_parcial_precio_compra_venta");

function limpiarMonto(valor) {
    if (!valor) return 0;
    return parseFloat(valor.replace(/[\$,]/g, "")) || 0;
}

$("#pago_propio_peculio, #pago_precio_compra_venta").on("input", function() {
    let propio = limpiarMonto(pago_propio_peculio.val());
    let precio = limpiarMonto(pago_precio_compra_venta.val());
    let parcial = precio - propio;
    pago_parcial_precio_compra_venta.val(parcial.toFixed(2));
});
