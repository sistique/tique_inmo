<?php /** @var gamboamartin\organigrama\controllers\controlador_org_actividad $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->tiempo; ?>
<?php echo $controlador->inputs->org_tipo_actividad_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

