<?php /** @var  \gamboamartin\facturacion\controllers\controlador_inm_comprador $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php include "init.php"; ?>

<?php
echo "<style>
.top-title {
    display: flex;
    align-items: center;
    justify-content: flex-start; /* 👈 clave */
    gap: 10px; /* espacio entre botones y título */
}

.top-title .breadcrumb {
    margin: 0;
}

.top-title h1 {
    margin: 0 0 0 20px;
}

.contenedor_completo{
    display: flex;
    flex-wrap: wrap;
    padding: 0 0 15px 0;
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

.split-view {
    display: flex;
    width: 100%;
    align-items: stretch;
}

.split-view .split-panel {
    min-width: 0;
    /*overflow: auto;*/
}

#panel-compradores {
    flex: 1 1 100%;
}

#split-divisor {
    display: none;
    flex: 0 0 6px;
    margin: 0 4px;
    background: #d9dde2;
    border-radius: 3px;
    cursor: col-resize;
    user-select: none;
}

#split-divisor:hover, #split-divisor.arrastrando {
    background: #007bff;
}

.split-view.con-ubicaciones #split-divisor {
    display: block;
}

.split-view.con-ubicaciones #panel-compradores,
.split-view.con-ubicaciones #panel-ubicaciones {
    flex: 0 0 auto;
}

#panel-ubicaciones {
    display: none;
}

.split-view.con-ubicaciones #panel-ubicaciones {
    display: block;
}

#panel-compradores.panel-estrecho .dropdown {
    justify-content: center;
    display: flex;
}

#panel-compradores.panel-estrecho table tbody tr td .dropdown-menu {
    right: auto !important;
    transform: translateX(-30px);
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
                    <div class="filtros col-md-1">
                        <label for="id">ID</label>
                        <input type="text" id="id" data-tipo="data" data-filtro_campo="inm_comprador.id" placeholder="Ej: 1 ">
                    </div>

                    <div class="col-md-2">
                        <label for="Nombre comprador">Nombre Comprador</label>
                        <input type="text" id="nombre_comprador" data-tipo="filtro" data-filtro_campo="<?php echo $controlador->modelo->columnas_extra['inm_comprador_razon_social']?>"
                               placeholder="Ej: JUAN PEREZ">
                    </div>

                    <div class="col-md-3">
                        <label for="Ubicacion">Ubicacion</label>
                        <input type="text" id="ubicacion" data-tipo="filtro" data-filtro_campo="<?php echo $controlador->modelo->columnas_extra['inm_ubicacion_completa']?>"
                               placeholder="Ej: AV. VALLARTA 220 ">
                    </div>
                    <div class="col-md-2">
                        <label for="agente">Prospectador</label>
                        <input type="text" id="agente" data-tipo="filtro" data-filtro_campo="com_agente.descripcion" placeholder="Ej: JUAN PEREZ">
                    </div>

                    <div class='col-md-2'>
                        <label for='agente_cerrador'>Cerrador</label>
                        <input type='text' id='agente_cerrador' data-tipo='filtro' data-filtro_campo='<?php echo $controlador->modelo->columnas_extra['com_agente_cerrador_descripcion']; ?>' placeholder='Ej: JUAN PEREZ'>
                    </div>

                    <div class="col-md-2">
                        <label for="nss">NSS</label>
                        <input type="text" id="nss" data-tipo="filtro" data-filtro_campo="inm_comprador.nss"
                               placeholder="Ej: 9999999999">
                    </div>

                    <div class="col-md-2">
                        <label for="numero_credito">Numero de Credito</label>
                        <input type="text" id="numero_credito" data-tipo="filtro" data-filtro_campo="inm_comprador.numero_credito"
                               placeholder="Ej: 02102">
                    </div>

                    <div class="col-md-2">
                        <label for="fecha_inicio">Fecha Alta Inicio</label>
                        <input type="date" id="fecha_inicio" data-tipo="rango-fechas" data-filtro_campo="inm_comprador.fecha_alta"
                               data-filtro_key="campo1">
                    </div>
                    <div class="col-md-2">
                        <label for="fecha_fin">Fecha Alta Fin</label>
                        <input type="date" id="fecha_fin" data-tipo="rango-fechas" data-filtro_campo="inm_comprador.fecha_alta"
                               data-filtro_key="campo2">
                    </div>

                    <div class="col-md-2">
                        <label>Status Comprador</label>
                        <select class="form-control basic-multiple" id="inm_status_comprador" name="inm_status_comprador[]"
                                data-tipo="in" data-filtro_campo="inm_status_comprador.descripcion" multiple
                                data-placeholder="Selecciona una Opcion">
                            <?php
                                foreach ($controlador->status_comprador AS $status){
                                    echo '<option value="'.$status['inm_status_comprador_descripcion'].'">'.$status['inm_status_comprador_descripcion'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="filtro-grupo col-md-12">
                <button id="filtrar">Filtrar</button>
                <button id="limpiar">Limpiar</button>
                <form method="post" action="<?php echo $controlador->link_exportar_xls; ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="hidden_id">
                    <input type="hidden" name="nombre_comprador" id="hidden_nombre_comprador">
                    <input type="hidden" name="ubicacion" id="hidden_ubicacion">
                    <input type="hidden" name="agente" id="hidden_agente">
                    <input type="hidden" name="agente_cerrador" id="hidden_agente_cerrador">
                    <input type="hidden" name="nss" id="hidden_nss">
                    <input type="hidden" name="numero_credito" id="hidden_numero_credito">
                    <input type="hidden" name="fecha_inicial" id="hidden_fecha_inicio">
                    <input type="hidden" name="fecha_final" id="hidden_fecha_fin">
                    <input type="hidden" name="inm_status_comprador" id="hidden_inm_status_comprador">
                    <button id="descargar_excel">Descargar Excel</button>
                </form>
            </div>
        </div>
        <div class="split-view" id="split-view">
            <div class="split-panel" id="panel-compradores">
                <table class="datatable table table-striped"></table>
            </div>
            <?php if ($controlador->ver_ubicaciones_clientes): ?>
            <div id="split-divisor" title="Arrastrar para redimensionar"></div>
            <div class="split-panel" id="panel-ubicaciones">
                <table id="tabla-ubicaciones" class="table table-striped" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ubicación</th>
                        <th>Valor Avaluo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>