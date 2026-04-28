<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_reparacion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_ubicacion_id; ?>
<?php echo $controlador->inputs->responsable_id; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->fecha_inicio; ?>
<?php echo $controlador->inputs->fecha_fin; ?>
<?php echo $controlador->inputs->estatus; ?>
<?php echo $controlador->inputs->observaciones; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
