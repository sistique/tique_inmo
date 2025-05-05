<?php /** @var  \gamboamartin\organigrama\controllers\controlador_org_asociado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>


<?php echo $controlador->inputs->dp_calle_pertenece_id; ?>


<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->razon_social; ?>


<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>


