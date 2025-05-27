<?php /** @var gamboamartin\acl\controllers\controlador_adm_tipo_evento $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->descripcion; ?>

<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

