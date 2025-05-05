<?php /** @var base\controller\controlador_base $controlador  viene de registros del controler/lista */ ?>

<?php
use config\generales;
use config\views;
?>
<div class="widget  widget-box box-container form-main widget-form-cart" id="form">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <section class="top-title">
                    <ul class="breadcrumb">
                        <li class="item"><a href="./index.php?seccion=adm_session&accion=inicio&session_id=<?php echo $controlador->session_id; ?>"> Inicio </a></li>
                        <li class="item"><a href="./index.php?seccion=gt_proveedor&accion=lista&session_id=<?php echo $controlador->session_id; ?>"> Lista </a></li>
                        <?php //var_dump($controlador->row_upd); exit;?>
                        <li class="item"> <?php echo $controlador->row_upd->descripcion?> </li>
                    </ul>    <h1 class="h-side-title page-title page-title-big text-color-primary"><?php echo strtoupper($controlador->row_upd->descripcion)?></h1>
                </section> <!-- /. content-header -->
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <div class="widget-header">
                        <h2>Ver Datos Fiscales</h2>
                    </div>
                    <div>
                        <form method="post" action="./index.php?seccion=gt_proveedor&accion=modifica_bd&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>" class="form-additional">
                            <div class="row form-group">
                                <div class="col-md-12 mb-3 mb-md-0">
                                    <h3>Datos Fiscales</h3>

                                </div>
                            </div>



                            <div class="control-group col-sm-6">
                                <label class="control-label" for="rfc">RFC</label>
                                <div class="controls">
                                    <input type="text" name="rfc" value="<?php echo $controlador->row_upd->rfc; ?>" class="form-control" required id="rfc" placeholder="RFC">
                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label class="control-label" for="razon_social">Razon social</label>
                                <div class="controls">
                                    <input type="text" name="razon_social" value="<?php echo $controlador->row_upd->razon_social; ?>" class="form-control" required id="razon_social" placeholder="Razon Social">
                                </div>
                            </div>
                            <?php echo $controlador->inputs->select->cat_sat_regimen_fiscal_id; ?>

                            <?php echo $controlador->inputs->select->dp_calle_pertenece_id; ?>

                            <div class="control-group col-sm-6">
                                <label class="control-label" for="exterior">Exterior</label>
                                <div class="controls">
                                    <input type="text" name="exterior" value="<?php echo $controlador->row_upd->exterior; ?>" class="form-control" required id="exterior" placeholder="Exterior">
                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label class="control-label" for="interior">Interior</label>
                                <div class="controls">
                                    <input type="text" name="interior" value="<?php echo $controlador->row_upd->interior; ?>" class="form-control" id="interior" placeholder="Interior">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12 mb-3 mb-md-0">
                                    <h3>Contacto</h3>

                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label class="control-label" for="contacto_1">Contacto 1</label>
                                <div class="controls">
                                    <input type="text" name="contacto_1" value="<?php echo $controlador->row_upd->contacto_1; ?>" class="form-control" required id="contacto_1" placeholder="Contacto 1">
                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label class="control-label" for="telefono_1">Telefono 1</label>
                                <div class="controls">
                                    <input type="text" name="telefono_1" value="<?php echo $controlador->row_upd->telefono_1; ?>" class="form-control" required id="telefono_1" placeholder="Telefono 1">
                                    <div class="col-sm-12 content_center">
                                        <label id="label_alerta" class="label-error-telefono-1 text-danger">Introduzca un numero de telefono valido </label>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label class="control-label" for="contacto_2">Contacto 2</label>
                                <div class="controls">
                                    <input type="text" name="contacto_2" value="<?php echo $controlador->row_upd->contacto_2; ?>" class="form-control" id="contacto_2" placeholder="Contacto 2">
                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label class="control-label" for="telefono_2">Telefono 2</label>
                                <div class="controls">
                                    <input type="text" name="telefono_2" value="<?php echo $controlador->row_upd->telefono_2; ?>" class="form-control" id="telefono_2" placeholder="Telefono 2">
                                    <div class="col-sm-12 content_center">
                                        <label id="label_alerta" class="label-error-telefono-2 text-danger">Introduzca un numero de telefono valido </label>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label class="control-label" for="contacto_3">Contacto 3</label>
                                <div class="controls">
                                    <input type="text" name="contacto_3" value="<?php echo $controlador->row_upd->contacto_3; ?>" class="form-control" id="contacto_3" placeholder="Contacto 3">
                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label class="control-label" for="telefono_3">Telefono 3</label>
                                <div class="controls">
                                    <input type="text" name="telefono_3" value="<?php echo $controlador->row_upd->telefono_3; ?>" class="form-control" id="telefono_3" placeholder="Telefono 3">
                                    <div class="col-sm-12 content_center">
                                        <label id="label_alerta" class="label-error-telefono-3 text-danger">Introduzca un numero de telefono valido </label>
                                    </div>
                                </div>
                            </div>
                            <?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
                        </form>
                    </div>

                </div>
            </div><!-- /.center-content -->
        </div>
    </div>
</div>
