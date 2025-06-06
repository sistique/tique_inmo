<?php
namespace gamboamartin\inmuebles\models;
use gamboamartin\comercial\models\com_agente;
use gamboamartin\comercial\models\com_cliente;
use gamboamartin\comercial\models\com_medio_prospeccion;
use gamboamartin\comercial\models\com_tipo_agente;
use gamboamartin\comercial\models\com_tipo_prospecto;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use PDO;
use stdClass;

class _conversion_ubicacion{

    private errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    private function data_ubicacion(int $inm_ubicacion_id, inm_ubicacion $modelo): array|stdClass
    {
        if($inm_ubicacion_id<=0){
            return $this->error->error(mensaje: 'Error inm_ubicacion_id es menor a 0', data: $inm_ubicacion_id);
        }

        $inm_ubicacion = $modelo->registro(registro_id: $inm_ubicacion_id, columnas_en_bruto: true, retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener ubicacion', data: $inm_ubicacion);
        }

        $data = new stdClass();
        $data->inm_ubicacion = $inm_ubicacion;

        return $data;
    }

    private function data_prospecto_ubicacion(int $inm_prospecto_ubicacion_id, inm_prospecto_ubicacion $modelo): array|stdClass
    {
        if($inm_prospecto_ubicacion_id<=0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id es menor a 0', data: $inm_prospecto_ubicacion_id);
        }

        $inm_prospecto_ubicacion = $modelo->registro(registro_id: $inm_prospecto_ubicacion_id, columnas_en_bruto: true,
            retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener prospecto_ubicacion', data: $inm_prospecto_ubicacion);
        }

        $inm_prospecto_ubicacion_completo = $modelo->registro(registro_id: $inm_prospecto_ubicacion_id, retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener prospecto_ubicacion', data: $inm_prospecto_ubicacion);
        }
        $data = new stdClass();
        $data->inm_prospecto_ubicacion = $inm_prospecto_ubicacion;
        $data->inm_prospecto_ubicacion_completo = $inm_prospecto_ubicacion_completo;

        return $data;
    }
    /**
     * Campos de inicializacion
     * @param array $inm_ubicacion_ins ubicacion registro
     * @return array
     */
    private function defaults_alta_ubicacion(array $inm_ubicacion_ins): array
    {

        return $inm_ubicacion_ins;
    }

    /**
     * Integra los campos de un ubicacion para la insersion
     * @param stdClass $data Datos de prospecto
     * @param PDO $link Conexion a la base de datos
     * @return array
     */
    private function inm_ubicacion_ins(stdClass $data, PDO $link): array
    {
        if(!isset($data->inm_prospecto_ubicacion)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto no existe', data: $data);
        }
        if(!is_object($data->inm_prospecto_ubicacion)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto debe ser un objeto', data: $data);
        }
        if(!isset($data->inm_prospecto_ubicacion_completo)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto_completo no existe', data: $data);
        }
        if(!is_object($data->inm_prospecto_ubicacion_completo)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto_completo debe ser un objeto', data: $data);
        }

        $keys = $this->keys_data_prospecto_ubicacion();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener keys', data: $keys);
        }

