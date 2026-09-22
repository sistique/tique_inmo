<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_tipo_credito extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_tipo_credito';
        $columnas = array($tabla=>false);

        $campos_obligatorios = array('x','y');

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array('x','y');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Tipo de Credito';
    }

    public function get_tipo_credito_id(string $nombre_tipo_credito): array|stdClass|null|int
    {
        if ($nombre_tipo_credito === '') {
            return null;
        }

        $filtro_especial = array();
        $filtro_especial[0]['inm_tipo_credito.descripcion']['operador'] = 'LIKE';
        $filtro_especial[0]['inm_tipo_credito.descripcion']['valor'] = '%' . $nombre_tipo_credito . '%';
        $filtro_especial[0]['inm_tipo_credito.descripcion']['comparacion'] = 'AND';

        $r_tipo_credito = $this->filtro_and(filtro_especial: $filtro_especial);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tipo_credito',data:  $r_tipo_credito);
        }

        if(count($r_tipo_credito->registros) === 0){
            return null;
        }

        return $r_tipo_credito->registros[0]['inm_tipo_credito_id'];
    }
}