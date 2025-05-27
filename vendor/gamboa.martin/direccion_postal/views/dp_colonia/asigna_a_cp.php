<?php /** @var controllers\controlador_dp_colonia $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_colonia_postal_alta_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->dp_colonia_id; ?>
                        <?php echo $controlador->inputs->dp_pais_id; ?>
                        <?php echo $controlador->inputs->dp_estado_id; ?>
                        <?php echo $controlador->inputs->dp_municipio_id; ?>
                        <?php echo $controlador->inputs->dp_cp_id; ?>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" value="lista" name="btn_action_next">Alta</button><br>
                        </div>
                        <div class="error"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

