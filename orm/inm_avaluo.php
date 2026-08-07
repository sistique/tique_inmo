<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use gamboamartin\gastos\models\gt_proveedor;
use PDO;


class inm_avaluo extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_avaluo';
        $columnas = array($tabla=>false,'inm_comprador'=>$tabla);

        $campos_obligatorios = array();

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Avaluo';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|\stdClass
    {
        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->existe_status_comprador(
            inm_comprador_id: $this->registro['inm_comprador_id'], values: array('11'));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener bitacora status comp',data:  $inm_bit_comp);
        }

        if ($inm_bit_comp->n_registros > 0) {
            return $this->error->error(mensaje: 'Error el cliente ya esta cancelado',data:  $inm_bit_comp);
        }

        $filtro_rel_ubi['inm_rel_ubi_comp.status'] = 'activo';
        $filtro_rel_ubi['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $r_inm_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->filtro_and(filtro: $filtro_rel_ubi);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inputs_hidden',data:  $r_inm_rel_ubi_comp);
        }

        if($r_inm_rel_ubi_comp->n_registros <= 0){
            return $this->error->error(mensaje: 'Error no existe relacion ubicacion',data:  $r_inm_rel_ubi_comp);
        }

        $filtro_doc = array();
        $filtro_doc['inm_ubicacion.id'] = $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_id'];
        $filtro_doc['doc_tipo_documento.id'] = 35;
        $r_inm_doc_ubicacion_reg = (new inm_doc_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_doc);
        if (errores::$error) {
            return  $this->error->error(mensaje: 'Error al obtener datos de bitacora', data: $r_inm_doc_ubicacion_reg);
        }

        if($r_inm_doc_ubicacion_reg->n_registros <= 0) {
            if(trim($_FILES['poder']['name']) !== '') {
                $_FILES['documento'] = $_FILES['poder'];
                $registro = array();
                $registro['inm_ubicacion_id'] = $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_id'];
                $registro['doc_tipo_documento_id'] = 35;
                $r_inm_doc_ubicacion = (new inm_doc_ubicacion(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar datos', data: $r_inm_doc_ubicacion);
                }
            }else{
                return $this->error->error(mensaje: 'Error no existe documento Escritura Poder',
                    data: $r_inm_doc_ubicacion_reg);
            }
        }

        $filtro_doc = array();
        $filtro_doc['inm_comprador.id'] =  $this->registro['inm_comprador_id'];
        $filtro_doc['doc_tipo_documento.id'] = 38;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar datos', data: $existe);
        }

        if(!$existe) {
            if(trim($_FILES['avaluo']['name']) !== '') {
                $_FILES['documento'] = $_FILES['avaluo'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro['inm_comprador_id'];
                $registro['doc_tipo_documento_id'] = 38;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador);
                }
            }
        }

        $keys = array('inm_comprador_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        if(!isset($this->registro['descripcion'])){
            $descripcion = $this->registro['inm_comprador_id'];
            $this->registro['descripcion'] = $descripcion;
        }

        if(!isset($this->registro['costo_avaluo'])){
            $this->registro['costo_avaluo'] = 0;
        }

        if(!isset($this->registro['fecha_solicitud'])){
            $this->registro['fecha_solicitud'] = date('Y-m-d');
        }

        $filtro['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $resultado = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe', data: $resultado);
        }

        if($resultado->n_registros > 0){
            $registro_puro = $this->registro(registro_id: $resultado->registros[0]['inm_avaluo_id'],
                columnas_en_bruto: true,retorno_obj: true);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener relacion', data: $registro_puro);
            }

            return $this->data_result_transaccion(
                mensaje: "El cliente ya tiene asignado un avaluo",
                registro: $resultado->registros[0],
                registro_ejecutado: $this->registro,
                registro_id: $resultado->registros[0]['inm_avaluo_id'],
                registro_original: $resultado->registros[0],
                registro_puro: $registro_puro,
                sql: 'Registro existente'
            );
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar',data:  $r_alta_bd);
        }

        $filtro_exi['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $filtro_exi['inm_status_comprador.id'] = 4;
        $existe = (new inm_bitacora_status_comprador(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos de bitacora', data: $existe);
        }

        if(!$existe) {
            $registro_alta = array();
            $registro_alta['inm_comprador_id'] = $this->registro['inm_comprador_id'];
            $registro_alta['inm_status_comprador_id'] = 4;
            $registro_alta['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_comprador = (new inm_bitacora_status_comprador(link: $this->link))->alta_registro(
                registro: $registro_alta);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_comprador);
            }
        }

        return $r_alta_bd;

    }

    final public function inm_avaluos(int $inm_comprador_id): array
    {
        if($inm_comprador_id<=0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0',data:  $inm_comprador_id);
        }

        $filtro = array();
        $filtro['inm_comprador.id'] = $inm_comprador_id;

        $r_inm_avaluo = (new inm_avaluo(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener r_inm_avaluo',data:  $r_inm_avaluo);
        }

        if($r_inm_avaluo->n_registros === 0){
            return $this->error->error(
                mensaje: 'Error no existe inm_avaluo',data:  $r_inm_avaluo);
        }

        return $r_inm_avaluo->registros[0];
    }

}