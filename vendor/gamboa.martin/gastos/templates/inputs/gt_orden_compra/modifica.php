<?php /** @var gamboamartin\gastos\controllers\controlador_gt_orden_compra $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_cotizacion_id; ?>
<?php echo $controlador->inputs->gt_tipo_orden_compra_id; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
