<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent;
use PDO;

class cat_sat_motivo_cancelacion extends _modelo_parent{

    public function __construct(PDO $link){

        $tabla = 'cat_sat_motivo_cancelacion';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Motivo de Cancelacion';
        $this->id_code = true;

    }
}