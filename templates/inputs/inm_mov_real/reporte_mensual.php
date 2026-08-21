<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_mov_real $controlador  controlador en ejecucion */ ?>
<?php $r = $controlador->reporte_data; ?>

<div class="container-fluid">
    <h3 class="mb-3"><i class="bi bi-journal-text"></i> Reporte Mensual de Movimientos Reales
        — <?php echo $controlador->anio_filtro; ?>/<?php echo str_pad($controlador->mes_filtro, 2, '0', STR_PAD_LEFT); ?>
    </h3>

    <!-- Filtros -->
    <form method="get" class="row mb-4">
        <input type="hidden" name="seccion" value="inm_mov_real">
        <input type="hidden" name="accion" value="reporte_mensual">
        <input type="hidden" name="session_id" value="<?php echo $_GET['session_id'] ?? ''; ?>">
        <div class="col-md-2">
            <label class="form-label">Año</label>
            <input type="number" name="anio" class="form-control" value="<?php echo $controlador->anio_filtro; ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Mes</label>
            <input type="number" name="mes" class="form-control" value="<?php echo $controlador->mes_filtro; ?>" min="1" max="12">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filtrar</button>
        </div>
    </form>

    <!-- Resumen -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Ingresos</h6>
                    <h3 class="text-success">$<?php echo number_format($r->total_ingresos, 2); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Egresos</h6>
                    <h3 class="text-danger">$<?php echo number_format($r->total_egresos, 2); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h6 class="text-muted">Flujo Neto</h6>
                    <h3 class="<?php echo $r->flujo_neto >= 0 ? 'text-success' : 'text-danger'; ?>">
                        $<?php echo number_format($r->flujo_neto, 2); ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de ingresos -->
    <?php if(count($r->ingresos) > 0): ?>
    <h5 class="text-success"><i class="bi bi-arrow-up-circle"></i> Ingresos</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Categoría</th>
                    <th>Tipo Mov.</th>
                    <th class="text-end">Monto</th>
                    <th>Referencia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($r->ingresos as $ing): ?>
                <tr>
                    <td><?php echo $ing['inm_mov_real_id']; ?></td>
                    <td><?php echo $ing['inm_mov_real_fecha']; ?></td>
                    <td><?php echo $ing['inm_categoria_financiera_descripcion']; ?></td>
                    <td><?php echo $ing['inm_tipo_movimiento_descripcion']; ?></td>
                    <td class="text-end">$<?php echo number_format((float)$ing['inm_mov_real_monto'], 2); ?></td>
                    <td><?php echo $ing['inm_mov_real_referencia']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Tabla de egresos -->
    <?php if(count($r->egresos) > 0): ?>
    <h5 class="text-danger"><i class="bi bi-arrow-down-circle"></i> Egresos</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm">
            <thead class="table-danger">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Categoría</th>
                    <th>Tipo Mov.</th>
                    <th class="text-end">Monto</th>
                    <th>Referencia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($r->egresos as $egr): ?>
                <tr>
                    <td><?php echo $egr['inm_mov_real_id']; ?></td>
                    <td><?php echo $egr['inm_mov_real_fecha']; ?></td>
                    <td><?php echo $egr['inm_categoria_financiera_descripcion']; ?></td>
                    <td><?php echo $egr['inm_tipo_movimiento_descripcion']; ?></td>
                    <td class="text-end">$<?php echo number_format((float)$egr['inm_mov_real_monto'], 2); ?></td>
                    <td><?php echo $egr['inm_mov_real_referencia']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

