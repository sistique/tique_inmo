<?php /** @var gamboamartin\cat_sat\controllers\controlador_cat_sat_periodo $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->cat_sat_periodicidad_pago_nom_id; ?>
<?php echo $controlador->inputs->fecha_inicio; ?>
<?php echo $controlador->inputs->fecha_fin; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
<div class="error"></div>


