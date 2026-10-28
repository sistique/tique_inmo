<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_ubicacion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div>

        <div class="row">

            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>
                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_presupuesto_costo_alta_bd; ?>" class="form-additional">
                        <?php echo $controlador->inputs->inm_ubicacion_id; ?>
                        <?php echo $controlador->inputs->inm_ubicacion_seleccionado_id; ?>
                        <?php echo $controlador->inputs->inm_concepto_id; ?>
                        <?php echo $controlador->inputs->monto_presupuesto; ?>

                        <?php echo $controlador->inputs->btn_action_next; ?>
                        <?php echo $controlador->inputs->id_retorno; ?>
                        <?php echo $controlador->inputs->seccion_retorno; ?>
                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                    </form>
                </div>
            </div>

            <div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget widget-box box-container widget-mylistings">
                            <table class="table" id="tabla-presupuesto">
                                <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Presupuesto Base</th>
                                    <th>Total Gastado</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($controlador->presupuestos as $grupo): ?>

                                    <!-- Fila morada: tipo de concepto -->
                                    <tr class="fila-tipo-concepto">
                                        <td colspan="4">
                                            <?= htmlspecialchars($grupo['inm_tipo_concepto_descripcion'] ?? 'SIN TIPO') ?>
                                        </td>
                                    </tr>

                                    <?php foreach ($grupo['presupuestos'] as $presupuesto):
                                        $id = $presupuesto['inm_presupuesto_costo_id'];
                                        ?>
                                        <tr class="fila-presupuesto" data-id="<?= $id ?>" style="cursor: pointer;">
                                            <td><?= htmlspecialchars($presupuesto['inm_concepto_descripcion'] ?? 'Concepto') ?></td>
                                            <td>$<?= number_format($presupuesto['inm_presupuesto_costo_monto_presupuesto'], 2) ?></td>
                                            <td><strong class="total-gastado">$<?= number_format($presupuesto['total_gastado'], 2) ?></strong></td>
                                            <td><?php echo $presupuesto['elimina_bd']; ?></td>
                                        </tr>

                                        <tr id="detalle-<?= $id ?>" class="fila-detalle" style="display: none; background-color: #f8f9fa;">
                                            <td colspan="4">
                                                <table class="table table-striped table-bordered mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>Proveedor</th>
                                                        <th>Monto</th>
                                                        <th>Forma de Pago</th>
                                                        <th>Fecha</th>
                                                        <th>Referencia</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($presupuesto['costos'] as $costo): ?>
                                                        <?php foreach ($costo['pagos'] as $pago): ?>
                                                            <tr>
                                                                <td><?= $pago['gt_proveedor_razon_social'] ?></td>
                                                                <td>$<?= number_format($pago['inm_pago_costo_monto'], 2) ?></td>
                                                                <td><?= $pago['cat_sat_forma_pago_descripcion'] ?></td>
                                                                <td><?= $pago['inm_pago_costo_fecha_pago'] ?></td>
                                                                <td><?= htmlspecialchars($pago['inm_pago_costo_referencia']) ?></td>
                                                                <td><?= $pago['elimina_bd'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                    <tr class="form-inline-pago" data-id="<?= $id ?>" data-concepto-id="<?= $presupuesto['inm_concepto_id'] ?>">
                                                        <td><?php echo $presupuesto['gt_proveedor_id']; ?></td>
                                                        <td>
                                                            <input type="text" name="monto" class="form-control" id="monto[<?= $id ?>]" placeholder="Monto" title="monto">
                                                        </td>
                                                        <td><?php echo $presupuesto['cat_sat_forma_pago_id']; ?></td>
                                                        <td>
                                                            <input type="date" name="fecha_pago" class="form-control" id="fecha_pago[<?= $id ?>]" placeholder="Fecha" title="fecha_pago">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="referencia_pago" class="form-control" id="referencia[<?= $id ?>]" placeholder="Referencia" title="referencia">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-sm btn-guardar-pago" title="Guardar">+</button>
                                                            <button type="button" class="btn btn-danger btn-sm btn-cancelar-pago" title="Cancelar">x</button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


















