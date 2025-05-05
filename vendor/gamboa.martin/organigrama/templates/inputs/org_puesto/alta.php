<?php /** @var \gamboamartin\organigrama\controllers\controlador_org_puesto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->org_tipo_puesto_id; ?>
<?php echo $controlador->inputs->org_departamento_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
