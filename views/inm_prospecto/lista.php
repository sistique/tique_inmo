<?php /** @var  \gamboamartin\facturacion\controllers\controlador_inm_prospecto $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php include "init.php"; ?>

<?php
echo "<style>
.filtros-avanzados {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 15px;
}

.filtro-grupo {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

.filtro-grupo label {
    font-weight: bold;
    margin-right: 5px;
}

.filtro-grupo input {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
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

        <div class="filtros-avanzados">
            <div class="filtro-grupo">
                <label for="fecha_inicio">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" data-ajax="rango-fechas" data-filtro_campo="inm_prospecto.fecha_alta"
                       data-filtro_key="campo1">

                <label for="fecha_fin">Fecha Fin</label>
                <input type="date" id="fecha_fin" data-ajax="rango-fechas" data-filtro_campo="inm_prospecto.fecha_alta"
                       data-filtro_key="campo2">
            </div>

            <div class="filtro-grupo">
                <label for="rfc">RFC</label>
                <input type="text" id="rfc" data-ajax="filtro" data-filtro_campo="inm_prospecto.rfc"
                       placeholder="Ej: ABCD123456XYZ">

                <label for="agente">Agente</label>
                <input type="text" id="agente" data-ajax="filtro" data-filtro_campo="com_agente.descripcion"
                       placeholder="Ej: JUAN PEREZ">

            </div>

            <button id="filtrar">Filtrar</button>
            <button id="limpiar">Limpiar</button>
            <form method="post" action="<?php echo $controlador->link_exportar_xls; ?>" enctype="multipart/form-data">
                <button id="descargar_excel">Descargar Excel</button>
            </form>

        </div>

        <table class="datatable table table-striped"></table>
    </div><!-- /. widget-table-->
</div><!-- /.center-content -->