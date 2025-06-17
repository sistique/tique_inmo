<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use gamboamartin\gastos\models\gt_proveedor;
use PDO;


class inm_firma extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_firma';
        $columnas = array($tabla=>false,'inm_comprador'=>$tabla);

        $campos_obligatorios = array();

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Firma';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|\stdClass
    {

        $filtro_doc['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $filtro_doc['doc_tipo_documento.id'] = 41;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $existe);
        }
        if(!$existe) {
            if(trim($_FILES['anexos']['name']) !== '') {
                $_FILES['documento'] = $_FILES['anexos'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro['inm_comprador_id'];
                $registro['doc_tipo_documento_id'] = 41;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al validar registro',data:  $r_inm_doc_comprador);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $filtro_doc['doc_tipo_documento.id'] = 42;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $existe);
        }

        if(!$existe) {
            if(trim($_FILES['instruccion_credito']['name']) !== '') {
                $_FILES['documento'] = $_FILES['instruccion_credito'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro['inm_comprador_id'];
                $registro['doc_tipo_documento_id'] = 42;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al validar registro',data:  $r_inm_doc_comprador);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $filtro_doc['doc_tipo_documento.id'] = 43;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $existe);
        }

        if(!$existe) {
            if(trim($_FILES['notificacion_descuento']['name']) !== '') {
                $_FILES['documento'] = $_FILES['notificacion_descuento'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro['inm_comprador_id'];
                $registro['doc_tipo_documento_id'] = 43;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al validar registro',data:  $r_inm_doc_comprador);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $filtro_doc['doc_tipo_documento.id'] = 44;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $existe);
        }

        if(!$existe) {
            if(trim($_FILES['isr_notaria']['name']) !== '') {
                $_FILES['documento'] = $_FILES['isr_notaria'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro['inm_comprador_id'];
                $registro['doc_tipo_documento_id'] = 44;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al validar registro',data:  $r_inm_doc_comprador);
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

        $filtro['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $resultado = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe', data: $resultado);
        }

        if($resultado->n_registros > 0){
            $registro_puro = $this->registro(registro_id: $resultado->registros[0]['inm_firma_id'],
                columnas_en_bruto: true,retorno_obj: true);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener relacion', data: $registro_puro);
            }

            return $this->data_result_transaccion(
                mensaje: "El cliente ya tiene asignado un firma",
                registro: $resultado->registros[0],
                registro_ejecutado: $this->registro,
                registro_id: $resultado->registros[0]['inm_firma_id'],
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
        $filtro_exi['inm_status_comprador.id'] = 7;
        $existe = (new inm_bitacora_status_comprador(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos de bitacora', data: $existe);
        }

        if(!$existe) {
            $registro_alta = array();
            $registro_alta['inm_comprador_id'] = $this->registro['inm_comprador_id'];
            $registro_alta['inm_status_comprador_id'] = 7;
            $registro_alta['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_comprador = (new inm_bitacora_status_comprador(link: $this->link))->alta_registro(
                registro: $registro_alta);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_comprador);
            }
        }

        return $r_alta_bd;

    }

}