<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_doc_comprador $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12 ">
                <div class="row">
                    <br>
                    <div class="col-md-12">
                        <?php echo $controlador->inputs->inm_doc_prospecto_id; ?>
                        <iframe class="col-md-12 view" height="100%" src="<?php echo $controlador->ruta_doc; ?>"></iframe>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</main>


















