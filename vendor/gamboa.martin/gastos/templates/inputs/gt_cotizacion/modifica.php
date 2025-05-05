<?php /** @var gamboamartin\gastos\controllers\controlador_gt_cotizacion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_requisicion_id; ?>
<?php echo $controlador->inputs->gt_tipo_cotizacion_id; ?>
<?php echo $controlador->inputs->gt_proveedor_id; ?>
<?php echo $controlador->inputs->gt_centro_costo_id; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
