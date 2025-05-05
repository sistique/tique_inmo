<?php /** @var \gamboamartin\cat_sat\controllers\controlador_cat_sat_tipo_producto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->cat_sat_conf_imps_id; ?>
<?php echo $controlador->inputs->cat_sat_factor_id; ?>
<?php echo $controlador->inputs->cat_sat_tipo_factor_id; ?>
<?php echo $controlador->inputs->cat_sat_tipo_impuesto_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
