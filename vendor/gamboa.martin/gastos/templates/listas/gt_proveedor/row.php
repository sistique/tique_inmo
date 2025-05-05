<?php use config\views;
use models\gt_tipo_proveedor; ?>
<?php /** @var controllers\controlador_gt_proveedor $controlador */ ?>
<?php /** @var stdClass $row  viene de registros del controler*/ ?>

<?php $gt_tipo_proveedor = new gt_tipo_proveedor(link: $controlador->link);?>
<?php $r_gt_tipo_proveedor = $gt_tipo_proveedor->registro(
        registro_id: $row->gt_proveedor_gt_tipo_proveedor_id);?>

<tr>
    <td><?php echo $row->gt_proveedor_codigo; ?></td>
    <td><?php echo $row->gt_proveedor_rfc; ?></td>
    <td><?php echo $row->gt_proveedor_descripcion; ?></td>
    <td><?php echo $r_gt_tipo_proveedor['gt_tipo_proveedor_descripcion']; ?></td>
    <td>
        <a href="./index.php?seccion=gt_proveedor&accion=proveedor_datos_fiscales&registro_id=<?php echo $row->gt_proveedor_id; ?>&session_id=<?php echo $controlador->session_id; ?>" class="btn btn-info"><i class=""></i>
            Datos Fiscales
        </a>
    </td>

    <!-- End dynamic generated -->

    <?php include (new views())->ruta_templates.'listas/action_row.php';?>
</tr>