<?php
namespace gamboamartin\inmuebles\html;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_co_acreditado;

use PDO;
use stdClass;

class inm_co_acreditado_html extends _base {



    private function adeudo_hipoteca(int $cols,  string $entidad, bool $disabled = false,
                                     string $name = 'adeudo_hipoteca', string $place_holder= 'Adeudo Hipoteca',
                                     stdClass $row_upd = new stdClass(), bool $value_vacio = false,
                                     bool $required = true ): array|string
    {
        return $this->input_text(cols: $cols,disabled:  $disabled,name:  $name,
            place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio,required: $required);
    }


    private function correo(int $cols,  string $entidad, bool $disabled = false, string $name = 'correo', string $place_holder= 'Correo',
                                  stdClass $row_upd = new stdClass(), bool $value_vacio = false, bool $required = true ): array|string
    {

        $regex = $this->validacion->patterns['correo_html_base'];

        return $this->input_text(cols: $cols,disabled:  $disabled,name:  $name,
            place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio,regex: $regex,
            required: $required);

    }
    private function curp(int $cols,  string $entidad, bool $disabled = false, string $name = 'curp', string $place_holder= 'CURP',
                              stdClass $row_upd = new stdClass(), bool $value_vacio = false,bool $required = true ): array|string
    {

        $regex = $this->validacion->patterns['curp_html'];
        $class_css = array('inm_co_acreditado_curp');

        return $this->input_text(cols: $cols, disabled: $disabled, name: $name, place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio, class_css: $class_css, regex: $regex,required: $required);

    }

    private function extension_nep(int $cols,  string $entidad, bool $disabled = false, string $name = 'extension_nep', string $place_holder= 'Extension',
                                     stdClass $row_upd = new stdClass(), bool $value_vacio = false, bool $required = true): array|string
    {


        return $this->input_text(cols: $cols,disabled:  $disabled,name:  $name,
            place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio, required: $required);

    }

    /**
     * Genera los inputs para co acreditado
     * @param string $entidad Entidad para integrar css
     * @param stdClass $params Parametros para inputs
     * @param stdClass $row_upd Registro en proceso
     * @return array|stdClass
     * @version 1.167.1
     */
    private function genera_inputs(
        string $entidad, stdClass $params, stdClass $row_upd = new stdClass()): array|stdClass
    {
        $valida = $this->valida_params(params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar parametros',data:  $valida);
        }

        $inputs = new stdClass();
        foreach ($params->campos as $campo){
            $inputs = $this->integra_input(campo: $campo, entidad: $entidad, inputs: $inputs, params: $params,
                row_upd: $row_upd);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar input',data:  $inputs);
            }
        }

