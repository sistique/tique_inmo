<?php /** @var  \gamboamartin\empleado\controllers\controlador_em_cuenta_bancaria $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->em_empleado_id; ?>
<?php echo $controlador->inputs->bn_sucursal_id; ?>
<?php echo $controlador->inputs->clabe; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->num_cuenta; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>