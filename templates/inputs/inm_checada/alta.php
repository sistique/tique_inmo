<?php /** @var  gamboamartin\facturacion\controllers\controlador_fc_docto_relacionado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->inm_empleado_id; ?>
<?php echo $controlador->inputs->fecha; ?>
<?php echo $controlador->inputs->hora; ?>
<?php echo $controlador->inputs->observaciones; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>