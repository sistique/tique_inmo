<?php /** @var gamboamartin\gastos\controllers\controlador_gt_requisicion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_solicitud_id; ?>
<?php echo $controlador->inputs->gt_tipo_requisicion_id; ?>
<?php echo $controlador->inputs->gt_centro_costo_id; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
