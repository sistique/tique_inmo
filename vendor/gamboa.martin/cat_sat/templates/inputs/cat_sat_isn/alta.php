<?php /** @var  \gamboamartin\cat_sat\controllers\controlador_cat_sat_isn $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->dp_estado_id; ?>
<?php echo $controlador->inputs->porcentaje_isn; ?>
<?php echo $controlador->inputs->factor_isn_adicional; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>