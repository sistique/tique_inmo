<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_estado_vivienda extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_estado_vivienda';
        $columnas = array($tabla=>false);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Estado Vivienda';
    }

    public function get_estado_vivienda_id(string $nombre_estado_vivienda): array|stdClass|null|int
    {
        if ($nombre_estado_vivienda === '') {
            return null;
        }

        $filtro_especial = array();
        $filtro_especial[0]['inm_estado_vivienda.descripcion']['operador'] = 'LIKE';
        $filtro_especial[0]['inm_estado_vivienda.descripcion']['valor'] = '%' . $nombre_estado_vivienda . '%';
        $filtro_especial[0]['inm_estado_vivienda.descripcion']['comparacion'] = 'AND';

        $r_estado_vivienda = $this->filtro_and(filtro_especial: $filtro_especial);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener estado_vivienda',data:  $r_estado_vivienda);
        }

        if(count($r_estado_vivienda->registros) === 0){
            return null;
        }

        return $r_estado_vivienda->registros[0]['inm_estado_vivienda_id'];
    }
}