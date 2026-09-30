<?php /** @var gamboamartin\comercial\controllers\controlador_com_tipo_cliente $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<style>
    .tarjetas-validacion {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .tarjeta-total {
        flex: 1;
        min-width: 140px;
        padding: 12px 16px !important;
        border-radius: 10px;
        border: 1px solid #e0e0e0 !important;
        background: #f8f9fa;
        text-align: left;
    }
    .tarjeta-total.validos {
        background: #eafaf0;
        border-color: #b7e4c7;
    }
    .tarjeta-total.con_errores {
        background: #fdecea;
        border-color: #f5b7b1;
    }
    .tarjeta-total .label {
        font-size: 13px;
        font-weight: 600;
        color: #555;
        margin-bottom: 4px;
    }
    .tarjeta-total.validos .label { color: #2e7d32; }
    .tarjeta-total.con_errores .label { color: #c0392b; }
    .tarjeta-total .valor {
        font-size: 22px;
        font-weight: 700;
        color: #333;
    }
    .tarjeta-total.validos .valor { color: #2e7d32; }
    .tarjeta-total.con_errores .valor { color: #c0392b; }
</style>

<main class="main section-color-primary">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_importa_duplicado; ?>" class="form-additional">
                        <div class="tarjetas-validacion">
                            <?php foreach ($controlador->totales as $key => $total){ ?>
                                <?php $placeholder = implode(' ', array_map('ucfirst', explode('_', $key))); ?>
                                <div class="tarjeta-total <?= $key ?>">
                                    <div class="label"><?= htmlspecialchars($placeholder) ?></div>
                                    <div class="valor"><?= htmlspecialchars($total) ?></div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="content_table">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <?php foreach ($controlador->ths as $th){ ?>
                                        <th><?php echo $th; ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php echo $controlador->html_mapeo; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="controls">
                            <button type="submit" class="btn btn-info" value="Validar" name="btn_action_next">Validar de Nuevo</button>
                            <button type="submit" class="btn btn-success" value="Importa" name="btn_action_next">Siguiente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

