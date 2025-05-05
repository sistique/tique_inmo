<?php /** @var \gamboamartin\empleado\models\em_empleado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <?php include (new views())->ruta_templates."head/title.php"; ?>
                    <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                    <?php include (new views())->ruta_templates."mensajes.php"; ?>
                    <form method="post" action="<?php echo $controlador->link_im_registro_patronal_modifica_bd; ?>" class="form-additional">
                        <?php echo $controlador->inputs->id; ?>
                        <?php echo $controlador->inputs->codigo; ?>
                        <?php echo $controlador->inputs->codigo_bis; ?>
                        <?php echo $controlador->inputs->fc_csd_id; ?>
                        <?php echo $controlador->inputs->descripcion; ?>
                        <?php echo $controlador->inputs->select->im_clase_riesgo_id; ?>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="submit" class="btn btn-success" value="departamento_modifica" name="btn_action_next">Modifica</button><br>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>

    </div>

</main>







