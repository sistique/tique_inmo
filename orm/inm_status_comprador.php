<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;


class inm_status_comprador extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_status_comprador';
        $columnas = array($tabla=>false);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Status Comprador';
    }


}