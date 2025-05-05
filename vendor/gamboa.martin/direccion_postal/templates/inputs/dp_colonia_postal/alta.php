<?php /** @var controllers\controlador_dp_colonia_postal $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->dp_pais_id; ?>
<?php echo $controlador->inputs->dp_estado_id; ?>
<?php echo $controlador->inputs->dp_municipio_id; ?>
<?php echo $controlador->inputs->dp_cp_id; ?>

<div class="col-md-12">
    <table id="dp_colonia"  class="table  table-striped" ></table>
</div>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>

