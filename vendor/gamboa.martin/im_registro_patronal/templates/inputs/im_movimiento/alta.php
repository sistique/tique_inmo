<?php /** @var gamboamartin\im_registro_patronal\controllers\controlador_im_registro_patronal $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->em_empleado_id; ?>
<?php echo $controlador->inputs->em_registro_patronal_id; ?>
<?php echo $controlador->inputs->im_tipo_movimiento_id; ?>
<?php echo $controlador->inputs->fecha; ?>
<?php echo $controlador->inputs->factor_integracion; ?>
<?php echo $controlador->inputs->salario_diario; ?>
<?php echo $controlador->inputs->salario_diario_integrado; ?>
<?php echo $controlador->inputs->salario_mixto; ?>
<?php echo $controlador->inputs->salario_variable; ?>
<?php echo $controlador->inputs->observaciones; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
