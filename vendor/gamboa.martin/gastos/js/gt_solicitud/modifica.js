$(document).ready(function () {

    let btn_alta_autorizante = $("#btn-alta-autorizante");
    let btn_alta_solicitante = $("#btn-alta-solicitante");
    let btn_alta_producto = $("#btn-alta-producto");

    let sl_gt_autorizante = $("#gt_autorizante_id");
    let sl_gt_soliciante = $("#gt_solicitante_id");

    let sl_com_producto = $("#com_producto_id");
    let sl_cat_sat_unidad = $("#cat_sat_unidad_id");

    let txt_cantidad = $("#cantidad");
    let txt_precio = $("#precio");

    let registro_id = getParameterByName('registro_id');

    var tables = $.fn.dataTable.tables(true);
    var table_gt_solicitud_producto = $(tables).DataTable().search('gt_solicitud_producto');
    table_gt_solicitud_producto.search('').columns().search('').draw();


    const columns_gt_autorizantes = [
        {
            title: "Id",
            data: `gt_autorizantes_id`
        },
        {
            title: "Autorizante",
            data: 'em_empleado_nombre_completo'
        },
        {
            title: "Acciones",
            data: null
        }
    ];

    const columns_gt_solicitantes = [
        {
            title: "Id",
            data: `gt_solicitantes_id`
        },
        {
            title: "Solicitante",
            data: 'em_empleado_nombre_completo'
        },
        {
            title: "Acciones",
            data: null
        }
    ];

    const filtro_gt_autorizantes = [
        {
            "key": "gt_solicitud.id",
            "valor": registro_id
        }
    ];

    const columns_gt_solicitud_requisicion = [
        {
            title: 'Id',
            data: `gt_requisicion_id`
        },
        {
            title: 'Requisición',
            data: `gt_requisicion_descripcion`
        },
        {
            title: 'Acciones',
            data: null
        }
    ];

    const filtro_gt_solicitud_requisicion = [
        {
            "key": "gt_solicitud_requisicion.gt_solicitud_id",
            "valor": registro_id
        }
    ];

    const callback_gt_solicitud_requisicion = (seccion, columns) => {
        return [
            {
                targets: 2,
                render: function (data, type, row, meta) {


                    let btn_actualiza = `<a href="${url_actualiza}" class="btn btn-warning btn-sm" style="margin: 0 15px;">Actualiza</a>`
                    let btn_elimina = `<button  data-url="${url_elimina}" class="btn btn-danger btn-sm">Elimina</button>`;

                    return `${btn_actualiza}${btn_elimina}`;
                }
            }
        ]
    }

    const table_gt_autorizantes = table('gt_autorizantes', columns_gt_autorizantes, filtro_gt_autorizantes);
    const table_gt_solicitantes = table('gt_solicitantes', columns_gt_solicitantes, filtro_gt_autorizantes);
    const table_gt_solicitud_requisicion = table('gt_solicitud_requisicion', columns_gt_solicitud_requisicion,
        filtro_gt_solicitud_requisicion, [], callback_gt_solicitud_requisicion);

    const callback_data_autorizantes = () => {
        let autorizante = sl_gt_autorizante.find('option:selected').val();

        if (autorizante === "") {
            alert("Seleccione un autorizante");
            return;
        }

        return {gt_autorizante_id: autorizante, gt_solicitud_id: registro_id};
    }

    const callback_respuesta_autorizantes = () => {
        sl_gt_autorizante.val('').change();
        table_gt_autorizantes.ajax.reload();
    }

    const callback_data_solicitantes = () => {
        let solicitante = sl_gt_soliciante.find('option:selected').val();

        if (solicitante === "") {
            alert("Seleccione un solicitante");
            return;
        }

        return {gt_solicitante_id: solicitante, gt_solicitud_id: registro_id};
    }

    const callback_respuesta_solicitantes = () => {
        sl_gt_soliciante.val('').change();
        table_gt_solicitantes.ajax.reload();
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
            gt_solicitud_id: registro_id
        };
    }

    const callback_respuesta_producto = () => {
        sl_com_producto.val('').change();
        sl_cat_sat_unidad.val('').change();
        txt_cantidad.val('');
        txt_precio.val('');
        table_gt_solicitud_producto.ajax.reload();
    }

    alta_registro(btn_alta_autorizante, "gt_autorizantes", callback_data_autorizantes, callback_respuesta_autorizantes);
    alta_registro(btn_alta_solicitante, "gt_solicitantes", callback_data_solicitantes, callback_respuesta_solicitantes);
    alta_registro(btn_alta_producto, "gt_solicitud_producto", callback_data_producto, callback_respuesta_producto);

    elimina_registro(table_gt_autorizantes);
    elimina_registro(table_gt_solicitantes);
    elimina_registro(table_gt_solicitud_requisicion);

    seleccionar_tabla('#gt_solicitud_producto', table_gt_solicitud_producto, '#agregar_producto', function (seleccionados) {
        alta_productos('#form-requisicion', seleccionados);
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

        let url = get_url("gt_solicitud_producto", "get_precio_promedio", {com_producto_id: selected.val()}, 0);

        getData(url, (data) => {
            txt_precio.val('');

            if (data.n_registros > 0) {
                let total = 0.0;
                $.each(data.registros, function (index, registro) {
                    total += parseFloat(registro.gt_solicitud_producto_precio);
                });

                let promedio = total / data.n_registros;
                txt_precio.val(promedio.toFixed(3));
            }
        });

    });
});





