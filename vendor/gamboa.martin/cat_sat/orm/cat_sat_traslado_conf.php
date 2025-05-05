<?php
namespace gamboamartin\cat_sat\models;
use PDO;


class cat_sat_traslado_conf extends _impuestos {

    public function __construct(PDO $link){
        $tabla = 'cat_sat_traslado_conf';
        $columnas = array($tabla=>false,'cat_sat_factor'=>$tabla,'cat_sat_tipo_factor'=>$tabla,
            'cat_sat_tipo_impuesto'=>$tabla,'cat_sat_conf_imps'=>$tabla);
        $campos_obligatorios[] = 'descripcion';

        $tipo_campos = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Configuraciones de Traslados';
        $this->id_code = true;

    }


}