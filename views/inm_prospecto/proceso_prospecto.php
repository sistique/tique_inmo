<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_prospecto_prospecto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div>

        <div class="row">

            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <body onload="
                    javascript:cambiarPestanna_inicialprospecto(pestanasprospecto);
                    javascript:inicializa_estado_civil();">
                    <div id="pestanasprospecto">
                                <ul id="listaprospecto">
                                    <li id="pestanaprospecto1"><a href='javascript:cambiarPestanna(pestanasprospecto,pestanaprospecto1);'>MODIFICA</a></li>
                                    <li id="pestanaprospecto2"><a href='javascript:cambiarPestanna(pestanasprospecto,pestanaprospecto2);'>DOCUMENTOS</a></li>
                                    <li id="pestanaprospecto3"><a href='javascript:cambiarPestanna(pestanasprospecto,pestanaprospecto3);'>INTEGRA RELACION</a></li>
                                    <li id="pestanaprospecto4"><a href='javascript:cambiarPestanna(pestanasprospecto,pestanaprospecto4);'>ETAPA MANUAL</a></li>
                                </ul>
                            </div>
                    <div id="contenidopestanasprospecto">
                        <div class="conten" id="cpestanaprospecto1">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                                                <form method="post" action="<?php echo $controlador->link_modifica_bd; ?>" class="form-additional"
                                                      enctype="multipart/form-data">

                                                    <?php echo $controlador->header_frontend->apartado_1; ?>
                                                    <div id="apartado_1">
                                                        <?php echo $controlador->inputs->org_sucursal_id; ?>
                                                        <?php echo $controlador->inputs->com_agente_id; ?>
                                                        <?php echo $controlador->inputs->com_tipo_prospecto_id; ?>
                                                        <?php echo $controlador->inputs->inm_tipo_venta_id; ?>
                                                        <?php echo $controlador->inputs->inm_institucion_hipotecaria_id; ?>
                                                        <?php echo $controlador->inputs->inm_producto_infonavit_id; ?>
                                                        <?php echo $controlador->inputs->inm_tipo_credito_id; ?>
                                                        <?php echo $controlador->inputs->inm_attr_tipo_credito_id; ?>
                                                        <?php echo $controlador->inputs->inm_destino_credito_id; ?>
                                                        <?php echo $controlador->inputs->es_segundo_credito; ?>
                                                        <?php echo $controlador->inputs->inm_plazo_credito_sc_id; ?>
                                                        <?php echo $controlador->inputs->devolucion; ?>
                                                        <div class="contenido-credito"></div>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_2; ?>
                                                    <div id="apartado_2">
                                                        <!-- Identificadores -->
                                                        <?php echo $controlador->inputs->nss; ?>
                                                        <?php echo $controlador->inputs->nombre; ?>
                                                        <?php echo $controlador->inputs->apellido_paterno; ?>
                                                        <?php echo $controlador->inputs->apellido_materno; ?>

                                                        <?php echo $controlador->inputs->curp; ?>
                                                        <?php echo $controlador->inputs->rfc; ?>

                                                        <!-- Nacimiento -->
                                                        <?php echo $controlador->inputs->fecha_nacimiento; ?>
                                                        <?php echo $controlador->inputs->dp_estado_nacimiento_id; ?>
                                                        <?php echo $controlador->inputs->dp_municipio_nacimiento_id; ?>
                                                        <?php echo $controlador->inputs->inm_nacionalidad_id; ?>
                                                        <?php echo $controlador->inputs->genero; ?>


                                                        <!-- Estado civil y ocupación -->
                                                        <?php echo $controlador->inputs->adm_estado_civil_id; ?>
                                                        <?php echo $controlador->inputs->inm_estado_civil_id; ?>
                                                        <?php echo $controlador->inputs->inm_ocupacion_id; ?>

                                                        <!-- Contacto -->
                                                        <?php echo $controlador->inputs->numero_com; ?>
                                                        <?php echo $controlador->inputs->cel_com; ?>
                                                        <?php echo $controlador->inputs->correo_com; ?>

                                                        <!-- Domicilio -->
                                                        <?php echo $controlador->inputs->dp_estado_id; ?>
                                                        <?php echo $controlador->inputs->dp_municipio_id; ?>
                                                        <?php echo $controlador->inputs->dp_cp_id; ?>
                                                        <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                                                        <?php echo $controlador->inputs->calle; ?>
                                                        <?php echo $controlador->inputs->numero_exterior; ?>
                                                        <?php echo $controlador->inputs->numero_interior; ?>

                                                        <?php echo $controlador->inputs->observaciones; ?>

                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_3; ?>
                                                    <div id="apartado_3">
                                                        <?php echo $controlador->inputs->descuento_pension_alimenticia_dh; ?>
                                                        <?php echo $controlador->inputs->descuento_pension_alimenticia_fc; ?>
                                                        <?php echo $controlador->inputs->monto_credito_solicitado_dh; ?>
                                                        <?php echo $controlador->inputs->monto_ahorro_voluntario; ?>
                                                        <?php echo $controlador->inputs->sub_cuenta; ?>
                                                        <?php echo $controlador->inputs->monto_final; ?>
                                                        <?php echo $controlador->inputs->descuento; ?>
                                                        <?php echo $controlador->inputs->puntos; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_4; ?>
                                                    <div id="apartado_4">
                                                        <?php echo $controlador->inputs->con_discapacidad; ?>
                                                        <?php echo $controlador->inputs->inm_tipo_discapacidad_id; ?>
                                                        <?php echo $controlador->inputs->inm_persona_discapacidad_id; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_5; ?>
                                                    <div id="apartado_5">
                                                        <?php echo $controlador->inputs->nombre_empresa_patron; ?>
                                                        <?php echo $controlador->inputs->nrp_nep; ?>
                                                        <?php echo $controlador->inputs->numero_nep; ?>
                                                        <?php echo $controlador->inputs->extension_nep; ?>
                                                        <?php echo $controlador->inputs->correo_empresa; ?>
                                                        <?php echo $controlador->inputs->area_empresa; ?>
                                                        <?php echo $controlador->inputs->inm_sindicato_id; ?>
                                                        <?php echo $controlador->inputs->direccion_empresa; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_6; ?>
                                                    <div  id="apartado_6">
                                                        <?php echo $controlador->inputs->inm_co_acreditado->nss; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->curp; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->rfc; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->nombre; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->apellido_paterno; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->apellido_materno; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->numero; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->celular; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->correo; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->genero; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->numero_credito; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->adeudo_hipoteca; ?>

                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_7; ?>
                                                    <div  id="apartado_7">
                                                        <?php echo $controlador->inputs->inm_co_acreditado->nombre_empresa; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->nrp; ?>
                                                        <?php echo $controlador->inputs->inm_co_acreditado->numero_nep; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_8; ?>
                                                    <div id="apartado_8">
                                                        <!-- Identificadores -->
                                                        <?php echo $controlador->inputs->conyuge->curp; ?>
                                                        <?php echo $controlador->inputs->conyuge->rfc; ?>

                                                        <!-- Identidad -->
                                                        <?php echo $controlador->inputs->conyuge->nombre; ?>
                                                        <?php echo $controlador->inputs->conyuge->apellido_paterno; ?>
                                                        <?php echo $controlador->inputs->conyuge->apellido_materno; ?>

                                                        <!-- Nacimiento -->
                                                        <?php echo $controlador->inputs->conyuge->inm_ocupacion_id; ?>
                                                        <?php echo $controlador->inputs->conyuge->fecha_nacimiento; ?>
                                                        <?php echo $controlador->inputs->conyuge->dp_estado_id; ?>
                                                        <?php echo $controlador->inputs->conyuge->dp_municipio_id; ?>
                                                        <?php echo $controlador->inputs->conyuge->inm_nacionalidad_id; ?>

                                                        <!-- Contacto -->
                                                        <?php echo $controlador->inputs->conyuge->telefono_casa; ?>
                                                        <?php echo $controlador->inputs->conyuge->telefono_celular; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_9; ?>
                                                    <div id="apartado_9">
                                                        <?php echo $controlador->inputs->beneficiario->inm_tipo_beneficiario_id; ?>
                                                        <?php echo $controlador->inputs->beneficiario->inm_parentesco_id; ?>
                                                        <?php echo $controlador->inputs->beneficiario->nombre; ?>
                                                        <?php echo $controlador->inputs->beneficiario->apellido_paterno; ?>
                                                        <?php echo $controlador->inputs->beneficiario->apellido_materno; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                                        <div class="col-md-12 table-responsive gt_beneficiario_table">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th>Id</th>
                                                                    <th>Tipo Beneficiario</th>
                                                                    <th>Parentesco</th>
                                                                    <th>Nombre</th>
                                                                    <th>Apellido Paterno</th>
                                                                    <th>Apellido Materno</th>
                                                                    <th>Elimina</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php foreach ($controlador->beneficiarios as $beneficiario) { ?>
                                                                    <tr>
                                                                        <td><?php echo $beneficiario['inm_beneficiario_id']; ?></td>
                                                                        <td><?php echo $beneficiario['inm_tipo_beneficiario_descripcion']; ?></td>
                                                                        <td><?php echo $beneficiario['inm_parentesco_descripcion']; ?></td>
                                                                        <td><?php echo $beneficiario['inm_beneficiario_nombre']; ?></td>
                                                                        <td><?php echo $beneficiario['inm_beneficiario_apellido_paterno']; ?></td>
                                                                        <td><?php echo $beneficiario['inm_beneficiario_apellido_materno']; ?></td>
                                                                        <td><?php echo $beneficiario['btn_del']; ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_10; ?>
                                                    <div id="apartado_10">
                                                        <?php echo $controlador->inputs->referencia->nombre; ?>
                                                        <?php echo $controlador->inputs->referencia->apellido_paterno; ?>
                                                        <?php echo $controlador->inputs->referencia->apellido_materno; ?>
                                                        <?php echo $controlador->inputs->referencia->inm_parentesco_id; ?>
                                                        <?php echo $controlador->inputs->referencia->numero; ?>
                                                        <?php echo $controlador->inputs->referencia->celular; ?>
                                                        <?php echo $controlador->inputs->referencia->dp_estado_id; ?>
                                                        <?php echo $controlador->inputs->referencia->dp_municipio_id; ?>
                                                        <?php echo $controlador->inputs->referencia->dp_cp_id; ?>
                                                        <?php echo $controlador->inputs->referencia->dp_colonia_postal_id; ?>
                                                        <?php echo $controlador->inputs->referencia->calle; ?>
                                                        <?php echo $controlador->inputs->referencia->numero_exterior; ?>
                                                        <?php echo $controlador->inputs->referencia->numero_interior; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

                                                        <div class="col-md-12 table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th>Id</th>
                                                                    <th>Nombre</th>
                                                                    <th>AP</th>
                                                                    <th>AM</th>
                                                                    <th>Parentesco</th>
                                                                    <th>Celular</th>
                                                                    <th>Elimina</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php foreach ($controlador->referencias as $referencia) { ?>
                                                                    <tr>
                                                                        <td><?php echo $referencia['inm_referencia_id']; ?></td>
                                                                        <td><?php echo $referencia['inm_referencia_nombre']; ?></td>
                                                                        <td><?php echo $referencia['inm_referencia_apellido_paterno']; ?></td>
                                                                        <td><?php echo $referencia['inm_referencia_apellido_materno']; ?></td>
                                                                        <td><?php echo $referencia['inm_parentesco_descripcion']; ?></td>
                                                                        <td><?php echo $referencia['inm_referencia_celular']; ?></td>
                                                                        <td><?php echo $referencia['btn_del']; ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_11; ?>
                                                    <div id="apartado_11">
                                                        <!-- Domicilio fiscal -->
                                                        <?php echo $controlador->inputs->dp_estado_id; ?>
                                                        <?php echo $controlador->inputs->dp_municipio_id; ?>
                                                        <?php echo $controlador->inputs->dp_cp_id; ?>
                                                        <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                                                        <?php echo $controlador->inputs->calle; ?>
                                                        <?php echo $controlador->inputs->numero_exterior; ?>
                                                        <?php echo $controlador->inputs->numero_interior; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>

                                                    <?php echo $controlador->header_frontend->apartado_12; ?>
                                                    <div id="apartado_12">
                                                        <?php echo $controlador->inputs->nss_extra; ?>
                                                        <?php echo $controlador->inputs->correo_mi_cuenta_infonavit; ?>
                                                        <?php echo $controlador->inputs->password_mi_cuenta_infonavit; ?>
                                                        <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                        <div class="conten" id="cpestanaprospecto2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widget widget-box box-container widget-mylistings">
                                        <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_documento_bd; ?>" class="form-additional">
                                            <div class="content_table">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Tipo Documento</th>
                                                        <?php
                                                        if($controlador->ver_descripcion){
                                                        ?>
                                                        <th>Descarga</th>
                                                        <th>Vista Previa</th>
                                                        <th>Zip</th>
                                                        <th>Elimina</th>
                                                        <?php } ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($controlador->inm_conf_docs_prospecto as $docs) {
                                                        echo $docs;
                                                    }?>
                                                    </tbody>

                                                </table>
                                            </div>
                                            <?php echo $controlador->inputs->btn_action_next; ?>
                                            <?php echo $controlador->inputs->id_retorno; ?>
                                            <?php echo $controlador->inputs->seccion_retorno; ?>
                                            <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="conten" id="cpestanaprospecto3">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                                                <form method="post" action="<?php echo $controlador->link_alta_integra_relacion_bd; ?>" class="form-additional">
                                                    <?php echo $controlador->inputs->razon_social; ?>
                                                    <?php echo $controlador->inputs->com_agente_id; ?>

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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <div class="conten" id="cpestanaprospecto4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                                                <form method="post" action="<?php echo $controlador->link_alta_bitacora; ?>" class="form-additional">
                                                    <?php echo $controlador->inputs->inm_prospecto_id; ?>
                                                    <?php echo $controlador->inputs->inm_status_prospecto_id; ?>
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
                                                            <td><?php echo $etapa['inm_bitacora_status_prospecto_id'] ?></td>
                                                            <td><?php echo $etapa['inm_status_prospecto_descripcion'] ?></td>
                                                            <td><?php echo $etapa['inm_bitacora_status_prospecto_fecha_status'] ?></td>
                                                            <td><?php echo $etapa['inm_bitacora_status_prospecto_observaciones'] ?></td>
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
            </div>
        </div>
    </div>
</main>

<dialog id="myModal">
    <span class="close-btn" id="closeModalBtn">&times;</span>

    <button type="button" class="preview-arrow preview-prev" id="previewPrev">
        &#10094;
    </button>

    <div class="content"></div>

    <button type="button" class="preview-arrow preview-next" id="previewNext">
        &#10095;
    </button>
</dialog>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <img class="imagen_modal">
    </div>
</div>













