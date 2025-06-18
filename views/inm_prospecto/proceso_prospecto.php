<?php /** @var  gamboamartin\inmuebles\controllers\controlador_inm_prospecto_prospecto $controlador  controlador en ejecucion */ ?>
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
                            <li id="pestanageneral1"><a href='javascript:cambiarPestannaGeneral(pestanasgeneral,pestanageneral1,pestanasprospecto);'>PROSPECTO</a></li>
                        </ul>
                    </div>
                    <body onload="javascript:cambiarPestannaGeneral_inicial(pestanasgeneral);
                    javascript:valor_inicial();
                    javascript:cambiarPestanna_inicialprospecto(pestanasprospecto);">
                    <div id="contenidopestanasgeneral">
                        <div class="contengeneral" id="cpestanageneral1">
                            <div id="pestanasprospecto">
                                <ul id="listaprospecto">
                                    <li id="pestanaprospecto1"><a href='javascript:cambiarPestanna(pestanasprospecto,pestanaprospecto1);'>MODIFICA</a></li>
                                    <li id="pestanaprospecto2"><a href='javascript:cambiarPestanna(pestanasprospecto,pestanaprospecto2);'>DOCUMENTOS</a></li>
                                    <li id="pestanaprospecto3"><a href='javascript:cambiarPestanna(pestanasprospecto,pestanaprospecto3);'>INTEGRA RELACION</a></li>
                                    <li id="pestanaprospecto4"><a href='javascript:cambiarPestanna(pestanasprospecto,pestanaprospecto4);'>ETAPA MANUAL</a></li>
                                </ul>
                            </div>
                            <div id="contenidopestanasprospecto">
                                <div class="conten" id="cpestanaprospecto1">
                                </div>
                                <div class="conten" id="cpestanaprospecto2">

                                </div>
                                <div class="conten" id="cpestanaprospecto3">

                                </div>
                                <div class="conten" id="cpestanaprospecto4">

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













