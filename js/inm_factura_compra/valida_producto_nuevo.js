let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let productos_xml = [];
let productos = [];
let productos_completos = [];

let asignaciones = [];

let producto_xml_actual = 0;

$(document).ready(function () {
    var url = 'index.php?seccion=inm_factura_compra&accion=obten_productos&ws=1&registro_id='+registro_id+'&session_id='+session_id;

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            productos_xml = data;
            console.log(productos_xml);
        },
        error: function () {
            console.log('Error al obtener productos');
        }
    });
});

function valores_formulario_producto(producto) {
    let producto_descrip = "Clave SAT: " + producto.ClaveProdServ
        + " - Descripcion: " + producto.Descripcion
        + " - Unidad: " + producto.Unidad;
    $('#producto').val(producto_descrip);

    let url_uni = get_url("cat_sat_unidad", "get_unidad", {cat_sat_unidad_codigo: producto.ClaveUnidad });
    get_data(url_uni, function (data_tp) {
        $('#cat_sat_unidad_id').val(data_tp.cat_sat_unidad_id);
        $('#cat_sat_unidad_id').selectpicker('refresh');
    });

    $('#descripcion_producto').val(producto.Descripcion);
    $('#cat_sat_cve_prod_codigo').val(producto.ClaveProdServ);
    $('#costo_promedio').val(producto.ValorUnitario);
    $('#cantidad_actual').val(producto.Cantidad);
}

var modal = document.getElementById("myModal");
var closeBtn = document.getElementById("closeModalBtn");

var loaderOverlay = $('<div class="loader-overlay"><div class="loader"></div></div>');

$(document).on("click", "button[title='Vista Previa']", function (event) {
    event.preventDefault();
    $('#table-inm_producto thead input').prop('disabled', true).hide();
    $('body').append(loaderOverlay);

    modal.showModal();
    loaderOverlay.remove();

    let ultimo_indice = 0;
    if (asignaciones.length > 0) {
        let maxItem = asignaciones.reduce((max, item) =>
            (parseInt(item.producto_xml) > parseInt(max.producto_xml) ? item : max)
        );

        ultimo_indice =  maxItem.producto_xml;
    }

    ultimo_indice = ultimo_indice + 1;

    productos_xml.forEach(function(producto) {
        if(producto.indice === ultimo_indice){
            valores_formulario_producto(producto);
            producto_xml_actual = ultimo_indice;
        }
    });

    $('input[name="producto"]:checked').prop('checked', false);

    currentPage = 1;
    renderTable_productos_completos(currentPage);
    renderPagination_productos_completos();
});


$('#asignar').on('click', function () {
    let chk = document.querySelector('input[name="producto"]:checked');
    if (!chk) {
        alert("Debes seleccionar un producto.");
        return;
    }

    if (chk.value === '-1') {
        alert("No existe el producto seleccionado.");
        return;
    }

    let existente = asignaciones.find(item => item.producto_xml === producto_xml_actual);

    if (existente) {
        existente.inm_producto_id = chk.value;
    } else {
        $('#por_asignar-' + producto_xml_actual).text('Asignado a Producto ID: ' + chk.value);

        asignaciones.push({
            inm_producto_id: chk.value,
            producto_xml: producto_xml_actual
        });
    }

    console.log(asignaciones);
});

function abrir_modal(indice){
    $('#table-inm_producto thead input').prop('disabled', true).hide();
    $('body').append(loaderOverlay);

    modal.showModal();
    loaderOverlay.remove();

    $('.filtros-avanzados input').val('');
    $('.filtros-avanzados select').val('').trigger('change');
    $('.filtros-avanzados li').remove();
    $('#limpiar').prop('disabled', true);

    productos_xml.forEach(function(producto) {
        if(producto.indice === indice){
            valores_formulario_producto(producto);
        }
    });
    producto_xml_actual = indice;

    $('input[name="producto"]:checked').prop('checked', false);

    currentPage = 1;
    renderTable_productos_completos(currentPage);
    renderPagination_productos_completos();
}


