$(document).ready(function () {

    let btn_alta_producto = $("#btn-alta-producto");

    let sl_gt_cotizacion = $("#gt_cotizacion_id");

    let sl_com_producto = $("#com_producto_id");
    let sl_cat_sat_unidad = $("#cat_sat_unidad_id");

    let txt_cantidad = $("#cantidad");
    let txt_precio = $("#precio");

    let registro_id = getParameterByName('registro_id');

    var productos_seleccionados = [];

    var tables = $.fn.dataTable.tables(true);
    var table_gt_orden_compra_producto = $(tables).DataTable().search('gt_orden_compra_producto');
    table_gt_orden_compra_producto.search('').columns().search('').draw();

    const table = (seccion, columns, filtro = [], extra_join = []) => {
        const ruta_load = get_url(seccion, "data_ajax", {ws: 1});

        return new DataTable(`#table-${seccion}`, {
            dom: 'Bfrtip',
            retrieve: true,
            ajax: {
                "url": ruta_load,
                'data': function (data) {
                    data.filtros = {
                        filtro: filtro,
                        extra_join: extra_join
                    }
                },
                "error": function (jqXHR, textStatus, errorThrown) {
                    let response = jqXHR.responseText;
                    console.log(response)
                }
            },
            columns: columns,
            columnDefs: [
                {
                    targets: columns.length - 1,
                    render: function (data, type, row, meta) {
                        let sec = getParameterByName('seccion');
                        let acc = getParameterByName('accion');
                        let registro_id = getParameterByName('registro_id');

                        let url = $(location).attr('href');
                        url = url.replace(acc, "elimina_bd");
                        url = url.replace(sec, seccion);
                        url = url.replace(registro_id, row[`${seccion}_id`]);
                        return `<button  data-url="${url}" class="btn btn-danger btn-sm">Elimina</button>`;
                    }
                }
            ]
        });
    }

    const alta = (seccion, data = {}, acciones) => {
        let url = get_url(seccion, "alta_bd", {});

        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            success: function (json) {
                acciones();

                if (json.hasOwnProperty("error")) {
                    alert(json.mensaje_limpio)
                }
            },
            error: function (xhr, status) {
                alert('Error, ocurrio un error al ejecutar la peticion');
                console.log({xhr, status})
            }
        });
    }

    const eliminar = (url, acciones) => {
        $.ajax({
            url: url,
            type: 'POST',
            success: function (json) {
                acciones();

                if (json.includes('error')) {
                    alert("Error al eliminar el regstro")
                }
            },
            error: function (xhr, status) {
                alert('Error, ocurrio un error al ejecutar la peticion');
                console.log({xhr, status})
            }
        });
    }

    btn_alta_producto.click(function () {

        let cotizacion = sl_gt_cotizacion.find('option:selected').val();
        let producto = sl_com_producto.find('option:selected').val();
        let unidad = sl_cat_sat_unidad.find('option:selected').val();
        let cantidad = txt_cantidad.val();
        let precio = txt_precio.val();

        if (cotizacion === "") {
            alert("Seleccione una cotizacion");
            return;
        }

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

        let data = {
            com_producto_id: producto,
            cat_sat_unidad_id: unidad,
            cantidad: cantidad,
            precio: precio,
            gt_orden_compra_id: registro_id
        };

        alta("gt_orden_compra_producto", data, () => {
            sl_gt_cotizacion.val('').change();
            sl_com_producto.val('').change();
            sl_cat_sat_unidad.val('').change();
            txt_cantidad.val('');
            txt_precio.val('');

            table_gt_orden_compra_producto.ajax.reload();
        });
    });

});
