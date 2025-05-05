<?php
namespace gamboamartin\cat_sat\models;
use base\orm\modelo;
use PDO;

class cat_sat_nom_tipo_contrato extends modelo{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_nom_tipo_contrato';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->etiqueta = 'Tipo de contrato';
    }
}