<?php /** @var gamboamartin\inmuebles\controllers\controlador_inm_sat_solicitud $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_sat_cer_id; ?>
<?php echo $controlador->inputs->tipo_solicitud; ?>
<?php echo $controlador->inputs->tipo_descarga; ?>
<?php echo $controlador->inputs->tipo_comprobante; ?>
<?php echo $controlador->inputs->rfc_tercero; ?>
<?php echo $controlador->inputs->fecha_inicio_periodo; ?>
<?php echo $controlador->inputs->fecha_fin_periodo; ?>
<?php echo $controlador->inputs->estatus; ?>
<?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

