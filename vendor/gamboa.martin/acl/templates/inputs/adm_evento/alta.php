<?php /** @var gamboamartin\acl\controllers\controlador_adm_evento $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->adm_tipo_evento_id; ?>
<?php echo $controlador->inputs->adm_calendario_id; ?>
<?php echo $controlador->inputs->titulo; ?>
<?php echo $controlador->inputs->fecha_inicio; ?>
<div class="control-group col-sm-6">
    <label class="control-label" for="hora_inicio">Hora Inicio</label>
    <div class="controls">
        <input type="time" name="hora_inicio" value="" class="form-control" required="" id="hora_inicio" placeholder="Hora Inicio">
    </div>
</div>

<?php echo $controlador->inputs->fecha_fin; ?>
<div class="control-group col-sm-6">
    <label class="control-label" for="hora_fin">Hora Fin</label>
    <div class="controls">
        <input type="time" name="hora_fin" value="" class="form-control" required="" id="hora_fin" placeholder="Hora Fin">
    </div>
</div>
<?php echo $controlador->inputs->descripcion; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
