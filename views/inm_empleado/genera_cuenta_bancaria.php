<?php /** @var gamboamartin\comercial\controllers\controlador_com_prospecto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_alta_cuenta; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->bn_sucursal_id; ?>
                        <?php echo $controlador->inputs->em_empleado_id; ?>
                        <?php echo $controlador->inputs->num_cuenta; ?>
                        <?php echo $controlador->inputs->clabe; ?>

                        <input type='hidden' name='seccion_retorno' value='inm_empleado'>
                        <input type='hidden' name='btn_action_next' value='genera_cuenta_bancaria'>
                        <input type='hidden' name='id_retorno' value='<?php echo $controlador->registro_id; ?>'>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" value="genera_cuenta_bancaria" name="btn_action_next">Alta</button><br>
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
                            <th>Empleado</th>
                            <th>Banco</th>
                            <th>Numero de cuenta</th>
                            <th>CLABE</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($controlador->cuentas_bancarias as $cuenta){
                        ?>
                        <tr>
                            <td><?php echo $cuenta['em_cuenta_bancaria_id'] ?></td>
                            <td><?php echo $cuenta['em_empleado_nombre']." ".$cuenta['em_empleado_ap']
                                        ." ".$cuenta['em_empleado_am'] ?></td>
                            <td><?php echo $cuenta['bn_sucursal_descripcion'] ?></td>
                            <td><?php echo $cuenta['em_cuenta_bancaria_num_cuenta'] ?></td>
                            <td><?php echo $cuenta['em_cuenta_bancaria_clabe'] ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>

    </div>
</main>

