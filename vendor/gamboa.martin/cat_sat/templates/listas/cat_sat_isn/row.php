<?php use config\views; ?>
<?php /** @var stdClass $row  viene de registros del controler*/ ?>
<tr>
    <td><?php echo $row->cat_sat_isn_codigo; ?></td>
    <!-- Dynamic generated -->
    <td><?php echo $row->cat_sat_isn_descripcion; ?></td>

    <td><?php echo $row->dp_estado_descripcion; ?></td>
    <td><?php echo $row->cat_sat_isn_porcentaje; ?></td>

    <!-- End dynamic generated -->

    <?php include (new views())->ruta_templates.'listas/action_row.php';?>
</tr>
