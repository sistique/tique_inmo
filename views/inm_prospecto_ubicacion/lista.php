<?php /** @var  \gamboamartin\facturacion\controllers\controlador_inm_comprador $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php include "init.php"; ?>

<?php
echo "<style>
.contenedor_completo{
    display: flex;
    flex-wrap: wrap;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 15px;
}

.filtro-grupo {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin-top: 15px;
}

.filtro-grupo label {
    font-weight: bold;
    margin-right: 5px;
}

.filtro-grupo input {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
}

#filtrar {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

#filtrar:hover {
    background: #0056b3;
}
#limpiar {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
    margin-left: 10px;
}

#limpiar:hover {
    background: #a71d2a;
}

#limpiar:disabled {
    background: #cccccc;
    color: #666666;
    cursor: not-allowed;
    border: none;
}

#descargar_excel {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
    margin-left: 10px;
}

#descargar_excel:hover {
    background: #218838;
}

</style>";
?>

<div class="col-md-12">
    <?php if ($controlador->include_breadcrumb !== '') {
        include $controlador->include_breadcrumb;
    } ?>
    <?php include (new views())->ruta_templates . "mensajes.php"; ?>
    <div class="widget widget-box box-container widget-mylistings">
        <?php //include (new views())->ruta_templates . 'etiquetas/_titulo_lista.php'; ?>

        <div class="contenedor_completo">
            <div class="filtros-avanzados">
                <div class="filtro-grupo col-md-12">
                    <label>Status Prospecto Ubicacion</label>
                    <select class="form-control basic-multiple" id="inm_status_prospecto_ubicacion" name="inm_status_prospecto_ubicacion[]"
                            data-tipo="in" data-filtro_campo="inm_status_prospecto_ubicacion.descripcion" multiple
                            data-placeholder="Selecciona una Opcion">
                        <?php
                            foreach ($controlador->status_prospecto_ubicacion AS $status){
                                echo '<option value="'.$status['inm_status_prospecto_ubicacion_descripcion'].'">'.$status['inm_status_prospecto_ubicacion_descripcion'].'</option>';
                            }
                        ?>
                    </select>
                </div>

                <div class="filtro-grupo col-md-12">
                    <div class="col-md-4">
                        <label for="Ubicacion">Ubicacion</label>
                        <input type="text" id="ubicacion" data-tipo="filtro" data-filtro_campo="<?php echo $controlador->modelo->columnas_extra['inm_prospecto_ubicacion_ubicacion']?>"
                               placeholder="Ej: AV. VALLARTA 220 ">
                    </div>

                    <div class="col-md-4">
                        <label for="Nombre prospecto_ubicacion">Nombre Prospecto</label>
                        <input type="text" id="nombre_prospecto_ubicacion" data-tipo="filtro" data-filtro_campo="inm_prospecto_ubicacion.razon_social"
                               placeholder="Ej: JUAN PEREZ">
                    </div>


                    <div class="col-md-4">
                        <label for="agente">Agente</label>
                        <input type="text" id="agente" data-tipo="filtro" data-filtro_campo="com_agente.descripcion"
                               placeholder="Ej: JUAN PEREZ">
                    </div>

                    <div class="col-md-4">
                        <label for="nss">NSS</label>
                        <input type="text" id="nss" data-tipo="filtro" data-filtro_campo="inm_prospecto_ubicacion.nss"
                               placeholder="Ej: 9999999999">
                    </div>
                </div>
            </div>
            <div class="filtro-grupo col-md-12">
                <button id="filtrar">Filtrar</button>
                <button id="limpiar">Limpiar</button>
                <form method="post" action="<?php echo $controlador->link_exportar_xls; ?>" enctype="multipart/form-data">
                    <input type="hidden" name="inm_status_prospecto_ubicacion" id="hidden_inm_status_prospecto_ubicacion">
                    <input type="hidden" name="nombre_prospecto_ubicacion" id="hidden_nombre_prospecto_ubicacion">
                    <input type="hidden" name="ubicacion" id="hidden_ubicacion">
                    <input type="hidden" name="agente" id="hidden_agente">
                    <input type="hidden" name="nss" id="hidden_nss">
                    <button id="descargar_excel">Descargar Excel</button>
                </form>
            </div>
        </div>
        <table class="datatable table table-striped"></table>
    </div><!-- /. widget-table-->
</div><!-- /.center-content -->