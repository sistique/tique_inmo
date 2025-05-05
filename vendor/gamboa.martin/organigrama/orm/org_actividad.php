<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;
use PDO;

class org_actividad extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_actividad';
        $columnas = array($tabla=>false,'org_tipo_actividad'=>$tabla);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Actividad';
    }
}