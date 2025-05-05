<?php /** @var gamboamartin\im_registro_patronal\controllers\controlador_im_codigo_clase $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->descripcion; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
