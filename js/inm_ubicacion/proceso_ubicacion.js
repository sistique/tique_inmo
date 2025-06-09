let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

/***** Pestañas *****/
function cambiarPestannaGeneral(pestannas,pestanna) {
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
        });
        i += 1;
    }

    $(document).ready(function(){
        $(cpestanna).css('display','block');
        $(pestanna_act).css('background','#0f7ad5');
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
        });
        i += 1;
    }

    $(document).ready(function(){
        $(cpestanna).css('display','block');
        $(pestanna_act).css('background','#0f7ad5');
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
