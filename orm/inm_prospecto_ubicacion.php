<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\administrador\models\adm_usuario;
use gamboamartin\comercial\models\com_direccion;
use gamboamartin\comercial\models\com_direccion_prospecto;
use gamboamartin\comercial\models\com_prospecto;
use gamboamartin\comercial\models\com_tipo_direccion;
use gamboamartin\comercial\models\com_tipo_prospecto;
use gamboamartin\errores\errores;
use gamboamartin\proceso\models\pr_sub_proceso;
use gamboamartin\validacion\validacion;
use PDO;
use stdClass;

class inm_prospecto_ubicacion extends _modelo_parent{

    public bool $viene_ubicacion = false;
    public function __construct(PDO $link)
    {
        $tabla = 'inm_prospecto_ubicacion';
        $columnas = array($tabla=>false,'com_prospecto'=>$tabla, 'inm_prototipo' => $tabla,'inm_complemento'=>$tabla,
            'inm_estado_vivienda'=>$tabla,'inm_status_prospecto_ubicacion'=>$tabla,
            'com_agente'=>'com_prospecto','com_tipo_prospecto'=>'com_prospecto',
            'com_medio_prospeccion'=>'com_prospecto', 'adm_usuario'=>'com_agente', 'dp_colonia_postal' => $tabla,
            'dp_cp'=>'dp_colonia_postal','dp_colonia'=>'dp_colonia_postal','dp_municipio'=>'dp_cp',
            'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado');

        $campos_obligatorios = array();

        $columnas_extra= array();

        $sql = "( IFNULL ((SELECT
                    pr_etapa_actual.descripcion 
                    FROM pr_etapa AS pr_etapa_actual 
                    LEFT JOIN com_prospecto_etapa AS com_prospecto_etapa_sel ON  com_prospecto_etapa_sel.com_prospecto_id = com_prospecto.id
                    LEFT JOIN pr_etapa_proceso AS pr_etapa_proceso_sel ON  com_prospecto_etapa_sel.pr_etapa_proceso_id = pr_etapa_proceso_sel.id
                     WHERE  pr_etapa_actual.id = pr_etapa_proceso_sel.pr_etapa_id ORDER BY com_prospecto_etapa_sel.fecha DESC LIMIT 1), -1) )";

        $columnas_extra['pr_etapa_descripcion'] = $sql;

        $sql = "(CONCAT_WS(' ', inm_prospecto_ubicacion.calle, inm_prospecto_ubicacion.numero_exterior, 
        inm_prospecto_ubicacion.numero_interior, dp_colonia.descripcion, dp_municipio.descripcion))";

        $columnas_extra['inm_prospecto_ubicacion_ubicacion'] = $sql;

        if(!isset($_SESSION['usuario_id'])){
            $error = (new errores())->error(mensaje: 'Error $_SESSION[usuario_id] no existe',data:  $_SESSION);
            print_r($error);
            exit;
        }

        $adm_usuario = (new adm_usuario(link: $link))->registro(registro_id: $_SESSION['usuario_id'],
            columnas: array('adm_grupo_root'));
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al obtener adm_usuario ',data:  $adm_usuario);
            print_r($error);
            exit;
        }


        $sql = "( IFNULL ((SELECT
                    adm_usuario_permitido.id 
                    FROM com_agente AS com_agente_permitido 
                    LEFT JOIN adm_usuario AS adm_usuario_permitido ON  com_agente_permitido.adm_usuario_id = adm_usuario_permitido.id
                    LEFT JOIN com_rel_agente ON com_rel_agente.com_agente_id = com_agente_permitido.id 
                    WHERE  adm_usuario_permitido.id = $_SESSION[usuario_id] AND 
                    com_rel_agente.com_prospecto_id = com_prospecto.id),-1) )";


        if($adm_usuario['adm_grupo_root'] === 'activo'){
            $sql = $_SESSION['usuario_id'];
        }

        $columnas_extra['usuario_permitido_id'] = $sql;

        $atributos_criticos = array();

        $tipo_campos= array();

        $renombres = array();

        parent::__construct(link: $link, tabla: $tabla, aplica_seguridad: true,
            campos_obligatorios: $campos_obligatorios, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres, tipo_campos: $tipo_campos, atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Prospecto de Ubicacion';
    }

    /**
     * Actualiza la descripcion basado en campos de upd
     * @param int $id Identificador de prospecto
     * @param array $keys_integra_ds Keys para descripcion select
     * @param bool $reactiva Valida si es correcta una reactivacion
     * @param stdClass $registro Registro en proceso
     * @return array|stdClass
     */
    private function actualiza_descripcion(int $id, array $keys_integra_ds, bool $reactiva, stdClass $registro): array|stdClass
    {
        if($id <= 0){
            return $this->error->error(mensaje: 'Error id es menor a 0',data:  $id);
        }

        $keys = array('nombre','apellido_paterno');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $registro_ds = $this->descripcion(registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al maquetar descripcion',data:  $registro);
        }

        $r_modifica_descripcion =  parent::modifica_bd(registro: $registro_ds,id:  $id,reactiva:  $reactiva,
            keys_integra_ds:  $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar prospecto',data:  $r_modifica_descripcion);
        }

        return $r_modifica_descripcion;
    }

    private function actualiza_nombre_completo_valida(int $id, array $keys_integra_ds, bool $reactiva, stdClass $registro): array|stdClass
    {

        if($id <= 0){
            return $this->error->error(mensaje: 'Error id es menor a 0',data:  $id);
        }

        $keys = array('nombre','apellido_paterno');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $nombre_completo_valida = (new _prospecto())->nombre_completo_valida(registro: (array)$registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener descripcion',data:  $nombre_completo_valida);
        }
        $registro_ds['nombre_completo_valida'] = $nombre_completo_valida;

        $r_modifica_nombre_completo_valida =  parent::modifica_bd(registro: $registro_ds,id:  $id,reactiva:  $reactiva,
            keys_integra_ds:  $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar prospecto',data:  $nombre_completo_valida);
        }


        $valida = $this->valida_prospecto_repetido_nombre(nombre_completo_valida: $nombre_completo_valida);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar prospecto repetido por nombre',data:  $valida);
        }

        return $r_modifica_nombre_completo_valida;
    }

    public function actualiza_etapa(int $com_prospecto_id, string $etapa) : array|stdClass
    {
        $accion = $this->modifica_bd(registro: array('etapa'=>$etapa), id: $com_prospecto_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al actualizar etapa',data:  $accion);
        }

        return $accion;
    }

    /**
     * Ajusta un registro de datos
     * @param stdClass $r_modifica Resultado de modificacion base
     * @param stdClass $registro Registro base
     * @return stdClass|array
     * @version 2.227.1
     */
    private function ajusta_registro(stdClass $r_modifica, stdClass $registro): stdClass|array
    {
        if(!isset($r_modifica->registro_actualizado)){
            return $this->error->error(mensaje: 'Error $r_modifica->registro_actualizado no existe',
                data:  $r_modifica);
        }
        if(!is_object($r_modifica->registro_actualizado)){
            return $this->error->error(mensaje: 'Error $r_modifica->registro_actualizado debe ser un objeto',
                data:  $r_modifica);
        }
        /*if(!isset($r_modifica->registro_actualizado->com_prospecto_rfc)){
            return $this->error->error(mensaje: 'Error $r_modifica->registro_actualizado->rfc no existe',
                data:  $r_modifica);
        }
        if(!isset($registro->nss)){
            return $this->error->error(mensaje: 'Error registro->nss no existe', data:  $registro);
        }
        if(!isset($registro->curp)){
            return $this->error->error(mensaje: 'Error registro->curp no existe', data:  $registro);
        }
        $registro->rfc = $r_modifica->registro_actualizado->com_prospecto_rfc;

        if($registro->nss === ''){
            $registro->nss = '99999999999';
        }
        if($registro->curp === ''){
            $registro->curp = 'XEXX010101HNEXXXA4';
        }*/
        return $registro;
    }

    public function valida_prioridad_campo(array $registro)
    {
        $keys_contacto = array('liga_red_social', 'lada_com', 'numero_com', 'cel_com', 'correo_com');

        $valores = array('liga_red_social' => 'SIN LIGA', 'lada_com' => '33', 'numero_com' => '33333333',
            'cel_com' => '3333333333', 'correo_com' => 'sincorreo@correo.com');

        $temp = array();
        foreach ($keys_contacto as $key){
            if(!isset($registro[$key]) || $registro[$key] === '') {
                if($key === 'lada_com' || $key === 'numero_com'){
                    $temp['lada_com'] = false;
                    $temp['numero_com'] = false;
                }
                $temp[$key] = false;
                $registro[$key] = $valores[$key];
            }
        }
        $res = true;
        foreach ($keys_contacto as $key){
            if (!isset($temp[$key])){
                $res = false;
            }
        }

        if($this->viene_ubicacion){
            $res = false;
        }

        $resultado = array();

        $resultado['resultado_completo'] = $res;
        $resultado['status_disabled'] = $temp;
        $resultado['registro'] = $registro;

        return $resultado;
    }

    /**
     * Inserta un prospecto
     * @param array $keys_integra_ds Identificadores para descripciones de tipo select
     * @return array|stdClass
     */
    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {

        $resultado = $this->valida_prioridad_campo(registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos de contacto default',
                data:  $resultado);
        }

        if($resultado['resultado_completo']){
            return $this->error->error(mensaje: 'Error al no existe ningun dato de contacto',data:  $resultado);
        }

        $this->registro = $resultado['registro'];

        $keys = array('nombre','apellido_paterno','numero_com','lada_com');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        if(!isset($this->registro['com_tipo_prospecto_id'])){
            $filtro_tipo_prosp['com_tipo_prospecto.descripcion'] = 'VENTA DE VIVIENDA';
            $r_tipo_prospecto = (new com_tipo_prospecto(link: $this->link))->filtro_and(filtro:$filtro_tipo_prosp);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al maquetar row',data:  $r_tipo_prospecto);
            }

            $this->registro['com_tipo_prospecto_id'] = $r_tipo_prospecto->registros[0]['com_tipo_prospecto_id'];
        }

        /*$entidades = array('inm_prototipo','inm_complemento','inm_estado_vivienda');
        $registro = (new _prospecto())->previo_alta(modelo: $this, registro: $this->registro,entidades: $entidades);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al maquetar row',data:  $registro);
        }*/

        $registro = $this->descripcion(registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al maquetar descripcion',data:  $registro);
        }

        $keys = array('inm_prototipo_id','inm_complemento_id','inm_estado_vivienda_id','com_tipo_prospecto_id',
            'com_agente_id');
        foreach ($keys as $key){
            if(!isset($registro[$key]) || trim($registro[$key]) === ''){
                $registro[$key] = -1;
            }
        }

        if((int)$registro['inm_prototipo_id'] === -1) {
            $registro['inm_prototipo_id'] = 1;
        }
        if((int)$registro['inm_complemento_id'] === -1){
            $registro['inm_complemento_id'] = 1;
        }
        if((int)$registro['inm_estado_vivienda_id'] === -1){
            $registro['inm_estado_vivienda_id'] = 1;
        }
        if((int)$registro['com_tipo_prospecto_id'] === -1){
            $registro['com_tipo_prospecto_id'] = 1;
        }
        if((int)$registro['com_agente_id'] === -1){
            $registro['com_agente_id'] = 10;
        }

        $com_prospecto_ins = $this->com_prospecto_ins(registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al maquetar com_prospecto',data:  $com_prospecto_ins);
        }

        $r_com_prospecto = (new com_prospecto(link: $this->link))->alta_registro(registro: $com_prospecto_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar com_prospecto',data:  $r_com_prospecto);
        }
        $registro['com_prospecto_id'] = $r_com_prospecto->registro_id;

        unset($this->registro['dp_municipio_nacimiento_id']);
        $this->registro = $registro;
        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar prospecto',data:  $r_alta_bd);
        }

        /*$alta_inm_prospecto_proceso = $this->inserta_sub_proceso(inm_prospecto_id: $r_alta_bd->registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error insertar alta_inm_prospecto_proceso',
                data:  $alta_inm_prospecto_proceso);
        }*/

        $filtro_status_prospecto_ubicacion['inm_status_prospecto_ubicacion.descripcion'] = 'ALTA';
        $r_status_prospecto_ubicacion = (new inm_status_prospecto_ubicacion(link: $this->link))->filtro_and(
            filtro: $filtro_status_prospecto_ubicacion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener status prospecto_ubicacion',
                data: $r_status_prospecto_ubicacion);
        }

        $modelo_inm_bitacora = new inm_bitacora_status_prospecto_ubicacion(link: $this->link);
        $modelo_inm_bitacora->registro['inm_status_prospecto_ubicacion_id'] =
            $r_status_prospecto_ubicacion->registros[0]['inm_status_prospecto_ubicacion_id'];
        $modelo_inm_bitacora->registro['inm_prospecto_ubicacion_id'] = $r_alta_bd->registro_id;
        $modelo_inm_bitacora->registro['fecha_status'] =  date('Y-m-d\TH:i:s');
        $modelo_inm_bitacora->registro['observaciones'] =  'Status Inicial';
        $r_alta_status = $modelo_inm_bitacora->alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al registrar elemnto de bitacora prospecto_ubicacion',
                data: $r_alta_status);
        }

        return $r_alta_bd;
    }

    final public function convierte_ubicacion(int $inm_prospecto_ubicacion_id): array|stdClass
    {
        if($inm_prospecto_ubicacion_id<=0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id es menor a 0', data: $inm_prospecto_ubicacion_id);
        }

        $r_alta_ubicacion = (new _conversion_ubicacion())->inserta_inm_ubicacion(
            inm_prospecto_ubicacion_id: $inm_prospecto_ubicacion_id, modelo: $this);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente', data: $r_alta_ubicacion);
        }

        $r_alta_rel = (new _conversion_ubicacion())->inserta_rel_ubicacion_prospecto_ubicacion(
            inm_ubicacion_id: $r_alta_ubicacion->registro_id, inm_prospecto_ubicacion_id:  $inm_prospecto_ubicacion_id,
            link: $this->link);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar inm_rel_prospecto_cliente_ins', data: $r_alta_rel);
        }

        $data = new stdClass();
        $data->r_alta_ubicacion = $r_alta_ubicacion;
        $data->r_alta_rel = $r_alta_rel;

        return $data;
    }

    private function com_prospecto_ins(array $registro): array
    {
        $keys = array('nombre','apellido_paterno','razon_social','com_tipo_prospecto_id','com_agente_id');

        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data: $valida);
        }

        if(!isset($registro['apellido_materno'])){
            $registro['apellido_materno'] = '';
        }
        $com_prospecto_ins['nombre'] = trim($registro['nombre']);
        $com_prospecto_ins['apellido_paterno'] = trim($registro['apellido_paterno']);
        $com_prospecto_ins['apellido_materno'] = trim($registro['apellido_materno']);
        $com_prospecto_ins['razon_social'] = trim($registro['razon_social']);
        $com_prospecto_ins['com_tipo_prospecto_id'] = trim($registro['com_tipo_prospecto_id']);
        $com_prospecto_ins['com_agente_id'] = trim($registro['com_agente_id']);

        return $com_prospecto_ins;
    }

    /**
     * Integra los datos de un prospecto para su modificacion en comercial
     * @param stdClass $registro Registro en proceso
     * @return array
     * @version 2.228.2
     */
    private function data_com_prospecto(stdClass $registro): array
    {
        $keys = array('nombre','apellido_paterno','lada_com','numero_com','correo_com','razon_social',
            'apellido_materno');

        foreach ($keys as $key){
            if(!isset($registro->$key)){
                $registro->$key = '';
            }
        }

        $data_com_prospecto['nombre'] = $registro->nombre;
        $data_com_prospecto['apellido_paterno'] = $registro->apellido_paterno;
        $data_com_prospecto['apellido_materno'] = $registro->apellido_materno;
        $data_com_prospecto['telefono'] = $registro->lada_com.$registro->numero_com;
        $data_com_prospecto['correo'] = $registro->correo_com;
        $data_com_prospecto['razon_social'] = $registro->razon_social;
        return $data_com_prospecto;
    }

    final public function descripcion(array|stdClass $registro): string|array
    {
        if(is_object($registro)){
            $registro = (array)$registro;
        }
        $keys = array('nombre','apellido_paterno');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        if(!isset($registro['apellido_materno'])){
            $registro['apellido_materno'] = '';
        }

        $descripcion = $registro['nombre'];
        $descripcion .= ' '.$registro['apellido_paterno'];
        $descripcion .= ' '.$registro['apellido_materno'];
        $descripcion .= ' '.date('Y-m-d-H-i-s');

        $registro['descripcion'] = $descripcion;

        return $registro;
    }

    /**
     * Elimina un prospecto junto con inm_doc_prospecto y inm_prospecto_proceso inm_rel_prospecto_cliente,
     * inm_rel_conyuge_prospecto
     * @param int $id Identificador de prospecto
     * @return array|stdClass
     * @version 2.223.2
     */
    public function elimina_bd(int $id): array|stdClass
    {
        if($id <= 0){
            return  $this->error->error(mensaje: 'El id no puede ser menor a 0 en '.$this->tabla, data: $id);
        }

        $filtro['inm_prospecto_ubicacion.id'] = $id;

        $del = (new inm_doc_prospecto_ubicacion(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_comprador_etapa',
                data:  $del);
        }

        $del = (new inm_prospecto_ubicacion_proceso(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_comprador_etapa',
                data:  $del);
        }

        $del = (new inm_rel_ubicacion_prospecto_ubicacion(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_rel_prospecto_cliente',
                data:  $del);
        }

        $del = (new inm_rel_conyuge_prospecto_ubicacion(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_rel_conyuge_prospecto',
                data:  $del);
        }

        $r_elimina = parent::elimina_bd(id: $id); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar ',data:  $r_elimina);
        }
        return $r_elimina;
    }

    /**
     * Valida si existe o no un conyuge ligado al prospecto
     * @param int $inm_prospecto_id Identificador de prospecto
     * @return array|bool
     * @version 2.257.2
     */
    final public function existe_conyuge(int $inm_prospecto_id): bool|array
    {
        if($inm_prospecto_id <=0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id es menor a 0',data:  $inm_prospecto_id);
        }
        $filtro = array();
        $filtro['inm_prospecto_ubicacion.id'] = $inm_prospecto_id;

        $existe_conyuge = (new inm_rel_conyuge_prospecto_ubicacion(link: $this->link))->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe conyuge',data:  $existe_conyuge);
        }
        return $existe_conyuge;
    }

    /**
     * Obtiene los datos del cliente de fc basados en el comprador
     * @param int $inm_prospecto_id
     * @param bool $retorno_obj Retorna un objeto en caso de ser true
     * @return array|object
     * @version 2.224.3
     */
    final public function get_com_prospecto(int $inm_prospecto_id, bool $retorno_obj = false): object|array
    {
        if($inm_prospecto_id<=0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id es menor a 0',data:  $inm_prospecto_id);
        }
        $inm_prospecto = $this->registro(registro_id: $inm_prospecto_id,columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_prospecto',data:  $inm_prospecto);
        }

        $com_prospecto = (new com_prospecto(link: $this->link))->registro(registro_id: $inm_prospecto->com_prospecto_id,
            retorno_obj: $retorno_obj);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener com_prospecto',data:  $com_prospecto);
        }
        return $com_prospecto;
    }

    final public function inm_beneficiarios(int $inm_prospecto_id){
        $filtro['inm_prospecto.id'] = $inm_prospecto_id;
        $r_inm_beneficiario = (new inm_beneficiario(link: $this->link))->filtro_and(filtro: $filtro,);
        if(errores::$error){
            return$this->error->error(mensaje: 'Error al obtener beneficiarios', data: $r_inm_beneficiario);
        }
        return $r_inm_beneficiario->registros_obj;
    }

    final public function inm_conyuge(int $inm_prospecto_id){
        $filtro['inm_prospecto.id'] = $inm_prospecto_id;
        $r_inm_rel_conyuge_prospecto = (new inm_rel_conyuge_prospecto(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener r_inm_rel_conyuge_prospecto',
                data:  $r_inm_rel_conyuge_prospecto);
        }

        if($r_inm_rel_conyuge_prospecto->n_registros === 0){
            return $this->error->error(mensaje: 'Error al no existe conyuge relacionado',
                data:  $r_inm_rel_conyuge_prospecto);
        }

        if($r_inm_rel_conyuge_prospecto->n_registros > 1){
            return $this->error->error(mensaje: 'Error solo debe existir un conyuge',
                data:  $r_inm_rel_conyuge_prospecto);
        }

        $inm_conyuge_id = $r_inm_rel_conyuge_prospecto->registros[0]['inm_conyuge_id'];
        $inm_conyuge = (new inm_conyuge(link: $this->link))->registro(registro_id: $inm_conyuge_id, retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_conyuge',
                data:  $inm_conyuge);
        }

        return $inm_conyuge;


    }

    /**
     * Genera un registro para insercion de prospecto proceso
     * @param int $inm_prospecto_id Identificador de prospecto
     * @param int $pr_sub_proceso_id Identificador de proceso
     * @return array
     * @version 2.205.1
     */
    private function inm_prospecto_proceso_ins(int $inm_prospecto_id, int $pr_sub_proceso_id): array
    {
        if($inm_prospecto_id<=0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id es menor a 1', data: $inm_prospecto_id);
        }
        if($pr_sub_proceso_id<=0){
            return $this->error->error(mensaje: 'Error pr_sub_proceso_id es menor a 1', data: $pr_sub_proceso_id);
        }
        $inm_prospecto_proceso_ins['pr_sub_proceso_id'] = $pr_sub_proceso_id;
        $inm_prospecto_proceso_ins['fecha'] = date('Y-m-d');
        $inm_prospecto_proceso_ins['inm_prospecto_id'] = $inm_prospecto_id;

        return $inm_prospecto_proceso_ins;
    }

    final public function inm_referencias(int $inm_prospecto_id){
        $filtro['inm_prospecto.id'] = $inm_prospecto_id;
        $r_inm_referencia_prospecto = (new inm_referencia_prospecto(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return$this->error->error(mensaje: 'Error al obtener r_inm_referencia_prospecto', data: $r_inm_referencia_prospecto);
        }


        return $r_inm_referencia_prospecto->registros_obj;
    }


    /**
     * Inserta un sub proceso de etapa en prospecto
     * @param int $inm_prospecto_id Identificador de prospecto
     * @return array|stdClass
     * @version 2.207.1
     */
    private function inserta_sub_proceso(int $inm_prospecto_id): array|stdClass
    {
        if($inm_prospecto_id<=0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id es menor a 1', data: $inm_prospecto_id);
        }

        $pr_sub_proceso = $this->pr_sub_proceso();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error obtener pr_sub_proceso',data:  $pr_sub_proceso);
        }

        $inm_prospecto_proceso_ins = $this->inm_prospecto_proceso_ins(inm_prospecto_id: $inm_prospecto_id,
            pr_sub_proceso_id: $pr_sub_proceso['pr_sub_proceso_id']);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error integrar pr_sub_proceso',data:  $inm_prospecto_proceso_ins);
        }

        $alta_inm_prospecto_proceso = (new inm_prospecto_proceso(link: $this->link))->alta_registro(
            registro: $inm_prospecto_proceso_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error insertar alta_inm_prospecto_proceso',
                data:  $alta_inm_prospecto_proceso);
        }
        return $alta_inm_prospecto_proceso;
    }

    /**
     * Modifica un prospecto, y su relacion con com_prospecto
     * @param array $registro Registro en proceso
     * @param int $id Id de prospecto
     * @param bool $reactiva valida la reactivacion del registro
     * @param array $keys_integra_ds columnas para descripcion select
     * @return array|stdClass
     */
    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {

        if($id <= 0){
            return $this->error->error(mensaje: 'Error id es menor a 0',data:  $id);
        }

        $r_modifica =  parent::modifica_bd(registro: $registro,id:  $id,reactiva:  $reactiva,
            keys_integra_ds:  $keys_integra_ds); // TODO: Change the autogenerated stub

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar prospecto',data:  $r_modifica);
        }

        $upd = $this->post_upd(id: $id,keys_integra_ds:  $keys_integra_ds,r_modifica:  $r_modifica,reactiva:  $reactiva);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar prospecto',data:  $upd);
        }


        return $r_modifica;
    }

    /**
     * Modifica un com_prospecto cuando se modifica inm_prospecto
     * @param stdClass $registro Registro en proceso
     * @return array|stdClass
     */
    private function modifica_com_prospecto(stdClass $registro): array|stdClass
    {
        $keys = array('com_prospecto_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $data_com_prospecto = $this->data_com_prospecto(registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al maquetar data_com_prospecto',data:  $data_com_prospecto);
        }

        $upd = (new com_prospecto(link: $this->link))->modifica_bd(registro: $data_com_prospecto,
            id:  $registro->com_prospecto_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar prospecto',data:  $upd);
        }

        $regenera = (new com_prospecto(link: $this->link))->regenera_agente_inicial(
            com_prospecto_id: $registro->com_prospecto_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al regenerar relaciones',data:  $regenera);
        }

        return $upd;
    }



    /**
     * Ejecta las modificaciones en prospecto comercial y descripcion misma
     * @param int $id Id de prospecto
     * @param array $keys_integra_ds Columnas de descripcion select
     * @param stdClass $r_modifica Resultado de modificacion
     * @param bool $reactiva valida la reactivacion
     * @return array|stdClass
     */
    private function post_upd(int $id, array $keys_integra_ds, stdClass $r_modifica, bool $reactiva): array|stdClass
    {
        if($id <= 0){
            return $this->error->error(mensaje: 'Error id es menor a 0',data:  $id);
        }
        /*if(!isset($r_modifica->registro_actualizado->com_prospecto_rfc)){
            return $this->error->error(mensaje: 'Error no existe $r_modifica->registro_actualizado->com_prospecto_rfc',
                data:  $r_modifica);
        }*/
        if(!isset($r_modifica->registro_puro)){
            return $this->error->error(mensaje: 'Error $r_modifica->registro_puro no existe', data:  $r_modifica);
        }

        $registro = $r_modifica->registro_puro;


        $registro = $this->ajusta_registro(r_modifica: $r_modifica,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al ajustar registro',data:  $registro);
        }

        $upd = $this->transacciones_externas(id: $id,keys_integra_ds:  $keys_integra_ds,reactiva:  $reactiva,
            registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar prospecto',data:  $upd);
        }

        $upd->registro = $registro;

        return $upd;
    }

    /**
     * Obtiene el sub proceso de alta de un prospecto
     * @return array
     * @version 2.204.1
     */
    private function pr_sub_proceso(): array
    {
        $filtro = array();
        $filtro['pr_sub_proceso.descripcion'] = 'ALTA PROSPECTO';
        $filtro['adm_seccion.descripcion'] = $this->tabla;

        $r_pr_sub_proceso = (new pr_sub_proceso(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error obtener r_pr_sub_proceso',data:  $r_pr_sub_proceso);
        }

        if($r_pr_sub_proceso->n_registros === 0){
            return $this->error->error(mensaje: 'Error no existe sub proceso definido',data:  $filtro);
        }

        if($r_pr_sub_proceso->n_registros > 1){
            return $this->error->error(mensaje: 'Error de integridad',data:  $r_pr_sub_proceso);
        }

        return $r_pr_sub_proceso->registros[0];
    }

    private function regenera_agente_inicial(int $inm_prospecto_id)
    {
        $inm_prospecto = $this->registro(registro_id: $inm_prospecto_id, columnas_en_bruto: true, retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_prospecto',data:  $inm_prospecto);
        }

        $com_prospecto_id = $inm_prospecto->com_prospecto_id;
        $regenera = (new com_prospecto(link: $this->link))->regenera_agente_inicial(com_prospecto_id: $com_prospecto_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al regenerar relacion inicial', data: $regenera);
        }
        return $regenera;

    }

    final public function regenera_agentes_iniciales()
    {
        $registros = $this->registros(return_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener prospectos',data:  $registros);
        }
        $regeneraciones = array();
        foreach ($registros as $inm_prospecto){
            $regenera = $this->regenera_agente_inicial($inm_prospecto->inm_prospecto_id);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al regenerar relacion inicial', data: $regenera);
            }
            $regeneraciones[] = $regenera;
        }
        return $regeneraciones;

    }

    final public function regenera_nombre_completo_valida()
    {
        $registros = $this->registros(columnas_en_bruto: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener prospectos',data:  $registros);
        }
        $regeneraciones = array();
        foreach ($registros as $inm_prospecto){

            $nombre_completo_valida = (new _prospecto())->nombre_completo_valida(registro: $inm_prospecto);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener nombre_completo_valida',data:  $nombre_completo_valida);
            }

            $registro_upd['nombre_completo_valida'] = $nombre_completo_valida;
            $upd = $this->modifica_bd(registro: $registro_upd,id:  $inm_prospecto['id']);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al actualizar registro',data:  $upd);
            }


            $regeneraciones[] = $upd;
        }
        return $regeneraciones;

    }

    public function status_prospecto_ubicacion(int $inm_prospecto_id,
                                     array $order = array('inm_bitacora_status_prospecto_ubicacion.fecha_status'=>'DESC')){
        $filtro['inm_prospecto_ubicacion.id'] = $inm_prospecto_id;
        $r_inm_bitacora_prospecto = (new inm_bitacora_status_prospecto_ubicacion(link: $this->link))->filtro_and(filtro: $filtro,order: $order);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener etapas', data: $r_inm_bitacora_prospecto);
        }

        return $r_inm_bitacora_prospecto->registros;
    }


    final public function transacciones_upd(int $inm_prospecto_ubicacion_id){
        $result_direccion = $this->transacciona_direccion(inm_prospecto_ubicacion_id: $inm_prospecto_ubicacion_id,
            link: $this->link);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar direccion', data: $result_direccion);
        }

        $result_conyuge = (new _upd_prospecto_ubicacion())->transacciona_conyuge(inm_prospecto_ubicacion_id: $inm_prospecto_ubicacion_id,link: $this->link);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar conyuge', data: $result_conyuge);
        }

        $data = new stdClass();
        $data->result_conyuge = $result_conyuge;
        $data->result_direccion = $result_direccion;

        return $data;
    }

    /**
     * Ejecuta las transacciones de modificacion de com prospecto y descripcion en this
     * @param int $id Id de prospecto
     * @param array $keys_integra_ds campos de descripcion select
     * @param bool $reactiva valida la reactivacion de un registro
     * @param stdClass $registro Registro modificado
     * @return array|stdClass
     */
    private function transacciones_externas(int $id, array $keys_integra_ds, bool $reactiva,
                                            stdClass $registro): array|stdClass
    {
        if($id <= 0){
            return $this->error->error(mensaje: 'Error id es menor a 0',data:  $id);
        }
        $keys = array('nombre','apellido_paterno');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }
        
        $keys = array('com_prospecto_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $r_modifica_descripcion =  $this->actualiza_descripcion(id: $id, keys_integra_ds: $keys_integra_ds,
            reactiva: $reactiva, registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar descripcion',data:  $r_modifica_descripcion);
        }

        $r_modifica_nombre_completo_valida =  $this->actualiza_nombre_completo_valida(id: $id,
            keys_integra_ds: $keys_integra_ds, reactiva: $reactiva, registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar nombre_completo_valida',
                data:  $r_modifica_nombre_completo_valida);
        }

        $upd = $this->modifica_com_prospecto(registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar prospecto',data:  $upd);
        }

        $data = new stdClass();
        $data->r_modifica_descripcion = $r_modifica_descripcion;
        $data->r_modifica_nombre_completo_valida = $r_modifica_nombre_completo_valida;
        $data->upd_com_prospecto = $upd;
        return $data;
    }

    private function valida_prospecto_repetido_nombre(string $nombre_completo_valida)
    {
        $filtro['inm_prospecto_ubicacion.nombre_completo_valida'] = $nombre_completo_valida;
        $n_prospectos = $this->cuenta(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al contar prospectos',data:  $n_prospectos);
        }
        if($n_prospectos > 1){
            return $this->error->error(mensaje: 'Error existe mas de un prospecto con el mismo nombre',
                data:  array($n_prospectos,$filtro));
        }
        return true;

    }

    private function ajusta_direccion(stdClass $datos, int $inm_prospecto_ubicacion_id, PDO $link){

        $r_inm_direccion_bd = $this->inserta_domicilio(domicilio: $datos->row,
            inm_prospecto_ubicacion_id: $inm_prospecto_ubicacion_id,link: $link);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar r_inm_beneficiario_bd', data: $r_inm_direccion_bd);
        }
        $datos = $r_inm_direccion_bd;

        return $datos;
    }

    final public function transacciona_direccion(int $inm_prospecto_ubicacion_id, PDO $link){
        $datos = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->dato(existe: false,key_data: 'direccion');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener dato de direccion',data:  $datos);
        }

        if($datos->tiene_dato){
            $result_direccion = $this->ajusta_direccion(datos: $datos,inm_prospecto_ubicacion_id: $inm_prospecto_ubicacion_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar direccion', data: $result_direccion);
            }
            $datos->result_direccion = $result_direccion;
        }
        return $datos;

    }

    public function inserta_domicilio(array $domicilio, int $inm_prospecto_ubicacion_id, PDO $link): array|stdClass
    {
        $keys = array('cp','colonia','calle','texto_exterior');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $domicilio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar direccion',data:  $valida);
        }

        $keys = array('dp_municipio_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $domicilio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar beneficiario',data:  $valida);
        }

        if($inm_prospecto_ubicacion_id <= 0){
            return $this->error->error(mensaje: 'Error inm_prospecto_ubicacion_id debe ser mayor a 0',data:  $inm_prospecto_ubicacion_id);
        }

        $r_com_prospecto = (new inm_prospecto_ubicacion(link: $link))->registro(registro_id: $inm_prospecto_ubicacion_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar beneficiario',data:  $valida);
        }

        $tipo_direccion = (new com_tipo_direccion(link: $link))->filtro_and();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tipo_direccion',data:  $tipo_direccion);
        }

        if ($tipo_direccion->n_registros === 0) {
            return ['error' => 'No existe un tipo de dirección valido registrado en el sistema'] ;
        }

        $alta_relacion = array();
        foreach ($tipo_direccion->registros as $value) {
            $domicilio['com_tipo_direccion_id'] = $value['com_tipo_direccion_id'];
            $alta_direccion = (new com_direccion(link: $link))->alta_registro(registro: $domicilio);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar alta_direccion', data: $alta_direccion);
            }

            $relacion['com_direccion_id'] = $alta_direccion->registro_id;
            $alta_relacion = $this->modifica_bd(registro: $relacion, id: $inm_prospecto_ubicacion_id);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar alta_relacion', data: $alta_relacion);
            }
        }

        return $alta_relacion;
    }
}