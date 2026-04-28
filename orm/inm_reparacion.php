<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;


class inm_reparacion extends _modelo_parent {
    public function __construct(PDO $link)
    {
        $tabla = 'inm_reparacion';
        // responsable_id no sigue la convención inm_responsable_id,
        // por lo tanto se omite el auto-JOIN y se gestiona manualmente en el controlador.
        $columnas = array($tabla => false, 'inm_ubicacion' => $tabla);

        $campos_obligatorios = array('inm_ubicacion_id', 'responsable_id', 'descripcion');

        $columnas_extra = array();
        $renombres = array();

        $atributos_criticos = array('inm_ubicacion_id', 'responsable_id', 'descripcion');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Reparaciones';
    }
}

