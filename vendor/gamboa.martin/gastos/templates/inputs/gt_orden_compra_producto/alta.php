<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_orden_compra_producto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_orden_compra_id; ?>
<?php echo $controlador->inputs->com_producto_id; ?>
<?php echo $controlador->inputs->cat_sat_unidad_id; ?>
<?php echo $controlador->inputs->cantidad; ?>
<?php echo $controlador->inputs->precio; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>