<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_solicitud_etapa $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_solicitud_id; ?>
<?php echo $controlador->inputs->pr_etapa_proceso_id; ?>
<?php echo $controlador->inputs->fecha; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>