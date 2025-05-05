<?php /** @var gamboamartin\cat_sat\controllers\controlador_cat_sat_clase_producto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->cat_sat_conf_reg_tp_id; ?>
<?php echo $controlador->inputs->cat_sat_conf_imps_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
<div class="error" style="margin-bottom: 20px"></div>

