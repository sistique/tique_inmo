<?php /** @var  \gamboamartin\im_registro_patronal\controllers\controlador_im_rcv $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->codigo_bis; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->descripcion_select; ?>
<?php echo $controlador->inputs->alias; ?>
<?php echo $controlador->inputs->factor; ?>
<?php echo $controlador->inputs->monto_inicial; ?>
<?php echo $controlador->inputs->monto_final; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>

