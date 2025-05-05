<?php
namespace base\orm;
use gamboamartin\administrador\modelado\validaciones;
use gamboamartin\encripta\encriptador;
use gamboamartin\errores\errores;
use stdClass;

class inicializacion{

    private errores $error;
    private validaciones $validacion;

    public function __construct(){
        $this->error = new errores();
        $this->validacion = new validaciones();
    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Ajusta los campos al actualizar un registro.
     *
     * Este método se utiliza para limpiar y verificar los campos
     * que se envían para actualizar un registro en la base de datos.
     * Si encuentra algún error, retorna un error con mensaje detallando el problema.
     *
     * @param int $id El ID del registro que se va a actualizar.
     * @param modelo $modelo El modelo de datos que contiene el registro.
     * @return array Devuelve el registro actualizado si todo va bien, o un error si algo va mal.
     *
     * @throws errores Si algún problema surge durante el proceso,
     * la función lanzará una excepción de un tipo específico definido en su implementación.
     *
     * @version 16.279.1
     */
    final public function ajusta_campos_upd(int $id, modelo $modelo): array
    {
        if($id <=0){
            return  $this->error->error(mensaje: 'Error al obtener registro $id debe ser mayor a 0',
                data: $id, es_final: true);
        }

        $registro_previo = $modelo->registro(registro_id: $id,columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro previo',data: $registro_previo);
        }

        foreach ($modelo->registro_upd as $campo=>$value_upd){
            $campo = trim($campo);
            if($campo === ''){
                return $this->error->error(mensaje:'Error el campo del row esta vacio',data:$campo, es_final: true);
            }
            if(is_numeric($campo)){
                return $this->error->error(mensaje:'Error el campo no puede ser un numero',data:$campo, es_final: true);
            }

            $ajusta = $this->ajusta_registro_upd(campo: $campo,modelo:  $modelo,
                registro_previo: $registro_previo,value_upd:  $value_upd);
            if(errores::$error){
                return $this->error->error(mensaje:'Error al ajustar elemento',data:$ajusta);
            }
        }
        return $modelo->registro_upd;
    }

    /**
     * REG
     * Ajusta la propiedad `params` del objeto complemento.
     *
     * Este método verifica si el objeto `$complemento` ya posee la propiedad `params`. Si no existe,
     * se inicializa llamando al método `init_params()`. En caso de que ocurra un error durante la inicialización,
     * se retorna un arreglo con la información del error; de lo contrario, se retorna el objeto `$complemento`
     * actualizado.
     *
     * @param stdClass $complemento Objeto complementario que contendrá los parámetros adicionales para la consulta SQL.
     *                              Se espera que, si no tiene la propiedad `params`, se inicialice a través de este método.
     *
     * @return stdClass|array Devuelve el objeto `$complemento` con la propiedad `params` inicializada si todo es correcto;
     *                        en caso de error, retorna un arreglo con los detalles del error.
     *
     * @example
     * <pre>
     * // Ejemplo de uso:
     * // Se crea un objeto complemento sin la propiedad "params".
     * $complemento = new stdClass();
     *
     * // Se ajustan los parámetros, inicializando "params" si es necesario.
     * $complemento = $this->ajusta_params($complemento);
     *
     * // Resultado esperado:
     * // $complemento->params es un objeto stdClass que contiene:
     * //    - offset   => ""
     * //    - group_by => ""
     * //    - order    => ""
     * //    - limit    => ""
     * </pre>
     *
     * @version 1.0.0
     */
    final public function ajusta_params(stdClass $complemento): array|stdClass
    {
        if (!isset($complemento->params)) {
            $complemento = $this->init_params(complemento: $complemento);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al inicializar params', data: $complemento);
            }
        }
        return $complemento;
    }


    /**
     * POR DOCUMENTAR EN WIKI
     * Ajusta la información de registro para una operación de actualización.
     *
     * Esta función compara un valor previo con el nuevo valor proporcionado para un campo específico.
     * Si ambos valores son iguales, entonces el campo respectivo es eliminado del registro de actualización
     *  del Modelo.
     * En el caso de que el campo esté vacío, arroja un error indicando que el campo está vacío.
     * También valida el registro previo para verificar si contiene el campo especificado.
     *
     * @param string $campo El campo que se necesita ajustar.
     * @param modelo $modelo El modelo en el que se realiza la operación de actualización.
     * @param stdClass $registro_previo Registro previo del modelo antes de la operación de actualización.
     * @param string|null $value_upd El nuevo valor que se quiere establecer para el campo.
     *
     * @return array Retorna el registro actualizado del modelo.
     * @throws errores si el campo está vacío, si la integración del registro previo falla o si la validacion del
     *  registro previo falla.
     * @version 16.277.1
     */
    private function ajusta_registro_upd(string $campo, modelo $modelo, stdClass $registro_previo,
                                        string|null $value_upd): array
    {
        $value_upd = trim($value_upd);
        $campo = trim($campo);

        if($campo === ''){
            return $this->error->error(mensaje: 'Error el campo esta vacio', data:$campo, es_final: true);
        }

        $registro_previo = $this->registro_previo_null(campo: $campo,registro_previo:  $registro_previo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar registro_previo', data:$registro_previo);
        }

        $keys = array($campo);
        $valida = (new validaciones())->valida_existencia_keys(keys: $keys, registro: $registro_previo,
            valida_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro_previo', data:$valida);
        }

        $value_previo = trim($registro_previo->$campo);

        if($value_previo === $value_upd){
            unset($modelo->registro_upd[$campo]);
        }

        return $modelo->registro_upd;
    }

