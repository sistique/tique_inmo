<?php /** @var \gamboamartin\empleado\controllers\controlador_em_centro_costo $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
<div class="col-row-12">
    <?php foreach ($controlador->buttons as $button){ ?>
        <?php echo $button; ?>
    <?php }?>
</div>

