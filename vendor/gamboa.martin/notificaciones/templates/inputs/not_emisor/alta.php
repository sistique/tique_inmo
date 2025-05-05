<?php /** @var  \gamboamartin\facturacion\controllers\controlador_fc_traslado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->email; ?>
<?php echo $controlador->inputs->user_name; ?>
<?php echo $controlador->inputs->password; ?>
<?php echo $controlador->inputs->port; ?>
<?php echo $controlador->inputs->host; ?>
<?php echo $controlador->inputs->smtp_secure; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>