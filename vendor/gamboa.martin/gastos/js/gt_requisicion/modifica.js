$(document).ready(function () {
    let btn_alta_requisitor = $("#btn-alta-requisitor");
    let btn_alta_producto = $("#btn-alta-producto");

    let sl_gt_requisitor = $("#gt_requisitor_id");
    let sl_com_producto = $("#com_producto_id");
    let sl_cat_sat_unidad = $("#cat_sat_unidad_id");

    let txt_cantidad = $("#cantidad");
    let txt_precio = $("#precio");

    let registro_id = getParameterByName('registro_id');

    var tables = $.fn.dataTable.tables(true);
    var table_gt_requisicion_producto = $(tables).DataTable().search('gt_requisicion_producto');
    table_gt_requisicion_producto.search('').columns().search('').draw();

    const columns_gt_requisitores = [
        {
            title: "Id",
            data: `gt_requisitores_id`
        },
        {
            title: "Requisitor",
            data: 'em_empleado_nombre_completo'
        },
        {
            title: "Acciones",
            data: null
        }
    ];

    const filtro_gt_requisitores = [
        {
            "key": "gt_requisicion.id",
            "valor": registro_id
        }
    ];

    const columns_gt_cotizacion_requisicion = [
        {
            title: 'Id',
            data: `gt_cotizacion_id`
        },
        {
            title: 'Tipo',
            data: `gt_tipo_cotizacion_descripcion`
        },
        {
            title: 'Proveedor',
            data: `gt_proveedor_descripcion`
        },
        {
            title: 'Acciones',
            data: null
        }
    ];


    const filtro_gt_cotizacion_requisicion = [
        {
            "key": "gt_cotizacion_requisicion.gt_requisicion_id",
            "valor": registro_id
        }
    ];

    const callback_gt_cotizacion_requisicion = (seccion, columns) => {
        return [
            {
                targets: 3,
                render: function (data, type, row, meta) {
                    let sec = getParameterByName('seccion');
                    let acc = getParameterByName('accion');
                    let registro_id = getParameterByName('registro_id');

                    let url_elimina = $(location).attr('href');
                    url_elimina = url_elimina.replace(acc, "elimina_bd");
                    url_elimina = url_elimina.replace(sec, `gt_cotizacion_requisicion`);
                    url_elimina = url_elimina.replace(registro_id, row[`gt_cotizacion_requisicion_id`]);

                    let url_actualiza = $(location).attr('href');
                    url_actualiza = url_actualiza.replace(acc, "modifica");
                    url_actualiza = url_actualiza.replace(sec, "gt_cotizacion");
                    url_actualiza = url_actualiza.replace(registro_id, row[`gt_cotizacion_id`]);

                    let btn_actualiza = `<a href="${url_actualiza}" class="btn btn-warning btn-sm" style="margin: 0 15px;">Actualiza</a>`
                    let btn_elimina = `<button  data-url="${url_elimina}" class="btn btn-danger btn-sm">Elimina</button>`;

                    return `${btn_actualiza}${btn_elimina}`;
                }
            }
        ]
    }

    const table_gt_requisitores = table('gt_requisitores', columns_gt_requisitores, filtro_gt_requisitores);
    const table_gt_cotizacion_requisicion = table('gt_cotizacion_requisicion', columns_gt_cotizacion_requisicion,
        filtro_gt_cotizacion_requisicion, [], callback_gt_cotizacion_requisicion);

    const callback_data_requisitor = () => {
        let requisitor = sl_gt_requisitor.find('option:selected').val();

        if (requisitor === "") {
            alert("Seleccione un requisitor");
            return;
        }

        return {gt_requisitor_id: requisitor, gt_requisicion_id: registro_id};
    }

    const callback_respuesta_requisitor = () => {
        sl_gt_requisitor.val('').change();
        table_gt_requisitores.ajax.reload();
    }

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
            gt_requisicion_id: registro_id
        };
    }

    const callback_respuesta_producto = () => {
        sl_com_producto.val('').change();
        sl_cat_sat_unidad.val('').change();
        txt_cantidad.val('');
        txt_precio.val('');
        table_gt_requisicion_producto.ajax.reload();
    }

    alta_registro(btn_alta_requisitor, "gt_requisitores", callback_data_requisitor, callback_respuesta_requisitor);
    alta_registro(btn_alta_producto, "gt_requisicion_producto", callback_data_producto, callback_respuesta_producto);

    elimina_registro(table_gt_requisitores);
    elimina_registro(table_gt_cotizacion_requisicion);

    seleccionar_tabla('#gt_requisicion_producto', table_gt_requisicion_producto, '#agregar_producto', function (seleccionados) {
        alta_productos('#form-cotizacion', seleccionados);
    });

});