        $inm_ubicacion_ins = $this->inm_ubicacion_ins_init(data: $data,keys:  $keys);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar inm_ubicacion', data: $inm_ubicacion_ins);
        }

        $inm_ubicacion_ins = $this->defaults_alta_ubicacion(inm_ubicacion_ins: $inm_ubicacion_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_ubicacion_ins', data: $inm_ubicacion_ins);
        }

        if(!isset($inm_ubicacion_ins['inm_tipo_ubicacion_id'])){
            $inm_ubicacion_ins['inm_tipo_ubicacion_id'] = '1';
        }

        $keys_necesarios = array('dp_colonia_postal_id','inm_tipo_ubicacion_id');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys_necesarios,registro:  $inm_ubicacion_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar row upd',data:  $valida);
        }

        return $inm_ubicacion_ins;
    }


    /**
     * Inicializa inm_ubicacion en vacio
     * @param stdClass $data datos para asignacion
     * @param array $keys Keys para inicializar
     * @return array
     */
    private function inm_ubicacion_ins_init(stdClass $data, array $keys): array
    {
        if(!isset($data->inm_prospecto_ubicacion)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto no existe', data: $data);
        }
        if(!is_object($data->inm_prospecto_ubicacion)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto debe ser un objeto', data: $data);
        }
        $inm_ubicacion_ins = array();

        foreach ($keys as $key){
            $inm_ubicacion_ins = $this->integra_key(data: $data,inm_ubicacion_ins:  $inm_ubicacion_ins,key:  $key);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar key', data: $inm_ubicacion_ins);
            }
        }

        return $inm_ubicacion_ins;
    }

    private function inm_referencia(int $inm_ubicacion_id, stdClass $inm_referencia_prospecto): array
    {
        if($inm_ubicacion_id <= 0){
            return $this->error->error(mensaje: 'Error inm_ubicacion_id debe ser mayor a 0', data: $inm_ubicacion_id);
        }

        $inm_referencia_ins['inm_ubicacion_id'] = $inm_ubicacion_id;
        $inm_referencia_ins['apellido_paterno'] = $inm_referencia_prospecto->inm_referencia_prospecto_apellido_paterno;
        $inm_referencia_ins['apellido_materno'] = $inm_referencia_prospecto->inm_referencia_prospecto_apellido_materno;
        $inm_referencia_ins['nombre'] = $inm_referencia_prospecto->inm_referencia_prospecto_nombre;
        $inm_referencia_ins['lada'] = $inm_referencia_prospecto->inm_referencia_prospecto_lada;
        $inm_referencia_ins['numero'] = $inm_referencia_prospecto->inm_referencia_prospecto_numero;
        $inm_referencia_ins['celular'] = $inm_referencia_prospecto->inm_referencia_prospecto_celular;
        $inm_referencia_ins['dp_calle_pertenece_id'] = $inm_referencia_prospecto->inm_referencia_prospecto_dp_calle_pertenece_id;
        $inm_referencia_ins['inm_parentesco_id'] = $inm_referencia_prospecto->inm_referencia_prospecto_inm_parentesco_id;
        $inm_referencia_ins['numero_dom'] = $inm_referencia_prospecto->inm_referencia_prospecto_numero_dom;

        return $inm_referencia_ins;
    }
    
    private function inm_rel_ubicacion_prospecto_ubicacion_ins(int $inm_ubicacion_id, int $inm_prospecto_ubicacion_id): array
    {
        if($inm_prospecto_ubicacion_id <= 0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id debe ser mayor a 0', data: $inm_prospecto_ubicacion_id);
        }
        if($inm_ubicacion_id <= 0){
            return $this->error->error(mensaje: 'Error inm_ubicacion_id debe ser mayor a 0', data: $inm_ubicacion_id);
        }
        $inm_rel_prospecto_cliente_ins['inm_prospecto_ubicacion_id'] = $inm_prospecto_ubicacion_id;
        $inm_rel_prospecto_cliente_ins['inm_ubicacion_id'] = $inm_ubicacion_id;

        return $inm_rel_prospecto_cliente_ins;
    }

    
    final public function inserta_inm_ubicacion(int $inm_prospecto_ubicacion_id, inm_prospecto_ubicacion $modelo): array|stdClass
    {
        if($inm_prospecto_ubicacion_id<=0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id es menor a 0', data: $inm_prospecto_ubicacion_id);
        }

        $data = $this->data_prospecto_ubicacion(inm_prospecto_ubicacion_id: $inm_prospecto_ubicacion_id,
            modelo: $modelo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener prospecto', data: $data);
        }

        $inm_ubicacion_ins = $this->inm_ubicacion_ins(data: $data,link: $modelo->link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener id_pref', data: $inm_ubicacion_ins);
        }

        $inm_ubicacion_modelo = new inm_ubicacion(link: $modelo->link);
        $inm_ubicacion_modelo->desde_prospecto = true;
        $r_alta_ubicacion = $inm_ubicacion_modelo->alta_registro(registro: $inm_ubicacion_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente', data: $r_alta_ubicacion);
        }

        return $r_alta_ubicacion;
    }

    public function inserta_inm_prospecto_ubicacion(int $inm_ubicacion_id, inm_ubicacion $modelo): array|stdClass
    {
        if($inm_ubicacion_id<=0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id es menor a 0', data: $inm_ubicacion_id);
        }

        $data = $this->data_ubicacion(inm_ubicacion_id: $inm_ubicacion_id,modelo: $modelo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener prospecto', data: $data);
        }

        $inm_prospecto_ubicacion_ins = $this->inm_prospecto_ubicacion_ins(data: $data,link: $modelo->link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener id_pref', data: $inm_prospecto_ubicacion_ins);
        }

        $modelo_inm_prospecto_ubicacion = new inm_prospecto_ubicacion(link: $modelo->link);
        $modelo_inm_prospecto_ubicacion->viene_ubicacion = true;
        $r_alta_prospecto = $modelo_inm_prospecto_ubicacion->alta_registro(registro: $inm_prospecto_ubicacion_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente', data: $r_alta_prospecto);
        }

        return $r_alta_prospecto;
    }

    private function inm_prospecto_ubicacion_ins(stdClass $data, PDO $link): array
    {
        if(!isset($data->inm_ubicacion)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto no existe', data: $data);
        }
        if(!is_object($data->inm_ubicacion)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto debe ser un objeto', data: $data);
        }

        $keys = $this->keys_data_ubicacion();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener keys', data: $keys);
        }

        $inm_prospecto_ins = $this->inm_prospecto_ins_init(data: $data,keys:  $keys);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar inm_prospecto', data: $inm_prospecto_ins);
        }

        return $inm_prospecto_ins;
    }

    private function inm_prospecto_ins_init(stdClass $data, array $keys): array
    {
        if(!isset($data->inm_ubicacion)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto no existe', data: $data);
        }
        if(!is_object($data->inm_ubicacion)){
            return $this->error->error(mensaje: 'Error $data->inm_prospecto debe ser un objeto', data: $data);
        }

        $inm_prospecto_ins = array();
        foreach ($keys as $key){
            $key = trim($key);
            if($key === ''){
                return $this->error->error(mensaje: 'Error key esta vacio', data: $key);
            }

            if(!property_exists($data->inm_ubicacion, $key)){
                return $this->error->error(mensaje: 'Error no existe atributo '.$key, data: $data->inm_ubicacion);
            }

            if(is_numeric($key)){
                return $this->error->error(mensaje: 'Error key debe ser un texto', data: $key);
            }

            if(is_null($data->inm_ubicacion->$key)){
                $data->inm_ubicacion->$key = '';
            }

            $inm_prospecto_ins[$key] = $data->inm_ubicacion->$key;
        }
        return $inm_prospecto_ins;
    }

    public function inserta_referencia(int $inm_ubicacion_id, int $inm_prospecto_id, PDO $link): array|stdClass
    {
        if ($inm_prospecto_id <= 0) {
            return $this->error->error(mensaje: 'Error inm_prospecto_id debe ser mayor a 0', data: $inm_prospecto_id);
        }
        if ($inm_ubicacion_id <= 0) {
            return $this->error->error(mensaje: 'Error inm_ubicacion_id debe ser mayor a 0', data: $inm_ubicacion_id);
        }

        $filtro['inm_prospecto.id'] = $inm_prospecto_id;
        $inm_referencia_prospecto = (new inm_referencia_prospecto(link: $link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener prospecto', data: $inm_referencia_prospecto);
        }

        $r_alta_rels = array();
        if($inm_referencia_prospecto->n_registros > 0){
            foreach ($inm_referencia_prospecto->registros_obj as $registro){
                $inm_referencia_ins = $this->inm_referencia(
                    inm_ubicacion_id: $inm_ubicacion_id, inm_referencia_prospecto: $registro);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar relacion', data: $inm_referencia_ins);
                }

                $r_alta_rel = (new inm_referencia(link: $link))->alta_registro(registro: $inm_referencia_ins);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al insertar inm_rel_prospecto_cliente_ins', data: $r_alta_rel);
                }
                $r_alta_rels[] = $r_alta_rel;
            }
        }
        return $r_alta_rels;
    }

    final public function inserta_rel_ubicacion_prospecto_ubicacion(
        int $inm_ubicacion_id, int $inm_prospecto_ubicacion_id, PDO $link): array|stdClass
    {
        if($inm_prospecto_ubicacion_id <= 0){
            return $this->error->error(mensaje: 'Error inm_prospecto_id debe ser mayor a 0', data: $inm_prospecto_ubicacion_id);
        }
        if($inm_ubicacion_id <= 0){
            return $this->error->error(mensaje: 'Error inm_ubicacion_id debe ser mayor a 0', data: $inm_ubicacion_id);
        }

        $inm_rel_ubicacion_prospecto_ubicacione_ins = $this->inm_rel_ubicacion_prospecto_ubicacion_ins(
            inm_ubicacion_id: $inm_ubicacion_id,inm_prospecto_ubicacion_id:  $inm_prospecto_ubicacion_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar relacion', data: $inm_rel_ubicacion_prospecto_ubicacione_ins);
        }

        $r_alta_rel = (new inm_rel_ubicacion_prospecto_ubicacion(link: $link))->alta_registro(
            registro: $inm_rel_ubicacion_prospecto_ubicacione_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar inm_rel_prospecto_cliente_ins', data: $r_alta_rel);
        }

        return $r_alta_rel;
    }

    /**
     * Integra un identificador de uso comun
     * @param string $entidad Entidad para obtener identificador
     * @param array $inm_ubicacion_ins Registro de ubicacion
     * @param inm_ubicacion|com_cliente $modelo Modelo de integracion
     * @return array
     */
    private function integra_id_pref(string $entidad, array $inm_ubicacion_ins,
                                     inm_ubicacion|com_cliente $modelo): array
    {
        $entidad = trim($entidad);
        if($entidad === ''){
            return $this->error->error(mensaje: 'Error entidad esta vacia', data: $entidad);
        }
        $key_id = $entidad.'_id';
        $id_pref = $modelo->id_preferido_detalle(entidad_preferida: $entidad);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener id_pref', data: $id_pref);
        }
        $inm_ubicacion_ins[$key_id] = $id_pref;
        return $inm_ubicacion_ins;
    }

    /**
     * Integra los identificadores mas usados
     * @param array $inm_ubicacion_ins Registro de ubicacion
     * @param PDO $link Conexion a la base de datos
     * @return array
     */
    private function integra_ids_prefs(array $inm_ubicacion_ins, PDO $link): array
    {
        $entidades_pref = array('bn_cuenta');

        $modelo_inm_ubicacion = new inm_ubicacion(link: $link);

        foreach ($entidades_pref as $entidad){
            $inm_ubicacion_ins = $this->integra_id_pref(entidad: $entidad, inm_ubicacion_ins:  $inm_ubicacion_ins,
                modelo: $modelo_inm_ubicacion);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener id_pref', data: $inm_ubicacion_ins);
            }
        }

        $entidades_pref = array('cat_sat_regimen_fiscal','cat_sat_moneda', 'cat_sat_forma_pago',
            'cat_sat_metodo_pago','cat_sat_uso_cfdi','com_tipo_cliente','cat_sat_tipo_persona');

        $modelo_com_cliente = new com_cliente(link: $link);
        foreach ($entidades_pref as $entidad){
            $inm_ubicacion_ins = $this->integra_id_pref(entidad: $entidad, inm_ubicacion_ins:  $inm_ubicacion_ins,
                modelo: $modelo_com_cliente);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener id_pref', data: $inm_ubicacion_ins);
            }
        }
        return $inm_ubicacion_ins;
    }


    /**
     * @param stdClass $data
     * @param array $inm_ubicacion_ins
     * @param string $key
     * @return array
     */
    private function integra_key(stdClass $data, array $inm_ubicacion_ins, string $key): array
    {
        $key = trim($key);
        $valida = $this->valida_key(key: $key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar key', data: $valida);
        }
        if(!property_exists($data->inm_prospecto_ubicacion, $key)){
            return $this->error->error(mensaje: 'Error no existe atributo', data: $key);
        }

        if(is_null($data->inm_prospecto_ubicacion->$key)){
            $data->inm_prospecto_ubicacion->$key = '';
        }

        $inm_ubicacion_ins[$key] = $data->inm_prospecto_ubicacion->$key;
        return $inm_ubicacion_ins;
    }


    /**
     * @return string[]
     */
    private function keys_data_ubicacion(): array
    {
        return array('calle', 'dp_colonia_postal_id', 'lote', 'manzana', 'costo_directo', 'numero_exterior',
            'numero_interior', 'etapa', 'cuenta_predial', 'inm_tipo_ubicacion_id', 'n_opiniones_valor',
            'monto_opinion_promedio', 'costo', 'inm_status_ubicacion_id', 'com_agente_id', 'nss', 'curp', 'nombre',
            'apellido_paterno', 'apellido_materno', 'nombre_completo_valida', 'adeudo_hipoteca', 'adeudo_predial',
            'adeudo_agua', 'adeudo_luz', 'monto_devolucion', 'cuenta_agua', 'nivel', 'recamaras', 'metros_terreno',
            'metros_construccion', 'razon_social', 'rfc', 'observaciones', 'fecha_otorgamiento_credito',
            'inm_prototipo_id', 'inm_complemento_id', 'inm_estado_vivienda_id', 'lada_com', 'numero_com', 'cel_com',
            'correo_com');
    }

    /**
     * Obtiene los keys de un prospecto para integrarlos con un cliente
     * @return string[]
     */
    private function keys_data_prospecto_ubicacion(): array
    {
        return array('lote', 'manzana', 'costo_directo', 'numero_exterior', 'numero_interior', 'etapa',
            'cuenta_predial', 'n_opiniones_valor', 'monto_opinion_promedio', 'costo', 'com_tipo_prospecto_id',
            'com_prospecto_id', 'com_direccion_id', 'nss', 'curp', 'nombre', 'apellido_paterno', 'apellido_materno',
            'nombre_completo_valida', 'adeudo_hipoteca', 'adeudo_predial', 'adeudo_agua', 'adeudo_luz',
            'monto_devolucion', 'cuenta_agua', 'nivel', 'recamaras', 'metros_terreno', 'metros_construccion',
            'razon_social', 'rfc', 'observaciones', 'fecha_otorgamiento_credito', 'inm_prototipo_id',
            'inm_complemento_id', 'inm_estado_vivienda_id', 'lada_com', 'numero_com', 'cel_com', 'correo_com',
            'inm_status_prospecto_ubicacion_id', 'calle', 'dp_colonia_postal_id');
    }

    /**
     * Validates a key.
     *
     * @param string $key The key to be validated.
     * @return true|array Returns true if the key is valid, or an array containing an error message and the invalid key if it is not valid.
     * @version 2.331.2
     */
    private function valida_key(string $key): true|array
    {
        $key = trim($key);
        if($key === ''){
            return $this->error->error(mensaje: 'Error key esta vacio', data: $key);
        }
        if(is_numeric($key)){
            return $this->error->error(mensaje: 'Error key debe ser un texto', data: $key);
        }
        return true;
    }
}
