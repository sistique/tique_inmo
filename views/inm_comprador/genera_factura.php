<?php /** @var  \gamboamartin\facturacion\controllers\controlador_fc_factura $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <!--<div class="container">-->

    <div class="row">

        <div class="col-lg-12">

            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">

                <form method="post" action="<?php echo $controlador->link_genera_factura_bd; ?>" class="form-additional">
                    <?php include (new views())->ruta_templates."head/title.php"; ?>
                    <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                    <?php include (new views())->ruta_templates."mensajes.php"; ?>
                    <?php echo $controlador->inputs->fc_csd_id; ?>
                    <?php echo $controlador->inputs->com_cliente_id; ?>
                    <?php echo $controlador->inputs->serie; ?>
                    <?php echo $controlador->inputs->folio; ?>
                    <?php echo $controlador->inputs->exportacion; ?>
                    <?php echo $controlador->inputs->fecha_factura; ?>
                    <?php echo $controlador->inputs->cat_sat_tipo_de_comprobante_id; ?>
                    <?php echo $controlador->inputs->cat_sat_metodo_pago_id; ?>
                    <?php echo $controlador->inputs->cat_sat_forma_pago_id; ?>
                    <?php echo $controlador->inputs->cat_sat_moneda_id; ?>
                    <?php echo $controlador->inputs->com_tipo_cambio_id; ?>
                    <?php echo $controlador->inputs->cat_sat_uso_cfdi_id; ?>
                    <?php echo $controlador->inputs->observaciones_factura; ?>
                    <?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

                </form>
            </div>

        </div>

    </div>
    <!--</div>-->

    <!--<div class="container">-->
    <?php echo $controlador->buttons_base; ?>
    <!--</div>-->

    <div class="partidas"> <!--class="containe partidas">-->
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header" style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Partidas</h2>
                    </div>
                    <form method="post" action="#" class="form-additional" id="frm-partida">

                        <?php echo $controlador->inputs->partidas->com_producto_id; ?>
                        <?php echo $controlador->inputs->partidas->unidad; ?>
                        <?php //echo $controlador->inputs->partidas->impuesto; ?>
                        <?php echo $controlador->inputs->partidas->cuenta_predial; ?>
                        <?php echo $controlador->inputs->partidas->cat_sat_obj_imp_id; ?>
                        <?php echo $controlador->inputs->partidas->descripcion; ?>
                        <?php echo $controlador->inputs->partidas->cantidad; ?>
                        <?php echo $controlador->inputs->partidas->valor_unitario; ?>
                        <?php echo $controlador->inputs->partidas->subtotal; ?>
                        <?php echo $controlador->inputs->partidas->descuento; ?>
                        <?php echo $controlador->inputs->partidas->total; ?>
                        <?php echo $controlador->inputs->partidas->cat_sat_conf_imps_id; ?>


                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="button" class="btn btn-success" value="modifica" name="btn_action_next" id="btn-alta-partida">Alta</button><br>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</main>

<script src="<?php echo (new \config\generales())->url_base."js/_facturacion.js" ?>"></script>

