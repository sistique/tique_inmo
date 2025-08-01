<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_descarga_reporte_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->inm_empleado_id; ?>
                        <?php echo $controlador->inputs->fecha_inicio; ?>
                        <?php echo $controlador->inputs->fecha_fin; ?>
                        <?php echo $controlador->inputs->inm_status_asistencia_id; ?>
                        <?php echo $controlador->inputs->inm_tipo_checada_id; ?>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" name="btn_action_next">Descarga</button><br>
                        </div>
                    </form>

                </div>

            </div>
        </div>

    </div>

</main>