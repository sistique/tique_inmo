<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_autorizante_ejecutores_compra $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_autorizante_id; ?>
<?php echo $controlador->inputs->gt_ejecutor_compra_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>