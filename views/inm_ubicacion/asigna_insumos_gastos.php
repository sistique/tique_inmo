<?php /** @var gamboamartin\inmuebles\controllers\controlador_inm_ubicacion $controlador */ ?>
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

hr{
    margin-top: 20px;
    margin-bottom: 20px;
    border: 1px solid #eee;
}

.factura-row{
    background: #eef4ff;
}

.factura-row td{
    vertical-align: middle !important;
}

.detalle-factura-row{
    display: none;
    background: #fff;
}

.detalle-factura-row td{
    padding: 0 !important;
}

.detalle-factura-contenido{
    padding: 15px;
}

.toggle-detalle-factura{
    min-width: 120px;
}

.resumen-total-asignado{
    margin: 15px 0;
}

.resumen-total-asignado input{
    font-weight: bold;
    background: #f8f9fa;
}

.fila-totales{
    background: #f3f3f3;
    font-weight: bold;
}

.filtro-fecha-factura{
    margin: 10px 0 20px 0;
    display: flex;
    flex-wrap: nowrap;
    gap: 10px;
    align-items: end;
}

.filtro-fecha-factura .control-group{
    margin-bottom: 0;
}

.filtro-fecha-factura .fecha-col{
    flex: 1 1 280px;
}

.filtro-fecha-factura .acciones-col{
    flex: 0 0 auto;
}

