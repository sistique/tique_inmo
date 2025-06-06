let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

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

let numero_exterior = $("#numero_exterior");
let numero_interior = $("#numero_interior");
let manzana = $("#manzana");
let lote = $("#lote");
let cuenta_predial = $("#cuenta_predial");

let sl_conyuge_dp_estado_id = $("#conyuge_dp_estado_id");
sl_conyuge_dp_estado_id.change(function () {
    conyuge_dp_estado_id = $(this).val();
    dp_asigna_municipios_conyuge(conyuge_dp_estado_id, '', '#conyuge_dp_municipio_id');
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

let collapse_a1 = $("#collapse_a1");
let collapse_a2 = $("#collapse_a2");
let collapse_a3 = $("#collapse_a3");
let collapse_a4 = $("#collapse_a4");
let collapse_a5 = $("#collapse_a5");

apartado_1.show();
apartado_2.show();
apartado_3.show();
apartado_4.show();
apartado_5.show();
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