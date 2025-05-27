<?php /** @var \gamboamartin\documento\controllers\controlador_doc_conf_tipo_documento_seccion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->doc_tipo_documento_id; ?>
<?php echo $controlador->inputs->adm_seccion_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>