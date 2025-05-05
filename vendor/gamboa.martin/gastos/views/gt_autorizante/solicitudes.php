<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_requisicion $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php include (new views())->ruta_templates . "head/title.php"; ?>
                <div class="widget widget-box box-container widget-mylistings">

                    <div class="widget-header"
                         style="display: flex;justify-content: space-between;align-items: center;">
                        <h2>Registro de solicitudes</h2>
                    </div>

                    <div class="table-responsive">
                        <table id="gt_autorizantes" class="datatables table mb-0 table-striped table-sm "></table>
                    </div>
                </div>
            </div>

        </div>
    </div>


</main>


















