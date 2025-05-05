<?php use config\views; ?>
<?php /** @var \gamboamartin\gastos\controllers\controlador_gt_proveedor $controlador */ ?>
<?php /** @var stdClass $row  viene de registros del controler*/ ?>

<form id="guardarDatos">
    <div class="modal fade" id="dataInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="datos_contact"></div>
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Telefono</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php echo $controlador->row_upd->gt_proveedor_contacto_1?></td>
                            <td><?php echo $controlador->row_upd->gt_proveedor_telefono_1?></td>

                        </tr>
                        <tr>
                            <td><?php echo $controlador->row_upd->gt_proveedor_contacto_2?></td>
                            <td><?php echo $controlador->row_upd->gt_proveedor_telefono_2?></td>

                        </tr>
                        <tr>
                            <td><?php echo $controlador->row_upd->gt_proveedor_contacto_3?></td>
                            <td><?php echo $controlador->row_upd->gt_proveedor_telefono_3?></td>

                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <a href="./index.php?seccion=wt_hogar&accion=detalles_ubicacion&registro_id=<?php echo $controlador->registro_id; ?>&session_id=<?php echo $controlador->session_id; ?>" class="btn btn-info"><i class="glyphicon glyphicon-edit"></i>
                        Modificar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
