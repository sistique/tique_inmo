<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent;

use PDO;


class cat_sat_tipo_nomina extends _modelo_parent{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_tipo_nomina';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigo'] = 'cod_1_letras_mayusc';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Nomina';
    }


}