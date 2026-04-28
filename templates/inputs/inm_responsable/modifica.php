<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_responsable $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->tipo; ?>
<?php echo $controlador->inputs->telefono; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
