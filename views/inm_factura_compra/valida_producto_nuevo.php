<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_inserta_producto_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->gt_proveedor_id; ?>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="submit" class="btn btn-success" name="btn_action_next">Inserta Producto</button>
                                <button type="submit" class="btn btn-success" name="btn_action_next" title="Vista Previa">Asigna Productos</button>
                                <br>
                            </div>
                        </div>
                    </form>
                    <br>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Clave Producto</th>
                            <th>Producto</th>
                            <th>Clave Unidad</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Valor Unitario</th>
                            <th>Subtotal</th>
                            <th>Trasladado</th>
                            <th>Retenido</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($controlador->registros_concepto as $concepto){
                            ?>
                            <tr>
                                <td><?php echo $concepto['ClaveProdServ'] ?></td>
                                <td><?php echo $concepto['Descripcion'] ?></td>
                                <td><?php echo $concepto['ClaveUnidad'] ?></td>
                                <td><?php echo $concepto['Unidad'] ?></td>
                                <td><?php echo $concepto['Cantidad'] ?></td>
                                <td><?php echo $concepto['ValorUnitario'] ?></td>
                                <td><?php echo $concepto['Importe'] ?></td>
                                <td><?php echo $concepto['Trasladado'] ?></td>
                                <td><?php echo $concepto['Retenido'] ?></td>
                                <td><?php echo $concepto['Total'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<dialog id="myModal">
    <span class="close-btn" id="closeModalBtn">&times;</span>
    <h2>Selecciona Productos</h2>
    <div class="content">
        <div class="row">
            <div class="col-lg-12 table-responsive">
                <div class="filtros-avanzados">
                    <div class="filtro-grupo col-md-12">
                        <label>Status Ubicacion</label>
                        <select class="form-control basic-multiple" id="inm_status_ubicacion" name="inm_status_ubicacion[]"
                                data-tipo="in" data-filtro_campo="inm_status_ubicacion.descripcion" multiple
                                data-placeholder="Selecciona una Opcion">
                            <?php
                            foreach ($controlador->status_ubicacion AS $status){
                                echo '<option value="'.$status['inm_status_ubicacion_descripcion'].'">'.$status['inm_status_ubicacion_descripcion'].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filtro-grupo col-md-12">
                        <div class="col-md-4">
                            <label for="Ubicacion">Ubicacion</label>
                            <input type="text" id="ubicacion" data-tipo="filtro" data-filtro_campo="<?php echo $controlador->modelo->columnas_extra['inm_ubicacion_ubicacion']?>"
                                   placeholder="Ej: AV. VALLARTA 220 ">
                        </div>

                        <div class="col-md-4">
                            <label for="agente">Agente</label>
                            <input type="text" id="agente" data-tipo="filtro" data-filtro_campo="com_agente.descripcion"
                                   placeholder="Ej: JUAN PEREZ">
                        </div>

                        <div class="col-md-4">
                            <label for="predial">Predial</label>
                            <input type="text" id="predial" data-tipo="filtro" data-filtro_campo="inm_ubicacion.cuenta_predial"
                                   placeholder="Ej: 9999999999">
                        </div>
                    </div>
                </div>
                <div class="filtro-grupo col-md-12">
                    <button id="filtrar">Filtrar</button>
                    <button id="limpiar">Limpiar</button>
                    <form method="post" action="<?php echo $controlador->link_exportar_xls; ?>" enctype="multipart/form-data">
                        <input type="hidden" name="inm_status_ubicacion" id="hidden_inm_status_ubicacion">
                        <input type="hidden" name="nombre_ubicacion" id="hidden_nombre_ubicacion">
                        <input type="hidden" name="ubicacion" id="hidden_ubicacion">
                        <input type="hidden" name="agente" id="hidden_agente">
                        <input type="hidden" name="predial" id="hidden_predial">
                        <button id="descargar_excel">Descargar Excel</button>
                    </form>
                </div>
                <table id="table-inm_producto" class="table mb-0 table-striped table-sm "></table>
            </div>
        </div>
    </div>
</dialog>