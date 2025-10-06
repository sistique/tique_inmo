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

</style>";
?>

<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <?php include (new views())->ruta_templates."mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <?php echo $controlador->inputs->gt_proveedor_id; ?>
                    <?php echo $controlador->inputs->fecha; ?>

                    <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_inm_detalle_factura_compra_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->inm_factura_compra_id; ?>

                        <?php echo $controlador->inputs->inm_producto_id; ?>
                        <?php echo $controlador->inputs->cat_sat_unidad_id; ?>
                        <?php echo $controlador->inputs->cantidad; ?>
                        <?php echo $controlador->inputs->valor_unitario; ?>
                        <?php echo $controlador->inputs->subtotal; ?>
                        <?php echo $controlador->inputs->iva; ?>
                        <?php echo $controlador->inputs->total; ?>

                        <?php echo $controlador->inputs->btn_action_next; ?>
                        <?php echo $controlador->inputs->id_retorno; ?>
                        <?php echo $controlador->inputs->seccion_retorno; ?>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="submit" class="btn btn-success btn-insert" name="btn_action_next" value="productos_factura">Inserta Producto</button>
                            </div>
                        </div>
                    </form>

                    <br>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Valor Unitario</th>
                            <th>Subtotal</th>
                            <th>IVA</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($controlador->productos_factura as $detalle){
                            ?>
                            <tr>
                                <td><?php echo $detalle['inm_producto_descripcion'] ?></td>
                                <td><?php echo $detalle['inm_detalle_factura_compra_cantidad'] ?></td>
                                <td><?php echo $detalle['inm_detalle_factura_compra_valor_unitario'] ?></td>
                                <td><?php echo $detalle['inm_detalle_factura_compra_subtotal'] ?></td>
                                <td><?php echo $detalle['inm_detalle_factura_compra_trasladado'] ?></td>
                                <td><?php echo $detalle['inm_detalle_factura_compra_total'] ?></td>
                                <td><?php echo $detalle['elimina_bd'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>