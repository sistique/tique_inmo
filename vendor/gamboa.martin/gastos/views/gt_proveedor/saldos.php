<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_proveedor $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <?php include (new views())->ruta_templates . "head/title.php"; ?>
                    <?php include (new views())->ruta_templates . "head/subtitulo.php"; ?>
                    <?php include (new views())->ruta_templates . "mensajes.php"; ?>
                    <div class="table-responsive">
                        <table id="table-gt_cotizacion" class="table mb-0 table-striped table-sm "></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                    <b>Total Cotizaciones</b></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-success">$
                                    <span class="counter-value"><?php echo number_format($controlador->saldos_cotizacion, 2); ?></span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                    <b>Total Ordenes de Compra</b></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-success">$
                                    <span class="counter-value"><?php echo number_format($controlador->saldos_orden_compra, 2); ?></span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-4" style="max-width: 300px; max-height: 300px;">
                <div class="card card-animate">
                    <div class="card-body">
                        <div>
                            <canvas id="saldos_cotizacion" ></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-4" style="max-width: 300px; max-height: 300px;">
                <div class="card card-animate">
                    <div class="card-body">
                        <div>
                            <canvas id="saldos_orden_compra" ></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>


















