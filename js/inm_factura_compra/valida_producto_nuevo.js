let url = getAbsolutePath();
let registro_id = getParameterByName('registro_id');
let session_id = getParameterByName('session_id');

let productos_xml = [];
let productos = [];
let productos_completos = [];

let filtro_inm_producto = [];
let url_prd = get_url("inm_producto", "get_productos", {registro_id: registro_id});

let asignaciones = [];

let producto_xml_actual = 0;

$(document).ready(function () {
    var url = 'index.php?seccion=inm_factura_compra&accion=obten_productos&ws=1&registro_id='+registro_id+'&session_id='+session_id;

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            productos_xml = data;
        },
        error: function () {
            console.log('Error al obtener productos');
        }
    });
});

function valores_formulario_producto(producto) {
    let producto_descrip = "No. "+ producto.indice +"  Clave SAT: " + producto.ClaveProdServ
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

    let existente_prd_xml = productos_xml.find(item => item.indice === producto_xml_actual);
    if (!existente_prd_xml) return;

    let existente = asignaciones.find(item => item.producto_xml === producto_xml_actual);

    if (existente) {
        existente.inm_producto_id = chk.value;

        $(`#content_form_productos input[data-xml="${producto_xml_actual}"][name$="[inm_producto_id]"]`).val(chk.value);

        $('#por_asignar-' + producto_xml_actual).text('Reasignado a Producto ID: ' + chk.value);
        alert("Se actualizó la asignación.");
    } else {
        let div = $('#content_form_productos');
        let index = asignaciones.length;

        div.append($('<input>', {
            type: "hidden",
            name: `asignaciones[${index}][inm_factura_compra_id]`,
            value: registro_id,
            "data-xml": producto_xml_actual
        }));
        div.append($('<input>', {
            type: "hidden",
            name: `asignaciones[${index}][inm_producto_id]`,
            value: chk.value,
            "data-xml": producto_xml_actual
        }));
        div.append($('<input>', {
            type: "hidden",
            name: `asignaciones[${index}][cantidad]`,
            value: existente_prd_xml.Cantidad,
            "data-xml": producto_xml_actual
        }));
        div.append($('<input>', {
            type: "hidden",
            name: `asignaciones[${index}][valor_unitario]`,
            value: existente_prd_xml.ValorUnitario,
            "data-xml": producto_xml_actual
        }));
        div.append($('<input>', {
            type: "hidden",
            name: `asignaciones[${index}][subtotal]`,
            value: existente_prd_xml.Importe,
            "data-xml": producto_xml_actual
        }));
        div.append($('<input>', {
            type: "hidden",
            name: `asignaciones[${index}][trasladado]`,
            value: existente_prd_xml.Trasladado,
            "data-xml": producto_xml_actual
        }));
        div.append($('<input>', {
            type: "hidden",
            name: `asignaciones[${index}][retenido]`,
            value: existente_prd_xml.Retenido,
            "data-xml": producto_xml_actual
        }));
        div.append($('<input>', {
            type: "hidden",
            name: `asignaciones[${index}][total]`,
            value: existente_prd_xml.Total,
            "data-xml": producto_xml_actual
        }));

        asignaciones.push({
            producto_xml: producto_xml_actual,
            inm_producto_id: chk.value
        });

        $('#por_asignar-' + producto_xml_actual).text('Asignado a Producto ID: ' + chk.value);
        alert("Se asignó con éxito.");
    }

    let todos_asignados = productos_xml.every(prod =>
        asignaciones.some(asig => asig.producto_xml === prod.indice && asig.inm_producto_id)
    );

    if (todos_asignados) {
        $('.btn-insert').css({
            "pointer-events": "auto",
            "opacity": "1",
            "cursor": "pointer"
        });

        modal.close();
        setTimeout(() => {
            alert("Ya están todos los productos asignados, inserta por favor.");
        }, 0);
    } else {
        $('.btn-insert').css({
            "pointer-events": "none",
            "opacity": "0.9",
            "cursor": "not-allowed"
        });
    }
});

function validarCampo(selector){
    return $(selector).val().trim();
}

