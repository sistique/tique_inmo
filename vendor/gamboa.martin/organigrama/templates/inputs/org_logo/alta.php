<?php /** @var gamboamartin\organigrama\controllers\controlador_org_tipo_sucursal $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->org_empresa_id; ?>
<?php echo $controlador->inputs->logo; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
