<?php /** @var gamboamartin\comercial\controllers\controlador_com_prospecto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_alta_bitacora; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->inm_ubicacion_id; ?>
                        <?php echo $controlador->inputs->inm_status_ubicacion_id; ?>
                        <?php echo $controlador->inputs->fecha; ?>
                        <?php echo $controlador->inputs->observaciones; ?>

                        <input type='hidden' name='seccion_retorno' value='inm_ubicacion'>
                        <input type='hidden' name='btn_action_next' value='etapa'>
                        <input type='hidden' name='id_retorno' value='<?php echo $controlador->registro_id; ?>'>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" value="etapa" name="btn_action_next">Alta</button><br>
                        </div>
                    </form>

                </div>

            </div>
        </div>

    </div>

</main>

<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="widget widget-box box-container widget-mylistings">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Etapa</th>
                            <th>Fecha</th>
                            <th>Observaciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($controlador->etapas as $etapa){
                        ?>
                        <tr>
                            <td><?php echo $etapa['inm_bitacora_status_ubicacion_id'] ?></td>
                            <td><?php echo $etapa['inm_status_ubicacion_descripcion'] ?></td>
                            <td><?php echo $etapa['inm_bitacora_status_ubicacion_fecha_status'] ?></td>
                            <td><?php echo $etapa['inm_bitacora_status_ubicacion_observaciones'] ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>

    </div>
</main>

