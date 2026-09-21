<?php /** @var gamboamartin\comercial\controllers\controlador_com_tipo_cliente $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php
echo "<style>
.widget-form-cart form div.drop-zone, .drop-zone {
    margin-bottom: 10px;
}
</style>";
?>

<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_importa_previo; ?>" class="form-additional" enctype="multipart/form-data">
                        <?php echo $controlador->inputs->input_file; ?>

                        <div class="controls">
                            <button type="submit" class="btn btn-success" value="Importa" name="btn_action_next">Importa</button><br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>


