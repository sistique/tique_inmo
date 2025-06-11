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
    console.log(pestanna_ini);

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

