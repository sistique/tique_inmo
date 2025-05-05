<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_autorizante $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->em_empleado_id; ?>

    <div class="col-md-12">
        <table id="table-pr_proceso" class="table mb-0 table-striped table-sm "></table>
    </div>

    <input type="hidden" id="pr_procesos" name="pr_procesos" required>


<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>