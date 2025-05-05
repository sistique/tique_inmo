<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_autorizantes $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_solicitud_id; ?>
<?php echo $controlador->inputs->gt_autorizante_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>