        return $inputs;
    }

    /**
     * Inicializa un campo si este existe
     * @param string $campo Campo a inicializar
     * @param array $data Datos previos
     * @return array
     * @version 1.152.1
     */
    private function init_campo(string $campo, array $data): array
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio', data: $campo);
        }
        if(!isset($data[$campo])){
            $data[$campo] = $campo;
        }
        return $data;
    }

    private function init_campo_disabled(string $campo, array $data): array
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio', data: $campo);
        }
        if(isset($data[$campo])){
            $data[$campo] = $campo;
        }
        return $data;
    }

    /**
     * Inicializa los campos de un array de parametros para inputs
     * @param array $campos Campos a inicializar
     * @param array $datas Datos previos
     * @return array
     * @version 1.154.1
     */
    private function init_campos(array $campos, array $datas): array
    {
        foreach ($campos as $campo) {
            $campo = trim($campo);
            if($campo === ''){
                return $this->error->error(mensaje: 'Error campo esta vacio', data: $campo);
            }

            $datas = $this->init_campo(campo: $campo, data: $datas);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al inicializar datas', data: $datas);
            }
        }
        return $datas;

    }

    private function init_campos_disabled(array $campos, array $datas): array
    {
        foreach ($campos as $campo) {
            $campo = trim($campo);
            if($campo === ''){
                return $this->error->error(mensaje: 'Error campo esta vacio', data: $campo);
            }

            $datas = $this->init_campo_disabled(campo: $campo, data: $datas);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al inicializar datas', data: $datas);
            }
        }
        return $datas;

    }

    /**
     * Obtiene los cols para css
     * @param array $cols_css Cols css previos
     * @return array
     * @version 1.156.1
     */
    private function init_cols(array $cols_css): array
    {
        $cols_6 = array();

        foreach ($cols_6 as $campo){
            if(!isset($cols_css[$campo])){
                $cols_css[$campo] = 6;
            }
        }

        $cols_4 = array('numero_nep','nombre_empresa', 'nrp');

        foreach ($cols_4 as $campo){
            if(!isset($cols_css[$campo])){
                $cols_css[$campo] = 4;
            }
        }

        $cols_3 = array('lada','lada_nep');

        foreach ($cols_3 as $campo){
            if(!isset($cols_css[$campo])){
                $cols_css[$campo] = 3;
            }
        }

        $cols_2 = array('nss', 'curp', 'rfc', 'nombre', 'apellido_materno','apellido_paterno', 'numero_credito',
            'adeudo_hipoteca','genero','extension_nep','numero','celular','correo');

        foreach ($cols_2 as $campo){
            if(!isset($cols_css[$campo])){
                $cols_css[$campo] = 2;
            }
        }

        return $cols_css;
    }

    /**
     * Inicializa los parametros de un input
     * @param string $campo Campo a inicializar
     * @param stdClass $params Parametros previos
     * @return stdClass|array
     * @version 1.165.1
     */
    private function init_param(string $campo, stdClass $params): stdClass|array
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio',data:  $campo);
        }
        if(!isset($params->cols[$campo])){
            $params->cols[$campo] = 12;
        }
        if(!isset($params->disableds[$campo])){
            $params->disableds[$campo] = false;
        }
        if(!isset($params->requireds[$campo])){
            $params->requireds[$campo] = false;
        }
        if(!isset($params->names[$campo])){
            $params->names[$campo] = $campo;
        }
        return $params;
    }

    /**
     * Integra los parametros para generacion de inputs
     * @param array $campos Campos a inicializar
     * @param array $cols_css Columnas css
     * @param array $disableds Disabled atributos
     * @param array $names Names
     * @return array|stdClass
     * @version 1.159.1
     */
    private function init_params(array $campos, array $cols_css, array $disableds, array $names, array $requireds): array|stdClass
    {
        $cols_css = $this->init_cols(cols_css: $cols_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar cols_css',data:  $cols_css);
        }
        $names = $this->init_campos(campos: $campos,datas:  $names);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar names',data:  $names);
        }
        $disableds = $this->init_campos_disabled(campos: $campos,datas:  $disableds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar disableds',data:  $disableds);
        }
        $requireds = $this->init_campos_disabled(campos: $campos,datas:  $requireds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar disableds',data:  $requireds);
        }

        $data = new stdClass();
        $data->cols = $cols_css;
        $data->names = $names;
        $data->disableds = $disableds;
        $data->requireds = $requireds;

        return $data;
    }

    /**
     * Integra los inputs de co acreditados
     * @param string $entidad Entidad para css
     * @param bool $integra_prefijo Si integra prefijo incluye el nombre dela tabla cada input
     * @param array $cols_css Col css
     * @param array $disableds Disableds atributos
     * @param array $names Names de inputs
     * @param stdClass $row_upd Registro en proceso
     * @return array|stdClass
     * @version 1.168.1
     */
    final public function inputs(string $entidad, bool $integra_prefijo = false,array $cols_css = array(),
                                 array $disableds = array(), array $names = array(),
                                 stdClass $row_upd = new stdClass()): array|stdClass
    {
        $params = $this->params_inputs(cols_css: $cols_css,disableds: $disableds,integra_prefijo:  $integra_prefijo,
            names: $names);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar params',data:  $params);
        }

        $inputs = $this->genera_inputs(entidad: $entidad, params: $params, row_upd: $row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $inputs);
        }

        return $inputs;
    }

    /**
     * Integra un input basado en un campo
     * @param string $campo Campo de base de datos
     * @param string $entidad
     * @param stdClass $inputs Inputs previos generados
     * @param stdClass $params Parametros previos
     * @param stdClass $row_upd Registro en proceso
     * @return array|stdClass
     * @version 1.166.1
     */
    private function integra_input(string $campo, string $entidad, stdClass $inputs, stdClass $params,
                                   stdClass $row_upd): array|stdClass
    {
        $valida = $this->valida_campo(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar campo',data:  $valida);
        }

        $params = $this->init_param(campo: $campo,params:  $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar param',data:  $params);
        }

        if($campo === 'genero'){
            $checked_default_g = 1;
            if($row_upd->genero_co_acreditado === 'F'){
                $checked_default_g = 2;
            }

            $input = $this->$campo(cols: $params->cols[$campo], disabled: $params->disableds[$campo], entidad: $entidad,
                name: $params->names[$campo], row_upd: $row_upd, required: $params->requireds[$campo],
                checked_default: $checked_default_g, val_1: 'M', val_2: 'F');
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar input',data:  $input);
            }
            $inputs->$campo = $input;

            return $inputs;
        }

        $input = $this->$campo(cols: $params->cols[$campo], disabled: $params->disableds[$campo], entidad: $entidad,
            name: $params->names[$campo], row_upd: $row_upd, required: $params->requireds[$campo]);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $input);
        }
        $inputs->$campo = $input;

        return $inputs;
    }




    private function lada_nep(int $cols,  string $entidad, bool $disabled = false, string $name = 'lada_nep', string $place_holder= 'Lada Empresa',
                               stdClass $row_upd = new stdClass(), bool $value_vacio = false, bool $required = true): array|string
    {

        $regex = $this->validacion->patterns['lada_html'];

        return $this->input_text(cols: $cols,disabled:  $disabled,name:  $name,
            place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio,regex: $regex,
            required: $required);

    }



    private function nombre_empresa(int $cols,  string $entidad, bool $disabled = false, string $name = 'nombre_empresa',
                                                string $place_holder= 'Nombre Empresa',
                                                stdClass $row_upd = new stdClass(),
                                                bool $value_vacio = false, bool $required = true): array|string
    {
        $class_css = array('inm_co_acreditado_nombre_empresa');

        return $this->input_text(cols: $cols, disabled: $disabled, name: $name, place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio, class_css: $class_css,required: $required);

    }

    private function nrp(int $cols,  string $entidad, bool $disabled = false, string $name = 'nrp',
                                                string $place_holder= 'NRP',
                                                stdClass $row_upd = new stdClass(),
                                                bool $value_vacio = false, bool $required = true): array|string
    {
        return $this->input_text(cols: $cols,disabled:  $disabled,name:  $name,
            place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio, required: $required);
    }

    private function genero(int $cols,  string $entidad, bool $disabled = false, string $name = 'genero',
                            string $place_holder= 'Genero', stdClass $row_upd = new stdClass(),
                            bool $value_vacio = false, bool $required = true, int $checked_default = 1,
                            string $val_1 = '', string $val_2 = ''): array|string
    {
        return $this->input_radio_doble(campo: $name, checked_default: $checked_default, tag: $place_holder,
            val_1: $val_1, val_2: $val_2, cols: $cols);
    }

    /**
     * Integra un input de tipo nss
     * @param int $cols Columnas css
     * @param string $entidad Entidad para prefijo css
     * @param bool $disabled atributo disabled input
     * @param string $name Name input
     * @param string $place_holder Marca de agua mostrable en input
     * @param bool $required Atributo required
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si vacio deja el input vacio
     * @return array|string
     */
    private function nss(int $cols,  string $entidad, bool $disabled = false, string $name = 'nss', string $place_holder= 'NSS',
                         bool $required = true, stdClass $row_upd = new stdClass(),
                         bool $value_vacio = false): array|string
    {
        $regex = $this->validacion->patterns['nss_html'];
        return $this->input_text(cols: $cols,disabled:  $disabled,name:  $name, place_holder:  $place_holder,
            row_upd:  $row_upd,value_vacio:  $value_vacio,regex: $regex,required: $required);

    }



    private function numero_nep(int $cols,  string $entidad, bool $disabled = false, string $name = 'numero_nep',
                                string $place_holder= 'Numero Empresa', stdClass $row_upd = new stdClass(),
                                bool $value_vacio = false, bool $required = true): array|string
    {

        $regex = $this->validacion->patterns['tel_sin_lada_html'];

        return $this->input_text(cols: $cols,disabled:  $disabled,name:  $name,
            place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio,regex: $regex,
            required: $required);

    }


    private function numero_credito(int $cols,  string $entidad, bool $disabled = false,
                                    string $name = 'numero_credito', string $place_holder= 'Numero de Credito',
                                    stdClass $row_upd = new stdClass(), bool $value_vacio = false,
                                    bool $required = true): array|string
    {
        $class_css = array('inm_co_acreditado_numero_credito');

        return $this->input_text(cols: $cols, disabled: $disabled, name: $name, place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio, class_css: $class_css,required: $required);

    }

    /**
     * Inicializa los parametros para inputs de co acreditado
     * @param array $cols_css Cols cd inputs
     * @param array $disableds Disableds de inputs
     * @param bool $integra_prefijo Si integra el prefijo anexa la tabla
     * @param array $names Names de inputs
     * @return array|stdClass
     * @version 1.161.1
     */
    private function params_inputs(array $cols_css, array $disableds, bool $integra_prefijo, array $names): array|stdClass
    {
        $campos = array('apellido_materno','apellido_paterno','celular','correo','curp','extension_nep','lada',
            'lada_nep','nombre', 'nombre_empresa','nrp','nss', 'numero','numero_nep','rfc','numero_credito',
            'adeudo_hipoteca','genero');

        $requireds = array();

        $params = $this->init_params(campos: $campos,cols_css:  $cols_css,disableds:  $disableds,names:  $names,
            requireds: $requireds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar params',data:  $params);
        }

        if($integra_prefijo){
            foreach ($params->names as $campo=>$name){
                $campo_rename = "co_acreditado[$campo]";
                $params->names[$campo] = $campo_rename;
            }
        }

        $params->campos = $campos;

        return $params;
    }

    private function rfc(int $cols,  string $entidad, bool $disabled = false, string $name = 'rfc', string $place_holder= 'RFC',
                               stdClass $row_upd = new stdClass(), bool $value_vacio = false, bool $required = true): array|string
    {

        $regex = $this->validacion->patterns['rfc_html'];

        $class_css = array('inm_co_acreditado_rfc');

        return $this->input_text(cols: $cols, disabled: $disabled, name: $name, place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio, class_css: $class_css, regex: $regex, required: $required);

    }

    /**
     * Genera un selector de tipo co acreditado
     * @param int $cols No de columnas css
     * @param bool $con_registros Si con registros integra registros en options
     * @param int $id_selected Selected id
     * @param PDO $link Conexion a la base de datos
     * @param array $columns_ds Columnas a mostrar en opciones
     * @param bool $disabled Atributo disabled
     * @param array $filtro Filtro de datos
     * @return array|string
     * @version 1.130.1
     */
    final public function select_inm_co_acreditado_id(int $cols, bool $con_registros, int $id_selected,
                                                      PDO $link, array $columns_ds=array(), bool $disabled = false,
                                                      array $filtro = array()): array|string
    {
        $modelo = new inm_co_acreditado(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, columns_ds: $columns_ds, disabled: $disabled, filtro: $filtro, label: 'Co Acreditado',
            required: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    /**
     * Valida que un campo para parametros sea valido
     * @param string $campo Campo a validar
     * @return bool|array
     * @version 1.164.1
     */
    private function valida_campo(string $campo): bool|array
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio',data:  $campo);
        }
        if(!method_exists($this,$campo)){
            return $this->error->error(mensaje: 'Error no existe la funcion',data:  $campo);
        }
        return true;
    }

    /**
     * Valida los parametros para inputs
     * @param stdClass $params parametros previos cargados
     * @return bool|array
     * @version 1.164.1
     */
    private function valida_params(stdClass $params): bool|array
    {
        if(!isset($params->campos)){
            return $this->error->error(mensaje: 'Error al params->campos no existe',data:  $params);
        }
        if(!is_array($params->campos)){
            return $this->error->error(mensaje: 'Error al params->campos no es un array',data:  $params);
        }

        if(!isset($params->cols)){
            return $this->error->error(mensaje: 'Error al params->cols no existe',data:  $params);
        }
        if(!is_array($params->cols)){
            return $this->error->error(mensaje: 'Error al params->cols no es un array',data:  $params);
        }

        if(!isset($params->disableds)){
            return $this->error->error(mensaje: 'Error al params->disableds no existe',data:  $params);
        }
        if(!is_array($params->disableds)){
            return $this->error->error(mensaje: 'Error al params->disableds no es un array',data:  $params);
        }
        if(!isset($params->names)){
            return $this->error->error(mensaje: 'Error al params->names no existe',data:  $params);
        }
        if(!is_array($params->names)){
            return $this->error->error(mensaje: 'Error al params->names no es un array',data:  $params);
        }
        return true;
    }


}
