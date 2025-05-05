<?php

namespace base\orm;

use config\database;
use gamboamartin\administrador\modelado\joins;
use gamboamartin\administrador\modelado\params_sql;
use gamboamartin\administrador\modelado\validaciones;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\errores\errores;
use JsonException;
use PDO;
use stdClass;

class modelo extends modelo_base
{

    public array $sql_seguridad_por_ubicacion;
    public array $campos_tabla = array();
    public array $extensiones_imagen = array('jpg', 'jpeg', 'png');
    public bool $aplica_transaccion_inactivo;
    public array $order = array();
    public int $limit = 0;
    public int $offset = 0;
    public array $extension_estructura = array();
    public array $renombres = array();
    public bool $validation;
    protected array $campos_encriptados;
    public array $campos_no_upd = array();
    public array $parents = array();
    public bool $valida_user = true;

    public string $etiqueta = '';

    public bool $valida_atributos_criticos = true;


    /**
     *
     * @param PDO $link Conexion a la BD
     * @param string $tabla
     * @param bool $aplica_bitacora
     * @param bool $aplica_seguridad
     * @param bool $aplica_transaccion_inactivo
     * @param bool $aplica_transacciones_base
     * @param array $campos_encriptados
     * @param array $campos_obligatorios
     * @param array $columnas
     * @param array $campos_view
     * @param array $columnas_extra
     * @param array $extension_estructura
     * @param array $no_duplicados
     * @param array $renombres
     * @param array $sub_querys
     * @param array $tipo_campos
     * @param bool $validation
     * @param array $campos_no_upd Conjunto de campos no modificables, por default id
     * @param array $parents
     * @param bool $temp
     * @param array $childrens
     * @param array $defaults
     * @param array $parents_data
     * @param array $atributos_criticos
     * @param bool $valida_atributos_criticos
     */
    public function __construct(PDO   $link, string $tabla, bool $aplica_bitacora = false, bool $aplica_seguridad = false,
                                bool  $aplica_transaccion_inactivo = true, bool $aplica_transacciones_base = true,
                                array $campos_encriptados = array(), array $campos_obligatorios = array(),
                                array $columnas = array(), array $campos_view = array(), array $columnas_extra = array(),
                                array $extension_estructura = array(), array $no_duplicados = array(),
                                array $renombres = array(), array $sub_querys = array(), array $tipo_campos = array(),
                                bool  $validation = false, array $campos_no_upd = array(), array $parents = array(),
                                bool  $temp = false, array $childrens = array(), array $defaults = array(),
                                array $parents_data = array(), array $atributos_criticos = array(),
                                bool  $valida_atributos_criticos = true)
    {


        $this->valida_atributos_criticos = $valida_atributos_criticos;

        /**
         * REFCATORIZAR
         */


        $tabla = str_replace('models\\', '', $tabla);
        parent::__construct(link: $link, aplica_transacciones_base: $aplica_transacciones_base, defaults: $defaults,
            parents_data: $parents_data, temp: $temp);

        $this->temp = false;
        $this->tabla = $tabla;
        $this->columnas_extra = $columnas_extra;
        $this->columnas = $columnas;
        $this->aplica_bitacora = $aplica_bitacora;
        $this->aplica_seguridad = $aplica_seguridad;
        $this->extension_estructura = $extension_estructura;
        $this->renombres = $renombres;
        $this->validation = $validation;
        $this->no_duplicados = $no_duplicados;
        $this->campos_encriptados = $campos_encriptados;
        $this->campos_no_upd = $campos_no_upd;
        $this->childrens = $childrens;
        $this->atributos_criticos = $atributos_criticos;

        $entidades = new estructuras(link: $link);
        $data = $entidades->entidades((new database())->db_name);
        if (errores::$error) {
            $error = $this->error->error(mensaje: 'Error al obtener entidades ' . $tabla, data: $data, class: __CLASS__,
                file: __FILE__, funcion: __FUNCTION__, line: __LINE__);
            print_r($error);
            die('Error');
        }

        if (!in_array($this->tabla, $data) && $this->valida_existe_entidad) {
            $error = $this->error->error(mensaje: 'Error no existe la entidad eb db ' . $this->tabla, data: $data);
            print_r($error);
            die('Error');
        }

        $campos_entidad = array();
        if (isset($entidades->estructura_bd->$tabla->campos)) {
            $campos_entidad = $entidades->estructura_bd->$tabla->campos;
        }

        $this->campos_entidad = $campos_entidad;


        $attrs = (new inicializacion())->integra_attrs(modelo: $this);
        if (errores::$error) {
            $error = $this->error->error(mensaje: 'Error al obtener attr ' . $tabla, data: $attrs);
            print_r($error);
            die('Error');
        }

        if ($this->valida_atributos_criticos) {
            $valida = $this->valida_atributos_criticos(atributos_criticos: $atributos_criticos);
            if (errores::$error) {
                $error = $this->error->error(mensaje: 'Error al verificar atributo critico ' . $tabla, data: $valida);
                print_r($error);
                die('Error');
            }
        }


        if (!in_array('id', $this->campos_no_upd, true)) {
            $this->campos_no_upd[] = 'id';
        }

        if (isset($_SESSION['usuario_id'])) {
            $this->usuario_id = (int)$_SESSION['usuario_id'];
        }


        $campos_tabla = (new columnas())->campos_tabla(modelo: $this, tabla: $tabla);
        if (errores::$error) {
            $error = $this->error->error(mensaje: 'Error al obtener campos tabla ' . $tabla, data: $campos_tabla);
            print_r($error);
            die('Error');
        }
        $this->campos_tabla = $campos_tabla;


        $campos_obligatorios = (new columnas())->integra_campos_obligatorios(
            campos_obligatorios: $campos_obligatorios, campos_tabla: $this->campos_tabla);
        if (errores::$error) {
            $error = $this->error->error(mensaje: 'Error al integrar campos obligatorios ' . $tabla, data: $campos_obligatorios);
            print_r($error);
            die('Error');
        }
        $this->campos_obligatorios = $campos_obligatorios;


        $this->sub_querys = $sub_querys;
        $this->sql_seguridad_por_ubicacion = array();


        $limpia = $this->campos_obligatorios(campos_obligatorios: $campos_obligatorios);
        if (errores::$error) {
            $error = $this->error->error(mensaje: 'Error al asignar campos obligatorios en ' . $tabla, data: $limpia);
            print_r($error);
            die('Error');
        }


        $this->campos_view = array_merge($this->campos_view, $campos_view);
        $this->tipo_campos = $tipo_campos;

        $this->aplica_transaccion_inactivo = $aplica_transaccion_inactivo;


        $aplica_seguridad_filter = (new seguridad_dada())->aplica_filtro_seguridad(modelo: $this);
        if (errores::$error) {
            $error = $this->error->error(mensaje: 'Error al obtener filtro de seguridad', data: $aplica_seguridad_filter);
            print_r($error);
            die('Error');
        }


        $this->key_id = $this->tabla . '_id';
        $this->key_filtro_id = $this->tabla . '.id';

        $this->etiqueta = $this->tabla;
    }


