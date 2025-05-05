<?php /** @var  \gamboamartin\cat_sat\controllers\controlador_cat_sat_obj_imp $controlador  $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>


