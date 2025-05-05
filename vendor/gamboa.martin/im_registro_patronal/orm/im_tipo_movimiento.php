<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
class im_tipo_movimiento extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_tipo_movimiento";
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function es_alta(int $im_tipo_movimiento_id): bool|array
    {
        $im_tipo_movimiento = $this->registro(registro_id: $im_tipo_movimiento_id, columnas_en_bruto: true, retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tipo de movimiento', data: $im_tipo_movimiento);
        }
        $es_alta = false;
        if($im_tipo_movimiento->es_alta === 'activo'){
            $es_alta = true;
        }
        return $es_alta;

    }
}