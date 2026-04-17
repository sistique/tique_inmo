<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_presupuesto $controlador  controlador en ejecucion */ ?>
<?php $d = $controlador->dashboard_data; ?>

<div class="container-fluid">
    <h3 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard Financiero — <?php echo $d->anio; ?></h3>

    <!-- Filtro de año -->
    <form method="get" class="row mb-4">
        <input type="hidden" name="seccion" value="inm_presupuesto">
        <input type="hidden" name="accion" value="dashboard">
        <input type="hidden" name="session_id" value="<?php echo $_GET['session_id'] ?? ''; ?>">
        <div class="col-md-2">
            <input type="number" name="anio" class="form-control" value="<?php echo $d->anio; ?>" min="2020" max="2040">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Cambiar Año</button>
        </div>
    </form>

    <!-- KPIs del mes actual -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Ingresos Reales - <?php echo $d->nombre_mes_actual; ?></h6>
                    <h3 class="text-success">$<?php echo number_format($d->totales_reales->total_ingresos, 2); ?></h3>
                    <small class="text-muted">Proyectado: $<?php echo number_format($d->flujo_proyectado->total_ingresos_proyectados, 2); ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Egresos Reales - <?php echo $d->nombre_mes_actual; ?></h6>
                    <h3 class="text-danger">$<?php echo number_format($d->totales_reales->total_egresos, 2); ?></h3>
                    <small class="text-muted">Proyectado: $<?php echo number_format($d->flujo_proyectado->total_egresos_proyectados, 2); ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Flujo Neto - <?php echo $d->nombre_mes_actual; ?></h6>
                    <h3 class="<?php echo $d->totales_reales->flujo_neto >= 0 ? 'text-success' : 'text-danger'; ?>">
                        $<?php echo number_format($d->totales_reales->flujo_neto, 2); ?>
                    </h3>
                    <small class="text-muted">Proyectado: $<?php echo number_format($d->flujo_proyectado->flujo_neto_proyectado, 2); ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Disponible para Gastar</h6>
                    <h3 class="<?php echo $d->disponible_mes >= 0 ? 'text-info' : 'text-danger'; ?>">
                        $<?php echo number_format($d->disponible_mes, 2); ?>
                    </h3>
                    <small class="text-muted">Presup. egresos - Egresos reales</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Saldo Acumulado -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-wallet2"></i> Saldo Acumulado <?php echo $d->anio; ?>
                </div>
                <div class="card-body">
                    <h4 class="<?php echo $d->saldo_acumulado->saldo_acumulado >= 0 ? 'text-success' : 'text-danger'; ?>">
                        Saldo al cierre de <?php echo $d->nombre_mes_actual; ?>:
                        $<?php echo number_format($d->saldo_acumulado->saldo_acumulado, 2); ?>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla resumen mensual -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-table"></i> Flujo de Efectivo Mensual <?php echo $d->anio; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Mes</th>
                                    <th class="text-end">Ing. Proyectado</th>
                                    <th class="text-end">Ing. Real</th>
                                    <th class="text-end">Egr. Proyectado</th>
                                    <th class="text-end">Egr. Real</th>
                                    <th class="text-end">Flujo Proyectado</th>
                                    <th class="text-end">Flujo Real</th>
                                    <th class="text-end">Desviación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($d->datos_mensuales as $dm):
                                    $desviacion = $dm['flujo_real'] - $dm['flujo_proyectado'];
                                    $color_desv = $desviacion >= 0 ? 'text-success' : 'text-danger';
                                    $es_actual = $dm['mes'] === $d->mes_actual;
                                ?>
                                <tr class="<?php echo $es_actual ? 'table-active fw-bold' : ''; ?>">
                                    <td><?php echo $dm['nombre_mes']; ?> <?php echo $es_actual ? '⬅' : ''; ?></td>
                                    <td class="text-end">$<?php echo number_format($dm['ingresos_proyectados'], 2); ?></td>
                                    <td class="text-end">$<?php echo number_format($dm['ingresos_reales'], 2); ?></td>
                                    <td class="text-end">$<?php echo number_format($dm['egresos_proyectados'], 2); ?></td>
                                    <td class="text-end">$<?php echo number_format($dm['egresos_reales'], 2); ?></td>
                                    <td class="text-end">$<?php echo number_format($dm['flujo_proyectado'], 2); ?></td>
                                    <td class="text-end">$<?php echo number_format($dm['flujo_real'], 2); ?></td>
                                    <td class="text-end <?php echo $color_desv; ?>">
                                        $<?php echo number_format($desviacion, 2); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfica con Chart.js -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-graph-up"></i> Gráfica Comparativa Mensual
                </div>
                <div class="card-body">
                    <canvas id="chartComparativa" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfica de pie: distribución egresos por categoría -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-pie-chart"></i> Egresos por Categoría (Mes Actual)
                </div>
                <div class="card-body">
                    <canvas id="chartEgresosCat" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-pie-chart"></i> Ingresos por Categoría (Mes Actual)
                </div>
                <div class="card-body">
                    <canvas id="chartIngresosCat" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota sobre CONTPAQi -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-info">
                <div class="card-header bg-light">
                    <i class="bi bi-link-45deg"></i> Integración Futura con CONTPAQi
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Estructura preparada para exportación contable:</strong></p>
                    <ul class="mb-0">
                        <li>Cada <code>inm_categoria_financiera</code> puede mapearse a una cuenta contable del catálogo SAT/CONTPAQi</li>
                        <li>Los movimientos reales (<code>inm_mov_real</code>) contienen fecha, monto, referencia y concepto → compatible con pólizas de diario</li>
                        <li>El campo <code>es_ingreso</code> distingue naturaleza de cargo/abono para la partida doble</li>
                        <li>Se puede agregar un campo <code>cuenta_contable</code> en <code>inm_categoria_financiera</code> para el mapeo directo</li>
                        <li>Exportación via API XML de CONTPAQi o archivo de pólizas (.pol)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Datos para la gráfica de barras comparativa
