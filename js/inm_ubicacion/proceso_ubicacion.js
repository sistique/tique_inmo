let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

function cambiarPestanna(pestannas,pestanna) {

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
        });
        i += 1;
    }

    for (i = valor_pestana - 1; i >= 0; i--){
        $(document).ready(function(){
            console.log($(listaPestannas.getElementsByTagName('li')[i]).attr('id'));

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

            cambiarPestanna(Pestannas, result);
        },
        error: function () {
            alert("No se ha podido obtener la información");
        }
    });
}