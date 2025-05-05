<?php use config\views; ?>
<?php /** @var controllers\controlador_wt_hogar $controlador */ ?>
<?php /** @var stdClass $row  viene de registros del controler*/ ?>

<tr>
    <td><?php echo $row->gt_solicitud_id; ?></td>
    <td><?php echo $row->gt_solicitud_codigo; ?></td>
    <td><?php echo $row->gt_solicitud_descripcion; ?></td>
    <td><a href="./index.php?seccion=gt_solicitud&accion=asignar_solicitante&registro_id=<?php echo $row->gt_solicitud_id; ?>&session_id=<?php echo $controlador->session_id; ?>" class="btn btn-info">
            Asignar Solicitante
        </a>
    </td>
    <td><a href="./index.php?seccion=gt_solicitud&accion=asignar_autorizante&registro_id=<?php echo $row->gt_solicitud_id; ?>&session_id=<?php echo $controlador->session_id; ?>" class="btn btn-info">
            Asignar Autorizante
        </a>
    </td>



    <!-- End dynamic generated -->

    <?php include (new views())->ruta_templates.'listas/action_row.php';?>
</tr>