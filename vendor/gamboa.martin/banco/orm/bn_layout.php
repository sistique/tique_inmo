<?php
namespace gamboamartin\banco\models;
use base\orm\modelo;
use PDO;

class bn_layout extends modelo{

    public function __construct(PDO $link){
        $tabla = 'bn_layout';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        $campos_view = array();
        $campos_view['bn_sucursal_id']['type'] = 'selects';
        $campos_view['bn_sucursal_id']['model'] = (new bn_sucursal($link));

        $campos_view['fecha_pago']['type'] = 'dates';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;
    }
}