$('#alta_producto').on('click', function () {

    let url_alta_prd = get_url("inm_factura_compra", "inserta_producto_bd", {registro_id: registro_id});

    let registro = {
        inm_concepto_id: validarCampo('#inm_concepto_id'),
        descripcion: validarCampo('#descripcion_producto'),
        cat_sat_unidad_id: validarCampo('#cat_sat_unidad_id'),
        cat_sat_cve_prod_codigo: validarCampo('#cat_sat_cve_prod_codigo'),
        costo_promedio: validarCampo('#costo_promedio'),
        cantidad_actual: validarCampo('#cantidad_actual')
    };

    for (let key in registro) {
        if (!registro[key]) {
            alert('Todos los campos son obligatorios');
            return;
        }
    }

    $.ajax({
        url: url_alta_prd,
        type: 'POST',
        data: {registro: registro},
        success: function (data) {

            let str = data.registro_id;
            if(typeof str === 'number'){
                str = String(data.registro_id);
            }

            let existente_prd_xml = productos_xml.find(item => item.indice === producto_xml_actual);

            let div = $('#content_form_productos');
            let index = asignaciones.length;

            div.append($('<input>', {
                type: "hidden",
                name: `asignaciones[${index}][inm_factura_compra_id]`,
                value: registro_id,
                "data-xml": producto_xml_actual
            }));
            div.append($('<input>', {
                type: "hidden",
                name: `asignaciones[${index}][inm_producto_id]`,
                value: str,
                "data-xml": producto_xml_actual
            }));
            div.append($('<input>', {
                type: "hidden",
                name: `asignaciones[${index}][cantidad]`,
                value: existente_prd_xml.Cantidad,
                "data-xml": producto_xml_actual
            }));
            div.append($('<input>', {
                type: "hidden",
                name: `asignaciones[${index}][valor_unitario]`,
                value: existente_prd_xml.ValorUnitario,
                "data-xml": producto_xml_actual
            }));
            div.append($('<input>', {
                type: "hidden",
                name: `asignaciones[${index}][subtotal]`,
                value: existente_prd_xml.Importe,
                "data-xml": producto_xml_actual
            }));
            div.append($('<input>', {
                type: "hidden",
                name: `asignaciones[${index}][trasladado]`,
                value: existente_prd_xml.Trasladado,
                "data-xml": producto_xml_actual
            }));
            div.append($('<input>', {
                type: "hidden",
                name: `asignaciones[${index}][retenido]`,
                value: existente_prd_xml.Retenido,
                "data-xml": producto_xml_actual
            }));
            div.append($('<input>', {
                type: "hidden",
                name: `asignaciones[${index}][total]`,
                value: existente_prd_xml.Total,
                "data-xml": producto_xml_actual
            }));

            $('#por_asignar-' + producto_xml_actual).text('Asignado a Producto ID: ' + str);

            asignaciones.push({
                inm_producto_id: str,
                producto_xml: producto_xml_actual
            });

            filtro_inm_producto = [];
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

                    $('#producto_asignado').val('');
                    let existente_prod = productos_completos.find(item =>
                        item.inm_producto_id === str
                    );

                    if(existente_prod){
                        $('#producto_asignado').val(existente_prod.inm_producto_descripcion);
                    }
                },
                error: function () {
                    console.log('error');
                }
            });

            $('.content_alta').hide();

            let todos_asignados = productos_xml.every(prod =>
                asignaciones.some(asig => asig.producto_xml === prod.indice && asig.inm_producto_id)
            );

            if (todos_asignados) {
                $('.btn-insert').css({
                    "pointer-events": "auto",
                    "opacity": "1",
                    "cursor": "pointer"
                });

                modal.close();
                setTimeout(() => {
                    alert("Ya están todos los productos asignados, inserta por favor.");
                }, 0);
            } else {
                $('.btn-insert').css({
                    "pointer-events": "none",
                    "opacity": "0.9",
                    "cursor": "not-allowed"
                });
            }

            alert("Se asigno con existo.");
            return;

        },
        error: function () {
            console.log('error');
        }
    });
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
    $('.content_alta').hide();

    productos_xml.forEach(function(producto) {
        if(producto.indice === indice){
            valores_formulario_producto(producto);
        }
    });
    producto_xml_actual = indice;

    $('#producto_asignado').val('');
    let existente = asignaciones.find(item =>
        item.producto_xml === producto_xml_actual
    );

    if (existente) {
        let existente_prod = productos_completos.find(item =>
            item.inm_producto_id === existente.inm_producto_id
        );

        if(existente_prod){
            $('#producto_asignado').val(existente_prod.inm_producto_descripcion);
        }
    }

    $('input[name="producto"]:checked').prop('checked', false);

    currentPage = 1;
    renderTable_productos_completos(currentPage);
    renderPagination_productos_completos();
}

