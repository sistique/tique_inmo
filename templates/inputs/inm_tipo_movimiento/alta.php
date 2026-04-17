<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_tipo_movimiento $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>

