<?php /** @var  gamboamartin\facturacion\controllers\controlador_fc_docto_relacionado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_prospecto_id; ?>
<?php echo $controlador->inputs->inm_comprador_id; ?>
<?php echo $controlador->inputs->inm_tipo_beneficiario_id; ?>
<?php echo $controlador->inputs->inm_parentesco_id; ?>
<?php echo $controlador->inputs->nombre; ?>
<?php echo $controlador->inputs->apellido_paterno; ?>
<?php echo $controlador->inputs->apellido_materno; ?>

<?php echo $controlador->inputs->btn_action_next; ?>
<?php echo $controlador->inputs->id_retorno; ?>
<?php echo $controlador->inputs->seccion_retorno; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>