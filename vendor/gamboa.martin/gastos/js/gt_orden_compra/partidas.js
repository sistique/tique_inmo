let registro_id = getParameterByName('registro_id');
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
                        "key": "gt_orden_compra_cotizacion.gt_cotizacion_id",
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
            {title: 'Orden Compra', data: `gt_orden_compra_descripcion`},
            {title: 'Producto', data: `com_producto_descripcion`},
            {title: 'Unidad', data: `cat_sat_unidad_descripcion`},
            {title: 'Cantidad', data: `gt_cotizacion_producto_cantidad`},
            {title: 'Precio', data: `gt_cotizacion_producto_precio`},
            {title: 'Total', data: null},
            {title: 'Acciones', data: null},
        ],
        columnDefs: [
            {
                targets: 6,
                render: function (data, type, row, meta) {
                    return Number(row[`gt_cotizacion_producto_cantidad`] * row[`gt_cotizacion_producto_precio`]).toFixed(2);
                }
            },
            {
                targets: 7,
                render: function (data, type, row, meta) {
                    let seccion = getParameterByName('seccion');
                    let accion = getParameterByName('accion');
                    let registro_id = getParameterByName('registro_id');

                    let url = $(location).attr('href');
                    url = url.replace(accion, "elimina_bd");
                    url = url.replace(seccion, `gt_cotizacion_producto`);
                    url = url.replace(registro_id, row[`gt_cotizacion_producto_id`]);
                    return `<button  data-url="${url}" class="btn btn-danger btn-sm">Elimina</button>`;
                }
            }
        ]
    });
}


const table_1 = main_productos('gt_orden_compra_cotizacion', 'productos');

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
            main_productos('gt_cotizacion_producto', 'productos');
        },
        error: function (xhr, status) {
            alert('Error, ocurrio un error al ejecutar la peticion');
            console.log({xhr, status})
        }
    });
});