    private function aplica_status_inactivo(string $key, array $registro): array
    {
        if(!isset($registro[$key])){
            $registro = $this->init_key_status_inactivo(key: $key, registro: $registro);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al inicializa status',data: $registro);
            }
        }
        return $registro;
    }

    private function asigna_data_attr(array $atributo, string $field, string $key, string $key_new, modelo $modelo){
        $modelo->atributos->$field->$key_new = $atributo[$key];
        return $modelo->atributos->$field;
    }


    /**
     * Funcion para asignar los parametros de una view
     * @version 1.181.34
     * @param array $campo Campo a validar elementos
     * @param array $bools conjunto de campos de tipo bool en bd activo o inactivo
     * @param stdClass $datos Datos a validar
     * @return array
     */
    private function asigna_data_campo(array $bools, array $campo, stdClass $datos): array
    {



        $datas = $this->init_data(bools:  $bools, campo: $campo,datos:  $datos);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializa $datos',data: $datas);
        }

        if(!isset($datas->campo['adm_elemento_lista_cols'])){
            $datas->campo['adm_elemento_lista_cols'] = 12;
        }
        if(!isset($datas->campo['adm_elemento_lista_tipo'])){
            $datas->campo['adm_elemento_lista_tipo'] = 'text';
        }
        if(!isset($datas->campo['adm_elemento_lista_tabla_externa'])){
            $datas->campo['adm_elemento_lista_tabla_externa'] = '';
        }
        if(!isset($datas->campo['adm_elemento_lista_etiqueta'])){
            $datas->campo['adm_elemento_lista_etiqueta'] = '';
        }
        if(!isset($datas->campo['adm_elemento_lista_campo'])){
            $datas->campo['adm_elemento_lista_campo'] = '';
        }
        if(!isset($datas->campo['adm_elemento_lista_descripcion'])){
            $datas->campo['adm_elemento_lista_descripcion'] = '';
        }
        if(!isset($datas->campo['adm_elemento_lista_id'])){
            $datas->campo['adm_elemento_lista_id'] = '';
        }

        if(!is_array($datas->datos->valor_extra)){
            $datas->datos->valor_extra = array();
        }

        if(!isset($datas->campo['disabled']) || $datas->campo['disabled'] === '' || $datas->campo['disabled'] === 'inactivo'){
            $datas->campo['disabled'] = false;
        }
        if(isset($datas->campo['disabled']) && $datas->campo['disabled'] === 'activo'){
            $datas->campo['disabled'] = true;
        }


        $data['cols'] = $datas->campo['adm_elemento_lista_cols'];
        $data['disabled'] = $datas->campo['disabled'];
        $data['con_label'] = $datas->bools['con_label'];
        $data['required'] = $datas->bools['required'];
        $data['tipo'] = $datas->campo['adm_elemento_lista_tipo'];
        $data['llaves_foraneas'] = $datas->datos->llaves;
        $data['vista'] = array($datas->datos->vista);
        $data['ln'] = $datas->bools['ln'];
        $data['tabla_foranea'] = $datas->campo['adm_elemento_lista_tabla_externa'];
        $data['columnas'] = $datas->datos->columnas;
        $data['pattern'] = $datas->datos->pattern;
        $data['select_vacio_alta'] = $datas->bools['select_vacio_alta'];
        $data['etiqueta'] = $datas->campo['adm_elemento_lista_etiqueta'];
        $data['campo_tabla_externa'] = $datas->datos->tabla_externa;
        $data['campo_name'] = $datas->campo['adm_elemento_lista_campo'];
        $data['campo'] = $datas->campo['adm_elemento_lista_descripcion'];
        $data['tabla_externa_renombrada'] = $datas->datos->externa_renombrada;
        $data['data_extra'] = $datas->datos->valor_extra;
        $data['separador_select_columnas'] = $datas->datos->separador;
        $data['representacion'] = $datas->datos->representacion;
        $data['css_id'] = $datas->datos->css_id;
        $data['adm_elemento_lista_id'] =$datas->campo['adm_elemento_lista_id'];

        return $data;
    }

    /**
     * REG
     * Desencripta los valores de un array asociativo basado en los campos definidos como encriptados.
     *
     * Este método:
     * 1. Itera sobre los elementos de `$row` (nombre del campo y su valor asociado).
     * 2. Valida que el nombre del campo (`$campo`) no sea numérico.
     * 3. Si el campo está en la lista de `$campos_encriptados`, desencripta su valor utilizando el método `value_desencriptado`.
     * 4. Reemplaza el valor original en `$row` con su valor desencriptado (si aplica).
     *
     * @param array $campos_encriptados Lista de nombres de campos que están definidos como encriptados.
     * @param array $row Array asociativo con los campos y valores a procesar.
     *
     * @return array
     *   - Retorna el array `$row` con los valores desencriptados en los campos especificados.
     *   - Si no hay campos encriptados en `$row`, devuelve el array sin modificaciones.
     *   - Si ocurre un error, retorna un arreglo con los detalles del error.
     *
     * @example
     *  Ejemplo 1: Desencriptar valores de un array
     *  --------------------------------------------
     *  $campos_encriptados = ['clave', 'token'];
     *  $row = [
     *      'id' => 1,
     *      'nombre' => 'Juan Perez',
     *      'clave' => 'ValorEncriptado123',
     *      'token' => 'OtroValorEncriptado456'
     *  ];
     *
     *  $resultado = $this->asigna_valor_desencriptado($campos_encriptados, $row);
     *  // $resultado será:
     *  // [
     *  //     'id' => 1,
     *  //     'nombre' => 'Juan Perez',
     *  //     'clave' => 'ValorDesencriptado1',
     *  //     'token' => 'ValorDesencriptado2'
     *  // ]
     *
     * @example
     *  Ejemplo 2: Error en la clave del campo
     *  --------------------------------------
     *  $campos_encriptados = ['clave', 'token'];
     *  $row = [
     *      0 => 'ValorIncorrecto'
     *  ];
     *
     *  $resultado = $this->asigna_valor_desencriptado($campos_encriptados, $row);
     *  // Retorna un arreglo con el error:
     *  // [
     *  //   'error' => 1,
     *  //   'mensaje' => 'Error el campo debe ser un texto',
     *  //   'data' => 0,
     *  //   'fix' => 'El campo dentro de row debe ser un texto no numérico...'
     *  // ]
     *
     * @throws array Si ocurre un error durante el desencriptado o el nombre del campo es numérico, retorna un arreglo de error.
     */
    final public function asigna_valor_desencriptado(array $campos_encriptados, array $row): array
    {
        // Itera sobre los campos del array
        foreach ($row as $campo => $value) {
            // Valida que el nombre del campo no sea numérico
            if (is_numeric($campo)) {
                $fix = 'El campo dentro de row debe ser un texto no numérico, puede ser id, registro, etc. No puede ';
                $fix .= 'ser 0, 1 o cualquier número. Ejemplo de row: $row["id"] o $row["campo"], no $row[0].';
                return $this->error->error(
                    mensaje: 'Error el campo debe ser un texto',
                    data: $campo,
                    es_final: true,
                    fix: $fix
                );
            }

            // Desencripta el valor si el campo está en la lista de campos encriptados
            $value_enc = $this->value_desencriptado(
                campo: $campo,
                campos_encriptados: $campos_encriptados,
                value: $value
            );
            if (errores::$error) {
                return $this->error->error(
                    mensaje: 'Error al desencriptar',
                    data: $value_enc
                );
            }

            // Reemplaza el valor original con el desencriptado
            $row[$campo] = $value_enc;
        }

        return $row;
    }


    /**
     * REG
     * Encripta un valor y lo asigna a un campo específico dentro de un registro.
     *
     * Este método:
     * 1. Valida que el objeto `$campo_limpio` contenga las claves `valor` y `campo`.
     * 2. Valida que el campo especificado en `$campo_limpio->campo` exista en `$registro` y no esté vacío.
     * 3. Encripta el valor contenido en `$campo_limpio->valor` usando la clase `encriptador`.
     * 4. Asigna el valor encriptado al campo dentro del `$registro`.
     * 5. Retorna el `$registro` actualizado.
     * 6. Si ocurre un error en cualquier validación o en el proceso de encriptación, devuelve un arreglo con información del error.
     *
     * ---
     *
     * ### Ejemplo de Uso:
     * ```php
     * $inicializacion = new inicializacion();
     *
     * // Registro de ejemplo
     * $registro = [
     *     'clave' => 'MiClave123'
     * ];
     *
     * // Datos a encriptar
     * $campo_limpio = new stdClass();
     * $campo_limpio->campo = 'clave';
     * $campo_limpio->valor = 'MiClave123';
     *
     * // Ejecutar encriptación
     * $resultado = $inicializacion->asigna_valor_encriptado($campo_limpio, $registro);
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //     'clave' => 'ValorEncriptadoABC123'
     * // ]
     *
     * // Caso 2: Error por falta de clave en $campo_limpio
     * $campo_limpio = new stdClass();
     * $campo_limpio->campo = 'clave';
     *
     * $resultado = $inicializacion->asigna_valor_encriptado($campo_limpio, $registro);
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //   'error' => 1,
     * //   'mensaje' => 'Error al validar campo_limpio',
     * //   'data' => 'valor no está definido en campo_limpio',
     * //   'es_final' => true
     * // ]
     * ```
     *
     * ---
     *
     * @param stdClass $campo_limpio Objeto que contiene la información del campo a encriptar.
     *                               Debe incluir:
     *                               - `campo`: Nombre del campo en el registro.
     *                               - `valor`: Valor que será encriptado.
     * @param array $registro Registro en el cual se asignará el valor encriptado.
     *
     * @return array Retorna el `$registro` con el valor encriptado asignado.
     *               Si ocurre un error, retorna un arreglo con detalles del error.
     *
     * @throws array Si no se encuentran las claves necesarias en `$campo_limpio` o `$registro`,
     *               o si ocurre un error durante la encriptación.
     */
    private function asigna_valor_encriptado(stdClass $campo_limpio, array $registro): array
    {
        $keys = array('valor','campo');
        $valida = $this->validacion->valida_existencia_keys(keys:  $keys, registro: $campo_limpio,valida_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar campo_limpio', data: $valida);
        }

        $keys = array('campo');
        $valida = $this->validacion->valida_existencia_keys(keys:  $keys, registro: $campo_limpio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar campo_limpio', data: $valida);
        }

        $keys = array($campo_limpio->campo);
        $valida = $this->validacion->valida_existencia_keys(keys:  $keys, registro: $registro, valida_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }

        $valor = (new encriptador())->encripta(valor:$campo_limpio->valor);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al encriptar valor del campo', data: $valor);
        }

        $registro[$campo_limpio->campo] = $valor;
        return $registro;
    }


    private function carga_atributos(stdClass $attr, array $keys, modelo $modelo){
        foreach ($attr->registros as $atributo){
            $attrs = $this->integra_atributos(atributo: $atributo,keys:  $keys,modelo:  $modelo);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al asignar atributos ', data: $attrs);
            }
        }
        return $modelo->atributos;
    }

    /**
     * Integra los datos para in in sql
     * @param string $llave LLave= tabla.campo
     * @param array $values_in Conjunto de valores para un in SQL
     * @return array
     * @version 1.527.51
     */
    private function data_in_sql(string $llave, array $values_in): array
    {
        $llave = trim($llave);
        if($llave === ''){
            return $this->error->error(mensaje: 'Error llave no puede venir vacia', data: $llave);
        }

        if(count($values_in) === 0){
            return $this->error->error(mensaje: 'Error values_in no puede venir vacios', data: $values_in);
        }

        $in = array();
        $in['llave'] = $llave;
        $in['values'] = $values_in;
        return $in;
    }

    /**
     * REG
     * Encripta un valor de un registro si el campo está dentro de los campos encriptados.
     *
     * Este método:
     * 1. Valida que el valor proporcionado no sea un array ni un objeto iterable.
     * 2. Valida que el campo no esté vacío.
     * 3. Verifica que el campo exista en el `$registro` y no esté vacío.
     * 4. Limpia los valores del campo antes de proceder con la encriptación.
     * 5. Si el campo se encuentra en `$campos_encriptados`, encripta su valor y lo asigna al `$registro`.
     * 6. Retorna el `$registro` con el valor encriptado (si aplica).
     * 7. Si ocurre un error en alguna validación o en la encriptación, retorna un arreglo con información del error.
     *
     * ---
     *
     * ### Ejemplo de Uso:
     * ```php
     * $inicializacion = new inicializacion();
     *
     * // Registro de ejemplo
     * $registro = [
     *     'clave' => 'MiClave123',
     *     'nombre' => 'Juan Pérez'
     * ];
     *
     * // Lista de campos que deben ser encriptados
     * $campos_encriptados = ['clave'];
     *
     * // Encriptar el campo 'clave'
     * $resultado = $inicializacion->encripta_valor_registro('clave', $campos_encriptados, $registro, 'MiClave123');
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //     'clave' => 'ValorEncriptadoXYZ789',
     * //     'nombre' => 'Juan Pérez'
     * // ]
     *
     * // Caso 2: Intentar encriptar un campo que no está en la lista de campos encriptados
     * $resultado = $inicializacion->encripta_valor_registro('nombre', $campos_encriptados, $registro, 'Juan Pérez');
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //     'clave' => 'ValorEncriptadoXYZ789',
     * //     'nombre' => 'Juan Pérez'
     * // ]
     *
     * // Caso 3: Error por campo vacío
     * $resultado = $inicializacion->encripta_valor_registro('', $campos_encriptados, $registro, 'MiClave123');
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //   'error' => 1,
     * //   'mensaje' => 'Error campo no puede venir vacio',
     * //   'data' => '',
     * //   'es_final' => true
     * // ]
     *
     * // Caso 4: Error por valor iterable
     * $resultado = $inicializacion->encripta_valor_registro('clave', $campos_encriptados, $registro, ['ArrayNoValido']);
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //   'error' => 1,
     * //   'mensaje' => 'Error valor no puede ser iterable',
     * //   'data' => ['ArrayNoValido'],
     * //   'es_final' => true
     * // ]
     * ```
     *
     * ---
     *
     * @param string $campo Nombre del campo a verificar y encriptar si es necesario.
     * @param array $campos_encriptados Lista de campos que deben ser encriptados.
     * @param array $registro Registro que contiene el campo y su valor correspondiente.
     * @param mixed $valor Valor a encriptar, si corresponde.
     *
     * @return array Retorna el `$registro` con el valor encriptado si aplica.
     *               Si el campo no está en `$campos_encriptados`, retorna el registro sin cambios.
     *               En caso de error, retorna un arreglo con los detalles del error.
     *
     * @throws array Si el campo está vacío, el valor es iterable o si ocurre un error en la encriptación.
     */
    private function encripta_valor_registro(string $campo, array $campos_encriptados, array $registro,
                                             mixed $valor): array
    {
        // Validar que el valor no sea iterable
        if (is_iterable($valor)) {
            return $this->error->error(
                mensaje: 'Error valor no puede ser iterable',
                data: $valor,
                es_final: true
            );
        }

        // Eliminar espacios en blanco del campo y del valor
        $campo = trim($campo);
        if (!is_null($valor)) {
            $valor = trim($valor);
        }

        // Validar que el campo no esté vacío
        if ($campo === '') {
            return $this->error->error(
                mensaje: 'Error campo no puede venir vacio',
                data: $campo,
                es_final: true
            );
        }

        // Validar que el campo exista en el registro y no esté vacío
        $keys = array($campo);
        $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $registro, valida_vacio: false);
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al validar registro',
                data: $valida
            );
        }

        // Limpiar valores antes de proceder
        $campo_limpio = $this->limpia_valores(campo: $campo, valor: $valor);
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al limpiar valores' . $campo,
                data: $campo_limpio
            );
        }

        // Si el campo está en la lista de campos encriptados, encriptarlo
        if (in_array($campo_limpio->campo, $campos_encriptados, true)) {
            $registro = $this->asigna_valor_encriptado(campo_limpio: $campo_limpio, registro: $registro);
            if (errores::$error) {
                return $this->error->error(
                    mensaje: 'Error al asignar campo encriptado' . $campo,
                    data: $registro
                );
            }
        }

        return $registro;
    }


    /**
     * REG
     * Encripta los valores de un registro si sus campos están en la lista de campos encriptados.
     *
     * Este método:
     * 1. Verifica que el `$registro` no esté vacío.
     * 2. Recorre todos los campos del `$registro` y valida que sus valores no sean iterables.
     * 3. Si el campo está en la lista de `$campos_encriptados`, encripta su valor usando `encripta_valor_registro()`.
     * 4. Retorna el `$registro` con los valores encriptados cuando corresponde.
     * 5. Si ocurre un error en la validación o en la encriptación, retorna un arreglo con información del error.
     *
     * ---
     *
     * ### Ejemplo de Uso:
     * ```php
     * $inicializacion = new inicializacion();
     *
     * // Registro de ejemplo
     * $registro = [
     *     'clave' => 'MiClave123',
     *     'nombre' => 'Juan Pérez',
     *     'token' => 'TokenSecreto456'
     * ];
     *
     * // Lista de campos que deben ser encriptados
     * $campos_encriptados = ['clave', 'token'];
     *
     * // Encriptar los valores del registro
     * $resultado = $inicializacion->encripta_valores_registro($campos_encriptados, $registro);
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //     'clave' => 'ValorEncriptadoXYZ789',
     * //     'nombre' => 'Juan Pérez',
     * //     'token' => 'ValorEncriptadoABC123'
     * // ]
     *
     * // Caso 2: Registro vacío
     * $resultado = $inicializacion->encripta_valores_registro($campos_encriptados, []);
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //   'error' => 1,
     * //   'mensaje' => 'Error el registro no puede venir vacio',
     * //   'data' => [],
     * //   'es_final' => true
     * // ]
     *
     * // Caso 3: Error por valor iterable
     * $registro = [
     *     'clave' => ['ArrayNoValido']
     * ];
     * $resultado = $inicializacion->encripta_valores_registro($campos_encriptados, $registro);
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //   'error' => 1,
     * //   'mensaje' => 'Error valor no puede ser iterable',
     * //   'data' => ['ArrayNoValido'],
     * //   'es_final' => true
     * // ]
     * ```
     *
     * ---
     *
     * @param array $campos_encriptados Lista de campos que deben ser encriptados.
     * @param array $registro Registro con los datos a procesar.
     *
     * @return array Retorna el `$registro` con los valores encriptados si corresponde.
     *               Si el `$registro` está vacío o ocurre un error, retorna un arreglo con detalles del error.
     *
     * @throws array Si el `$registro` está vacío o si un valor es iterable.
     */
    private function encripta_valores_registro(array $campos_encriptados, array $registro): array
    {
        // Validar que el registro no esté vacío
        if (count($registro) === 0) {
            return $this->error->error(
                mensaje: 'Error el registro no puede venir vacio',
                data: $registro,
                es_final: true
            );
        }

        // Recorrer cada campo del registro
        foreach ($registro as $campo => $valor) {
            // Validar que el valor no sea iterable
            if (is_iterable($valor)) {
                return $this->error->error(
                    mensaje: 'Error valor no puede ser iterable',
                    data: $valor,
                    es_final: true
                );
            }

            // Encriptar el valor si corresponde
            $registro = $this->encripta_valor_registro(
                campo: $campo,
                campos_encriptados: $campos_encriptados,
                registro: $registro,
                valor: $valor
            );

            // Verificar si ocurrió un error al encriptar
            if (errores::$error) {
                return $this->error->error(
                    mensaje: 'Error al asignar campo encriptado ' . $campo,
                    data: $registro
                );
            }
        }

        return $registro;
    }


    private function genera_atributos(stdClass $attr, modelo $modelo){
        $keys = array('Null','Key','Default','Extra');

        $attrs = $this->inicializa_atributos(attr: $attr,modelo:  $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar atributos ', data: $attrs);

        }

        $attrs = $this->carga_atributos(attr: $attr,keys:  $keys, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al asignar atributos ', data: $attrs);
        }
        return $attrs;
    }

    public function genera_data_in(string $campo, string $tabla,array $registros): array
    {
        $values_in = $this->values_in(key_value: $tabla.'_'.$campo, rows: $registros);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener values in',data:  $values_in);
        }

        $in = $this->data_in_sql(llave:$tabla.'.'.$campo, values_in: $values_in);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar in',data:  $in);
        }
        return $in;
    }

    /**
     * Obtiene los atributos de un modelo
     * @param modelo $modelo Modelo a obtener atributos
     * @return array|stdClass
     * @version 9.14.0
     *
     */
    private function get_atributos_db(modelo $modelo): array|stdClass
    {
        $tabla = trim($modelo->tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error tabla esta vacia', data: $tabla);
        }
        $sql = (new sql())->describe_table(tabla: $modelo->tabla);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener sql ', data: $sql);
        }

        $attr = $modelo->ejecuta_consulta(consulta: $sql);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener attr ', data: $attr);
        }
        return $attr;
    }

    /**
     * @param stdClass $attr
     * @param modelo $modelo
     * @return array|stdClass
     */
    private function inicializa_atributos(stdClass $attr, modelo $modelo): array|stdClass
    {
        foreach ($attr->registros as $atributo){
            $attrs = $this->init_atributo(atributo: $atributo,modelo:  $modelo);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al inicializar atributos ', data: $attrs);
            }
        }
        return $modelo->atributos;
    }

    public function inicializa_statuses(array $keys, array $registro): array
    {
        foreach ($keys as $key) {
            $registro = $this->aplica_status_inactivo(key: $key, registro: $registro);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al inicializa status', data: $registro);
            }
        }
        return $registro;

    }

    /**
     * Inicializa un field en attr
     * @param array $atributo Atributo
     * @param modelo $modelo Modelo en ejecucion
     * @return stdClass
     */
    private function init_atributo(array $atributo, modelo $modelo): stdClass
    {
        $field = $atributo['Field'];
        $modelo->atributos->$field = new stdClass();
        return $modelo->atributos;
    }

    /**
     * Inicializa valores booleanos
     * @version 1.148.31
     * @param array $bools conjunto de campos de tipo bool en bd activo o inactivo
     * @return array
     */
    private function init_bools(array $bools): array
    {
        $keys = array('con_label','required','ln','select_vacio_alta', 'disabled');
        foreach ($keys as $key){
            if(!isset($bools[$key])){
                $bools[$key] = '';
            }
        }
        return $bools;
    }

    /**
     * Inicializa un campo a todo vacio
     * @version 1.104.25
     * @param array $campo Campo a validar elementos
     * @return array
     */
    private function init_campo(array $campo): array
    {
        $keys = array('elemento_lista_cols','elemento_lista_tipo','elemento_lista_tabla_externa',
            'elemento_lista_etiqueta','elemento_lista_campo','elemento_lista_descripcion','elemento_lista_id');
        foreach ($keys as $key){
            if(!isset($campo[$key])){
                $campo[$key] = '';
            }
        }
        return $campo;
    }

    /**
     *
     * @param array $campo Campo a validar elementos
     * @version 1.172.33
     * @param array $bools conjunto de campos de tipo bool en bd activo o inactivo
     * @param stdClass $datos Datos a verificar
     * @return array|stdClass
     */
    private function init_data( array $bools, array $campo, stdClass $datos): array|stdClass
    {
        $campo = $this->init_campo(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializa $campo',data: $campo);
        }

        $bools = $this->init_bools(bools: $bools);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializa $bools',data: $bools);
        }

        $datos = $this->init_datos(datos: $datos);
        if(errores::$error){
            return $this->error->error('Error al inicializa $datos',$datos);
        }

        $data = new stdClass();
        $data->campo = $campo;
        $data->bools = $bools;
        $data->datos = $datos;
        return $data;
    }

    /**
     * Inicializa datos para campos
     * @version 1.148.31
     * @param stdClass $datos Datos a verificar
     * @return stdClass
     */
    private function init_datos(stdClass $datos): stdClass
    {
        $keys = array('llaves','vista','columnas','pattern','tabla_externa','externa_renombrada','valor_extra',
            'separador','representacion','css_id');
        foreach ($keys as $key){
            if(!isset($datos->$key)){
                $datos->$key = '';
            }
        }
        return $datos;
    }

    /** Inicializa un key a inactivo
     * @param string $key Key a integrar
     * @param array $registro Registro en proceso
     * @return array
     *
     *
     */
    private function init_key_status_inactivo(string $key, array $registro): array
    {
        $registro[$key] = 'inactivo';
        return $registro;
    }

    /**
     * REG
     * Inicializa la propiedad "params" del objeto complemento.
     *
     * Este método asigna un nuevo objeto `stdClass` a la propiedad `params` del objeto `$complemento` y define
     * los atributos `offset`, `group_by`, `order` y `limit` con cadenas vacías. Estos atributos se utilizan para almacenar
     * parámetros adicionales en consultas SQL, como el desplazamiento (offset), la cláusula de agrupación (group_by),
     * el ordenamiento (order) y el límite (limit).
     *
     * @param stdClass $complemento Objeto complementario al que se asignarán los parámetros de consulta SQL.
     *
     * @return stdClass Retorna el objeto `$complemento` con la propiedad `params` inicializada.
     *
     * @example
     * <pre>
     * // Ejemplo de uso:
     * $complemento = new stdClass();
     * $complemento = $this->init_params($complemento);
     *
     * // Resultado esperado:
     * // $complemento->params->offset   => ""
     * // $complemento->params->group_by => ""
     * // $complemento->params->order    => ""
     * // $complemento->params->limit    => ""
     * </pre>
     *
     * @version 1.0.0
     */
    private function init_params(stdClass $complemento): stdClass
    {
        $complemento->params = new stdClass();
        $complemento->params->offset = '';
        $complemento->params->group_by = '';
        $complemento->params->order = '';
        $complemento->params->limit = '';
        return $complemento;
    }


    /**
     * TOTAL
     * Método para inicializar los datos que serán actualizados en un modelo.
     *
     * @param int $id El identificador único del registro.
     * @param modelo $modelo Una instancia del modelo donde se realizará la actualización.
     * @param array $registro Los datos que se utilizarán para la actualización.
     * @param bool $valida_row_vacio Un parámetro opcional que sirve para validar si el registro está vacío.
     *
     * @return array|stdClass Devuelve un objeto con los datos de actualización o un array en caso de error.
     * @version 16.267.1
     * @url https://github.com/gamboamartin/administrador/wiki/administrador.base.orm.inicializacion.init_upd
     */
    final public function init_upd(
        int $id, modelo $modelo, array $registro, bool $valida_row_vacio = true): array|stdClass
    {
        $registro_original = $registro;
        $registro = (new columnas())->campos_no_upd(campos_no_upd: $modelo->campos_no_upd, registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al ajustar camp no upd',data: $registro);
        }


        $modelo->registro_upd = $registro;
        $modelo->registro_id = $id;

        $valida = (new validaciones())->valida_upd_base(id:$id, registro_upd: $modelo->registro_upd,
            valida_row_vacio: $valida_row_vacio);
        if(errores::$error){
            $datos = serialize($registro);
            $registro_original = serialize($registro_original);
            $mensaje = "Error al validar datos del modelo ";
            $mensaje .= $modelo->tabla." del id $id";
            $mensaje .= " registro procesado $datos";
            $mensaje .= " registro original $registro_original";
            return $this->error->error(mensaje: $mensaje, data: $valida);
        }

        $data = new stdClass();
        $data->registro_upd = $modelo->registro_upd;
        $data->id = $modelo->registro_id;

        return $data;
    }

    private function integra_attr(array $atributo, string $field, string $key, modelo $modelo){
        $key_new = $this->normaliza_key_db(key: $key);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al normalizar $key', data: $key_new);
        }

        $attr_r = $this->asigna_data_attr(atributo: $atributo,field:  $field,key:  $key, key_new: $key_new,modelo:  $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al asignar atributo ', data: $attr_r);
        }
        return $attr_r;
    }

    final public function integra_attrs(modelo $modelo){
        if(!isset($_SESSION[$modelo->tabla]['atributos'])) {
            $attr = $this->get_atributos_db(modelo: $modelo);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener attr ', data: $attr);
            }

            $attrs = $this->genera_atributos(attr: $attr, modelo: $modelo);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al inicializar atributos ', data: $attrs);
            }
            $_SESSION[$modelo->tabla]['atributos'] = $modelo->atributos;
        }
        else{
            $attrs = $modelo->atributos = $_SESSION[$modelo->tabla]['atributos'];
        }
        return $attrs;
    }

    private function integra_atributos(array $atributo, array $keys, modelo $modelo){
        $field = $atributo['Field'];

        foreach ($keys as $key){
            $attr_r =$this->integra_attr(atributo: $atributo,field:  $field, key: $key, modelo: $modelo);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al asignar atributo ', data: $attr_r);
            }
        }
        return $modelo->atributos;
    }

    /**
     * Integra un value para ser utilizado en un IN
     * @param string $value Valor a integrar
     * @param array $values_in Valores previos
     * @return array
     * @version 1.526.51
     */
    private function integra_value_in(string $value, array $values_in): array
    {
        $values_in[] = $value;
        return $values_in;
    }


    /**
     * REG
     * Limpia y normaliza los valores de un campo, eliminando espacios en blanco.
     *
     * Este método:
     * 1. Elimina los espacios en blanco al inicio y al final de la cadena en `$campo` y `$valor`.
     * 2. Valida que el `$campo` no esté vacío.
     * 3. Retorna un objeto `stdClass` con los valores limpios en caso de éxito.
     * 4. En caso de error, retorna un arreglo de error detallando el problema.
     *
     * ---
     *
     * ### Ejemplo de Uso:
     * ```php
     * $inicializacion = new inicializacion();
     *
     * // Caso 1: Limpieza de un campo con valores normales
     * $resultado = $inicializacion->limpia_valores(' nombre ', '  Juan Pérez  ');
     * print_r($resultado);
     * // Salida esperada:
     * // stdClass Object (
     * //     [campo] => nombre
     * //     [valor] => Juan Pérez
     * // )
     *
     * // Caso 2: Error por campo vacío
     * $resultado = $inicializacion->limpia_valores(' ', 'Valor');
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //   'error' => 1,
     * //   'mensaje' => 'Error campo no puede venir vacio',
     * //   'data' => '',
     * //   'es_final' => true
     * // ]
     * ```
     *
     * ---
     *
     * @param string $campo Nombre del campo a limpiar.
     * @param string $valor Valor asociado al campo que también será limpiado.
     *
     * @return stdClass|array Retorna un objeto `stdClass` con los valores limpios si la validación es exitosa.
     *                        Si el campo está vacío, retorna un arreglo con los detalles del error.
     *
     * @throws array Si `$campo` está vacío, retorna un arreglo de error con el mensaje correspondiente.
     */
    private function limpia_valores(string $campo, string $valor): stdClass|array
    {
        $campo = trim($campo);
        $valor = trim($valor);

        if ($campo === '') {
            return $this->error->error(
                mensaje: 'Error campo no puede venir vacio',
                data: $campo,
                es_final: true
            );
        }

        $data = new stdClass();
        $data->campo = $campo;
        $data->valor = $valor;
        return $data;
    }


    /**
     *
     * Funcion para maquetar un array para ser mostrado en las vistas base
     * @version 1.182.34
     * @param array $campo datos del campo
     * @param string $vista vista para su aplicacion en views
     * @param array $valor_extra  datos para anexar extras
     * @param string $representacion para su vista en lista
     * @param array $bools datos booleanos con los keys de los campos a aplicar
     * @example
     *      $campo_envio = $this->maqueta_campo_envio($campo,$vista, $valor_extra,$representacion, $bools);
     *
     * @return array con datos para su utilizacion en views
     * @throws errores por definir
     * @uses consultas_base->inicializa_estructura
     */
    public function maqueta_campo_envio(array $bools, array $campo, string $representacion, array $valor_extra,
                                        string $vista):array{


        $valida = $this->validacion->valida_campo_envio(bools: $bools, campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al validar campo", data: $valida);
        }

        $datos = new stdClass();

        $campo_tabla_externa = (new elementos())->campo_tabla_externa(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al obtener campo_tabla_externa",data:  $campo_tabla_externa);
        }

        $elemento_lista_columnas = (new elementos())->columnas_elemento_lista(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al asignar columnas",data:  $elemento_lista_columnas);
        }

        $elemento_lista_llaves_valores = (new elementos())->llaves_valores(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al generar llaves", data: $elemento_lista_llaves_valores);
        }

        $elemento_lista_pattern = (new elementos())->pattern(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al generar pattern",data:  $elemento_lista_pattern);
        }


        $elemento_lista_tabla_externa_renombrada = (new elementos())->tabla_ext_renombrada(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al generar tabla externa",data:  $elemento_lista_tabla_externa_renombrada);
        }


        $elemento_lista_separador_select_columnas = (new elementos())->separador_columnas(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al generar separador",data:  $elemento_lista_separador_select_columnas);
        }


        $elemento_lista_css_id = (new elementos())->elemento_lista_css_id(campo: $campo);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al obtener $elemento_lista_css_id",data:  $elemento_lista_css_id);
        }

        $datos->tabla_externa = $campo_tabla_externa;
        $datos->columnas = $elemento_lista_columnas;
        $datos->llaves = $elemento_lista_llaves_valores;
        $datos->pattern = $elemento_lista_pattern;
        $datos->externa_renombrada = $elemento_lista_tabla_externa_renombrada;
        $datos->separador = $elemento_lista_separador_select_columnas;
        $datos->css_id = $elemento_lista_css_id;
        $datos->vista = $vista;
        $datos->valor_extra = $valor_extra;
        $datos->representacion = $representacion;

        $datos = $this->asigna_data_campo(bools: $bools, campo: $campo, datos: $datos);
        if(errores::$error){
            return $this->error->error(mensaje: "Error al asignar datos",data: $datos);
        }

        return $datos;

    }

    /**
     * @param string $key
     * @return string
     */
    private function normaliza_key_db(string $key): string
    {
        $key_new = trim($key);
        $key_new = str_replace(' ','',$key_new);
        return strtolower($key_new);
    }

    /**
     * REG
     * Prepara un registro para inserción en la base de datos, asignando estado, ajustando valores monetarios
     * y encriptando campos según sea necesario.
     *
     * Este método realiza las siguientes acciones:
     * 1. **Valida el parámetro `$status_default`** asegurando que no esté vacío.
     * 2. **Asigna el estado (`status`) al registro** si la integración de datos base está activada.
     * 3. **Ajusta los valores de tipo moneda** en el registro para eliminar caracteres no numéricos como `$` y `,`.
     * 4. **Encripta los valores de los campos especificados** en `$campos_encriptados`.
     * 5. **Retorna el registro modificado**, listo para ser insertado en la base de datos.
     *
     * ---
     *
     * ### Ejemplo de Uso:
     * ```php
     * $inicializacion = new inicializacion();
     *
     * // Registro de ejemplo
     * $registro = [
     *     'id' => 1,
     *     'nombre' => 'Juan Pérez',
     *     'salario' => '$10,000.50',
     *     'password' => 'miClaveSegura'
     * ];
     *
     * // Campos que deben ser encriptados
     * $campos_encriptados = ['password'];
     *
     * // Tipo de datos para los campos
     * $tipo_campos = [
     *     'salario' => 'moneda'
     * ];
     *
     * // Preparar el registro para inserción
     * $resultado = $inicializacion->registro_ins(
     *     $campos_encriptados,
     *     true,                // Integrar datos base
     *     $registro,           // Registro original
     *     'activo',            // Estado por defecto
     *     $tipo_campos         // Tipos de datos
     * );
     *
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //     'id' => 1,
     * //     'nombre' => 'Juan Pérez',
     * //     'salario' => '10000.50',
     * //     'password' => 'ValorEncriptadoXYZ789',
     * //     'status' => 'activo'
     * // ]
     *
     * // Caso 2: Error por `status_default` vacío
     * $resultado = $inicializacion->registro_ins($campos_encriptados, true, $registro, '', $tipo_campos);
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //   'error' => 1,
     * //   'mensaje' => 'Error status_default no puede venir vacio',
     * //   'data' => '',
     * //   'es_final' => true
     * // ]
     * ```
     *
     * ---
     *
     * @param array $campos_encriptados Lista de campos que deben ser encriptados antes de la inserción.
     * @param bool $integra_datos_base Indica si se debe integrar el estado predeterminado en el registro.
     * @param array $registro Registro original que se insertará en la base de datos.
     * @param string $status_default Estado predeterminado para el registro (ejemplo: 'activo' o 'inactivo').
     * @param array $tipo_campos Lista de tipos de datos de los campos, donde los valores pueden ser 'moneda' o 'double'.
     *
     * @return array Retorna el registro listo para ser insertado en la base de datos.
     *               Si ocurre un error, retorna un arreglo con los detalles del error.
     *
     * @throws array Si `$status_default` está vacío o si ocurre un error en la validación o encriptación.
     */
    final public function registro_ins(array $campos_encriptados, bool $integra_datos_base, array $registro,
                                       string $status_default, array $tipo_campos): array
    {
        // Validar que el status predeterminado no esté vacío
        $status_default = trim($status_default);
        if ($status_default === '') {
            return $this->error->error(
                mensaje: 'Error status_default no puede venir vacio',
                data: $status_default,
                es_final: true
            );
        }

        // Asignar status al registro si corresponde
        $registro = $this->status(
            integra_datos_base: $integra_datos_base,
            registro: $registro,
            status_default: $status_default
        );
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al asignar status',
                data: $registro
            );
        }

        // Ajustar los valores de tipo moneda en el registro
        $registro = (new data_format())->ajusta_campos_moneda(
            registro: $registro,
            tipo_campos: $tipo_campos
        );
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al asignar campo ',
                data: $registro
            );
        }

        // Encriptar los valores de los campos especificados
        $registro = $this->encripta_valores_registro(
            campos_encriptados: $campos_encriptados,
            registro: $registro
        );
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al asignar campos encriptados',
                data: $registro
            );
        }

        return $registro;
    }


    /**
     * POR DOCUMENTAR EN WIKI
     * Método privado que valida y realiza asignaciones a un objeto stdClass en base a la key proporcionada.
     *
     * @param string $campo        El nombre del campo del stdClass que se quiere validar y asignar.
     * @param stdClass $registro_previo   Objeto stdClass que será validado y modificado.
     *
     * @return stdClass|array   Devuelve el objeto stdClass modificado o un arreglo de errores.
     *
     * @throws errores   Si el $campo proporcionado está vacío.
     * @version 16.275.1
     */
    private function registro_previo_null(string $campo, stdClass $registro_previo): stdClass|array
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio', data: $campo, es_final: true);
        }
        if(!isset($registro_previo->$campo)){
            $registro_previo->$campo = '';
        }
        if(is_null($registro_previo->$campo)){
            $registro_previo->$campo = '';
        }
        return $registro_previo;

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Esta función personalizada en PHP se utiliza para generar un resultado de advertencia durante la actualización de un registro.
     *
     * @param int $id Es el identificador del registro a actualizar.
     * @param array $registro_upd Es una matriz que contiene los datos actualizados del registro.
     * @param stdClass $resultado Es un objeto que contiene el resultado de la operación de actualización.
     *
     * @return stdClass Retorna un objeto que contiene datos sobre los resultados de la operación:
     *          - 'mensaje': un mensaje de clarificación sobre los resultados de la operación.
     *          - 'sql': una cadena vacía.
     *          - 'result': una cadena vacía.
     *          - 'registro': la matriz de los datos actualizados del registro.
     *          - 'registro_id': el identificador del registro a actualizar.
     *          - 'salida': un estado de 'advertencia' que indica que no se realizó ninguna actualización.
     *
     * @final
     * @public
     * @version 16.280.1
     */
    final public function result_warning_upd(int $id, array $registro_upd, stdClass $resultado): stdClass
    {
        $mensaje = 'Info no hay elementos a modificar';
        $resultado->mensaje = $mensaje;
        $resultado->sql = '';
        $resultado->result = '';
        $resultado->registro = $registro_upd;
        $resultado->registro_id = $id;
        $resultado->salida = 'warning';

        return $resultado;
    }

    /**
     * REG
     * Ajusta el estado de un registro.
     *
     * Este método verifica si el registro tiene un estado (`status`). Si no lo tiene y está habilitada la opción
     * `$integra_datos_base`, se asigna el estado predeterminado proporcionado en `$status_default`.
     *
     * ---
     *
     * ### Ejemplo de Uso:
     * ```php
     * $inicializacion = new inicializacion();
     *
     * // Caso 1: Registro sin estado, con integración de datos base activada
     * $registro = [];
     * $resultado = $inicializacion->status(true, $registro, 'activo');
     * print_r($resultado);
     * // Salida esperada:
     * // ['status' => 'activo']
     *
     * // Caso 2: Registro con estado existente
     * $registro = ['status' => 'inactivo'];
     * $resultado = $inicializacion->status(true, $registro, 'activo');
     * print_r($resultado);
     * // Salida esperada:
     * // ['status' => 'inactivo']
     *
     * // Caso 3: Registro sin estado, pero sin integración de datos base
     * $registro = [];
     * $resultado = $inicializacion->status(false, $registro, 'activo');
     * print_r($resultado);
     * // Salida esperada:
     * // []
     *
     * // Caso 4: Error al pasar un `status_default` vacío
     * $registro = [];
     * $resultado = $inicializacion->status(true, $registro, '');
     * print_r($resultado);
     * // Salida esperada:
     * // [
     * //   'error' => 1,
     * //   'mensaje' => 'Error status_default no puede venir vacio',
     * //   'data' => '',
     * //   'es_final' => true
     * // ]
     * ```
     *
     * ---
     *
     * @param bool $integra_datos_base Indica si se debe integrar el estado predeterminado en caso de ausencia.
     * @param array $registro Registro en proceso de validación.
     * @param string $status_default Estado predeterminado en caso de que no exista el campo `status` en el registro.
     *
     * @return array Retorna el registro con el estado ajustado si aplica.
     *
     * @throws array Si `$status_default` está vacío, retorna un error con los detalles.
     */
    private function status(bool $integra_datos_base, array $registro, string $status_default): array
    {
        $status_default = trim($status_default);
        if ($status_default === '') {
            return $this->error->error(
                mensaje: 'Error status_default no puede venir vacio',
                data: $status_default,
                es_final: true
            );
        }

        if (!isset($registro['status'])) {
            if ($integra_datos_base) {
                $registro['status'] = $status_default;
            }
        }

        return $registro;
    }


    /**
     * REG
     * Obtiene las columnas de una tabla específica para realizar consultas SQL.
     *
     * Este método:
     * 1. Elimina el namespace del nombre de la tabla proporcionada en el modelo.
     * 2. Establece las columnas de la tabla en la estructura de base de datos del objeto `sql_bass`.
     * 3. Valida la existencia de las columnas asociadas a la tabla.
     * 4. Retorna las columnas de la tabla si existen.
     *
     * @param modelo_base $modelo Instancia del modelo que contiene información de la tabla y sus columnas.
     *                            El modelo debe tener las siguientes propiedades:
     *                            - `NAMESPACE`: Namespace del modelo.
     *                            - `tabla`: Nombre completo de la tabla (incluyendo namespace si aplica).
     *                            - `columnas`: Array con las columnas definidas para la tabla.
     *
     * @return array Retorna un array con las columnas de la tabla si existen.
     *
     * @throws array Si no existen columnas asociadas a la tabla, retorna un arreglo de error
     *               generado por `$this->error->error()`.
     *
     * @example
     *  Ejemplo 1: Obtención de columnas para una tabla
     *  ------------------------------------------------
     *  $modelo = new modelo_base();
     *  $modelo->NAMESPACE = "gamboamartin\\modelos\\";
     *  $modelo->tabla = "gamboamartin\\modelos\\usuarios";
     *  $modelo->columnas = [
     *      "id", "nombre", "email"
     *  ];
     *
     *  $resultado = $this->tablas_select($modelo);
     *  // $resultado será:
     *  // [
     *  //     "id",
     *  //     "nombre",
     *  //     "email"
     *  // ]
     *
     * @example
     *  Ejemplo 2: Error al no definir columnas en la tabla
     *  ----------------------------------------------------
     *  $modelo = new modelo_base();
     *  $modelo->NAMESPACE = "gamboamartin\\modelos\\";
     *  $modelo->tabla = "gamboamartin\\modelos\\usuarios";
     *
     *  $resultado = $this->tablas_select($modelo);
     *  // Retorna un arreglo de error:
     *  // [
     *  //     'error' => 1,
     *  //     'mensaje' => 'No existen columnas para la tabla usuarios',
     *  //     'data' => 'usuarios',
     *  //     ...
     *  // ]
     */
    final public function tablas_select(modelo_base $modelo): array
    {
        // Elimina el namespace del nombre de la tabla
        $tabla_sin_namespace = str_replace($modelo->NAMESPACE, '', $modelo->tabla);
        $modelo->tabla = $tabla_sin_namespace;

        // Inicializa la estructura de la base de datos
        $consulta_base = new sql_bass();
        $consulta_base->estructura_bd[$modelo->tabla]['columnas'] = $modelo->columnas;

        // Obtiene las columnas asociadas a la tabla
        $columnas_tabla = $consulta_base->estructura_bd[$modelo->tabla]['columnas'];

        // Valida la existencia de columnas en la tabla
        if (!isset($columnas_tabla)) {
            return $this->error->error(
                mensaje: 'No existen columnas para la tabla ' . $modelo->tabla,
                data: $modelo->tabla,
                es_final: true
            );
        }

        return $columnas_tabla;
    }


    /**
     * REG
     * Obtiene el valor desencriptado de un campo si está definido como encriptado.
     *
     * Este método:
     * 1. Valida que el nombre del campo (`$campo`) no sea un valor numérico.
     * 2. Verifica si el campo está listado en `$campos_encriptados`.
     * 3. Si el campo está encriptado, intenta desencriptar el valor proporcionado utilizando la clase `encriptador`.
     * 4. Si el campo no está encriptado, retorna el valor original sin modificaciones.
     *
     * @param string $campo Nombre del campo a verificar (por ejemplo, `id`, `nombre`, etc.).
     * @param array $campos_encriptados Lista de campos que están definidos como encriptados.
     * @param mixed $value Valor asociado al campo que podría estar encriptado.
     *
     * @return array|string|null
     *   - Retorna el valor desencriptado si el campo está en `$campos_encriptados`.
     *   - Retorna el valor original si el campo no está en `$campos_encriptados`.
     *   - Si ocurre un error, retorna un arreglo con los detalles del error.
     *
     * @example
     *  Ejemplo 1: Campo desencriptado exitosamente
     *  --------------------------------------------
     *  $campo = 'clave';
     *  $campos_encriptados = ['clave', 'token'];
     *  $value = 'ValorEncriptado123';
     *
     *  $resultado = $this->value_desencriptado($campo, $campos_encriptados, $value);
     *  // Retorna el valor desencriptado del campo "clave".
     *
     * @example
     *  Ejemplo 2: Campo no encriptado
     *  -------------------------------
     *  $campo = 'nombre';
     *  $campos_encriptados = ['clave', 'token'];
     *  $value = 'Juan Perez';
     *
     *  $resultado = $this->value_desencriptado($campo, $campos_encriptados, $value);
     *  // Retorna el valor original: "Juan Perez".
     *
     * @example
     *  Ejemplo 3: Error en el nombre del campo
     *  ----------------------------------------
     *  $campo = 123; // Nombre del campo no válido
     *  $campos_encriptados = ['clave', 'token'];
     *  $value = 'ValorEncriptado123';
     *
     *  $resultado = $this->value_desencriptado($campo, $campos_encriptados, $value);
     *  // Retorna un arreglo con el error:
     *  // [
     *  //   'error' => 1,
     *  //   'mensaje' => 'Error el campo debe ser un texto',
     *  //   'data' => 123,
     *  //   'fix' => 'El campo debe ser un texto no numérico, como id, registro, etc. No puede ser un número.',
     *  //   ...
     *  // ]
     *
     * @throws array Si el desencriptado falla o el nombre del campo no es válido, genera un arreglo con detalles del error.
     */
    private function value_desencriptado(string $campo, array $campos_encriptados, mixed $value): array|string|null
    {
        // Valida que el nombre del campo no sea numérico
        if (is_numeric($campo)) {
            $fix = 'El campo debe ser un texto no numérico, puede ser id, registro, etc. No puede ser 0, 1 u otro número.';
            return $this->error->error(
                mensaje: 'Error el campo debe ser un texto',
                data: $campo,
                es_final: true,
                fix: $fix
            );
        }

        $value_enc = $value;

        // Verifica si el campo está definido como encriptado
        if (in_array($campo, $campos_encriptados, true)) {
            // Intenta desencriptar el valor
            $value_enc = (new encriptador())->desencripta(valor: $value);
            if (errores::$error) {
                return $this->error->error(
                    mensaje: 'Error al desencriptar',
                    data: $value_enc
                );
            }
        }

        return $value_enc;
    }


    private function values_in(string $key_value, array $rows): array
    {
        $values_in = array();

        foreach ($rows as $row){
            $value = $row[$key_value];
            $values_in = $this->integra_value_in(value:$value,values_in:  $values_in);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar values in', data:$values_in);
            }
        }
        return $values_in;
    }


}
