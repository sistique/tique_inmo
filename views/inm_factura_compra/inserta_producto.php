<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_inserta_producto_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->inm_empleado_id; ?>
                        <?php echo $controlador->inputs->documento_xml_factura; ?>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" name="btn_action_next">Inserta Producto</button><br>
                        </div>
                    </form>

                </div>

            </div>
        </div>

    </div>

</main>