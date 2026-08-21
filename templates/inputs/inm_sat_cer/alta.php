<?php /** @var gamboamartin\inmuebles\controllers\controlador_inm_sat_cer $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->rfc; ?>
<?php echo $controlador->inputs->ruta_certificado; ?>
<?php echo $controlador->inputs->numero_serie; ?>
<?php include (new views())->ruta_templates . 'botons/submit/alta_bd.php'; ?>

