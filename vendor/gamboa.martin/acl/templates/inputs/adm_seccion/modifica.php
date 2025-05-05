<?php /** @var gamboamartin\controllers\controlador_adm_seccion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->etiqueta_label; ?>
<?php echo $controlador->inputs->adm_menu_id; ?>
<?php echo $controlador->inputs->adm_namespace_id; ?>


<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

