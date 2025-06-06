<?php /** @var gamboamartin\comercial\controllers\controlador_com_prospecto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_alta_integra_relacion_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->razon_social; ?>
                        <?php echo $controlador->inputs->inm_prospecto_ubicacion_ubicacion; ?>
                        <?php echo $controlador->inputs->com_agente_id; ?>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" value="correo" name="btn_action_next">Alta</button><br>
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
                            <th>Agente</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($controlador->relaciones as $etapa){
                            ?>
                            <tr>
                                <td><?php echo $etapa['com_rel_agente_id'] ?></td>
                                <td><?php echo $etapa['com_agente_descripcion'] ?></td>
                                <td><?php echo $etapa['com_rel_agente_fecha_alta'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>

    </div>
</main>
