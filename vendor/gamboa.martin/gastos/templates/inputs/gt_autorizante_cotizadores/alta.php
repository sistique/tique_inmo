<?php /** @var  \gamboamartin\gastos\controllers\controlador_gt_autorizante_cotizadores $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->gt_autorizante_id; ?>
<?php echo $controlador->inputs->gt_cotizador_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>