/*$(document).on("click", "button[title='Vista Previa']", function (event) {
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

                let url_uni = get_url("cat_sat_unidad", "get_unidad", {cat_sat_unidad_codigo: producto.ClaveUnidad });
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
});*/

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

let currentPage = 1;
let rowsPerPage = 5;

function renderTable(page) {
    let tbody = $('.productos tbody');
    tbody.empty();

    let start = (page - 1) * rowsPerPage;
    let end = start + rowsPerPage;

    let pageItems = productos.slice(start, end);

    pageItems.forEach(function(producto) {
        let tr = $('<tr>');
        let checkbox = $('<input name="producto" type="checkbox" class="producto-checkbox">')
            .val(producto.inm_producto_id);

        let existente = asignaciones.find(item =>
            item.producto_xml === producto_xml_actual &&
            item.inm_producto_id === producto.inm_producto_id
        );

        if (existente) {
            checkbox.prop("checked", true);
        }

        tr.append($('<td>').append(checkbox));
        tr.append($('<td>').text(producto.inm_producto_id));
        tr.append($('<td>').text(producto.inm_producto_descripcion));
        tbody.append(tr);
    });

    $('.producto-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('.producto-checkbox').not(this).prop('checked', false);
            if ($(this).val() === '-1') {
                $('.content_alta').show();
            }
        }else{
            $('.content_alta').hide();
            if ($(this).val() === '-1') {
                $('.content_alta').hide();
            }
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

    $('.page-btn').on('click', function() {
        let chk = document.querySelector('input[name="producto"]:checked');
        if (chk) {
            $(chk).prop("checked", false);
            $('.content_alta').hide();
        }

        currentPage = parseInt($(this).attr('data-page'));
        renderTable(currentPage);
        renderPagination();
    });
}

function renderTable_productos_completos(page) {
    let tbody = $('.productos tbody');
    tbody.empty();

    let start = (page - 1) * rowsPerPage;
    let end = start + rowsPerPage;

    let pageItems = productos_completos.slice(start, end);

    pageItems.forEach(function(producto) {
        let tr = $('<tr>');
        let checkbox = $('<input name="producto" type="checkbox" class="producto-checkbox">')
            .val(producto.inm_producto_id);

        let existente = asignaciones.find(item =>
            item.producto_xml === producto_xml_actual &&
            item.inm_producto_id === producto.inm_producto_id
        );

        if (existente) {
            checkbox.prop("checked", true);
        }

        tr.append($('<td>').append(checkbox));
        tr.append($('<td>').text(producto.inm_producto_id));
        tr.append($('<td>').text(producto.inm_producto_descripcion));
        tbody.append(tr);
    });

    $('.producto-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('.producto-checkbox').not(this).prop('checked', false);
            if ($(this).val() === '-1') {
                $('.content_alta').show();
            }
        }else{
            $('.content_alta').hide();
            if ($(this).val() === '-1') {
                $('.content_alta').hide();
            }
        }
    });
}

function renderPagination_productos_completos() {
    let totalPages = Math.ceil(productos_completos.length / rowsPerPage);
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

    $('.page-btn').on('click', function() {
        let chk = document.querySelector('input[name="producto"]:checked');
        if (chk) {
            $(chk).prop("checked", false);
            $('.content_alta').hide();
        }

        currentPage = parseInt($(this).attr('data-page'));
        renderTable_productos_completos(currentPage);
        renderPagination_productos_completos();
    });
}

$.ajax({
    url: url_prd,
    type: 'POST',
    data: { filtros: filtro_inm_producto },
    success: function (data) {
        productos = data;
        productos_completos = data;
        currentPage = 1;
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
            productos = data;
            currentPage = 1;
            renderTable(currentPage);
            renderPagination();
        },
        error: function () {
            console.log('error');
        }
    });

    $('.content_alta').hide();

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
            productos = data;
            currentPage = 1;
            renderTable(currentPage);
            renderPagination();
        },
        error: function () {
            console.log('error');
        }
    });

    $('.content_alta').hide();
});