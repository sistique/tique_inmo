<?php /** @var gamboamartin\comercial\controllers\controlador_com_tipo_cliente $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_importa_previo_muestra; ?>" class="form-additional">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Campo Base</th>
                                <th>Columna Excel</th>
                                <th>Vista Previa (Fila 1)</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php echo  $controlador->html_mapeo; ?>
                            </tbody>
                        </table>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" value="Importa" name="btn_action_next">Importa</button><br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>


