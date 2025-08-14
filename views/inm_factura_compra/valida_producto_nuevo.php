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

                        <div class="controls">
                            <button type="submit" class="btn btn-success" name="btn_action_next">Inserta Producto</button><br>
                        </div>
                    </form>
                    <br>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Numero Identificacion</th>
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
                                <td><?php echo $concepto['NoIdentificacion'] ?></td>
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