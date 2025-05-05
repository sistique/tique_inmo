<?php /** @var gamboamartin\acl\controllers\controlador_adm_seccion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->doc_documento_id; ?>
<?php echo $controlador->inputs->pr_etapa_proceso_id; ?>
<?php echo $controlador->inputs->fecha; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
