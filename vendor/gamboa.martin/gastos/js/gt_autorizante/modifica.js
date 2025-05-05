$(document).ready(function () {

    let registro_id = getParameterByName('registro_id');
    let pr_procesos = $("#pr_procesos");
    let procesos_seleccionados = [];


    const columns_pr_proceso = [
        {
            title: "Id",
            data: "pr_proceso_id"
        },
        {
            title: "Tipo",
            data: "pr_proceso_descripcion"
        }
    ];

    const table_pr_proceso = table('pr_proceso', columns_pr_proceso, [], [], function () {}, true);


    $("#table-pr_proceso").on('click', 'thead:first-child, tbody', function (e) {
        let timer = null;

        clearTimeout(timer);

        timer = setTimeout(() => {
            let selectedData = table_pr_proceso.rows({selected: true}).data();

            procesos_seleccionados = [];

            selectedData.each(function (value, index, data) {
                procesos_seleccionados.push(value.pr_proceso_id);
            });

            $('#pr_procesos').val(procesos_seleccionados);
        }, 500);
    });

    $("#form_gt_autorizante_alta").on('submit', function (e) {
        if (procesos_seleccionados.length === 0) {
            e.preventDefault();
            alert("Seleccione como mínimo un proceso para el autorizante");
        }
    });

    table_pr_proceso.on('init', function () {
        let url = get_url("gt_autorizante", "get_autorizante", {gt_autorizante_id: registro_id}, 0);

        get_data(url, (data) => {
            let autorizante = data.registros[0];
            let procesos = table_pr_proceso.rows().data().toArray();

            if (autorizante.gt_autorizante_puede_hacer_cotizaciones == 1) {
                let filaProceso = procesos.findIndex(proceso => proceso.pr_proceso_descripcion === "COTIZACION");
                table_pr_proceso.row(`:eq(${filaProceso})`, { page: 'current' }).select();
            }

            if (autorizante.gt_autorizante_puede_hacer_ordenes == 1) {
                let filaProceso = procesos.findIndex(proceso => proceso.pr_proceso_descripcion === "ORDEN COMPRA");
                table_pr_proceso.row(`:eq(${filaProceso})`, { page: 'current' }).select();
            }

            if (autorizante.gt_autorizante_puede_hacer_requisiciones == 1) {
                let filaProceso = procesos.findIndex(proceso => proceso.pr_proceso_descripcion === "REQUISICION");
                table_pr_proceso.row(`:eq(${filaProceso})`, { page: 'current' }).select();
            }

            if (autorizante.gt_autorizante_puede_hacer_solicitudes == 1) {
                let filaProceso = procesos.findIndex(proceso => proceso.pr_proceso_descripcion === "SOLICITUD");
                table_pr_proceso.row(`:eq(${filaProceso})`, { page: 'current' }).select();
            }

        });
    });

});





