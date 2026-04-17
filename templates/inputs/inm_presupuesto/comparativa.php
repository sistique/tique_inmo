<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_presupuesto $controlador  controlador en ejecucion */ ?>

<div class="container-fluid">
    <h3 class="mb-3"><i class="bi bi-bar-chart-fill"></i> Comparativa Presupuesto vs Real
        — <?php echo $controlador->anio_filtro; ?>
        <?php echo $controlador->mes_filtro > 0 ? '/ Mes ' . $controlador->mes_filtro : '(Anual)'; ?>
    </h3>

    <!-- Filtros -->
    <form method="get" class="row mb-4">
        <input type="hidden" name="seccion" value="inm_presupuesto">
        <input type="hidden" name="accion" value="comparativa">
        <input type="hidden" name="session_id" value="<?php echo $_GET['session_id'] ?? ''; ?>">
        <div class="col-md-3">
            <label class="form-label">Año</label>
            <input type="number" name="anio" class="form-control"
                   value="<?php echo $controlador->anio_filtro; ?>" min="2020" max="2040">
        </div>
        <div class="col-md-3">
            <label class="form-label">Mes (0 = todos)</label>
            <input type="number" name="mes" class="form-control"
                   value="<?php echo $controlador->mes_filtro; ?>" min="0" max="12">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Filtrar
            </button>
        </div>
    </form>

    <?php if(is_array($controlador->comparativo) && count($controlador->comparativo) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Categoría</th>
                    <th>Tipo</th>
                    <th>Año</th>
                    <th>Mes</th>
                    <th class="text-end">Presupuestado</th>
                    <th class="text-end">Real</th>
                    <th class="text-end">Diferencia</th>
                    <th class="text-end">% Cumplimiento</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_presupuestado = 0;
                $total_real = 0;
                foreach($controlador->comparativo as $row):
                    $presupuestado = (float)$row['inm_presupuesto_monto_proyectado'];
                    $real = (float)$row['inm_presupuesto_monto_real'];
                    $diferencia = (float)$row['inm_presupuesto_diferencia'];
                    $pct = (float)$row['inm_presupuesto_pct_cumplimiento'];
                    $total_presupuestado += $presupuestado;
                    $total_real += $real;
                    $color = $diferencia >= 0 ? 'text-success' : 'text-danger';
                    $tipo = $row['inm_presupuesto_es_ingreso'] === 'activo' ? 'INGRESO' : 'EGRESO';
                    $badge = $row['inm_presupuesto_es_ingreso'] === 'activo' ?
                        '<span class="badge bg-success">INGRESO</span>' :
                        '<span class="badge bg-danger">EGRESO</span>';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['inm_categoria_financiera_descripcion']); ?></td>
                    <td><?php echo $badge; ?></td>
                    <td><?php echo $row['inm_presupuesto_anio']; ?></td>
                    <td><?php echo $row['inm_presupuesto_mes']; ?></td>
                    <td class="text-end">$<?php echo number_format($presupuestado, 2); ?></td>
                    <td class="text-end">$<?php echo number_format($real, 2); ?></td>
                    <td class="text-end <?php echo $color; ?>">
                        $<?php echo number_format($diferencia, 2); ?>
                    </td>
                    <td class="text-end">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar <?php echo $pct > 100 ? 'bg-danger' : 'bg-success'; ?>"
                                 style="width: <?php echo min($pct, 100); ?>%">
                                <?php echo $pct; ?>%
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="table-secondary fw-bold">
                <tr>
                    <td colspan="4">TOTALES</td>
                    <td class="text-end">$<?php echo number_format($total_presupuestado, 2); ?></td>
                    <td class="text-end">$<?php echo number_format($total_real, 2); ?></td>
                    <td class="text-end <?php echo ($total_presupuestado - $total_real) >= 0 ? 'text-success' : 'text-danger'; ?>">
                        $<?php echo number_format($total_presupuestado - $total_real, 2); ?>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No hay datos de presupuesto para el periodo seleccionado.
    </div>
    <?php endif; ?>
</div>

