<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_proveedor $controlador  controlador en ejecucion */ ?>
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

                        <?php echo $controlador->inputs->documento; ?>
                        <?php echo $controlador->inputs->razon_social; ?>
                        <?php echo $controlador->inputs->rfc; ?>
                        <?php echo $controlador->inputs->dp_pais_id; ?>
                        <?php echo $controlador->inputs->dp_estado_id; ?>
                        <?php echo $controlador->inputs->dp_municipio_id; ?>
                        <?php echo $controlador->inputs->dp_cp_id; ?>
                        <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                        <?php echo $controlador->inputs->calle; ?>
                        <?php echo $controlador->inputs->exterior; ?>
                        <?php echo $controlador->inputs->interior; ?>
                        <?php echo $controlador->inputs->cat_sat_regimen_fiscal_id; ?>
                        <?php echo $controlador->inputs->gt_tipo_proveedor_id; ?>
                        <?php echo $controlador->inputs->pagina_web; ?>
                        <?php echo $controlador->inputs->telefono_1; ?>
                        <?php echo $controlador->inputs->telefono_2; ?>
                        <?php echo $controlador->inputs->telefono_3; ?>
                        <?php echo $controlador->inputs->contacto_1; ?>
                        <?php echo $controlador->inputs->contacto_2; ?>
                        <?php echo $controlador->inputs->contacto_3; ?>
                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>

                        <input type='hidden' name='seccion_retorno' value='inm_comprador'>
                        <input type='hidden' name='btn_action_next' value='etapa'>
                        <input type='hidden' name='id_retorno' value='<?php echo $controlador->registro_id; ?>'>
                    </form>

                </div>

            </div>
        </div>

    </div>

</main>