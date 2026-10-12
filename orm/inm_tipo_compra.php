<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class inm_tipo_compra extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_tipo_compra';
        $columnas = array($tabla => false);

        $campos_obligatorios = array();

        $columnas_extra = array();
        $renombres = array();

        $atributos_criticos = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Tipos de Compra';
    }

    public function get_tipo_compra_id(string $nombre_tipo_compra): array|stdClass|null|int
    {
        if ($nombre_tipo_compra === '') {
            return null;
        }

        $filtro_especial = array();
        $filtro_especial[0]['inm_tipo_compra.descripcion']['operador'] = 'LIKE';
        $filtro_especial[0]['inm_tipo_compra.descripcion']['valor'] = '%' . $nombre_tipo_compra . '%';
        $filtro_especial[0]['inm_tipo_compra.descripcion']['comparacion'] = 'AND';

        $r_tipo_compra = $this->filtro_and(filtro_especial: $filtro_especial);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tipo_compra',data:  $r_tipo_compra);
        }

        if(count($r_tipo_compra->registros) === 0){
            return null;
        }

        return $r_tipo_compra->registros[0]['inm_tipo_compra_id'];
    }
}

