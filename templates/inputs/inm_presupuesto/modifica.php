<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_presupuesto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_categoria_financiera_id; ?>
<?php echo $controlador->inputs->anio; ?>
<?php echo $controlador->inputs->mes; ?>
<?php echo $controlador->inputs->es_ingreso; ?>
<?php echo $controlador->inputs->monto_proyectado; ?>
<?php echo $controlador->inputs->observaciones; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

