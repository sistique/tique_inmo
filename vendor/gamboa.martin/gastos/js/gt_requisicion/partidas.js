let btn_alta_producto = $("#btn-alta-producto");

let sl_com_producto = $("#com_producto_id");
let sl_cat_sat_unidad = $("#cat_sat_unidad_id");

let txt_cantidad = $("#cantidad");
let txt_precio = $("#precio");

let registro_id = getParameterByName('registro_id');

btn_alta_producto.click(function () {

    let producto = sl_com_producto.find('option:selected').val();
    let unidad = sl_cat_sat_unidad.find('option:selected').val();
    let cantidad = txt_cantidad.val();
    let precio = txt_precio.val();

    if (producto === "") {
        alert("Seleccione un producto");
        return;
    }

    if (unidad === "") {
        alert("Seleccione una unidad");
        return;
    }

    if (cantidad === "") {
        alert("Ingrese una cantidad");
        return;
    }

    if (precio === "") {
        alert("Ingrese un precio");
        return;
    }

    let url = get_url("gt_requisicion_producto", "alta_bd", {});

    $.ajax({
        url: url,
        data: {com_producto_id: producto, cat_sat_unidad_id: unidad, cantidad: cantidad, precio: precio, gt_requisicion_id: registro_id},
        type: 'POST',
        success: function (json) {
            sl_com_producto.val('').change();
            sl_cat_sat_unidad.val('').change();
            txt_cantidad.val('');
            txt_precio.val('');

            if (json.hasOwnProperty("error")) {
                alert(json.mensaje_limpio)
                return;
            }

            $('#table-productos').DataTable().clear().destroy();
            main_productos('gt_requisicion_producto', 'productos');
        },
        error: function (xhr, status) {
            alert('Error, ocurrio un error al ejecutar la peticion');
            console.log({xhr, status})
        }
    });
});

const main_productos = (seccion, identificador) => {
    const ruta_load = get_url(seccion, "data_ajax", {ws: 1});


    return new DataTable(`#table-${identificador}`, {
        dom: 'Bfrtip',
        retrieve: true,
        ajax: {
            "url": ruta_load,
            'data': function (data) {
                data.filtros = {
                    filtro: [{
                        "key": "gt_requisicion.id",
                        "valor": registro_id
                    }]
                }
            },
            "error": function (jqXHR, textStatus, errorThrown) {
                let response = jqXHR.responseText;
                console.log(response)
            }
        },
        columns: [
            {title: 'Id', data: `${seccion}_id`},
            {title: 'Producto', data: `com_producto_descripcion`},
            {title: 'Unidad', data: `cat_sat_unidad_descripcion`},
            {title: 'Cantidad', data: `${seccion}_cantidad`},
            {title: 'Precio', data: `${seccion}_precio`},
            {title: 'Total', data: null},
            {title: 'Acciones', data: null},
        ],
        columnDefs: [
            {
                targets: 5,
                render: function (data, type, row, meta) {
                    return Number(row[`gt_requisicion_producto_cantidad`] * row[`gt_requisicion_producto_precio`]).toFixed(2);
                }
            },
            {
                targets: 6,
                render: function (data, type, row, meta) {
                    let seccion = getParameterByName('seccion');
                    let accion = getParameterByName('accion');
                    let registro_id = getParameterByName('registro_id');

                    let url = $(location).attr('href');
                    url = url.replace(accion, "elimina_bd");
                    url = url.replace(seccion, `gt_requisicion_producto`);
                    url = url.replace(registro_id, row[`gt_requisicion_producto_id`]);
                    return `<button  data-url="${url}" class="btn btn-danger btn-sm">Elimina</button>`;
                }
            }
        ]
    });
}

const main = (seccion, identificador) => {
    const ruta_load = get_url(seccion, "data_ajax", {ws: 1});


    let table = new DataTable(`#table-${identificador}`, {
        dom: 'Bfrtip',
        retrieve: true,
        ajax: {
            "url": ruta_load,
            'data': function (data) {
                data.filtros = {
                    filtro: [{
                        "key": "gt_requisicion.id",
                        "valor": registro_id
                    }]
                }
            },
            "error": function (jqXHR, textStatus, errorThrown) {
                let response = jqXHR.responseText;
                console.log(response)
            }
        },
        columns: [
            {title: 'Id', data: `gt_${identificador}_id`},
            {title: identificador, data: 'em_empleado_nombre'},
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, row, meta) {
                    return `${row.em_empleado_ap} ${row.em_empleado_am} ${row.em_empleado_nombre}`;
                }
            }
        ]
    });

    return table;
}

const table_1 = main_productos('gt_requisicion_producto', 'productos');
const table_2 = main('gt_requisitores', 'requisitor');

table_1.on('click', 'button', function (e) {
    const url = $(this).data("url");

    $.ajax({
        url: url,
        type: 'POST',
        success: function (json) {
            if (json.includes('error')) {
                alert("Error al eliminar el regstro")
                return;
            }

            $('#table-productos').DataTable().clear().destroy();
            main_productos('gt_solicitud_producto', 'productos');
        },
        error: function (xhr, status) {
            alert('Error, ocurrio un error al ejecutar la peticion');
            console.log({xhr, status})
        }
    });
});

