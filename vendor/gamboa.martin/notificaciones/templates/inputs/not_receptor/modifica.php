<?php /** @var  \gamboamartin\facturacion\controllers\controlador_fc_traslado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->email; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>