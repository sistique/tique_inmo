<?php /** @var gamboamartin\acl\controllers\controlador_adm_seccion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->titulo; ?>
<?php echo $controlador->inputs->css; ?>
<?php echo $controlador->inputs->icono; ?>


<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

