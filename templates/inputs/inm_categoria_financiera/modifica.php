<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_categoria_financiera $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

