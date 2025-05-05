<?php /** @var  gamboamartin\proceso\controllers\controlador_pr_proceso $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->pr_proceso_id; ?>
<?php echo $controlador->inputs->adm_seccion_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>