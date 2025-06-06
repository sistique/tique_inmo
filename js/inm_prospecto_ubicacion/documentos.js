const registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

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

const table_tipos_documentos = table('inm_prospecto_ubicacion', columns_tipos_documentos, [], [], function () {
    }, true,
    "tipos_documentos", {registro_id: registro_id}, options);


var modal = document.getElementById("myModal");
var modalSend = document.getElementById("modalSnd");
var closeBtn = document.getElementById("closeModalBtn");
var openMdl = document.getElementById("enviar");
var closeMdl = document.getElementById("closeModalSendBtn");
let inm_doc_prospecto_ubicacion_id = '';

$(document).on("click", "#table-inm_prospecto_ubicacion a[title='Vista Previa']", function (event) {
    event.preventDefault();
    var url = $(this).attr("href");

    var loaderOverlay = $('<div class="loader-overlay"><div class="loader"></div></div>');
    $('body').append(loaderOverlay);

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            var tempDiv = $("<div>").html(data);
            var inputdoc = tempDiv.find('[name="inm_doc_prospecto_ubicacion_id"]');
            var viewContent = tempDiv.find(".view");
            inm_doc_prospecto_ubicacion_id = inputdoc.val();

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
        data: {id:inm_doc_prospecto_ubicacion_id},
        url: 'index.php?seccion=inm_doc_prospecto_ubicacion&accion=elimina_temporal&ws=1&session_id='+session_id,
        success: function(data_r) {
            console.log(data_r);
        },
        error: function() {
            alert("No se ha podido obtener la información");
        }
    });
}
openMdl.onclick = function () {
    modalSend.showModal();
}
closeMdl.onclick = function () {
    modalSend.close();
}
modal.addEventListener('click', function (event) {
    if (event.target === modal) {
        $("#myModal .content").empty();
        modal.close();

        $.ajax({
            type: "POST",
            data: {id:inm_doc_prospecto_ubicacion_id},
            url: 'index.php?seccion=inm_doc_prospecto_ubicacion&accion=elimina_temporal&ws=1&session_id='+session_id,
            success: function(data_r) {
                console.log(data_r);
            },
            error: function() {
                alert("No se ha podido obtener la información");
            }
        });
    }
});

let documentos_seleccionados = [];

$("#table-inm_prospecto_ubicacion").on('click', 'td:first-child,td:nth-child(2)', function (e) {
    let timer = null;

    clearTimeout(timer);

    timer = setTimeout(() => {
        let selectedData = table_tipos_documentos.rows({selected: true}).data();

        documentos_seleccionados = [];

        selectedData.each(function (value, index, data) {
            const url = $(value.vista_previa).attr('href')
            const params = new URLSearchParams(url);
            const accion = params.get('accion');
            const id = params.get('registro_id');

            if (accion === 'vista_previa') {
                documentos_seleccionados.push(id);
            } else {
                const rowIndex = table_tipos_documentos.rows().indexes().filter((idx) => {
                    return table_tipos_documentos.row(idx).data() === value;
                });

                table_tipos_documentos.rows(rowIndex).deselect();
                alert("Seleccione un documento cargado");
            }
        });

        $('#documentos').val(documentos_seleccionados);
        $('#documentos-verificar').val(documentos_seleccionados);
        $('#documentos-enviar').val(documentos_seleccionados);
    }, 500);
});

$("#table-inm_prospecto_ubicacion").on('click', 'tr:first-child', function (e) {
    let timer = null;

    clearTimeout(timer);

    timer = setTimeout(() => {
        let selectedData = table_tipos_documentos.rows({selected: true}).data();

        documentos_seleccionados = [];

        selectedData.each(function (value, index, data) {
            console.log(value);

            const url = $(value.vista_previa).attr('href')
            const params = new URLSearchParams(url);
            const accion = params.get('accion');
            const id = params.get('registro_id');

            if (accion === 'vista_previa') {
                documentos_seleccionados.push(id);
            } else {
                const rowIndex = table_tipos_documentos.rows().indexes().filter((idx) => {
                    return table_tipos_documentos.row(idx).data() === value;
                });

                table_tipos_documentos.rows(rowIndex).deselect();
            }
        });

        $('#documentos').val(documentos_seleccionados);
        $('#documentos-verificar').val(documentos_seleccionados);
        $('#documentos-enviar').val(documentos_seleccionados);
    }, 500);
});



$("#form-documentos").on('submit', function (e) {
    if (documentos_seleccionados.length <= 1) {
        e.preventDefault();
        alert("Seleccione más de un documento para agruparlos");
    }
});

$("#form-documentos-verificar").on('submit', function (e) {
    if (documentos_seleccionados.length < 1) {
        e.preventDefault();
        alert("Seleccione un documento para verificarlo");
    }
});

$("#form-documentos-enviar").on('submit', function (e) {
    if (documentos_seleccionados.length < 1) {
        e.preventDefault();
        alert("Seleccione un documento para enviar");
    }
});

