<?php
namespace gamboamartin\cat_sat\models;

use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use PDO;
use stdClass;

/**
 * PRUEBAS FINALIZADAS
 */
class _validacion{
    public array $metodo_pago_permitido = array();
    private errores $error;


    public function __construct(){

        $this->error = new errores();

        $this->metodo_pago_permitido['PUE'] = array('01','02','03','04','05','06','08','12','13','14',
            '15','17','23','24','25','26','27','28','29','30','31');

        $this->metodo_pago_permitido['PPD'] = array('99');
        $this->metodo_pago_permitido['PRED'] = array('PRED');
    }

    /**
     * Obtiene el codigo de un metodo de pago
     * @param stdClass $data datos de obtencion de codigo
     * @return array|string
     * @version 12.2.0
     */
    private function cat_sat_metodo_pago_codigo(stdClass $data): array|string
    {
        if(!isset($data->cat_sat_metodo_pago)){
            return $this->error->error(mensaje: 'Error cat_sat_metodo_pago no existe', data: $data);
        }
        if(!isset($data->cat_sat_metodo_pago->codigo)){
            return $this->error->error(mensaje: 'Error cat_sat_metodo_pago->codigo no existe', data: $data);
        }
        $cat_sat_metodo_pago_codigo = trim($data->cat_sat_metodo_pago->codigo);
        if($cat_sat_metodo_pago_codigo === ''){
            return $this->error->error(mensaje: 'Error cat_sat_metodo_pago_codigo esta vacio',
                data: $cat_sat_metodo_pago_codigo);
        }
        return $cat_sat_metodo_pago_codigo;
    }

    /**
     * Inicializa los datos para validacion de facturas
     * @param array|stdClass $cat_sat_forma_pago Forma de pago
     * @param array|stdClass $cat_sat_metodo_pago Metodo de pago
     * @return array|stdClass
     *
     */
    private function data(array|stdClass $cat_sat_forma_pago, array|stdClass $cat_sat_metodo_pago): array|stdClass
    {
        $data = $this->init_data(cat_sat_forma_pago: $cat_sat_forma_pago,cat_sat_metodo_pago:  $cat_sat_metodo_pago);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos registro', data: $data);
        }

        $data = $this->init_codigo_metodo_pago(data: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos registro', data: $data);
        }

