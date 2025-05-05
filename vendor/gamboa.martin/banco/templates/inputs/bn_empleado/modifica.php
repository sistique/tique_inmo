<?php /** @var  \gamboamartin\banco\controllers\controlador_bn_banco $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->nombre; ?>
<?php echo $controlador->inputs->ap; ?>
<?php echo $controlador->inputs->am; ?>
<?php echo $controlador->inputs->org_puesto_id; ?>

<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>



<div class="cold-row-12">
    <?php foreach ($controlador->buttons as $button){ ?>
        <?php echo $button; ?>
    <?php }?>
</div>
