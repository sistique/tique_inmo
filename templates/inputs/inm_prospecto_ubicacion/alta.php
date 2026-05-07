<?php /** @var  gamboamartin\facturacion\controllers\controlador_fc_docto_relacionado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->org_sucursal_id; ?>
<?php echo $controlador->inputs->com_agente_id; ?>
<?php echo $controlador->inputs->com_tipo_prospecto_id; ?>
<?php echo $controlador->inputs->nombre; ?>
<?php echo $controlador->inputs->apellido_paterno; ?>
<?php echo $controlador->inputs->apellido_materno; ?>
<?php echo $controlador->inputs->lada_com; ?>
<?php echo $controlador->inputs->numero_com; ?>
<?php echo $controlador->inputs->cel_com; ?>
<?php echo $controlador->inputs->correo_com; ?>
<?php echo $controlador->inputs->razon_social; ?>

<?php echo $controlador->inputs->dp_estado_id; ?>
<?php echo $controlador->inputs->dp_municipio_id; ?>
<?php echo $controlador->inputs->dp_cp_id; ?>
<?php echo $controlador->inputs->dp_colonia_postal_id; ?>
<?php echo $controlador->inputs->calle; ?>
<?php echo $controlador->inputs->numero_exterior; ?>
<?php echo $controlador->inputs->numero_interior; ?>
<?php echo $controlador->inputs->entre_calle_1; ?>
<?php echo $controlador->inputs->entre_calle_2; ?>

<?php echo $controlador->inputs->observaciones; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>