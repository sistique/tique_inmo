<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_ubicacion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div >

        <div class="row">

            <div class="col-lg-12">

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">

                    <form method="post" action="<?php echo $controlador->link_asigna_escritura_bd; ?>"
                          class="form-additional" enctype="multipart/form-data">

                    <?php include (new views())->ruta_templates."head/title.php"; ?>
                    <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                    <?php include (new views())->ruta_templates."mensajes.php"; ?>

                    <?php echo $controlador->inputs->documento_validacion_poder; ?>
                    <?php echo $controlador->inputs->documento_acuse_patron; ?>
                    <?php echo $controlador->inputs->documento_escrituras; ?>
                    <?php echo $controlador->inputs->numero_escritura; ?>
                    <?php echo $controlador->inputs->fecha; ?>


                    <?php echo $controlador->inputs->btn_action_next; ?>
                    <?php echo $controlador->inputs->id_retorno; ?>
                    <?php echo $controlador->inputs->seccion_retorno; ?>

                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                    </form>
                </div>
            </div>
        </div>


</main>


















