$(document).ready(function () {
    let btn_alta_producto = $("#btn-alta-producto");

    let sl_com_producto = $("#com_producto_id");
    let sl_cat_sat_unidad = $("#cat_sat_unidad_id");

    let txt_cantidad = $("#cantidad");
    let txt_precio = $("#precio");

    let registro_id = getParameterByName('registro_id');

    var productos_seleccionados = [];

    var tables = $.fn.dataTable.tables(true);
    var table_gt_cotizacion_producto = $(tables).DataTable().search('gt_cotizacion_producto');
    table_gt_cotizacion_producto.search('').columns().search('').draw();

    const columns_gt_orden_compra_cotizacion = [
        {
            title: 'Id',
            data: `gt_orden_compra_id`
        },
        {
            title: 'Tipo',
            data: `gt_tipo_orden_compra_descripcion`
        },
        {
            title: 'Orden Compra',
            data: `gt_orden_compra_descripcion`
        },
        {
            title: 'Acciones',
            data: null
        }
    ];

    const filtro_gt_orden_compra_cotizacion = [
        {
            "key": "gt_orden_compra_cotizacion.gt_cotizacion_id",
            "valor": registro_id
        }
    ];

    const callback_orden_compra_cotizacion = (seccion, columns) => {
        return [
            {
                targets: 3,
                render: function (data, type, row, meta) {
                    let sec = getParameterByName('seccion');
                    let acc = getParameterByName('accion');
                    let registro_id = getParameterByName('registro_id');

                    let url_elimina = $(location).attr('href');
                    url_elimina = url_elimina.replace(acc, "elimina_bd");
                    url_elimina = url_elimina.replace(sec, `gt_orden_compra_cotizacion`);
                    url_elimina = url_elimina.replace(registro_id, row[`gt_orden_compra_cotizacion_id`]);

                    let url_actualiza = $(location).attr('href');
                    url_actualiza = url_actualiza.replace(acc, "modifica");
                    url_actualiza = url_actualiza.replace(sec, "gt_orden_compra");
                    url_actualiza = url_actualiza.replace(registro_id, row[`gt_orden_compra_id`]);

                    let btn_actualiza = `<a href="${url_actualiza}" class="btn btn-warning btn-sm" style="margin: 0 15px;">Actualiza</a>`
                    let btn_elimina = `<button  data-url="${url_elimina}" class="btn btn-danger btn-sm">Elimina</button>`;

                    return `${btn_actualiza}${btn_elimina}`;
                }
            }
        ]
    }

    const table_gt_orden_compra_cotizacion = table('gt_orden_compra_cotizacion', columns_gt_orden_compra_cotizacion,
        filtro_gt_orden_compra_cotizacion, [], callback_orden_compra_cotizacion);

    const callback_data_producto = () => {
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

        return {
            com_producto_id: producto,
            cat_sat_unidad_id: unidad,
            cantidad: cantidad,
            precio: precio,
            gt_cotizacion_id: registro_id
        };
    }

    const callback_respuesta_producto = () => {
        sl_com_producto.val('').change();
        sl_cat_sat_unidad.val('').change();
        txt_cantidad.val('');
        txt_precio.val('');
        table_gt_cotizacion_producto.ajax.reload();
    }

    alta_registro(btn_alta_producto, "gt_cotizacion_producto", callback_data_producto, callback_respuesta_producto);

    elimina_registro(table_gt_orden_compra_cotizacion);

    seleccionar_tabla('#gt_cotizacion_producto', table_gt_cotizacion_producto, '#agregar_producto', function (seleccionados) {
        alta_productos('#form-orden', seleccionados);
    });

    let getData = async (url, acciones) => {
        fetch(url)
            .then(response => response.json())
            .then(data => acciones(data))
            .catch(err => {
                alert('Error al ejecutar');
                console.error("ERROR: ", err.message)
            });
    }

    sl_com_producto.change(function () {
        let selected = $(this).find('option:selected');

        let url = get_url("gt_cotizacion_producto", "get_precio_promedio", {com_producto_id: selected.val()}, 0);

        getData(url, (data) => {
            txt_precio.val('');

            if (data.n_registros > 0) {
                txt_precio.val(data.registros[data.n_registros - 1].gt_cotizacion_producto_precio);
            }
        });

    });

});





