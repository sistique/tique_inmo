<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent_sin_codigo;
use PDO;

class cat_sat_tipo_persona extends _modelo_parent_sin_codigo {
    public function __construct(PDO $link){
        $tabla = 'cat_sat_tipo_persona';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->etiqueta = 'Tipo Persona';
    }


}