let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

var modal = document.getElementById("myModal");
var closeBtn = document.getElementById("closeModalBtn");
$(document).on("click", "button[title='Vista Previa']", function (event) {
    event.preventDefault();
    //var url = $(this).attr("href");
    var url = 'index.php?seccion=inm_factura_compra&accion=obten_productos&ws=1&registro_id='+registro_id+'&session_id='+session_id;

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
}

modal.addEventListener('click', function (event) {
    if (event.target === modal) {
        $("#myModal .content").empty();
        modal.close();
    }
});
