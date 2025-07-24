<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;


class inm_politica_asistencia extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_politica_asistencia';
        $columnas = array($tabla=>false,'inm_status_asistencia'=>$tabla);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Status Comprador';
    }


}