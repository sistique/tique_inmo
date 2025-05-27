<?php

namespace gamboamartin\direccion_postal\controllers;

use base\controller\controler;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

class _init_dps{

    private errores $error;
    private validacion $validacion;

    public function __construct(){
        $this->error = new errores();
        $this->validacion = new validacion();
    }

    /**
     * Asigna los datos de una ejecucion GET
     * @param array $childrens Tablas de ejecucion
     * @param string $entidad Entidad base de ejecucion
     * @param string $entidad_key Key de entidad
     * @param string $key_option Key de valor option
     * @param string $seccion_limpia Seccion
     * @param string $seccion_param Parametro
     * @return array|string
     * @version 15.9.0
     */
    private function asigna_data(array $childrens, string $entidad, string $entidad_key, string $key_option,
                                 string $seccion_limpia, string $seccion_param): array|string
    {
        $seccion_limpia = trim($seccion_limpia);
        if($seccion_limpia === ''){
            return $this->error->error(mensaje: 'Error seccion_limpia esta vacia',data:  $seccion_limpia,
                es_final: true);
        }
        $entidad_key = trim($entidad_key);
        if($entidad_key === ''){
            return $this->error->error(mensaje: 'Error entidad_key esta vacia', data: $entidad_key, es_final: true);
        }
        $key_option = trim($key_option);
        if($key_option === ''){
            return $this->error->error(mensaje: 'Error key_option esta vacia', data: $key_option, es_final: true);
        }
        $entidad = trim($entidad);
        if($entidad === ''){
            return $this->error->error(mensaje: 'Error entidad esta vacia', data: $entidad, es_final: true);
        }
        $seccion_param = trim($seccion_param);
        if($seccion_param === ''){
            return $this->error->error(mensaje: 'Error seccion_param esta vacia', data: $seccion_param, es_final: true);
        }

        $update = $this->update_ejecuta(childrens: $childrens,entidad_key:  $entidad_key,
            key_option:  $key_option,seccion_limpia:  $seccion_limpia,seccion_param:  $seccion_param);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar update', data: $update);
        }

