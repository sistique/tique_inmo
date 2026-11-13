<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_rel_usuario_emisor $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->adm_usuario_id; ?>
<?php echo $controlador->inputs->not_emisor_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
