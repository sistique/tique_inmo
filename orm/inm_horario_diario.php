<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;


class inm_horario_diario extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_horario_diario';
        $columnas = array($tabla=>false,'inm_horario'=>$tabla,'inm_dia_semana'=>$tabla);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Status Comprador';
    }


}