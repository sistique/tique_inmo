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
            data.forEach(function(producto) {
                let producto_descrip = "Clave SAT: " + producto.ClaveProdServ
                + " - Descripcion: " + producto.Descripcion
                + " - Unidad: " + producto.Unidad;
                $('#producto').val(producto_descrip);

                let url_uni = get_url("cat_sat_unidad", "get_unidad", {cat_sat_unidad_codigo: data.ClaveUnidad });
                get_data(url_uni, function (data_tp) {
                    $('#cat_sat_unidad_id').val(data_tp.cat_sat_unidad_id);
                    $('#cat_sat_unidad_id').selectpicker('refresh');
                });

                $('#descripcion_producto').val(producto.Descripcion);
                $('#cat_sat_cve_prod_codigo').val(producto.ClaveProdServ);
                $('#costo_promedio').val(producto.ValorUnitario);
                $('#cantidad_actual').val(producto.Cantidad);
            });

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

let productos = []; // Guardar todos los productos
let currentPage = 1;
let rowsPerPage = 5;

function renderTable(page) {
    let tbody = $('.productos tbody');
    tbody.empty();

    // Calcular inicio y fin
    let start = (page - 1) * rowsPerPage;
    let end = start + rowsPerPage;

    // Obtener registros de esta página
    let pageItems = productos.slice(start, end);

    pageItems.forEach(function(producto) {
        let tr = $('<tr>');
        let checkbox = $('<input name="producto" type="checkbox" class="producto-checkbox">')
            .val(producto.inm_producto_id);

        tr.append($('<td>').append(checkbox));
        tr.append($('<td>').text(producto.inm_producto_id));
        tr.append($('<td>').text(producto.inm_producto_descripcion));
        tbody.append(tr);
    });

    // Validar solo un checkbox activo
    $('.producto-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('.producto-checkbox').not(this).prop('checked', false);
        }
    });
}

function renderPagination() {
    let totalPages = Math.ceil(productos.length / rowsPerPage);
    let pagination = $('#pagination');
    pagination.empty();

    for (let i = 1; i <= totalPages; i++) {
        let btn = $('<button>')
            .text(i)
            .addClass('page-btn btn btn-sm btn-primary mx-1')
            .attr('data-page', i);

        if (i === currentPage) {
            btn.addClass('active');
        }

        pagination.append(btn);
    }

    // Evento cambio de página
    $('.page-btn').on('click', function() {
        currentPage = parseInt($(this).attr('data-page'));
        renderTable(currentPage);
        renderPagination();
    });
}

// Cargar con AJAX
$.ajax({
    url: url_prd,
    type: 'POST',
    data: { filtros: filtro_inm_producto },
    success: function (data) {
        productos = data; // Guardar todos los productos
        currentPage = 1; // Reiniciar a la primera página
        renderTable(currentPage);
        renderPagination();
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
        data: { filtros: filtro_inm_producto },
        success: function (data) {
            productos = data; // Guardar todos los productos
            currentPage = 1; // Reiniciar a la primera página
            renderTable(currentPage);
            renderPagination();
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
        data: { filtros: filtro_inm_producto },
        success: function (data) {
            productos = data; // Guardar todos los productos
            currentPage = 1; // Reiniciar a la primera página
            renderTable(currentPage);
            renderPagination();
        },
        error: function () {
            console.log('error');
        }
    });
});