        return 'let asigna_'.$entidad.' = ('.$seccion_param.'_id = "", val_selected_id = "") => {
            '.$update.'
        }
        ';

    }

    public function asigna_propiedades_base(_ctl_calles|_ctl_dps $controlador): _ctl_calles|_ctl_dps
    {
        $identificador = "dp_pais_id";
        $propiedades = array("label" => "Pais",'key_descripcion_select' => 'dp_pais_descripcion');
        $prop = $controlador->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar propiedad', data: $prop);
        }


        $identificador = "dp_estado_id";
        $propiedades = array("label" => "Estado", "con_registros" => false,
            'key_descripcion_select' => 'dp_estado_descripcion');
        $prop = $controlador->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar propiedad', data: $prop);
        }

        $identificador = "dp_municipio_id";
        $propiedades = array("label" => "Municipio", "con_registros" => false,
            'key_descripcion_select' => 'dp_municipio_descripcion');
        $prop = $controlador->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar propiedad', data: $prop);
        }

        $identificador = "dp_cp_id";
        $propiedades = array("label" => "Código Postal", "con_registros" => false,
            'key_descripcion_select' => 'dp_cp_descripcion');
        $prop = $controlador->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar propiedad', data: $prop);
        }

        return $controlador;
    }

    /**
     * Integra el llamado de la funcion en java evento change
     * @param string $entidad Entidad de llamado
     * @param string $exe Entidad a ejecutar
     * @return array|string
     * @version 15.11.0
     */
    private function change(string $entidad, string $exe): array|string
    {
        $entidad = trim($entidad);
        if($entidad === ''){
            return $this->error->error(mensaje: 'Error entidad esta vacia',data:  $entidad, es_final: true);
        }

        $selected = $this->selected(entidad: $entidad);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selected',data:  $selected);
        }

        $exe_fn = '';
        if($exe !== '') {

            $exe_fn = $this->ejecuta_funcion(entidad: $exe);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar exe',data:  $exe_fn);
            }
        }

        return $selected.$exe_fn;
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Integra los childrens de data para conf de js
     * @param array $data Datos de urls
     * @return array
     * @version 21.1.0
     *
     */
    private function childrens(array $data): array
    {
        $childrens = array();
        if(isset($data['childrens'])){
            if(!is_array($data['childrens'])){
                return $this->error->error(mensaje: 'Error $data[childrens] dbe ser array',data:  $data,
                    es_final: true);
            }
            $childrens = $data['childrens'];
        }
        return $childrens;
    }

    /**
     * TOTAL
     * Integra la llamada de la ejecucion en java
     * @param string $entidad Entidad a ejecutar
     * @return string|array
     * @version 15.10.0
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.ejecuta_funcion
     */
    private function ejecuta_funcion(string $entidad): string|array
    {
        $entidad = trim($entidad);
        if($entidad === ''){
            return $this->error->error(mensaje: 'Error entidad esta vacio',data:  $entidad, es_final: true);
        }
        return 'asigna_'.$entidad.'(selected.val());';
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Integra el nombre de la entidad
     * @param array $data Datos previos
     * @param string $key Key  integrar
     * @return string|array
     * @version 20.9.0
     */
    private function entidad_key(array $data, string $key): string|array
    {
        $key = trim($key);
        if($key === ''){
            return $this->error->error(mensaje: 'Error key esta vacio',data:  $key, es_final: true);
        }
        $entidad_key = $key;
        if(isset($data['entidad_key'])){
            $data['entidad_key'] = trim($data['entidad_key']);
            if($data['entidad_key']!=='') {
                $entidad_key = $data['entidad_key'];
            }
        }
        return $entidad_key;
    }

    /**
     * Integra el llamado del evento change en java
     * @param string $entidad Entidad que detona exe
     * @param string $exe Entidad a ejecutar
     * @return array|string
     * @version 15.11.0
     */
    private function event_change(string $entidad, string $exe): array|string
    {
        $entidad = trim($entidad);
        if($entidad === ''){
            return $this->error->error(mensaje: 'Error entidad esta vacia',data:  $entidad, es_final: true);
        }

        $change = $this->change(entidad: $entidad, exe: $exe);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar change',data:  $change);
        }

        return 'sl_'.$entidad.'.change(function () {
        '.$change.'
        });';
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Integra la funcion js a ejecutar
     * @param array $data Datos de urls
     * @return string|array
     * @version 24.2.0
     */
    private function exe(array $data): string|array
    {
        $exe = '';
        if(isset($data['exe'])){
            if(!is_string($data['exe'])){
                return $this->error->error(mensaje: 'Error exe debe ser string',data:  $data, es_final: true);
            }

            $exe = trim($data['exe']);
        }
        return $exe;
    }

    /**
     * @param array $data
     * @param string $seccion_limpia
     * @param array $urls_js
     * @return array
     */
    private function genera_data_java(array $data, string $seccion_limpia, array $urls_js): array
    {
        $params = $this->params(data: $data, seccion_limpia: $seccion_limpia);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar params',data:  $params);
        }

        $java = $this->genera_java(params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar java',data:  $java);
        }

        $urls_js = $this->integra_datas(java: $java,params:  $params,urls_js:  $urls_js);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar java',data:  $java);
        }
        return $urls_js;
    }

    /**
     * Genera el java para cambio de direcciones
     * @param stdClass $params Parametros
     * @return array|stdClass
     * @version 16.1.0
     */
    private function genera_java(stdClass $params): array|stdClass
    {
        $valida = $this->valida_params(params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar parametros de java',data:  $valida);
        }

        $java = $this->java(params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar java',data:  $java);
        }

        $java = $this->java_compuesto(java: $java);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar java',data:  $java);
        }
        return $java;
    }

    /**
     * TOTAL
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.init_datatables.26.0.0
     * Método init_datatables
     *
     * @param array $columns Columnas para la tabla de datos.
     * @param array $filtro Filtro aplicado en la tabla de datos.
     *
     * @return stdClass
     *
     * Este método recibe dos parámetros, $columns y $filtro, que representan las
     * columnas y el filtro aplicado en la tabla de datos, respectivamente.
     *
     * Crea una nueva instancia de stdClass llamada $datatables y asigna los
     * valores de $columns y $filtro a las propiedades 'columns' y 'filtro'
     * del objeto $datatables, respectivamente.
     *
     * Finalmente, devuelve el objeto $datatables.
     * @version 19.0.0
     */
    final public function init_datatables(array $columns, array $filtro): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    /**
     * @param controler $controler
     * @return array
     */
    final public function init_js(controler $controler): array
    {
        $urls = array();

        $urls['pais'] = array();
        $urls['pais']['seccion_param'] = 'dp_pais';
        $urls['pais']['key_option'] = 'descripcion';
        $urls['pais']['exe'] = 'dp_estado';

        $urls['calle'] = array();
        $urls['calle']['seccion_param'] = 'dp_calle';
        $urls['calle']['key_option'] = 'descripcion';;

        $urls['estado']['seccion_param'] = 'dp_pais';
        $urls['estado']['key_option'] = 'descripcion';
        $urls['estado']['childrens'] = array('estado', 'municipio','cp','colonia_postal','calle_pertenece');
        $urls['estado']['exe'] = 'dp_municipio';

        $urls['municipio']['seccion_param'] = 'dp_estado';
        $urls['municipio']['key_option'] = 'descripcion';
        $urls['municipio']['childrens'] = array('municipio','cp','colonia_postal','calle_pertenece');
        $urls['municipio']['exe'] = 'dp_cp';

        $urls['cp']['seccion_param'] = 'dp_municipio';
        $urls['cp']['key_option'] = 'descripcion';
        $urls['cp']['childrens'] = array('cp','colonia_postal','calle_pertenece');
        $urls['cp']['exe'] = 'dp_colonia_postal';

        $urls['colonia_postal']['seccion_param'] = 'dp_cp';
        $urls['colonia_postal']['key_option'] = 'descripcion';
        $urls['colonia_postal']['entidad_key'] = 'dp_colonia';
        $urls['colonia_postal']['childrens'] = array('colonia_postal','calle_pertenece');
        $urls['colonia_postal']['exe'] = 'dp_calle_pertenece';

        $urls['calle_pertenece']['seccion_param'] = 'dp_colonia_postal';
        $urls['calle_pertenece']['key_option'] = 'descripcion';
        $urls['calle_pertenece']['entidad_key'] = 'dp_calle';
        $urls['calle_pertenece']['childrens'] = array('calle_pertenece');


        $urls_js = $this->urls(urls:$urls);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar url js',data:  $urls_js);
        }
        $controler->url_servicios = $urls_js;
        return $urls_js;
    }

    final public function init_propiedades_ctl(controlador_dp_calle|controlador_dp_calle_pertenece|_ctl_calles $controler){
        $controler->titulo_lista = 'Calles';

        $propiedades = $controler->inicializa_priedades();
        if(errores::$error){
           return $this->error->error(mensaje: 'Error al inicializar propiedades',data:  $propiedades);
        }
        $controler->lista_get_data = true;

        return $controler;
    }

    /**
     * Integra los datos en un array para la integracion en frontend
     * @param stdClass $java Java a integrar
     * @param string $key Key de base
     * @param stdClass $params Parametros
     * @param array $urls_js urls a ejecutar
     * @return array
     */
    private function integra_data(stdClass $java, string $key, stdClass $params, array $urls_js): array
    {
        $data = $java->$key;
        $urls_js[$params->key][$key] = "<script> $data </script>";
        return $urls_js;
    }

    /**
     * Integra los datos para se usados en frontend
     * @param stdClass $java
     * @param stdClass $params
     * @param array $urls_js
     * @return array
     */
    private function integra_datas(stdClass $java, stdClass $params, array $urls_js): array
    {
        $keys = array('update','css_id','change','event_full','event_change','event_update');
        foreach ($keys as $key){
            $urls_js = $this->integra_data(java: $java,key:  $key,params:  $params,urls_js:  $urls_js);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar java',data:  $java);
            }
        }
        return $urls_js;
    }


    /**
     * Genera el java de selects
     * @param stdClass $params Parametros a integrar
     * @return array|stdClass
     * @version 15.12.0
     */
    private function java(stdClass $params): array|stdClass
    {

        $valida = $this->valida_params(params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar parametros de java',data:  $valida);
        }

        $css_id = $this->select(entidad: $params->key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar css',data:  $css_id);
        }

        $update = $this->asigna_data(childrens: $params->childrens, entidad: $params->key,
            entidad_key: $params->entidad_key, key_option: $params->key_option, seccion_limpia: $params->seccion_limpia,
            seccion_param: $params->seccion_param);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar update',data:  $update);
        }

        $change = $this->event_change(entidad: $params->key,exe:  $params->exe);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar change',data:  $change);
        }

        $data = new stdClass();
        $data->css_id = $css_id;
        $data->update = $update;
        $data->change = $change;
        return $data;

    }

    /**
     * Ajusta los elementos de java en objetos
     * @param stdClass $java Datos de java
     * @return stdClass|array
     * @version 15.13.0
     */
    private function java_compuesto(stdClass $java): stdClass|array
    {
        if(!isset($java->css_id)){
            $java->css_id = '';
        }
        if(!isset($java->update)){
            $java->update = '';
        }
        if(!isset($java->change)){
            $java->change = '';
        }

        $event_full = $java->css_id.$java->update.$java->change;
        $event_change = $java->css_id.$java->change;
        $event_update = $java->css_id.$java->update;

        $java->event_full = $event_full;
        $java->event_change = $event_change;
        $java->event_update = $event_update;
        return $java;

    }

    /**
     * TOTAL
     * Esta función toma un string que representa una sección y devuelve una
     * clave específica en función del string proporcionado.
     *
     * @param string $seccion_limpia El nombre de la sección para la que se creará la clave.
     *
     * @return string|array Retorna una cadena con la clave formada añadiendo "dp_" al inicio
     *       del parámetro dado si este es válido. Si el parámetro está vacío, devuelve un
     *       arreglo con un mensaje de error.
     *
     * @throws errores En caso de que el parámetro proporcionado esté vacío, se generará un error.
     * @version 20.1.0
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.key
     */
    private function key(string $seccion_limpia): string|array
    {
        $seccion_limpia = trim($seccion_limpia);
        if($seccion_limpia === ''){
            return $this->error->error(mensaje: 'Error seccion_limpia esta vacia', data: $seccion_limpia,
                es_final: true);
        }
        return "dp_$seccion_limpia";
    }

    /**
     * POD DOCUMENTAR EN WIKI FINAL REV
     * Obtiene la llave 'key_option' de los datos proporcionados.
     *
     * @param array $data Array de datos donde se busca el valor de 'key_option'.
     * @return string Retorna el valor encontrado para la llave 'key_option'. Si no se encuentra, retorna una cadena vacía.
     *
     * Este método espera un parámetro `$data` que es un array de datos.
     * Intenta obtener el valor de la llave 'key_option' del array.
     * Si la llave existe, entonces obtiene su valor, lo recorta y lo retorna.
     * Si la llave 'key_option' no existe en el array de datos, entonces retorna una cadena vacía.
     * @version 19.2.0
     */
    private function key_option(array $data): string
    {
        $key_option = '';
        if(isset($data['key_option'])){
            $key_option = trim($data['key_option']);
        }
        return $key_option;
    }

    /**
     * TOTAL
     * Esta es la función '_init_dps.limpia_selector'.
     * Realiza la limpieza del selector y luego inicializa una nueva opción en el selector.
     *
     * @param string $css_id          El ID del elemento CSS al que se dirige la función.
     * @param string $entidad_limpia  La entidad a limpiar.
     *
     * @return string|array           Devuelve un string en caso de éxito, la combinación del proceso de limpieza del selector
     *                                y la inicialización de una nueva opción en el selector.
     *
     * @throws errores              Si $css_id está vacío o si $entidad_limpia está vacío,
     *                                lanza una Exception con un mensaje de error correspondiente.
     *
     * @version 24.2.0
     *
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.limpia_selector.26.2.0
     */
    private function limpia_selector(string $css_id, string $entidad_limpia): string|array
    {

        $css_id = trim($css_id);
        if($css_id === ''){
            return $this->error->error(mensaje: 'Error css_id esta vacio', data: $css_id, es_final: true);
        }
        $entidad_limpia = trim($entidad_limpia);
        if($entidad_limpia === ''){
            return $this->error->error(mensaje: 'Error entidad_limpia esta vacio', data: $entidad_limpia,
                es_final: true);
        }
        $empty = $css_id.'.empty();';
        $init = 'integra_new_option('.$css_id.',"Seleccione '.$entidad_limpia.'","-1");';

        return $empty.$init;

    }

    /**
     * TOTAL
     * Esta es la función '_init_dps.limpia_selectores'.
     * Limpia un conjunto de selectores y regresa el resultado concatenado de las operaciones sobre dichos selectores.
     *
     * @param array $selectores  Array de selectores a limpiar.
     *
     * @return array|string      Devuelve un string que es el resultado de concatenar las operaciones de
     *                           limpieza sobre cada uno de los selectores. Si ocurre un error durante el proceso,
     *                           retorna un mensaje de error.
     *
     * @throws errores         Si un selector está vacío, si existe un error al generar el css_id,
     *                           o si existe un error al generar la "limpia". En cada caso, lanza una Exception
     *                           con un mensaje de error correspondiente.
     *
     * @version 24.2.0
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.limpia_selectores.26.2.0
     */
    private function limpia_selectores(array $selectores): array|string
    {
        $limpia_selectores = '';
        foreach ($selectores as $selector) {

            $selector = trim($selector);
            if($selector === ''){
                return $this->error->error(mensaje: 'Error selector esta vacio', data: $selector, es_final: true);
            }

            $entidad = "dp_$selector";

            $css_id = $this->selector(entidad:$entidad);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al generar css_id', data: $css_id);
            }

            $limpia = $this->limpia_selector(css_id: $css_id, entidad_limpia: $selector);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al generar limpia', data: $limpia);
            }
            $limpia_selectores.=$limpia;
        }
        return $limpia_selectores;
    }

    /**
     *  TOTAL
     * Integra la funcion new option definida en java base
     * @param string $entidad_key Entidad
     * @param string $key_option para obtener valor
     * @param string $seccion Seccion a ejecutar
     * @return string|array
     * @version 15.4.0
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.new_option.26.2.0
     */
    private function new_option(string $entidad_key, string $key_option, string $seccion): string|array
    {
        $seccion = trim($seccion);
        $entidad_key = trim($entidad_key);
        $key_option = trim($key_option);

        $valida = $this->valida_base(entidad_key: $entidad_key,key_option:  $key_option,seccion:  $seccion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $data = 'sl_'.$seccion.','.$seccion.'.'.$entidad_key.'_'.$key_option.','.$seccion.'.'.$seccion.'_id';

        return 'integra_new_option('.$data.');';
    }

    /**
     * TOTAL
     * Integra las opciones para java
     * @param string $entidad_key Entidad
     * @param string $key_option para obtener valor
     * @param string $seccion Seccion a ejecutar
     * @return array|string
     * @version 15.4.0
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.options.26.2.0
     */
    private function options(string $entidad_key, string $key_option, string $seccion): array|string
    {
        $seccion = trim($seccion);
        $entidad_key = trim($entidad_key);
        $key_option = trim($key_option);

        $valida = $this->valida_base(entidad_key: $entidad_key,key_option:  $key_option,seccion:  $seccion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $new_option = $this->new_option(entidad_key: $entidad_key,key_option:  $key_option,seccion:  $seccion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar new option', data: $new_option);
        }
        return '$.each(data.registros, function( index, '.$seccion.' ) {
            '.$new_option.'
        });';
    }

    /**
     * Integra los parametros para generacion de java
     * @param array $data Datos de url
     * @param string $seccion_limpia Seccion a integrar
     * @return array|stdClass
     */
    private function params(array $data, string $seccion_limpia): array|stdClass
    {
        $seccion_limpia = trim($seccion_limpia);
        if($seccion_limpia === ''){
            return $this->error->error(mensaje: 'Error seccion_limpia esta vacia', data: $seccion_limpia,
                es_final: true);
        }

        $key = $this->key(seccion_limpia: $seccion_limpia);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar key',data:  $key);
        }

        $seccion_param = $this->seccion_param(data: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar seccion_param',data:  $seccion_param);
        }

        $key_option = $this->key_option(data: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar key_option',data:  $key_option);
        }

        $entidad_key = $this->entidad_key(data: $data, key: $key );
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar entidad_key',data:  $entidad_key);
        }

        $childrens = $this->childrens(data: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar childrens',data:  $childrens);
        }

        $exe = $this->exe(data: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar exe',data:  $exe);
        }


        $css_id = $this->select(entidad: $key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar css',data:  $css_id);
        }




        $data = new stdClass();
        $data->key = $key;
        $data->seccion_param = $seccion_param;
        $data->key_option = $key_option;
        $data->entidad_key = $entidad_key;
        $data->childrens = $childrens;
        $data->exe = $exe;
        $data->css_id = $css_id;
        $data->seccion_limpia = $seccion_limpia;

        return $data;
    }

    /**
     * Integra los refresh de java conforme los selectores enviados
     * @param array $selectores Conjunto de selectores
     * @return array|string
     * @version 15.5.0
     */
    private function refresh_selectores(array $selectores): array|string
    {

        $refreshs = '';
        foreach ($selectores as $selector) {

            $selector = trim($selector);
            if($selector === ''){
                return $this->error->error(mensaje: 'Error selector esta vacio', data: $selector, es_final: true);
            }

            $entidad = "dp_$selector";

            $css_id = $this->selector(entidad:$entidad);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al generar css_id', data: $css_id);
            }

            $refresh = $this->refresh_selectpicker(css_id: $css_id);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al generar refresh', data: $refresh);
            }
            $refreshs.=$refresh;
        }
        return $refreshs;

    }

    /**
     * Integra la ejecucion de refresh de un selector
     * @param string $css_id Identificador de css a ejecutar
     * @return string|array
     * @version 15.5.0
     */
    private function refresh_selectpicker(string $css_id): string|array
    {
        $css_id = trim($css_id);
        if($css_id === ''){
            return $this->error->error(mensaje: 'Error css_id esta vacio', data: $css_id, es_final: true);
        }
        return $css_id.'.selectpicker("refresh");';
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Extrae y limpia el valor 'seccion_param' de un array
     *
     * @param array $data El array de input en el tiene que extraerse y limpiarse el 'seccion_param'
     * @return string|array El valor 'seccion_param' limpio si se encuentra. En caso contrario retorna una cadena vacía.
     * @version 19.1.0
     */
    private function seccion_param(array $data): string|array
    {
        $seccion_param = '';
        if(isset($data['seccion_param'])){
            $seccion_param = trim($data['seccion_param']);
        }

        return $seccion_param;
    }

    /**
     * Integra un selector de tipo id forma jquery
     * @param string $entidad Entidad a integrar equivalente a select
     * @return array|string
     */
    private function select(string $entidad): array|string
    {
        $entidad = trim($entidad);
        if($entidad === ''){
            return $this->error->error(mensaje: 'Error entidad esta vacia',data:  $entidad, es_final: true);
        }

        $css_id = $this->selector(entidad: $entidad);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar css',data:  $css_id);
        }
        return 'let sl_'.$entidad.' = '.$css_id.';';
    }

    /**
     * TOTAL
     * Integra selected en option
     * @param string $entidad Entidad de integracion
     * @return string|array
     * @version 15.9.0
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.selected
     */
    private function selected(string $entidad): string|array
    {
        $entidad = trim($entidad);
        if($entidad === ''){
            return $this->error->error(mensaje: 'Error entidad esta vacia',data:  $entidad, es_final: true);
        }
        return 'let selected = sl_'.$entidad.'.find("option:selected");';
    }


    /**
     * TOTAL
     * Esta es la función 'selector', en la cual selecciona y retorna una entidad, para uso en jquery
     *
     * @param string $entidad  La entidad a seleccionar.
     *
     * @return string|array    Devuelve un string si la operación es exitosa.
     *                         Si la entidad está vacía, lanza un error y devuelve
     *                         el mensaje de error junto con los datos de la entidad.
     *
     * @throws errores       Cuando la entidad está vacía, lanza una Exception con el mensaje de error.
     * @version 24.2.0
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.selector.26.2.0
     */
    private function selector(string $entidad): string|array
    {
        $entidad = trim($entidad);
        if($entidad === ''){
            return $this->error->error(mensaje: 'Error entidad esta vacia',data:  $entidad, es_final: true);
        }
        return '$("#'.$entidad.'_id")';
    }

    /**
     * Aplica la ejecucion tipo javascript
     * @param array $childrens Elementos hijos a integrar update
     * @param string $entidad_key Entidad base de ejecucion
     * @param string $key Key = seccion
     * @param string $key_option Campo para la integracion del nuevo valor
     * @return array|string
     * @version 15.5.0
     */
    private function update(array $childrens, string $entidad_key, string $key, string $key_option): array|string
    {
        $key = trim($key);
        $entidad_key = trim($entidad_key);
        $key_option = trim($key_option);

        $valida = $this->valida_base(entidad_key: $entidad_key,key_option:  $key_option,seccion:  $key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $limpia = $this->limpia_selectores(selectores: $childrens);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar limpia',data:  $limpia);
        }

        $options = $this->options(entidad_key: $entidad_key,key_option:  $key_option,seccion:  $key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar options',data:  $options);
        }
        $val_selected = '$("#'.$key.'_id").val(val_selected_id);';

        $refresh = $this->refresh_selectores(selectores: $childrens);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar refresh',data:  $refresh);
        }

        return $limpia.$options.$val_selected.$refresh;

    }

    /**
     * Integra los elementos de un update
     * @param array $childrens Secciones
     * @param string $entidad_key Entidad
     * @param string $key seccion
     * @param string $key_option campo valor
     * @return array|string
     * @version 15.6.0
     */
    private function update_data(array $childrens, string $entidad_key, string $key, string $key_option): array|string
    {
        $key = trim($key);
        $entidad_key = trim($entidad_key);
        $key_option = trim($key_option);

        $valida = $this->valida_base(entidad_key: $entidad_key,key_option:  $key_option,seccion:  $key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $update = $this->update(childrens: $childrens,entidad_key: $entidad_key,key: $key,key_option: $key_option);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar update',data:  $update);
        }

        return 'get_data(url, function (data) {
        '.$update.'
        });';
    }

    /**
     * Integra el llamado a la ejecucion de un selector
     * @param array $childrens Conjunto de elementos a integrar
     * @param string $entidad_key Entidad de ejecucion
     * @param string $key_option Campo valor option
     * @param string $seccion_limpia Seccion
     * @param string $seccion_param Parametros extra
     * @return array|string
     * @version15.7.0
     */
    private function update_ejecuta(array $childrens, string $entidad_key, string $key_option,
                                    string $seccion_limpia, string $seccion_param): array|string
    {

        $seccion_limpia = trim($seccion_limpia);
        if($seccion_limpia === ''){
            return $this->error->error(mensaje: 'Error seccion_limpia esta vacia',data:  $seccion_limpia,
                es_final: true);
        }


        $key = "dp_$seccion_limpia";

        $key = trim($key);
        $entidad_key = trim($entidad_key);
        $key_option = trim($key_option);

        $valida = $this->valida_base(entidad_key: $entidad_key,key_option:  $key_option,seccion:  $key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $url = $this->url_servicio_get(seccion_limpia: $seccion_limpia, seccion_param: $seccion_param);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar url',data:  $url);
        }

        $url_val = 'let url = '.$url.';';


        $update = $this->update_data(childrens: $childrens, entidad_key: $entidad_key,key:  $key,
            key_option:  $key_option);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar update',data:  $update);
        }

        return $url_val.$update;
    }


    /**
     * Genera el elemento necesario para integrar en java la obtencion de una url
     * @param string $accion Accion a ejecutar
     * @param string $seccion Seccion a ejecutar
     * @param string $extra_params Params GET
     * @return string|array
     * @version 10.15.0
     */
    private function url_servicio(string $accion, string $seccion, string $extra_params = ''): string|array
    {
        $valida = $this->valida_pep_8_base(accion: $accion, seccion: $seccion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos',data: $valida);
        }

        $extra_params = trim($extra_params);
        if($extra_params !== '') {

            $valida = $this->validacion->valida_params_json_parentesis(txt: $extra_params);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar seccion', data: $valida);
            }
        }
        else{
            $extra_params = '{}';
        }

        return "get_url('$seccion','$accion', $extra_params);";
    }

    /**
     * Integra un elemento de extra param a un selector
     * @param string $accion Accion  de obtencion de datos
     * @param string $seccion Seccion de obtencion de datos
     * @param string $seccion_param Parametros de funcion
     * @return array|string
     * @version 10.15.0
     */
    private function url_servicio_extra_param(string $accion, string $seccion, string $seccion_param): array|string
    {
        $extra_param_js = '';

        $seccion_param = trim($seccion_param);
        if($seccion_param !== '') {
            $extra_param_js = '{' . $seccion_param . '_id: ' . $seccion_param . '_id}';
        }

        $valida = $this->valida_pep_8_base(accion: $accion, seccion: $seccion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos',data: $valida);
        }

        $url = $this->url_servicio(accion: $accion,seccion:  $seccion,extra_params:  $extra_param_js);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar url',data: $url);
        }
        return $url;
    }

    /**
     * Genera la url como jquery para obtencion de ws
     * @param string $seccion_limpia Seccion a obtener
     * @param string $seccion_param parametros de la seccion
     * @return array|string
     * @version 10.16.0
     */
    private function url_servicio_get(string $seccion_limpia, string $seccion_param): array|string
    {
        $seccion_limpia = trim($seccion_limpia);
        if($seccion_limpia === ''){
            return $this->error->error(mensaje: 'Error seccion_limpia esta vacia',data:  $seccion_limpia);
        }

        $accion = "get_$seccion_limpia";
        $seccion = "dp_$seccion_limpia";

        $url = $this->url_servicio_extra_param(accion: $accion,seccion:  $seccion, seccion_param: $seccion_param);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar url js',data:  $url);

        }
        return $url;
    }

    /**
     * @param array $urls
     * @return array
     */
    final public function urls(array $urls): array
    {
        $urls_js = array();
        foreach ($urls as $seccion_limpia=>$data){

            $urls_js = $this->genera_data_java(data: $data,seccion_limpia:  $seccion_limpia,urls_js:  $urls_js);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar java',data:  $urls_js);
            }

        }
        return $urls_js;
    }

    /**
     * TOTAL
     * Valida la integracion de datos basica
     * @param string $entidad_key Entidad
     * @param string $key_option para obtener valor
     * @param string $seccion Seccion a ejecutar
     * @return bool|array
     * @version 15.4.0
     * @url https://github.com/gamboamartin/direccion_postal/wiki/controllers._init_dps.valida_base.25.2.0
     */
    private function valida_base(string $entidad_key, string $key_option, string $seccion): true|array
    {
        $data_err = new stdClass();
        $data_err->entidad_key = $entidad_key;
        $data_err->key_option = $key_option;
        $data_err->seccion = $seccion;

        $seccion = trim($seccion);
        if($seccion === ''){
            return $this->error->error(mensaje: 'Error seccion esta vacia', data: $data_err, es_final: true);
        }
        $entidad_key = trim($entidad_key);
        if($entidad_key === ''){
            return $this->error->error(mensaje: 'Error entidad_key esta vacia', data: $data_err, es_final: true);
        }
        $key_option = trim($key_option);
        if($key_option === ''){
            return $this->error->error(mensaje: 'Error key_option esta vacia', data: $data_err, es_final: true);
        }
        return true;
    }

    private function valida_params(stdClass $params): true|array
    {
        if(!isset($params->key)){
            return $this->error->error(mensaje: 'Error $params->key no existe',data:  $params, es_final: true);
        }
        $params->key = trim($params->key);
        if($params->key === ''){
            return $this->error->error(mensaje: 'Error $params->key esta vacio',data:  $params, es_final: true);
        }
        if(!isset($params->childrens)){
            return $this->error->error(mensaje: 'Error $params->childrens no existe',data:  $params, es_final: true);
        }
        if(!isset($params->entidad_key)){
            return $this->error->error(mensaje: 'Error $params->entidad_key no existe',data:  $params, es_final: true);
        }
        if(!isset($params->key_option)){
            return $this->error->error(mensaje: 'Error $params->key_option no existe',data:  $params, es_final: true);
        }
        if(!isset($params->seccion_limpia)){
            return $this->error->error(mensaje: 'Error $params->seccion_limpia no existe',data:  $params,
                es_final: true);
        }
        if(!isset($params->seccion_param)){
            return $this->error->error(mensaje: 'Error $params->seccion_param no existe',data:  $params,
                es_final: true);
        }
        if(!is_array($params->childrens)){
            return $this->error->error(mensaje: 'Error $params->childrens debe ser un array',data:  $params,
                es_final: true);
        }
        if($params->seccion_limpia === ''){
            return $this->error->error(mensaje: 'Error $params->seccion_limpia esta vacio',data:  $params,
                es_final: true);
        }
        if($params->entidad_key === ''){
            return $this->error->error(mensaje: 'Error $params->entidad_key esta vacio',data:  $params, es_final: true);
        }
        if($params->key_option === ''){
            return $this->error->error(mensaje: 'Error $params->key_option esta vacio',data:  $params, es_final: true);
        }
        if($params->seccion_param === ''){
            return $this->error->error(mensaje: 'Error $params->seccion_param esta vacio',data:  $params,
                es_final: true);
        }
        if(!isset($params->exe)){
            return $this->error->error(mensaje: 'Error $params->exe no existe',data:  $params, es_final: true);
        }
        return true;

    }

    /**
     * Valida que un sting sea conformado pep_8
     * @param string $accion Accion a validar
     * @param string $seccion Seccion a validar
     * @return array|true
     * @version 10.14.0
     */
    private function valida_pep_8_base(string $accion, string $seccion): bool|array
    {
        $accion = trim($accion);
        $valida = $this->validacion->valida_texto_pep_8(txt: $accion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar accion',data: $valida);
        }
        $seccion = trim($seccion);
        $valida = $this->validacion->valida_texto_pep_8(txt: $seccion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar seccion',data: $valida);
        }
        return true;
    }
}
