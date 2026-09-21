const registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

function actualizarPreview(select) {
    const opcionSeleccionada = select.options[select.selectedIndex];
    const preview = opcionSeleccionada?.dataset.preview ?? '';
    const input = document.getElementById(select.dataset.target);
    if (input) input.value = preview;
}

document.querySelectorAll('.select-mapeo').forEach(select => {
    actualizarPreview(select);

    select.addEventListener('change', function () {
        actualizarPreview(this);
    });
});