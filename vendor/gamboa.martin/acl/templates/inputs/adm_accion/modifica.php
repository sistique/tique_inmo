<?php /** @var gamboamartin\acl\controllers\controlador_adm_seccion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->descripcion; ?>

<?php echo $controlador->inputs->titulo; ?>
<?php echo $controlador->inputs->css; ?>
<?php echo $controlador->inputs->icono; ?>


<?php echo $controlador->inputs->adm_menu_id; ?>
<?php echo $controlador->inputs->adm_seccion_id; ?>



<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

