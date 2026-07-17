<?php /** @var  gamboamartin\facturacion\controllers\controlador_fc_docto_relacionado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->nss; ?>
<?php echo $controlador->inputs->nombre; ?>
<?php echo $controlador->inputs->apellido_paterno; ?>
<?php echo $controlador->inputs->apellido_materno; ?>
<?php echo $controlador->inputs->numero_com; ?>
<?php echo $controlador->inputs->cel_com; ?>
<?php echo $controlador->inputs->correo_com; ?>
<?php echo $controlador->inputs->observaciones; ?>
<?php echo $controlador->inputs->documento; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>