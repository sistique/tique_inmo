<?php use config\views; ?>
<?php
echo "<style>
.filtros-avanzados{
    width: 100%;
}

.contenedor_completo{
    display: flex;
    flex-wrap: wrap;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 15px;
}

.filtro-grupo {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

.filtro-grupo label {
    font-weight: bold;
    margin-right: 5px;
}

.filtro-grupo input {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
}

.descripcion_producto {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

.descripcion_producto label {
    font-weight: bold;
    margin-right: 5px;
}

.descripcion_producto input {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
}

#filtrar {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

#filtrar:hover {
    background: #0056b3;
}

#asignar {
    background: #5cb85c;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

#asignar:hover {
    background: #4CAE4C;
}

#siguiente {
    background: #5cb85c;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

#siguiente:hover {
    background: #4CAE4C;
}

#anterior {
    background: #5cb85c;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

#anterior:hover {
    background: #4CAE4C;
}

.asignar {
    background: #5cb85c;
    color: white !important;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
    text-decoration: none !important;
}

.asignar:hover {
    background: #4CAE4C;
}

#limpiar {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
    margin-left: 10px;
}

#limpiar:hover {
    background: #a71d2a;
}

#limpiar:disabled {
    background: #cccccc;
    color: #666666;
    cursor: not-allowed;
    border: none;
}

#descargar_excel {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
    margin-left: 10px;
}

#descargar_excel:hover {
    background: #218838;
}

#myModal{
    width: 100%;
    height: 80%;
}

#table-inm_producto{
    width: 100% !important;
}

.close-btn{
    font-size: x-large;
}

.content_close{
    text-align: right;
}

.content_alta{
    display: none;
}

.btn-insert{
    pointer-events: none;
    opacity: 0.9;
    cursor: not-allowed;
}

</style>";
?>

<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_inserta_detalle_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->gt_proveedor_id; ?>

                        <?php echo $controlador->inputs->btn_action_next; ?>
                        <?php echo $controlador->inputs->id_retorno; ?>
                        <?php echo $controlador->inputs->seccion_retorno; ?>

                        <div id="content_form_productos">

                        </div>
                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="submit" class="btn btn-success btn-insert" name="btn_action_next">Inserta Producto</button>
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
                            <th>Por Asignar</th>
                            <th>Asignar</th>
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
                                <td id="por_asignar-<?php echo $concepto['indice'] ?>"></td>
                                <td><a class="asignar  text-white text-decoration-none" href='javascript:abrir_modal(<?php echo $concepto['indice'] ?>);' id="producto_id-<?php echo $concepto['indice'] ?>" value="<?php echo $concepto['indice'] ?>">Asignar</a></td>
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
    <div class="row">
        <div class="col-lg-12 content_close">
            <span class="close-btn" id="closeModalBtn">&times;</span>
        </div>
    </div>

    <h2>Selecciona Productos</h2>

    <div class="row">
        <div class="descripcion_producto col-lg-6">
            <label for="producto">Producto</label>
            <input name="producto" type="text" id="producto" disabled>
        </div>
        <div class="descripcion_producto col-lg-6">
            <label for="producto_asignado">Producto Asignado</label>
            <input name="producto_asignado" type="text" id="producto_asignado" disabled>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <div class="col-lg-12 table-responsive">
                <div class="contenedor_completo">
                    <div class="filtros-avanzados">
                        <div class="filtro-grupo col-md-12">
                            <div class="col-md-6">
                                <label for="inm_producto_id">ID</label>
                                <input type="text" id="inm_producto_id" data-tipo="filtro" data-filtro_campo="inm_producto.id"
                                       placeholder="Ej: 1">
                            </div>

                            <div class="col-md-6">
                                <label for="inm_producto_descripcion">Descripcion</label>
                                <input type="text" id="inm_producto_descripcion" data-tipo="filtro" data-filtro_campo="inm_producto.descripcion"
                                       placeholder="Ej: TUBERIA">
                            </div>
                            <br>
                            <br>
                            <br>
                            <br>
                            <button id="filtrar">Filtrar</button>
                            <button id="limpiar">Limpiar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-striped productos">
                    <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Descripcion</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <!-- Paginador -->
                <div id="pagination" class="mt-3"></div>
                <br>
                <button id="anterior">Anterior</button>
                <button id="asignar">Asignar</button>
                <button id="siguiente">Siguiente</button>
                <div class="form-main" id="form">
                    <div class="col-lg-12 content_alta">
                        <div class="form-additional">
                            <div class="widget-header">
                                <h2>Alta Producto</h2>
                            </div>
                            <?php echo $controlador->inputs->inm_concepto_id; ?>
                            <?php echo $controlador->inputs->descripcion_producto; ?>
                            <?php echo $controlador->inputs->cat_sat_unidad_id; ?>
                            <?php echo $controlador->inputs->cat_sat_cve_prod_codigo; ?>
                            <?php echo $controlador->inputs->costo_promedio; ?>
                            <?php echo $controlador->inputs->cantidad_actual; ?>

                            <div class="control-group btn-alta">
                                <div class="controls">
                                    <button id="alta_producto" type="submit" class="btn btn-success" >Alta</button><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</dialog>