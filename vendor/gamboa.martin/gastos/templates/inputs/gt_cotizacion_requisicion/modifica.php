<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_empleado_usuario $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_cotizacion_id; ?>
<?php echo $controlador->inputs->gt_requisicion_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>