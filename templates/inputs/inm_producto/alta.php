<?php /** @var  gamboamartin\facturacion\controllers\controlador_fc_docto_relacionado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->cat_sat_unidad_id; ?>
<?php echo $controlador->inputs->cat_sat_cve_prod_codigo; ?>
<?php echo $controlador->inputs->costo_promedio; ?>
<?php echo $controlador->inputs->cantidad_actual; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>