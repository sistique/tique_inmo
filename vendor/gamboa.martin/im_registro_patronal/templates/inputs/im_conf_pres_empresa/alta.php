<?php /** @var gamboamartin\im_registro_patronal\controllers\controlador_im_conf_pres_empresa $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->org_empresa_id; ?>
<?php echo $controlador->inputs->im_conf_prestaciones_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
<div class="control-group btn-alta">
</div>