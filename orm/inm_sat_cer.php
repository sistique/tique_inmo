<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;

/**
 * Modelo para almacenar certificados .cer de la e.firma / FIEL del SAT
 */
class inm_sat_cer extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_sat_cer';

        $columnas = array($tabla => false);

        $campos_obligatorios = array('rfc', 'ruta_certificado');

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
        $this->etiqueta = 'Certificado SAT (.cer)';
    }
}

