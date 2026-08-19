<?php /** @var gamboamartin\inmuebles\controllers\controlador_inm_sat_key $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_sat_cer_id; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->ruta_llave; ?>
<?php echo $controlador->inputs->contrasenia; ?>
<?php include (new views())->ruta_templates . 'botons/submit/modifica_bd.php'; ?>

