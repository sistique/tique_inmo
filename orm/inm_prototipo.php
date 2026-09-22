<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_prototipo extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_prototipo';
        $columnas = array($tabla=>false);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Prototipo';
    }

    public function get_prototipo_id(string $nombre_prototipo): array|stdClass|null|int
    {
        if ($nombre_prototipo === '') {
            return null;
        }

        $filtro_especial = array();
        $filtro_especial[0]['inm_prototipo.descripcion']['operador'] = 'LIKE';
        $filtro_especial[0]['inm_prototipo.descripcion']['valor'] = '%' . $nombre_prototipo . '%';
        $filtro_especial[0]['inm_prototipo.descripcion']['comparacion'] = 'AND';

        $r_prototipo = $this->filtro_and(filtro_especial: $filtro_especial);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener prototipo',data:  $r_prototipo);
        }

        if(count($r_prototipo->registros) === 0){
            return null;
        }

        return $r_prototipo->registros[0]['inm_prototipo_id'];
    }
}