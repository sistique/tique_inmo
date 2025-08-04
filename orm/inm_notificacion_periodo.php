<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use PDO;


class inm_notificacion_periodo extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_notificacion_periodo';
        $columnas = array($tabla=>false, 'not_mensaje'=>$tabla, 'inm_periodo_asistencia'=>$tabla);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Notificacion Periodo';
    }


}