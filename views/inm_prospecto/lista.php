<?php /** @var  \gamboamartin\facturacion\controllers\controlador_inm_prospecto $controlador controlador en ejecucion */ ?>
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
                    <label>Status Prospecto</label>
                    <select class="form-control basic-multiple" id="inm_status_prospecto" name="inm_status_prospecto[]"
                            data-tipo="in" data-filtro_campo="inm_status_prospecto.descripcion" multiple
                            data-placeholder="Selecciona una Opcion">
                        <?php
                            foreach ($controlador->status_prospecto AS $status){
                                echo '<option value="'.$status['inm_status_prospecto_descripcion'].'">'.$status['inm_status_prospecto_descripcion'].'</option>';
                            }
                        ?>
                    </select>
                </div>

                <div class="filtro-grupo col-md-12">
                    <div class="col-md-4">
                        <label for="Nombre Prospecto">Nombre Prospecto</label>
                        <input type="text" id="nombre_prospecto" data-tipo="filtro" data-filtro_campo="inm_prospecto.razon_social"
                               placeholder="Ej: JUAN PEREZ">
                    </div>

                    <div class="col-md-4">
                        <label for="nss">NSS</label>
                        <input type="text" id="nss" data-tipo="filtro" data-filtro_campo="inm_prospecto.nss"
                               placeholder="Ej: 9999999999">
                    </div>
                    <div class="col-md-4">
                        <label for="agente">Agente</label>
                        <input type="text" id="agente" data-tipo="filtro" data-filtro_campo="com_agente.descripcion"
                               placeholder="Ej: JUAN PEREZ">
                    </div>
                </div>

                <div class="filtro-grupo col-md-12">
                    <div class="col-md-3">
                        <label for="fecha_inicio">Fecha Alta Inicio</label>
                        <input type="date" id="fecha_inicio" data-tipo="rango-fechas" data-filtro_campo="inm_prospecto.fecha_alta"
                               data-filtro_key="campo1">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin">Fecha Alta Fin</label>
                        <input type="date" id="fecha_fin" data-tipo="rango-fechas" data-filtro_campo="inm_prospecto.fecha_alta"
                               data-filtro_key="campo2">
                    </div>
                </div>
            </div>
            <div class="filtro-grupo col-md-12">
                <button id="filtrar">Filtrar</button>
                <button id="limpiar">Limpiar</button>
                <form method="post" action="<?php echo $controlador->link_exportar_xls; ?>" enctype="multipart/form-data">
                    <input type="hidden" name="inm_status_prospecto" id="hidden_inm_status_prospecto">
                    <input type="hidden" name="nombre_prospecto" id="hidden_nombre_prospecto">
                    <input type="hidden" name="nss" id="hidden_nss">
                    <input type="hidden" name="agente" id="hidden_agente">
                    <input type="hidden" name="fecha_inicial" id="hidden_fecha_inicio">
                    <input type="hidden" name="fecha_final" id="hidden_fecha_fin">
                    <button id="descargar_excel">Descargar Excel</button>
                </form>
            </div>
        </div>
        <table class="datatable table table-striped"></table>
    </div><!-- /. widget-table-->
</div><!-- /.center-content -->