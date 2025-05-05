<?php /** @var base\controller\controlador_base $controlador  viene de registros del controler/lista */ ?>

<?php
use config\generales;
use config\views;
use models\gt_solicitantes;

$gt_solicitantes = new gt_solicitantes($controlador->link);
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
                        <li class="item"> Asignar Solicitante </li>
                    </ul>    <h1 class="h-side-title page-title page-title-big text-color-primary"><?php echo strtoupper($controlador->row_upd->gt_solicitud_codigo)?></h1>
                </section> <!-- /. content-header -->
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header">
                        <h2>Asignar Solicitante</h2>
                    </div>
                    <div>
                        <form method="post" action="./index.php?seccion=gt_solicitantes&accion=alta_bd&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>" class="form-additional">

                            <?php echo $controlador->inputs->select->gt_solicitud_id; ?>
                            <?php echo $controlador->inputs->select->gt_solicitante_id; ?>
                            <br>
                            <div class="col-sm-12 text-center">
                                <label id="label_alerta" class="label-error-solicitante text-danger">El solicitante ya ha sido asignado </label>
                            </div>
                            <input type="hidden" name="gt_solicitud_id" value="<?php echo $controlador->registro_id ?>">
                            <div class="control-group btn-modifica">
                                <div class="controls">
                                    <button type="submit" class="btn btn-success btn-asignar ">Asignar Solicitante</button>
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
                                let id_solicitantes = Array();
                            </script>
                            <?php
                            $gt_solicitantes_buscados['gt_solicitud_id'] = $controlador->registro_id;
                            $gt_solicitantes->registros();
                            $r_gt_autorizantes = $gt_solicitantes->filtro_and(filtro: $gt_solicitantes_buscados);
                            foreach ($gt_solicitantes->registros as $solicitantes){
                                ?>
                                <tr>
                                    <td><?php echo $solicitantes['gt_solicitantes_id']; ?></td>
                                    <td><?php echo $solicitantes['gt_solicitantes_codigo']; ?></td>
                                    <td><?php echo $solicitantes['gt_solicitantes_codigo_bis']; ?></td>
                                    <td><?php echo $solicitantes['gt_solicitantes_descripcion']; ?></td>
                                    <td><?php echo $solicitantes['gt_solicitantes_fecha_alta']; ?></td>
                                    <script>
                                        id_solicitantes.push(<?php echo $solicitantes['gt_solicitantes_gt_solicitante_id']; ?>);
                                    </script>
                                    <td><a href="./index.php?seccion=gt_solicitantes&accion=modifica&registro_id=<?php echo $solicitantes['gt_solicitantes_id']; ?>&session_id=<?php echo $controlador->session_id; ?>" class="btn btn-info">
                                            Modificar
                                        </a>
                                    </td>
                                    <td><a href="./index.php?seccion=gt_solicitantes&accion=elimina_bd&registro_id=<?php echo $solicitantes['gt_solicitantes_id']; ?>&session_id=<?php echo $controlador->session_id; ?>" class="btn btn-danger">
                                            Eliminar
                                        </a>
                                    </td>


                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="box-body">
                            * Total registros: <?php echo $gt_solicitantes->n_registros; ?><br />
                            * Fecha Hora: <?php echo $controlador->fecha_hoy; ?>
                        </div>
                    </div>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>
    </div>
</div>