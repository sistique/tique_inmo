<?php
namespace gamboamartin\proceso\models;
use base\orm\modelo;
use PDO;

class pr_tipo_proceso extends modelo{
    public function __construct(PDO $link){
        $tabla = 'pr_tipo_proceso';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }
}