<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent;

use PDO;


class cat_sat_tipo_contrato_nom  extends _modelo_parent{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_tipo_contrato_nom';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigo'] = 'cod_int_0_2_numbers';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, tipo_campos: $tipo_campos);


        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Contrato NOM';
    }



}