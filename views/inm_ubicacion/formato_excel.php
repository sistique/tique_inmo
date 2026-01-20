<?php /** @var gamboamartin\comercial\controllers\controlador_com_prospecto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div class="row">
        <div class="col-lg-12">
            <?php include (new views())->ruta_templates."head/title.php"; ?>
            <?php include (new views())->ruta_templates."mensajes.php"; ?>

            <div class="widget widget-box box-container widget-mylistings">
                <div class="control-group btn-alta">
                    <div class="controls">
                        <button type="submit" class="btn btn-success btn-insert" name="btn_action_next" onclick="copiarRegistros()">Copiar</button>
                    </div>
                </div>
                <table id="miTabla" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Propietario</th>
                            <th>Empresa</th>
                            <th>Calle</th>
                            <th>Número</th>
                            <th>Col./fracc.</th>
                            <th>C.p.</th>
                            <th>Municipio</th>
                            <th>Status poder</th>
                            <th>Comentarios</th>
                            <th>Nss</th>
                            <th>Contraseña</th>
                            <th>Apoderado</th>
                            <th>Ejecutivo</th>
                            <th>Número de crédito</th>
                            <th>Adeudo</th>
                            <th>Pago cliente</th>
                            <th>Comisión asesor adquisición</th>
                            <th>Escritura</th>
                            <th>Notaría</th>
                            <th>Año</th>
                            <th>Valor operación</th>
                            <th>Orig</th>
                            <th>Validada por notaría</th>
                            <th>Fecha validación</th>
                            <th>Prototipo inmueble</th>
                            <th>Recámaras</th>
                            <th>Baños</th>
                            <th>1/2 baños</th>
                            <th>Estac.</th>
                            <th>Terreno m2.</th>
                            <th>Construcción m2.</th>
                            <th>Predial</th>
                            <th>Adeudo</th>
                            <th>Cna</th>
                            <th>Cuenta agua</th>
                            <th>Adeudo cna</th>
                            <th>Poder</th>
                            <th>Notaría</th>
                            <th>Fecha poder</th>
                            <th>Orig</th>
                            <th>Hora</th>
                            <th>Exenta</th>
                            <th>Estatus obra</th>
                            <th>Llaves</th>
                            <th>Precio venta</th>
                            <th>Estatus vivienda</th>
                            <th>Precio</th>
                            <th>Monto pagado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $controlador->registro['inm_ubicacion_razon_social'] ?></td>
                            <td></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_calle'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_numero_exterior']; if($controlador->registro['inm_ubicacion_numero_interior'] !== '') echo " ".$controlador->registro['inm_ubicacion_numero_interior']?></td>
                            <td><?php echo $controlador->registro['dp_colonia_descripcion'] ?></td>
                            <td><?php echo $controlador->registro['dp_cp_descripcion'] ?></td>
                            <td><?php echo $controlador->registro['dp_municipio_descripcion'] ?></td>
                            <td><?php echo $controlador->registro['inm_status_ubicacion_descripcion'] ?></td>
                            <td></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_nss'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_password_mi_cuenta_infonavit'] ?></td>
                            <td></td>
                            <td><?php echo $controlador->registro['com_agente_descripcion'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_numero_credito'] ?></td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td><?php echo $controlador->registro['inm_ubicacion_numero_escritura'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_numero_notaria']." ". $controlador->registro['inm_ubicacion_plaza_notaria'] ?></td>
                            <td></td>
                            <td>0</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $controlador->registro['inm_prototipo_descripcion'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_recamaras'] ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_metros_terreno'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_metros_construccion'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_cuenta_predial'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_adeudo_predial'] ?></td>
                            <td></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_cuenta_agua'] ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

