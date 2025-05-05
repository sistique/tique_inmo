<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_solicitante $controlador controlador en ejecucion */ ?>
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
                        <?php echo $controlador->inputs->gt_tipo_solicitud_id; ?>
                        <?php echo $controlador->inputs->gt_centro_costo_id; ?>
                        <?php echo $controlador->inputs->descripcion; ?>
                        <?php //include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>
                    </form>
                </div>

            </div>

        </div>
    </div>

    <div class="container autorizantes">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header"
                         style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Autorizantes</h2>
                    </div>
                    <form method="post" action="#" class="form-additional" id="frm-autorizante">

                        <?php echo $controlador->inputs->gt_autorizante_id; ?>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="button" class="btn btn-success" value="modifica" name="btn_action_next"
                                        id="btn-alta-autorizante">Alta
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
                        <h2>Autorizantes</h2>
                    </div>

                    <div class="table-responsive">
                        <table id="table-gt_autorizantes" class="table mb-0 table-striped table-sm "></table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container solicitantes">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header"
                         style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Solicitantes</h2>
                    </div>
                    <form method="post" action="#" class="form-additional" id="frm-solicitante">

                        <?php echo $controlador->inputs->gt_solicitante_id; ?>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button type="button" class="btn btn-success" value="modifica" name="btn_action_next"
                                        id="btn-alta-solicitante">Alta
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
                        <h2>Solicitantes</h2>
                    </div>

                    <div class="table-responsive">
                        <table id="table-gt_solicitantes" class="table mb-0 table-striped table-sm "></table>
                    </div>
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
                        <table id="gt_solicitud_producto" class="datatables table mb-0 table-striped table-sm "></table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container ">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header"
                         style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Requisicion</h2>
                    </div>
                    <form method="post" class="form-additional" id="form-requisicion" action="<?php echo $controlador->link_producto_bd; ?>">

                        <input id="agregar_producto" name="agregar_producto" type="hidden">
                        <?php echo $controlador->inputs->gt_tipo_requisicion_id; ?>
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
                        <h2>Registro de Requisiciones</h2>
                    </div>

                    <div class="table-responsive">
                        <table id="table-gt_solicitud_requisicion" class="table mb-0 table-striped table-sm "></table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>


















