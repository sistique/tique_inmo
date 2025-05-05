<?php
namespace gamboamartin\cat_sat\models;
use base\orm\modelo;
use gamboamartin\direccion_postal\models\dp_estado;
use PDO;

class cat_sat_isn extends modelo{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_isn';
        $columnas = array($tabla=>false, 'dp_estado' => $tabla);
        $campos_obligatorios[] = 'descripcion';

        $campos_view = array();
        $campos_view['dp_estado_id']['type'] = 'selects';
        $campos_view['dp_estado_id']['model'] = (new dp_estado($link));

        $campos_view['porcentaje']['type'] = 'inputs';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'ISN';
    }
}