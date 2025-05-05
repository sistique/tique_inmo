<?php
namespace gamboamartin\notificaciones\models;

use base\orm\_modelo_parent;

use base\orm\modelo;
use PDO;

class not_tipo_medio extends _modelo_parent {

    private modelo $modelo_etapa;
    public function __construct(PDO $link){
        $tabla = 'not_tipo_medio';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        $no_duplicados = array();

        $campos_view = array();
        $columnas_extra = array();



        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view, columnas_extra: $columnas_extra,
            no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipos de Medios';

    }




}