<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use gamboamartin\gastos\models\gt_proveedor;
use PDO;


class inm_escritura extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_escritura';
        $columnas = array($tabla=>false,'inm_comprador'=>$tabla);

        $campos_obligatorios = array();

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Escritura';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|\stdClass
    {

        $filtro_doc['inm_comprador.id'] =  $this->registro['inm_comprador_id'];
        $filtro_doc['doc_tipo_documento.id'] = 46;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar datos', data: $existe);

        }

        if(!$existe) {
            if(trim($_FILES['validacion_poder']['name']) !== '') {
                $_FILES['documento'] = $_FILES['validacion_poder'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro['inm_comprador_id'];
                $registro['doc_tipo_documento_id'] = 46;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] =  $this->registro['inm_comprador_id'];
        $filtro_doc['doc_tipo_documento.id'] = 47;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar datos', data: $existe);

        }

        if(!$existe) {
            if(trim($_FILES['acuse_patron']['name']) !== '') {
                $_FILES['documento'] = $_FILES['acuse_patron'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro['inm_comprador_id'];
                $registro['doc_tipo_documento_id'] = 47;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] =  $this->registro['inm_comprador_id'];
        $filtro_doc['doc_tipo_documento.id'] = 37;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar datos', data: $existe);

        }

        if(!$existe) {
            if(trim($_FILES['escritura']['name']) !== '') {
                $_FILES['documento'] = $_FILES['escritura'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro['inm_comprador_id'];
                $registro['doc_tipo_documento_id'] = 37;
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

        $filtro['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $resultado = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe', data: $resultado);
        }

        if($resultado->n_registros > 0){
            $registro_puro = $this->registro(registro_id: $resultado->registros[0]['inm_escritura_id'],
                columnas_en_bruto: true,retorno_obj: true);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener relacion', data: $registro_puro);
            }

            return $this->data_result_transaccion(
                mensaje: "El cliente ya tiene asignado un escritura",
                registro: $resultado->registros[0],
                registro_ejecutado: $this->registro,
                registro_id: $resultado->registros[0]['inm_escritura_id'],
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
        $filtro_exi['inm_status_comprador.id'] = 8;
        $existe = (new inm_bitacora_status_comprador(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos de bitacora', data: $existe);
        }

        if(!$existe) {
            $registro_alta = array();
            $registro_alta['inm_comprador_id'] = $this->registro['inm_comprador_id'];
            $registro_alta['inm_status_comprador_id'] = 8;
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