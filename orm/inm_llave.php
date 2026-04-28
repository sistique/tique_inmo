<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;


class inm_llave extends _modelo_parent {
    public function __construct(PDO $link)
    {
        $tabla = 'inm_llave';
        $columnas = array($tabla => false, 'inm_ubicacion' => $tabla);

        $campos_obligatorios = array('inm_ubicacion_id', 'descripcion');

        $columnas_extra = array();
        $renombres = array();

        $atributos_criticos = array('inm_ubicacion_id', 'descripcion');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Llaves';
    }
}

