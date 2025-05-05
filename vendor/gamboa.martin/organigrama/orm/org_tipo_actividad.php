<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;

use PDO;


class org_tipo_actividad extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_tipo_actividad';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['descripcion'] = array('type' => 'inputs');

        $columnas_extra = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view, columnas_extra: $columnas_extra);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Actividad';
    }




}