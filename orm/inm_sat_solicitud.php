<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;

/**
 * Modelo para almacenar solicitudes de descarga masiva del SAT
 */
class inm_sat_solicitud extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_sat_solicitud';

        $columnas = array(
            $tabla        => false,
            'inm_sat_cer' => $tabla,
        );

        $campos_obligatorios = array(
            'tipo_solicitud',
            'fecha_inicio_periodo',
            'fecha_fin_periodo',
            'inm_sat_cer_id',
        );

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
        $this->etiqueta = 'Solicitud Descarga Masiva SAT';
    }
}

