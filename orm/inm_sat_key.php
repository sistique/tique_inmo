<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;

/**
 * Modelo para almacenar llaves privadas .key de la e.firma / FIEL del SAT
 */
class inm_sat_key extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_sat_key';

        $columnas = array(
            $tabla        => false,
            'inm_sat_cer' => $tabla,
        );

        $campos_obligatorios = array('ruta_llave', 'inm_sat_cer_id');

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
        $this->etiqueta = 'Llave Privada SAT (.key)';
    }
}

