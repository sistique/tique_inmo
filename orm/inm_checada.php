<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;


class inm_checada extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_checada';
        $columnas = array($tabla=>false,'inm_empleado'=>$tabla,'inm_status_asistencia'=>$tabla,
            'inm_periodo_asistencia'=>$tabla,'inm_tipo_checada'=>$tabla);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Status Comprador';
    }


}