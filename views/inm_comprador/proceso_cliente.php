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

                    <?php echo $controlador->inputs->inm_comprador_seleccionado_id; ?>
                    <div id="pestanasgeneral">
                        <ul id="listageneral">
                            <li id="pestanageneral1"><a href='javascript:cambiarPestannaGeneralCl(pestanasgeneral,pestanageneral1,pestanascliente);'>CLIENTE</a></li>
                            <li id="pestanageneral2"><a href='javascript:cambiarPestannaGeneralCl(pestanasgeneral,pestanageneral2,pestanas);'>ETAPAS</a></li>
                        </ul>
                    </div>
                    <body onload="javascript:cambiarPestannaGeneral_inicial(pestanasgeneral);
                    javascript:valor_inicial();
                    javascript:cambiarPestanna_inicialcliente(pestanascliente);
                    javascript:inicializa_conyuge();
                    javascript:inicializa_estado_civil();">
                    <div id="contenidopestanasgeneral">
                        <div class="contengeneral" id="cpestanageneral1">
                            <div id="pestanascliente">
                                <ul id="listacliente">
                                    <li id="pestanacliente1"><a href='javascript:cambiarPestanna(pestanascliente,pestanacliente1);'>MODIFICA</a></li>
                                    <li id="pestanacliente2"><a href='javascript:cambiarPestanna(pestanascliente,pestanacliente2);'>DOCUMENTOS</a></li>
                                    <li id="pestanacliente3"><a href='javascript:cambiarPestanna(pestanascliente,pestanacliente3);'>ETAPA MANUAL</a></li>
                                    <li id="pestanacliente4"><a href='javascript:cambiarPestanna(pestanascliente,pestanacliente4);'>ASIGNA UBICACION</a></li>
                                </ul>
                            </div>
                            <div id="contenidopestanascliente">
                                <div class="conten" id="cpestanacliente1">
                                    <?php
                                    $checked_genero_m = 'checked';
                                    $checked_genero_f = '';
                                    if($controlador->row_upd->genero === 'F'){
                                        $checked_genero_m = '';
                                        $checked_genero_f = 'checked';
                                    }

                                    $checked_genero_co_m = 'checked';
                                    $checked_genero_co_f = '';
                                    if($controlador->row_upd->genero_co_acreditado === 'F'){
                                        $checked_genero_co_m = '';
                                        $checked_genero_co_f = 'checked';
                                    }
                                    ?>
                                    <form method="post" action="<?php echo $controlador->link_modifica_bd; ?>" class="form-additional"
                                          enctype="multipart/form-data">
                                    <?php echo $controlador->header_frontend->apartado_1; ?>

                                    <div  id="apartado_1">
                                        <?php echo $controlador->inputs->inm_institucion_hipotecaria_id; ?>
                                        <?php echo $controlador->inputs->inm_producto_infonavit_id; ?>
                                        <?php echo $controlador->inputs->inm_tipo_credito_id; ?>
                                        <?php echo $controlador->inputs->inm_attr_tipo_credito_id; ?>
                                        <?php echo $controlador->inputs->inm_destino_credito_id; ?>
                                        <?php echo $controlador->inputs->es_segundo_credito; ?>
                                        <?php echo $controlador->inputs->inm_plazo_credito_sc_id; ?>
                                        <?php echo $controlador->btn; ?>
                                    </div>


                                    <?php echo $controlador->header_frontend->apartado_2; ?>

                                    <div  id="apartado_2">
                                        <?php echo $controlador->inputs->descuento_pension_alimenticia_dh; ?>
                                        <?php echo $controlador->inputs->descuento_pension_alimenticia_fc; ?>
                                        <?php echo $controlador->inputs->monto_credito_solicitado_dh; ?>
                                        <?php echo $controlador->inputs->monto_ahorro_voluntario; ?>
                                        <?php echo $controlador->inputs->sub_cuenta; ?>
                                        <?php echo $controlador->inputs->monto_final; ?>
                                        <?php echo $controlador->inputs->descuento; ?>
                                        <?php echo $controlador->inputs->puntos; ?>
                                        <?php echo $controlador->btn; ?>
                                    </div>


                                    <?php echo $controlador->header_frontend->apartado_3; ?>

                                    <div  id="apartado_3">
                                        <?php echo $controlador->inputs->con_discapacidad; ?>
                                        <?php echo $controlador->inputs->inm_tipo_discapacidad_id; ?>
                                        <?php echo $controlador->inputs->inm_persona_discapacidad_id; ?>
                                        <?php echo $controlador->btn; ?>
                                    </div>

                                    <?php echo $controlador->header_frontend->apartado_4; ?>


                                    <div  id="apartado_4">
                                        <?php echo $controlador->inputs->nombre_empresa_patron; ?>
                                        <?php echo $controlador->inputs->nrp_nep; ?>
                                        <?php echo $controlador->inputs->lada_nep; ?>
                                        <?php echo $controlador->inputs->numero_nep; ?>
                                        <?php echo $controlador->inputs->extension_nep; ?>
                                        <?php echo $controlador->inputs->inm_sindicato_id; ?>
                                        <?php echo $controlador->inputs->correo_empresa; ?>
                                        <?php echo $controlador->btn; ?>

                                    </div>


                                    <?php echo $controlador->header_frontend->apartado_5; ?>

                                    <div  id="apartado_5">
                                        <?php echo $controlador->inputs->nss; ?>
                                        <?php echo $controlador->inputs->curp; ?>
                                        <?php echo $controlador->inputs->rfc; ?>
                                        <?php echo $controlador->inputs->nombre; ?>
                                        <?php echo $controlador->inputs->apellido_paterno; ?>
                                        <?php echo $controlador->inputs->apellido_materno; ?>
                                        <?php echo $controlador->inputs->dp_pais_id; ?>
                                        <?php echo $controlador->inputs->dp_estado_id; ?>
                                        <?php echo $controlador->inputs->dp_municipio_id; ?>
                                        <?php echo $controlador->inputs->dp_cp_id; ?>
                                        <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                                        <?php echo $controlador->inputs->calle; ?>
                                        <?php echo $controlador->inputs->numero_exterior; ?>
                                        <?php echo $controlador->inputs->numero_interior; ?>
                                        <?php echo $controlador->inputs->lada_com; ?>
                                        <?php echo $controlador->inputs->numero_com; ?>
                                        <?php echo $controlador->inputs->cel_com; ?>

                                        <div class="control-group col-sm-6">
                                            <label class="control-label" for="inm_attr_tipo_credito_id">Genero</label>
                                            <label class="form-check-label chk">
                                                <input type="radio" name="genero" value="M"
                                                       class="form-check-input" id="genero"
                                                       title="Genero" <?php echo $checked_genero_m; ?> >
                                                M
                                            </label>
                                            <label class="form-check-label chk">
                                                <input type="radio" name="genero" value="F"
                                                       class="form-check-input" id="genero"
                                                       title="Genero" <?php echo $checked_genero_f; ?>>
                                                F
                                            </label>
                                        </div>

                                        <?php echo $controlador->inputs->correo_com; ?>
                                        <?php echo $controlador->inputs->adm_estado_civil_id; ?>
                                        <?php echo $controlador->inputs->inm_estado_civil_id; ?>
                                        <?php echo $controlador->inputs->dp_estado_nacimiento_id; ?>
                                        <?php echo $controlador->inputs->dp_municipio_nacimiento_id; ?>
                                        <?php echo $controlador->inputs->fecha_nacimiento; ?>
                                        <?php echo $controlador->inputs->inm_nacionalidad_id; ?>
                                        <?php echo $controlador->inputs->inm_ocupacion_id; ?>
                                        <?php echo $controlador->inputs->telefono_casa; ?>
                                        <?php echo $controlador->btn; ?>

                                    </div>
                                        <?php echo $controlador->header_frontend->apartado_6; ?>
                                        <div  id="apartado_6">
                                            <?php echo $controlador->inputs->inm_co_acreditado->nss; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->curp; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->rfc; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->nombre; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->apellido_paterno; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->apellido_materno; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->numero_credito; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->adeudo_hipoteca; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->lada; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->numero; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->celular; ?>
                                            <div class="control-group col-sm-6">
                                                <label class="control-label" for="inm_attr_tipo_credito_id">Genero</label>
                                                <label class="form-check-label chk">
                                                    <input type="radio" name="co_acreditado[genero]" value="M" class="form-check-input" id="genero"
                                                           title="Genero" <?php echo $checked_genero_co_m; ?>>
                                                    M
                                                </label>
                                                <label class="form-check-label chk">
                                                    <input type="radio" name="co_acreditado[genero]" value="F" class="form-check-input" id="genero"
                                                           title="Genero" <?php echo $checked_genero_co_f; ?>>
                                                    F
                                                </label>
                                            </div>
                                            <?php echo $controlador->inputs->inm_co_acreditado->correo; ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_7; ?>
                                        <div  id="apartado_7">
                                            <?php echo $controlador->inputs->inm_co_acreditado->nombre_empresa_patron; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->nrp; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->lada_nep; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->numero_nep; ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>

                                    <?php echo $controlador->header_frontend->apartado_8; ?>

                                    <div  id="apartado_8">

                                        <?php echo $controlador->inputs->cat_sat_regimen_fiscal_id; ?>
                                        <?php echo $controlador->inputs->cat_sat_moneda_id; ?>
                                        <?php echo $controlador->inputs->cat_sat_forma_pago_id; ?>
                                        <?php echo $controlador->inputs->cat_sat_metodo_pago_id; ?>
                                        <?php echo $controlador->inputs->cat_sat_uso_cfdi_id; ?>
                                        <?php echo $controlador->inputs->cat_sat_tipo_persona_id; ?>
                                        <?php echo $controlador->inputs->bn_cuenta_id; ?>
                                        <?php echo $controlador->btn; ?>

                                    </div>

                                    <?php echo $controlador->header_frontend->apartado_9; ?>

                                    <div  id="apartado_9">
                                        <?php echo $controlador->inputs->com_tipo_cliente_id; ?>
                                        <?php echo $controlador->btn; ?>
                                    </div>

                                    <?php echo $controlador->header_frontend->apartado_10; ?>
                                    <div  id="apartado_10">
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
                                        <?php echo $controlador->inputs->conyuge->telefono_celular;  ?>
                                        <?php echo $controlador->btn; ?>
                                    </div>

                                    <?php echo $controlador->header_frontend->apartado_11; ?>
                                    <div  id="apartado_11">
                                        <?php echo $controlador->inputs->beneficiario->inm_tipo_beneficiario_id; ?>
                                        <?php echo $controlador->inputs->beneficiario->inm_parentesco_id; ?>
                                        <?php echo $controlador->inputs->beneficiario->nombre; ?>
                                        <?php echo $controlador->inputs->beneficiario->apellido_paterno; ?>
                                        <?php echo $controlador->inputs->beneficiario->apellido_materno; ?>
                                        <?php echo $controlador->btn; ?>
                                        <div class="col-md-12 table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Tipo Beneficiario</th>
                                                    <th>Parentesco</th>
                                                    <th>Nombre</th>
                                                    <th>Apellido Paterno</th>
                                                    <th>Apellido Materno</th>
                                                    <th>Modifica</th>
                                                    <th>Elimina</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($controlador->beneficiarios as $beneficiario){ ?>
                                                    <tr>
                                                        <td><?php echo $beneficiario['inm_beneficiario_id']; ?></td>
                                                        <td><?php echo $beneficiario['inm_tipo_beneficiario_descripcion']; ?></td>
                                                        <td><?php echo $beneficiario['inm_parentesco_descripcion']; ?></td>
                                                        <td><?php echo $beneficiario['inm_beneficiario_nombre']; ?></td>
                                                        <td><?php echo $beneficiario['inm_beneficiario_apellido_paterno']; ?></td>
                                                        <td><?php echo $beneficiario['inm_beneficiario_apellido_materno']; ?></td>
                                                        <td><?php echo $beneficiario['btn_mod']; ?></td>
                                                        <td><?php echo $beneficiario['btn_del']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                    <?php echo $controlador->header_frontend->apartado_12; ?>
                                    <div  id="apartado_12">
                                        <?php echo $controlador->inputs->referencia->nombre; ?>
                                        <?php echo $controlador->inputs->referencia->apellido_paterno; ?>
                                        <?php echo $controlador->inputs->referencia->apellido_materno; ?>
                                        <?php echo $controlador->inputs->referencia->lada; ?>
                                        <?php echo $controlador->inputs->referencia->numero; ?>
                                        <?php echo $controlador->inputs->referencia->celular; ?>
                                        <?php echo $controlador->inputs->referencia->dp_estado_id; ?>
                                        <?php echo $controlador->inputs->referencia->dp_municipio_id; ?>
                                        <?php echo $controlador->inputs->referencia->dp_cp_id; ?>
                                        <?php echo $controlador->inputs->referencia->dp_colonia_postal_id; ?>
                                        <?php echo $controlador->inputs->referencia->calle; ?>
                                        <?php echo $controlador->inputs->referencia->numero_exterior; ?>
                                        <?php echo $controlador->inputs->referencia->numero_interior; ?>
                                        <?php echo $controlador->inputs->referencia->inm_parentesco_id; ?>
                                        <div class="col-md-12 table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Nombre</th>
                                                    <th>AP</th>
                                                    <th>AM</th>
                                                    <th>Parentesto</th>
                                                    <th>Celular</th>
                                                    <th>Modifica</th>
                                                    <th>Elimina</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($controlador->referencias as $referencia){ ?>
                                                    <tr>
                                                        <td><?php echo $referencia['inm_referencia_id']; ?></td>
                                                        <td><?php echo $referencia['inm_referencia_nombre']; ?></td>
                                                        <td><?php echo $referencia['inm_referencia_apellido_paterno']; ?></td>
                                                        <td><?php echo $referencia['inm_referencia_apellido_materno']; ?></td>
                                                        <td><?php echo $referencia['inm_parentesco_descripcion']; ?></td>
                                                        <td><?php echo $referencia['inm_referencia_celular']; ?></td>
                                                        <td><?php echo $referencia['btn_mod']; ?></td>
                                                        <td><?php echo $referencia['btn_del']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                    <?php echo $controlador->btn; ?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestanacliente2">
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
                                                        foreach ($controlador->inm_conf_docs_comprador as $docs) {
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

                                    <!--<div>
                                        <div class="row">
                                            <div class="col-lg-12 table-responsive">
                                                <table id="table-inm_comprador" class="table mb-0 table-striped table-sm "></table>
                                            </div>
                                        </div>
                                    </div>-->
                                </div>
                                <div class="conten" id="cpestanacliente3">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                                                <form method="post" action="<?php echo $controlador->link_alta_bitacora; ?>" class="form-additional">
                                                    <?php echo $controlador->inputs->inm_comprador_id; ?>
                                                    <?php echo $controlador->inputs->inm_status_comprador_id; ?>
                                                    <?php echo $controlador->inputs->fecha_etapa; ?>
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
                                                            <td><?php echo $etapa['inm_bitacora_status_comprador_id'] ?></td>
                                                            <td><?php echo $etapa['inm_status_comprador_descripcion'] ?></td>
                                                            <td><?php echo $etapa['inm_bitacora_status_comprador_fecha_status'] ?></td>
                                                            <td><?php echo $etapa['inm_bitacora_status_comprador_observaciones'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div> <!-- /. widget-table-->
                                        </div><!-- /.center-content -->
                                    </div>
                                </div>
                                <div class="conten" id="cpestanacliente4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                                                <?php echo $controlador->inputs->registro_id; ?>
                                                <form method="post" action="<?php echo $controlador->link_rel_ubi_comp_alta_bd; ?>"
                                                      class="form-additional">

                                                    <?php echo $controlador->inputs->inm_ubicacion_util_id; ?>
                                                    <?php echo $controlador->inputs->precio_operacion; ?>

                                                    <?php echo $controlador->inputs->com_tipo_cliente_id; ?>
                                                    <?php echo $controlador->inputs->nss; ?>
                                                    <?php echo $controlador->inputs->curp; ?>
                                                    <?php echo $controlador->inputs->rfc; ?>
                                                    <?php echo $controlador->inputs->apellido_paterno; ?>
                                                    <?php echo $controlador->inputs->apellido_materno; ?>
                                                    <?php echo $controlador->inputs->nombre; ?>
                                                    <?php echo $controlador->inputs->inm_comprador_id; ?>
                                                    <?php echo $controlador->inputs->seccion_retorno; ?>
                                                    <?php echo $controlador->inputs->btn_action_next; ?>
                                                    <?php echo $controlador->inputs->id_retorno; ?>
                                                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                                </form>
                                            </div>
                                        </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="widget widget-box box-container widget-mylistings">
                                                        <div class="widget-header" style="display: flex;justify-content: space-between;align-items: center;">
                                                            <h2>Ubicaciones</h2>
                                                        </div>
                                                        <div class="table table-responsive">
                                                            <table class='table table-striped data-partida'>
                                                                <thead>
                                                                <tr>
                                                                    <th>Id</th>
                                                                    <th>Fecha Asignacion</th>
                                                                    <th>Estatus de Asignacion</th>
                                                                    <th>Direccion</th>
                                                                    <th>Manzana</th>
                                                                    <th>Lote</th>
                                                                <tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php    foreach ($controlador->inm_ubicaciones as $inm_ubicacion){ ?>
                                                                <tr>
                                                                    <td><?php echo $inm_ubicacion['inm_ubicacion_id'] ?></td>
                                                                    <td><?php echo $inm_ubicacion['inm_rel_ubi_comp_fecha_alta'] ?></td>
                                                                    <td><?php echo $inm_ubicacion['inm_rel_ubi_comp_status'] ?></td>
                                                                    <td><?php echo $inm_ubicacion['inm_ubicacion_ubicacion'] ?></td>
                                                                    <td><?php echo $inm_ubicacion['inm_ubicacion_manzana'] ?></td>
                                                                    <td><?php echo $inm_ubicacion['inm_ubicacion_lote'] ?></td>
                                                                <tr>
                                                                    <?php }  ?>
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
                        <div class="contengeneral" id="cpestanageneral2">
                            <div id="pestanas">
                                <ul id=listacl>
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
                            <div id="contenidopestanas">
                                <div class="conten" id="cpestana1">
                                    <form method="post" action="<?php echo $controlador->link_modifica_bd; ?>" class="form-additional"
                                          enctype="multipart/form-data">
                                        <?php echo $controlador->header_frontend->apartado_1; ?>

                                        <div  id="apartado_1">
                                            <?php echo $controlador->inputs->inm_institucion_hipotecaria_id; ?>
                                            <?php echo $controlador->inputs->inm_producto_infonavit_id; ?>
                                            <?php echo $controlador->inputs->inm_tipo_credito_id; ?>
                                            <?php echo $controlador->inputs->inm_attr_tipo_credito_id; ?>
                                            <?php echo $controlador->inputs->inm_destino_credito_id; ?>
                                            <?php echo $controlador->inputs->es_segundo_credito; ?>
                                            <?php echo $controlador->inputs->inm_plazo_credito_sc_id; ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>


                                        <?php echo $controlador->header_frontend->apartado_2; ?>

                                        <div  id="apartado_2">
                                            <?php echo $controlador->inputs->descuento_pension_alimenticia_dh; ?>
                                            <?php echo $controlador->inputs->descuento_pension_alimenticia_fc; ?>
                                            <?php echo $controlador->inputs->monto_credito_solicitado_dh; ?>
                                            <?php echo $controlador->inputs->monto_ahorro_voluntario; ?>
                                            <?php echo $controlador->inputs->sub_cuenta; ?>
                                            <?php echo $controlador->inputs->monto_final; ?>
                                            <?php echo $controlador->inputs->descuento; ?>
                                            <?php echo $controlador->inputs->puntos; ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>


                                        <?php echo $controlador->header_frontend->apartado_3; ?>

                                        <div  id="apartado_3">
                                            <?php echo $controlador->inputs->con_discapacidad; ?>
                                            <?php echo $controlador->inputs->inm_tipo_discapacidad_id; ?>
                                            <?php echo $controlador->inputs->inm_persona_discapacidad_id; ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_4; ?>


                                        <div  id="apartado_4">
                                            <?php echo $controlador->inputs->nombre_empresa_patron; ?>
                                            <?php echo $controlador->inputs->nrp_nep; ?>
                                            <?php echo $controlador->inputs->lada_nep; ?>
                                            <?php echo $controlador->inputs->numero_nep; ?>
                                            <?php echo $controlador->inputs->extension_nep; ?>
                                            <?php echo $controlador->inputs->inm_sindicato_id; ?>
                                            <?php echo $controlador->inputs->correo_empresa; ?>
                                            <?php echo $controlador->btn; ?>

                                        </div>


                                        <?php echo $controlador->header_frontend->apartado_5; ?>

                                        <div  id="apartado_5">
                                            <?php echo $controlador->inputs->nss; ?>
                                            <?php echo $controlador->inputs->curp; ?>
                                            <?php echo $controlador->inputs->rfc; ?>
                                            <?php echo $controlador->inputs->nombre; ?>
                                            <?php echo $controlador->inputs->apellido_paterno; ?>
                                            <?php echo $controlador->inputs->apellido_materno; ?>
                                            <?php echo $controlador->inputs->dp_pais_id; ?>
                                            <?php echo $controlador->inputs->dp_estado_id; ?>
                                            <?php echo $controlador->inputs->dp_municipio_id; ?>
                                            <?php echo $controlador->inputs->dp_cp_id; ?>
                                            <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                                            <?php echo $controlador->inputs->calle; ?>
                                            <?php echo $controlador->inputs->numero_exterior; ?>
                                            <?php echo $controlador->inputs->numero_interior; ?>
                                            <?php echo $controlador->inputs->lada_com; ?>
                                            <?php echo $controlador->inputs->numero_com; ?>
                                            <?php echo $controlador->inputs->cel_com; ?>

                                            <div class="control-group col-sm-6">
                                                <label class="control-label" for="inm_attr_tipo_credito_id">Genero</label>
                                                <label class="form-check-label chk">
                                                    <input type="radio" name="genero" value="M"
                                                           class="form-check-input" id="genero"
                                                           title="Genero" <?php echo $checked_genero_m; ?> >
                                                    M
                                                </label>
                                                <label class="form-check-label chk">
                                                    <input type="radio" name="genero" value="F"
                                                           class="form-check-input" id="genero"
                                                           title="Genero" <?php echo $checked_genero_f; ?>>
                                                    F
                                                </label>
                                            </div>

                                            <?php echo $controlador->inputs->correo_com; ?>
                                            <?php echo $controlador->inputs->adm_estado_civil_id; ?>
                                            <?php echo $controlador->inputs->inm_estado_civil_id; ?>
                                            <?php echo $controlador->inputs->dp_estado_nacimiento_id; ?>
                                            <?php echo $controlador->inputs->dp_municipio_nacimiento_id; ?>
                                            <?php echo $controlador->inputs->fecha_nacimiento; ?>
                                            <?php echo $controlador->inputs->inm_nacionalidad_id; ?>
                                            <?php echo $controlador->inputs->inm_ocupacion_id; ?>
                                            <?php echo $controlador->inputs->telefono_casa; ?>
                                            <?php echo $controlador->btn; ?>

                                        </div>
                                        <?php echo $controlador->header_frontend->apartado_6; ?>
                                        <div  id="apartado_6">
                                            <?php echo $controlador->inputs->inm_co_acreditado->nss; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->curp; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->rfc; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->nombre; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->apellido_paterno; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->apellido_materno; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->numero_credito; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->adeudo_hipoteca; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->lada; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->numero; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->celular; ?>
                                            <div class="control-group col-sm-6">
                                                <label class="control-label" for="inm_attr_tipo_credito_id">Genero</label>
                                                <label class="form-check-label chk">
                                                    <input type="radio" name="co_acreditado[genero]" value="M" class="form-check-input" id="genero"
                                                           title="Genero" <?php echo $checked_genero_co_m; ?>>
                                                    M
                                                </label>
                                                <label class="form-check-label chk">
                                                    <input type="radio" name="co_acreditado[genero]" value="F" class="form-check-input" id="genero"
                                                           title="Genero" <?php echo $checked_genero_co_f; ?>>
                                                    F
                                                </label>
                                            </div>
                                            <?php echo $controlador->inputs->inm_co_acreditado->correo; ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_7; ?>
                                        <div  id="apartado_7">
                                            <?php echo $controlador->inputs->inm_co_acreditado->nombre_empresa_patron; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->nrp; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->lada_nep; ?>
                                            <?php echo $controlador->inputs->inm_co_acreditado->numero_nep; ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_8; ?>

                                        <div  id="apartado_8">

                                            <?php echo $controlador->inputs->cat_sat_regimen_fiscal_id; ?>
                                            <?php echo $controlador->inputs->cat_sat_moneda_id; ?>
                                            <?php echo $controlador->inputs->cat_sat_forma_pago_id; ?>
                                            <?php echo $controlador->inputs->cat_sat_metodo_pago_id; ?>
                                            <?php echo $controlador->inputs->cat_sat_uso_cfdi_id; ?>
                                            <?php echo $controlador->inputs->cat_sat_tipo_persona_id; ?>
                                            <?php echo $controlador->inputs->bn_cuenta_id; ?>
                                            <?php echo $controlador->btn; ?>

                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_9; ?>

                                        <div  id="apartado_9">
                                            <?php echo $controlador->inputs->com_tipo_cliente_id; ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_10; ?>
                                        <div  id="apartado_10">
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
                                            <?php echo $controlador->inputs->conyuge->telefono_celular;  ?>
                                            <?php echo $controlador->btn; ?>
                                        </div>

                                        <?php echo $controlador->header_frontend->apartado_11; ?>
                                        <div  id="apartado_11">
                                            <?php echo $controlador->inputs->beneficiario->inm_tipo_beneficiario_id; ?>
                                            <?php echo $controlador->inputs->beneficiario->inm_parentesco_id; ?>
                                            <?php echo $controlador->inputs->beneficiario->nombre; ?>
                                            <?php echo $controlador->inputs->beneficiario->apellido_paterno; ?>
                                            <?php echo $controlador->inputs->beneficiario->apellido_materno; ?>
                                            <?php echo $controlador->btn; ?>
                                            <div class="col-md-12 table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Tipo Beneficiario</th>
                                                        <th>Parentesco</th>
                                                        <th>Nombre</th>
                                                        <th>Apellido Paterno</th>
                                                        <th>Apellido Materno</th>
                                                        <th>Modifica</th>
                                                        <th>Elimina</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($controlador->beneficiarios as $beneficiario){ ?>
                                                        <tr>
                                                            <td><?php echo $beneficiario['inm_beneficiario_id']; ?></td>
                                                            <td><?php echo $beneficiario['inm_tipo_beneficiario_descripcion']; ?></td>
                                                            <td><?php echo $beneficiario['inm_parentesco_descripcion']; ?></td>
                                                            <td><?php echo $beneficiario['inm_beneficiario_nombre']; ?></td>
                                                            <td><?php echo $beneficiario['inm_beneficiario_apellido_paterno']; ?></td>
                                                            <td><?php echo $beneficiario['inm_beneficiario_apellido_materno']; ?></td>
                                                            <td><?php echo $beneficiario['btn_mod']; ?></td>
                                                            <td><?php echo $beneficiario['btn_del']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <?php echo $controlador->header_frontend->apartado_12; ?>
                                        <div  id="apartado_12">
                                            <?php echo $controlador->inputs->referencia->nombre; ?>
                                            <?php echo $controlador->inputs->referencia->apellido_paterno; ?>
                                            <?php echo $controlador->inputs->referencia->apellido_materno; ?>
                                            <?php echo $controlador->inputs->referencia->lada; ?>
                                            <?php echo $controlador->inputs->referencia->numero; ?>
                                            <?php echo $controlador->inputs->referencia->celular; ?>
                                            <?php echo $controlador->inputs->referencia->dp_estado_id; ?>
                                            <?php echo $controlador->inputs->referencia->dp_municipio_id; ?>
                                            <?php echo $controlador->inputs->referencia->dp_cp_id; ?>
                                            <?php echo $controlador->inputs->referencia->dp_colonia_postal_id; ?>
                                            <?php echo $controlador->inputs->referencia->calle; ?>
                                            <?php echo $controlador->inputs->referencia->numero_exterior; ?>
                                            <?php echo $controlador->inputs->referencia->numero_interior; ?>
                                            <?php echo $controlador->inputs->referencia->inm_parentesco_id; ?>
                                            <div class="col-md-12 table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Nombre</th>
                                                        <th>AP</th>
                                                        <th>AM</th>
                                                        <th>Parentesto</th>
                                                        <th>Celular</th>
                                                        <th>Modifica</th>
                                                        <th>Elimina</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($controlador->referencias as $referencia){ ?>
                                                        <tr>
                                                            <td><?php echo $referencia['inm_referencia_id']; ?></td>
                                                            <td><?php echo $referencia['inm_referencia_nombre']; ?></td>
                                                            <td><?php echo $referencia['inm_referencia_apellido_paterno']; ?></td>
                                                            <td><?php echo $referencia['inm_referencia_apellido_materno']; ?></td>
                                                            <td><?php echo $referencia['inm_parentesco_descripcion']; ?></td>
                                                            <td><?php echo $referencia['inm_referencia_celular']; ?></td>
                                                            <td><?php echo $referencia['btn_mod']; ?></td>
                                                            <td><?php echo $referencia['btn_del']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <?php echo $controlador->btn; ?>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana2">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                                                <?php echo $controlador->inputs->registro_id; ?>
                                                <form method="post" action="<?php echo $controlador->link_rel_ubi_comp_alta_bd; ?>"
                                                      class="form-additional">

                                                    <?php echo $controlador->inputs->inm_ubicacion_util_id; ?>
                                                    <?php echo $controlador->inputs->precio_operacion; ?>

                                                    <?php echo $controlador->inputs->com_tipo_cliente_id; ?>
                                                    <?php echo $controlador->inputs->nss; ?>
                                                    <?php echo $controlador->inputs->curp; ?>
                                                    <?php echo $controlador->inputs->rfc; ?>
                                                    <?php echo $controlador->inputs->apellido_paterno; ?>
                                                    <?php echo $controlador->inputs->apellido_materno; ?>
                                                    <?php echo $controlador->inputs->nombre; ?>
                                                    <?php echo $controlador->inputs->inm_comprador_id; ?>

                                                    <?php echo $controlador->inputs->seccion_retorno; ?>
                                                    <?php echo $controlador->inputs->btn_action_next; ?>
                                                    <?php echo $controlador->inputs->id_retorno; ?>
                                                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="widget widget-box box-container widget-mylistings">
                                                    <div class="widget-header" style="display: flex;justify-content: space-between;align-items: center;">
                                                        <h2>Ubicaciones</h2>
                                                    </div>
                                                    <div class="table table-responsive">
                                                        <table class='table table-striped data-partida'>
                                                            <thead>
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Fecha Asignacion</th>
                                                                <th>Estatus de Asignacion</th>
                                                                <th>Status Ubicacion</th>
                                                                <th>Direccion</th>
                                                                <th>Precio Operacion</th>
                                                                <th>Manzana</th>
                                                                <th>Lote</th>
                                                            <tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php    foreach ($controlador->inm_ubicaciones as $inm_ubicacion){ ?>
                                                            <tr>
                                                                <td><?php echo $inm_ubicacion['inm_ubicacion_id'] ?></td>
                                                                <td><?php echo $inm_ubicacion['inm_rel_ubi_comp_fecha_alta'] ?></td>
                                                                <td><?php echo $inm_ubicacion['inm_rel_ubi_comp_status'] ?></td>
                                                                <td><?php echo $inm_ubicacion['inm_status_ubicacion_descripcion'] ?></td>
                                                                <td><?php echo $inm_ubicacion['inm_ubicacion_ubicacion'] ?></td>
                                                                <td><?php echo $inm_ubicacion['inm_rel_ubi_comp_precio_operacion'] ?></td>
                                                                <td><?php echo $inm_ubicacion['inm_ubicacion_manzana'] ?></td>
                                                                <td><?php echo $inm_ubicacion['inm_ubicacion_lote'] ?></td>
                                                            <tr>
                                                                <?php }  ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana3">
                                    <form method="post" action="<?php echo $controlador->link_inm_rel_cliente_valuador_alta_bd; ?>"
                                          class="form-additional">
                                        <?php echo $controlador->inputs->inm_comprador_id; ?>
                                        <?php echo $controlador->inputs->com_cliente_id; ?>
                                        <?php echo $controlador->inputs->inm_valuador_id; ?>

                                        <?php echo $controlador->inputs->seccion_retorno; ?>
                                        <?php echo $controlador->inputs->btn_action_next; ?>
                                        <?php echo $controlador->inputs->id_retorno; ?>

                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                    <div class="row buttons-form">
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_solicitud_avaluo; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana4">
                                    <form method="post" action="<?php echo $controlador->link_inm_avaluo_alta_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <?php echo $controlador->inputs->inm_comprador_id; ?>
                                        <?php echo $controlador->inputs->com_cliente_id; ?>
                                        <?php echo $controlador->inputs->metros_terreno; ?>
                                        <?php echo $controlador->inputs->metros_construidos; ?>
                                        <?php echo $controlador->inputs->valor_avaluo; ?>
                                        <?php echo $controlador->inputs->avaluo; ?>
                                        <?php echo $controlador->inputs->documento_poder; ?>

                                        <?php echo $controlador->inputs->seccion_retorno; ?>
                                        <?php echo $controlador->inputs->btn_action_next; ?>
                                        <?php echo $controlador->inputs->id_retorno; ?>

                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                    <div class="row buttons-form">
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_doc_comprador; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd; ?>
                                        </div>
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_doc_ubicacion_escritura; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_ubicacion_escritura_descarga; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_ubicacion_escritura_vista_previa; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_ubicacion_escritura_descarga_zip; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_ubicacion_escritura_elimina_bd; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana5">
                                    <form method="post" action="<?php echo $controlador->link_ingresado_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <?php echo $controlador->inputs->inm_comprador_id; ?>
                                        <?php echo $controlador->inputs->com_cliente_id; ?>
                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row buttons-form">
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_solicitud_infonavit; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana6">
                                    <form method="post" action="<?php echo $controlador->link_autorizado_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <?php echo $controlador->inputs->inm_comprador_id; ?>

                                        <?php echo $controlador->inputs->documento_sic; ?>
                                        <?php echo $controlador->inputs->documento_constancia_credito; ?>

                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                    <div class="row buttons-form">
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_sic; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_sic; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_sic; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_sic; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_sic; ?>
                                        </div>

                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_constancia_credito; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_constancia_credito; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_constancia_credito; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_constancia_credito; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_constancia_credito; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana7">
                                    <form method="post" action="<?php echo $controlador->link_inm_firma_alta_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <?php echo $controlador->inputs->inm_comprador_id; ?>

                                        <?php echo $controlador->inputs->documento_anexos; ?>
                                        <?php echo $controlador->inputs->documento_instruccion_credito; ?>
                                        <?php echo $controlador->inputs->documento_notificacion_descuento; ?>
                                        <?php echo $controlador->inputs->documento_isr_notaria; ?>
                                        <?php echo $controlador->inputs->isr; ?>
                                        <?php echo $controlador->inputs->pago_propio_peculio; ?>
                                        <?php echo $controlador->inputs->pago_precio_compra_venta; ?>
                                        <?php echo $controlador->inputs->pago_parcial_precio_compra_venta; ?>
                                        <?php echo $controlador->inputs->pago_cuv; ?>

                                        <?php echo $controlador->inputs->seccion_retorno; ?>
                                        <?php echo $controlador->inputs->btn_action_next; ?>
                                        <?php echo $controlador->inputs->id_retorno; ?>
                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                    <div class="row buttons-form">
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_anexos; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_anexos; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_anexos; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_anexos; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_anexos; ?>
                                        </div>

                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_instruccion_credito; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_instruccion_credito; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_instruccion_credito; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_instruccion_credito; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_instruccion_credito; ?>
                                        </div>

                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_notificacion_descuento; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_notificacion_descuento; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_notificacion_descuento; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_notificacion_descuento; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_notificacion_descuento; ?>
                                        </div>

                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_isr_notaria; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_isr_notaria; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_isr_notaria; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_isr_notaria; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_isr_notaria; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana8">
                                    <form method="post" action="<?php echo $controlador->link_inm_escritura_alta_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">
                                        <?php echo $controlador->inputs->inm_comprador_id; ?>

                                        <?php echo $controlador->inputs->documento_validacion_poder; ?>
                                        <?php echo $controlador->inputs->documento_acuse_patron; ?>
                                        <?php echo $controlador->inputs->documento_escrituras; ?>
                                        <?php echo $controlador->inputs->numero_escritura; ?>
                                        <?php echo $controlador->inputs->fecha_escritura; ?>

                                        <?php echo $controlador->inputs->seccion_retorno; ?>
                                        <?php echo $controlador->inputs->btn_action_next; ?>
                                        <?php echo $controlador->inputs->id_retorno; ?>
                                        <?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
                                    </form>
                                    <div class="row buttons-form">
                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_validacion_poder; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_validacion_poder; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_validacion_poder; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_validacion_poder; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_validacion_poder; ?>
                                        </div>

                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_acuse_patron; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_acuse_patron; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_acuse_patron; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_acuse_patron; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_acuse_patron; ?>
                                        </div>

                                        <div class="col-lg-12">
                                            <label class="label-docs">
                                                <?php echo $controlador->descripcion_escritura; ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_escritura; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_vista_previa_escritura; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_descarga_zip_escritura; ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <?php echo $controlador->button_inm_doc_comprador_elimina_bd_escritura; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestana9">
                                    <form method="post" action="<?php echo $controlador->link_cotejado_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="conten" id="cpestana10">
                                    <!--<form method="post" action="<?php //echo $controlador->link_cobrado_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <div class="control-group btn-alta">
                                            <div class="controls">
                                                <button type="submit" class="btn btn-success">Avanza Etapa</button><br>
                                            </div>
                                        </div>
                                    </form>-->
                                    <form method="post" action="<?php echo $controlador->link_cobrado_bd; ?>"
                                          class="form-additional" enctype="multipart/form-data">

                                        <?php echo $controlador->inputs->inm_tipo_gasto_id; ?>

                                        <?php echo $controlador->inputs->nombre_beneficiario; ?>
                                        <div id="cont_cheque">
                                            <?php echo $controlador->inputs->inm_tipo_cheque_id; ?>
                                            <?php echo $controlador->inputs->bn_cuenta_sl_id; ?>
                                            <?php echo $controlador->inputs->numero_cheque; ?>
                                            <?php echo $controlador->inputs->monto; ?>
                                        </div>
                                        <div id="cont_transfer">
                                            <?php echo $controlador->inputs->transferencia; ?>
                                            <?php echo $controlador->inputs->bn_cuenta_sl_trs_id; ?>
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
                                                <button type="submit" class="btn btn-success" name="alta_documento" value="alta_documento">
                                                    Alta Documento
                                                </button>
                                                <button type="submit" class="btn btn-success" name="avanza_etapa" value="avanza_etapa">
                                                    Avanza Etapa
                                                </button>
                                                <br>
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
                                                            <th>Referencia</th>
                                                            <th>Monto</th>
                                                            <th>Cuenta Bancaria</th>
                                                            <th>Fecha</th>
                                                            <th>Elimina</th>
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

                                                            <?php echo $cheque['documento'] ?>
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
                                                                <td><?php echo $transferencia['bn_cuenta_descripcion'] ?></td>
                                                                <td><?php echo $transferencia['inm_transferencia_fecha_alta'] ?></td>
                                                                <td><?php echo $transferencia['elimina_bd'] ?></td>
                                                            </tr>

                                                            <?php echo $transferencia['documento'] ?>
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
















