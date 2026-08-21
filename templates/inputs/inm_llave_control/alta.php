<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_llave_control $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_llave_id; ?>
<?php echo $controlador->inputs->responsable_id; ?>
<?php echo $controlador->inputs->fecha_entrega; ?>
<?php echo $controlador->inputs->fecha_devolucion; ?>
<?php echo $controlador->inputs->observaciones; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
