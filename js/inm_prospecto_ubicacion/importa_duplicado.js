const registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

$(document).ready(function () {
    const mensajes = {
        upsert: "Los registros que ya existan se actualizarán con los datos del archivo. Los nuevos se crearán.",
        omitir: "Los registros que ya existan se conservarán sin cambios. Solo se importarán los nuevos.",
    };

    const $aviso = $('#notice-text');

    function actualizarAviso() {
        const valor = $('input[name="estrategia"]:checked').val();
        $aviso.text(mensajes[valor]);
    }

    $('input[name="estrategia"]').on('change', actualizarAviso);
    actualizarAviso();
});
