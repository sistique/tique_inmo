<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_mov_real $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_categoria_financiera_id; ?>
<?php echo $controlador->inputs->inm_tipo_movimiento_id; ?>
<?php echo $controlador->inputs->fecha; ?>
<?php echo $controlador->inputs->es_ingreso; ?>
<?php echo $controlador->inputs->monto; ?>
<?php echo $controlador->inputs->referencia; ?>
<?php echo $controlador->inputs->observaciones; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

