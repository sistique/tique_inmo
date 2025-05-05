<?php /** @var controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->select->org_actividad_id; ?>
<?php echo $controlador->inputs->select->org_puesto_id; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
