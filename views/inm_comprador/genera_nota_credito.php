<?php /** @var gamboamartin\comercial\controllers\controlador_com_prospecto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_nota_credito_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->com_sucursal_id; ?>
                        <?php echo $controlador->inputs->fc_factura_id; ?>
                        <?php echo $controlador->inputs->observaciones_nota_credito; ?>
                        <?php echo $controlador->inputs->com_producto_id; ?>
                        <?php echo $controlador->inputs->cat_sat_forma_pago_id; ?>
                        <?php echo $controlador->inputs->descripcion_nota_credito; ?>
                        <?php echo $controlador->inputs->monto_nota_credito; ?>
                        <?php echo $controlador->inputs->valor_unitario_nota_credito; ?>

                        <input type='hidden' name='seccion_retorno' value='inm_comprador'>
                        <input type='hidden' name='btn_action_next' value='genera_factura'>
                        <input type='hidden' name='id_retorno' value='<?php echo $controlador->registro_id; ?>'>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" value="genera_factura" name="btn_action_next">Alta</button><br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php echo $controlador->buttons_base; ?>

<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="widget widget-box box-container widget-mylistings">
                    <div class="widget-header col-md-12">
                        <h2>Notas de Credito</h2>
                    </div>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Observaciones</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>UUID</th>
                            <th>Detalles</th>
                            <th>Timbra XML</th>
                            <th>Descargar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($controlador->notas_credito as $nota_credito){
                            ?>
                            <tr>
                                <td><?php echo $nota_credito['fc_nota_credito_id'] ?></td>
                                <td><?php echo $nota_credito['fc_nota_credito_folio'] ?></td>
                                <td><?php echo $nota_credito['com_cliente_razon_social'] ?></td>
                                <td><?php echo $nota_credito['fc_nota_credito_observaciones'] ?></td>
                                <td><?php echo $nota_credito['fc_nota_credito_fecha'] ?></td>
                                <td><?php echo $nota_credito['fc_nota_credito_total'] ?></td>
                                <td><?php echo $nota_credito['fc_nota_credito_uuid'] ?></td>
                                <td><?php echo $nota_credito['modifica'] ?></td>
                                <td><?php echo $nota_credito['timbra_xml'] ?></td>
                                <td><?php echo $nota_credito['exportar_documentos'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>

    </div>
</main>