const meses = [<?php echo implode(',', array_map(function($dm){ return "'" . $dm['nombre_mes'] . "'"; }, $d->datos_mensuales)); ?>];
const ingProy = [<?php echo implode(',', array_column($d->datos_mensuales, 'ingresos_proyectados')); ?>];
const ingReal = [<?php echo implode(',', array_column($d->datos_mensuales, 'ingresos_reales')); ?>];
const egrProy = [<?php echo implode(',', array_column($d->datos_mensuales, 'egresos_proyectados')); ?>];
const egrReal = [<?php echo implode(',', array_column($d->datos_mensuales, 'egresos_reales')); ?>];

new Chart(document.getElementById('chartComparativa'), {
    type: 'bar',
    data: {
        labels: meses,
        datasets: [
            { label: 'Ingresos Proyectados', data: ingProy, backgroundColor: 'rgba(40,167,69,0.3)', borderColor: '#28a745', borderWidth: 1 },
            { label: 'Ingresos Reales', data: ingReal, backgroundColor: 'rgba(40,167,69,0.8)', borderColor: '#28a745', borderWidth: 1 },
            { label: 'Egresos Proyectados', data: egrProy, backgroundColor: 'rgba(220,53,69,0.3)', borderColor: '#dc3545', borderWidth: 1 },
            { label: 'Egresos Reales', data: egrReal, backgroundColor: 'rgba(220,53,69,0.8)', borderColor: '#dc3545', borderWidth: 1 }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '$' + v.toLocaleString() } } }
    }
});

// Gráficas de pie — se llenan con datos del comparativo anual si existen
<?php
    $cat_egresos = array();
    $cat_ingresos = array();
    if(is_array($d->comparativo_anual)){
        foreach($d->comparativo_anual as $row){
            $cat = $row['inm_categoria_financiera_descripcion'] ?? 'Sin Categoría';
            $monto_real = (float)($row['inm_presupuesto_monto_real'] ?? 0);
            if($row['inm_presupuesto_es_ingreso'] === 'activo'){
                $cat_ingresos[$cat] = ($cat_ingresos[$cat] ?? 0) + $monto_real;
            } else {
                $cat_egresos[$cat] = ($cat_egresos[$cat] ?? 0) + $monto_real;
            }
        }
    }
?>

const coloresPie = ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#C9CBCF','#7BC67E','#E74C3C','#3498DB'];

new Chart(document.getElementById('chartEgresosCat'), {
    type: 'doughnut',
    data: {
        labels: [<?php echo implode(',', array_map(function($k){ return "'" . addslashes($k) . "'"; }, array_keys($cat_egresos))); ?>],
        datasets: [{ data: [<?php echo implode(',', array_values($cat_egresos)); ?>], backgroundColor: coloresPie }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('chartIngresosCat'), {
    type: 'doughnut',
    data: {
        labels: [<?php echo implode(',', array_map(function($k){ return "'" . addslashes($k) . "'"; }, array_keys($cat_ingresos))); ?>],
        datasets: [{ data: [<?php echo implode(',', array_values($cat_ingresos)); ?>], backgroundColor: coloresPie }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>

