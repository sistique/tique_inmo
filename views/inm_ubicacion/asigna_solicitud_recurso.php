<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_ubicacion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div>

        <div class="row">

            <div class="col-lg-12">

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">

                    <form method="post" action="<?php echo $controlador->link_asigna_validacion_bd; ?>"
                          class="form-additional" enctype="multipart/form-data">

                    <?php include (new views())->ruta_templates."head/title.php"; ?>
                    <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                    <?php include (new views())->ruta_templates."mensajes.php"; ?>

                    <?php echo $controlador->inputs->nombre_beneficiario; ?>
                    <?php echo $controlador->inputs->numero_cheque; ?>
                    <?php echo $controlador->inputs->monto; ?>


                    <?php //echo $controlador->inputs->btn_action_next; ?>
                    <?php //echo $controlador->inputs->id_retorno; ?>
                    <?php //echo $controlador->inputs->seccion_retorno; ?>

                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="widget widget-box box-container widget-mylistings">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Beneficiario</th>
                            <th>No. Cheque</th>
                            <th>Moto</th>
                            <th>Fecha</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($controlador->cheques as $cheque){
                            ?>
                            <tr>
                                <td><?php echo $cheque['inm_cheque_id'] ?></td>
                                <td><?php echo $cheque['inm_cheque_nombre_beneficiario'] ?></td>
                                <td><?php echo $cheque['inm_cheque_numero_cheque'] ?></td>
                                <td><?php echo $cheque['inm_cheque_monto'] ?></td>
                                <td><?php echo $cheque['inm_cheque_fecha_alta'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>
    </div>


</main>


















