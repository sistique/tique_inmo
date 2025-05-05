<?php use config\views; ?>
<?php /** @var controllers\controlador_wt_hogar $controlador */ ?>
<?php /** @var stdClass $row  viene de registros del controler*/ ?>

<tr>
    <td><?php echo $row->gt_tipo_proveedor_id; ?></td>
    <td><?php echo $row->gt_tipo_proveedor_codigo; ?></td>
    <td><?php echo $row->gt_tipo_proveedor_codigo_bis; ?></td>
    <td><?php echo $row->gt_tipo_proveedor_descripcion; ?></td>
    <td><?php echo $row->gt_tipo_proveedor_descripcion_select; ?></td>
    <td><?php echo $row->gt_tipo_proveedor_alias; ?></td>

    <!-- End dynamic generated -->

    <?php include (new views())->ruta_templates.'listas/action_row.php';?>
</tr>