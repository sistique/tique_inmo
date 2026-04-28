<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_llave $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_ubicacion_id; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
