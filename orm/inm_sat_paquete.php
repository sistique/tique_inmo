<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;

/**
 * Modelo para almacenar los paquetes de una descarga masiva del SAT
 */
class inm_sat_paquete extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_sat_paquete';

        $columnas = array(
            $tabla               => false,
            'inm_sat_solicitud'  => $tabla,
        );

        $campos_obligatorios = array('id_paquete', 'inm_sat_solicitud_id');

        $columnas_extra = array();
        $renombres = array();
        $atributos_criticos = array();

        parent::__construct(
            link: $link,
            tabla: $tabla,
            campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,
            columnas_extra: $columnas_extra,
            renombres: $renombres,
            atributos_criticos: $atributos_criticos
        );

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Paquete Descarga Masiva SAT';
    }
}