$('#anterior').on('click', function() {
    if (producto_xml_actual > 0) {
        abrir_modal(producto_xml_actual - 1);
    }
});

$('#siguiente').on('click', function() {
    if (producto_xml_actual <= productos_xml.length - 1) {
        abrir_modal(producto_xml_actual + 1);
    }
});

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
            $('#producto_asignado').val(producto.inm_producto_descripcion);
            checkbox.prop("checked", true);
        }

        tr.append($('<td>').append(checkbox));
        tr.append($('<td>').text(producto.inm_producto_id));
        tr.append($('<td>').text(producto.inm_producto_descripcion));
        tbody.append(tr);
    });

    $('.producto-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            let existente_prod = productos_completos.find(item =>
                item.inm_producto_id === $(this).val()
            );

            if(existente_prod){
                $('#producto_asignado').val(existente_prod.inm_producto_descripcion);
            }

            $('.producto-checkbox').not(this).prop('checked', false);
            if ($(this).val() === '-1') {
                $('.content_alta').show();
            }
        }else{
            $('#producto_asignado').val('');
            $('.content_alta').hide();
            if ($(this).val() === '-1') {
                $('.content_alta').hide();
            }
        }
    });
}

/*function renderPagination() {
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
            $('#producto_asignado').val('');
            $(chk).prop("checked", false);
            $('.content_alta').hide();
        }

        currentPage = parseInt($(this).attr('data-page'));
        renderTable(currentPage);
        renderPagination();
    });
}*/

function renderPagination() {

    let totalPages = Math.ceil(productos.length / rowsPerPage);
    let pagination = $('#pagination');
    pagination.empty();

    let limite = 2; // paginas antes y despues
    let start = Math.max(1, currentPage - limite);
    let end = Math.min(totalPages, currentPage + limite);

    // BOTON ANTERIOR
    if (currentPage > 1) {
        pagination.append(
            $('<button>')
                .text('Anterior')
                .addClass('btn btn-sm btn-secondary mx-1')
                .click(function () {
                    currentPage--;
                    renderTable(currentPage);
                    renderPagination();
                })
        );
    }

    // PRIMERA PAGINA
    if (start > 1) {
        pagination.append(createPageButton(1));
        pagination.append($('<span>').text('...'));
    }

    // PAGINAS CENTRALES
    for (let i = start; i <= end; i++) {
        pagination.append(createPageButton(i));
    }

    // ULTIMA PAGINA
    if (end < totalPages) {
        pagination.append($('<span>').text('...'));
        pagination.append(createPageButton(totalPages));
    }

    // BOTON SIGUIENTE
    if (currentPage < totalPages) {
        pagination.append(
            $('<button>')
                .text('Siguiente')
                .addClass('btn btn-sm btn-secondary mx-1')
                .click(function () {
                    currentPage++;
                    renderTable(currentPage);
                    renderPagination();
                })
        );
    }
}

function createPageButton(page) {

    let btn = $('<button>')
        .text(page)
        .addClass('page-btn btn btn-sm btn-primary mx-1')
        .attr('data-page', page);

    if (page === currentPage) {
        btn.addClass('active');
    }

    btn.on('click', function () {

        let chk = document.querySelector('input[name="producto"]:checked');
        if (chk) {
            $('#producto_asignado').val('');
            $(chk).prop("checked", false);
            $('.content_alta').hide();
        }

        currentPage = page;
        renderTable(currentPage);
        renderPagination();
    });

    return btn;
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
            $('#producto_asignado').val(producto.inm_producto_descripcion);
            checkbox.prop("checked", true);
        }

        tr.append($('<td>').append(checkbox));
        tr.append($('<td>').text(producto.inm_producto_id));
        tr.append($('<td>').text(producto.inm_producto_descripcion));
        tbody.append(tr);
    });

    $('.producto-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            let existente_prod = productos_completos.find(item =>
                item.inm_producto_id === $(this).val()
            );

            if(existente_prod){
                $('#producto_asignado').val(existente_prod.inm_producto_descripcion);
            }

            $('.producto-checkbox').not(this).prop('checked', false);
            if ($(this).val() === '-1') {
                let sl_inm_concepto_id = $('#inm_concepto_id');

                sl_inm_concepto_id.val('-1');
                sl_inm_concepto_id.selectpicker('refresh');
                
                $('.content_alta').show();
            }
        }else{
            $('#producto_asignado').val('');
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
            $('#producto_asignado').val('');
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