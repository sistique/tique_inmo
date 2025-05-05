<?php
namespace gamboamartin\organigrama\models;
use base\orm\modelo;
use PDO;

class org_representante_legal extends modelo{
    public function __construct(PDO $link){
        $tabla = 'org_representante_legal';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Representante legal';
    }
}