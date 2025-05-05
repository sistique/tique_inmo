<?php /** @var  gamboamartin\notificaciones\controllers\controlador_not_mensaje $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->asunto; ?>
<?php echo $controlador->inputs->mensaje; ?>
<?php echo $controlador->inputs->not_emisor_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>