<?php /** @var \gamboamartin\notificaciones\controllers\controlador_not_notificacion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->adm_usuario_accion_id; ?>
<?php echo $controlador->inputs->adm_usuario_notificado_id; ?>
<?php echo $controlador->inputs->adm_accion_id; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>