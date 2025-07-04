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

                    <?php echo $controlador->inputs->inm_ubicacion_seleccionado_id; ?>
                    <div id="pestanasgeneral">
                        <ul id="listageneral">
                            <li id="pestanageneral1"><a href='javascript:cambiarPestannaGeneral(pestanasgeneral,pestanageneral1,pestanasubicacion);'>UBICACION</a></li>
                            <li id="pestanageneral2"><a href='javascript:cambiarPestannaGeneral(pestanasgeneral,pestanageneral2,pestanas);'>ETAPAS</a></li>
                        </ul>
                    </div>
                    <body onload="javascript:cambiarPestannaGeneral_inicial(pestanasgeneral);
                    javascript:valor_inicial();
                    javascript:cambiarPestanna_inicialubicacion(pestanasubicacion);
                    javascript:inicializa_conyuge();">
                    <div id="contenidopestanasgeneral">
                        <div class="contengeneral" id="cpestanageneral1">
                            <div id="pestanasubicacion">
                                <ul id="listaubicacion">
                                    <li id="pestanaubicacion1"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion1);'>MODIFICA</a></li>
                                    <li id="pestanaubicacion2"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion2);'>DOCUMENTOS</a></li>
                                    <li id="pestanaubicacion3"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion3);'>FOTOGRAFIAS</a></li>
                                    <li id="pestanaubicacion4"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion4);'>ETAPA MANUAL</a></li>
                                </ul>
                            </div>
                            <div id="contenidopestanasubicacion">
                                <div class="conten" id="cpestanaubicacion1">
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
                                            <?php echo $controlador->inputs->inm_tipo_credito_id; ?>
                                            <?php echo $controlador->inputs->numero_credito; ?>
                                            <?php echo $controlador->inputs->correo_mi_cuenta_infonavit; ?>
                                            <?php echo $controlador->inputs->password_mi_cuenta_infonavit; ?>
                                            <?php echo $controlador->inputs->fecha_otorgamiento_credito; ?>
                                            <?php echo $controlador->inputs->adeudo_hipoteca; ?>
                                            <?php echo $controlador->inputs->monto_devolucion; ?>

                                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_5; ?>
                                        <div id="apartado_5">
                                            <?php echo $controlador->inputs->cuenta_predial; ?>
                                            <?php echo $controlador->inputs->adeudo_predial; ?>
                                            <?php echo $controlador->inputs->cuenta_agua; ?>
                                            <?php echo $controlador->inputs->adeudo_agua; ?>
                                            <?php echo $controlador->inputs->adeudo_luz; ?>
                                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                        </div>
                                        <?php echo $controlador->header_frontend->apartado_6; ?>
                                        <div id="apartado_6">
                                            <?php echo $controlador->inputs->conyuge->nombre; ?>
                                            <?php echo $controlador->inputs->conyuge->apellido_paterno; ?>
                                            <?php echo $controlador->inputs->conyuge->apellido_materno; ?>
                                            <?php echo $controlador->inputs->conyuge->dp_estado_id; ?>
                                            <?php echo $controlador->inputs->conyuge->dp_municipio_id; ?>
                                            <?php echo $controlador->inputs->conyuge->fecha_nacimiento; ?>
                                            <?php echo $controlador->inputs->conyuge->inm_nacionalidad_id; ?>
                                            <?php echo $controlador->inputs->conyuge->curp; ?>
                                            <?php echo $controlador->inputs->conyuge->rfc; ?>
                                            <?php echo $controlador->inputs->conyuge->numero_credito; ?>
                                            <?php echo $controlador->inputs->conyuge->adeudo_hipoteca; ?>
                                            <?php echo $controlador->inputs->conyuge->inm_ocupacion_id; ?>
                                            <?php echo $controlador->inputs->conyuge->telefono_casa; ?>
                                            <?php echo $controlador->inputs->conyuge->telefono_celular; ?>
                                        </div>
                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestanaubicacion2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="widget widget-box box-container widget-mylistings">
                                                <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_documento_bd; ?>" class="form-additional">
                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Tipo Documento</th>
                                                            <th>Descarga</th>
                                                            <th>Vista Previa</th>
                                                            <th>Zip</th>
                                                            <th>Elimina</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        foreach ($controlador->inm_conf_docs_ubicacion as $docs) {
                                                            echo $docs;
                                                        }?>
                                                        </tbody>
                                                    </table>
                                                    <?php echo $controlador->inputs->btn_action_next; ?>
                                                    <?php echo $controlador->inputs->id_retorno; ?>
                                                    <?php echo $controlador->inputs->seccion_retorno; ?>
                                                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <form enctype="multipart/form-data" method="post" action="<?php //echo $controlador->link_documento_bd; ?>" class="form-additional">
                                                <table id="table-inm_ubicacion" class="table mb-0 table-striped table-sm "></table>
                                            </form>
                                        </div>
                                    </div>-->
                                </div>
                                <div class="conten" id="cpestanaubicacion3">
                                    <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_fotografia_bd; ?>" class="form-additional">
                                        <?php foreach ($controlador->fotos as $registro){ ?>
                                            <div class="col-lg-12 contorno"  data-doc_tipo_documento_id ="<?php echo $registro['doc_tipo_documento_id']; ?>" >
                                                <?php echo $registro['input']; ?>
                                                <?php foreach ($registro['fotos'] as $foto){
                                                    foreach ($foto as $img){?>
                                                        <div class="col-lg-6 contenedor_img" data-doc_documento_id ="<?php echo $img['doc_documento_id']; ?>">
                                                            <?php echo $img['input']; ?>
                                                            <a class="btn btn-danger elimina_img"  data-inm_doc_ubicacion_id =
                                                            "<?php echo $img['inm_doc_ubicacion_id']; ?>">Elimina</a>.
                                                        </div>
                                                    <?php       }
                                                }
                                                ?>
                                            </div>
                                        <?php } ?>
                                        <?php echo $controlador->inputs->btn_action_next; ?>
                                        <?php echo $controlador->inputs->id_retorno; ?>
                                        <?php echo $controlador->inputs->seccion_retorno; ?>
                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestanaubicacion4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                                                <form method="post" action="<?php echo $controlador->link_alta_bitacora; ?>" class="form-additional">
                                                    <?php echo $controlador->inputs->inm_ubicacion_id; ?>
                                                    <?php echo $controlador->inputs->inm_status_ubicacion_id; ?>
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
                                                            <td><?php echo $etapa['inm_bitacora_status_ubicacion_id'] ?></td>
                                                            <td><?php echo $etapa['inm_status_ubicacion_descripcion'] ?></td>
                                                            <td><?php echo $etapa['inm_bitacora_status_ubicacion_fecha_status'] ?></td>
                                                            <td><?php echo $etapa['inm_bitacora_status_ubicacion_observaciones'] ?></td>
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
                                <ul id="lista">
                                    <li id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>ALTA</a></li>
                                    <li id="pestana2"><a href='javascript:cambiarPestanna(pestanas,pestana2);'>VALIDACION</a></li>
                                    <li id="pestana3"><a href='javascript:cambiarPestanna(pestanas,pestana3);'>SOLICITUD DE RECURSO</a></li>
                                    <li id="pestana4"><a href='javascript:cambiarPestanna(pestanas,pestana4);'>EMISION DE RECURSO</a></li>
                                    <li id="pestana5"><a href='javascript:cambiarPestanna(pestanas,pestana5);'>POR FIRMAR</a></li>
                                    <li id="pestana6"><a href='javascript:cambiarPestanna(pestanas,pestana6);'>FIRMADO POR APROBAR</a></li>
                                    <li id="pestana7"><a href='javascript:cambiarPestanna(pestanas,pestana7);'>FIRMADO</a></li>
                                </ul>
                            </div>
                            <div id="contenidopestanas">
                                <div class="conten" id="cpestana1">
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
                                            <?php echo $controlador->inputs->inm_tipo_credito_id; ?>
                                            <?php echo $controlador->inputs->numero_credito; ?>
                                            <?php echo $controlador->inputs->correo_mi_cuenta_infonavit; ?>
                                            <?php echo $controlador->inputs->password_mi_cuenta_infonavit; ?>
                                            <?php echo $controlador->inputs->fecha_otorgamiento_credito; ?>
                                            <?php echo $controlador->inputs->adeudo_hipoteca; ?>
                                            <?php echo $controlador->inputs->monto_devolucion; ?>

                                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_5; ?>
                                        <div id="apartado_5">
                                            <?php echo $controlador->inputs->cuenta_predial; ?>
                                            <?php echo $controlador->inputs->adeudo_predial; ?>
                                            <?php echo $controlador->inputs->cuenta_agua; ?>
                                            <?php echo $controlador->inputs->adeudo_agua; ?>
                                            <?php echo $controlador->inputs->adeudo_luz; ?>
                                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                        </div>
                                        <?php echo $controlador->header_frontend->apartado_6; ?>
                                        <div id="apartado_6">
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
                                <div class="conten" id="cpestana2">
                                    <form method="post" action="<?php echo $controlador->link_validacion_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <?php echo $controlador->inputs->documento_rppc; ?>

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
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

                                        <?php echo $controlador->inputs->inm_tipo_gasto_id; ?>

                                        <?php echo $controlador->inputs->nombre_beneficiario; ?>
                                        <div id="cont_cheque">
                                            <?php echo $controlador->inputs->inm_tipo_cheque_id; ?>
                                            <?php echo $controlador->inputs->monto; ?>
                                        </div>
                                        <div id="cont_transfer">
                                            <?php echo $controlador->inputs->monto_transferencia; ?>
                                        </div>
                                        <div id="cont_efectivo">
                                            <?php echo $controlador->inputs->efectivo; ?>
                                        </div>
                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success" name="alta_gasto" value="alta_gasto">
                                                    Alta
                                                </button>
                                                <button type="submit" class="btn btn-success" name="avanza_etapa" value="avanza_etapa">
                                                    Avanza Etapa
                                                </button>
                                                <br>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="widget widget-box box-container widget-mylistings">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Beneficiario</th>
                                                        <th>Referencia</th>
                                                        <th>Monto</th>
                                                        <th>Cuenta Bancaria</th>
                                                        <th>Fecha</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="7">Cheques</td>
                                                    </tr>
                                                    <?php
                                                    foreach ($controlador->cheques as $cheque){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $cheque['inm_cheque_id'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_nombre_beneficiario'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_numero_cheque'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_monto'] ?></td>
                                                            <td><?php echo $cheque['bn_cuenta_descripcion'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_fecha_alta'] ?></td>
                                                            <td><?php echo $cheque['elimina_bd'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td colspan="7">Transferencias</td>
                                                    </tr>
                                                    <?php
                                                    foreach ($controlador->transferencias as $transferencia){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $transferencia['inm_transferencia_id'] ?></td>
                                                            <td><?php echo $transferencia['inm_transferencia_nombre_beneficiario'] ?></td>
                                                            <td><?php echo $transferencia['inm_transferencia_transferencia'] ?></td>
                                                            <td><?php echo $transferencia['inm_transferencia_monto'] ?></td>
                                                            <td></td>
                                                            <td><?php echo $transferencia['inm_transferencia_fecha_alta'] ?></td>
                                                            <td><?php echo $transferencia['elimina_bd'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td colspan="7">Efectivo</td>
                                                    </tr>
                                                    <?php
                                                    foreach ($controlador->efectivos as $efectivo){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $efectivo['inm_efectivo_id'] ?></td>
                                                            <td><?php echo $efectivo['inm_efectivo_nombre_beneficiario'] ?></td>
                                                            <td></td>
                                                            <td><?php echo $efectivo['inm_efectivo_monto'] ?></td>
                                                            <td></td>
                                                            <td><?php echo $efectivo['inm_efectivo_fecha_alta'] ?></td>
                                                            <td><?php echo $efectivo['elimina_bd'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana4">
                                    <form method="post" action="<?php echo $controlador->link_emision_de_recurso_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <?php echo $controlador->inputs->nombre_beneficiario_emision; ?>
                                        <div id="cont_cheque_emi">
                                            <?php echo $controlador->inputs->inm_tipo_cheque_sl_id; ?>
                                            <?php echo $controlador->inputs->bn_cuenta_sl_id; ?>

                                            <?php echo $controlador->inputs->numero_cheque; ?>
                                            <?php echo $controlador->inputs->monto_emision; ?>
                                        </div>

                                        <?php //echo $controlador->inputs->numero_cheque_secundario; ?>
                                        <?php //echo $controlador->inputs->monto_cheque_secundario; ?>

                                        <div id="cont_transfer_emi">
                                            <?php echo $controlador->inputs->transferencia; ?>
                                            <?php echo $controlador->inputs->monto_transferencia_emision; ?>
                                        </div>

                                        <?php //echo $controlador->inputs->numero_cheque_comision; ?>
                                        <?php //echo $controlador->inputs->monto_comision; ?>

                                        <div id="cont_efectivo_emi">
                                            <?php echo $controlador->inputs->efectivo_emision; ?>
                                        </div>

                                        <?php echo $controlador->inputs->inm_tipo_gasto_sl_id; ?>
                                        <?php echo $controlador->inputs->registro_ajustar_id; ?>

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success" name="alta_gasto" value="alta_gasto">
                                                    Actualiza
                                                </button>
                                                <button type="submit" class="btn btn-success" name="avanza_etapa" value="avanza_etapa">
                                                    Avanza Etapa
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="widget widget-box box-container widget-mylistings">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Id</th>
                                                        <th>Beneficiario</th>
                                                        <th>Referencia</th>
                                                        <th>Monto</th>
                                                        <th>Cuenta Bancaria</th>
                                                        <th>Fecha</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="8">Cheques</td>
                                                    </tr>
                                                    <?php
                                                    foreach ($controlador->cheques as $cheque){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $cheque['checkbox'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_id'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_nombre_beneficiario'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_numero_cheque'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_monto'] ?></td>
                                                            <td><?php echo $cheque['bn_cuenta_descripcion'] ?></td>
                                                            <td><?php echo $cheque['inm_cheque_fecha_alta'] ?></td>
                                                            <td><?php echo $cheque['elimina_bd'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td colspan="8">Transferencias</td>
                                                    </tr>
                                                    <?php
                                                    foreach ($controlador->transferencias as $transferencia){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $transferencia['checkbox'] ?></td>
                                                            <td><?php echo $transferencia['inm_transferencia_id'] ?></td>
                                                            <td><?php echo $transferencia['inm_transferencia_nombre_beneficiario'] ?></td>
                                                            <td><?php echo $transferencia['inm_transferencia_transferencia'] ?></td>
                                                            <td><?php echo $transferencia['inm_transferencia_monto'] ?></td>
                                                            <td></td>
                                                            <td><?php echo $transferencia['inm_transferencia_fecha_alta'] ?></td>
                                                            <td><?php echo $transferencia['elimina_bd'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td colspan="8">Efectivo</td>
                                                    </tr>
                                                    <?php
                                                    foreach ($controlador->efectivos as $efectivo){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $efectivo['checkbox'] ?></td>
                                                            <td><?php echo $efectivo['inm_efectivo_id'] ?></td>
                                                            <td><?php echo $efectivo['inm_efectivo_nombre_beneficiario'] ?></td>
                                                            <td></td>
                                                            <td><?php echo $efectivo['inm_efectivo_monto'] ?></td>
                                                            <td></td>
                                                            <td><?php echo $efectivo['inm_efectivo_fecha_alta'] ?></td>
                                                            <td><?php echo $efectivo['elimina_bd'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana5">
                                    <form method="post" action="<?php echo $controlador->link_por_firmar_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana6">
                                    <form method="post" action="<?php echo $controlador->link_firmado_por_aprobar_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <?php echo $controlador->inputs->inm_notaria_id; ?>
                                        <?php echo $controlador->inputs->documento_poder; ?>
                                        <?php echo $controlador->inputs->numero_escritura_poder; ?>
                                        <?php echo $controlador->inputs->fecha_poder; ?>

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
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
                                <div class="conten" id="cpestana7">
                                    <form method="post" action="<?php echo $controlador->link_firmado_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="widget widget-box box-container widget-mylistings">
                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Beneficiario</th>
                                                            <th>Referencia</th>
                                                            <th>Monto</th>
                                                            <th>Cuenta Bancaria</th>
                                                            <th>Fecha</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="6">Cheques</td>
                                                        </tr>
                                                        <?php
                                                        foreach ($controlador->cheques as $cheque){
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $cheque['inm_cheque_id'] ?></td>
                                                                <td><?php echo $cheque['inm_cheque_nombre_beneficiario'] ?></td>
                                                                <td><?php echo $cheque['inm_cheque_numero_cheque'] ?></td>
                                                                <td><?php echo $cheque['inm_cheque_monto'] ?></td>
                                                                <td><?php echo $cheque['bn_cuenta_descripcion'] ?></td>
                                                                <td><?php echo $cheque['inm_cheque_fecha_alta'] ?></td>
                                                            </tr>

                                                            <?php echo $cheque['documento'] ?>
                                                        <?php } ?>
                                                        <tr>
                                                            <td colspan="6">Transferencias</td>
                                                        </tr>
                                                        <?php
                                                        foreach ($controlador->transferencias as $transferencia){
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $transferencia['inm_transferencia_id'] ?></td>
                                                                <td><?php echo $transferencia['inm_transferencia_nombre_beneficiario'] ?></td>
                                                                <td><?php echo $transferencia['inm_transferencia_transferencia'] ?></td>
                                                                <td><?php echo $transferencia['inm_transferencia_monto'] ?></td>
                                                                <td></td>
                                                                <td><?php echo $transferencia['inm_transferencia_fecha_alta'] ?></td>
                                                            </tr>

                                                            <?php echo $transferencia['documento'] ?>
                                                        <?php } ?>
                                                        <tr>
                                                            <td colspan="6">Efectivo</td>
                                                        </tr>
                                                        <?php
                                                        foreach ($controlador->efectivos as $efectivo){
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $efectivo['inm_efectivo_id'] ?></td>
                                                                <td><?php echo $efectivo['inm_efectivo_nombre_beneficiario'] ?></td>
                                                                <td></td>
                                                                <td><?php echo $efectivo['inm_efectivo_monto'] ?></td>
                                                                <td></td>
                                                                <td><?php echo $efectivo['inm_efectivo_fecha_alta'] ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
                                    </form>

                                    <!--<form method="post" action="<?php //echo $controlador->link_firmado_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <?php //echo $controlador->inputs->documento_poliza_firmada; ?>
                                        <?php //echo $controlador->inputs->documento_transferencia; ?>
                                        <?php //echo $controlador->inputs->documento_poliza_comision_firmada; ?>
                                        <?php //echo $controlador->inputs->documento_poliza_secundaria_firmada; ?>

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row buttons-form">
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php //echo $controlador->descripcion_poliza_firmada; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_descarga_firmado; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_vista_previa_firmado; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_descarga_zip_firmado; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_elimina_bd_firmado; ?>
                                        </div>
                                    </div>

                                    <div class="row buttons-form">
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php //echo $controlador->descripcion_comprobante_transferencia_firmada; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_descarga_transferencia_firmada; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_vista_previa_transferencia_firmada; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_descarga_zip_transferencia_firmada; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_elimina_bd_transferencia_firmada; ?>
                                        </div>
                                    </div>

                                    <div class="row buttons-form">
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php //echo $controlador->descripcion_poliza_comision_firmada; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_descarga_firmado_comision; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_vista_previa_firmado_comision; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_descarga_zip_firmado_comision; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_elimina_bd_firmado_comision; ?>
                                        </div>
                                    </div>

                                    <div class="row buttons-form">
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php //echo $controlador->descripcion_poliza_secundaria_firmada; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_descarga_firmado_poliza_secundaria; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_vista_previa_firmado_poliza_secundaria; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_descarga_zip_firmado_poliza_secundaria; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php //echo $controlador->button_inm_doc_ubicacion_elimina_bd_firmado_poliza_secundaria; ?>
                                        </div>
                                    </div>-->
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

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <img class="imagen_modal">
    </div>
</div>













