<?php /** @var  \gamboamartin\banco\controllers\controlador_adm_session $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->nombre; ?>
<?php echo $controlador->inputs->ap; ?>
<?php echo $controlador->inputs->am; ?>
<?php echo $controlador->inputs->org_puesto_id; ?>


<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>