<?php /** @var base\controller\controlador_base $controlador  viene de registros del controler/lista */ ?>

<?php
use config\generales;
use config\views;
use models\gt_autorizantes;

$gt_autorizantes = new gt_autorizantes($controlador->link);
?>
<div class="widget  widget-box box-container form-main widget-form-cart" id="form">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <section class="top-title">
                    <ul class="breadcrumb">
                        <li class="item"><a href="./index.php?seccion=adm_session&accion=inicio&session_id=<?php echo $controlador->session_id; ?>"> Inicio </a></li>
                        <li class="item"><a href="./index.php?seccion=gt_solicitud&accion=lista&session_id=<?php echo $controlador->session_id; ?>"> Lista </a></li>
                        <?php //var_dump($controlador->row_upd); exit; ?>
                        <li class="item"> Asignar Autorizante </li>
                    </ul>    <h1 class="h-side-title page-title page-title-big text-color-primary"><?php echo strtoupper($controlador->row_upd->gt_solicitud_codigo)?></h1>
                </section> <!-- /. content-header -->
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header">
                        <h2>Asignar Autorizante</h2>
                    </div>
                    <div>
                        <form method="post" action="./index.php?seccion=gt_autorizantes&accion=alta_bd&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>" class="form-additional">

                            <?php echo $controlador->inputs->select->gt_solicitud_id; ?>
                            <?php echo $controlador->inputs->select->gt_autorizante_id; ?>
                            <br>
                            <div class="col-sm-12 text-center">
                                <label id="label_alerta" class="label-error-autorizante text-danger">El autorizante ya ha sido asignado </label>
                            </div>
                            <input type="hidden" name="gt_solicitud_id" value="<?php echo $controlador->registro_id ?>">
                            <div class="control-group btn-modifica">
                                <div class="controls">
                                    <button type="submit" class="btn btn-success btn-asignar">Asignar Autorizante</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div><!-- /.center-content -->
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="widget widget-box box-container widget-mylistings">

                    <div class="">
                        <table class="table table-striped footable-sort" data-sorting="true">
                            <th>Id</th>
                            <th>Codigo</th>
                            <th>Codigo Bis</th>
                            <th>Descripcion</th>
                            <th>Fecha asignacion</th>

                            <th>Modifica</th>
                            <th>Elimina</th>

                            <tbody>
                            <script>
                                let id_autorizantes = Array();
                            </script>
                            <?php
                            $gt_autorizantes_buscados['gt_solicitud_id'] = $controlador->registro_id;
                            $gt_autorizantes->registros();
                            $r_gt_autorizantes = $gt_autorizantes->filtro_and(filtro: $gt_autorizantes_buscados);
                            foreach ($gt_autorizantes->registros as $autorizante){
                                ?>
                                <tr>
                                    <td><?php echo $autorizante['gt_autorizantes_id']; ?></td>
                                    <td><?php echo $autorizante['gt_autorizantes_codigo']; ?></td>
                                    <td><?php echo $autorizante['gt_autorizantes_codigo_bis']; ?></td>
                                    <td><?php echo $autorizante['gt_autorizantes_descripcion']; ?></td>
                                    <td><?php echo $autorizante['gt_autorizantes_fecha_alta']; ?></td>
                                    <script>
                                        id_autorizantes.push(<?php echo $autorizante['gt_autorizantes_gt_autorizante_id']; ?>);
                                    </script>
                                    <td><a href="./index.php?seccion=gt_autorizantes&accion=modifica&registro_id=<?php echo $autorizante['gt_autorizantes_id']; ?>&session_id=<?php echo $controlador->session_id; ?>" class="btn btn-info">
                                            Modificar
                                        </a>
                                    </td>
                                    <td><a href="./index.php?seccion=gt_autorizantes&accion=elimina_bd&registro_id=<?php echo $autorizante['gt_autorizantes_id']; ?>&session_id=<?php echo $controlador->session_id; ?>" class="btn btn-danger">
                                            Eliminar
                                        </a>
                                    </td>


                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="box-body">
                            * Total registros: <?php echo $gt_autorizantes->n_registros; ?><br />
                            * Fecha Hora: <?php echo $controlador->fecha_hoy; ?>
                        </div>
                    </div>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>
    </div>
</div>

