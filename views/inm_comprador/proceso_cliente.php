<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_cliente $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div >

        <div class="row">

            <div class="col-lg-12">

                <?php include (new views())->ruta_templates."head/title.php"; ?>
                <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                <?php include (new views())->ruta_templates."mensajes.php"; ?>
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">

                    <div id="pestanasgeneral">
                        <ul id="listageneral">
                            <li id="pestanageneral1"><a href='javascript:cambiarPestannaGeneral(pestanasgeneral,pestanageneral1,pestanascliente);'>CLIENTE</a></li>
                            <li id="pestanageneral2"><a href='javascript:cambiarPestannaGeneral(pestanasgeneral,pestanageneral2,pestanas);'>ETAPAS</a></li>
                        </ul>
                    </div>
                    <body onload="javascript:cambiarPestannaGeneral_inicial(pestanasgeneral);
                    javascript:valor_inicial();
                    javascript:cambiarPestanna_inicialcliente(pestanascliente);">
                    <div id="contenidopestanasgeneral">
                        <div class="contengeneral" id="cpestanageneral1">
                            <div id="pestanascliente">
                                <ul id="listacliente">
                                    <li id="pestanacliente1"><a href='javascript:cambiarPestanna(pestanascliente,pestanacliente1);'>MODIFICA</a></li>
                                    <li id="pestanacliente2"><a href='javascript:cambiarPestanna(pestanascliente,pestanacliente2);'>DOCUMENTOS</a></li>
                                    <li id="pestanacliente3"><a href='javascript:cambiarPestanna(pestanascliente,pestanacliente3);'>ETAPA MANUAL</a></li>
                                </ul>
                            </div>
                            <div id="contenidopestanascliente">
                                <div class="conten" id="cpestanacliente1">
                                    <form method="post" action="<?php echo $controlador->link_modifica_bd; ?>" class="form-additional"
                                          enctype="multipart/form-data">

                                        <?php echo $controlador->header_frontend->apartado_1; ?>
                                        <div id="apartado_1">
                                            <?php echo $controlador->inputs->com_agente_id; ?>
                                            <?php echo $controlador->inputs->nombre; ?>
                                            <?php echo $controlador->inputs->apellido_paterno; ?>
                                            <?php echo $controlador->inputs->apellido_materno; ?>
                                            <?php echo $controlador->inputs->nss; ?>
                                            <?php echo $controlador->inputs->curp; ?>
                                            <?php echo $controlador->inputs->rfc; ?>
                                            <?php //cho $controlador->inputs->observaciones; ?>
                                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_2; ?>
                                        <div id="apartado_2">
                                            <?php echo $controlador->inputs->lada_com; ?>
                                            <?php echo $controlador->inputs->numero_com; ?>
                                            <?php echo $controlador->inputs->cel_com; ?>
                                            <?php echo $controlador->inputs->correo_com; ?>
                                            <?php echo $controlador->inputs->razon_social; ?>
                                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_3; ?>
                                        <div id="apartado_3">
                                            <?php echo $controlador->inputs->dp_estado_id; ?>
                                            <?php echo $controlador->inputs->dp_municipio_id; ?>
                                            <?php echo $controlador->inputs->dp_cp_id; ?>
                                            <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                                            <?php echo $controlador->inputs->calle; ?>
                                            <?php echo $controlador->inputs->numero_exterior; ?>
                                            <?php echo $controlador->inputs->numero_interior; ?>

                                            <?php echo $controlador->inputs->inm_estado_vivienda_id; ?>
                                            <?php echo $controlador->inputs->fecha_otorgamiento_credito; ?>
                                            <?php echo $controlador->inputs->inm_prototipo_id; ?>
                                            <?php echo $controlador->inputs->inm_complemento_id; ?>
                                            <?php echo $controlador->inputs->manzana; ?>
                                            <?php echo $controlador->inputs->lote; ?>
                                            <?php echo $controlador->inputs->nivel; ?>
                                            <?php echo $controlador->inputs->recamaras; ?>
                                            <?php echo $controlador->inputs->metros_terreno; ?>
                                            <?php echo $controlador->inputs->metros_construccion; ?>

                                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_4; ?>
                                        <div id="apartado_4">
                                            <?php echo $controlador->inputs->adeudo_hipoteca; ?>
                                            <?php echo $controlador->inputs->cuenta_predial; ?>
                                            <?php echo $controlador->inputs->adeudo_predial; ?>
                                            <?php echo $controlador->inputs->cuenta_agua; ?>
                                            <?php echo $controlador->inputs->adeudo_agua; ?>
                                            <?php echo $controlador->inputs->adeudo_luz; ?>
                                            <?php echo $controlador->inputs->monto_devolucion; ?>
                                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                        </div>
                                        <?php echo $controlador->header_frontend->apartado_5; ?>
                                        <div id="apartado_5">
                                            <?php echo $controlador->inputs->conyuge->nombre; ?>
                                            <?php echo $controlador->inputs->conyuge->apellido_paterno; ?>
                                            <?php echo $controlador->inputs->conyuge->apellido_materno; ?>
                                            <?php echo $controlador->inputs->conyuge->dp_estado_id; ?>
                                            <?php echo $controlador->inputs->conyuge->dp_municipio_id; ?>
                                            <?php echo $controlador->inputs->conyuge->fecha_nacimiento; ?>
                                            <?php echo $controlador->inputs->conyuge->inm_nacionalidad_id; ?>
                                            <?php echo $controlador->inputs->conyuge->curp; ?>
                                            <?php echo $controlador->inputs->conyuge->rfc; ?>
                                            <?php echo $controlador->inputs->conyuge->inm_ocupacion_id; ?>
                                            <?php echo $controlador->inputs->conyuge->telefono_casa; ?>
                                            <?php echo $controlador->inputs->conyuge->telefono_celular; ?>
                                        </div>
                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestanacliente2">
                                    <div>
                                        <div class="row">
                                            <div class="col-lg-12 table-responsive">
                                                <table id="table-inm_cliente" class="table mb-0 table-striped table-sm "></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestanacliente3">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                                                <form method="post" action="<?php echo $controlador->link_alta_bitacora; ?>" class="form-additional">
                                                    <?php echo $controlador->inputs->inm_cliente_id; ?>
                                                    <?php echo $controlador->inputs->inm_status_cliente_id; ?>
                                                    <?php echo $controlador->inputs->fecha; ?>
                                                    <?php echo $controlador->inputs->observaciones; ?>

                                                    <?php echo $controlador->inputs->btn_action_next; ?>
                                                    <?php echo $controlador->inputs->id_retorno; ?>
                                                    <?php echo $controlador->inputs->seccion_retorno; ?>

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
                                                            <td><?php echo $etapa['inm_bitacora_status_cliente_id'] ?></td>
                                                            <td><?php echo $etapa['inm_status_cliente_descripcion'] ?></td>
                                                            <td><?php echo $etapa['inm_bitacora_status_cliente_fecha_status'] ?></td>
                                                            <td><?php echo $etapa['inm_bitacora_status_cliente_observaciones'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="contengeneral" id="cpestanageneral2">
                            <div id="pestanas">
                                <ul id=lista>
                                    <li id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>DETENIDO</a></li>
                                    <li id="pestana2"><a href='javascript:cambiarPestanna(pestanas,pestana2);'>ASIGNADO</a></li>
                                    <li id="pestana3"><a href='javascript:cambiarPestanna(pestanas,pestana3);'>EN AVALUO</a></li>
                                    <li id="pestana4"><a href='javascript:cambiarPestanna(pestanas,pestana4);'>POR INGRESAR</a></li>
                                    <li id="pestana5"><a href='javascript:cambiarPestanna(pestanas,pestana5);'>INGRESADO</a></li>
                                    <li id="pestana6"><a href='javascript:cambiarPestanna(pestanas,pestana6);'>AUTORIZADO</a></li>
                                    <li id="pestana7"><a href='javascript:cambiarPestanna(pestanas,pestana7);'>POR FIRMAR</a></li>
                                    <li id="pestana8"><a href='javascript:cambiarPestanna(pestanas,pestana8);'>ESCRITURADO</a></li>
                                    <li id="pestana9"><a href='javascript:cambiarPestanna(pestanas,pestana9);'>COTEJADO</a></li>
                                    <li id="pestana10"><a href='javascript:cambiarPestanna(pestanas,pestana10);'>COBRADO</a></li>
                                </ul>
                            </div>
                            <body onload="javascript:cambiarPestanna(pestanas,pestana1);">
                            <div id="contenidopestanas">
                                <div class="conten" id="cpestana1">
                                    <form method="post" action="<?php echo $controlador->link_rel_ubi_comp_alta_bd; ?>" class="form-additional">

                                        <?php echo $controlador->inputs->inm_cliente_id; ?>
                                        <?php echo $controlador->inputs->precio_operacion; ?>

                                        <?php echo $controlador->inputs->com_tipo_cliente_id; ?>
                                        <?php echo $controlador->inputs->nss; ?>
                                        <?php echo $controlador->inputs->curp; ?>
                                        <?php echo $controlador->inputs->rfc; ?>
                                        <?php echo $controlador->inputs->apellido_paterno; ?>
                                        <?php echo $controlador->inputs->apellido_materno; ?>
                                        <?php echo $controlador->inputs->nombre; ?>
                                        <?php echo $controlador->inputs->inm_comprador_id; ?>
                                        <?php echo $controlador->inputs->inm_comprador_id; ?>
                                        <?php echo $controlador->inputs->seccion_retorno; ?>
                                        <?php echo $controlador->inputs->btn_action_next; ?>
                                        <?php echo $controlador->inputs->id_retorno; ?>

                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana2">
                                    <form method="post" action="<?php echo $controlador->link_inm_rel_cliente_valuador_alta_bd; ?>"
                                          class="form-additional">
                                        <?php echo $controlador->inputs->com_cliente_id; ?>
                                        <?php echo $controlador->inputs->inm_valuador_id; ?>
                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana3">
                                    <form method="post" action="<?php echo $controlador->link_asigna_avaluo_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <?php echo $controlador->inputs->com_cliente_id; ?>
                                        <?php echo $controlador->inputs->mts_terrenos; ?>
                                        <?php echo $controlador->inputs->mts_construidos; ?>
                                        <?php echo $controlador->inputs->valor_avaluo; ?>
                                        <?php echo $controlador->inputs->documento; ?>

                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana4">
                                    <?php echo $controlador->inputs->com_cliente_id; ?>

                                    <a role="button" title="Solicitud Infonavit" href="index.php?seccion=inm_comprador&amp;accion=solicitud_infonavit&amp;registro_id=55&amp;session_id=5514223136&amp;adm_menu_id=45" class="btn btn-warning " style="margin-left: 2px; margin-bottom: 2px; ">Solicitud Infonavit</a>
                                </div>
                                <div class="conten" id="cpestana5">
                                    <form method="post" action="<?php echo $controlador->link_asigna_avaluo_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <?php echo $controlador->inputs->documento_sic; ?>
                                        <?php echo $controlador->inputs->documento_constancia_credito; ?>

                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana6">
                                    <form method="post" action="<?php echo $controlador->link_asigna_firma_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <?php echo $controlador->inputs->documento_anexos; ?>
                                        <?php echo $controlador->inputs->documento_instruccion_credito; ?>
                                        <?php echo $controlador->inputs->documento_notificacion_descuento; ?>
                                        <?php echo $controlador->inputs->documento_isr_notaria; ?>
                                        <?php echo $controlador->inputs->isr; ?>
                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana7">
                                    <form method="post" action="<?php echo $controlador->link_asigna_escritura_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <?php echo $controlador->inputs->documento_validacion_poder; ?>
                                        <?php echo $controlador->inputs->documento_acuse_patron; ?>
                                        <?php echo $controlador->inputs->documento_escrituras; ?>
                                        <?php echo $controlador->inputs->numero_escritura; ?>
                                        <?php echo $controlador->inputs->fecha; ?>

                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana8">
                                    Contenido de la pestaña 2
                                </div>
                                <div class="conten" id="cpestana9">
                                    <form method="post" action="<?php echo $controlador->link_por_firmar_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana10">
                                    <form method="post" action="<?php echo $controlador->link_por_firmar_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
                                    </form>
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
















