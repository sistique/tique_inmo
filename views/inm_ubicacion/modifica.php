<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_prospecto $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates . "head/title.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <?php include (new views())->ruta_templates . "head/subtitulo.php"; ?>
                    <?php include (new views())->ruta_templates . "mensajes.php"; ?>
                    <?php echo $controlador->buttons['btn_collapse_all']; ?>

                    <?php
                    $checked_genero_m = 'checked';
                    $checked_genero_f = '';
                    if($controlador->row_upd->genero_co_acreditado === 'F'){
                        $checked_genero_m = '';
                        $checked_genero_f = 'checked';
                    }
                    ?>
                    <form method="post" action="<?php echo $controlador->link_modifica_bd; ?>" class="form-additional"
                          enctype="multipart/form-data">

                        <?php echo $controlador->header_frontend->apartado_1; ?>
                        <div id="apartado_1">
                            <?php echo $controlador->inputs->org_sucursal_id; ?>
                            <?php echo $controlador->inputs->com_agente_id; ?>

                            <!-- Identificadores -->
                            <?php echo $controlador->inputs->nss; ?>
                            <?php echo $controlador->inputs->curp; ?>
                            <?php echo $controlador->inputs->rfc; ?>

                            <!-- Identidad -->
                            <?php echo $controlador->inputs->nombre; ?>
                            <?php echo $controlador->inputs->apellido_paterno; ?>
                            <?php echo $controlador->inputs->apellido_materno; ?>

                            <!-- Contexto administrativo -->
                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                        </div>

                        <?php echo $controlador->header_frontend->apartado_2; ?>
                        <div id="apartado_2">
                            <!-- Dirección de la vivienda -->
                            <?php echo $controlador->inputs->dp_estado_id; ?>
                            <?php echo $controlador->inputs->dp_municipio_id; ?>
                            <?php echo $controlador->inputs->dp_cp_id; ?>
                            <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
                            <?php echo $controlador->inputs->calle; ?>
                            <?php echo $controlador->inputs->numero_exterior; ?>
                            <?php echo $controlador->inputs->numero_interior; ?>
                            <?php echo $controlador->inputs->entre_calle_1; ?>
                            <?php echo $controlador->inputs->entre_calle_2; ?>

                            <!-- Tipo y características -->
                            <?php echo $controlador->inputs->inm_tipo_vivienda_id; ?>
                            <?php echo $controlador->inputs->inm_estado_vivienda_id; ?>
                            <?php echo $controlador->inputs->inm_prototipo_id; ?>
                            <?php echo $controlador->inputs->inm_complemento_id; ?>

                            <!-- Identificadores de la unidad -->
                            <?php echo $controlador->inputs->lote; ?>
                            <?php echo $controlador->inputs->nivel; ?>
                            <?php echo $controlador->inputs->entrada; ?>
                            <?php echo $controlador->inputs->manzana; ?>
                            <?php echo $controlador->inputs->supermanzana; ?>
                            <?php echo $controlador->inputs->edificio; ?>
                            <?php echo $controlador->inputs->condominio; ?>
                            <?php echo $controlador->inputs->etapa; ?>

                            <!-- Dimensiones -->
                            <?php echo $controlador->inputs->recamaras; ?>
                            <?php echo $controlador->inputs->metros_terreno; ?>
                            <?php echo $controlador->inputs->metros_construccion; ?>

                            <!-- Datos registrales / notaría -->
                            <?php echo $controlador->inputs->numero_notaria; ?>
                            <?php echo $controlador->inputs->plaza_notaria; ?>
                            <?php echo $controlador->inputs->nombre_notario; ?>
                            <?php echo $controlador->inputs->numero_escritura; ?>
                            <?php echo $controlador->inputs->libro; ?>
                            <?php echo $controlador->inputs->volumen; ?>
                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                        </div>

                        <?php echo $controlador->header_frontend->apartado_3; ?>
                        <div id="apartado_3">
                            <?php echo $controlador->inputs->inm_tipo_credito_id; ?>
                            <?php echo $controlador->inputs->numero_credito; ?>
                            <?php echo $controlador->inputs->fecha_otorgamiento_credito; ?>
                            <?php echo $controlador->inputs->adeudo_hipoteca; ?>
                            <?php echo $controlador->inputs->monto_devolucion; ?>
                            <?php echo $controlador->inputs->correo_mi_cuenta_infonavit; ?>
                            <?php echo $controlador->inputs->password_mi_cuenta_infonavit; ?>
                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                        </div>

                        <?php echo $controlador->header_frontend->apartado_4; ?>
                        <div id="apartado_4">
                            <?php echo $controlador->inputs->razon_social; ?>

                            <!-- Domicilio del titular -->
                            <?php echo $controlador->inputs->dp_estado_domicilio_id; ?>
                            <?php echo $controlador->inputs->dp_municipio_domicilio_id; ?>
                            <?php echo $controlador->inputs->dp_cp_domicilio_id; ?>
                            <?php echo $controlador->inputs->dp_colonia_postal_domicilio_id; ?>
                            <?php echo $controlador->inputs->calle_domicilio; ?>
                            <?php echo $controlador->inputs->numero_exterior_domicilio; ?>
                            <?php echo $controlador->inputs->numero_interior_domicilio; ?>

                            <!-- Contacto -->
                            <?php echo $controlador->inputs->lada_com; ?>
                            <?php echo $controlador->inputs->numero_com; ?>
                            <?php echo $controlador->inputs->cel_com; ?>
                            <?php echo $controlador->inputs->correo_com; ?>
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
                            <!-- Identificadores -->
                            <?php echo $controlador->inputs->inm_co_acreditado->nss; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->curp; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->rfc; ?>

                            <!-- Identidad -->
                            <?php echo $controlador->inputs->inm_co_acreditado->nombre; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->apellido_paterno; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->apellido_materno; ?>

                            <!-- Contacto -->
                            <?php echo $controlador->inputs->inm_co_acreditado->lada; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->numero; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->celular; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->correo; ?>

                            <div class="control-group col-sm-4">
                                <label class="control-label" for="inm_attr_tipo_credito_id">Genero</label>
                                <label class="form-check-label chk">
                                    <input type="radio" name="co_acreditado[genero]" value="M" class="form-check-input" id="genero"
                                           title="Genero"  <?php echo $checked_genero_m; ?>>
                                    M
                                </label>
                                <label class="form-check-label chk">
                                    <input type="radio" name="co_acreditado[genero]" value="F" class="form-check-input" id="genero"
                                           title="Genero"  <?php echo $checked_genero_f; ?>>
                                    F
                                </label>
                            </div>
                            <?php echo $controlador->inputs->inm_co_acreditado->numero_credito; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->adeudo_hipoteca; ?>

                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                        </div>

                        <?php echo $controlador->header_frontend->apartado_7; ?>
                        <div id="apartado_7">
                            <?php echo $controlador->inputs->inm_co_acreditado->nombre_empresa_patron; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->nrp; ?>
                            <?php echo $controlador->inputs->inm_co_acreditado->lada_nep; ?>
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
                            <?php echo $controlador->inputs->conyuge->fecha_nacimiento; ?>
                            <?php echo $controlador->inputs->conyuge->dp_estado_id; ?>
                            <?php echo $controlador->inputs->conyuge->dp_municipio_id; ?>
                            <?php echo $controlador->inputs->conyuge->inm_nacionalidad_id; ?>
                            <?php echo $controlador->inputs->conyuge->inm_ocupacion_id; ?>

                            <!-- Crédito -->
                            <?php echo $controlador->inputs->conyuge->numero_credito; ?>
                            <?php echo $controlador->inputs->conyuge->adeudo_hipoteca; ?>

                            <!-- Contacto -->
                            <?php echo $controlador->inputs->conyuge->telefono_casa; ?>
                            <?php echo $controlador->inputs->conyuge->telefono_celular; ?>
                            <?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>


<dialog id="myModal">
    <form method="post" action="<?php echo $controlador->link_modifica_direccion; ?>" class="form-additional"
          enctype="multipart/form-data">
        <span class="close-btn" id="closeModalBtn">&times;</span>
        <h2>Modificar dirección</h2>
       <input type="hidden" name="com_direccion_id" id="com_direccion_id" value=""/>
        <?php echo $controlador->inputs->dp_pais_id; ?>
        <?php echo $controlador->inputs->dp_estado_id; ?>
        <?php echo $controlador->inputs->dp_municipio_id; ?>
        <?php echo $controlador->inputs->cp; ?>
        <?php echo $controlador->inputs->colonia; ?>
        <?php echo $controlador->inputs->calle; ?>
        <?php echo $controlador->inputs->texto_exterior; ?>
        <?php echo $controlador->inputs->texto_interior; ?>

        <div class="control-group btn-modifica">
            <div class="controls">
                <button type="submit" class="btn btn-success ">Modifica</button>
                <br>
            </div>
        </div>
    </form>
</dialog>



