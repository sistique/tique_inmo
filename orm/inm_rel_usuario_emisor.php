<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_rel_usuario_emisor extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_rel_usuario_emisor';
        $columnas = array($tabla=>false,'adm_usuario'=>$tabla,'not_emisor'=>$tabla);

        $campos_obligatorios = array('adm_usuario_id','not_emisor_id');

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array('adm_usuario_id','not_emisor_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Relacion Usuario Emisor';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $keys = array('adm_usuario_id','not_emisor_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        if(!isset($this->registro['descripcion'])){
            $this->registro['descripcion'] = $this->registro['adm_usuario_id'].' '.
                $this->registro['not_emisor_id'];
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar',data:  $r_alta_bd);
        }

        return $r_alta_bd;
    }

}
