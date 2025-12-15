<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php use config\views;

                include (new views())->ruta_templates."head/title.php"; ?>
                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">

                    <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_descuento_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->inm_factura_compra_id; ?>
                        <?php echo $controlador->inputs->total; ?>
                        <?php echo $controlador->inputs->descuento; ?>

                        <?php echo $controlador->inputs->btn_action_next; ?>
                        <?php echo $controlador->inputs->id_retorno; ?>
                        <?php echo $controlador->inputs->seccion_retorno; ?>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="submit" class="btn btn-success btn-insert">Aplica Descuento</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>