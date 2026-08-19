<?php /** @var gamboamartin\inmuebles\controllers\controlador_inm_sat_paquete $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_sat_solicitud_id; ?>
<?php echo $controlador->inputs->id_paquete; ?>
<?php echo $controlador->inputs->estatus; ?>
<?php include (new views())->ruta_templates . 'botons/submit/alta_bd.php'; ?>

