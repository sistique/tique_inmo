<?php /** @var gamboamartin\cat_sat\controllers\controlador_cat_sat_grupo_producto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->descripcion; ?>

<?php echo $controlador->inputs->n_dias; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>

