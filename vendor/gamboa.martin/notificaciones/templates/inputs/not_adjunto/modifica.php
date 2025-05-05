<?php /** @var  gamboamartin\notificaciones\controllers\controlador_not_mensaje $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->not_mensaje_id; ?>
<?php echo $controlador->inputs->doc_documento_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>