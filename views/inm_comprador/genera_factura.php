<?php /** @var  \gamboamartin\facturacion\controllers\controlador_fc_factura $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <!--<div class="container">-->
    <div class="row">
        <div class="col-lg-12">
            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                <?php include (new views())->ruta_templates."head/title.php"; ?>
                <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                <?php include (new views())->ruta_templates."mensajes.php"; ?>
                <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_genera_factura_bd; ?>" class="form-additional">
                    <?php //echo $controlador->inputs->uuid; ?>
                    <?php echo $controlador->inputs->fc_csd_id; ?>
                    <?php echo $controlador->inputs->com_sucursal_id; ?>
                    <?php //echo $controlador->inputs->serie; ?>
                    <?php //echo $controlador->inputs->folio; ?>
                    <?php //echo $controlador->inputs->exportacion; ?>
                    <?php echo $controlador->inputs->fecha_factura; ?>
                    <?php //echo $controlador->inputs->cat_sat_tipo_de_comprobante_id; ?>
                    <?php //echo $controlador->inputs->cat_sat_metodo_pago_id; ?>
                    <?php //echo $controlador->inputs->cat_sat_forma_pago_id; ?>
                    <?php //echo $controlador->inputs->cat_sat_moneda_id; ?>
                    <?php //echo $controlador->inputs->com_tipo_cambio_id; ?>
                    <?php //echo $controlador->inputs->cat_sat_uso_cfdi_id; ?>
                    <?php echo $controlador->inputs->observaciones_factura; ?>

                    <div class="widget-header col-md-12">
                        <h2>Partida</h2>
                    </div>

                    <?php echo $controlador->inputs->com_producto_id; ?>
                    <?php //echo $controlador->inputs->unidad; ?>
                    <?php echo $controlador->inputs->cuenta_predial; ?>
                    <?php //echo $controlador->inputs->cat_sat_obj_imp_id; ?>
                    <?php echo $controlador->inputs->descripcion_factura; ?>
                    <?php //echo $controlador->inputs->cantidad; ?>
                    <?php echo $controlador->inputs->valor_unitario; ?>
                    <?php //echo $controlador->inputs->subtotal; ?>
                    <?php //echo $controlador->inputs->descuento_factura; ?>
                    <?php //echo $controlador->inputs->total; ?>
                    <?php //echo $controlador->inputs->cat_sat_conf_imps_id; ?>

                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                </form>

                <?php echo $controlador->buttons_base; ?>
            </div>
        </div>
    </div>
    <!--</div>-->

    <!--<div class="container">--><!--</div>-->
</main>

<script src="<?php echo (new \config\generales())->url_base."js/_facturacion.js" ?>"></script>

