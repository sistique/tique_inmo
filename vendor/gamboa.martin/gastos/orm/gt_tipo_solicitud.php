<?php
namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent_sin_codigo;
use PDO;


class gt_tipo_solicitud extends _modelo_parent_sin_codigo {
    public function __construct(PDO $link){
        $tabla = 'gt_tipo_solicitud';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        $no_duplicados = array();


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }
}