.filtro-fecha-factura .acciones-col .controls{
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
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

                    <form enctype="multipart/form-data" method="post" action="<?php echo $controlador->link_asigna_insumos_gastos_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>

                        <?php echo $controlador->inputs->inm_ubicacion_seleccionado_id; ?>
                        <?php echo $controlador->inputs->inm_factura_compra_id; ?>
                        <?php echo $controlador->inputs->inm_detalle_factura_compra_id; ?>
                        <?php echo $controlador->inputs->cantidad_detalle; ?>
                        <?php echo $controlador->inputs->valor_unitario; ?>
                        <?php echo $controlador->inputs->subtotal; ?>
                        <?php echo $controlador->inputs->trasladado; ?>
                        <?php echo $controlador->inputs->retenido; ?>
                        <?php echo $controlador->inputs->total_con_impuesto; ?>

                        <hr>
                        <?php echo $controlador->inputs->cantidad_consumo; ?>

                        <?php echo $controlador->inputs->btn_action_next; ?>
                        <?php echo $controlador->inputs->id_retorno; ?>
                        <?php echo $controlador->inputs->seccion_retorno; ?>

                        <div id="content_form_productos"></div>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="submit" class="btn btn-success btn-insert" name="factura_completa" value="factura_completa">Asigna Factura Completa</button>
                                <button type="submit" class="btn btn-success btn-insert" name="asigna_insumo" value="asigna_insumo">Asigna Insumo</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <?php
                    $link_limpia_filtro = 'index.php?seccion=inm_ubicacion&accion=asigna_insumos_gastos&registro_id='.$controlador->registro_id;
                    if(isset($_GET['session_id'])){
                        $link_limpia_filtro .= '&session_id='.(string)$_GET['session_id'];
                    }
                    ?>
                    <form method="get" action="index.php" class="filtro-fecha-factura form-additional">
                        <input type="hidden" name="seccion" value="inm_ubicacion">
                        <input type="hidden" name="accion" value="asigna_insumos_gastos">
                        <input type="hidden" name="registro_id" value="<?php echo $controlador->registro_id; ?>">
                        <?php if(isset($_GET['session_id'])){ ?>
                            <input type="hidden" name="session_id" value="<?php echo (string)$_GET['session_id']; ?>">
                        <?php } ?>

                        <div class="control-group fecha-col">
                            <label class="control-label" for="fecha_desde">Fecha desde</label>
                            <div class="controls">
                                <input type="date" id="fecha_desde" name="fecha_desde" class="form-control"
                                       value="<?php echo $controlador->fecha_desde_filtro; ?>">
                            </div>
                        </div>

                        <div class="control-group fecha-col">
                            <label class="control-label" for="fecha_hasta">Fecha hasta</label>
                            <div class="controls">
                                <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control"
                                       value="<?php echo $controlador->fecha_hasta_filtro; ?>">
                            </div>
                        </div>

                        <div class="control-group acciones-col">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="<?php echo $link_limpia_filtro; ?>" class="btn btn-default">Limpiar</a>
                            </div>
                        </div>
                    </form>

                    <div class="control-group resumen-total-asignado col-sm-6">
                        <label class="control-label" for="total_asignado_ubicacion">Total Asignado a la Ubicación</label>
                        <div class="controls">
                            <input type="text" id="total_asignado_ubicacion" class="form-control"
                                   value="$<?php echo number_format((float)$controlador->total_asignado_ubicacion, 2); ?>"
                                   readonly>
                        </div>
                    </div>

                    <table class="table table-striped inm_detalle_factura_compra">
                        <thead>
                        <tr>
                            <th>Factura</th>
                            <th>Fecha Factura</th>
                            <th>Registros Asignados</th>
                            <th>Cantidad Asignada</th>
                            <th>Total Asignado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_registros_asignados = 0;
                        $total_cantidad_asignada = 0;
                        $total_monto_asignado = 0;
                        ?>

                        <?php if(count($controlador->facturas_movimiento_consumo) === 0){ ?>
                            <tr>
                                <td colspan="6">No hay facturas asignadas a esta ubicación.</td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($controlador->facturas_movimiento_consumo as $factura){ ?>
                            <?php
                            $total_registros_asignados += (int)$factura['n_detalles'];
                            $total_cantidad_asignada += (float)$factura['cantidad_asignada'];
                            $total_monto_asignado += (float)$factura['total_asignado'];
                            $detalle_row_id = 'detalle_factura_'.$factura['inm_factura_compra_id'];
                            ?>
                            <tr class="factura-row">
                                <td>
                                    <strong><?php echo $factura['inm_factura_compra_descripcion']; ?></strong><br>
                                    <small>Factura ID: <?php echo $factura['inm_factura_compra_id']; ?></small>
                                </td>
                                <td><?php echo $factura['inm_factura_compra_fecha']; ?></td>
                                <td><?php echo $factura['n_detalles']; ?></td>
                                <td><?php echo $factura['cantidad_asignada']; ?></td>
                                <td>$<?php echo number_format((float)$factura['total_asignado'], 2); ?></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm toggle-detalle-factura"
                                            data-target="<?php echo $detalle_row_id; ?>">Ver detalle</button>
                                </td>
                            </tr>
                            <tr id="<?php echo $detalle_row_id; ?>" class="detalle-factura-row">
                                <td colspan="6">
                                    <div class="detalle-factura-contenido">
                                        <table class="table table-striped table-bordered mb-0">
                                            <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Producto</th>
                                                <th>Cantidad Asignada</th>
                                                <th>Valor Unitario</th>
                                                <th>Total Asignado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($factura['detalles'] as $detalle){ ?>
                                                <tr>
                                                    <td><?php echo $detalle['inm_concepto_descripcion']; ?></td>
                                                    <td><?php echo $detalle['inm_producto_descripcion']; ?></td>
                                                    <td><?php echo $detalle['inm_movimiento_consumo_cantidad']; ?></td>
                                                    <td>$<?php echo number_format((float)$detalle['inm_movimiento_consumo_valor_unitario'], 2); ?></td>
                                                    <td>$<?php echo number_format((float)$detalle['inm_movimiento_consumo_total'], 2); ?></td>
                                                    <td><?php echo $detalle['elimina_bd']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if(count($controlador->facturas_movimiento_consumo) > 0){ ?>
                            <tr class="fila-totales">
                                <td colspan="2">Totales</td>
                                <td><?php echo $total_registros_asignados; ?></td>
                                <td><?php echo $total_cantidad_asignada; ?></td>
                                <td>$<?php echo number_format((float)$total_monto_asignado, 2); ?></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <br>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const botones = document.querySelectorAll('.toggle-detalle-factura');

        botones.forEach(function (boton) {
            boton.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const detalle = document.getElementById(targetId);

                if (!detalle) {
                    return;
                }

                const visible = detalle.style.display === 'table-row';
                detalle.style.display = visible ? 'none' : 'table-row';
                this.textContent = visible ? 'Ver detalle' : 'Ocultar detalle';
            });
        });
    });
</script>
