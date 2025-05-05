<?php /** @var gamboamartin\organigrama\controllers\controlador_org_ejecuta $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->select->org_actividad_id; ?>
<?php echo $controlador->inputs->select->org_puesto_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
