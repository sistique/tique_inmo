<?php /** @var gamboamartin\organigrama\controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_org_departamento_alta_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/title.php"; ?>
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                        <?php include (new views())->ruta_templates."mensajes.php"; ?>

                    <?php echo $controlador->inputs->org_empresa_id; ?>
                    <?php echo $controlador->inputs->org_clasificacion_dep_id; ?>
                    <?php echo $controlador->inputs->codigo; ?>
                    <?php echo $controlador->inputs->descripcion; ?>
                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="submit" class="btn btn-success" value="departamentos" name="btn_action_next">Alta</button><br>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="widget widget-box box-container widget-mylistings">

                    <div class="">
                        <table class="table table-striped footable-sort" data-sorting="true">
                            <th>Id</th>
                            <th>Codigo</th>
                            <th>Codigo BIS</th>
                            <th>Descripcion</th>
                            <th>Empresa</th>
                            <th>Clasificacion Departamento</th>

                            <th>Modifica</th>
                            <th>Elimina</th>

                            <tbody>
                            <?php foreach ($controlador->departamentos->registros as $departamento){
                                ?>
                            <tr>
                                <td><?php echo $departamento['org_departamento_id']; ?></td>
                                <td><?php echo $departamento['org_departamento_codigo']; ?></td>
                                <td><?php echo $departamento['org_departamento_codigo_bis']; ?></td>
                                <td><?php echo $departamento['org_departamento_descripcion']; ?></td>
                                <td><?php echo $departamento['org_empresa_razon_social']; ?></td>
                                <td><?php echo $departamento['org_clasificacion_dep_descripcion']; ?></td>

                                <td><?php echo $departamento['link_modifica']; ?></td>
                                <td><?php echo $departamento['link_elimina']; ?></td>

                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="box-body">
                            * Total registros: <?php echo $controlador->departamentos->n_registros; ?><br />
                            * Fecha Hora: <?php echo $controlador->fecha_hoy; ?>
                        </div>
                    </div>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>
    </div>


</main>





