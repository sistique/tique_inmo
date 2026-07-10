<?php /** @var  gamboamartin\facturacion\controllers\controlador_fc_docto_relacionado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->nss; ?>

<?php echo $controlador->inputs->nombre; ?>
<?php echo $controlador->inputs->apellido_paterno; ?>
<?php echo $controlador->inputs->apellido_materno; ?>

<?php echo $controlador->inputs->lada_com; ?>
<?php echo $controlador->inputs->numero_com; ?>
<?php echo $controlador->inputs->cel_com; ?>
<?php echo $controlador->inputs->correo_com; ?>

<?php echo $controlador->inputs->dp_estado_id; ?>
<?php echo $controlador->inputs->dp_municipio_id; ?>
<?php echo $controlador->inputs->dp_cp_id; ?>
<?php echo $controlador->inputs->dp_colonia_postal_id; ?>
<?php echo $controlador->inputs->calle; ?>
<?php echo $controlador->inputs->numero_exterior; ?>
<?php echo $controlador->inputs->numero_interior; ?>
<?php echo $controlador->inputs->entre_calle_1; ?>
<?php echo $controlador->inputs->entre_calle_2; ?>

<?php echo $controlador->inputs->numero_credito; ?>
<?php echo $controlador->inputs->adeudo_hipoteca; ?>
<?php echo $controlador->inputs->mensualidad; ?>

<?php echo $controlador->inputs->cuenta_predial; ?>
<?php echo $controlador->inputs->adeudo_predial; ?>
<?php echo $controlador->inputs->cuenta_agua; ?>
<?php echo $controlador->inputs->adeudo_agua; ?>
<?php echo $controlador->inputs->adeudo_luz; ?>

<?php echo $controlador->inputs->observaciones; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>