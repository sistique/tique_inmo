<?php /** @var \controllers\controlador_dp_direccion_pendiente $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion_pais; ?>
<?php echo $controlador->inputs->descripcion_estado; ?>
<?php echo $controlador->inputs->descripcion_municipio; ?>
<?php echo $controlador->inputs->descripcion_cp; ?>
<?php echo $controlador->inputs->descripcion_colonia; ?>
<?php echo $controlador->inputs->descripcion_calle_pertenece; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>