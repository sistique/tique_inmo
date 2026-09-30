const registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

$(document).ready(function () {
    const mensajes = {
        actualizar: "Los registros que ya existan se actualizaran con los datos del archivo. Los nuevos se crearan.",
        omitir: "Los registros que ya existan se conservaran sin cambios. Solo se importaran los nuevos.",
    };

    const $aviso = $('#notice-text');

    function actualizarAviso() {
        const valor = $('input[name="estrategia"]:checked').val();
        $aviso.text(mensajes[valor]);
    }

    $('input[name="estrategia"]').on('change', actualizarAviso);
    actualizarAviso();
});

