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
                            <th>Comprador</th>
                            <th>Empresa</th>
                            <th>Calle</th>
                            <th>Número</th>
                            <th>Col./fracc.</th>
                            <th>C.p.</th>
                            <th>Municipio</th>
                            <th>Precio de venta área comercial</th>
                            <th>Ubicación</th>
                            <th>Estatus poder</th>
                            <th>Estatus titulación</th>
                            <th>Fecha firma</th>
                            <th>Hora</th>
                            <th>Fecha cotejo o cancelación</th>
                            <th>Esquema</th>
                            <th>Asesor</th>
                            <th>Devolución cliente negociada</th>
                            <th>Comisión asesor</th>
                            <th>Observaciones</th>
                            <th>Toma de fotos</th>
                            <th>Precalificación</th>
                            <th>Nss</th>
                            <th>Acceso a mi cuenta</th>
                            <th>No crédito</th>
                            <th>Precio cv instrucción</th>
                            <th>Pago parcial instrucción</th>
                            <th>Pago por su propio peculio</th>
                            <th>Cuv</th>
                            <th>Saldo a favor</th>
                            <th>Notaría</th>
                            <th>Clg</th>
                            <th>Cch</th>
                            <th>Correo a info</th>
                            <th>Anexo a</th>
                            <th>Anexo b</th>
                            <th>Nd sc</th>
                            <th>Autorización patrón</th>
                            <th>Pago avalúo</th>
                            <th>Unidad de valuación</th>
                            <th>Valor concluido</th>
                            <th>Mts</th>
                            <th>Exenta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $controlador->registro['inm_comprador_razon_social'] ?></td>
                            <td>TIQUE</td>
                            <td><?php echo $controlador->registro['inm_ubicacion_calle'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_numero'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_colonia'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_cp'] ?></td>
                            <td><?php echo $controlador->registro['inm_ubicacion_municipio'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

