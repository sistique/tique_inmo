$(document).ready(function () {

    let registro_id = getParameterByName('registro_id');

    const columns_gt_cotizacion = [
        {
            title: "Id",
            data: "gt_cotizacion_id"
        },
        {
            title: "Tipo",
            data: "gt_tipo_cotizacion_descripcion"
        },
        {
            title: "Cotización",
            data: "gt_cotizacion_descripcion"
        },
        {
            title: "Etapa",
            data: "gt_cotizacion_etapa"
        }

    ];

    const filtro_gt_cotizacion = [
        {
            "key": "gt_cotizacion.gt_proveedor_id",
            "valor": registro_id
        }
    ];

    const callback_gt_cotizacion = (seccion, columns) => {
        return [
            {
                targets: 3,
                render: function (data, type, row, meta) {
                    let etapa = row[`gt_cotizacion_etapa`];
                    let badge = 'primary';

                    if (etapa.toLowerCase() === 'autorizado') {
                        badge = 'success';
                    }

                    return `<span class="badge badge-pill badge-${badge}">${etapa.toLowerCase()}</span>`;
                }
            }
        ]
    }

    const table_gt_cotizacion = table('gt_cotizacion', columns_gt_cotizacion, filtro_gt_cotizacion, [], callback_gt_cotizacion);

    const url = get_url('gt_proveedor', 'api_sados_cotizacion', {registro_id: registro_id});

    ajax(url, function (result) {
        const data = {
            labels: result.labels,
            datasets: [
                {
                    label: 'Monto',
                    data: result.data
                }
            ]
        };

        const ctx = document.getElementById('saldos_cotizacion');

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Total de cotizaciones'
                    }
                }
            }
        });
    });

    const url2 = get_url('gt_proveedor', 'api_sados_orden_compra', {registro_id: registro_id});

    ajax(url2, function (result) {
        const data = {
            labels: result.labels,
            datasets: [
                {
                    label: 'Monto',
                    data: result.data
                }
            ]
        };

        const ctx = document.getElementById('saldos_orden_compra');

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Total de ordenes de compra'
                    }
                }
            }
        });
    });

});





