let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

var modal = document.getElementById("myModal");
var closeBtn = document.getElementById("closeModalBtn");
$(document).on("click", "button[title='Vista Previa']", function (event) {
    event.preventDefault();
    //var url = $(this).attr("href");
    $('#table-inm_producto thead input').prop('disabled', true).hide();

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
            //$("#myModal .content").html('');
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
    modal.close();
}

modal.addEventListener('click', function (event) {
    if (event.target === modal) {
        modal.close();
    }
});


/***** Productos *****/
let filtro_inm_producto = [];
let url_prd = get_url("inm_producto", "get_productos", {registro_id: registro_id});

$.ajax({
    url: url_prd,
    type: 'POST',
    data: {filtros: filtro_inm_producto},
    success: function (data) {
        let tbody = $('.productos tbody');
        tbody.empty();

        data.forEach(function(producto) {
            let tr = $('<tr>');
            let checkbox = $('<input name="producto" type="checkbox" class="producto-checkbox">')
                .val(producto.inm_producto_id);

            tr.append($('<td>').append(checkbox));
            tr.append($('<td>').text(producto.inm_producto_id));
            tr.append($('<td>').text(producto.inm_producto_descripcion));
            tbody.append(tr);
        });

        $('.producto-checkbox').on('change', function() {
            if ($(this).is(':checked')) {
                // Desmarcar todos los demás
                $('.producto-checkbox').not(this).prop('checked', false);
            }
        });
    },
    error: function () {
        console.log('error');
    }
});

/***** Filtros *****/

var filtro_aplicado = false;

$('#limpiar').prop('disabled', true);
$('#filtrar').on('click', function () {
    let producto_id = $('#inm_producto_id').val();
    let producto_descripcion = $('#inm_producto_descripcion').val();

    filtro_inm_producto = {
        filtro: {},
        extra_join: []
    };

    if (producto_id) {
        filtro_inm_producto.filtro['inm_producto_id'] = producto_id;
    }

    if (producto_descripcion) {
        filtro_inm_producto.filtro['inm_producto_descripcion'] = producto_descripcion;
    }


    $.ajax({
        url: url_prd,
        type: 'POST',
        data: {filtros: filtro_inm_producto},
        success: function (data) {
            let tbody = $('.productos tbody');
            tbody.empty();

            data.forEach(function(producto) {
                let tr = $('<tr>');
                let checkbox = $('<input name="producto" type="checkbox" class="producto-checkbox">')
                    .val(producto.inm_producto_id);

                tr.append($('<td>').append(checkbox));
                tr.append($('<td>').text(producto.inm_producto_id));
                tr.append($('<td>').text(producto.inm_producto_descripcion));
                tbody.append(tr);
            });

            $('.producto-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    // Desmarcar todos los demás
                    $('.producto-checkbox').not(this).prop('checked', false);
                }
            });
        },
        error: function () {
            console.log('error');
        }
    });

    $('#filtrar').prop('disabled', false);
    $('#limpiar').prop('disabled', false);
});

$('#limpiar').on('click', function () {
    $('.filtros-avanzados input').val('');
    $('.filtros-avanzados select').val('').trigger('change');
    $('.filtros-avanzados li').remove();
    $('#limpiar').prop('disabled', true);

    filtro_inm_producto = [];
    $.ajax({
        url: url_prd,
        type: 'POST',
        data: {filtros: filtro_inm_producto},
        success: function (data) {
            let tbody = $('.productos tbody');
            tbody.empty();

            data.forEach(function(producto) {
                let tr = $('<tr>');
                let checkbox = $('<input name="producto" type="checkbox" class="producto-checkbox">')
                    .val(producto.inm_producto_id);

                tr.append($('<td>').append(checkbox));
                tr.append($('<td>').text(producto.inm_producto_id));
                tr.append($('<td>').text(producto.inm_producto_descripcion));
                tbody.append(tr);
            });

            $('.producto-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    // Desmarcar todos los demás
                    $('.producto-checkbox').not(this).prop('checked', false);
                }
            });
        },
        error: function () {
            console.log('error');
        }
    });
});