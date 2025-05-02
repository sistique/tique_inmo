<?php /** @var gamboamartin\system\ $controlador  viene de registros del controler/lista */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if($controlador->include_breadcrumb!==''){
                    include $controlador->include_breadcrumb;
                } ?>
                <?php include (new views())->ruta_templates."mensajes.php"; ?>
                <div class="widget widget-box box-container widget-mylistings">
                    <?php //include (new views())->ruta_templates.'etiquetas/_titulo_lista.php';?>
                    <table class="datatable table table-striped"></table>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>
    </div>
</main>
