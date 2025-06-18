<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_prospecto_ubicacion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div>

        <div class="row">

            <div class="col-lg-12">
                <?php include (new views())->ruta_templates."head/title.php"; ?>
                <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                <?php include (new views())->ruta_templates."mensajes.php"; ?>
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">

                    <div id="pestanasgeneral">
                        <ul id="listageneral">
                            <li id="pestanageneral1"><a href='javascript:cambiarPestannaGeneral(pestanasgeneral,pestanageneral1,pestanasubicacion);'>PROSPECTO UBICACION</a></li>
                        </ul>
                    </div>
                    <body onload="javascript:cambiarPestannaGeneral_inicial(pestanasgeneral);
                    javascript:valor_inicial();
                    javascript:cambiarPestanna_inicialubicacion(pestanasubicacion);">
                    <div id="contenidopestanasgeneral">
                        <div class="contengeneral" id="cpestanageneral1">
                            <div id="pestanasubicacion">
                                <ul id="listaubicacion">
                                    <li id="pestanaubicacion1"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion1);'>MODIFICA</a></li>
                                    <li id="pestanaubicacion2"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion2);'>DOCUMENTOS</a></li>
                                    <li id="pestanaubicacion3"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion3);'>FOTOGRAFIAS</a></li>
                                    <li id="pestanaubicacion4"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion4);'>INTEGRA RELACION</a></li>
                                    <li id="pestanaubicacion5"><a href='javascript:cambiarPestanna(pestanasubicacion,pestanaubicacion5);'>ETAPA MANUAL</a></li>
                                </ul>
                            </div>
                            <div id="contenidopestanasubicacion">
                                <div class="conten" id="cpestanaubicacion1">

                                </div>
                                <div class="conten" id="cpestanaubicacion2">
                                    <div>
                                        <div class="row">
                                            <div class="col-lg-12 table-responsive">
                                                <table id="table-inm_prospecto_ubicacion" class="table mb-0 table-striped table-sm "></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="conten" id="cpestanaubicacion3">

                                </div>
                                <div class="conten" id="cpestanaubicacion4">

                                </div>
                                <div class="conten" id="cpestanaubicacion5">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<dialog id="myModal">
    <span class="close-btn" id="closeModalBtn">&times;</span>
    <h2>Vista Previa</h2>
    <div class="content">
    </div>
</dialog>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <img class="imagen_modal">
    </div>
</div>













