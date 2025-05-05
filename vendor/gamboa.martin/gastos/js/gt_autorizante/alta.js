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

});