        $data = $this->init_codigo_forma_pago(data: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos registro', data: $data);
        }
        return $data;
    }

    /**
     * Obtiene los datos para validacion
     * @param array|stdClass $cat_sat_forma_pago Forma de pago entidad
     * @param array|stdClass $cat_sat_metodo_pago Metodo de pago entidad
     * @return array|stdClass
     */
    private function get_data(array|stdClass $cat_sat_forma_pago, array|stdClass $cat_sat_metodo_pago): array|stdClass
    {
        $data = $this->data(cat_sat_forma_pago: $cat_sat_forma_pago,cat_sat_metodo_pago:  $cat_sat_metodo_pago);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos registro', data: $data);
        }

        $cat_sat_metodo_pago_codigo = $this->cat_sat_metodo_pago_codigo(data: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener codigo',
                data: $cat_sat_metodo_pago_codigo);
        }
        $data->cat_sat_metodo_pago_codigo = $cat_sat_metodo_pago_codigo;
        return $data;
    }

    /**
     * Inicializa la forma de pago codigo
     * @param stdClass $data datos de envio
     * @return array|stdClass
     * @version 12.4.0
     */
    private function init_codigo_forma_pago(stdClass $data): array|stdClass
    {
        if(!isset($data->cat_sat_forma_pago->codigo)){
            if(!isset($data->cat_sat_forma_pago->cat_sat_forma_pago_codigo)){
                return $this->error->error(mensaje: 'Error cat_sat_forma_pago_codigo no existe en validacion',
                    data: $data);
            }
            $data->cat_sat_forma_pago->codigo = $data->cat_sat_forma_pago->cat_sat_forma_pago_codigo;
        }
        return $data;
    }

    /**
     * asigna el codigo de un metodo de pago
     * @param stdClass $data Datos
     * @return array|stdClass
     * @version 12.3.0
     */
    private function init_codigo_metodo_pago(stdClass $data): array|stdClass
    {
        if(!isset($data->cat_sat_metodo_pago->codigo)){
            if(!isset($data->cat_sat_metodo_pago->cat_sat_metodo_pago_codigo)){
                return $this->error->error(mensaje: 'Error cat_sat_metodo_pago_codigo no existe en validacion',
                    data: $data);
            }
            $data->cat_sat_metodo_pago->codigo = $data->cat_sat_metodo_pago->cat_sat_metodo_pago_codigo;
        }
        return $data;
    }

    /**
     * Inicializa los datos para la validacion de un metodo de pago
     * @param stdClass|array $cat_sat_forma_pago Forma de pago datos
     * @param stdClass|array $cat_sat_metodo_pago Metodo de pago datos
     * @return stdClass
     */
    private function init_data(stdClass|array $cat_sat_forma_pago, stdClass|array $cat_sat_metodo_pago): stdClass
    {
        if(is_array($cat_sat_metodo_pago)){
            $cat_sat_metodo_pago = (object)$cat_sat_metodo_pago;
        }
        if(is_array($cat_sat_forma_pago)){
            $cat_sat_forma_pago = (object)$cat_sat_forma_pago;
        }

        $data = new stdClass();
        $data->cat_sat_metodo_pago = $cat_sat_metodo_pago;
        $data->cat_sat_forma_pago = $cat_sat_forma_pago;
        return $data;
    }

    /**
     * Verifica si los datos del cliente van conforme al tipo de persona y regimen fiscal
     * @param PDO $link Conexion a la base de datos
     * @param array $registro Registro en proceso
     * @return array|true
     */
    final public function valida_conf_tipo_persona(PDO $link, array $registro): bool|array
    {

        $keys = array('cat_sat_regimen_fiscal_id','cat_sat_tipo_persona_id');
        $valida = (new validacion())->valida_ids(keys: $keys, registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al al validar registro', data: $valida);
        }

        $valida = true;
        if((int)$registro['cat_sat_regimen_fiscal_id'] === 999 || (int)$registro['cat_sat_tipo_persona_id'] === 6){
            $valida = false;
        }
        $filtro['cat_sat_regimen_fiscal.id'] = $registro['cat_sat_regimen_fiscal_id'];
        $filtro['cat_sat_tipo_persona.id'] = $registro['cat_sat_tipo_persona_id'];

        $existe_conf = (new cat_sat_conf_reg_tp(link: $link))->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al verificar si existe configuracion de regimen',
                data: $existe_conf);
        }
        if(!$existe_conf && $valida){
            return $this->error->error(mensaje: 'Error al no existe configuracion de regimen', data: $filtro);
        }
        return true;
    }

    /**
     * Valida que un metodo de pago sea valido
     * @param PDO $link Conexion a la base de datos
     * @param array $registro Registro en proceso
     * @return array|bool
     */
    final public function valida_metodo_pago(PDO $link, array $registro): bool|array
    {

        $keys = array('cat_sat_metodo_pago_id','cat_sat_forma_pago_id');
        $valida = (new validacion())->valida_ids(keys: $keys, registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }

        $cat_sat_metodo_pago = (new cat_sat_metodo_pago(link: $link))->registro(
            registro_id: $registro['cat_sat_metodo_pago_id'], columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener cat_sat_metodo_pago', data: $cat_sat_metodo_pago);
        }

        $cat_sat_forma_pago = (new cat_sat_forma_pago(link: $link))->registro(
            registro_id: $registro['cat_sat_forma_pago_id'], columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener cat_sat_forma_pago', data: $cat_sat_forma_pago);
        }

        $es_predeterminado = false;

        if($cat_sat_forma_pago->predeterminado === 'activo'){
            $es_predeterminado = true;
        }
        elseif($cat_sat_metodo_pago->predeterminado === 'activo'){
            $es_predeterminado = true;
        }
        $verifica = true;
        if(!$es_predeterminado) {
            $verifica = $this->verifica_forma_pago(cat_sat_forma_pago: $cat_sat_forma_pago,
                cat_sat_metodo_pago: $cat_sat_metodo_pago, registro: $registro);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al verifica registro', data: $verifica);
            }
        }
        return $verifica;
    }

    /**
     * Verifica si existe o no un codigo de metodo de pago
     * @param string $cat_sat_metodo_pago_codigo Codigo a validar
     * @return bool|array
     * @version 12.7.0
     */
    private function valida_si_existe(string $cat_sat_metodo_pago_codigo): bool|array
    {
        $cat_sat_metodo_pago_codigo = trim($cat_sat_metodo_pago_codigo);
        if($cat_sat_metodo_pago_codigo === ''){
            return $this->error->error(mensaje: 'Error cat_sat_metodo_pago_codigo esta vacio',
                data: $cat_sat_metodo_pago_codigo);
        }
        if(!isset($this->metodo_pago_permitido[$cat_sat_metodo_pago_codigo])){
            return $this->error->error(mensaje: 'Error cat_sat_metodo_pago_codigo no existe en validacion',
                data: $cat_sat_metodo_pago_codigo);

        }
        return true;
    }

    /**
     * Valida si existe un codigo en los metodos de pago definidos
     * @param string $cat_sat_metodo_pago_codigo Codigo a verificar
     * @param stdClass $data Datos cargados
     * @param array|stdClass $registro Registro en proceso
     * @return bool|array
     */
    private function valida_si_existe_en_array(string $cat_sat_metodo_pago_codigo, stdClass $data,
                                               array|stdClass $registro): bool|array
    {
        $cat_sat_metodo_pago_codigo = trim($cat_sat_metodo_pago_codigo);
        if($cat_sat_metodo_pago_codigo === ''){
            return $this->error->error(mensaje: 'Error cat_sat_metodo_pago_codigo esta vacio',
                data: $cat_sat_metodo_pago_codigo);
        }
        if(!isset($data->cat_sat_forma_pago)){
            return $this->error->error(mensaje: 'Error data->cat_sat_forma_pago no existe', data: $data);
        }
        if(!is_object($data->cat_sat_forma_pago)){
            return $this->error->error(mensaje: 'Error data->cat_sat_forma_pago debe ser un objeto', data: $data);
        }
        if(!isset($data->cat_sat_forma_pago->codigo)){
            return $this->error->error(mensaje: 'Error data->cat_sat_forma_pago->codigo no existe', data: $data);
        }
        if(!isset($this->metodo_pago_permitido[$cat_sat_metodo_pago_codigo])){
            return $this->error->error(
                mensaje: 'Error $this->metodo_pago_permitido[$cat_sat_metodo_pago_codigo] no existe',
                data: $this->metodo_pago_permitido);
        }
        if(!is_array($this->metodo_pago_permitido[$cat_sat_metodo_pago_codigo])){
            return $this->error->error(
                mensaje: 'Error $this->metodo_pago_permitido[$cat_sat_metodo_pago_codigo] debe ser una array',
                data: $this->metodo_pago_permitido);
        }
        if((!in_array($data->cat_sat_forma_pago->codigo, $this->metodo_pago_permitido[$cat_sat_metodo_pago_codigo]))){
            return $this->error->error(mensaje: 'Error al metodo o forma de pago incorrecto', data: $registro);
        }
        return true;
    }

    /**
     * Verifica que una forma de pago se valida en relacion al metodo de pago
     * @param stdClass|array $cat_sat_forma_pago Row de forma de pago
     * @param stdClass|array $cat_sat_metodo_pago Row de metodo de pago
     * @param array|stdClass $registro Registro en proceso
     * @return true|array
     */
    private function verifica_forma_pago(stdClass|array $cat_sat_forma_pago, stdClass|array $cat_sat_metodo_pago,
                                         array|stdClass $registro): true|array
    {

        $data = $this->get_data(cat_sat_forma_pago: $cat_sat_forma_pago,cat_sat_metodo_pago:  $cat_sat_metodo_pago);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos registro', data: $data);
        }


        $valida = $this->valida_si_existe(cat_sat_metodo_pago_codigo: $data->cat_sat_metodo_pago_codigo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar metodo de pago', data: $valida);

        }
        $valida = $this->valida_si_existe_en_array(cat_sat_metodo_pago_codigo: $data->cat_sat_metodo_pago_codigo,
            data:  $data,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar metodo de pago', data: $valida);

        }

        return true;
    }



}
