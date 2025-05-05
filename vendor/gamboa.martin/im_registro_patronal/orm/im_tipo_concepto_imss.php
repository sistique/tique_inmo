<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;
use PDO;

class im_tipo_concepto_imss extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_tipo_concepto_imss";
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}