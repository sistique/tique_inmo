<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_ubicacion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div>

        <div class="row">

            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>
                <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                <?php include (new views())->ruta_templates."mensajes.php"; ?>
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">

                        <div id="pestanas">
                            <ul id="lista">
                                <li id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>ALTA</a></li>
                                <li id="pestana2"><a href='javascript:cambiarPestanna(pestanas,pestana2);'>VALIDACION</a></li>
                                <li id="pestana3"><a href='javascript:cambiarPestanna(pestanas,pestana3);'>SOLICITUD DE RECURSO</a></li>
                                <li id="pestana4"><a href='javascript:cambiarPestanna(pestanas,pestana4);'>POR FIRMAR</a></li>
                                <li id="pestana5"><a href='javascript:cambiarPestanna(pestanas,pestana5);'>FIRMADO POR APROBAR</a></li>
                                <li id="pestana6"><a href='javascript:cambiarPestanna(pestanas,pestana6);'>FIRMADO</a></li>
                            </ul>
                        </div>
                        <body onload="javascript:valor_inicial();">
                        <div id="contenidopestanas">
                            <div class="conten" id="cpestana1">
                            </div>
                            <div class="conten" id="cpestana2">
                                <form method="post" action="<?php echo $controlador->link_validacion_bd; ?>"
                                      class="form-additional" enctype="multipart/form-data">

                                    <?php echo $controlador->inputs->documento_rppc; ?>

                                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                </form>

                                <div class="row buttons-form">
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_descarga; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_vista_previa; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_descarga_zip; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_elimina_bd; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="conten" id="cpestana3">
                                <form method="post" action="<?php echo $controlador->link_solicitud_de_recurso_bd; ?>"
                                      class="form-additional" enctype="multipart/form-data">

                                    <?php echo $controlador->inputs->nombre_beneficiario; ?>
                                    <?php echo $controlador->inputs->numero_cheque; ?>
                                    <?php echo $controlador->inputs->monto; ?>

                                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                </form>
                            </div>
                            <div class="conten" id="cpestana4">
                                <form method="post" action="<?php echo $controlador->link_por_firmar_bd; ?>"
                                      class="form-additional" enctype="multipart/form-data">

                                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                </form>
                            </div>
                            <div class="conten" id="cpestana5">
                                <form method="post" action="<?php echo $controlador->link_firmado_por_aprobar_bd; ?>"
                                      class="form-additional" enctype="multipart/form-data">

                                    <?php echo $controlador->inputs->inm_notaria_id; ?>
                                    <?php echo $controlador->inputs->documento_poder; ?>
                                    <?php echo $controlador->inputs->numero_escritura_poder; ?>
                                    <?php echo $controlador->inputs->fecha_poder; ?>

                                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                </form>
                                <div class="row buttons-form">
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_descarga_firmado_por_aprobar; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_vista_previa_firmado_por_aprobar; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_descarga_zip_firmado_por_aprobar; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_elimina_bd_firmado_por_aprobar; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="conten" id="cpestana6">
                                <form method="post" action="<?php echo $controlador->link_firmado_bd; ?>"
                                      class="form-additional" enctype="multipart/form-data">

                                    <?php echo $controlador->inputs->documento_poliza_firmada; ?>

                                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                </form>
                                <div class="row buttons-form">
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_descarga_firmado; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_vista_previa_firmado; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_descarga_zip_firmado; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $controlador->button_inm_doc_ubicacion_elimina_bd_firmado; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</main>

<dialog id="myModal">
    <span class="close-btn" id="closeModalBtn">&times;</span>
    <h2>Vista Previa</h2>
    <div class="content">
    </div>
</dialog>
















