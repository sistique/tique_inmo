<?php /** @var  gamboamartin\proceso\controllers\controlador_pr_entidad_proceso $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->select->adm_seccion_id; ?>
<?php echo $controlador->inputs->select->pr_proceso_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>