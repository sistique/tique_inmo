<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_cotizacion $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form" style="display: flex;">

                    <form method="post" action="<?php echo $controlador->link_modifica_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates . "head/title.php"; ?>
                        <?php include (new views())->ruta_templates . "head/subtitulo.php"; ?>
                        <?php include (new views())->ruta_templates . "mensajes.php"; ?>
                        <?php echo $controlador->inputs->gt_tipo_cotizacion_id; ?>
                        <?php echo $controlador->inputs->gt_proveedor_id; ?>
                        <?php echo $controlador->inputs->gt_centro_costo_id; ?>
                        <?php echo $controlador->inputs->descripcion; ?>
                        <?php //include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                    </form>
                </div>

            </div>

        </div>
    </div>

    <div class="container producto">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header"
                         style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Producto</h2>
                    </div>
                    <form method="post" action="#" class="form-additional" id="frm-producto">
                        <?php echo $controlador->inputs->com_producto_id; ?>
                        <?php echo $controlador->inputs->cat_sat_unidad_id; ?>
                        <?php echo $controlador->inputs->cantidad; ?>
                        <?php echo $controlador->inputs->precio; ?>
                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="button" class="btn btn-success" value="producto" name="btn_action_next"
                                        id="btn-alta-producto">Alta
                                </button>
                                <br>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="widget widget-box box-container widget-mylistings">
                    <div class="widget-header"
                         style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Registro de productos</h2>
                    </div>

                    <div class="table-responsive">
                        <table id="gt_cotizacion_producto" class="datatables table mb-0 table-striped table-sm "></table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container requisitores">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header"
                         style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Orden de Compra</h2>
                    </div>
                    <form method="post" class="form-additional" id="form-orden" action="<?php echo $controlador->link_producto_bd; ?>">

                        <input id="agregar_producto" name="agregar_producto" type="hidden">
                        <?php echo $controlador->inputs->gt_tipo_orden_compra_id; ?>
                        <?php echo $controlador->inputs->descripcion2; ?>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="submit" class="btn btn-success" value="modifica" name="btn_action_next"
                                        id="btn-alta-requisitor">Alta
                                </button>
                                <br>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="widget widget-box box-container widget-mylistings">
                    <div class="widget-header"
                         style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Registro de Ordenes de Compra</h2>
                    </div>

                    <div class="table-responsive">
                        <table id="table-gt_orden_compra_cotizacion" class="table mb-0 table-striped table-sm "></table>
                    </div>

                </div>
            </div>

        </div>
    </div>

</main>


