    /**
     * REG
     * Activa un registro en la base de datos y registra la transacción en la bitácora.
     *
     * Esta función realiza la activación de un registro en la base de datos,
     * validando la existencia de un ID de registro válido y asegurando que se permita
     * la ejecución de transacciones. Además, genera los datos necesarios para la activación
     * y registra la transacción en la bitácora del sistema.
     *
     * ### Funcionamiento:
     * 1. **Verifica que la propiedad `aplica_transacciones_base` esté habilitada.**
     * 2. **Si `registro_id` es mayor a 0, lo asigna a la propiedad `registro_id`.**
     * 3. **Valida que `registro_id` sea mayor a 0, de lo contrario, devuelve un error.**
     * 4. **Genera los datos de activación utilizando la clase `activaciones`.**
     * 5. **Ejecuta la transacción con `bitacoras::ejecuta_transaccion`.**
     * 6. **Devuelve un objeto con el mensaje de éxito, `registro_id` y los detalles de la transacción.**
     *
     * ### Parámetros:
     *
     * @param bool $reactiva Indica si el registro debe ser reactivado en caso de estar inactivo (por defecto `false`).
     * @param int $registro_id ID del registro a activar. Si se deja en `-1`, se usará el valor de `$this->registro_id`.
     *
     * @return array|stdClass Retorna un objeto con el mensaje de éxito, el `registro_id` y la transacción realizada,
     * o un array de error en caso de fallos.
     *
     * ### Ejemplo de uso:
     * ```php
     * $modelo = new MiModelo();
     * $resultado = $modelo->activa_bd(reactiva: true, registro_id: 15);
     * print_r($resultado);
     * ```
     *
     * ### Posibles Salidas:
     * **Caso 1: Éxito**
     * ```php
     * stdClass Object
     * (
     *     [mensaje] => "Registro activado con éxito en usuarios"
     *     [registro_id] => 15
     *     [transaccion] => Array (...) // Datos de la bitácora
     * )
     * ```
     *
     * **Caso 2: Error - Transacciones deshabilitadas**
     * ```php
     * Array
     * (
     *     [error] => "Error solo se puede transaccionar desde layout"
     *     [data] => 15
     *     [es_final] => true
     * )
     * ```
     *
     * **Caso 3: Error - ID de registro inválido**
     * ```php
     * Array
     * (
     *     [error] => "Error id debe ser mayor a 0 en usuarios"
     *     [data] => 0
     *     [es_final] => true
     * )
     * ```
     *
     * **Caso 4: Error - Fallo en la generación de datos de activación**
     * ```php
     * Array
     * (
     *     [error] => "Error al generar datos de activacion usuarios"
     *     [data] => false
     * )
     * ```
     *
     * **Caso 5: Error - Fallo en la ejecución de la transacción**
     * ```php
     * Array
     * (
     *     [error] => "Error al EJECUTAR TRANSACCION en usuarios"
     *     [data] => false
     * )
     * ```
     *
     * @throws errores Si las validaciones de transacción, activación o bitácora fallan.
     */
    public function activa_bd(bool $reactiva = false, int $registro_id = -1): array|stdClass
    {

        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro_id,
                es_final: true);
        }

        if ($registro_id > 0) {
            $this->registro_id = $registro_id;
        }
        if ($this->registro_id <= 0) {
            return $this->error->error(mensaje: 'Error id debe ser mayor a 0 en ' . $this->tabla,
                data: $this->registro_id, es_final: true);
        }

        $data_activacion = (new activaciones())->init_activa(modelo: $this, reactiva: $reactiva);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar datos de activacion ' . $this->tabla,
                data: $data_activacion);
        }

        $transaccion = (new bitacoras())->ejecuta_transaccion(tabla: $this->tabla, funcion: __FUNCTION__,
            modelo: $this, registro_id: $this->registro_id, sql: $data_activacion->consulta);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al EJECUTAR TRANSACCION en ' . $this->tabla, data: $transaccion);
        }

        $data = new stdClass();
        $data->mensaje = 'Registro activado con éxito en ' . $this->tabla;
        $data->registro_id = $this->registro_id;
        $data->transaccion = $transaccion;


        return $data;
    }

    /**
     * PARAMS ORDER P INT
     * Aplica status = a activo a todos los elementos o registros de una tabla
     * @return array
     * @final rev
     */
    public function activa_todo(): array
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: array());
        }

        $this->transaccion = 'UPDATE';
        $consulta = "UPDATE " . $this->tabla . " SET status = 'activo'  ";

        $resultado = $this->ejecuta_sql(consulta: $consulta);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $resultado);
        }


        return array('mensaje' => 'Registros activados con éxito', 'sql' => $this->consulta);
    }

    /**
     *
     * Inserta un registro por registro enviado
     * @return array|stdClass con datos del registro insertado
     * @internal  $this->valida_campo_obligatorio();
     * @internal  $this->valida_estructura_campos();
     * @internal  $this->asigna_data_user_transaccion();
     * @internal  $this->bitacora($this->registro,__FUNCTION__,$consulta);
     * @example
     *      $entrada_modelo->registro = array('tipo_entrada_id'=>1,'almacen_id'=>1,'fecha'=>'2020-01-01',
     *          'proveedor_id'=>1,'tipo_proveedor_id'=>1,'referencia'=>1,'tipo_almacen_id'=>1);
     * $resultado = $entrada_modelo->alta_bd();
     */
    public function alta_bd(): array|stdClass
    {
        if (!isset($_SESSION['usuario_id'])) {
            return $this->error->error(mensaje: 'Error SESSION no iniciada', data: array(), es_final: true);
        }

        if ($_SESSION['usuario_id'] <= 0) {
            return $this->error->error(mensaje: 'Error USUARIO INVALIDO', data: $_SESSION['usuario_id'],
                es_final: true);
        }

        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $this->registro,
                es_final: true);
        }

        $registro_original = $this->registro;
        $this->status_default = 'activo';
        $registro = (new inicializacion())->registro_ins(campos_encriptados: $this->campos_encriptados,
            integra_datos_base: $this->integra_datos_base, registro: $this->registro,
            status_default: $this->status_default, tipo_campos: $this->tipo_campos);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al maquetar registro ', data: $registro);
        }

        $this->registro = $registro;

        $valida = (new val_sql())->valida_base_alta(campos_obligatorios: $this->campos_obligatorios, modelo: $this,
            no_duplicados: $this->no_duplicados, registro: $registro, tabla: $this->tabla,
            tipo_campos: $this->tipo_campos, parents: $this->parents);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar alta ', data: $valida);
        }

        if ($this->id_code && !isset($this->registro['id'])) {
            $this->registro['id'] = $this->registro['codigo'];
        }

        $transacciones = (new inserts())->transacciones(modelo: $this);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar transacciones', data: $transacciones);
        }

        $registro = $this->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro', data: $registro);
        }
        $registro_puro = $this->registro(registro_id: $this->registro_id, columnas_en_bruto: true, retorno_obj: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro', data: $registro);
        }

        $data = $this->data_result_transaccion(mensaje: 'Registro insertado con éxito', registro: $registro,
            registro_ejecutado: $this->registro, registro_id: $this->registro_id, registro_original: $registro_original,
            registro_puro: $registro_puro, sql: $transacciones->sql);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al maquetar respuesta registro', data: $registro);
        }

        return $data;
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Accion destinada a ser heredable en paquete documento para uso de importacion
     * @param array $registro Registro en proceso de alta
     * @param array $file FILE var
     *
     * @return stdClass|array
     * @version 18.21.0
     */
    public function alta_documento(array $registro, array $file): stdClass|array
    {
        return new stdClass();

    }

    /**
     * Obtiene un registro existente y da salida homolagada
     * @param array $filtro Filtro de registro
     * @return array|stdClass
     */
    final protected function alta_existente(array $filtro): array|stdClass
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $filtro);
        }
        if (count($filtro) === 0) {
            return $this->error->error(mensaje: 'Error filtro esta vacio', data: $filtro);
        }

        $result = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al verificar si existe', data: $result);
        }

        if ($result->n_registros > 1) {
            return $this->error->error(mensaje: 'Error de integridad existe mas de un registro', data: $result);
        }
        if ($result->n_registros === 0) {
            return $this->error->error(mensaje: 'Error de integridad no existe registro', data: $result);
        }

        $registro = $result->registros[0];
        $registro_original = $registro;

        $registro_puro = $this->registro(registro_id: $registro[$this->key_id], columnas_en_bruto: true,
            retorno_obj: true);

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro', data: $registro);
        }

        $r_alta_bd = $this->data_result_transaccion(mensaje: "Registro existente", registro: $registro,
            registro_ejecutado: $this->registro, registro_id: $registro[$this->key_id],
            registro_original: $registro_original, registro_puro: $registro_puro, sql: 'Sin ejecucion');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al maquetar salida', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }


    /**
     * Inserta un registro predeterminado
     * @param string|int $codigo Codigo predeterminado default
     * @param string $descripcion Descripcion predeterminado
     * @return array|stdClass
     * @version 6.21.0
     */
    private function alta_predeterminado(
        string|int $codigo = 'PRED', string $descripcion = 'PREDETERMINADO'): array|stdClass
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $this->registro);
        }

        $pred_ins['predeterminado'] = 'activo';
        $pred_ins['codigo'] = $codigo;
        $pred_ins['descripcion'] = $descripcion;
        $r_alta = $this->alta_registro(registro: $pred_ins);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar prederminado en modelo ' . $this->tabla, data: $r_alta);
        }
        return $r_alta;
    }

    /**
     * REG
     * Registra un nuevo registro en la base de datos.
     *
     * Este método realiza la inserción de un nuevo registro en la base de datos,
     * validando previamente la sesión del usuario, el estado del modelo y la aplicación
     * de transacciones base. Si alguna validación falla, se devuelve un error detallado.
     *
     * @param array $registro Datos del registro a insertar en la base de datos.
     *
     * @return array|stdClass Retorna el resultado de la inserción si es exitosa o un array con información de error.
     *
     * @throws errores Si ocurre un error en la inserción del registro.
     *
     * @example
     * // Ejemplo de entrada:
     * $registro = [
     *     'nombre' => 'Ejemplo',
     *     'descripcion' => 'Descripción del ejemplo',
     *     'usuario_id' => 1
     * ];
     *
     * $modelo = new Modelo();
     * $resultado = $modelo->alta_registro($registro);
     *
     * // Ejemplo de salida en caso de éxito:
     * stdClass Object
     * (
     *     [id] => 10
     *     [nombre] => Ejemplo
     *     [descripcion] => Descripción del ejemplo
     *     [usuario_id] => 1
     * )
     *
     * // Ejemplo de salida en caso de error:
     * Array
     * (
     *     [error] => true
     *     [mensaje] => 'Error SESSION no iniciada'
     *     [data] => Array()
     * )
     */
    public function alta_registro(array $registro): array|stdClass
    {


        if (!isset($_SESSION['usuario_id'])) {
            return $this->error->error(mensaje: 'Error SESSION no iniciada', data: array(), es_final: true);
        }

        if ($_SESSION['usuario_id'] <= 0) {
            return $this->error->error(mensaje: 'Error USUARIO INVALIDO en modelo ' . $this->tabla,
                data: $_SESSION['usuario_id'], es_final: true);
        }
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout',
                data: $this->registro, es_final: true);
        }

        $this->registro = $registro;

        $r_alta = $this->alta_bd();
        if (errores::$error) {
            $database = (new database())->db_name;
            return $this->error->error(mensaje: 'Error al dar de alta registro en database ' . $database . '  en modelo '
                . $this->tabla, data: $r_alta);
        }

        return $r_alta;
    }

    /**
     * Realiza una operación de alteración de tabla utilizando una declaración SQL generada.
     * Devuelve un error si hay fallas al generar la declaración SQL o durante su ejecución.
     *
     * @param string $campo Nombre del campo de la tabla a ser alterado.
     * @param string $statement Operación para realizar sobre el campo. Puede tomar valores 'ADD', 'DROP', 'RENAME', 'MODIFY'.
     * @param string $table Nombre de la tabla en la que se realizará la operación.
     * @param string $longitud Opcional. Longitud del campo. Predeterminado es ''.
     * @param string $new_name Opcional. Nuevo nombre para el campo en caso de una operación 'RENAME'. Predeterminado es ''.
     * @param string $tipo_dato Opcional. Tipo de dato del campo en caso de una operación 'ADD' o 'MODIFY'. Predeterminado es ''.
     * @return array|stdClass Si el proceso es exitoso, retorna un objeto con los detalles de la operación.
     *                        Si ocurre un error, retorna un array con el mensaje y los datos del error.
     */
    final public function alter_table(
        string $campo, string $statement, string $table, string $longitud = '', string $new_name = '',
        string $tipo_dato = '', bool $valida_pep_8 = true): array|stdClass
    {
        $sql = (new sql())->alter_table(campo: $campo, statement: $statement, table: $table,
            longitud: $longitud, new_name: $new_name, tipo_dato: $tipo_dato, valida_pep_8: $valida_pep_8);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar sql', data: $sql);
        }
        $exe = $this->ejecuta_sql(consulta: $sql);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $exe);
        }
        return $exe;

    }

    private function campos_obligatorios(array $campos_obligatorios)
    {
        $this->campos_obligatorios = array_merge($this->campos_obligatorios, $campos_obligatorios);

        if (isset($campos_obligatorios[0]) && trim($campos_obligatorios[0]) === '*') {

            $limpia = $this->todos_campos_obligatorios();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al limpiar campos obligatorios en ' . $this->tabla, data: $limpia);
            }
        }
        return $this->campos_obligatorios;
    }


    /**
     * REG
     * Calcula la cantidad de registros en la base de datos según los filtros especificados.
     *
     * Esta función construye y ejecuta una consulta SQL `COUNT(*)` basada en diferentes criterios de filtrado,
     * incluyendo filtros estándar, rangos, fechas, exclusiones y condiciones especiales.
     *
     * ### Funcionamiento:
     * 1. **Validación del tipo de filtro:** Se valida que el `tipo_filtro` proporcionado sea válido.
     * 2. **Generación de joins:** Se generan las relaciones necesarias entre tablas según la estructura de la base de datos.
     * 3. **Construcción de filtros:** Se configuran los filtros con base en los parámetros proporcionados.
     * 4. **Ejecución de la consulta:** Se construye la sentencia SQL y se ejecuta para obtener la cantidad de registros.
     * 5. **Retorno del resultado:** Se devuelve el número total de registros encontrados.
     *
     * @param array $diferente_de Lista de campos y valores para excluir de la consulta.
     *                            Ejemplo:
     *                            ```php
     *                            ['estado' => 'inactivo']
     *                            ```
     *
     * @param array $extra_join Lista de tablas adicionales a incluir en la consulta.
     *                          Ejemplo:
     *                          ```php
     *                          [
     *                              ['tabla' => 'usuarios', 'tipo' => 'INNER JOIN', 'on' => 'usuarios.id = pedidos.usuario_id']
     *                          ]
     *                          ```
     *
     * @param array $filtro Lista de condiciones para filtrar registros específicos.
     *                      Ejemplo:
     *                      ```php
     *                      ['categoria' => 'electronica']
     *                      ```
     *
     * @param string $tipo_filtro Define el tipo de comparación a aplicar en los filtros.
     *                            Puede ser 'numeros', 'texto', 'fechas', etc.
     *                            Ejemplo:
     *                            ```php
     *                            'texto'
     *                            ```
     *
     * @param array $filtro_especial Condiciones avanzadas de filtrado.
     *                               Ejemplo:
     *                               ```php
     *                               ['precio > 1000']
     *                               ```
     *
     * @param array $filtro_rango Rango de valores para ciertos campos.
     *                            Ejemplo:
     *                            ```php
     *                            ['fecha' => ['2024-01-01', '2024-12-31']]
     *                            ```
     *
     * @param array $filtro_fecha Filtros específicos para fechas.
     *                            Ejemplo:
     *                            ```php
     *                            ['fecha_creacion' => '2024-03-01']
     *                            ```
     *
     * @param array $in Lista de valores permitidos en una columna específica.
     *                  Ejemplo:
     *                  ```php
     *                  ['estado' => ['activo', 'pendiente']]
     *                  ```
     *
     * @param array $not_in Lista de valores prohibidos en una columna específica.
     *                      Ejemplo:
     *                      ```php
     *                      ['estado' => ['inactivo', 'cancelado']]
     *                      ```
     *
     * @return array|int Retorna la cantidad de registros que cumplen con los filtros aplicados.
     *                   En caso de error, devuelve un array con detalles del error.
     *
     * @example **Ejemplo de uso:**
     * ```php
     * $modelo = new modelo();
     * $total_registros = $modelo->cuenta(
     *     filtro: ['categoria' => 'ropa'],
     *     filtro_rango: ['precio' => [100, 500]],
     *     filtro_fecha: ['fecha_venta' => '2025-01-01'],
     *     in: ['estado' => ['activo', 'pendiente']],
     *     not_in: ['estado' => ['cancelado']],
     *     tipo_filtro: 'numeros'
     * );
     * print_r($total_registros);
     * ```
     *
     * ### **Posibles resultados:**
     *
     * **Caso 1: Se encontraron registros**
     * ```php
     * 35 // Retorna la cantidad de registros que cumplen con los filtros
     * ```
     *
     * **Caso 2: No se encontraron registros**
     * ```php
     * 0
     * ```
     *
     * **Caso 3: Error en los filtros**
     * ```php
     * [
     *     'error' => 1,
     *     'mensaje' => 'Error al generar filtros',
     *     'data' => [...]
     * ]
     * ```
     */
    final public function cuenta(array  $diferente_de = array(), array $extra_join = array(), array $filtro = array(),
                                 string $tipo_filtro = 'numeros', array $filtro_especial = array(),
                                 array  $filtro_rango = array(), array $filtro_fecha = array(),
                                 array  $in = array(), array $not_in = array()): array|int
    {

        // Validar el tipo de filtro antes de ejecutar la consulta
        $verifica_tf = (new \gamboamartin\where\where())->verifica_tipo_filtro(tipo_filtro: $tipo_filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar tipo_filtro', data: $verifica_tf);
        }

        // Configuración inicial de joins y estructuras adicionales
        $extension_estructura = array();
        $renombradas = array();

        $tablas = (new joins())->tablas(
            columnas: $this->columnas,
            extension_estructura: $extension_estructura,
            extra_join: $extra_join,
            modelo_tabla: $this->tabla,
            renombradas: $renombradas,
            tabla: $this->tabla
        );
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar joins en ' . $this->tabla, data: $tablas);
        }

        // Generar filtros con los parámetros recibidos
        $filtros = (new where())->data_filtros_full(
            columnas_extra: $this->columnas_extra,
            diferente_de: $diferente_de,
            filtro: $filtro,
            filtro_especial: $filtro_especial,
            filtro_extra: array(),
            filtro_fecha: $filtro_fecha,
            filtro_rango: $filtro_rango,
            in: $in,
            keys_data_filter: $this->keys_data_filter,
            not_in: $not_in,
            sql_extra: '',
            tipo_filtro: $tipo_filtro
        );

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar filtros', data: $filtros);
        }

        // Construcción de la consulta SQL para contar registros
        $sql = /** @lang MYSQL */
            " SELECT COUNT(*) AS total_registros FROM $tablas $filtros->where $filtros->sentencia 
            $filtros->filtro_especial $filtros->filtro_rango $filtros->in";

        // Ejecución de la consulta
        $result = $this->ejecuta_consulta(consulta: $sql, campos_encriptados: $this->campos_encriptados);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar SQL', data: $result);
        }

        // Retornar la cantidad de registros encontrados
        return (int)$result->registros[0]['total_registros'];
    }


    /**
     * REG
     * Obtiene el número total de registros que cumplen con los filtros y condiciones especificadas.
     *
     * Este método genera una consulta SQL de conteo (`COUNT(*)`) basada en los filtros y condiciones
     * especificadas en los parámetros. Posteriormente, ejecuta la consulta y retorna el número total
     * de registros encontrados.
     *
     * ### Flujo del método:
     * 1. **Validación del tipo de filtro:** Se verifica que el `$tipo_filtro` sea válido mediante `where::verifica_tipo_filtro()`.
     * 2. **Aplicación de seguridad:** Si la seguridad está activada, se añaden filtros de seguridad a la consulta.
     * 3. **Generación de la consulta SQL:** Se construye la consulta de conteo utilizando `genera_sql_filtro()`.
     * 4. **Ejecución de la consulta:** Se ejecuta la consulta mediante `ejecuta_consulta()`.
     * 5. **Retorno del resultado:** Se extrae el total de registros y se devuelve como un entero.
     *
     * ### Ejemplo de uso:
     * ```php
     * $modelo = new ModeloEjemplo();
     * $total = $modelo->cuenta_bis(
     *     aplica_seguridad: true,
     *     filtro: ['categoria_id' => 1],
     *     tipo_filtro: 'AND'
     * );
     * echo "Total de registros: " . $total;
     * ```
     *
     * ### Ejemplo de salida esperada:
     * ```
     * Total de registros: 45
     * ```
     *
     * @param bool $aplica_seguridad Indica si se deben aplicar filtros de seguridad.
     * @param array $columnas Columnas a incluir en la consulta (no afecta el conteo).
     * @param array $columnas_by_table Columnas organizadas por tabla.
     * @param bool $columnas_en_bruto Si `true`, las columnas se mantienen sin alias ni modificaciones.
     * @param bool $con_sq Si `true`, permite generar subconsultas (`WITH`).
     * @param array $diferente_de Filtros de exclusión (`!=`).
     * @param array $extra_join Joins adicionales en la consulta.
     * @param array $filtro Condiciones en formato `columna => valor`.
     * @param array $filtro_especial Condiciones avanzadas de filtrado.
     * @param array $filtro_extra Filtros adicionales personalizados.
     * @param array $filtro_fecha Filtros basados en fechas.
     * @param array $filtro_rango Filtros basados en rangos de valores.
     * @param array $group_by Cláusula `GROUP BY`.
     * @param array $hijo Configuración de relaciones con otras tablas.
     * @param array $in Filtros `IN`.
     * @param array $not_in Filtros `NOT IN`.
     * @param string $sql_extra SQL adicional a incluir en la consulta.
     * @param string $tipo_filtro Tipo de filtro (`AND` o `OR`).
     *
     * @return array|int Retorna el número total de registros encontrados o un array de error si falla.
     */

    final public function cuenta_bis(
        bool   $aplica_seguridad = true,
        array  $columnas = array(),
        array  $columnas_by_table = array(),
        bool   $columnas_en_bruto = false,
        bool   $con_sq = true,
        array  $diferente_de = array(),
        array  $extra_join = array(),
        array  $filtro = array(),
        array  $filtro_especial = array(),
        array  $filtro_extra = array(),
        array  $filtro_fecha = array(),
        array  $filtro_rango = array(),
        array  $group_by = array(),
        array  $hijo = array(),
        array  $in = array(),
        array  $not_in = array(),
        string $sql_extra = '',
        string $tipo_filtro = 'numeros'
    ): array|int
    {
        $verifica_tf = (new \gamboamartin\where\where())->verifica_tipo_filtro(tipo_filtro: $tipo_filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar tipo_filtro', data: $verifica_tf);
        }
        if ($this->aplica_seguridad && $aplica_seguridad) {
            $filtro = array_merge($filtro, $this->filtro_seguridad);
        }
        $sql = $this->genera_sql_filtro(
            columnas: $columnas,
            columnas_by_table: $columnas_by_table,
            columnas_en_bruto: $columnas_en_bruto,
            con_sq: $con_sq,
            diferente_de: $diferente_de,
            extra_join: $extra_join,
            filtro: $filtro,
            filtro_especial: $filtro_especial,
            filtro_extra: $filtro_extra,
            filtro_rango: $filtro_rango,
            group_by: $group_by,
            in: $in,
            limit: 0,
            not_in: $not_in,
            offset: 0,
            order: array(),
            sql_extra: $sql_extra,
            tipo_filtro: $tipo_filtro,
            count: true,
            filtro_fecha: $filtro_fecha
        );
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al maquetar sql', data: $sql);
        }
        $result = $this->ejecuta_consulta(consulta: $sql, campos_encriptados: $this->campos_encriptados, hijo: $hijo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $result);
        }
        return (int)$result->registros[0]['total_registros'];
    }


    /**
     * REG
     * Construye un objeto con una sentencia SQL actualizada y su cláusula WHERE correspondiente.
     *
     * @param string $campo Nombre del campo de la base de datos que se utilizará en la condición.
     * @param string $sentencia Sentencia SQL existente a la cual se añadirá la nueva condición OR.
     * @param string $value Valor que será comparado con el campo en la condición OR.
     * @param string $where Cláusula WHERE inicial para la sentencia. Si está vacía, se asigna automáticamente "WHERE".
     *
     * @return array|stdClass Devuelve un objeto con las claves `where` y `sentencia` que contienen la cláusula WHERE
     *                        y la sentencia SQL actualizada respectivamente. En caso de error, devuelve un array con los detalles del problema.
     *
     * @throws errores Si ocurre algún problema, como que el campo esté vacío.
     *
     * @example Generar una nueva sentencia con WHERE y condición OR:
     * ```php
     * $campo = 'nombre';
     * $sentencia = '';
     * $value = 'Juan';
     * $where = '';
     *
     * $resultado = $this->data_sentencia(campo: $campo, sentencia: $sentencia, value: $value, where: $where);
     * // Resultado:
     * // stdClass {
     * //     "where": " WHERE ",
     * //     "sentencia": " nombre = 'Juan' "
     * // }
     * ```
     *
     * @example Actualizar una sentencia existente con una nueva condición OR:
     * ```php
     * $campo = 'apellido';
     * $sentencia = "nombre = 'Juan'";
     * $value = 'Pérez';
     * $where = ' WHERE ';
     *
     * $resultado = $this->data_sentencia(campo: $campo, sentencia: $sentencia, value: $value, where: $where);
     * // Resultado:
     * // stdClass {
     * //     "where": " WHERE ",
     * //     "sentencia": "nombre = 'Juan' OR apellido = 'Pérez'"
     * // }
     * ```
     *
     * @example Manejo de error si el campo está vacío:
     * ```php
     * $campo = '';
     * $sentencia = "nombre = 'Juan'";
     * $value = 'Pérez';
     * $where = ' WHERE ';
     *
     * $resultado = $this->data_sentencia(campo: $campo, sentencia: $sentencia, value: $value, where: $where);
     * // Resultado: Array con detalles del error, indicando que el campo está vacío.
     * ```
     */
    private function data_sentencia(string $campo, string $sentencia, string $value, string $where): array|stdClass
    {
        $campo = trim($campo);
        if ($campo === '') {
            return $this->error->error(mensaje: 'Error el campo está vacío', data: $campo, es_final: true);
        }

        if ($where === '') {
            $where = ' WHERE ';
        }

        $sentencia_env = $this->sentencia_or(campo: $campo, sentencia: $sentencia, value: $value);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $sentencia_env);
        }

        $data = new stdClass();
        $data->where = $where;
        $data->sentencia = $sentencia_env;
        return $data;
    }


    /**
     * Maqueta la salida de los resultados
     * @param string $mensaje Mensaje a integrar
     * @param array $registro Registro resultante
     * @param array $registro_ejecutado Registro en ejecucion
     * @param int $registro_id Identificador resultante o en ejecucion
     * @param array|stdClass $registro_original
     * @param stdClass $registro_puro Registro en bruto insertado completo
     * @param string $sql Sql ejecutado
     * @return stdClass
     */
    final protected function data_result_transaccion(string   $mensaje, array $registro, array $registro_ejecutado,
                                                     int      $registro_id, array|stdClass $registro_original,
                                                     stdClass $registro_puro, string $sql): stdClass
    {
        $data = new stdClass();
        $data->mensaje = $mensaje;
        $data->registro_id = $registro_id;
        $data->sql = $sql;
        $data->registro = $registro;
        $data->registro_obj = (object)$registro;
        $data->registro_ins = $registro_ejecutado;
        $data->registro_puro = $registro_puro;
        $data->campos = $this->campos_tabla;
        $data->registro_original = $registro_original;
        $key_id = $this->tabla . '_id';
        $data->$key_id = $registro_id;
        return $data;
    }

    /**
     * REG
     * Desactiva un registro en la base de datos, marcándolo como inactivo.
     *
     * Este método realiza la desactivación lógica de un registro en la base de datos, asegurando que:
     * - El ID del registro sea mayor a 0.
     * - Se esté ejecutando dentro de un contexto que permita transacciones.
     * - Se valide la transacción activa antes de ejecutar la actualización.
     * - Se registre la transacción en la bitácora.
     * - Se desactiven las dependencias relacionadas si existen.
     *
     * @return array|stdClass Retorna un array con mensaje de éxito o un error detallado en caso de falla.
     *
     * @example Entrada válida:
     * ```php
     * $modelo->registro_id = 5;
     * $modelo->tabla = 'usuarios';
     * $modelo->aplica_transacciones_base = true;
     * $resultado = $modelo->desactiva_bd();
     * print_r($resultado);
     * ```
     *
     * @example Salida esperada en caso de éxito:
     * ```php
     * Array (
     *     [mensaje] => 'Registro desactivado con éxito'
     *     [registro_id] => 5
     * )
     * ```
     *
     * @example Salida esperada en caso de error (registro_id <= 0):
     * ```php
     * Array (
     *     [error] => true,
     *     [mensaje] => 'Error $this->registro_id debe ser mayor a 0',
     *     [data] => 0,
     *     [es_final] => true
     * )
     * ```
     *
     * @example Salida esperada en caso de error (transacciones no habilitadas):
     * ```php
     * Array (
     *     [error] => true,
     *     [mensaje] => 'Error solo se puede transaccionar desde layout',
     *     [data] => 5,
     *     [es_final] => true
     * )
     * ```
     */
    public function desactiva_bd(): array|stdClass
    {

        if ($this->registro_id <= 0) {
            return $this->error->error(mensaje: 'Error $this->registro_id debe ser mayor a 0',
                data: $this->registro_id, es_final: true);
        }

        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout',
                data: $this->registro_id, es_final: true);
        }

        $registro = $this->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro', data: $registro);
        }


        $valida = $this->validacion->valida_transaccion_activa(
            aplica_transaccion_inactivo: $this->aplica_transaccion_inactivo, registro: $registro,
            registro_id: $this->registro_id, tabla: $this->tabla);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar transaccion activa', data: $valida);
        }
        $tabla = $this->tabla;
        $this->consulta = /** @lang MYSQL */
            "UPDATE $tabla SET status = 'inactivo' WHERE id = $this->registro_id";
        $this->transaccion = 'DESACTIVA';
        $transaccion = (new bitacoras())->ejecuta_transaccion(tabla: $this->tabla, funcion: __FUNCTION__, modelo: $this,
            registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al EJECUTAR TRANSACCION', data: $transaccion);
        }

        $desactiva = $this->aplica_desactivacion_dependencias();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al desactivar dependiente', data: $desactiva);
        }


        return array('mensaje' => 'Registro desactivado con éxito', 'registro_id' => $this->registro_id);

    }

    /**
     * PHPUNIT
     * @return array
     */
    public function desactiva_todo(): array
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: array());
        }

        $consulta = /** @lang MYSQL */
            "UPDATE  $this->tabla SET status='inactivo'";

        $this->link->query($consulta);
        if ($this->link->errorInfo()[1]) {
            return $this->error->error($this->link->errorInfo()[0], '');
        } else {

            return array('mensaje' => 'Registros desactivados con éxito');
        }
    }


    /**
     * REG
     * Elimina un registro de la base de datos asegurando validaciones y transacciones previas.
     *
     * Este método permite eliminar un registro en la base de datos, verificando previamente que la transacción
     * esté activa, validando dependencias, eliminando registros relacionados y registrando la eliminación
     * en la bitácora.
     *
     * ### Flujo del método:
     * 1. **Validación de transacción:** Se verifica que la transacción esté habilitada (`$this->aplica_transacciones_base`).
     * 2. **Validación del ID:** Se verifica que `$id` sea un número positivo mayor a 0.
     * 3. **Validación de activación:** Se comprueba que el modelo tiene una transacción activa usando `valida_activacion()`.
     * 4. **Obtención de datos previos:** Se recupera el registro a eliminar (`obten_data()`) y su estado puro (`registro()`).
     * 5. **Generación de consulta SQL:** Se construye la consulta `DELETE` para eliminar el registro de la base de datos.
     * 6. **Eliminación de dependencias:** Se ejecuta `aplica_eliminacion_dependencias()` para manejar registros dependientes.
     * 7. **Validación de dependencias hijos:** Se usa `valida_eliminacion_children()` para asegurar que no existan registros hijos.
     * 8. **Ejecución de la consulta:** Se ejecuta la consulta SQL (`ejecuta_sql()`).
     * 9. **Registro en bitácora:** Se registra la eliminación en la bitácora con `bitacora()`.
     * 10. **Retorno de datos:** Se retorna un objeto `stdClass` con la información del registro eliminado.
     *
     * ---
     *
     * @param int $id Identificador del registro que se desea eliminar.
     *                - Debe ser un número entero mayor a 0.
     *
     * @return array|stdClass Devuelve un objeto con los datos del registro eliminado, la consulta SQL ejecutada y un mensaje de éxito.
     *                        En caso de error, retorna un array con detalles del error.
     *
     * @example **Ejemplo 1: Eliminación exitosa**
     * ```php
     * $id = 15;
     * $resultado = $this->elimina_bd($id);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * stdClass Object
     * (
     *     [registro_id] => 15
     *     [sql] => "DELETE FROM productos WHERE id = 15"
     *     [registro] => Array ( ... datos del registro eliminado ... )
     *     [registro_puro] => Array ( ... datos originales del registro ... )
     *     [mensaje] => "Se elimino el registro con el id 15"
     * )
     * ```
     *
     * @example **Ejemplo 2: Error por ID inválido**
     * ```php
     * $id = -5;
     * $resultado = $this->elimina_bd($id);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'El id no puede ser menor a 0 en productos'
     *     [data] => -5
     *     [es_final] => true
     * )
     * ```
     *
     * @example **Ejemplo 3: Error al validar transacción**
     * ```php
     * $this->aplica_transacciones_base = false;
     * $id = 10;
     * $resultado = $this->elimina_bd($id);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error solo se puede transaccionar desde layout'
     *     [data] => 10
     *     [es_final] => true
     * )
     * ```
     *
     * @example **Ejemplo 4: Error por dependencias encontradas**
     * ```php
     * $id = 20;
     * $resultado = $this->elimina_bd($id);
     * print_r($resultado);
     * ```
     * **Salida esperada si el registro tiene dependencias en otras tablas:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error al validar children'
     *     [data] => Array
     *         (
     *             [error] => 1
     *             [mensaje] => 'Error el registro tiene dependencias asignadas en facturas'
     *             [data] => true
     *             [es_final] => true
     *         )
     * )
     * ```
     */
    public function elimina_bd(int $id): array|stdClass
    {

        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $id,
                es_final: true);
        }

        if ($id <= 0) {
            return $this->error->error(mensaje: 'El id no puede ser menor a 0 en ' . $this->tabla, data: $id,
                es_final: true);
        }
        $this->registro_id = $id;

        $valida = (new activaciones())->valida_activacion(modelo: $this);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar transaccion activa en ' . $this->tabla,
                data: $valida);
        }

        $registro_bitacora = $this->obten_data();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro en ' . $this->tabla,
                data: $registro_bitacora);
        }
        $registro_puro = $this->registro(registro_id: $id, columnas_en_bruto: true, retorno_obj: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro en ' . $this->tabla, data: $registro_puro);
        }

        $tabla = $this->tabla;
        $this->consulta = /** @lang MYSQL */
            'DELETE FROM ' . $tabla . ' WHERE id = ' . $id;
        $consulta = $this->consulta;
        $this->transaccion = 'DELETE';

        $elimina = (new dependencias())->aplica_eliminacion_dependencias(
            desactiva_dependientes: $this->desactiva_dependientes, link: $this->link,
            models_dependientes: $this->models_dependientes, registro_id: $this->registro_id, tabla: $this->tabla);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al eliminar dependiente ', data: $elimina);
        }

        $valida = $this->valida_eliminacion_children(id: $id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar children', data: $valida);
        }

        $resultado = $this->ejecuta_sql(consulta: $this->consulta);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql en ' . $this->tabla, data: $resultado);
        }
        $bitacora = (new bitacoras())->bitacora(
            consulta: $consulta, funcion: __FUNCTION__, modelo: $this, registro: $registro_bitacora);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar bitacora de ' . $this->tabla, data: $bitacora);
        }

        $data = new stdClass();
        $data->registro_id = $id;
        $data->sql = $this->consulta;
        $data->registro = $registro_bitacora;
        $data->registro_puro = $registro_puro;
        $data->mensaje = 'Se elimino el registro con el id ' . $id;


        return $data;

    }

    /**
     * REG
     * Elimina registros de la base de datos utilizando un filtro AND.
     *
     * Este método busca registros en la base de datos utilizando un conjunto de condiciones
     * proporcionadas en el parámetro `$filtro`. Si se encuentran registros coincidentes,
     * se eliminan uno por uno mediante `elimina_bd()`.
     *
     * @param array $filtro Filtro que se aplicará para seleccionar los registros a eliminar.
     *                      Debe ser un array asociativo con las condiciones de búsqueda.
     *
     * @return array Un array con los resultados de las eliminaciones de cada registro.
     *               En caso de error, devuelve un array con los detalles del error.
     *
     * @throws errores En caso de error, la función retorna un array con un mensaje de error
     *                 y los datos asociados.
     *
     * @example Uso básico:
     * ```php
     * $filtro = [
     *     'usuario_id' => 5,
     *     'status' => 'inactivo'
     * ];
     * $resultado = $modelo->elimina_con_filtro_and($filtro);
     * print_r($resultado);
     * ```
     * **Salida esperada (si hay registros eliminados):**
     * ```php
     * [
     *     ['mensaje' => 'Se eliminó el registro con ID 10'],
     *     ['mensaje' => 'Se eliminó el registro con ID 15']
     * ]
     * ```
     *
     * **Salida esperada (si no hay coincidencias en la base de datos):**
     * ```php
     * [
     *     'error' => 'Error al obtener registros tabla_usuarios',
     *     'data' => []
     * ]
     * ```
     *
     * @example Uso con múltiples filtros:
     * ```php
     * $filtro = [
     *     'categoria_id' => 3,
     *     'activo' => 'no'
     * ];
     * $resultado = $modelo->elimina_con_filtro_and($filtro);
     * if (isset($resultado['error'])) {
     *     echo "Error: " . $resultado['error'];
     * } else {
     *     echo "Registros eliminados exitosamente.";
     * }
     * ```
     */
    public function elimina_con_filtro_and(array $filtro): array
    {

        if (count($filtro) === 0) {
            return $this->error->error('Error no existe filtro', $filtro, es_final: true);
        }
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $filtro);
        }

        $result = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros ' . $this->tabla, data: $result);
        }
        $dels = array();
        foreach ($result->registros as $row) {

            $del = $this->elimina_bd(id: $row[$this->tabla . '_id']);
            if (errores::$error) {
                return $this->error->error('Error al eliminar registros ' . $this->tabla, $del);
            }
            $dels[] = $del;

        }

        return $dels;

    }

    public function elimina_full_childrens(): array
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: array(),
                es_final: true);
        }
        $dels = array();
        foreach ($this->childrens as $modelo_children => $namespace) {

            $modelo_children_obj = $this->genera_modelo(modelo: $modelo_children, namespace_model: $namespace);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al generar modelo', data: $modelo_children_obj);
            }
            $elimina_todo_children = $modelo_children_obj->elimina_todo();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al eliminar children', data: $elimina_todo_children);
            }
            $dels[] = $elimina_todo_children;
        }
        return $dels;
    }

    /**
     * PHPUNIT
     * @return string[]
     */
    public function elimina_todo(): array
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: array(),
                es_final: true);
        }

        $elimina_todo_children = $this->elimina_full_childrens();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al eliminar childrens', data: $elimina_todo_children);
        }


        $tabla = $this->tabla;
        $this->transaccion = 'DELETE';
        $this->consulta = /** @lang MYSQL */
            'DELETE FROM ' . $tabla;

        $resultado = $this->ejecuta_sql($this->consulta);

        if (errores::$error) {
            return $this->error->error('Error al ejecutar sql', $resultado);
        }

        $exe = (new _instalacion(link: $this->link))->init_auto_increment(table: $this->tabla);
        if (errores::$error) {
            return $this->error->error('Error al ejecutar sql init', $exe);
        }

        return array('mensaje' => 'Registros eliminados con éxito');
    }

    /**
     * PHPUNIT
     * @return array
     */
    protected function estado_inicial(): array
    {
        $filtro[$this->tabla . '.inicial'] = 'activo';
        $r_estado = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error('Error al filtrar estado', $r_estado);
        }
        if ((int)$r_estado['n_registros'] === 0) {
            return $this->error->error('Error al no existe estado default', $r_estado);
        }
        if ((int)$r_estado['n_registros'] > 1) {
            return $this->error->error('Error existe mas de un estado', $r_estado);
        }
        return $r_estado['registros'][0];
    }

    /**
     * PHPUNIT
     * @return int|array
     */
    protected function estado_inicial_id(): int|array
    {
        $estado_inicial = $this->estado_inicial();
        if (errores::$error) {
            return $this->error->error('Error al obtener estado', $estado_inicial);
        }
        return (int)$estado_inicial[$this->tabla . '_id'];
    }


    /**
     * REG
     * Verifica si existen registros en la base de datos que cumplan con un conjunto de filtros.
     *
     * Esta función utiliza el método `cuenta()` para contar la cantidad de registros que cumplen con los criterios
     * especificados en `$filtro`. Si el número de registros es mayor a 0, se considera que existen registros y devuelve `true`,
     * de lo contrario, devuelve `false`. En caso de error, devuelve un array con detalles del problema.
     *
     * @param array $filtro Un array asociativo que define los criterios de filtrado para la consulta.
     *                      Debe contener claves que correspondan a los nombres de las columnas en la base de datos.
     *
     * @return array|bool Retorna:
     *  - `true` si existen registros que coinciden con el filtro.
     *  - `false` si no hay registros que coincidan con el filtro.
     *  - Un `array` con detalles del error si ocurre un problema al contar los registros.
     *
     * @example
     * ### Ejemplo 1: Verificar si existe un usuario con un ID específico
     * ```php
     * $modelo = new ModeloUsuarios();
     * $filtro = ['id' => 10];
     * $resultado = $modelo->existe($filtro);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * true  // Si el usuario con ID 10 existe
     * false // Si el usuario con ID 10 no existe
     * ```
     *
     * @example
     * ### Ejemplo 2: Verificar si existe un producto con un código específico
     * ```php
     * $modelo = new ModeloProductos();
     * $filtro = ['codigo' => 'PRD123'];
     * $resultado = $modelo->existe($filtro);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * true  // Si el producto con código "PRD123" existe
     * false // Si el producto con código "PRD123" no existe
     * ```
     *
     * @example
     * ### Ejemplo 3: Intentar verificar existencia sin filtro (Error)
     * ```php
     * $modelo = new ModeloPedidos();
     * $filtro = [];
     * $resultado = $modelo->existe($filtro);
     * print_r($resultado);
     * ```
     * **Salida esperada (error, porque el filtro está vacío):**
     * ```php
     * [
     *   'error' => 1,
     *   'mensaje' => 'Error al contar registros',
     *   'data' => [...detalles del error...]
     * ]
     * ```
     */
    final public function existe(array $filtro): array|bool
    {
        // Obtener la cantidad de registros que cumplen con el filtro
        $resultado = $this->cuenta(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al contar registros', data: $resultado);
        }

        // Determinar si existen registros
        $existe = false;
        if ((int)$resultado > 0) {
            $existe = true;
        }

        return $existe;
    }


    private function existe_atributo_critico(string $atributo_critico, string $key_attr): bool
    {
        $existe_atributo_critico = false;
        if ($key_attr === $atributo_critico) {
            $existe_atributo_critico = true;
        }
        return $existe_atributo_critico;
    }

    /**
     * REG
     * Verifica si un registro existe en la base de datos basado en su ID.
     *
     * Esta función genera un filtro con el ID del registro y consulta si dicho registro existe en la tabla
     * correspondiente. Utiliza el método `existe()` para realizar la verificación.
     *
     * ---
     *
     * ### **Parámetros:**
     *
     * @param int $registro_id El ID del registro a verificar.
     *                         - **Ejemplo:** `25`
     *
     * ---
     *
     * @return bool|array Retorna:
     *  - `true` si el registro con el ID especificado existe en la base de datos.
     *  - `false` si no existe.
     *  - Un `array` con detalles del error si la consulta falla.
     *
     * ---
     *
     * ### **Ejemplo de Uso:**
     * ```php
     * $modelo = new UsuarioModel($pdo);
     * $registro_id = 10;
     * $resultado = $modelo->existe_by_id($registro_id);
     * print_r($resultado);
     * ```
     *
     * **Salida esperada si el registro existe:**
     * ```php
     * true
     * ```
     *
     * **Salida esperada si el registro no existe:**
     * ```php
     * false
     * ```
     *
     * ---
     *
     * ### **Ejemplo de Error:**
     * ```php
     * $registro_id = -1; // ID inválido
     * $resultado = $modelo->existe_by_id($registro_id);
     * print_r($resultado);
     * ```
     *
     * **Salida esperada (error de validación):**
     * ```php
     * Array
     * (
     *     [error] => 1,
     *     [mensaje] => "Error al obtener row",
     *     [data] => false
     * )
     * ```
     *
     * ---
     *
     * @throws array Devuelve un array con un mensaje de error si ocurre un problema en la consulta.
     */
    final public function existe_by_id(int $registro_id): bool|array
    {
        $filtro[$this->tabla . '.id'] = $registro_id;
        $existe = $this->existe(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener row', data: $existe);
        }
        return $existe;
    }

    final public function existe_by_codigo(string $codigo): bool|array
    {
        $filtro[$this->tabla . '.codigo'] = $codigo;
        $existe = $this->existe(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener row', data: $existe);
        }
        return $existe;
    }

    /**
     * PHPUNIT
     * Funcion para validar si existe un valor de un key de un array dentro de otro array
     * @param array $compare_1
     * @param array $compare_2
     * @param string $key
     * @return bool|array
     */
    private function existe_en_array(array $compare_1, array $compare_2, string $key): bool|array
    {
        $key = trim($key);
        if ($key === '') {
            return $this->error->error('Error $key no puede venir vacio', $key);
        }
        $existe = false;
        if (isset($compare_1[$key], $compare_2[$key])) {
            if ((string)$compare_1[$key] === (string)$compare_2[$key]) {
                $existe = true;
            }
        }
        return $existe;
    }

    /**
     * Verifica un elemento predetermindao de la entidad
     * @return bool|array
     */
    final public function existe_predeterminado(): bool|array
    {
        $key = $this->tabla . '.predeterminado';
        $filtro[$key] = 'activo';
        $existe = $this->existe(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al verificar si existe', data: $existe);
        }
        return $existe;
    }

    /**
     * PHPUNIT
     * @param array $compare_1
     * @param array $compare_2
     * @param string $key
     * @return bool|array
     */
    protected function existe_registro_array(array $compare_1, array $compare_2, string $key): bool|array
    {
        $key = trim($key);
        if ($key === '') {
            return $this->error->error('Error $key no puede venir vacio', $key);
        }
        $existe = false;
        foreach ($compare_1 as $data) {
            if (!is_array($data)) {
                return $this->error->error("Error data debe ser un array", $data);
            }
            $existe = $this->existe_en_array($data, $compare_2, $key);
            if (errores::$error) {
                return $this->error->error("Error al comparar dato", $existe);
            }
            if ($existe) {
                break;
            }
        }
        return $existe;
    }


    /**
     * REG
     * Genera y ejecuta una consulta SQL aplicando filtros en formato `AND`.
     *
     * Este método construye y ejecuta una consulta SQL con múltiples condiciones de filtrado basadas en `AND`.
     * Permite aplicar restricciones como filtros básicos, filtros especiales, filtros de fechas, rangos de valores,
     * inclusiones (`IN`), exclusiones (`NOT IN`), agrupaciones (`GROUP BY`), ordenamientos (`ORDER BY`),
     * límites (`LIMIT`), y paginación (`OFFSET`).
     *
     * ### Flujo del método:
     * 1. **Validación del tipo de filtro:** Se verifica que `$tipo_filtro` sea válido mediante `where::verifica_tipo_filtro()`.
     * 2. **Aplicación de seguridad:** Si `$aplica_seguridad` es `true`, se agregan filtros de seguridad automáticamente.
     * 3. **Validación del límite:** Si `$limit` es menor a 0, se retorna un error.
     * 4. **Generación de la consulta SQL:** Se construye la consulta utilizando `genera_sql_filtro()`.
     * 5. **Ejecución de la consulta:** Se ejecuta la consulta con `ejecuta_consulta()`.
     * 6. **Retorno del resultado:** Se devuelve un objeto `stdClass` con los registros obtenidos o un error si falla el proceso.
     *
     * ---
     *
     * ### Ejemplo de uso 1: Búsqueda básica con filtros específicos
     * ```php
     * $modelo = new ModeloEjemplo();
     * $resultado = $modelo->filtro_and(
     *     aplica_seguridad: true,
     *     filtro: ['categoria_id' => 2, 'activo' => 1],
     *     tipo_filtro: 'AND',
     *     order: ['nombre' => 'ASC'],
     *     limit: 5
     * );
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * stdClass Object
     * (
     *     [registros] => Array
     *         (
     *             [0] => Array
     *                 (
     *                     [id] => 1
     *                     [nombre] => "Producto A"
     *                     [categoria_id] => 2
     *                     [activo] => 1
     *                 )
     *             [1] => Array
     *                 (
     *                     [id] => 2
     *                     [nombre] => "Producto B"
     *                     [categoria_id] => 2
     *                     [activo] => 1
     *                 )
     *         )
     * )
     * ```
     * ---
     *
     * ### Ejemplo de uso 2: Aplicando filtros de fecha y paginación
     * ```php
     * $resultado = $modelo->filtro_and(
     *     filtro_fecha: ['fecha_creacion' => ['>=', '2024-01-01']],
     *     order: ['fecha_creacion' => 'DESC'],
     *     limit: 10,
     *     offset: 5
     * );
     * ```
     * **Salida esperada:** Devuelve 10 registros a partir del sexto registro ordenados por fecha de creación descendente.
     * ---
     *
     * ### Ejemplo de uso 3: Filtros avanzados con exclusiones y agrupaciones
     * ```php
     * $resultado = $modelo->filtro_and(
     *     filtro: ['estado' => 'activo'],
     *     not_in: ['id' => [3, 7, 10]],
     *     group_by: ['categoria_id'],
     *     order: ['categoria_id' => 'ASC']
     * );
     * ```
     * **Salida esperada:** Devuelve registros agrupados por `categoria_id`, excluyendo los IDs `3`, `7` y `10`.
     * ---
     *
     * ### Parámetros:
     * @param bool $aplica_seguridad Si `true`, aplica filtros de seguridad adicionales automáticamente.
     * @param array $columnas Lista de columnas a incluir en la consulta.
     * @param array $columnas_by_table Columnas organizadas por tabla.
     * @param bool $columnas_en_bruto Si `true`, se mantiene la estructura original de las columnas sin alias.
     * @param array $columnas_totales Lista de columnas a incluir en la salida total.
     * @param bool $con_sq Si `true`, permite la generación de subconsultas (`WITH`).
     * @param array $diferente_de Condiciones de exclusión (`!=`).
     * @param array $extra_join Joins adicionales en la consulta.
     * @param array $filtro Filtros en formato `columna => valor`.
     * @param array $filtro_especial Filtros avanzados personalizados.
     * @param array $filtro_extra Filtros adicionales opcionales.
     * @param array $filtro_fecha Filtros de fecha (ejemplo: `['fecha_creacion' => ['>=', '2024-01-01']]`).
     * @param array $filtro_rango Rango de valores (ejemplo: `['precio' => ['BETWEEN', 100, 500]]`).
     * @param array $group_by Cláusula `GROUP BY` para agrupar resultados.
     * @param array $hijo Configuración de relaciones con otras tablas.
     * @param array $in Filtros `IN` (ejemplo: `['id' => [1, 2, 3]]`).
     * @param int $limit Límite de registros (0 para ilimitado).
     * @param array $not_in Filtros `NOT IN` (ejemplo: `['id' => [3, 7, 10]]`).
     * @param int $offset Número de registros a saltar (para paginación).
     * @param array $order Cláusula `ORDER BY` para ordenar resultados.
     * @param string $sql_extra SQL adicional a incluir en la consulta.
     * @param string $tipo_filtro Tipo de filtro (TEXTOS, NUMEROS).
     *
     * @return array|stdClass Devuelve un `stdClass` con los registros obtenidos o un array con el error en caso de falla.
     *
     * ---
     *
     * ### Ejemplo de salida con error:
     * ```php
     * // Si `limit` es negativo:
     * $resultado = $modelo->filtro_and(limit: -1);
     * ```
     * **Salida esperada:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error limit debe ser mayor o igual a 0'
     *     [data] => -1
     * )
     * ```
     */

    final public function filtro_and(
        bool   $aplica_seguridad = true,
        array  $columnas = array(),
        array  $columnas_by_table = array(),
        bool   $columnas_en_bruto = false,
        array  $columnas_totales = array(),
        bool   $con_sq = true,
        array  $diferente_de = array(),
        array  $extra_join = array(),
        array  $filtro = array(),
        array  $filtro_especial = array(),
        array  $filtro_extra = array(),
        array  $filtro_fecha = array(),
        array  $filtro_rango = array(),
        array  $group_by = array(),
        array  $hijo = array(),
        array  $in = array(),
        int    $limit = 0,
        array  $not_in = array(),
        int    $offset = 0,
        array  $order = array(),
        string $sql_extra = '',
        string $tipo_filtro = 'numeros'
    ): array|stdClass
    {
        $verifica_tf = (new \gamboamartin\where\where())->verifica_tipo_filtro(tipo_filtro: $tipo_filtro);
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al validar tipo_filtro',
                data: $verifica_tf
            );
        }

        if ($this->aplica_seguridad && $aplica_seguridad) {
            $filtro = array_merge($filtro, $this->filtro_seguridad);
        }

        if ($limit < 0) {
            return $this->error->error(
                mensaje: 'Error limit debe ser mayor o igual a 0  con 0 no aplica limit',
                data: $limit
            );
        }

        $sql = $this->genera_sql_filtro(
            columnas: $columnas,
            columnas_by_table: $columnas_by_table,
            columnas_en_bruto: $columnas_en_bruto,
            con_sq: $con_sq,
            diferente_de: $diferente_de,
            extra_join: $extra_join,
            filtro: $filtro,
            filtro_especial: $filtro_especial,
            filtro_extra: $filtro_extra,
            filtro_rango: $filtro_rango,
            group_by: $group_by,
            in: $in,
            limit: $limit,
            not_in: $not_in,
            offset: $offset,
            order: $order,
            sql_extra: $sql_extra,
            tipo_filtro: $tipo_filtro,
            filtro_fecha: $filtro_fecha
        );
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al maquetar sql',
                data: $sql
            );
        }

        $result = $this->ejecuta_consulta(
            consulta: $sql,
            campos_encriptados: $this->campos_encriptados,
            columnas_totales: $columnas_totales,
            hijo: $hijo
        );
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al ejecutar sql',
                data: $result
            );
        }

        return $result;
    }


    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Ejecuta una consulta SQL basada en múltiples parametros. Una consulta base se genera primero y luego
     * se modificada con filtros, órdenes, limites y otros parámetros.
     *
     * @param bool $aplica_seguridad Determina si se aplicará seguridad a la consulta
     * @param array $columnas Define las columnas que se buscarán en la consulta
     * @param array $columnas_by_table Define las columnas que se buscarán en la consulta por tabla
     * @param bool $columnas_en_bruto Determina si se devolverán columnas en bruto
     * @param array $extra_join Define cualquier unión extra que se utilizará en la consulta
     * @param array $filtro Define cualquier filtro que se añadirá a la consulta
     * @param array $group_by Define cualquier agrupación que se utilizará en la consulta
     * @param array $hijo Define cualquier relación de hijo que se utilizará en la consulta
     * @param int $limit Define un límite en la cantidad de filas que se devolverán
     * @param int $offset Define un offset para las filas que se devolverán
     * @param array $order Define cualquier ordenamiento que se aplicará a las filas devueltas
     * @return array|stdClass Regresa un arreglo o un objeto stdClass basado en el resultado de la consulta
     * @throws errores si hay algún error al ejecutar la consulta
     * @version 19.6.0
     */
    final public function filtro_or(bool  $aplica_seguridad = false, array $columnas = array(),
                                    array $columnas_by_table = array(), bool $columnas_en_bruto = false,
                                    array $extra_join = array(), array $filtro = array(), array $group_by = array(),
                                    array $hijo = array(), int $limit = 0, int $offset = 0,
                                    array $order = array()): array|stdClass
    {

        $consulta = $this->genera_consulta_base(columnas: $columnas, columnas_by_table: $columnas_by_table,
            columnas_en_bruto: $columnas_en_bruto, extension_estructura: $this->extension_estructura,
            extra_join: $extra_join, renombradas: $this->renombres);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar sql', data: $consulta);
        }
        $where = '';
        $sentencia = '';
        foreach ($filtro as $campo => $value) {
            $data_sentencia = $this->data_sentencia(campo: $campo, sentencia: $sentencia, value: $value, where: $where);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al generar data sentencia', data: $data_sentencia);
            }
            $where = $data_sentencia->where;
            $sentencia = $data_sentencia->sentencia;
        }

        $params_sql = (new params_sql())->params_sql(aplica_seguridad: $aplica_seguridad, group_by: $group_by,
            limit: $limit, modelo_columnas_extra: $this->columnas_extra, offset: $offset, order: $order,
            sql_where_previo: $sentencia);

        $consulta .= $where . $sentencia . $params_sql->limit;

        $result = $this->ejecuta_consulta(consulta: $consulta, campos_encriptados: $this->campos_encriptados,
            hijo: $hijo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $result);
        }

        return $result;
    }

    /**
     * REG
     * Genera una consulta SQL a partir de filtros y condiciones específicas.
     *
     * Este método construye dinámicamente una sentencia SQL basada en los parámetros proporcionados, incluyendo
     * columnas, filtros, ordenamientos, paginación y condiciones especiales.
     *
     * ### Flujo del método:
     * 1. Valida los parámetros de `limit` y `offset` para asegurar que sean valores positivos.
     * 2. Verifica que el `tipo_filtro` sea válido llamando a `where::verifica_tipo_filtro()`.
     * 3. Genera la consulta base mediante `genera_consulta_base()`, la cual estructura las columnas y joins.
     * 4. Aplica filtros de inclusión (`IN`) mediante `in_llave()`.
     * 5. Construye un complemento SQL con filtros aplicados utilizando `filtros::complemento_sql()`.
     * 6. Ensambla la consulta SQL final con `filtros::consulta_full_and()`.
     * 7. Asigna la consulta resultante a `$this->consulta` y la retorna.
     *
     * ### Ejemplo de uso:
     * ```php
     * $modelo = new ModeloEjemplo();
     * $sql = $modelo->genera_sql_filtro(
     *     columnas: ['id', 'nombre', 'precio'],
     *     columnas_by_table: [],
     *     columnas_en_bruto: false,
     *     con_sq: false,
     *     diferente_de: [],
     *     extra_join: [],
     *     filtro: ['categoria_id' => 1],
     *     filtro_especial: [],
     *     filtro_extra: [],
     *     filtro_rango: [],
     *     group_by: [],
     *     in: [],
     *     limit: 10,
     *     not_in: [],
     *     offset: 0,
     *     order: ['nombre ASC'],
     *     sql_extra: '',
     *     tipo_filtro: 'AND'
     * );
     * echo $sql;
     * ```
     *
     * ### Ejemplo de salida esperada:
     * ```sql
     * SELECT id, nombre, precio FROM productos WHERE categoria_id = 1 ORDER BY nombre ASC LIMIT 10 OFFSET 0;
     * ```
     *
     * @param array $columnas Columnas a seleccionar en la consulta.
     * @param array $columnas_by_table Columnas organizadas por tabla.
     * @param bool $columnas_en_bruto Indica si las columnas deben estar sin alias ni modificaciones.
     * @param bool $con_sq Indica si se debe generar una subquery en la consulta.
     * @param array $diferente_de Condiciones de exclusión (`!=`).
     * @param array $extra_join Joins adicionales a aplicar en la consulta.
     * @param array $filtro Filtros básicos en formato `columna => valor`.
     * @param array $filtro_especial Filtros especiales con condiciones avanzadas.
     * @param array $filtro_extra Filtros adicionales no estándar.
     * @param array $filtro_rango Filtros por rangos de valores.
     * @param array $group_by Cláusula `GROUP BY`.
     * @param array $in Filtros `IN` para la consulta.
     * @param int $limit Número máximo de registros a retornar.
     * @param array $not_in Filtros `NOT IN`.
     * @param int $offset Desplazamiento (`OFFSET`) para la paginación.
     * @param array $order Cláusula `ORDER BY`.
     * @param string $sql_extra Fragmento SQL adicional para integrar.
     * @param string $tipo_filtro Tipo de filtro a aplicar (`AND` o `OR`).
     * @param bool $count Indica si se debe generar una consulta de conteo (`COUNT(*)`).
     * @param array $filtro_fecha Filtros por fechas.
     *
     * @return array|string Retorna la consulta SQL generada como string o un array de error en caso de falla.
     */


    private function genera_sql_filtro(
        array  $columnas,
        array  $columnas_by_table,
        bool   $columnas_en_bruto,
        bool   $con_sq,
        array  $diferente_de,
        array  $extra_join,
        array  $filtro,
        array  $filtro_especial,
        array  $filtro_extra,
        array  $filtro_rango,
        array  $group_by,
        array  $in,
        int    $limit,
        array  $not_in,
        int    $offset,
        array  $order,
        string $sql_extra,
        string $tipo_filtro,
        bool   $count = false,
        array  $filtro_fecha = array()
    ): array|string
    {
        if ($limit < 0) {
            return $this->error->error(
                mensaje: 'Error limit debe ser mayor o igual a 0',
                data: $limit,
                es_final: true
            );
        }
        if ($offset < 0) {
            return $this->error->error(
                mensaje: 'Error $offset debe ser mayor o igual a 0',
                data: $offset,
                es_final: true
            );
        }

        $verifica_tf = (new \gamboamartin\where\where())->verifica_tipo_filtro(tipo_filtro: $tipo_filtro);
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al validar tipo_filtro',
                data: $verifica_tf
            );
        }

        $consulta = $this->genera_consulta_base(
            columnas: $columnas,
            columnas_by_table: $columnas_by_table,
            columnas_en_bruto: $columnas_en_bruto,
            con_sq: $con_sq,
            count: $count,
            extension_estructura: $this->extension_estructura,
            extra_join: $extra_join,
            renombradas: $this->renombres
        );
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al generar sql',
                data: $consulta
            );
        }

        $in = $this->in_llave(in: $in);
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al integrar in',
                data: $in
            );
        }

        $complemento_sql = (new filtros())->complemento_sql(
            aplica_seguridad: false,
            diferente_de: $diferente_de,
            filtro: $filtro,
            filtro_especial: $filtro_especial,
            filtro_extra: $filtro_extra,
            filtro_rango: $filtro_rango,
            group_by: $group_by,
            in: $in,
            limit: $limit,
            modelo: $this,
            not_in: $not_in,
            offset: $offset,
            order: $order,
            sql_extra: $sql_extra,
            tipo_filtro: $tipo_filtro,
            filtro_fecha: $filtro_fecha
        );
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al maquetar sql',
                data: $complemento_sql
            );
        }

        $sql = (new filtros())->consulta_full_and(
            complemento: $complemento_sql,
            consulta: $consulta,
            modelo: $this
        );
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al maquetar sql',
                data: $sql
            );
        }

        $this->consulta = $sql;

        return $sql;
    }


    /**
     * REG
     * Genera un código aleatorio de longitud variable compuesto por números y letras.
     *
     * Esta función genera una cadena aleatoria utilizando caracteres alfanuméricos
     * (`0-9, a-z, A-Z`). La longitud del código puede ser especificada por el usuario.
     *
     * ### Funcionamiento:
     * 1. **Valida que la longitud sea mayor a 0.**
     * 2. **Define el conjunto de caracteres permitidos (`0-9, a-z, A-Z`).**
     * 3. **Genera un código aleatorio seleccionando caracteres de manera aleatoria.**
     * 4. **Devuelve la cadena generada o un error si la validación falla.**
     *
     * @param int $longitud Longitud del código a generar (por defecto es `6`).
     *
     * @return string|array Un código aleatorio de la longitud especificada o un **array de error** si hay problemas.
     *
     * @example **Ejemplo de uso:**
     * ```php
     * $codigo = $this->get_codigo_aleatorio(longitud: 8);
     * print_r($codigo);
     * ```
     *
     * ### **Posibles salidas:**
     * **Caso 1: Éxito (código generado con longitud `8`)**
     * ```php
     * "G3aL9zQX"
     * ```
     *
     * **Caso 2: Error (`longitud` menor o igual a 0)**
     * ```php
     * Array
     * (
     *     [error] => "Error longitud debe ser mayor a 0"
     *     [data] => 0
     *     [es_final] => true
     * )
     * ```
     *
     * @throws errores Si `$longitud` es menor o igual a `0`, se devuelve un error.
     */
    final public function get_codigo_aleatorio(int $longitud = 6): string|array
    {
        if ($longitud <= 0) {
            return $this->error->error(mensaje: 'Error longitud debe ser mayor  a 0', data: $longitud, es_final: true);
        }
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_string = '';

        for ($i = 0; $i < $longitud; $i++) {
            $random_character = $chars[mt_rand(0, strlen($chars) - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    final public function get_data_by_code(string $codigo, bool $columnas_en_bruto = false)
    {
        $filtro = array();
        $filtro[$this->tabla . '.codigo'] = $codigo;

        $r_data = $this->filtro_and(columnas_en_bruto: $columnas_en_bruto, filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros', data: $r_data);
        }
        if ($r_data->n_registros === 0) {
            return $this->error->error(mensaje: 'Error no existe registro', data: $r_data);
        }
        return $r_data->registros_obj[0];

    }

    final public function get_data_descripcion(string $dato, int $limit = 10, bool $por_descripcion_select = false)
    {
        $filtro = array();
        $filtro[$this->tabla . '.descripcion'] = $dato;
        if ($por_descripcion_select) {
            $filtro = array();
            $filtro[$this->tabla . '.descripcion_select'] = $dato;
        }
        $r_data = $this->filtro_and(filtro: $filtro, limit: $limit, tipo_filtro: 'textos');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros', data: $r_data);
        }

        return $r_data;

    }

    /**
     * Obtiene los datos para datatable
     * @param array $filtro
     * @param array $columnas
     * @param array $filtro_especial Filtro para get data
     * @param int $n_rows_for_page N rows
     * @param int $pagina Num pag
     * @param array $in
     * @param array $extra_join
     * @param array $order
     * @return array
     */
    final public function get_data_lista(array $filtro = array(), array $columnas = array(),
                                         array $filtro_especial = array(), array $filtro_extra = array() ,
                                         array $filtro_rango = array(), int   $n_rows_for_page = 10, int $pagina = 1,
                                         array $in = array(), array $extra_join = array(), array $order = array()): array
    {
        if (count($order) === 0) {
            $order[$this->tabla . '.id'] = 'DESC';
        }

        $limit = $n_rows_for_page;

        $n_rows = $this->cuenta_bis(extra_join: $extra_join, filtro: $filtro, filtro_especial: $filtro_especial,
            filtro_extra: $filtro_extra, filtro_rango: $filtro_rango, in: $in);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros', data: $n_rows);
        }

        $offset = ($pagina - 1) * $n_rows_for_page;

        if ($n_rows <= $limit) {
            $offset = 0;
        }

        $result = $this->filtro_and(columnas: $columnas, extra_join: $extra_join, filtro: $filtro,
            filtro_especial: $filtro_especial, filtro_extra: $filtro_extra, filtro_rango: $filtro_rango, in: $in,
            limit: $limit, offset: $offset,
            order: $order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros', data: $result);
        }

        $out = array();
        $out['n_registros'] = $n_rows;
        $out['registros'] = $result->registros;
        $out['data_result'] = $result;

        return $out;
    }

    final public function get_id_by_codigo(string $codigo)
    {
        $id = -1;
        $existe = $this->existe_by_codigo(codigo: $codigo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar si existe codigo', data: $existe);
        }
        if ($existe) {
            $filtro[$this->tabla . '.codigo'] = $codigo;
            $r_filtro = $this->filtro_and(columnas_en_bruto: true, filtro: $filtro);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener datos', data: $r_filtro);
            }
            $id = (int)$r_filtro->registros_obj[0]->id;
        }
        return $id;

    }

    final public function get_foraneas()
    {
        $foraneas = (new _instalacion(link: $this->link))->get_foraneas(table: $this->tabla);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener foraneas', data: $foraneas);
        }

        $out = new stdClass();
        foreach ($foraneas as $fk) {
            $key = $fk->columna_foranea;
            $out->$key = $fk;
        }
        return $out;

    }

    private function get_predeterminado(): array|stdClass
    {
        $key = $this->tabla . '.predeterminado';
        $filtro[$key] = 'activo';
        $r_modelo = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos', data: $r_modelo);
        }
        if ((int)$r_modelo->n_registros > 1) {
            return $this->error->error(mensaje: 'Error existe mas de un predeterminado', data: $r_modelo);
        }
        return $r_modelo;
    }

    /**
     * Obtiene un identificador predeterminado
     * @return array|int
     * @version 1.486.49
     */
    final public function id_predeterminado(): array|int
    {
        $key = $this->tabla . '.predeterminado';

        $filtro[$key] = 'activo';

        $r_modelo = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener predeterminado', data: $r_modelo);
        }

        if ($r_modelo->n_registros === 0) {
            return $this->error->error(mensaje: 'Error no existe predeterminado', data: $r_modelo);
        }
        if ($r_modelo->n_registros > 1) {
            return $this->error->error(
                mensaje: 'Error existe mas de un predeterminado', data: $r_modelo);
        }

        return (int)$r_modelo->registros[0][$this->key_id];

    }

    final public function id_preferido(string $entidad_relacion)
    {

        $key_id = $entidad_relacion . '_id';
        $sql = "SELECT COUNT(*), $key_id FROM $this->tabla GROUP BY $key_id ORDER BY COUNT(*) DESC LIMIT 1;";

        $result = $this->ejecuta_consulta(consulta: $sql);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener id preferido', data: $result);
        }
        return (int)$result->registros[0][$key_id];

    }

    /**
     * REG
     * Obtiene el ID más frecuente de una entidad específica basada en la cantidad de registros en una tabla.
     *
     * Esta función toma una entidad preferida y realiza una consulta SQL para determinar el ID más frecuente
     * basado en un conteo agrupado. Devuelve el ID con mayor ocurrencia o un array de error si ocurre algún problema.
     *
     * @param string $entidad_preferida Nombre de la entidad sobre la cual se quiere determinar el ID más frecuente.
     *                                  Ejemplo: 'usuario', 'producto', 'cliente'.
     * @param array $extension_estructura Arreglo de estructuras adicionales a incluir en la consulta de `joins`.
     *                                    Ejemplo:
     *                                    [
     *                                        ['tabla' => 'usuario_datos', 'llave' => 'usuario_id']
     *                                    ]
     * @param array $extra_join Joins adicionales que se deseen agregar a la consulta.
     *                          Ejemplo:
     *                          [
     *                              ['tabla' => 'direccion', 'llave' => 'cliente_id']
     *                          ]
     * @param array $renombradas Arreglo de nombres de columnas renombradas en la consulta.
     *                           Ejemplo:
     *                           [
     *                               'usuario.nombre' => 'nombre_usuario'
     *                           ]
     *
     * @return int|array Retorna el ID más frecuente encontrado en la consulta. En caso de error, devuelve un array con detalles del error.
     *
     * Ejemplo de retorno exitoso:
     * ```php
     * 25
     * ```
     *
     * Ejemplo de retorno en caso de error:
     * ```php
     * [
     *     'error' => true,
     *     'mensaje' => 'Error entidad_preferida esta vacia',
     *     'data' => ''
     * ]
     * ```
     *
     * @throws errores Si ocurre un error durante la consulta SQL o el procesamiento de datos.
     */
    public function id_preferido_detalle(string $entidad_preferida, array $extension_estructura = array(),
                                         array  $extra_join = array(), array $renombradas = array()): int|array
    {
        // Validación: Verificar que la entidad preferida no esté vacía.
        $entidad_preferida = trim($entidad_preferida);
        if ($entidad_preferida === '') {
            return $this->error->error(mensaje: 'Error entidad_preferida esta vacia', data: $entidad_preferida);
        }

        // Definición de las claves para la consulta SQL.
        $key_id_preferido = "$entidad_preferida.id";
        $key_id_preferido_out = $entidad_preferida . "_id";

        // Generación de joins para la consulta SQL.
        $tablas = (new joins())->tablas(
            columnas: $this->columnas,
            extension_estructura: $extension_estructura,
            extra_join: $extra_join,
            modelo_tabla: $this->tabla,
            renombradas: $renombradas,
            tabla: $this->tabla
        );

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar joins en ' . $this->tabla, data: $tablas);
        }

        // Construcción de la consulta SQL para obtener el ID más frecuente.
        $sql = sprintf(
        /** @lang MYSQL */
            "SELECT COUNT(*), %s AS %s FROM %s GROUP BY %s 
         ORDER BY COUNT(*) DESC LIMIT 1;",
            $key_id_preferido, $key_id_preferido_out, $tablas, $key_id_preferido
        );

        // Ejecución de la consulta.
        $result = $this->ejecuta_consulta(consulta: $sql);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener id preferido', data: $result);
        }

        // Procesamiento del resultado.
        $id_pref = -1;
        if (isset($result->registros[0][$key_id_preferido_out])) {
            $id_pref = (int)$result->registros[0][$key_id_preferido_out];
        }

        return $id_pref;
    }


    /**
     * REG
     * Normaliza y valida el arreglo de configuración para la cláusula IN en SQL.
     *
     * Esta función procesa un array que debe contener la clave 'llave' (y opcionalmente 'values') para
     * construir una cláusula IN en una consulta SQL. Realiza las siguientes acciones:
     *
     * <ul>
     *     <li>Verifica si el array tiene elementos.</li>
     *     <li>Si existe la clave 'llave', se valida que su valor sea un string.</li>
     *     <li>Se recorta el valor de 'llave' para eliminar espacios en blanco al inicio y al final.</li>
     *     <li>Si el valor resultante es una cadena vacía, se retorna un error indicando que la llave está vacía.</li>
     *     <li>Si la 'llave' existe en el array de columnas extra (<code>$this->columnas_extra</code>), se reemplaza
     *         por el valor correspondiente de dicho arreglo.</li>
     * </ul>
     *
     * @param array $in Array asociativo que debe incluir, al menos, la clave:
     *                  - <code>'llave'</code>: Nombre de la columna que se utilizará en la cláusula IN.
     *                    Este valor debe ser un string no vacío. Opcionalmente, puede incluir la clave
     *                  - <code>'values'</code>: Un array de valores a utilizar en la cláusula IN.
     *
     * @return array Devuelve el mismo array <code>$in</code> con la llave validada y normalizada.
     *               En caso de error, retorna un array con la estructura de error definida por la clase <code>errores</code>.
     *
     * @return array
     * @example Ejemplo 2: La llave es una cadena vacía
     * <pre>
     * $in = [
     *     'llave'  => '   ',
     *     'values' => [1, 2, 3]
     * ];
     *
     * // La función detecta que, tras aplicar trim, la llave es vacía y retorna un error:
     * // [
     * //     'error'     => 1,
     * //     'mensaje'   => 'Error in[llave] esta vacia',
     * //     'data'      => $in,
     * //     'es_final'  => true
     * // ]
     * $resultado = $this->in_llave($in);
     * </pre>
     *
     * @example Ejemplo 3: La llave no es un string
     * <pre>
     * $in = [
     *     'llave'  => 123, // Valor numérico en lugar de string
     *     'values' => [1, 2, 3]
     * ];
     *
     * // La función retorna un error indicando que la llave debe ser un string:
     * // [
     * //     'error'     => 1,
     * //     'mensaje'   => 'Error in[llave] debe ser un string',
     * //     'data'      => $in,
     * //     'es_final'  => true
     * // ]
     * $resultado = $this->in_llave($in);
     * </pre>
     *
     * @example Ejemplo 1: Llave definida correctamente sin coincidencia en columnas extra
     * <pre>
     * $in = [
     *     'llave'  => 'nombre_columna',
     *     'values' => [1, 2, 3]
     * ];
     *
     * // Si $this->columnas_extra no contiene 'nombre_columna', la función devuelve:
     * // [
     * //     'llave'  => 'nombre_columna',
     * //     'values' => [1, 2, 3]
     * // ]
     * $resultado = $this->in_llave($in);
     * </pre>
     *
     */
    private function in_llave(array $in): array
    {
        if (count($in) > 0) {
            if (isset($in['llave'])) {
                if (!is_string($in['llave'])) {
                    return $this->error->error(
                        mensaje: 'Error in[llave] debe ser un string',
                        data: $in,
                        es_final: true
                    );
                }
                $in['llave'] = trim($in['llave']);
                if ($in['llave'] === '') {
                    return $this->error->error(
                        mensaje: 'Error in[llave] esta vacia',
                        data: $in,
                        es_final: true
                    );
                }
                if (array_key_exists($in['llave'], $this->columnas_extra)) {
                    $in['llave'] = $this->columnas_extra[$in['llave']];
                }
            }
        }
        return $in;
    }


    /**
     * Inserta un registro predeterminado del modelo en ejecucion
     * @param string|int $codigo Codigo predeterminado default
     * @param string $descripcion Descripcion predeterminado
     * @return array|stdClass
     */
    final public function inserta_predeterminado(
        string|int $codigo = 'PRED', string $descripcion = 'PREDETERMINADO'): array|stdClass
    {

        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: array());
        }

        $r_pred = new stdClass();
        $existe = $this->existe_predeterminado();
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al validar si existe predeterminado en modelo ' . $this->tabla, data: $existe);
        }
        if (!$existe) {
            $r_pred = $this->alta_predeterminado(codigo: $codigo, descripcion: $descripcion);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar prederminado en modelo ' . $this->tabla, data: $r_pred);
            }
        }
        return $r_pred;
    }

    final public function inserta_registro_si_no_existe(array $registro, array $con_descripcion = array()): array|string|stdClass
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro);
        }

        if (count($con_descripcion) === 0) {
            $existe = $this->existe_by_id(registro_id: $registro['id']);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al verificar si existe registro', data: $existe);
            }
            $inserta = 'Id ' . $registro['id'] . ' Ya existe';
        } else {
            $existe = $this->existe(filtro: $con_descripcion);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al verificar si existe registro', data: $existe);
            }
            $inserta = 'Id ' . $registro['descripcion'] . ' Ya existe';
        }

        if (!$existe) {
            $inserta = $this->alta_registro(registro: $registro);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_persona', data: $inserta);
            }
        }
        return $inserta;

    }

    final public function inserta_registro_si_no_existe_code(array $registro, array $con_descripcion = array()): array|string|stdClass
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro);
        }

        if (count($con_descripcion) === 0) {
            $existe = $this->existe_by_codigo(codigo: $registro['codigo']);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al verificar si existe registro', data: $existe);
            }
            $inserta = 'Codigo ' . $registro['codigo'] . ' Ya existe';
        } else {
            $existe = $this->existe(filtro: $con_descripcion);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al verificar si existe registro', data: $existe);
            }
            $inserta = 'Descripcion ' . $registro['descripcion'] . ' Ya existe';
        }

        if (!$existe) {
            $inserta = $this->alta_registro(registro: $registro);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_persona', data: $inserta);
            }
        }
        return $inserta;

    }

    final public function inserta_registro_si_no_existe_filtro(array $registro, array $filtro): array|string|stdClass
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro);
        }
        $existe = $this->existe(filtro: $filtro);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al verificar si existe registro', data: $existe);
        }
        $inserta = 'Row ' . serialize($filtro) . ' Ya existe';

        if (!$existe) {
            $inserta = $this->alta_registro(registro: $registro);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_persona', data: $inserta);
            }
        }
        return $inserta;

    }

    final public function inserta_registros(array $registros)
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registros);
        }
        $out = array();
        foreach ($registros as $registro) {
            $alta_bd = $this->alta_registro(registro: $registro);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar registro del modelo ' . $this->tabla,
                    data: $alta_bd);
            }
            $out[] = $alta_bd;
        }
        return $out;

    }

    final public function inserta_registros_no_existentes_id(array $registros): array
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registros);
        }
        $out = array();
        foreach ($registros as $registro) {

            $inserta = $this->inserta_registro_si_no_existe(registro: $registro);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar registro', data: $inserta);
            }
            $out[] = $inserta;

        }

        return $out;

    }


    /**
     * PHPUNIT
     * @param float $sub_total
     * @return float
     */
    protected function iva(float $sub_total): float
    {
        $iva = $sub_total * .16;
        return round($iva, 2);
    }


    /**
     * Limpia campos extras de un registro de datos
     * @param array $registro Registro en proceso
     * @param array $campos_limpiar Campos a limpiar
     * @return array
     * @version 9.82.2
     */
    final public function limpia_campos_extras(array $registro, array $campos_limpiar): array
    {
        foreach ($campos_limpiar as $valor) {
            $valor = trim($valor);
            if ($valor === '') {
                return $this->error->error(mensaje: 'Error el valor no puede venir vacio' . $this->tabla, data: $valor);
            }
            if (isset($registro[$valor])) {
                unset($registro[$valor]);
            }
        }
        return $registro;
    }

    /**
     * PRUEBAS FINALIZADAS
     * @param array $registro
     * @param int $id
     * @return array
     */
    public function limpia_campos_registro(array $registro, int $id): array
    {
        $data_upd = array();
        foreach ($registro as $campo) {
            $data_upd[$campo] = '';
        }
        $r_modifica = $this->modifica_bd($data_upd, $id);
        if (errores::$error) {
            return $this->error->error("Error al modificar", $r_modifica);
        }
        $registro = $this->registro(registro_id: $id);
        if (errores::$error) {
            return $this->error->error("Error al obtener registro", $registro);
        }
        return $registro;

    }

    private function limpia_campos_obligatorios(array $unsets): array
    {
        foreach ($this->campos_obligatorios as $key => $campo_obligatorio) {
            if (in_array($campo_obligatorio, $unsets, true)) {
                unset($this->campos_obligatorios[$key]);
            }
        }
        return $this->campos_obligatorios;
    }

    /**
     * TOTAL
     * Limpia el array de registro proporcionado, eliminando los campos que no existen en el array de atributos.
     *
     * @param array $registro El array a limpiar.
     *
     * @return array El array limpio. Este array no incluirá los campos que no existen en el array de atributos.
     *
     * @throws errores Si algún campo es una cadena vacía, se generará un error con el mensaje "Error campo está vacio".
     * @version 16.121.0
     * @url https://github.com/gamboamartin/administrador/wiki/administrador.base.orm.modelo.limpia_campos_sin_bd
     */
    private function limpia_campos_sin_bd(array $registro): array
    {
        foreach ($registro as $campo => $value) {
            $campo = trim($campo);
            if ($campo === '') {
                return $this->error->error(mensaje: "Error campo esta vacio", data: $registro, es_final: true);
            }
            $attrs = (array)$this->atributos;
            if (!array_key_exists($campo, $attrs)) {
                unset($registro[$campo]);
            }
        }
        return $registro;
    }


    /**
     *
     * Modifica los datos de un registro de un modelo
     * @param array $registro registro con datos a modificar
     * @param int $id id del registro a modificar
     * @param bool $reactiva para evitar validacion de status inactivos
     * @return array|stdClass resultado de la insercion
     * @example
     *      $r_modifica_bd =  parent::modifica_bd($registro, $id, $reactiva);
     * @internal  $this->validacion->valida_transaccion_activa($this, $this->aplica_transaccion_inactivo, $this->registro_id, $this->tabla);
     * @internal  $this->genera_campos_update();
     * @internal  $this->agrega_usuario_session();
     * @internal  $this->ejecuta_sql();
     * @internal  $this->bitacora($this->registro_upd,__FUNCTION__, $consulta);
     */
    public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {
        if ($this->usuario_id <= 0) {
            return $this->error->error(mensaje: 'Error usuario invalido no esta logueado', data: $this->usuario_id);
        }

        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro);
        }


        $resultado = $this->modifica_bd_base(registro: $registro, id: $id, reactiva: $reactiva);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $resultado);
        }


        return $resultado;
    }

    final public function modifica_bd_base(array $registro, int $id, bool $reactiva = false, bool $valida_row_vacio = true)
    {
        $registro_original = $registro;
        $registro_original = serialize(value: $registro_original);
        if ($this->usuario_id <= 0) {
            return $this->error->error(mensaje: 'Error usuario invalido no esta logueado', data: $this->usuario_id,
                es_final: true);
        }

        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro,
                es_final: true);
        }

        $registro = $this->limpia_campos_sin_bd(registro: $registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $registro);
        }

        $init = (new inicializacion())->init_upd(id: $id, modelo: $this, registro: $registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar registro original ' . $registro_original .
                ' del modelo ' . $this->tabla, data: $init);
        }


        $valida = (new validaciones())->valida_upd_base(id: $id, registro_upd: $this->registro_upd,
            tipo_campos: $this->tipo_campos, valida_row_vacio: $valida_row_vacio);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $ajusta = (new inicializacion())->ajusta_campos_upd(id: $id, modelo: $this);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ajustar elemento', data: $ajusta);
        }

        $ejecuta_upd = (new upd())->ejecuta_upd(id: $id, modelo: $this);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al verificar actualizacion', data: $ejecuta_upd);
        }

        $resultado = (new upd())->aplica_ejecucion(ejecuta_upd: $ejecuta_upd, id: $id, modelo: $this,
            reactiva: $reactiva, registro: $registro, valida_user: $this->valida_user);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $resultado);
        }

        return $resultado;

    }

    /**
     * PHPUNIT
     * @param array $filtro
     * @param array $registro
     * @return string[]
     */
    public function modifica_con_filtro_and(array $filtro, array $registro): array
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro);
        }

        $this->registro_upd = $registro;
        if (count($this->registro_upd) === 0) {
            return $this->error->error('El registro no puede venir vacio', $this->registro_upd);
        }
        if (count($filtro) === 0) {
            return $this->error->error('El filtro no puede venir vacio', $filtro);
        }

        $r_data = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error('Error al obtener registros', $r_data);
        }

        $data = array();
        foreach ($r_data['registros'] as $row) {
            $upd = $this->modifica_bd($registro, $row[$this->tabla . '_id']);
            if (errores::$error) {
                return $this->error->error('Error al modificar registro', $upd);
            }
            $data[] = $upd;
        }


        return array('mensaje' => 'Registros modificados con exito', $data);

    }

    /**
     * PHPUNIT
     * @param array $registro
     * @param int $id
     * @return array
     */
    public function modifica_por_id(array $registro, int $id): array
    {

        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro);
        }
        $r_modifica = $this->modifica_bd($registro, $id);
        if (errores::$error) {
            return $this->error->error("Error al modificar", $r_modifica);
        }
        return $r_modifica;

    }

    /**
     * REG
     * Obtiene un registro único de la base de datos basado en su ID y asigna sus valores a `$this->row`.
     *
     * Esta función realiza una consulta para obtener un registro único de la base de datos. La consulta incluye columnas
     * específicas, estructuras extendidas y relaciones, según los parámetros proporcionados. Además, valida que el ID del
     * registro sea válido y que los resultados obtenidos no generen problemas de integridad.
     *
     * @param array $columnas (Opcional) Lista de columnas específicas a incluir en la consulta. Si está vacío, se seleccionan
     *                        todas las columnas.
     * @param bool $columnas_en_bruto (Opcional) Si es `true`, las columnas se procesan sin modificaciones.
     * @param array $extension_estructura (Opcional) Estructura extendida que define tablas relacionadas para uniones
     *                                           adicionales. Si está vacío, se utiliza la propiedad `$this->extension_estructura`.
     * @param array $hijo (Opcional) Propiedades adicionales para enriquecer el resultado, como dependencias o subconsultas.
     *
     * @return array Retorna un arreglo con el registro obtenido o un arreglo con los detalles del error en caso de fallo.
     *
     * @throws errores Retorna un error si:
     * - `registro_id` es menor a 0.
     * - La consulta no genera un registro.
     * - Existen múltiples registros con el mismo ID.
     * - Falla la función interna `obten_por_id` para construir y ejecutar la consulta.
     *
     * @note Esta función asigna los valores del registro encontrado a la propiedad `$this->row`.
     * @note Depende de la función `obten_por_id` para realizar la consulta principal.
     * @example Uso exitoso:
     * ```php
     * $modelo = new modelo();
     * $modelo->registro_id = 123;
     * $modelo->tabla = 'usuarios';
     * $resultado = $modelo->obten_data(
     *     columnas: ['id', 'nombre', 'email'],
     *     columnas_en_bruto: false,
     *     extension_estructura: ['perfiles' => ['id', 'nombre']],
     *     hijo: []
     * );
     * // Resultado:
     * // [
     * //     'id' => 123,
     * //     'nombre' => 'Juan Pérez',
     * //     'email' => 'juan.perez@ejemplo.com'
     * // ]
     * // Además, $modelo->row tendrá los datos asignados:
     * // stdClass {
     * //     "id": 123,
     * //     "nombre": "Juan Pérez",
     * //     "email": "juan.perez@ejemplo.com"
     * // }
     * ```
     *
     * @example Error por ID no válido:
     * ```php
     * $modelo = new modelo();
     * $modelo->registro_id = -1; // ID no válido
     * $resultado = $modelo->obten_data();
     * // Resultado:
     * // [
     * //     'error' => true,
     * //     'mensaje' => 'Error el id debe ser mayor a 0 en el modelo usuarios',
     * //     'data' => -1
     * // ]
     * ```
     *
     * @example Error por registro no encontrado:
     * ```php
     * $modelo = new modelo();
     * $modelo->registro_id = 9999; // ID inexistente
     * $modelo->tabla = 'usuarios';
     * $resultado = $modelo->obten_data();
     * // Resultado:
     * // [
     * //     'error' => true,
     * //     'mensaje' => 'Error no existe registro de usuarios',
     * //     'data' => [...]
     * // ]
     * ```
     *
     * @example Error por múltiples registros con el mismo ID:
     * ```php
     * $modelo = new modelo();
     * $modelo->registro_id = 123; // ID duplicado
     * $modelo->tabla = 'usuarios';
     * $resultado = $modelo->obten_data();
     * // Resultado:
     * // [
     * //     'error' => true,
     * //     'mensaje' => 'Error de integridad existe mas de un registro con el mismo id usuarios',
     * //     'data' => [...]
     * // ]
     * ```
     *
     */
    final public function obten_data(
        array $columnas = array(),
        bool  $columnas_en_bruto = false,
        array $extension_estructura = array(),
        array $hijo = array()
    ): array
    {
        $this->row = new stdClass();
        if ($this->registro_id < 0) {
            return $this->error->error(
                mensaje: 'Error el id debe ser mayor a 0 en el modelo ' . $this->tabla,
                data: $this->registro_id,
                es_final: true
            );
        }
        if (count($extension_estructura) === 0) {
            $extension_estructura = $this->extension_estructura;
        }
        $resultado = $this->obten_por_id(
            columnas: $columnas,
            columnas_en_bruto: $columnas_en_bruto,
            extension_estructura: $extension_estructura,
            hijo: $hijo
        );

        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al obtener por id en ' . $this->tabla,
                data: $resultado
            );
        }
        if ((int)$resultado->n_registros === 0) {
            return $this->error->error(
                mensaje: 'Error no existe registro de ' . $this->tabla,
                data: $resultado
            );
        }
        if ((int)$resultado->n_registros > 1) {
            return $this->error->error(
                mensaje: 'Error de integridad existe mas de un registro con el mismo id ' . $this->tabla,
                data: $resultado
            );
        }
        foreach ($resultado->registros[0] as $campo => $value) {
            $this->row->$campo = $value;
        }
        return $resultado->registros[0];
    }


    /**
     *
     * Devuelve un array con los datos del ultimo registro
     * @param array $filtro filtro a aplicar en sql
     * @param bool $aplica_seguridad si aplica seguridad integra usuario_permitido_id
     * @return array con datos del registro encontrado o registro vacio
     * @example
     *      $filtro['prospecto.aplica_ruleta'] = 'activo';
     * $resultado = $this->obten_datos_ultimo_registro($filtro);
     *
     * @internal  $this->filtro_and($filtro,'numeros',array(),$this->order,1);
     * @version 1.451.48
     */
    public function obten_datos_ultimo_registro(bool  $aplica_seguridad = true, array $columnas = array(),
                                                bool  $columnas_en_bruto = false, array $filtro = array(),
                                                array $filtro_extra = array(), array $order = array()): array
    {
        if ($this->tabla === '') {
            return $this->error->error(mensaje: 'Error tabla no puede venir vacia', data: $this->tabla);
        }
        if (count($order) === 0) {
            $order = array($this->tabla . '.id' => 'DESC');
        }

        $this->limit = 1;

        $resultado = $this->filtro_and(aplica_seguridad: $aplica_seguridad, columnas: $columnas,
            columnas_en_bruto: $columnas_en_bruto, filtro: $filtro, filtro_extra: $filtro_extra, limit: 1,
            order: $order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos', data: $resultado);
        }
        if ((int)$resultado->n_registros === 0) {
            return array();
        }
        return $resultado->registros[0];

    }

    /**
     * REG
     * Obtiene un registro específico de la base de datos basado en su ID.
     *
     * Esta función genera una consulta SQL para obtener un registro único de la base de datos, incluyendo columnas
     * específicas, estructuras extendidas y uniones adicionales según los parámetros proporcionados. La consulta
     * aplica filtros basados en el `registro_id` del modelo.
     *
     * @param array $columnas (Opcional) Un arreglo de columnas que se incluirán en la consulta. Si está vacío,
     *                        se seleccionarán todas las columnas disponibles.
     * @param array $columnas_by_table (Opcional) Un arreglo de columnas agrupadas por tabla. Si está vacío,
     *                                  se omite este filtrado.
     * @param bool $columnas_en_bruto (Opcional) Si es `true`, genera las columnas de forma directa, sin procesar.
     * @param array $extension_estructura (Opcional) Estructura extendida de tablas que se unirán a la consulta. Si
     *                                           está vacío, se utiliza la propiedad `$this->extension_estructura`.
     * @param array $extra_join (Opcional) Uniones adicionales a incluir en la consulta.
     * @param array $hijo (Opcional) Propiedades adicionales para enriquecer el resultado, como subconsultas o
     *                    dependencias de datos.
     *
     * @return array|stdClass Retorna el registro obtenido como un objeto `stdClass` o un arreglo con los detalles
     *                        del error en caso de fallo.
     *
     * @throws errores Retorna un error si:
     * - `registro_id` es menor a 0.
     * - Falla la generación de la consulta base mediante `genera_consulta_base`.
     * - Falla la generación de la cláusula `WHERE` mediante `_where::sql_where`.
     * - Falla la ejecución de la consulta SQL mediante `ejecuta_consulta`.
     *
     * @note Esta función utiliza otras funciones internas como `genera_consulta_base`, `_where::sql_where`, y
     *       `ejecuta_consulta` para construir, filtrar y ejecutar la consulta SQL.
     * @example Uso exitoso:
     * ```php
     * $modelo = new modelo();
     * $modelo->registro_id = 123;
     * $modelo->tabla = 'usuarios';
     * $resultado = $modelo->obten_por_id(
     *     columnas: ['id', 'nombre', 'email'],
     *     columnas_by_table: ['usuarios' => ['id', 'nombre']],
     *     extension_estructura: ['perfiles' => ['id', 'nombre']],
     *     extra_join: [['tabla_base' => 'usuarios', 'tabla_enlace' => 'perfiles', 'campo' => 'perfil_id']],
     * );
     * // Resultado:
     * // stdClass {
     * //     "id": 123,
     * //     "nombre": "Juan Pérez",
     * //     "email": "juan.perez@ejemplo.com",
     * //     "perfil_nombre": "Administrador"
     * // }
     * ```
     *
     * @example Error por ID no válido:
     * ```php
     * $modelo = new modelo();
     * $modelo->registro_id = -1; // ID no válido
     * $resultado = $modelo->obten_por_id();
     * // Resultado:
     * // [
     * //     'error' => true,
     * //     'mensaje' => 'Error el id debe ser mayor a 0',
     * //     'data' => -1
     * // ]
     * ```
     *
     * @example Error en la consulta:
     * ```php
     * $modelo = new modelo();
     * $modelo->registro_id = 123;
     * $modelo->tabla = 'usuarios';
     * $resultado = $modelo->obten_por_id(
     *     columnas: ['id', 'nombre'],
     *     extension_estructura: [['tabla_base' => '', 'tabla_enlace' => 'perfiles']]
     * );
     * // Resultado:
     * // [
     * //     'error' => true,
     * //     'mensaje' => 'Error al generar consulta base',
     * //     'data' => [...detalles del error...]
     * // ]
     * ```
     *
     */
    private function obten_por_id(
        array $columnas = array(),
        array $columnas_by_table = array(),
        bool  $columnas_en_bruto = false,
        array $extension_estructura = array(),
        array $extra_join = array(),
        array $hijo = array()
    ): array|stdClass
    {
        if ($this->registro_id < 0) {
            return $this->error->error(
                mensaje: 'Error el id debe ser mayor a 0',
                data: $this->registro_id,
                es_final: true
            );
        }
        if (count($extension_estructura) === 0) {
            $extension_estructura = $this->extension_estructura;
        }

        $consulta = $this->genera_consulta_base(
            columnas: $columnas,
            columnas_by_table: $columnas_by_table,
            columnas_en_bruto: $columnas_en_bruto,
            extension_estructura: $extension_estructura,
            extra_join: $extra_join,
            renombradas: $this->renombres
        );
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar consulta base', data: $consulta);
        }

        $consulta = (new _where())->sql_where(consulta: $consulta, modelo: $this);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar consulta con where', data: $consulta);
        }

        $result = $this->ejecuta_consulta(
            consulta: $consulta,
            campos_encriptados: $this->campos_encriptados,
            hijo: $hijo
        );

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $result);
        }
        return $result;
    }


    /**
     * POR DOCUMENTAR EN WIKI
     * Obtiene los registros completos de una entidad
     * @param bool $aplica_seguridad Indica si se debe aplicar seguridad a la consulta SQL
     * @param array $columnas Las columnas de la tabla que se deben incluir en la consulta SQL
     * @param bool $columnas_en_bruto Indica si se deben incluir las columnas en bruto (sin procesar)
     * @param bool $con_sq Indica si se debe incluir la consulta SQL en la consulta final
     * @param array $group_by Los campos por los que se debe agrupar la consulta SQL
     * @param int $limit El límite de registros que se deben obtener de la consulta SQL
     * @param string $sql_extra Una sentencia SQL adicional para agregar a la consulta
     * @return array|stdClass Los registros obtenidos de la consulta SQL
     * @version 16.247.0
     */
    final public function obten_registros(bool   $aplica_seguridad = false, array $columnas = array(),
                                          bool   $columnas_en_bruto = false, bool $con_sq = true,
                                          array  $group_by = array(), int $limit = 0,
                                          string $sql_extra = ''): array|stdClass
    {

        if ($this->limit > 0) {
            $limit = $this->limit;
        }


        $base = (new sql())->sql_select_init(aplica_seguridad: $aplica_seguridad, columnas: $columnas,
            columnas_en_bruto: $columnas_en_bruto, con_sq: $con_sq, extension_estructura: $this->extension_estructura,
            group_by: $group_by, limit: $limit, modelo: $this, offset: $this->offset, order: $this->order,
            renombres: $this->renombres, sql_where_previo: $sql_extra);

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar datos en ' . $this->tabla, data: $base);
        }

        $consulta = (new sql())->sql_select(consulta_base: $base->consulta_base, params_base: $base->params,
            sql_extra: $sql_extra);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar consulta en ' . $this->tabla, data: $consulta);
        }

        $this->transaccion = 'SELECT';
        $result = $this->ejecuta_consulta(consulta: $consulta, campos_encriptados: $this->campos_encriptados);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar consulta en ' . $this->tabla, data: $result);
        }
        $this->transaccion = '';

        return $result;
    }

    /**
     * Devuelve un conjunto de registros con status igual a activo
     * @param array $order array para ordenar el resultado
     * @param array $filtro filtro para generar AND en el resultado
     * @param array $hijo parametros para la asignacion de registros de tipo hijo del modelo en ejecucion
     * @return array|stdClass conjunto de registros
     * @example
     *      $resultado = $modelo->obten_registros_activos(array(),array());
     * @example
     *      $resultado = $modelo->obten_registros_activos(array(), $filtro);
     * @example
     *      $r_producto = $this->obten_registros_activos();
     *
     * @internal $this->genera_consulta_base()
     * @internal $this->genera_and()
     * @internal $this->ejecuta_consulta()
     * @version 1.264.40
     * @verfuncion 1.1.0
     * @fecha 2022-08-02 17:03
     * @author mgamboa
     */
    final public function obten_registros_activos(array $filtro = array(), array $hijo = array(),
                                                  array $order = array()): array|stdClass
    {

        $filtro[$this->tabla . '.status'] = 'activo';
        $r_data = $this->filtro_and(filtro: $filtro, hijo: $hijo, order: $order);
        if (errores::$error) {
            return $this->error->error(mensaje: "Error al filtrar", data: $r_data);
        }

        return $r_data;
    }

    /**
     *
     * Devuelve un conjunto de registros ordenados con filtro
     * @param string $campo campo de orden
     * @param bool $columnas_en_bruto
     * @param array $extra_join
     * @param array $filtros filtros para generar AND en el resultado
     * @param string $orden metodo ordenamiento ASC DESC
     * @return array|stdClass conjunto de registros
     * @example
     *  $filtro = array('elemento_lista.status'=>'activo','seccion_menu.descripcion'=>$seccion,'elemento_lista.encabezado'=>'activo');
     * $resultado = $elemento_lista_modelo->obten_registros_filtro_and_ordenado($filtro,'elemento_lista.orden','ASC');
     *
     * @internal  $this->genera_and();
     * @internal this->genera_consulta_base();
     * @internal $this->ejecuta_consulta();
     */
    public function obten_registros_filtro_and_ordenado(string $campo, bool $columnas_en_bruto, array $extra_join,
                                                        array  $filtros, string $orden): array|stdClass
    {
        $this->filtro = $filtros;
        if (count($this->filtro) === 0) {
            return $this->error->error(mensaje: 'Error los filtros no pueden venir vacios', data: $this->filtro,
                es_final: true);
        }
        if ($campo === '') {
            return $this->error->error(mensaje: 'Error campo no pueden venir vacios', data: $this->filtro, es_final: true);
        }

        $sentencia = (new \gamboamartin\where\where())->genera_and(columnas_extra: $this->columnas_extra, filtro: $filtros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar and', data: $sentencia);
        }
        $consulta = $this->genera_consulta_base(columnas_en_bruto: $columnas_en_bruto,
            extension_estructura: $this->extension_estructura, extra_join: $extra_join, renombradas: $this->renombres);

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar consulta', data: $consulta);
        }

        $where = " WHERE $sentencia";
        $order_by = " ORDER BY $campo $orden";
        $consulta .= $where . $order_by;

        $result = $this->ejecuta_consulta(consulta: $consulta, campos_encriptados: $this->campos_encriptados);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar sql', data: $result);
        }

        return $result;
    }

    /**
     * @return array|int
     */
    final public function obten_ultimo_registro(): int|array
    {
        $this->order = array($this->tabla . '.id' => 'DESC');
        $this->limit = 1;
        $resultado = $this->obten_registros(limit: $this->limit);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros', data: $resultado);
        }

        if ((int)$resultado->n_registros === 0) {
            return 1;
        }

        return $resultado->registros[0][$this->key_id] + 1;
    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Obtiene el primer id del modelo en ejecucion
     * @return array|int un array si existe error, un numero entero en caso de exito
     * @version 16.256.1
     */
    final public function primer_id(): int|array
    {
        $rows = $this->registros(columnas_en_bruto: true, limit: 1);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros', data: $rows);
        }
        $primer_id = -1;
        if (count($rows) > 0) {
            $primer_id = (int)$rows[0]['id'];
        }
        return $primer_id;

    }


    /**
     * REG
     * Obtiene un registro de la base de datos con las columnas y estructuras especificadas.
     *
     * @param int $registro_id ID del registro a obtener. Debe ser mayor a 0.
     * @param array $columnas Lista de columnas a recuperar. Si está vacío, se recuperan todas las disponibles.
     * @param bool $columnas_en_bruto Si es `true`, obtiene los datos sin procesar.
     * @param array $extension_estructura Permite extender la estructura del registro con datos adicionales.
     * @param array $hijo Configura la relación con registros hijos.
     * @param bool $retorno_obj Si es `true`, el resultado se devuelve como un objeto en lugar de un array.
     *
     * @return array|stdClass Devuelve el registro como un array o un objeto (`stdClass`) según la configuración de `$retorno_obj`.
     *
     * @throws array Devuelve un array de error en el formato `errores::$error` si `$registro_id` es menor o igual a 0 o si ocurre un fallo al obtener el registro.
     *
     * @example
     * // Ejemplo 1: Obtener un registro con ID 10 con columnas específicas
     * $registro = $obj->registro(
     *     registro_id: 10,
     *     columnas: ['nombre', 'email', 'fecha_creacion']
     * );
     * print_r($registro);
     *
     * @example
     * // Ejemplo 2: Obtener un registro como objeto
     * $registro = $obj->registro(
     *     registro_id: 25,
     *     columnas: ['nombre', 'apellido'],
     *     retorno_obj: true
     * );
     * echo $registro->nombre;
     *
     * @example
     * // Ejemplo 3: Obtener un registro con estructura extendida y sin procesamiento de columnas
     * $registro = $obj->registro(
     *     registro_id: 50,
     *     extension_estructura: ['extra_info'],
     *     columnas_en_bruto: true
     * );
     * print_r($registro);
     *
     * @example
     * // Ejemplo 4: Manejo de error si el registro ID es inválido
     * $registro = $obj->registro(
     *     registro_id: 0
     * );
     * if (isset($registro['error'])) {
     *     echo "Error: " . $registro['mensaje'];
     * }
     */
    final public function registro(
        int   $registro_id,
        array $columnas = array(),
        bool  $columnas_en_bruto = false,
        array $extension_estructura = array(),
        array $hijo = array(),
        bool  $retorno_obj = false
    ): array|stdClass
    {
        if ($registro_id <= 0) {
            return $this->error->error(
                mensaje: 'Error al obtener registro: $registro_id debe ser mayor a 0',
                data: $registro_id,
                es_final: true
            );
        }

        $this->registro_id = $registro_id;
        $registro = $this->obten_data(
            columnas: $columnas,
            columnas_en_bruto: $columnas_en_bruto,
            extension_estructura: $extension_estructura,
            hijo: $hijo
        );

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro', data: $registro);
        }

        if ($retorno_obj) {
            $registro = (object)$registro;
        }

        return $registro;
    }


    /**
     * Obtiene el registro basado en el codigo
     * @param string $codigo codigo a obtener
     * @param array $columnas Columnas custom
     * @param bool $columnas_en_bruto true retorna las columnas tal cual la bd
     * @param array $extra_join joins extra
     * @param array $hijo Hijos de row
     * @param bool $retorno_obj Retorna el resultado como un objeto
     * @return array|stdClass
     * @version 8.86.1
     */
    final public function registro_by_codigo(string $codigo, array $columnas = array(), bool $columnas_en_bruto = false,
                                             array  $extra_join = array(), array $hijo = array(),
                                             bool   $retorno_obj = false): array|stdClass
    {

        $codigo = trim($codigo);
        if ($codigo === '') {
            return $this->error->error(mensaje: 'Error el codigo esta vacio', data: $codigo);
        }

        $filtro[$this->tabla . '.codigo'] = $codigo;

        $registros = $this->filtro_and(columnas: $columnas, columnas_en_bruto: $columnas_en_bruto,
            extra_join: $extra_join, filtro: $filtro, hijo: $hijo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros con codigo: ' . $codigo, data: $registros);
        }
        if ($registros->n_registros === 0) {
            return $this->error->error(mensaje: 'Error no existe registro con codigo: ' . $codigo, data: $registros);
        }
        if ($registros->n_registros > 1) {
            return $this->error->error(mensaje: 'Error existe mas de un registro con codigo: ' . $codigo,
                data: $registros);
        }

        $registro = $registros->registros[0];
        if ($retorno_obj) {
            $registro = (object)$registro;
        }
        return $registro;

    }

    /**
     * Obtiene un conjunto de rows basados en la descripcion
     * @param string $descripcion Descripcion
     * @return array|stdClass
     */
    final public function registro_by_descripcion(string $descripcion): array|stdClass
    {

        $key_descripcion = $this->tabla . '.descripcion';
        $filtro[$key_descripcion] = $descripcion;
        $result = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros con descripcion: ' . $descripcion,
                data: $result);
        }
        return $result;

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Esta es la función 'registros', que se utiliza para obtener los registros de una tabla.
     *
     * @param array $columnas Se utiliza para especificar las columnas que se desean obtener.
     * @param bool $columnas_en_bruto Se usa para determinar si se desea recuperar las columnas en su formato original.
     * @param bool $con_sq Indica si se quieren obtener las columnas que tienen una sub-consulta.
     * @param bool $aplica_seguridad Indica si se quiere aplicar las reglas de seguridad en la consulta.
     * @param int $limit Se utiliza para limitar el número de registros retornados.
     * @param array $order Se utiliza para ordenar los registros obtenidos.
     * @param bool $return_obj Indica si se requiere devolver un objeto en lugar de un array.
     *
     * @return array|stdClass Devuelve un array de registros o un objeto si $return_obj está establecido como 'true'.
     * @version 16.254.1
     */
    final public function registros(array $columnas = array(), bool $columnas_en_bruto = false, bool $con_sq = true, bool $aplica_seguridad = false, int $limit = 0, array $order = array(),
                                    bool  $return_obj = false): array|stdClass
    {

        $this->order = $order;
        $resultado = $this->obten_registros(aplica_seguridad: $aplica_seguridad, columnas: $columnas,
            columnas_en_bruto: $columnas_en_bruto, con_sq: $con_sq, limit: $limit);

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros activos en ' . $this->tabla, data: $resultado);
        }
        $this->registros = $resultado->registros;
        $registros = $resultado->registros;
        if ($return_obj) {
            $registros = $resultado->registros_obj;
        }

        return $registros;
    }

    /**
     * Obtiene los registros activos de un modelo de datos
     * @param array $columnas Columnas a integrar
     * @param bool $aplica_seguridad Si aplica seguridad obtiene datos permitidos
     * @param int $limit Limit de registros
     * @param bool $retorno_obj Retorna los rows encontrados en forma de objetos
     * @return array
     * @version 11.22.0
     */
    final public function registros_activos(array $columnas = array(), bool $aplica_seguridad = false,
                                            int   $limit = 0, bool $retorno_obj = false): array
    {
        $filtro[$this->tabla . '.status'] = 'activo';
        $resultado = $this->filtro_and(aplica_seguridad: $aplica_seguridad, columnas: $columnas, filtro: $filtro,
            limit: $limit);

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros', data: $resultado);
        }
        $this->registros = $resultado->registros;

        $result = $resultado->registros;
        if ($retorno_obj) {
            $result = $resultado->registros_obj;
        }

        return $result;
    }

    /**
     * Obtiene registros con permisos
     * @param array $columnas
     * @return array
     */
    public function registros_permitidos(array $columnas = array()): array
    {
        $registros = $this->registros(columnas: $columnas, aplica_seguridad: $this->aplica_seguridad);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros en ' . $this->tabla, data: $registros);
        }

        return $registros;
    }

    /**
     * Inicializa un elemento de salida para homolagar resultados
     * @return stdClass
     * @version 7.2.2
     */
    private function result_ini(): stdClass
    {
        $r_modelo = new stdClass();
        $r_modelo->n_registros = 0;
        $r_modelo->registros = array();
        $r_modelo->sql = '';
        $r_modelo->registros_obj = array();
        return $r_modelo;
    }

    final public function row_predeterminado(): array|stdClass
    {

        $r_modelo = $this->result_ini();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar result', data: $r_modelo);
        }


        $tiene_predeterminado = $this->tiene_predeterminado();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener si predeterminado', data: $tiene_predeterminado);
        }

        if ($tiene_predeterminado) {
            $r_modelo = $this->get_predeterminado();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener datos', data: $r_modelo);
            }

        }
        return $r_modelo;
    }

    /**
     * Obtiene el id de una seccion
     * @param string $seccion Seccion a obtener el id
     * @return array|int
     * @version 1.356.41
     */
    protected function seccion_menu_id(string $seccion): array|int
    {
        $seccion = trim($seccion);
        if ($seccion === '') {
            return $this->error->error(mensaje: 'Error seccion no puede venir vacio', data: $seccion);
        }
        $filtro['adm_seccion.descripcion'] = $seccion;
        $modelo_sm = new adm_seccion($this->link);

        $r_seccion_menu = $modelo_sm->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener seccion menu', data: $r_seccion_menu);
        }
        if ((int)$r_seccion_menu->n_registros === 0) {
            return $this->error->error(mensaje: 'Error al obtener seccion menu no existe', data: $r_seccion_menu);
        }

        $registros = $r_seccion_menu->registros[0];
        $seccion_menu_id = $registros['adm_seccion_id'];
        return (int)$seccion_menu_id;
    }

    /**
     * REG
     * Genera o actualiza una sentencia SQL con una condición OR basada en un campo y un valor proporcionados.
     *
     * @param string $campo Nombre del campo de la base de datos que se utilizará en la condición.
     * @param string $sentencia Sentencia SQL existente a la cual se añadirá la nueva condición OR.
     * @param string $value Valor que será comparado con el campo en la sentencia OR.
     *
     * @return string|array Retorna la sentencia SQL actualizada con la condición OR agregada.
     *                      En caso de error, devuelve un array con los detalles del problema.
     *
     * @throws errores Si ocurre algún problema, como que el campo esté vacío.
     *
     * @example Generar una sentencia OR desde cero:
     * ```php
     * $campo = 'nombre';
     * $sentencia = '';
     * $value = 'Juan';
     *
     * $resultado = $this->sentencia_or(campo: $campo, sentencia: $sentencia, value: $value);
     * // Resultado: " nombre = 'Juan' "
     * ```
     *
     * @example Añadir una condición OR a una sentencia existente:
     * ```php
     * $campo = 'apellido';
     * $sentencia = "nombre = 'Juan'";
     * $value = 'Pérez';
     *
     * $resultado = $this->sentencia_or(campo: $campo, sentencia: $sentencia, value: $value);
     * // Resultado: "nombre = 'Juan' OR apellido = 'Pérez'"
     * ```
     *
     * @example Manejo de error si el campo está vacío:
     * ```php
     * $campo = '';
     * $sentencia = "nombre = 'Juan'";
     * $value = 'Pérez';
     *
     * $resultado = $this->sentencia_or(campo: $campo, sentencia: $sentencia, value: $value);
     * // Resultado: Array con detalles del error, indicando que el campo está vacío.
     * ```
     */
    private function sentencia_or(string $campo, string $sentencia, string $value): string|array
    {
        $campo = trim($campo);
        if ($campo === '') {
            return $this->error->error(mensaje: 'Error el campo está vacío', data: $campo, es_final: true);
        }
        $or = '';
        if ($sentencia !== '') {
            $or = ' OR ';
        }
        $sentencia .= " $or $campo = '$value'";
        return $sentencia;
    }


    /**
     * REG
     * Calcula una suma total basada en los campos especificados y un conjunto opcional de filtros.
     *
     * Esta función genera una consulta SQL dinámica para calcular sumas totales de los campos indicados,
     * aplicando los filtros proporcionados. La consulta se ejecuta y devuelve los resultados procesados.
     *
     * @param array $campos Campos a sumar en la consulta SQL. Debe ser un array asociativo donde las claves
     *                      representan los alias y los valores los nombres de los campos. Ejemplo:
     *                      `['total_monto' => 'monto', 'total_iva' => 'iva']`.
     * @param array $filtro Opcional. Filtros para aplicar en la cláusula `WHERE`. Debe ser un array asociativo.
     *                      Ejemplo: `['estatus' => 'activo', 'fecha >=' => '2023-01-01']`.
     *
     * @return array Retorna un array con los resultados de la suma.
     *               Si ocurre algún error, devuelve un array de error estructurado.
     *
     * @throws array Si se presenta un error en la validación de parámetros, generación de columnas, filtros
     *                   o ejecución de la consulta SQL.
     *
     * ### Ejemplos de uso:
     *
     * 1. **Calcular sumas con filtros aplicados**:
     *    ```php
     *    $campos = [
     *        'total_monto' => 'monto',
     *        'total_iva' => 'iva'
     *    ];
     *    $filtros = [
     *        'estatus' => 'activo',
     *        'fecha >=' => '2023-01-01'
     *    ];
     *
     *    $resultado = $modelo->suma(campos: $campos, filtro: $filtros);
     *    // Resultado esperado:
     *    // [
     *    //     'total_monto' => 15000,
     *    //     'total_iva' => 2400
     *    // ]
     *    ```
     *
     * 2. **Calcular sumas sin filtros**:
     *    ```php
     *    $campos = [
     *        'total_monto' => 'monto',
     *        'total_iva' => 'iva'
     *    ];
     *
     *    $resultado = $modelo->suma(campos: $campos);
     *    // Resultado esperado:
     *    // [
     *    //     'total_monto' => 20000,
     *    //     'total_iva' => 3200
     *    // ]
     *    ```
     *
     * 3. **Caso de error por campos vacíos**:
     *    ```php
     *    $campos = [];
     *
     *    $resultado = $modelo->suma(campos: $campos);
     *    // Resultado esperado:
     *    // [
     *    //     'error' => 1,
     *    //     'mensaje' => 'Error campos no puede venir vacio',
     *    //     'data' => []
     *    // ]
     *    ```
     *
     * ### Descripción de la operación:
     * 1. **Validación de parámetros**:
     *    - Valida que `$campos` no esté vacío.
     *    - Genera las columnas SQL para las sumas mediante la clase `sumas`.
     * 2. **Generación de filtros**:
     *    - Utiliza la clase `where` para construir la cláusula `WHERE` basada en `$filtro`.
     * 3. **Construcción de la consulta**:
     *    - Genera la consulta SQL combinando columnas, tablas y filtros.
     * 4. **Ejecución de la consulta**:
     *    - Ejecuta la consulta SQL y devuelve el primer registro con los resultados de las sumas.
     *
     * ### Resultado esperado:
     * - Si todo es correcto: Devuelve un array con las sumas totales de los campos especificados.
     * - Si ocurre un error: Devuelve un array con detalles del error, incluyendo el mensaje y los datos relacionados.
     */

    final public function suma(array $campos, array $filtro = array()): array
    {
        $this->filtro = $filtro;
        if (count($campos) === 0) {
            return $this->error->error(mensaje: 'Error campos no puede venir vacio', data: $campos, es_final: true);
        }

        $columnas = (new sumas())->columnas_suma(campos: $campos);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar columnas', data: $columnas);
        }

        $filtro_sql = (new \gamboamartin\where\where())->genera_and(columnas_extra: $this->columnas_extra, filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar filtro', data: $filtro_sql);
        }

        $where = (new where())->where_suma(filtro_sql: $filtro_sql);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar where', data: $where);
        }

        $tabla = $this->tabla;
        $tablas = (new joins())->obten_tablas_completas(columnas_join: $this->columnas, tabla: $tabla);
        if (errores::$error) {
            return $this->error->error('Error al obtener tablas', $tablas);
        }

        $consulta = 'SELECT ' . $columnas . ' FROM ' . $tablas . $where;

        $resultado = $this->ejecuta_consulta(consulta: $consulta, campos_encriptados: $this->campos_encriptados);
        if (errores::$error) {
            return $this->error->error('Error al ejecutar sql', $resultado);
        }

        return $resultado->registros[0];
    }


    /**
     * 1.- Esta función recupera un registro de la base de datos usando el ID proporcionado. Si hay un error durante
     * este proceso, la función lo capturará y devolverá un mensaje de error.
     *
     * 2.- Luego, recupera el estado actual del campo proporcionado del registro recuperado.
     * Si este estado es 'activo', lo cambia a 'inactivo' y viceversa.
     *
     * 3.- Finalmente, actualiza el registro en la base de datos con el nuevo estado y retorna el resultado de la
     * actualización. Si hay algún error durante la actualización, la función captura el error y
     * devuelve un mensaje de error.
     *
     *
     *
     * @param string $campo Se refiere al nombre de la columna en la base de datos que tiene el estado actual
     *  del registro.
     * @param int $registro_id Se refiere al ID del registro en la base de datos.
     * @return array|stdClass Esta función devuelve un error o el resultado de la actualización del registro
     * en la base de datos, que podría ser array si es error o stdClass si es exito.
     */
    public function status(string $campo, int $registro_id): array|stdClass
    {
        if (!$this->aplica_transacciones_base) {
            return $this->error->error(mensaje: 'Error solo se puede transaccionar desde layout', data: $registro_id);
        }
        $registro = $this->registro(registro_id: $registro_id, columnas_en_bruto: true, retorno_obj: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro', data: $registro);
        }

        $status_actual = $registro->$campo;
        $status_nuevo = 'activo';

        if ($status_actual === 'activo') {
            $status_nuevo = 'inactivo';
        }

        $registro_upd[$campo] = $status_nuevo;

        $upd = $this->modifica_bd(registro: $registro_upd, id: $registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al actualizar registro', data: $upd);
        }

        return $upd;

    }

    final public function tiene_predeterminado(): bool
    {
        $tiene_predeterminado = false;
        if (in_array('predeterminado', $this->data_columnas->columnas_parseadas)) {
            $tiene_predeterminado = true;
        }
        return $tiene_predeterminado;
    }

    /**
     * Verifica una entidad tiene registros
     * @return array|bool
     * @version 9.115.4
     */
    final public function tiene_registros(): bool|array
    {
        $total_registros = $this->total_registros();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener total registros ' . $this->tabla, data: $total_registros);
        }
        $tiene_registros = false;
        if ($total_registros > 0) {
            $tiene_registros = true;
        }
        return $tiene_registros;
    }

    private function todos_campos_obligatorios()
    {
        $this->campos_obligatorios = $this->campos_tabla;
        $limpia = $this->unset_campos_obligatorios();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos obligatorios en ' . $this->tabla, data: $limpia);

        }
        return $limpia;
    }

    /**
     * Obtiene el total de registros de una entidad
     * @return array|int
     * @version 9.104.4
     */
    final public function total_registros(): array|int
    {
        $n_rows = $this->cuenta();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al contar registros', data: $n_rows);
        }
        return (int)$n_rows;
    }


    /**
     * PHPUNIT
     * @return array
     */
    public function ultimo_registro(): array
    {
        $this->order = array($this->tabla . '.id' => 'DESC');
        $this->limit = 1;
        $resultado = $this->obten_registros();
        if (errores::$error) {
            return $this->error->error('Error al obtener registros', $resultado);
        }

        if ((int)$resultado['n_registros'] === 0) {
            return array();
        }

        return $resultado['registros'][0];
    }

    /**
     * @return array|int
     */
    final public function ultimo_registro_id(): int|array
    {
        $this->order = array($this->tabla . '.id' => 'DESC');
        $this->limit = 1;
        $resultado = $this->obten_registros();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registros', data: $resultado);
        }

        if ((int)$resultado->n_registros === 0) {
            return 0;
        }
        return (int)$resultado->registros[0][$this->tabla . '_id'];
    }

    /**
     * @param int $n_registros
     * @return array
     */
    protected function ultimos_registros(int $n_registros): array
    {
        $this->order = array($this->tabla . '.id' => 'DESC');
        $this->limit = $n_registros;
        $resultado = $this->obten_registros();
        if (errores::$error) {
            return $this->error->error('Error al obtener registros', $resultado);
        }
        if ((int)$resultado['n_registros'] === 0) {
            $resultado['registros'] = array();
        }
        return $resultado['registros'];
    }

    private function unset_campos_obligatorios()
    {
        $unsets = array('fecha_alta', 'fecha_update', 'id', 'usuario_alta_id', 'usuario_update_id');

        $limpia = $this->limpia_campos_obligatorios(unsets: $unsets);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos obligatorios en ' . $this->tabla, data: $limpia);
        }
        return $limpia;
    }

    private function valida_atributos_criticos(array $atributos_criticos)
    {
        foreach ($atributos_criticos as $atributo_critico) {

            $existe_atributo_critico = $this->verifica_atributo_critico(atributo_critico: $atributo_critico);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al verificar atributo critico ', data: $existe_atributo_critico);

            }

            if (!$existe_atributo_critico) {
                return $this->error->error(mensaje: 'Error no existe en db el  atributo ' . $atributo_critico .
                    ' del modelo ' . $this->tabla, data: $this->atributos);
            }
        }
        return true;
    }

    /**
     * REG
     * Valida si un registro tiene dependencias antes de ser eliminado.
     *
     * Este método verifica si existen registros relacionados en la base de datos antes de proceder con la eliminación.
     * Se basa en un filtro (`$filtro_children`) que se usa para comprobar la existencia de registros en el modelo proporcionado.
     *
     * ### Flujo de validación:
     * 1. **Consulta de existencia:** Se ejecuta `$modelo->existe(filtro: $filtro_children)`, verificando si hay registros con ese filtro.
     * 2. **Manejo de errores:** Si ocurre un error en la verificación, se retorna un array con detalles del error.
     * 3. **Validación de dependencias:** Si existen registros relacionados, se retorna un error indicando que el registro tiene dependencias.
     * 4. **Retorno exitoso:** Si no hay dependencias encontradas, retorna `true`, indicando que la eliminación puede continuar.
     *
     * ---
     *
     * @param array  $filtro_children Filtro que se usará para verificar si existen registros relacionados.
     *                                - Debe ser un array asociativo con las claves y valores de filtrado.
     *                                - Ejemplo: `['usuarios.id' => 10]` busca si existe un usuario con ID 10.
     *
     * @param modelo $modelo Instancia del modelo en el cual se buscarán registros dependientes.
     *                       - Debe contener un método `existe(array $filtro): bool` que verifique la existencia de registros.
     *                       - Debe incluir una propiedad `$modelo->tabla` para indicar la tabla donde se busca la relación.
     *
     * @return bool|array Retorna `true` si no existen dependencias y el registro puede eliminarse.
     *                    Si se encuentran dependencias o ocurre un error, retorna un array con detalles del error.
     *
     * @example **Ejemplo 1: Eliminación permitida (sin dependencias)**
     * ```php
     * $filtro = ['usuarios.id' => 10];
     * $modelo = new modelo(); // Suponiendo que `modelo` es una instancia válida
     * $resultado = $this->valida_elimina_children($filtro, $modelo);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * @example **Ejemplo 2: Error por existencia de dependencias**
     * ```php
     * $filtro = ['productos.id' => 5];
     * $modelo = new modelo();
     * $resultado = $this->valida_elimina_children($filtro, $modelo);
     * print_r($resultado);
     * ```
     * **Salida esperada (si existen registros relacionados en `productos`):**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error el registro tiene dependencias asignadas en productos'
     *     [data] => true
     *     [es_final] => true
     * )
     * ```
     *
     * @example **Ejemplo 3: Error en la consulta de existencia**
     * ```php
     * $filtro = ['clientes.id' => 3];
     * $modelo = new modelo();
     * $resultado = $this->valida_elimina_children($filtro, $modelo);
     * print_r($resultado);
     * ```
     * **Salida esperada (si ocurre un error en la consulta de existencia):**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error al validar si existe'
     *     [data] => null
     * )
     * ```
     */
    private function valida_elimina_children(array $filtro_children, modelo $modelo): bool|array
    {
        $existe = $modelo->existe(filtro: $filtro_children);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar si existe', data: $existe);
        }
        if ($existe) {
            return $this->error->error(
                mensaje: 'Error el registro tiene dependencias asignadas en ' . $modelo->tabla, data: $existe,
                es_final: true);
        }
        return true;
    }

    /**
     * Valida si existe un elemento predeterminado previo a su alta
     * @return bool|array
     * @version 1.532.51
     */
    protected function valida_predetermiando(): bool|array
    {
        if (isset($this->registro['predeterminado']) && $this->registro['predeterminado'] === 'activo') {
            $existe = $this->existe_predeterminado();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al verificar si existe', data: $existe);
            }
            if ($existe) {
                return $this->error->error(mensaje: 'Error ya existe elemento predeterminado', data: $this->registro);
            }
        }
        return true;
    }

    /**
     * REG
     * Valida si un registro puede ser eliminado verificando que no tenga dependencias en modelos hijos.
     *
     * Este método revisa si el registro con el ID proporcionado tiene relaciones en modelos hijos especificados
     * en `$this->childrens`. Para cada modelo hijo, se ejecuta `verifica_eliminacion_children`, asegurando
     * que el registro no tenga dependencias antes de permitir su eliminación.
     *
     * ### Flujo del método:
     * 1. **Validación del ID:** Se verifica que `$id` sea un número positivo mayor a 0.
     * 2. **Iteración sobre modelos hijos:** Se recorren las relaciones en `$this->childrens`, verificando en cada modelo si el registro tiene dependencias.
     * 3. **Llamada a `verifica_eliminacion_children`:** Para cada modelo hijo, se invoca `verifica_eliminacion_children(id, modelo_children, namespace)`.
     * 4. **Manejo de errores:** Si alguna validación falla, se retorna un array con detalles del error.
     * 5. **Retorno exitoso:** Si ninguna validación falla, retorna `true`, indicando que el registro puede eliminarse.
     *
     * ---
     *
     * @param int $id Identificador del registro que se desea eliminar.
     *                - Debe ser un número entero mayor a 0.
     *
     * @return bool|array Retorna `true` si el registro puede eliminarse sin problemas.
     *                    Si existen dependencias o errores, retorna un array con detalles del error.
     *
     * @example **Ejemplo 1: Eliminación permitida (sin dependencias)**
     * ```php
     * $id = 7;
     * $resultado = $this->valida_eliminacion_children($id);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * @example **Ejemplo 2: Error por ID inválido**
     * ```php
     * $id = 0;
     * $resultado = $this->valida_eliminacion_children($id);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error $id debe ser mayor a 0'
     *     [data] => 0
     *     [es_final] => true
     * )
     * ```
     *
     * @example **Ejemplo 3: Error por dependencias encontradas en un modelo hijo**
     * ```php
     * $id = 10;
     * $this->childrens = [
     *     'facturas' => 'gamboamartin\\facturacion\\models',
     *     'pagos' => 'gamboamartin\\pagos\\models'
     * ];
     *
     * $resultado = $this->valida_eliminacion_children($id);
     * print_r($resultado);
     * ```
     * **Salida esperada si `facturas` tiene registros relacionados:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error al validar children'
     *     [data] => Array
     *         (
     *             [error] => 1
     *             [mensaje] => 'Error el registro tiene dependencias asignadas en facturas'
     *             [data] => true
     *             [es_final] => true
     *         )
     * )
     * ```
     */
    private function valida_eliminacion_children(int $id): bool|array
    {
        if($id <= 0){
            return $this->error->error(mensaje:'Error $id debe ser mayor a 0', data:$id, es_final: true);
        }

        foreach ($this->childrens as $modelo_children => $namespace) {
            $valida = $this->verifica_eliminacion_children(id: $id, modelo_children: $modelo_children,
                namespace: $namespace);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar children', data: $valida);
            }
        }
        return true;
    }

    private function verifica_atributo_critico(string $atributo_critico)
    {
        $existe_atributo_critico = false;

        foreach ($this->atributos as $key_attr => $atributo) {
            $existe_atributo_critico = $this->existe_atributo_critico(atributo_critico: $atributo_critico, key_attr: $key_attr);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener atributo critico ', data: $existe_atributo_critico);
            }
            if ($existe_atributo_critico) {
                break;
            }
        }
        return $existe_atributo_critico;
    }

    /**
     * REG
     * Verifica si un registro puede ser eliminado validando que no tenga dependencias en modelos hijos.
     *
     * Este método se encarga de comprobar si un registro en la base de datos tiene relaciones dependientes
     * en una tabla secundaria antes de permitir su eliminación. Para ello, genera un modelo hijo con el
     * namespace indicado, construye un filtro para buscar dependencias y valida si existen registros asociados.
     *
     * ### Flujo del método:
     * 1. **Validación inicial:** Verifica que `$modelo_children` no esté vacío y que `$id` sea un número positivo.
     * 2. **Generación del modelo hijo:** Crea una instancia del modelo hijo utilizando el namespace proporcionado.
     * 3. **Generación del filtro:** Construye el filtro de búsqueda utilizando la tabla base y el ID.
     * 4. **Validación de dependencias:** Verifica si existen registros relacionados en el modelo hijo.
     * 5. **Retorno del resultado:** Si no hay dependencias, retorna `true`. Si hay dependencias o errores, retorna un array con detalles del error.
     *
     * ---
     *
     * @param int    $id Identificador del registro que se desea eliminar.
     *                   - Debe ser un número entero mayor a 0.
     *
     * @param string $modelo_children Nombre del modelo hijo en el que se buscarán dependencias.
     *                                - Debe ser una cadena no vacía.
     *                                - Debe corresponder a un modelo válido dentro del sistema.
     *
     * @param string $namespace Namespace donde se encuentra el modelo hijo.
     *                          - Debe ser una cadena válida que incluya el espacio de nombres del modelo.
     *
     * @return bool|array Retorna `true` si el registro puede ser eliminado sin problemas.
     *                    Si existen dependencias o errores, retorna un array con detalles del error.
     *
     * @example **Ejemplo 1: Eliminación permitida (sin dependencias)**
     * ```php
     * $id = 5;
     * $modelo_children = 'facturas';
     * $namespace = 'gamboamartin\\facturacion\\models';
     *
     * $resultado = $this->verifica_eliminacion_children($id, $modelo_children, $namespace);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * @example **Ejemplo 2: Error por modelo vacío**
     * ```php
     * $id = 5;
     * $modelo_children = '';
     * $namespace = 'gamboamartin\\facturacion\\models';
     *
     * $resultado = $this->verifica_eliminacion_children($id, $modelo_children, $namespace);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error $modelo_children esta vacio'
     *     [data] => ''
     * )
     * ```
     *
     * @example **Ejemplo 3: Error por ID no válido**
     * ```php
     * $id = 0;
     * $modelo_children = 'facturas';
     * $namespace = 'gamboamartin\\facturacion\\models';
     *
     * $resultado = $this->verifica_eliminacion_children($id, $modelo_children, $namespace);
     * print_r($resultado);
     * ```
     * **Salida esperada:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error $id debe ser mayor a 0'
     *     [data] => 0
     *     [es_final] => true
     * )
     * ```
     *
     * @example **Ejemplo 4: Error al generar modelo**
     * ```php
     * $id = 10;
     * $modelo_children = 'facturas';
     * $namespace = 'namespace_invalido\\models';
     *
     * $resultado = $this->verifica_eliminacion_children($id, $modelo_children, $namespace);
     * print_r($resultado);
     * ```
     * **Salida esperada si el namespace es incorrecto:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error al generar modelo'
     *     [data] => null
     * )
     * ```
     *
     * @example **Ejemplo 5: Error por dependencias encontradas**
     * ```php
     * $id = 3;
     * $modelo_children = 'facturas';
     * $namespace = 'gamboamartin\\facturacion\\models';
     *
     * $resultado = $this->verifica_eliminacion_children($id, $modelo_children, $namespace);
     * print_r($resultado);
     * ```
     * **Salida esperada si existen facturas asociadas al registro:**
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error el registro tiene dependencias asignadas en facturas'
     *     [data] => true
     *     [es_final] => true
     * )
     * ```
     */
    private function verifica_eliminacion_children(int $id, string $modelo_children, string $namespace): bool|array
    {
        $modelo_children = trim($modelo_children);
        if($modelo_children === ''){
            return $this->error->error(mensaje: 'Error $modelo_children esta vacio', data: $modelo_children);
        }
        if($id <= 0){
            return $this->error->error(mensaje:'Error $id debe ser mayor a 0', data:$id, es_final: true);
        }
        $modelo = $this->genera_modelo(modelo: $modelo_children, namespace_model: $namespace);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar modelo', data: $modelo);
        }

        $filtro_children = (new filtros())->filtro_children(tabla: $this->tabla, id: $id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar filtro', data: $filtro_children);
        }

        $valida = $this->valida_elimina_children(filtro_children: $filtro_children, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar children', data: $valida);
        }

        return $valida;
    }


}
