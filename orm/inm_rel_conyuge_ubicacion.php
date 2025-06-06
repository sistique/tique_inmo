<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_rel_conyuge_ubicacion extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_rel_conyuge_ubicacion';
        $columnas = array($tabla=>false,'inm_ubicacion'=>$tabla,'inm_conyuge'=>$tabla);

        $campos_obligatorios = array('inm_ubicacion_id','inm_conyuge_id');

        $columnas_extra= array();

        $renombres = array();

        $atributos_criticos = array('inm_ubicacion_id','inm_conyuge_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Relacion Prospecto Ubicacion Conyuge';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {

        $keys = array('inm_conyuge_id','inm_ubicacion_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        if(!isset($this->registro['descripcion'])){
            $descripcion = $this->registro['inm_conyuge_id'];
            $descripcion .= ' '.$this->registro['inm_ubicacion_id'];
            $this->registro['descripcion'] = $descripcion;
        }

        $filtro['inm_conyuge.id'] = $this->registro['inm_conyuge_id'];
        $filtro['inm_ubicacion.id'] = $this->registro['inm_ubicacion_id'];

        $existe = $this->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe',data:  $existe);
        }
        if(!$existe) {
            $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar', data: $r_alta_bd);
            }
        }
        else{

            $data = $this->inm_rel_conyuge_ubicacion_filtro(filtro: $filtro);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener relacion', data: $data);
            }
            

            $r_alta_bd = $this->data_result_transaccion(mensaje: 'Registro insertado con éxito', registro: $data->registro,
                registro_ejecutado: $this->registro, registro_id: $data->r_registro->registros[0]['inm_rel_co_acred_id'],
                registro_original: $this->registro, registro_puro: $data->registro_puro,
                sql: 'Registro existente');
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al maquetar respuesta registro', data: $r_alta_bd);
            }

        }



        return $r_alta_bd;

    }

    private function inm_rel_conyuge_ubicacion_filtro(array $filtro){
        $r_registro = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener relacion', data: $r_registro);
        }

        $registro_puro = $this->registro(registro_id: $r_registro->registros[0]['inm_rel_conyuge_ubicacion_id'],
            columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener relacion', data: $registro_puro);
        }

        $registro = $r_registro->registros[0];

        $data = new stdClass();
        $data->r_registro = $r_registro;
        $data->registro_puro = $registro_puro;
        $data->registro = $registro;
        return $data;
    }


}