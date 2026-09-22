<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_complemento extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_complemento';
        $columnas = array($tabla=>false);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Complemento';
    }

    public function get_complemento_id(string $nombre_complemento): array|stdClass|null|int
    {
        if ($nombre_complemento === '') {
            return null;
        }

        $filtro_especial = array();
        $filtro_especial[0]['inm_complemento.descripcion']['operador'] = 'LIKE';
        $filtro_especial[0]['inm_complemento.descripcion']['valor'] = '%' . $nombre_complemento . '%';
        $filtro_especial[0]['inm_complemento.descripcion']['comparacion'] = 'AND';

        $r_complemento = $this->filtro_and(filtro_especial: $filtro_especial);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener complemento',data:  $r_complemento);
        }

        if(count($r_complemento->registros) === 0){
            return null;
        }

        return $r_complemento->registros[0]['inm_complemento_id'];
    }
}