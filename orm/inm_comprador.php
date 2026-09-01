<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\comercial\models\com_agente;
use gamboamartin\comercial\models\com_cliente;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_comprador extends _modelo_parent{
    public bool $desde_prospecto = false;
    public function __construct(PDO $link, bool $valida_atributos_criticos = true)
    {
        $tabla = 'inm_comprador';
        $columnas = array($tabla=>false,'inm_producto_infonavit'=>$tabla,'inm_attr_tipo_credito'=>$tabla,
            'inm_tipo_credito'=>'inm_attr_tipo_credito','inm_destino_credito'=>$tabla,'inm_plazo_credito_sc'=>$tabla,
            'inm_tipo_discapacidad'=>$tabla,'inm_persona_discapacidad'=>$tabla,'inm_estado_civil'=>$tabla,
            'adm_estado_civil' => 'inm_estado_civil', 'bn_cuenta'=>$tabla,'org_sucursal'=>'bn_cuenta',
            'org_empresa'=>'org_sucursal', 'inm_institucion_hipotecaria'=>$tabla,'inm_sindicato'=>$tabla,
            'inm_nacionalidad'=>$tabla, 'inm_ocupacion'=>$tabla, 'com_agente'=>$tabla,'inm_status_comprador' => $tabla,
            'inm_tipo_exento' => $tabla, 'inm_notaria' => $tabla, 'dp_colonia_postal'=>$tabla,
            'dp_cp'=>'dp_colonia_postal', 'dp_colonia'=>'dp_colonia_postal', 'dp_municipio'=>'dp_cp',
            'dp_estado'=>'dp_municipio', 'dp_pais'=>'dp_estado', 'inm_tipo_venta' => $tabla);

        $campos_obligatorios = array('apellido_paterno','bn_cuenta_id','curp', 'descuento_pension_alimenticia_dh',
            'descuento_pension_alimenticia_fc', 'es_segundo_credito', 'inm_attr_tipo_credito_id',
            'inm_destino_credito_id','inm_estado_civil_id','inm_persona_discapacidad_id', 'inm_producto_infonavit_id',
            'inm_plazo_credito_sc_id', 'inm_tipo_discapacidad_id', 'monto_ahorro_voluntario',
            'monto_credito_solicitado_dh','nombre','inm_institucion_hipotecaria_id','inm_sindicato_id',
            'dp_municipio_nacimiento_id', 'fecha_nacimiento','monto_final','sub_cuenta','descuento','puntos',
            'inm_nacionalidad_id', 'inm_ocupacion_id');

        $renombres['dp_cp_empresa']['nombre_original']= 'dp_cp';
        $renombres['dp_cp_empresa']['enlace']= 'dp_colonia_postal';
        $renombres['dp_cp_empresa']['key']= 'id';
        $renombres['dp_cp_empresa']['key_enlace']= 'dp_cp_id';

        $renombres['dp_municipio_empresa']['nombre_original']= 'dp_municipio';
        $renombres['dp_municipio_empresa']['enlace']= 'dp_cp_empresa';
        $renombres['dp_municipio_empresa']['key']= 'id';
        $renombres['dp_municipio_empresa']['key_enlace']= 'dp_municipio_id';

        $renombres['dp_estado_empresa']['nombre_original']= 'dp_estado';
        $renombres['dp_estado_empresa']['enlace']= 'dp_municipio_empresa';
        $renombres['dp_estado_empresa']['key']= 'id';
        $renombres['dp_estado_empresa']['key_enlace']= 'dp_estado_id';

        $renombres = (new _base_paquete())->rename_data_nac(enlace: $tabla, renombres: $renombres);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al integrar rename', data: $renombres);
            print_r($error);
            exit;
        }

        $atributos_criticos = array('apellido_materno','apellido_paterno','bn_cuenta_id','cel_com','curp','correo_com',
            'descuento_pension_alimenticia_dh', 'descuento_pension_alimenticia_fc','es_segundo_credito',
            'extension_nep','genero', 'inm_attr_tipo_credito_id', 'inm_destino_credito_id','inm_estado_civil_id',
            'inm_persona_discapacidad_id', 'inm_plazo_credito_sc_id', 'inm_producto_infonavit_id',
            'inm_tipo_discapacidad_id','lada_com', 'monto_ahorro_voluntario', 'monto_credito_solicitado_dh',
            'nombre', 'nombre_empresa_patron', 'nrp_nep','numero_com','numero_nep','inm_institucion_hipotecaria_id',
            'inm_sindicato_id','dp_municipio_nacimiento_id','fecha_nacimiento','monto_final','sub_cuenta','descuento',
            'puntos','inm_nacionalidad_id','inm_ocupacion_id','telefono_casa','correo_empresa');


        $tipo_campos['lada_com'] = 'lada';
        $tipo_campos['lada_nep'] = 'lada';
        $tipo_campos['numero_nep'] = 'telefono_mx';
        $tipo_campos['numero_com'] = 'telefono_mx';
        $tipo_campos['curp'] = 'curp';
        $tipo_campos['nss'] = 'nss';
        $tipo_campos['cel_com'] = 'telefono_mx';
        $tipo_campos['telefono_casa'] = 'telefono_mx';
        $tipo_campos['correo_com'] = 'correo';
        $tipo_campos['correo_empresa'] = 'correo';

        $columnas_extra= array();
        $sql = "(CONCAT_WS(' ', inm_comprador.nombre, inm_comprador.apellido_paterno, inm_comprador.apellido_materno))";

        $columnas_extra['inm_comprador_razon_social'] = $sql;

        $sql = "( IFNULL((SELECT
                    CONCAT(inm_ubicacion.calle, ' ', inm_ubicacion.numero_exterior, ' ', inm_ubicacion.numero_interior, ' ', dp_colonia.descripcion, ' ', dp_cp.descripcion, ' ', dp_municipio.descripcion)
                    FROM inm_rel_ubi_comp 
                        LEFT JOIN inm_ubicacion ON inm_ubicacion.id = inm_rel_ubi_comp.inm_ubicacion_id
                        LEFT JOIN dp_colonia_postal ON dp_colonia_postal.id = inm_ubicacion.dp_colonia_postal_id
                        LEFT JOIN dp_colonia ON dp_colonia.id = dp_colonia_postal.dp_colonia_id
                        LEFT JOIN dp_cp ON dp_cp.id = dp_colonia_postal.dp_cp_id
                        LEFT JOIN dp_municipio ON dp_municipio.id = dp_cp.dp_municipio_id
                        LEFT JOIN dp_estado ON dp_estado.id = dp_municipio.dp_estado_id
                        LEFT JOIN dp_pais ON dp_pais.id = dp_estado.dp_pais_id
                        WHERE
                        inm_rel_ubi_comp.inm_comprador_id = inm_comprador.id
                         LIMIT 1), ''))";

        $columnas_extra['inm_ubicacion_completa'] = $sql;

        /*$sql = "( IFNULL((SELECT
                    CONCAT(inm_ubicacion.calle)
                    FROM inm_rel_ubi_comp 
                        LEFT JOIN inm_ubicacion ON inm_ubicacion.id = inm_rel_ubi_comp.inm_ubicacion_id
                        WHERE
                        inm_rel_ubi_comp.inm_comprador_id = inm_comprador.id
                         LIMIT 1), ''))";

        $columnas_extra['inm_ubicacion_calle'] = $sql;

        $sql = "( IFNULL((SELECT
                    CONCAT(inm_ubicacion.numero_exterior, ' ', inm_ubicacion.numero_interior)
                    FROM inm_rel_ubi_comp 
                        LEFT JOIN inm_ubicacion ON inm_ubicacion.id = inm_rel_ubi_comp.inm_ubicacion_id
                        WHERE
                        inm_rel_ubi_comp.inm_comprador_id = inm_comprador.id
                         LIMIT 1), ''))";

        $columnas_extra['inm_ubicacion_numero'] = $sql;

        $sql = "( IFNULL((SELECT
                    CONCAT(dp_colonia.descripcion)
                    FROM inm_rel_ubi_comp 
                        LEFT JOIN inm_ubicacion ON inm_ubicacion.id = inm_rel_ubi_comp.inm_ubicacion_id
                        LEFT JOIN dp_colonia_postal ON dp_colonia_postal.id = inm_ubicacion.dp_colonia_postal_id
                        LEFT JOIN dp_colonia ON dp_colonia.id = dp_colonia_postal.dp_colonia_id
                        WHERE
                        inm_rel_ubi_comp.inm_comprador_id = inm_comprador.id
                         LIMIT 1), ''))";

        $columnas_extra['inm_ubicacion_colonia'] = $sql;

        $sql = "( IFNULL((SELECT
                    CONCAT(dp_cp.descripcion)
                    FROM inm_rel_ubi_comp 
                        LEFT JOIN inm_ubicacion ON inm_ubicacion.id = inm_rel_ubi_comp.inm_ubicacion_id
                        LEFT JOIN dp_colonia_postal ON dp_colonia_postal.id = inm_ubicacion.dp_colonia_postal_id
                        LEFT JOIN dp_colonia ON dp_colonia.id = dp_colonia_postal.dp_colonia_id
                        LEFT JOIN dp_cp ON dp_cp.id = dp_colonia_postal.dp_cp_id
                        WHERE
                        inm_rel_ubi_comp.inm_comprador_id = inm_comprador.id
                         LIMIT 1), ''))";

        $columnas_extra['inm_ubicacion_cp'] = $sql;

        $sql = "( IFNULL((SELECT
                    CONCAT(dp_municipio.descripcion)
                    FROM inm_rel_ubi_comp 
                        LEFT JOIN inm_ubicacion ON inm_ubicacion.id = inm_rel_ubi_comp.inm_ubicacion_id
                        LEFT JOIN dp_colonia_postal ON dp_colonia_postal.id = inm_ubicacion.dp_colonia_postal_id
                        LEFT JOIN dp_colonia ON dp_colonia.id = dp_colonia_postal.dp_colonia_id
                        LEFT JOIN dp_cp ON dp_cp.id = dp_colonia_postal.dp_cp_id
                        LEFT JOIN dp_municipio ON dp_municipio.id = dp_cp.dp_municipio_id
                        WHERE
                        inm_rel_ubi_comp.inm_comprador_id = inm_comprador.id
                         LIMIT 1), ''))";

        $columnas_extra['inm_ubicacion_municipio'] = $sql;*/


        $sql = "( IFNULL((SELECT
                        fecha_status
                    FROM inm_bitacora_status_comprador 
                        WHERE
                    inm_bitacora_status_comprador.inm_comprador_id = inm_comprador.id
                        AND
                    inm_bitacora_status_comprador.inm_status_comprador_id = inm_status_comprador.id
                         LIMIT 1), ''))";

        $columnas_extra['inm_fecha_status'] = $sql;

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            tipo_campos: $tipo_campos, atributos_criticos: $atributos_criticos,
            valida_atributos_criticos: $valida_atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Clientes';
    }

    /**
     * Inserta un comprador, un cliente, una relacion entre comprador y cliente proceso comprador y etapa comprador
     * @param array $keys_integra_ds Keys para descripcion select
     * @return array|stdClass
     */
    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        if(!isset($this->registro['dp_colonia_postal_id']) || (string)$this->registro['dp_colonia_postal_id'] === '-1'){
            $this->registro['dp_colonia_postal_id'] = 105;
        }

        if(!isset($this->registro['com_agente_id'])){
            $filtro_tipo_prosp['com_tipo_agente.descripcion'] = 'PREDETERMINADO';
            $r_agente = (new com_agente(link: $this->link))->filtro_and(filtro:$filtro_tipo_prosp);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al maquetar row',data:  $r_agente);
            }

            $this->registro['com_agente_id'] = $r_agente->registros[0]['com_agente_id'];
        }

        $registro_entrada = $this->registro;

        $registro = (new _alta_comprador())->init_row_alta(modelo: $this, registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar registro',data:  $registro);
        }

        $this->registro = $registro;

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar',data:  $r_alta_bd);
        }

        if(!$this->desde_prospecto) {
            $tiene_prospecto = (new inm_comprador(link: $this->link))->tiene_prospecto(
                inm_comprador_id: $r_alta_bd->registro_id);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar inm_prospecto', data: $tiene_prospecto);
            }

            if (!$tiene_prospecto) {
                $r_alta_prospecto = (new _conversion())->inserta_inm_prospecto(inm_comprador_id: $r_alta_bd->registro_id,
                    modelo: $this);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar cliente', data: $r_alta_prospecto);
                }

                $r_alta_rel = (new _conversion())->inserta_rel_prospecto_cliente(
                    inm_comprador_id: $r_alta_bd->registro_id, inm_prospecto_id: $r_alta_prospecto->registro_id,
                    link: $this->link);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar inm_rel_prospecto_cliente_ins', data: $r_alta_rel);
                }
            }

            if(!isset($registro_entrada['cp'])){
                $inm_prospecto = (new inm_comprador(link: $this->link))->inm_prospecto(inm_comprador_id: $r_alta_bd->registro_id);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al obtener prospecto', data: $inm_prospecto);
                }
                $registro_entrada['cp'] = $inm_prospecto->dp_cp_codigo;
                $registro_entrada['dp_municipio_id'] = $inm_prospecto->dp_municipio_id;
            }
        }

        $transacciones = (new _alta_comprador())->posterior_alta(
            accion: __FUNCTION__, etapa: 'ALTA', inm_comprador_id: $r_alta_bd->registro_id, link: $this->link,
            pr_proceso_descripcion: 'INMOBILIARIA CLIENTES', registro_entrada: $registro_entrada, tabla: $this->tabla);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar transacciones', data: $transacciones);
        }

        $filtro_status_comprador['inm_status_comprador.descripcion'] = 'DETENIDO';
        $r_status_comprador = (new inm_status_comprador(link: $this->link))->filtro_and(
            filtro: $filtro_status_comprador);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener status comprador', data: $r_status_comprador);
        }

        $modelo_inm_bitacora = new inm_bitacora_status_comprador(link: $this->link);
        $modelo_inm_bitacora->registro['inm_status_comprador_id'] = $r_status_comprador->registros[0]['inm_status_comprador_id'];
        $modelo_inm_bitacora->registro['inm_comprador_id'] = $r_alta_bd->registro_id;
        $modelo_inm_bitacora->registro['fecha_status'] =  date('Y-m-d\TH:i:s');
        $modelo_inm_bitacora->registro['observaciones'] =  'Status Inicial';
        $r_alta_status = $modelo_inm_bitacora->alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al registrar elemnto de bitacora comprador', data: $r_alta_status);
        }


        return $r_alta_bd;

    }

    private function ajusta_beneficiario(stdClass $datos, int $inm_comprador_id, PDO $link){

        $r_inm_beneficiario_bd = $this->inserta_beneficiario(beneficiario: $datos->row,
            inm_comprador_id: $inm_comprador_id,link: $link);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar r_inm_beneficiario_bd', data: $r_inm_beneficiario_bd);
        }
        $datos = $r_inm_beneficiario_bd;

        return $datos;
    }
    
    private function ajusta_referencia(stdClass $datos, int $inm_comprador_id, PDO $link){

        $r_inm_referencia_bd = $this->inserta_referencia(referencia: $datos->row,
            inm_comprador_id: $inm_comprador_id,link: $link);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar r_inm_referencia_bd', data: $r_inm_referencia_bd);
        }
        $datos = $r_inm_referencia_bd;

        return $datos;
    }

    private function ajusta_co_acreditado(stdClass $datos, int $inm_comprador_id, PDO $link): array|stdClass
    {
        if(!$datos->existe) {
            $r_inm_rel_co_acreditado_comprador_bd = $this->inserta_co_acreditado(co_acreditado: $datos->row,
                inm_comprador_id: $inm_comprador_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar co_acreditado', data: $r_inm_rel_co_acreditado_comprador_bd);
            }
            $data = $r_inm_rel_co_acreditado_comprador_bd;
        }
        else{
            $r_modifica_co_acreditado = $this->modifica_co_acreditado(
                co_acreditado: $datos->row, inm_comprador_id: $inm_comprador_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al modificar co_acreditado', data: $r_modifica_co_acreditado);
            }
            $data = $r_modifica_co_acreditado;
        }

        return $data;
    }
    
    private function ajusta_conyuge(stdClass $datos, int $inm_comprador_id, PDO $link): array|stdClass
    {
        if(!$datos->existe) {
            $r_inm_rel_conyuge_comprador_bd = $this->inserta_conyuge(conyuge: $datos->row,
                inm_comprador_id: $inm_comprador_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar conyuge', data: $r_inm_rel_conyuge_comprador_bd);
            }
            $data = $r_inm_rel_conyuge_comprador_bd;
        }
        else{
            $r_modifica_conyuge = $this->modifica_conyuge(
                conyuge: $datos->row, inm_comprador_id: $inm_comprador_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al modificar conyuge', data: $r_modifica_conyuge);
            }
            $data = $r_modifica_conyuge;
        }

        return $data;
    }

    /**
     * Asigna un co acreditado a un comprador
     * @param int $inm_comprador_id Identificador
     * @param array $inm_co_acreditado Registro de co acreditado
     * @return array|stdClass
     */
    final public function asigna_nuevo_co_acreditado_bd(
        int $inm_comprador_id, array $inm_co_acreditado): array|stdClass
    {

        $valida = (new inm_co_acreditado(link: $this->link))->valida_data_alta(inm_co_acreditado: $inm_co_acreditado);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar inm_co_acreditado',data:  $valida);
        }
        $valida = (new inm_co_acreditado(link: $this->link))->valida_alta(inm_co_acreditado: $inm_co_acreditado);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $alta_inm_co_acreditado = (new inm_co_acreditado(link: $this->link))->alta_registro
        (registro: $inm_co_acreditado);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar alta_inm_co_acreditado',
                data:  $alta_inm_co_acreditado);
        }
        $inm_rel_co_acred_ins['inm_co_acreditado_id'] = $alta_inm_co_acreditado->registro_id;
        $inm_rel_co_acred_ins['inm_comprador_id'] = $inm_comprador_id;

        $alta_inm_rel_co_acred = (new inm_rel_co_acred(link: $this->link))->alta_registro(
            registro: $inm_rel_co_acred_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar alta_inm_rel_co_acred',
                data:  $alta_inm_rel_co_acred);
        }

        $data = new stdClass();
        $data->inm_co_acreditado = $alta_inm_co_acreditado;
        $data->inm_rel_co_acred = $alta_inm_rel_co_acred;

        return $data;

    }

    /**
     * Obtiene los datos par ala generacion de la solicitud de infonavit
     * @param int $inm_comprador_id Comprador en proceso
     * @return array|stdClass
     * @version 1.115.1
     */
    final public function data_pdf(int $inm_comprador_id): array|stdClass
    {
        if($inm_comprador_id<=0){
            return $this->error->error(mensaje: 'Error al inm_comprador_id debe ser mayor a 0',
                data: $inm_comprador_id);
        }
        $inm_comprador = $this->registro(registro_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener comprador', data: $inm_comprador);
        }

        $imp_rel_comprador_com_cliente = (new inm_rel_comprador_com_cliente(link: $this->link))
            ->imp_rel_comprador_com_cliente(inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener imp_rel_comprador_com_cliente',
                data: $imp_rel_comprador_com_cliente);
        }

        $com_cliente = (new com_cliente(link: $this->link))->registro(
            registro_id: $imp_rel_comprador_com_cliente['com_cliente_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener com_cliente', data: $com_cliente);
        }

        $imp_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->imp_rel_ubi_comp(
            inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener imp_rel_ubi_comp', data: $imp_rel_ubi_comp);
        }

        $inm_conf_empresa = (new inm_conf_empresa(link: $this->link))->inm_conf_empresa(
            org_empresa_id: $inm_comprador['org_empresa_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener r_inm_conf_empresa', data: $inm_conf_empresa);
        }

        $inm_rel_co_acreditados = (new inm_co_acreditado(link: $this->link))->inm_co_acreditados(
            inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener inm_rel_co_acreditados', data: $inm_rel_co_acreditados);
        }

        $inm_referencias = (new inm_referencia(link: $this->link))->inm_referencias(inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener inm_referencias', data: $inm_referencias);
        }

        $inm_avaluos = (new inm_avaluo(link: $this->link))->inm_avaluos(inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener inm_avaluos', data: $inm_avaluos);
        }

        $data = new stdClass();
        $data->inm_comprador = $inm_comprador;
        $data->imp_rel_comprador_com_cliente = $imp_rel_comprador_com_cliente;
        $data->com_cliente = $com_cliente;
        $data->imp_rel_ubi_comp = $imp_rel_ubi_comp;
        $data->inm_conf_empresa = $inm_conf_empresa;
        $data->inm_rel_co_acreditados = $inm_rel_co_acreditados;
        $data->inm_referencias = $inm_referencias;
        $data->inm_avaluos = $inm_avaluos;
        $data->nombre_archivo = 'solicitud_infonavit.pdf';

        return $data;

    }


    final public function data_solicitud_avaluo_pdf(int $inm_comprador_id): array|stdClass
    {
        if($inm_comprador_id<=0){
            return $this->error->error(mensaje: 'Error al inm_comprador_id debe ser mayor a 0',
                data: $inm_comprador_id);
        }
        $inm_comprador = $this->registro(registro_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener comprador', data: $inm_comprador);
        }

        $imp_rel_comprador_com_cliente = (new inm_rel_comprador_com_cliente(link: $this->link))
            ->imp_rel_comprador_com_cliente(inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener imp_rel_comprador_com_cliente',
                data: $imp_rel_comprador_com_cliente);
        }

        $com_cliente = (new com_cliente(link: $this->link))->registro(
            registro_id: $imp_rel_comprador_com_cliente['com_cliente_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener com_cliente', data: $com_cliente);
        }

        $imp_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->imp_rel_ubi_comp(
            inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener imp_rel_ubi_comp', data: $imp_rel_ubi_comp);
        }

        $inm_conf_empresa = (new inm_conf_empresa(link: $this->link))->inm_conf_empresa(
            org_empresa_id: $inm_comprador['org_empresa_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener r_inm_conf_empresa', data: $inm_conf_empresa);
        }

        $inm_rel_co_acreditados = (new inm_co_acreditado(link: $this->link))->inm_co_acreditados(
            inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener inm_rel_co_acreditados', data: $inm_rel_co_acreditados);
        }

        $inm_referencias = (new inm_referencia(link: $this->link))->inm_referencias(inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener inm_referencias', data: $inm_referencias);
        }

        $data = new stdClass();
        $data->inm_comprador = $inm_comprador;
        $data->imp_rel_comprador_com_cliente = $imp_rel_comprador_com_cliente;
        $data->com_cliente = $com_cliente;
        $data->imp_rel_ubi_comp = $imp_rel_ubi_comp;
        $data->inm_conf_empresa = $inm_conf_empresa;
        $data->inm_rel_co_acreditados = $inm_rel_co_acreditados;
        $data->inm_referencias = $inm_referencias;
        $data->nombre_archivo = 'solicitud_gasto.pdf';

        return $data;

    }

    final public function dato(bool $existe, string $key_data): array|stdClass
    {
        $key_data = trim($key_data);
        if($key_data === ''){
            return $this->error->error(mensaje: 'Error key_data esta vacio',data:  $key_data);
        }

        $row = $this->init_post(key_data: $key_data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar row',data:  $row);
        }

        $tiene_dato = $this->tiene_dato(row: $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si tiene dato tiene_dato',data:  $tiene_dato);
        }
        $datos = new stdClass();
        $datos->existe = $existe;
        $datos->row = $row;
        $datos->tiene_dato = $tiene_dato;
        return $datos;
    }

    public function datos_co_acreditado(PDO $link, int $inm_comprador_id): array|stdClass{
        $existe_co_acreditado = false;
        if($inm_comprador_id > 0) {
            $existe_co_acreditado = (new inm_comprador(link: $link))->existe_co_acreditado(inm_comprador_id: $inm_comprador_id);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar si existe co_acreditado', data: $existe_co_acreditado);
            }
        }

        $datos = $this->dato(existe: $existe_co_acreditado,key_data:  'co_acreditado');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos',data:  $datos);
        }

        return $datos;
    }

    public function datos_conyuge(PDO $link, int $inm_comprador_id): array|stdClass{
        $existe_conyuge = false;
        if($inm_comprador_id > 0) {
            $existe_conyuge = (new inm_comprador(link: $link))->existe_conyuge(inm_comprador_id: $inm_comprador_id);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar si existe conyuge', data: $existe_conyuge);
            }
        }

        $datos = $this->dato(existe: $existe_conyuge,key_data:  'conyuge');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos',data:  $datos);
        }

        return $datos;
    }

    /**
     * Elimina todas las relaciones de comprador y con ella a si misma
     * @relaciones inm_rel_comprador_com_cliente, inm_comprador_etapa, inm_referencia, inm_rel_co_acred,
     *  inm_rel_ubi_comp, inm_comprador_proceso, inm_rel_prospecto_cliente
     * @param int $id Id de registro
     * @return array|stdClass
     * @version 2.51.0
     */
    public function elimina_bd(int $id): array|stdClass
    {
        if($id <= 0){
            return  $this->error->error(mensaje: 'El id no puede ser menor a 0 en '.$this->tabla, data: $id);
        }

        $filtro['inm_comprador.id'] = $id;
        $del = (new inm_rel_comprador_com_cliente(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_rel_comprador_com_cliente',
                data:  $del);
        }
        $del = (new inm_comprador_etapa(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_comprador_etapa',
                data:  $del);
        }
        $del = (new inm_referencia(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_referencia',
                data:  $del);
        }
        $del = (new inm_rel_co_acred(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_rel_co_acred',
                data:  $del);
        }
        $del = (new inm_rel_ubi_comp(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_rel_ubi_comp',
                data:  $del);
        }
        $del = (new inm_comprador_proceso(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_comprador_proceso',
                data:  $del);
        }
        $del = (new inm_rel_comprador_prospecto(link: $this->link))->elimina_con_filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar inm_rel_prospecto_cliente',
                data:  $del);
        }

        $r_elimina_bd = parent::elimina_bd(id: $id); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar registro de comprador',data:  $r_elimina_bd);
        }
        return $r_elimina_bd;
    }

    final public function existe_co_acreditado(int $inm_comprador_id): bool|array
    {
        if($inm_comprador_id <=0){
            return $this->error->error(mensaje: 'Error inm_comprador_id es menor a 0',data:  $inm_comprador_id);
        }
        $filtro = array();
        $filtro['inm_comprador.id'] = $inm_comprador_id;

        $existe_co_acreditado = (new inm_rel_co_acred(link: $this->link))->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe co_acreditado',data:  $existe_co_acreditado);
        }
        return $existe_co_acreditado;
    }

    final public function existe_conyuge(int $inm_comprador_id): bool|array
    {
        if($inm_comprador_id <=0){
            return $this->error->error(mensaje: 'Error inm_comprador_id es menor a 0',data:  $inm_comprador_id);
        }
        $filtro = array();
        $filtro['inm_comprador.id'] = $inm_comprador_id;

        $existe_conyuge = (new inm_rel_conyuge_comprador(link: $this->link))->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe conyuge',data:  $existe_conyuge);
        }
        return $existe_conyuge;
    }

    /**
     * Obtiene los datos del cliente de fc basados en el comprador
     * @param int $inm_comprador_id Comprador id
     * @param bool $columnas_en_bruto
     * @param bool $retorno_obj Retorna un objeto en caso de ser true
     * @return array|object
     */
    final public function get_com_cliente(int $inm_comprador_id, bool $columnas_en_bruto = false,
                                          bool $retorno_obj = false): object|array
    {
        if($inm_comprador_id<=0){
            return $this->error->error(mensaje: 'Error inm_comprador_id es menor a 0',data:  $inm_comprador_id);
        }
        $imp_rel_comprador_com_cliente = (new _base_comprador())->inm_rel_comprador_cliente(
            inm_comprador_id: $inm_comprador_id, link: $this->link, columnas_en_bruto: $columnas_en_bruto);
        if(errores::$error){
            return $this->error->error(
                mensaje: 'Error al obtener imp_rel_comprador_com_cliente',data:  $imp_rel_comprador_com_cliente);
        }

        /*$com_cliente = (new _base_comprador())->com_cliente(com_cliente_id: $imp_rel_comprador_com_cliente['com_cliente_id'],
            link: $this->link, columnas_en_bruto: $columnas_en_bruto, retorno_obj: $retorno_obj);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener com_cliente',data:  $com_cliente);
        }*/
        return $imp_rel_comprador_com_cliente;
    }

    /**
     * Obtiene los co acreditados de un comprador
     * @param int $inm_comprador_id Identificador de comprador
     * @return array
     * @version 2.52.0
     */
    final public function get_co_acreditados(int $inm_comprador_id): array
    {
        if($inm_comprador_id <= 0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0',data:  $inm_comprador_id);
        }
        $filtro['inm_comprador.id'] = $inm_comprador_id;
        $r_inm_rel_co_acredit = (new inm_rel_co_acred(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_rel_co_acredit',data:  $r_inm_rel_co_acredit);
        }
        $rels = $r_inm_rel_co_acredit->registros;
        $co_acreditados = array();
        foreach ($rels as $rel){
            $co_acreditado = (new inm_co_acreditado(link: $this->link))->registro(
                registro_id: $rel['inm_co_acreditado_id'],columnas_en_bruto: true);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener co_acreditado',data:  $co_acreditado);
            }
            $co_acreditados[] = $co_acreditado;
        }
        return $co_acreditados;

    }

    /**
     * Obtiene las referencias de un comprador
     * @param int $inm_comprador_id Comprador id
     * @return array
     * @version 2.53.0
     */
    final public function get_referencias(int $inm_comprador_id): array
    {
        if($inm_comprador_id <= 0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0',data:  $inm_comprador_id);
        }

        $filtro['inm_comprador.id'] = $inm_comprador_id;
        $r_inm_referencia = (new inm_referencia(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener r_inm_referencia',data:  $r_inm_referencia);
        }

        return $r_inm_referencia->registros;

    }

    private function init_post(string $key_data): array
    {
        $key_data = trim($key_data);
        if($key_data === ''){
            return $this->error->error(mensaje: 'Error key_data esta vacio',data:  $key_data);
        }
        $data = array();
        if(isset($_POST[$key_data])){
            $data = $_POST[$key_data];
            if(is_string($data)){
                return $this->error->error(mensaje: 'Error POST '.$key_data.' debe ser un array',data:  $data);
            }
            unset($_POST[$key_data]);
        }
        return $data;
    }

    private function inserta_beneficiario(array $beneficiario, int $inm_comprador_id, PDO $link): array|stdClass
    {
        $keys = array('nombre','apellido_paterno');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $beneficiario);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar beneficiario',data:  $valida);
        }

        if($inm_comprador_id <= 0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0',data:  $inm_comprador_id);
        }

        $alta_beneficiario = (new inm_beneficiario(link: $link))->alta_registro(registro: $beneficiario);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar beneficiario', data: $alta_beneficiario);
        }

        $inm_rel_beneficiario_ins['inm_comprador_id'] = $inm_comprador_id;
        $inm_rel_beneficiario_ins['inm_beneficiario_id'] = $alta_beneficiario->registro_id;

        $r_inm_rel_beneficiario_bd = (new inm_rel_beneficiario_comprador(link: $link))->alta_registro(
            registro: $inm_rel_beneficiario_ins);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar beneficiario', data: $r_inm_rel_beneficiario_bd);
        }

        $data = new stdClass();
        $data->alta_beneficiario = $alta_beneficiario;
        $data->inm_rel_beneficiario_ins = $inm_rel_beneficiario_ins;
        $data->r_inm_rel_beneficiario_bd = $r_inm_rel_beneficiario_bd;

        return $data;
    }

    private function inserta_co_acreditado(array $co_acreditado, int $inm_comprador_id, PDO $link): array|stdClass
    {
        $keys = array('nombre','apellido_paterno');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $co_acreditado);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar co_acreditado',data:  $valida);
        }

        if($inm_comprador_id <= 0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0',data:  $inm_comprador_id);
        }

        $alta_co_acreditado = (new inm_co_acreditado(link: $link))->alta_registro(registro: $co_acreditado);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar co_acreditado', data: $alta_co_acreditado);
        }

        $inm_rel_co_acred_ins['inm_comprador_id'] = $inm_comprador_id;
        $inm_rel_co_acred_ins['inm_co_acreditado_id'] = $alta_co_acreditado->registro_id;

        $r_inm_rel_co_acred_bd = (new inm_rel_co_acred(link: $link))->alta_registro(
            registro: $inm_rel_co_acred_ins);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar co_acreditado', data: $r_inm_rel_co_acred_bd);
        }

        $data = new stdClass();
        $data->alta_co_acreditado = $alta_co_acreditado;
        $data->inm_rel_co_acred_ins = $inm_rel_co_acred_ins;
        $data->r_inm_rel_co_acred_bd = $r_inm_rel_co_acred_bd;

        return $data;
    }
    
    private function inserta_conyuge(array $conyuge, int $inm_comprador_id, PDO $link): array|stdClass
    {
        $keys = array('nombre','apellido_paterno');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $conyuge);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar conyuge',data:  $valida);
        }

        if($inm_comprador_id <= 0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0',data:  $inm_comprador_id);
        }

        $alta_conyuge = (new inm_conyuge(link: $link))->alta_registro(registro: $conyuge);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar conyuge', data: $alta_conyuge);
        }

        $inm_rel_conyuge_ins['inm_comprador_id'] = $inm_comprador_id;
        $inm_rel_conyuge_ins['inm_conyuge_id'] = $alta_conyuge->registro_id;

        $r_inm_rel_conyuge_bd = (new inm_rel_conyuge_comprador(link: $link))->alta_registro(
            registro: $inm_rel_conyuge_ins);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar conyuge', data: $r_inm_rel_conyuge_bd);
        }

        $data = new stdClass();
        $data->alta_conyuge = $alta_conyuge;
        $data->inm_rel_conyuge_ins = $inm_rel_conyuge_ins;
        $data->r_inm_rel_conyuge_bd = $r_inm_rel_conyuge_bd;

        return $data;
    }


    private function inserta_referencia(array $referencia, int $inm_comprador_id, PDO $link): array|stdClass
    {
        $keys = array('nombre','apellido_paterno');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $referencia);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar referencia',data:  $valida);
        }

        if($inm_comprador_id <= 0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0',data:  $inm_comprador_id);
        }

        $alta_referencia = (new inm_referencia(link: $link))->alta_registro(registro: $referencia);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar referencia', data: $alta_referencia);
        }

        $inm_rel_referencia_ins['inm_comprador_id'] = $inm_comprador_id;
        $inm_rel_referencia_ins['inm_referencia_id'] = $alta_referencia->registro_id;

        $r_inm_rel_referencia_bd = (new inm_rel_referencia_comprador(link: $link))->alta_registro(
            registro: $inm_rel_referencia_ins);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar referencia', data: $r_inm_rel_referencia_bd);
        }

        $data = new stdClass();
        $data->alta_referencia = $alta_referencia;
        $data->inm_rel_referencia_ins = $inm_rel_referencia_ins;
        $data->r_inm_rel_referencia_bd = $r_inm_rel_referencia_bd;

        return $data;
    }

    final public function inm_co_acreditado(bool $columnas_en_bruto, int $inm_comprador_id, PDO $link,
                                            bool $retorno_obj): array|stdClass
    {
        if($inm_comprador_id<=0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0', data:  $inm_comprador_id);
        }
        $filtro = array();
        $filtro['inm_comprador.id'] = $inm_comprador_id;
        $r_inm_rel_co_acred = (new inm_rel_co_acred(link: $link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener co_acreditado relacion',
                data:  $r_inm_rel_co_acred);
        }
        if($r_inm_rel_co_acred->n_registros === 0){
            return $this->error->error(mensaje: 'Error no existe relacion',data:  $r_inm_rel_co_acred);
        }
        if($r_inm_rel_co_acred->n_registros > 1){
            return $this->error->error(mensaje: 'Error de integridad',data:  $r_inm_rel_co_acred);
        }

        $inm_rel_co_acred = $r_inm_rel_co_acred->registros[0];

        $inm_co_acreditado = (new inm_co_acreditado(link: $link))->registro(
            registro_id: $inm_rel_co_acred['inm_co_acreditado_id'],columnas_en_bruto: $columnas_en_bruto,
            retorno_obj: $retorno_obj);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener co_acreditado',data:  $inm_co_acreditado);
        }

        return $inm_co_acreditado;
    }

    final public function inm_conyuge(bool $columnas_en_bruto, int $inm_comprador_id, PDO $link,
                                            bool $retorno_obj): array|stdClass
    {
        if($inm_comprador_id<=0){
            return $this->error->error(mensaje: 'Error inm_comprador_id debe ser mayor a 0', data:  $inm_comprador_id);
        }
        $filtro = array();
        $filtro['inm_comprador.id'] = $inm_comprador_id;
        $r_inm_rel_conyuge = (new inm_rel_conyuge_comprador(link: $link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener conyuge relacion',
                data:  $r_inm_rel_conyuge);
        }
        if($r_inm_rel_conyuge->n_registros === 0){
            return $this->error->error(mensaje: 'Error no existe relacion',data:  $r_inm_rel_conyuge);
        }
        if($r_inm_rel_conyuge->n_registros > 1){
            return $this->error->error(mensaje: 'Error de integridad',data:  $r_inm_rel_conyuge);
        }

        $inm_rel_conyuge = $r_inm_rel_conyuge->registros[0];

        $inm_conyuge = (new inm_conyuge(link: $link))->registro(
            registro_id: $inm_rel_conyuge['inm_conyuge_id'],columnas_en_bruto: $columnas_en_bruto,
            retorno_obj: $retorno_obj);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener conyuge',data:  $inm_conyuge);
        }

        return $inm_conyuge;
    }

    final public function inm_prospecto(int $inm_comprador_id){

        $filtro['inm_comprador.id'] = $inm_comprador_id;
        $r_inm_rel_prospecto_cliente = (new inm_rel_comprador_prospecto(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener r_inm_rel_prospecto_cliente',
                data:  $r_inm_rel_prospecto_cliente);
        }
        if($r_inm_rel_prospecto_cliente->n_registros === 0){
            return $this->error->error(mensaje: 'Error al no existe prospecto relacionado',
                data:  $r_inm_rel_prospecto_cliente);
        }
        if($r_inm_rel_prospecto_cliente->n_registros > 1){
            return $this->error->error(mensaje: 'Error de integridad prospecto relacionado',
                data:  $r_inm_rel_prospecto_cliente);
        }

        $inm_rel_prospecto_cliente = $r_inm_rel_prospecto_cliente->registros[0];

        $inm_prospecto = (new inm_prospecto(link: $this->link))->registro(
            registro_id: $inm_rel_prospecto_cliente['inm_prospecto_id'],retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_prospecto', data:  $inm_prospecto);
        }

        return $inm_prospecto;

    }

    private function modifica_co_acreditado(array $co_acreditado, int $inm_comprador_id, PDO $link): array|stdClass
    {
        if($inm_comprador_id<=0){
            return $this->error->error(mensaje: 'Error inm__id debe ser mayor a 0', data:  $inm_comprador_id);
        }
        $inm_co_acreditado_previo = $this->inm_co_acreditado(columnas_en_bruto: true, inm_comprador_id: $inm_comprador_id,
            link: $link, retorno_obj: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener co_acreditado', data: $inm_co_acreditado_previo);
        }

        $inm_co_acreditado_id = $inm_co_acreditado_previo->id;

        $r_modifica_co_acreditado = (new inm_co_acreditado(link: $link))->modifica_bd(registro: $co_acreditado,id: $inm_co_acreditado_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al modificar co_acreditado', data: $r_modifica_co_acreditado);
        }

        $data = new stdClass();
        $data->inm_co_acreditado_previo = $inm_co_acreditado_previo;
        $data->r_modifica_co_acreditado = $r_modifica_co_acreditado;

        return $data;
    }

    private function modifica_conyuge(array $conyuge, int $inm_comprador_id, PDO $link): array|stdClass
    {
        if($inm_comprador_id<=0){
            return $this->error->error(mensaje: 'Error inm__id debe ser mayor a 0', data:  $inm_comprador_id);
        }
        $inm_conyuge_previo = $this->inm_conyuge(columnas_en_bruto: true, inm_comprador_id: $inm_comprador_id,
            link: $link, retorno_obj: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener conyuge', data: $inm_conyuge_previo);
        }

        $inm_conyuge_id = $inm_conyuge_previo->id;

        $r_modifica_conyuge = (new inm_conyuge(link: $link))->modifica_bd(registro: $conyuge,id: $inm_conyuge_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al modificar conyuge', data: $r_modifica_conyuge);
        }

        $data = new stdClass();
        $data->inm_conyuge_previo = $inm_conyuge_previo;
        $data->r_modifica_conyuge = $r_modifica_conyuge;

        return $data;
    }


    /**
     * Modifica un registro de tipo comprador, ademas inserta si existe un co acreditado, la relacion y si existe
     * referencia inserta o modifica
     * @param array $registro Registro en proceso
     * @param int $id Id de comprador
     * @param bool $reactiva si reactiva se brinca validaciones de cancelacion
     * @param array $keys_integra_ds campos para generar una descripcion select
     * @return array|stdClass
     */
    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {

        if($id<=0){
            return $this->error->error(mensaje: 'Error id debe ser mayor a 0',data: $registro);
        }

        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->existe_status_comprador(
            inm_comprador_id: $id, values: array('11'));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener bitacora status comp',data:  $inm_bit_comp);
        }

        if ($inm_bit_comp->n_registros > 0) {
            return $this->error->error(mensaje: 'Error el cliente ya esta cancelado',data:  $inm_bit_comp);
        }

        $r_modifica = parent::modifica_bd(registro: $registro,id:  $id, reactiva: $reactiva,
            keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar comprador',data:  $r_modifica);
        }

        $transacciones = (new _base_comprador())->transacciones_posterior_upd(inm_comprador_upd: $registro,
            inm_comprador_id:  $id,modelo_inm_comprador:  $this,r_modifica:  $r_modifica);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar transacciones',data:  $transacciones);
        }

        return $r_modifica;
    }

    /**
     * Transacciona un elemento para su actualizacion posterior
     * @param stdClass $data_upd Datos de actualizacion
     * @param int $id Id de comprador
     * @return array|stdClass
     */
    private function r_modifica_post(stdClass $data_upd, int $id): array|stdClass
    {
        if($id <=0){
            return  $this->error->error(mensaje: 'Error al obtener registro id debe ser mayor a 0', data: $id);
        }

        $registro_previo = $this->registro(registro_id: $id,columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro_previo',data:  $registro_previo);
        }
        if(!isset($data_upd->row_upd_post['descripcion'])){
            $data_upd->row_upd_post['descripcion'] = $registro_previo->descripcion;
        }

        $r_modifica_post = parent::modifica_bd(registro: $data_upd->row_upd_post,id:  $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar comprador',data:  $r_modifica_post);
        }
        return $r_modifica_post;
    }

    /**
     * Integra el resultado post upd si es que aplica cambio
     * @param stdClass $data_upd Datos de upd
     * @param int $id Id de comprador
     * @return array|stdClass
     */
    private function result_upd_post(stdClass $data_upd, int $id): array|stdClass
    {
        if($id <= 0){
            return $this->error->error(mensaje: 'Error id debe ser mayor a 0',data:  $id);
        }
        if(!isset($data_upd->aplica_upd_posterior)){
            return $this->error->error(mensaje: 'Error $data_upd->aplica_upd_posterior no existe',data:  $data_upd);
        }
        $r_modifica_post = new stdClass();
        if($data_upd->aplica_upd_posterior){

            $r_modifica_post = $this->r_modifica_post(data_upd: $data_upd,id:  $id);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al modificar comprador',data:  $r_modifica_post);
            }
        }
        return $r_modifica_post;
    }

    public function status_comprador(int $inm_comprador_id,
                                     array $order = array('inm_bitacora_status_comprador.fecha_status'=>'DESC')){
        $filtro['inm_comprador.id'] = $inm_comprador_id;
        $r_inm_bitacora_comprador = (new inm_bitacora_status_comprador(link: $this->link))->filtro_and(filtro: $filtro,order: $order);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener etapas', data: $r_inm_bitacora_comprador);
        }

        return $r_inm_bitacora_comprador->registros;
    }

    final public function transacciona_beneficiario(int $inm_comprador_id, PDO $link){
        $datos = $this->dato(existe: false,key_data:  'beneficiario');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos',data:  $datos);
        }

        if($datos->tiene_dato){
            $result_beneficiario = $this->ajusta_beneficiario(datos: $datos,inm_comprador_id: $inm_comprador_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar beneficiario', data: $result_beneficiario);
            }
            $datos->result_beneficiario = $result_beneficiario;
        }

        return $datos;
    }
    
    public function transacciona_co_acreditado(int $inm_comprador_id, PDO $link){
        $datos = $this->datos_co_acreditado(link: $link,inm_comprador_id: $inm_comprador_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener dato co_acreditado',data:  $datos);
        }

        if($datos->tiene_dato){
            $result_co_acreditado = $this->ajusta_co_acreditado(datos: $datos,inm_comprador_id: $inm_comprador_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar co_acreditado', data: $result_co_acreditado);
            }
            $datos->result_co_acreditado = $result_co_acreditado;
        }
        return $datos;
    }

    public function transacciona_conyuge(int $inm_comprador_id, PDO $link){
        $datos = $this->datos_conyuge(link: $link,inm_comprador_id: $inm_comprador_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener dato conyuge',data:  $datos);
        }

        if($datos->tiene_dato){
            $result_conyuge = $this->ajusta_conyuge(datos: $datos,inm_comprador_id: $inm_comprador_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar conyuge', data: $result_conyuge);
            }
            $datos->result_conyuge = $result_conyuge;
        }
        return $datos;
    }

    final public function transacciona_referencia(int $inm_comprador_id, PDO $link){
        $datos = $this->dato(existe: false,key_data:  'referencia');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos',data:  $datos);
        }

        if($datos->tiene_dato){
            $result_referencia = $this->ajusta_referencia(datos: $datos,inm_comprador_id: $inm_comprador_id,link: $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar referencia', data: $result_referencia);
            }
            $datos->result_referencia = $result_referencia;
        }
        
        return $datos;
    }
    
    final public function tiene_cliente(int $inm_comprador_id):bool
    {
        $filtro['inm_comprador.id'] = $inm_comprador_id;
        $existe = (new inm_rel_comprador_com_cliente(link: $this->link))->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe cliente',data:  $existe);
        }
        return $existe;

    }

    private function tiene_dato(array $row): bool
    {
        $tiene_dato = false;
        foreach ($row as $key => $value){
            if($key === 'genero'){
                $value = '';
            }
            if($value === null){
                $value = '';
            }
            $value = trim($value);
            if($value!==''){
                $tiene_dato = true;
                break;
            }
        }
        return $tiene_dato;
    }


    final public function tiene_prospecto(int $inm_comprador_id){
        $filtro['inm_comprador.id'] = $inm_comprador_id;
        $existe = (new inm_rel_comprador_prospecto(link: $this->link))->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar si existe prospecto',data:  $existe);
        }

        return $existe;
    }

    /**
     * Ejecuta transacciones posteriores a la actualizacion de un comprador
     * @param int $id Id de comprador
     * @param stdClass $r_modifica resultado de modificacion
     * @return array|stdClass
     */
    final public function upd_post(int $id, stdClass $r_modifica): array|stdClass
    {
        $valida = (new _base_comprador())->valida_r_modifica(r_modifica: $r_modifica);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar r_modifica', data: $valida);
        }
        if($id<=0){
            return $this->error->error(mensaje: 'Error id debe ser mayor a 0',data:  $id);
        }

        $data_upd = (new _base_comprador())->data_upd_post(r_modifica: $r_modifica);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener datos',data:  $data_upd);
        }

        $r_modifica_post = $this->result_upd_post(data_upd: $data_upd, id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar comprador',data:  $r_modifica_post);
        }

        $r_modifica_post->data_upd = $data_upd;
        return $r_modifica_post;
    }

}