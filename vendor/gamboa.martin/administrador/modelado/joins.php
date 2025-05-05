<?php
namespace gamboamartin\administrador\modelado;

use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

/**
 * PRUEBAS FINALIZADAS FUNCIONES REVISADAS FINAL
 */
class joins{

    public errores $error;
    public validacion $validacion;

    public function __construct(){
        $this->error = new errores();
        $this->validacion = new validacion();
    }

    /**
     * REG
     * Ajusta el nombre del modelo basado en una tabla dada, formateando la cadena
     * y generando el nombre completo de la clase para el modelo.
     *
     * @param string $tabla Nombre de la tabla que se desea ajustar y convertir en el nombre del modelo.
     *                      Este parámetro no puede estar vacío.
     *
     * @return stdClass|array Retorna un objeto `stdClass` con dos propiedades:
     *                        - `tabla`: El nombre ajustado de la tabla.
     *                        - `name_model`: El nombre completo de la clase modelo (incluyendo su namespace).
     *                        En caso de error, retorna un array con los detalles del mismo.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $tabla = 'usuarios';
     *
     * $resultado = $miClase->ajusta_name_model($tabla);
     *
     * print_r($resultado);
     * // Resultado esperado:
     * // stdClass Object
     * // (
     * //     [tabla] => usuarios
     * //     [name_model] => models\usuarios
     * // )
     * ```
     *
     * ### Ejemplo de error:
     *
     * - Caso: La tabla viene como una cadena vacía.
     *
     * ```php
     * $tabla = '';
     *
     * $resultado = $miClase->ajusta_name_model($tabla);
     *
     * print_r($resultado);
     * // Resultado esperado:
     * // Array
     * // (
     * //     [error] => 1
     * //     [mensaje] => Error tabla no puede venir vacia
     * //     [data] =>
     * // )
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$tabla`**:
     *   Nombre de la tabla a procesar. Este valor será ajustado y usado para generar
     *   el nombre del modelo.
     *   Ejemplo válido: `'usuarios'`.
     *   Ejemplo inválido: `''` (cadena vacía).
     *
     * ### Resultado esperado:
     *
     * - **Éxito**:
     *   Retorna un objeto `stdClass` con las siguientes claves:
     *   - `tabla`: Nombre ajustado de la tabla (se eliminan referencias a `models\\`).
     *   - `name_model`: Nombre completo del modelo con el namespace prefijado (`models\\`).
     *
     * - **Error**:
     *   Si la tabla está vacía, retorna un array con los detalles del error, incluyendo
     *   el mensaje descriptivo y los datos proporcionados.
     *
     * ### Notas adicionales:
     *
     * - Esta función asegura que el nombre del modelo siempre tenga el prefijo `models\\`.
     * - Es útil para casos en los que se necesita mapear dinámicamente nombres de tablas
     *   a nombres de clases modelo en un contexto estructurado.
     */

    private function ajusta_name_model(string $tabla): stdClass|array
    {
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error tabla no puede venir vacia', data: $tabla, es_final: true);
        }

        $tabla = str_replace('models\\','',$tabla);
        $class = 'models\\'.$tabla;

        $data = new stdClass();
        $data->tabla = $tabla;
        $data->name_model = $class;
        return $data;
    }

    /**
     * REG
     * Ajusta los nombres de los modelos para dos tablas específicas, generando los nombres
     * completos de las clases de modelo para ambas tablas.
     *
     * @param string $tabla Nombre de la tabla base que se desea ajustar y convertir en el nombre del modelo.
     *                      Este parámetro no puede estar vacío.
     * @param string $tabla_enlace Nombre de la tabla de enlace que se desea ajustar y convertir en el nombre del modelo.
     *                             Este parámetro no puede estar vacío.
     *
     * @return stdClass|array Retorna un objeto `stdClass` con las siguientes propiedades:
     *                        - `tabla`: Objeto `stdClass` resultante de ajustar el nombre del modelo para `$tabla`.
     *                        - `tabla_enlace`: Objeto `stdClass` resultante de ajustar el nombre del modelo para `$tabla_enlace`.
     *                        En caso de error, retorna un array con los detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles_usuarios';
     *
     * $resultado = $miClase->ajusta_name_models($tabla, $tabla_enlace);
     *
     * print_r($resultado);
     * // Resultado esperado:
     * // stdClass Object
     * // (
     * //     [tabla] => stdClass Object
     * //         (
     * //             [tabla] => usuarios
     * //             [name_model] => models\usuarios
     * //         )
     * //
     * //     [tabla_enlace] => stdClass Object
     * //         (
     * //             [tabla] => roles_usuarios
     * //             [name_model] => models\roles_usuarios
     * //         )
     * // )
     * ```
     *
     * ### Ejemplo de error:
     *
     * - Caso: Uno de los nombres de las tablas está vacío.
     *
     * ```php
     * $tabla = '';
     * $tabla_enlace = 'roles_usuarios';
     *
     * $resultado = $miClase->ajusta_name_models($tabla, $tabla_enlace);
     *
     * print_r($resultado);
     * // Resultado esperado:
     * // Array
     * // (
     * //     [error] => 1
     * //     [mensaje] => Error tabla no puede venir vacia
     * //     [data] =>
     * // )
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$tabla`**:
     *   Nombre de la tabla base. Este valor será ajustado para generar el nombre del modelo.
     *   Ejemplo válido: `'usuarios'`.
     *   Ejemplo inválido: `''` (cadena vacía).
     *
     * - **`$tabla_enlace`**:
     *   Nombre de la tabla de enlace. Este valor será ajustado para generar el nombre del modelo.
     *   Ejemplo válido: `'roles_usuarios'`.
     *   Ejemplo inválido: `''` (cadena vacía).
     *
     * ### Resultado esperado:
     *
     * - **Éxito**:
     *   Retorna un objeto `stdClass` con las siguientes claves:
     *   - `tabla`: Resultado de ajustar el nombre del modelo para `$tabla`.
     *   - `tabla_enlace`: Resultado de ajustar el nombre del modelo para `$tabla_enlace`.
     *
     * - **Error**:
     *   Si alguno de los parámetros está vacío, retorna un array con los detalles del error, incluyendo
     *   el mensaje descriptivo y los datos proporcionados.
     *
     * ### Notas adicionales:
     *
     * - Esta función es útil para mapear dinámicamente los nombres de tablas a nombres de modelos en un entorno estructurado.
     * - Utiliza la función interna `ajusta_name_model` para realizar los ajustes individuales de cada tabla.
     */

    private function ajusta_name_models(string $tabla, string $tabla_enlace): array|stdClass
    {
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error tabla no puede venir vacia', data: $tabla,es_final: true);
        }
        $tabla_enlace = trim($tabla_enlace);
        if($tabla_enlace === ''){
            return $this->error->error(mensaje: 'Error $tabla_enlace no puede venir vacia', data: $tabla_enlace,
                es_final: true);
        }

        $data_model_tabla = $this->ajusta_name_model(tabla: $tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al ajustar nombre del modelo', data: $data_model_tabla);
        }

        $data_model_tabla_enl = $this->ajusta_name_model(tabla:$tabla_enlace);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al ajustar nombre del modelo', data: $data_model_tabla_enl);
        }

        $data = new stdClass();
        $data->tabla = $data_model_tabla;
        $data->tabla_enlace = $data_model_tabla_enl;
        return $data;
    }

    /**
     * REG
<<<<<<< HEAD
     * Ajusta las tablas en una consulta SQL, generando los JOINs necesarios basados en las configuraciones dadas.
     *
     * @param string $tablas Cadena acumulativa de las tablas y sus respectivos JOINs ya generados.
     *                       - Puede comenzar como una cadena vacía si no hay tablas previas.
     * @param array $tablas_join Arreglo que define las relaciones entre tablas para construir los JOINs.
     *                           - La clave del arreglo debe ser el nombre de la tabla base.
     *                           - El valor puede ser:
     *                             - Una cadena que representa la tabla de unión.
     *                             - Un arreglo con información detallada para construir el JOIN.
     *
     * @return array|string Devuelve una cadena con los JOINs generados o un array de error si ocurre un fallo.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * // Caso con $tablas_join como array de detalles
     * $tablas = '';
     * $tablas_join = [
     *     'usuarios' => [
     *         'tabla_base' => 'usuarios',
     *         'tabla_enlace' => 'roles',
     *         'campo_tabla_base_id' => 'id',
     *         'campo_renombrado' => 'roles_id',
     *     ],
     *     'roles' => 'permisos'
     * ];
     *
     * $resultado = $this->ajusta_tablas($tablas, $tablas_join);
     * echo $resultado;
     * // Resultado esperado:
     * // ' LEFT JOIN roles ON usuarios.id = roles.roles_id LEFT JOIN permisos ON roles.id = permisos.roles_id'
     * ```
     *
     * ### Proceso de la función:
     * 1. **Inicialización de `$tablas_env`:**
     *    - Se asigna el valor inicial de `$tablas` a `$tablas_env`.
     * 2. **Iteración sobre `$tablas_join`:**
     *    - Recorre cada elemento del arreglo `$tablas_join`, donde:
     *      - La clave (`$key`) representa la tabla base.
     *      - El valor (`$tabla_join`) contiene los detalles del JOIN.
     *    - Llama a la función `data_tabla_sql` para generar el SQL de las tablas y sus JOINs.
     * 3. **Validación de errores:**
     *    - Verifica si la ejecución de `data_tabla_sql` produce errores y los maneja adecuadamente.
     * 4. **Acumulación de resultados:**
     *    - Concatena los JOINs generados en `$tablas_env`.
     * 5. **Retorno del resultado:**
     *    - Devuelve la cadena SQL acumulativa con los JOINs generados.
     *
     * ### Ejemplo de errores:
     * **Error por `$tablas_join` mal estructurado:**
     * ```php
     * $tablas = '';
     * $tablas_join = [
     *     'usuarios' => [
     *         // Falta la clave 'tabla_base'
     *         'tabla_enlace' => 'roles',
     *         'campo_tabla_base_id' => 'id',
     *         'campo_renombrado' => 'roles_id',
     *     ],
     * ];
     *
     * $resultado = $this->ajusta_tablas($tablas, $tablas_join);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error al generar data join',
     * //     'data' => [
     * //         'error' => 1,
     * //         'mensaje' => 'Error al validar $tabla_join',
     * //         'data' => [...]
     * //     ]
     * // ]
     * ```
     *
     * **Error por `$key` vacío:**
     * ```php
     * $tablas = '';
     * $tablas_join = [
     *     '' => 'roles',
     * ];
     *
     * $resultado = $this->ajusta_tablas($tablas, $tablas_join);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error al generar data join',
     * //     'data' => [
     * //         'error' => 1,
     * //         'mensaje' => 'Error el key no puede ser un numero',
     * //         'data' => ''
     * //     ]
     * // ]
     * ```
     *
     * ### Casos de uso:
     * - Construcción dinámica de consultas SQL que involucren múltiples tablas y relaciones.
     * - Uso en sistemas con esquemas complejos de bases de datos y JOINs condicionales.
     *
     * ### Consideraciones:
     * - El arreglo `$tablas_join` debe estar correctamente estructurado para evitar errores.
     * - La función depende de `data_tabla_sql` para generar el SQL de las relaciones entre tablas.
=======
     * Ajusta y construye una cadena SQL de sentencias `JOIN` a partir de un conjunto de tablas base y relaciones.
     *
     * La función procesa un array de relaciones de tablas (`$tablas_join`) y genera una cadena completa de sentencias
     * `JOIN` que se concatenan con las tablas previamente definidas en `$tablas`.
     *
     * @param string $tablas Cadena de sentencias SQL previamente generadas para las tablas.
     *                       Puede ser una cadena vacía si no se tienen sentencias iniciales.
     *                       Ejemplo: `'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id'`.
     * @param array $tablas_join Array asociativo que define las relaciones entre las tablas.
     *                           La clave del array es el nombre de la tabla base, y el valor es:
     *                           - Un array con los detalles de la relación.
     *                           - Una cadena con el nombre de la tabla a enlazar.
     *                           Ejemplo:
     *                           ```php
     *                           [
     *                               'usuarios' => [
     *                                   'tabla_base' => 'usuarios',
     *                                   'tabla_enlace' => 'roles'
     *                               ],
     *                               'roles' => 'permisos'
     *                           ]
     *                           ```
     *
     * @return array|string Devuelve la cadena completa de sentencias SQL `JOIN` ajustadas con las relaciones especificadas.
     *                      Si ocurre un error, devuelve un array con el mensaje y los detalles del problema.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $tablas = 'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id';
     * $tablas_join = [
     *     'usuarios' => [
     *         'tabla_base' => 'usuarios',
     *         'tabla_enlace' => 'roles'
     *     ],
     *     'roles' => 'permisos'
     * ];
     *
     * $resultado = $this->ajusta_tablas(tablas: $tablas, tablas_join: $tablas_join);
     *
     * // Resultado esperado:
     * // 'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id
     * //  LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id
     * //  LEFT JOIN roles AS roles ON roles.id = permisos.roles_id'
     * ```
     *
     * ### Casos de uso:
     *
     * 1. **Construcción desde cero**:
     *    ```php
     *    $tablas = '';
     *    $tablas_join = [
     *        'usuarios' => [
     *            'tabla_base' => 'usuarios',
     *            'tabla_enlace' => 'roles'
     *        ]
     *    ];
     *
     *    $resultado = $this->ajusta_tablas(tablas: $tablas, tablas_join: $tablas_join);
     *
     *    // Resultado esperado:
     *    // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     *    ```
     *
     * 2. **Concatenación con sentencias previas**:
     *    ```php
     *    $tablas = 'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id';
     *    $tablas_join = [
     *        'usuarios' => [
     *            'tabla_base' => 'usuarios',
     *            'tabla_enlace' => 'roles'
     *        ]
     *    ];
     *
     *    $resultado = $this->ajusta_tablas(tablas: $tablas, tablas_join: $tablas_join);
     *
     *    // Resultado esperado:
     *    // 'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id
     *    //  LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     *    ```
     *
     * ### Validaciones:
     *
     * - Se asegura que `$tablas_join` esté correctamente definido y que cada relación tenga los datos necesarios.
     * - Valida que las claves del array `$tablas_join` (nombres de tablas) no sean numéricas ni estén vacías.
     * - Gestiona errores mediante la función auxiliar `data_tabla_sql`.
     *
     * ### Casos de error:
     *
     * 1. **`$tablas_join` mal estructurado**:
     *    ```php
     *    $tablas = '';
     *    $tablas_join = [
     *        'usuarios' => 'roles', // Correcto.
     *        123 => 'permisos'     // Incorrecto (clave numérica).
     *    ];
     *
     *    $resultado = $this->ajusta_tablas(tablas: $tablas, tablas_join: $tablas_join);
     *    // Resultado esperado: array con mensaje de error "Error al validar $tabla_join".
     *    ```
     *
     * 2. **Errores en `data_tabla_sql`**:
     *    Si ocurre un error durante el procesamiento de una relación, el flujo se interrumpe y se retorna el error.
     *
     * ### Resultado esperado:
     * - Devuelve una cadena concatenada de sentencias SQL `JOIN`.
     * - Si ocurre un error, devuelve un array con el mensaje descriptivo y los datos del problema.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function ajusta_tablas( string $tablas, array $tablas_join): array|string
    {
        $tablas_env = $tablas;
        foreach ($tablas_join as $key=>$tabla_join){
            $tablas_env = $this->data_tabla_sql(key: $key, tabla_join: $tabla_join,tablas:  $tablas);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar data join',data:  $tablas_env);
            }
            $tablas = (string)$tablas_env;
        }
        return $tablas_env;
    }

    /**
     * REG
     * Procesa y valida los datos necesarios para construir una estructura de unión (join) en una consulta SQL.
     *
     * Esta función toma un array que describe los parámetros de una unión y lo valida. También normaliza valores
     * faltantes en los datos de entrada, retornando un objeto con la estructura lista para usar.
     *
     * @param array $tabla_join Array asociativo con los datos de la unión. Debe incluir las claves:
     *                          - `tabla_base` (string): Nombre de la tabla principal.
     *                          - `tabla_enlace` (string): Nombre de la tabla que se enlaza.
     *                          Opcionalmente puede incluir:
     *                          - `tabla_renombrada` (string): Alias para renombrar la tabla enlazada.
     *                          - `campo_tabla_base_id` (string): Nombre del campo en la tabla base que actúa como ID.
     *                          - `campo_renombrado` (string): Alias del campo renombrado en la unión.
     *
     * @return stdClass|array Retorna un objeto `stdClass` con los datos procesados de la unión si todo es correcto.
     *                        En caso de error, retorna un array con detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $tabla_join = [
     *     'tabla_base' => 'usuarios',
     *     'tabla_enlace' => 'roles',
     *     'tabla_renombrada' => 'roles_usuario',
     *     'campo_tabla_base_id' => 'usuario_id',
     *     'campo_renombrado' => 'id_rol'
     * ];
     *
     * $resultado = $miClase->data_join($tabla_join);
     *
     * print_r($resultado);
     * // Resultado esperado:
     * // stdClass Object
     * // (
     * //     [tabla_base] => usuarios
     * //     [tabla_enlace] => roles
     * //     [tabla_renombre] => roles_usuario
     * //     [campo_renombrado] => id_rol
     * //     [campo_tabla_base_id] => usuario_id
     * // )
     * ```
     *
     * ### Ejemplo de error:
     *
     * - Caso: Faltan claves requeridas en `$tabla_join`.
     *
     * ```php
     * $tabla_join = [
     *     'tabla_enlace' => 'roles'
     * ];
     *
     * $resultado = $miClase->data_join($tabla_join);
     *
     * print_r($resultado);
     * // Resultado esperado:
     * // Array
     * // (
     * //     [error] => 1
     * //     [mensaje] => Error al validar $tabla_join
     * //     [data] => Array
     * //         (
     * //             [error] => 1
     * //             [mensaje] => Falta la clave requerida 'tabla_base'
     * //         )
     * // )
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$tabla_join['tabla_base']`**:
     *   Tabla principal sobre la que se realizará la unión. Este parámetro es obligatorio.
     *   Ejemplo: `'usuarios'`.
     *
     * - **`$tabla_join['tabla_enlace']`**:
     *   Tabla que se enlaza con la tabla base. Este parámetro es obligatorio.
     *   Ejemplo: `'roles'`.
     *
     * - **`$tabla_join['tabla_renombrada']`**:
     *   Alias opcional para la tabla enlazada. Valor por defecto: `''`.
     *   Ejemplo: `'roles_usuario'`.
     *
     * - **`$tabla_join['campo_tabla_base_id']`**:
     *   Campo ID en la tabla base utilizado en la unión. Este parámetro es opcional.
     *   Ejemplo: `'usuario_id'`.
     *
     * - **`$tabla_join['campo_renombrado']`**:
     *   Alias opcional para el campo renombrado en la unión. Valor por defecto: `''`.
     *   Ejemplo: `'id_rol'`.
     *
     * ### Resultado esperado:
     *
     * - **Éxito**:
     *   Un objeto `stdClass` con las claves procesadas:
     *   - `tabla_base`: Nombre de la tabla base.
     *   - `tabla_enlace`: Nombre de la tabla enlazada.
     *   - `tabla_renombre`: Alias de la tabla enlazada.
     *   - `campo_tabla_base_id`: Campo ID de la tabla base.
     *   - `campo_renombrado`: Alias del campo renombrado.
     *
     * - **Error**:
     *   Un array con detalles del error si no se cumplen las validaciones.
     */

    private function data_join(array $tabla_join): stdClass|array
    {
        $keys = array('tabla_base','tabla_enlace');
        $valida = $this->validacion->valida_existencia_keys(keys:$keys, registro: $tabla_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar $tabla_join',data: $valida);
        }

        if(!isset($tabla_join['tabla_renombrada'])){
            $tabla_join['tabla_renombrada'] = '';
        }
        $data = new stdClass();
        $data->tabla_base = $tabla_join['tabla_base'];
        $data->tabla_enlace = $tabla_join['tabla_enlace'];
        $data->tabla_renombre = $tabla_join['tabla_renombrada'];
        $data->campo_renombrado = '';
        $data->campo_tabla_base_id  = '';

        if(isset($tabla_join['campo_tabla_base_id'])) {
            $data->campo_tabla_base_id = $tabla_join['campo_tabla_base_id'];
        }
        if(isset($tabla_join['campo_renombrado'])){
            $data->campo_renombrado = $tabla_join['campo_renombrado'];
        }

        return $data;

    }

    /**
     * REG
<<<<<<< HEAD
     * Genera la estructura de datos necesaria para renombrar una tabla en una consulta SQL,
     * incluyendo instrucciones JOIN y asignaciones con alias.
     *
     * @param string $id_renombrada Identificador de la tabla base. Por ejemplo, puede ser `".id"` o `".campo_id"`.
     * @param stdClass $init Objeto con información inicial sobre las tablas a utilizar en la construcción.
     *                       Debe contener las claves:
     *                       - `tabla`: Nombre de la tabla principal.
     *                       - `tabla_enlace`: Nombre de la tabla de enlace.
     * @param string $join Tipo de JOIN a utilizar en la consulta (por ejemplo: `INNER`, `LEFT`, `RIGHT`).
     * @param string $renombrada Alias o nuevo nombre que se asignará a la tabla en la consulta.
     *
     * @return stdClass|array Devuelve un objeto con los siguientes atributos:
     *                        - `join_tabla`: Instrucción JOIN para la tabla.
     *                        - `on_join`: Condición ON del JOIN.
     *                        - `asignacion_tabla`: Alias asignado a la tabla.
     *                        En caso de error, devuelve un array con detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * // Datos iniciales
     * $id_renombrada = '.id';
     * $init = new stdClass();
     * $init->tabla = 'usuarios';
     * $init->tabla_enlace = 'roles.id_usuario';
     * $join = 'INNER';
     * $renombrada = 'u';
     *
     * // Llamada a la función
=======
     * Genera los datos necesarios para renombrar y establecer las relaciones entre tablas en una consulta SQL.
     *
     * Esta función construye los componentes de un `JOIN` en una consulta SQL, incluyendo el tipo de `JOIN`,
     * las condiciones de unión y el alias para la tabla renombrada. Valida los datos de entrada antes de generar el resultado.
     *
     * @param string $id_renombrada Identificador renombrado que se usará en la condición del `JOIN`.
     *                              Usualmente incluye el prefijo de la tabla seguido de un identificador (`.id`).
     * @param stdClass $init Objeto que contiene las propiedades necesarias para construir los datos de la relación.
     *                       Debe incluir las claves `tabla` y `tabla_enlace`.
     * @param string $join Tipo de `JOIN` a utilizar (por ejemplo: `INNER`, `LEFT`, `RIGHT`).
     * @param string $renombrada Alias para la tabla renombrada que se usará en la consulta SQL.
     *
     * @return stdClass|array Retorna un objeto con las siguientes propiedades en caso de éxito:
     *                        - `join_tabla`: Sentencia del `JOIN` con el tipo y la tabla base.
     *                        - `on_join`: Condición para la unión entre tablas.
     *                        - `asignacion_tabla`: Alias para la tabla renombrada.
     *                        Si ocurre un error, retorna un array con los detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $id_renombrada = '.id';
     * $init = new stdClass();
     * $init->tabla = 'usuarios';
     * $init->tabla_enlace = 'roles.usuario_id';
     * $join = 'INNER';
     * $renombrada = 'usuarios_renombrados';
     *
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     * $resultado = $this->data_for_rename(
     *     id_renombrada: $id_renombrada,
     *     init: $init,
     *     join: $join,
     *     renombrada: $renombrada
     * );
     *
<<<<<<< HEAD
     * // Salida esperada
     * // $resultado->join_tabla: "INNER JOIN usuarios"
     * // $resultado->on_join: "u.id = roles.id_usuario"
     * // $resultado->asignacion_tabla: "INNER JOIN usuarios AS u"
     * ```
     *
     * ### Detalles de validación:
     * - La función valida que `$init` contenga las claves `tabla` y `tabla_enlace`.
     * - Si algún valor requerido está vacío, devuelve un error con detalles.
     *
     * ### Ejemplo de integración:
     * ```php
     * class joins {
     *     private function data_for_rename(string $id_renombrada, stdClass $init, string $join, string $renombrada): stdClass|array {
     *         $keys = array('tabla', 'tabla_enlace');
     *         $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $init);
     *         if (errores::$error) {
     *             return $this->error->error(mensaje: 'Error al validar $init', data: $valida);
     *         }
     *
     *         $join_tabla = $join . ' JOIN ' . $init->tabla;
     *         $on_join = $renombrada . $id_renombrada . ' = ' . $init->tabla_enlace;
     *         $asignacion_tabla = $join_tabla . ' AS ' . $renombrada;
     *
     *         $data = new stdClass();
     *         $data->join_tabla = $join_tabla;
     *         $data->on_join = $on_join;
     *         $data->asignacion_tabla = $asignacion_tabla;
     *         return $data;
     *     }
     * }
     * ```
     *
     * ### Consideraciones:
     * - Asegúrate de que los valores de `$join` sean válidos (`INNER`, `LEFT`, `RIGHT`) antes de llamarla.
     * - Esta función se puede usar para construir consultas dinámicas y mantener consistencia en alias y relaciones entre tablas.
=======
     * // Resultado esperado:
     * // $resultado->join_tabla => 'INNER JOIN usuarios'
     * // $resultado->on_join => 'usuarios_renombrados.id = roles.usuario_id'
     * // $resultado->asignacion_tabla => 'INNER JOIN usuarios AS usuarios_renombrados'
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$id_renombrada`**:
     *   Representa el identificador renombrado que se usará en la condición `ON` del `JOIN`.
     *   Ejemplo: `'.id'`.
     *
     * - **`$init`**:
     *   Objeto que debe incluir las claves:
     *     - `tabla`: Nombre de la tabla principal. Ejemplo: `'usuarios'`.
     *     - `tabla_enlace`: Campo que define la relación con la tabla principal. Ejemplo: `'roles.usuario_id'`.
     *
     * - **`$join`**:
     *   Tipo de unión a utilizar en la consulta SQL. Debe ser uno de los valores válidos como `INNER`, `LEFT` o `RIGHT`.
     *   Ejemplo: `'LEFT'`.
     *
     * - **`$renombrada`**:
     *   Alias que se asignará a la tabla principal en la consulta SQL.
     *   Ejemplo: `'usuarios_renombrados'`.
     *
     * ### Resultado esperado:
     *
     * - **`join_tabla`**: Sentencia que indica el tipo de `JOIN` y la tabla a unirse.
     *   Ejemplo: `'INNER JOIN usuarios'`.
     *
     * - **`on_join`**: Condición de unión para el `JOIN`.
     *   Ejemplo: `'usuarios_renombrados.id = roles.usuario_id'`.
     *
     * - **`asignacion_tabla`**: Sentencia del `JOIN` con el alias asignado a la tabla.
     *   Ejemplo: `'INNER JOIN usuarios AS usuarios_renombrados'`.
     *
     * ### Casos de error:
     *
     * - Si el parámetro `$init` no incluye las claves necesarias (`tabla`, `tabla_enlace`), se devuelve un array con detalles del error.
     * - Si algún parámetro está vacío, se genera un mensaje de error indicando el problema.
     *
     * ### Notas adicionales:
     *
     * - Esta función está diseñada para facilitar la creación dinámica de `JOIN` en consultas SQL.
     * - Asegura que los datos requeridos para la consulta estén correctamente formateados y validados.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function data_for_rename(string $id_renombrada, stdClass $init, string $join,
                                    string $renombrada): stdClass|array
    {
        $keys = array('tabla','tabla_enlace');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $init);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar $init',data: $valida);
        }

        $join_tabla = $join.' JOIN '.$init->tabla;
        $on_join = $renombrada.$id_renombrada.' = '.$init->tabla_enlace;
        $asignacion_tabla = $join_tabla.' AS '.$renombrada;

        $data = new stdClass();
        $data->join_tabla = $join_tabla;
        $data->on_join = $on_join;
        $data->asignacion_tabla = $asignacion_tabla;
        return $data;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera una cláusula SQL JOIN basada en la estructura proporcionada en `$tabla_join`.
     * Valida la existencia de claves necesarias y construye la relación entre las tablas base y de enlace.
     *
     * @param array $tabla_join Array que contiene la información necesaria para generar el JOIN.
     *                          Debe incluir las claves:
     *                          - `tabla_base`: Nombre de la tabla base.
     *                          - `tabla_enlace`: Nombre de la tabla de enlace.
     *                          Opcionalmente puede incluir:
     *                          - `tabla_renombrada`: Alias para la tabla base en el SQL.
     *                          - `campo_tabla_base_id`: Campo en la tabla base que se utiliza en la relación.
     *                          - `campo_renombrado`: Campo renombrado utilizado en la relación.
     *
     * @return array|string Retorna la cláusula SQL generada como una cadena en caso de éxito,
     *                      o un array con el detalle del error si ocurre algún problema.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * $tabla_join = [
     *     'tabla_base' => 'usuarios',
     *     'tabla_enlace' => 'roles',
     *     'tabla_renombrada' => 'u',
     *     'campo_tabla_base_id' => 'id',
     *     'campo_renombrado' => 'usuario_id'
     * ];
     *
     * $sql_join = $this->data_para_join($tabla_join);
     *
     * // Resultado esperado:
     * // " LEFT JOIN usuarios AS u ON u.id = roles.usuario_id"
     * ```
     *
     * ### Validaciones realizadas:
     * - Verifica que el array `$tabla_join` contenga las claves requeridas (`tabla_base` y `tabla_enlace`).
     * - Llama a la función `data_join` para estructurar la información base del JOIN.
     * - Llama a `genera_join` para construir la cláusula SQL completa con los datos estructurados.
     *
     * ### Detalles de la implementación:
     * 1. **Validación del array `$tabla_join`:**
     *    - Verifica que existan las claves `tabla_base` y `tabla_enlace`.
     *    - Si faltan claves o los valores no son válidos, retorna un error.
     * 2. **Generación de la estructura de datos para el JOIN:**
     *    - Llama a `data_join` para obtener un objeto con las propiedades necesarias (`tabla`, `tabla_enlace`, etc.).
     * 3. **Construcción de la cláusula JOIN:**
     *    - Usa `genera_join` con los datos obtenidos para construir la cláusula SQL final.
     *
     * ### Ejemplo de integración en un sistema:
     * ```php
     * class GeneradorSQL {
     *     public function genera_query() {
     *         $tabla_join = [
     *             'tabla_base' => 'productos',
     *             'tabla_enlace' => 'categorias',
     *             'tabla_renombrada' => 'p',
     *             'campo_tabla_base_id' => 'id',
     *             'campo_renombrado' => 'producto_id'
     *         ];
     *
     *         $sql_join = $this->data_para_join($tabla_join);
     *         echo $sql_join;
     *         // Resultado: " LEFT JOIN productos AS p ON p.id = categorias.producto_id"
     *     }
     * }
     * ```
     *
     * ### Posibles errores y sus causas:
     * - **Faltan claves en `$tabla_join`:** Si no están presentes `tabla_base` o `tabla_enlace`, se retorna un error.
     * - **Error en `data_join`:** Si la estructura del JOIN no puede generarse correctamente.
     * - **Error en `genera_join`:** Si la cláusula SQL no puede generarse debido a valores inválidos o inconsistentes.
     *
     * ### Consideraciones:
     * - Asegúrate de que `$tabla_join` tenga todas las claves necesarias para evitar errores.
     * - Es ideal para construir relaciones SQL dinámicas entre tablas con validaciones estrictas.
=======
     * Genera la cláusula SQL necesaria para realizar un `JOIN` entre tablas con validaciones previas.
     *
     * Esta función se encarga de validar los datos de configuración necesarios para un `JOIN`, construir la
     * estructura requerida y finalmente generar la sentencia SQL del `JOIN` con los datos proporcionados.
     *
     * @param array $tabla_join Configuración de las tablas que participarán en el `JOIN`.
     *                          Este array debe contener las claves:
     *                          - `'tabla_base'`: Nombre de la tabla principal. Ejemplo: `'usuarios'`.
     *                          - `'tabla_enlace'`: Nombre de la tabla relacionada (enlace). Ejemplo: `'roles'`.
     *                          - `'tabla_renombrada'` (opcional): Alias para la tabla base. Ejemplo: `'usuarios_renombrados'`.
     *                          - `'campo_tabla_base_id'` (opcional): Campo de la tabla base utilizado en la cláusula `ON`.
     *                            Ejemplo: `'usuario_id'`.
     *                          - `'campo_renombrado'` (opcional): Campo clave de la tabla de enlace para el `JOIN`.
     *                            Ejemplo: `'id_usuario'`.
     *
     * @return array|string La cláusula `JOIN` generada en caso de éxito. En caso de error, devuelve un array con los
     *                      detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $tabla_join = array(
     *     'tabla_base' => 'usuarios',
     *     'tabla_enlace' => 'roles',
     *     'tabla_renombrada' => 'usuarios_renombrados',
     *     'campo_tabla_base_id' => 'usuario_id',
     *     'campo_renombrado' => 'id_usuario'
     * );
     *
     * $resultado = $this->data_para_join(tabla_join: $tabla_join);
     *
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$tabla_join`**:
     *   Array que contiene las configuraciones necesarias para construir el `JOIN`.
     *   Ejemplo:
     *   ```php
     *   array(
     *       'tabla_base' => 'usuarios',
     *       'tabla_enlace' => 'roles',
     *       'tabla_renombrada' => 'usuarios_renombrados',
     *       'campo_tabla_base_id' => 'usuario_id',
     *       'campo_renombrado' => 'id_usuario'
     *   );
     *   ```
     *
     * ### Casos de error:
     *
     * - Si el array `$tabla_join` no contiene las claves `'tabla_base'` o `'tabla_enlace'`, se genera un error indicando que
     *   las claves son obligatorias.
     * - Si ocurre un problema al validar o generar la cláusula `JOIN`, se devuelve un array con los detalles del error.
     *
     * ### Resultado esperado:
     *
     * - Genera una cláusula SQL `JOIN` en formato correcto.
     *   Ejemplo:
     *   `' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'`.
     *
     * ### Notas adicionales:
     *
     * - Esta función utiliza validaciones estrictas para garantizar que la estructura de entrada sea correcta y evitar errores.
     * - Es útil para construir consultas SQL dinámicas en aplicaciones que manejan relaciones complejas entre tablas.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function data_para_join(array $tabla_join): array|string
    {
        $keys = array('tabla_base','tabla_enlace');
        $valida = $this->validacion->valida_existencia_keys( keys:$keys, registro: $tabla_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar $tabla_join',data: $valida);
        }

        $data_join = $this->data_join(tabla_join: $tabla_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar data',data:  $data_join);
        }

        $data = $this->genera_join(tabla: $data_join->tabla_base, tabla_enlace: $data_join->tabla_enlace,
            campo_renombrado: $data_join->campo_renombrado, campo_tabla_base_id: $data_join->campo_tabla_base_id,
            renombrada: $data_join->tabla_renombre);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar join', data: $data);
        }
        return $data;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera la estructura necesaria para un SQL JOIN entre dos tablas, validando previamente los parámetros.
     *
     * @param string $key Nombre de la tabla base del JOIN.
     *                    - No debe ser numérico.
     *                    - No debe estar vacío.
     * @param string $tabla_join Nombre de la tabla que se unirá en el JOIN.
     *                           - No debe ser numérico.
     *                           - No debe estar vacío.
     *
     * @return array|string Retorna una cadena con el SQL JOIN generado o un array con detalles del error si ocurre una validación fallida.
     *
     * ### Ejemplo de uso exitoso:
=======
     * Genera los datos necesarios para construir una sentencia SQL `JOIN` específica.
     *
     * Esta función valida las tablas proporcionadas (`key` y `tabla_join`) y genera una sentencia `JOIN`
     * utilizando los parámetros especificados.
     *
     * @param string $key Clave principal o alias de la tabla base utilizada en el `JOIN`.
     *                    Debe ser una cadena no vacía y no numérica.
     *                    Ejemplo: `'usuarios'`.
     * @param string $tabla_join Nombre de la tabla de enlace utilizada en el `JOIN`.
     *                           Debe ser una cadena no vacía y no numérica.
     *                           Ejemplo: `'roles'`.
     *
     * @return array|string Devuelve un string con la sentencia `JOIN` generada si no hay errores.
     *                      En caso de error, devuelve un array con detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     *
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     * ```php
     * $key = 'usuarios';
     * $tabla_join = 'roles';
     *
<<<<<<< HEAD
     * $resultado = $this->data_para_join_esp($key, $tabla_join);
     *
     * if (is_string($resultado)) {
     *     echo 'SQL JOIN generado: ' . $resultado;
     * } else {
     *     print_r($resultado); // Mostrará el detalle del error si ocurre.
     * }
     * // Resultado esperado:
     * // SQL JOIN generado: ' LEFT JOIN roles ON usuarios.id = roles.usuarios_id'
     * ```
     *
     * ### Validaciones realizadas:
     * 1. **Validación de `$key` y `$tabla_join`:**
     *    - Verifica que no sean numéricos ni cadenas vacías.
     *    - Utiliza la función `valida_tabla_join` para realizar estas validaciones.
     * 2. **Generación del JOIN:**
     *    - Utiliza la función `genera_join` para construir el SQL JOIN entre las tablas especificadas.
     *
     * ### Ejemplo de errores:
     * **Error por `$key` vacío:**
     * ```php
     * $key = '';
     * $tabla_join = 'roles';
     *
     * $resultado = $this->data_para_join_esp($key, $tabla_join);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error key esta vacio',
     * //     'data' => ''
     * // ]
     * ```
     *
     * **Error por `$tabla_join` numérico:**
     * ```php
     * $key = 'usuarios';
     * $tabla_join = '123';
     *
     * $resultado = $this->data_para_join_esp($key, $tabla_join);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error el $tabla_join no puede ser un numero',
     * //     'data' => '123'
     * // ]
     * ```
     *
     * ### Detalles de implementación:
     * - La función utiliza `trim` para limpiar los espacios en blanco de los parámetros `$key` y `$tabla_join`.
     * - Llama a `valida_tabla_join` para validar la estructura de las tablas antes de generar el JOIN.
     * - Si las validaciones son exitosas, utiliza `genera_join` para construir el SQL JOIN entre las tablas.
     *
     * ### Casos de uso:
     * - Generar dinámicamente un JOIN entre dos tablas en una consulta SQL, asegurando que los parámetros sean válidos.
     * - Validar los nombres de las tablas antes de realizar operaciones en la base de datos.
     *
     * ### Consideraciones:
     * - Los nombres de tablas deben estar bien definidos y ser coherentes con la estructura de la base de datos.
     * - Esta función es ideal para sistemas que construyen consultas SQL de manera dinámica y necesitan robustez en la validación de entradas.
=======
     * $resultado = $this->data_para_join_esp(key: $key, tabla_join: $tabla_join);
     *
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$key`**:
     *   Representa el alias o nombre de la tabla base utilizada en el `JOIN`.
     *   Requisitos:
     *   - Debe ser una cadena no vacía.
     *   - No puede ser numérico.
     *   Ejemplo válido:
     *   ```php
     *   'usuarios'
     *   ```
     *   Ejemplo inválido:
     *   ```php
     *   ''  // Está vacío.
     *   123 // Es numérico.
     *   ```
     *
     * - **`$tabla_join`**:
     *   Es el nombre de la tabla de enlace que participa en el `JOIN`.
     *   Requisitos:
     *   - Debe ser una cadena no vacía.
     *   - No puede ser numérico.
     *   Ejemplo válido:
     *   ```php
     *   'roles'
     *   ```
     *   Ejemplo inválido:
     *   ```php
     *   ''   // Está vacío.
     *   456  // Es numérico.
     *   ```
     *
     * ### Casos de uso exitoso:
     *
     * 1. Generar un `JOIN` entre las tablas `'usuarios'` y `'roles'`:
     *    ```php
     *    $key = 'usuarios';
     *    $tabla_join = 'roles';
     *
     *    $resultado = $this->data_para_join_esp(key: $key, tabla_join: $tabla_join);
     *
     *    // Resultado esperado:
     *    // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     *    ```
     *
     * ### Casos de error:
     *
     * 1. **`$key` está vacío**:
     *    ```php
     *    $resultado = $this->data_para_join_esp(key: '', tabla_join: 'roles');
     *    // Resultado esperado: array con mensaje de error "Error key esta vacio".
     *    ```
     *
     * 2. **`$tabla_join` está vacío**:
     *    ```php
     *    $resultado = $this->data_para_join_esp(key: 'usuarios', tabla_join: '');
     *    // Resultado esperado: array con mensaje de error "Error $tabla_join esta vacio".
     *    ```
     *
     * 3. **`$key` es numérico**:
     *    ```php
     *    $resultado = $this->data_para_join_esp(key: '123', tabla_join: 'roles');
     *    // Resultado esperado: array con mensaje de error "Error el key no puede ser un numero".
     *    ```
     *
     * 4. **Error al generar el `JOIN`**:
     *    Si ocurre un error en la construcción del `JOIN`, se devuelve un array con detalles del problema.
     *
     * ### Resultado esperado:
     * - Devuelve un string con la sentencia `JOIN` generada correctamente.
     * - Si ocurre un error, devuelve un array con los detalles y el mensaje del problema.
     *
     * ### Notas adicionales:
     * - La validación de las tablas se realiza a través de la función `valida_tabla_join`.
     * - La generación de la sentencia SQL `JOIN` utiliza `genera_join` internamente.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function data_para_join_esp(string $key, string $tabla_join): array|string
    {
        $key = trim($key);
        $tabla_join = trim($tabla_join);

        $valida = (new validaciones())->valida_tabla_join(key: $key, tabla_join: $tabla_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar join', data: $valida);
        }

        $data = $this->genera_join(tabla:$key, tabla_enlace: $tabla_join );
        if(errores::$error){
            return $this->error->error(mensaje:'Error al generar join', data:$data);
        }
        return $data;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera la estructura SQL de las tablas para una consulta, incluyendo los JOINs necesarios.
     *
     * @param string $key Nombre de la tabla base en el JOIN.
     *                    - Debe ser una cadena no vacía.
     * @param array|string $tabla_join Datos para generar el JOIN.
     *                                  - Puede ser un arreglo con detalles para la construcción del JOIN.
     *                                  - O una cadena con el nombre de la tabla a unir.
     * @param string $tablas Cadena acumulativa que contiene los JOINs previamente generados.
     *
     * @return array|string Devuelve la cadena SQL con los JOINs generados, o un array de error si ocurre un fallo.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * // Caso con $tabla_join como array
     * $key = 'usuarios';
     * $tabla_join = [
     *     'tabla_base' => 'usuarios',
     *     'tabla_enlace' => 'roles',
     *     'campo_tabla_base_id' => 'id',
     *     'campo_renombrado' => 'roles_id',
     * ];
     * $tablas = '';
     *
     * $resultado = $this->data_tabla_sql($key, $tabla_join, $tablas);
     * echo $resultado;
     * // Resultado esperado:
     * // ' LEFT JOIN roles ON usuarios.id = roles.roles_id'
     *
     * // Caso con $tabla_join como string
     * $key = 'usuarios';
     * $tabla_join = 'roles';
     * $tablas = '';
     *
     * $resultado = $this->data_tabla_sql($key, $tabla_join, $tablas);
     * echo $resultado;
     * // Resultado esperado:
     * // ' LEFT JOIN roles ON usuarios.id = roles.usuarios_id'
     * ```
     *
     * ### Proceso de la función:
     * 1. **Inicialización de `$tablas_env`:**
     *    - Inicializa la variable `$tablas_env` con el valor del parámetro `$tablas`.
     * 2. **Validación y procesamiento de `$tabla_join`:**
     *    - Si `$tabla_join` es un array:
     *      - Llama a la función `tablas_join_base` para generar los JOINs en base al contenido del arreglo.
     *      - Verifica errores tras la ejecución.
     *    - Si `$tabla_join` es una cadena:
     *      - Llama a la función `tablas_join_esp` para generar un JOIN simple basado en la tabla base y la de unión.
     *      - Verifica errores tras la ejecución.
     * 3. **Retorno del resultado:**
     *    - Devuelve la cadena SQL acumulativa con los JOINs generados.
     *
     * ### Ejemplo de errores:
     * **Error por `$key` vacío:**
     * ```php
     * $key = '';
     * $tabla_join = 'roles';
     * $tablas = '';
     *
     * $resultado = $this->data_tabla_sql($key, $tabla_join, $tablas);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error al generar join',
     * //     'data' => 'Error key esta vacio'
     * // ]
     * ```
     *
     * **Error por `$tabla_join` mal estructurado (array):**
     * ```php
     * $key = 'usuarios';
     * $tabla_join = [
     *     'tabla_enlace' => 'roles', // Falta la clave 'tabla_base'
     * ];
     * $tablas = '';
     *
     * $resultado = $this->data_tabla_sql($key, $tabla_join, $tablas);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error al validar $tabla_join',
     * //     'data' => ['tabla_enlace' => 'roles']
     * // ]
     * ```
     *
     * ### Casos de uso:
     * - Generación dinámica de consultas SQL que involucran múltiples tablas y JOINs.
     * - Construcción flexible para casos con configuraciones complejas de tablas relacionadas.
     *
     * ### Consideraciones:
     * - La función depende de las funciones `tablas_join_base` y `tablas_join_esp` para generar los JOINs.
     * - Los nombres de las tablas deben ser válidos y coincidir con las definiciones en la base de datos.
     * - Si `$tabla_join` es un array, debe contener las claves necesarias para definir el JOIN.
=======
     * Genera y ajusta una cadena de sentencias SQL de tipo `JOIN` con base en la configuración de tablas proporcionadas.
     *
     * Esta función construye las relaciones necesarias entre tablas para una consulta SQL,
     * utilizando diferentes métodos según el tipo de `$tabla_join` (array o string).
     *
     * @param string $key Nombre o alias de la tabla base que servirá como referencia para la relación `JOIN`.
     *                    Debe ser una cadena no vacía.
     *                    Ejemplo: `'usuarios'`.
     * @param array|string $tabla_join Configuración de las tablas a relacionar.
     *                                 Puede ser:
     *                                 - Un **array** con la estructura de la relación.
     *                                 - Un **string** representando la tabla de enlace.
     *                                 Ejemplo array:
     *                                 ```php
     *                                 [
     *                                     'tabla_base' => 'usuarios',
     *                                     'tabla_enlace' => 'roles'
     *                                 ]
     *                                 ```
     *                                 Ejemplo string:
     *                                 ```php
     *                                 'roles'
     *                                 ```
     * @param string $tablas Cadena de tablas o relaciones ya generadas, a la que se agregarán las nuevas sentencias `JOIN`.
     *                       Puede estar vacía para comenzar desde cero.
     *                       Ejemplo: `'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id'`.
     *
     * @return array|string Retorna la cadena actualizada de tablas con las relaciones `JOIN` añadidas.
     *                      En caso de error, retorna un array con los detalles del problema.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $key = 'usuarios';
     * $tabla_join = [
     *     'tabla_base' => 'usuarios',
     *     'tabla_enlace' => 'roles'
     * ];
     * $tablas = '';
     *
     * $resultado = $this->data_tabla_sql(key: $key, tabla_join: $tabla_join, tablas: $tablas);
     *
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     * ```
     *
     * ### Parámetros:
     *
     * - **`$key`**:
     *   Nombre o alias de la tabla base para la relación `JOIN`.
     *   Ejemplo válido:
     *   ```php
     *   'usuarios'
     *   ```
     *   Ejemplo inválido:
     *   ```php
     *   '' // Está vacío.
     *   ```
     *
     * - **`$tabla_join`**:
     *   Relación de tablas o nombre de la tabla de enlace.
     *   Puede ser un **array** o un **string**.
     *   Ejemplo de array válido:
     *   ```php
     *   [
     *       'tabla_base' => 'usuarios',
     *       'tabla_enlace' => 'roles'
     *   ]
     *   ```
     *   Ejemplo de string válido:
     *   ```php
     *   'roles'
     *   ```
     *
     * - **`$tablas`**:
     *   Cadena de tablas o relaciones SQL ya generadas.
     *   Se concatenará con las nuevas relaciones `JOIN`.
     *   Ejemplo inicial:
     *   ```php
     *   ''
     *   ```
     *   Ejemplo con datos:
     *   ```php
     *   'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id'
     *   ```
     *
     * ### Casos de uso exitoso:
     *
     * 1. Generar un `JOIN` con un array de relación:
     *    ```php
     *    $key = 'usuarios';
     *    $tabla_join = [
     *        'tabla_base' => 'usuarios',
     *        'tabla_enlace' => 'roles'
     *    ];
     *    $tablas = '';
     *
     *    $resultado = $this->data_tabla_sql(key: $key, tabla_join: $tabla_join, tablas: $tablas);
     *
     *    // Resultado esperado:
     *    // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     *    ```
     *
     * 2. Generar un `JOIN` con una tabla de enlace (string):
     *    ```php
     *    $key = 'usuarios';
     *    $tabla_join = 'roles';
     *    $tablas = '';
     *
     *    $resultado = $this->data_tabla_sql(key: $key, tabla_join: $tabla_join, tablas: $tablas);
     *
     *    // Resultado esperado:
     *    // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     *    ```
     *
     * 3. Concatenar a una cadena existente:
     *    ```php
     *    $key = 'usuarios';
     *    $tabla_join = 'roles';
     *    $tablas = 'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id';
     *
     *    $resultado = $this->data_tabla_sql(key: $key, tabla_join: $tabla_join, tablas: $tablas);
     *
     *    // Resultado esperado:
     *    // 'INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id
     *    //  LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     *    ```
     *
     * ### Casos de error:
     *
     * 1. `$key` está vacío:
     *    ```php
     *    $resultado = $this->data_tabla_sql(key: '', tabla_join: 'roles', tablas: '');
     *    // Resultado esperado: array con mensaje de error "Error key esta vacio".
     *    ```
     *
     * 2. `$tabla_join` no es un array ni un string:
     *    ```php
     *    $resultado = $this->data_tabla_sql(key: 'usuarios', tabla_join: 123, tablas: '');
     *    // Resultado esperado: array con mensaje de error "Error al validar $tabla_join".
     *    ```
     *
     * 3. Error interno en generación del `JOIN`:
     *    Si ocurre un problema en `tablas_join_base` o `tablas_join_esp`, se retorna el error correspondiente.
     *
     * ### Resultado esperado:
     * - Devuelve una cadena concatenada de sentencias SQL `JOIN`.
     * - Si ocurre un error, retorna un array descriptivo del problema.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function data_tabla_sql(string $key, array|string $tabla_join, string $tablas): array|string
    {
        $tablas_env = $tablas;
        if(is_array($tabla_join)){
            $tablas_env = $this->tablas_join_base(tabla_join: $tabla_join, tablas: $tablas);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar data join', data: $tablas_env);
            }
        }
        else if ($tabla_join) {
            $tablas_env = $this->tablas_join_esp(key: $key,tabla_join:  $tabla_join, tablas: $tablas);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar join', data: $tablas_env);
            }
        }
        return $tablas_env;
    }

    /**
     * REG
     * Genera y extiende las instrucciones SQL de JOIN basadas en una estructura de extensiones proporcionada.
     *
     * Este método recorre una estructura de extensión, valida los datos de cada tabla y construye las instrucciones
     * de JOIN necesarias para integrarlas a la cadena de tablas SQL existente. Asegura que los datos y las claves
     * requeridas sean válidas antes de proceder con la generación del SQL.
     *
     * @param array $extension_estructura Estructura de extensión que define las tablas y datos necesarios para
     * construir los JOIN. Cada entrada debe contener:
     *  - `key`: Nombre del campo clave en la tabla base.
     *  - `enlace`: Nombre de la tabla con la que se realiza el JOIN.
     *  - `key_enlace`: Nombre del campo clave en la tabla de enlace.
     *  - `renombre` (opcional): Nombre renombrado para la tabla base en el JOIN.
     * @param string $modelo_tabla Nombre de la tabla principal del modelo. Se utiliza para validaciones.
     * @param string $tablas Cadena actual de tablas SQL con las instrucciones generadas previamente.
     *
     * @return array|string Devuelve la cadena SQL actualizada con las nuevas instrucciones JOIN generadas.
     * En caso de error, retorna un array con los detalles del mismo.
     *
     * @throws errores Si:
     * - `$extension_estructura` contiene datos que no son arrays.
     * - Alguna clave requerida (`key`, `enlace`, `key_enlace`) no está presente o está vacía.
     * - Alguna tabla en `$extension_estructura` tiene un índice numérico o no es un texto válido.
     * - Falla la generación de la instrucción JOIN.
     *
     * ### Ejemplos de uso:
     *
     * 1. **Caso exitoso con datos completos**:
     *    ```php
     *    $extension_estructura = [
     *        'usuarios' => [
     *            'key' => 'id',
     *            'enlace' => 'pedidos',
     *            'key_enlace' => 'usuario_id',
     *            'renombre' => 'usuarios_alias'
     *        ],
     *        'productos' => [
     *            'key' => 'id',
     *            'enlace' => 'pedidos',
     *            'key_enlace' => 'producto_id',
     *            'renombre' => 'productos_alias'
     *        ]
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tablas = 'pedidos AS pedidos';
     *
     *    $resultado = $modelo->extensiones_join(extension_estructura: $extension_estructura, modelo_tabla: $modelo_tabla, tablas: $tablas);
     *    // Resultado esperado: "pedidos AS pedidos LEFT JOIN usuarios AS usuarios_alias ON usuarios_alias.id = pedidos.usuario_id LEFT JOIN productos AS productos_alias ON productos_alias.id = pedidos.producto_id"
     *    ```
     *
     * 2. **Caso con estructura no válida (índice numérico en la clave de tabla)**:
     *    ```php
     *    $extension_estructura = [
     *        1 => [
     *            'key' => 'id',
     *            'enlace' => 'pedidos',
     *            'key_enlace' => 'usuario_id',
     *            'renombre' => 'usuarios_alias'
     *        ]
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tablas = 'pedidos AS pedidos';
     *
     *    $resultado = $modelo->extensiones_join(extension_estructura: $extension_estructura, modelo_tabla: $modelo_tabla, tablas: $tablas);
     *    // Resultado esperado: Array con error indicando que $tabla debe ser un texto.
     *    ```
     *
     * 3. **Caso con datos incompletos (clave faltante)**:
     *    ```php
     *    $extension_estructura = [
     *        'usuarios' => [
     *            'enlace' => 'pedidos',
     *            'key_enlace' => 'usuario_id'
     *        ]
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tablas = 'pedidos AS pedidos';
     *
     *    $resultado = $modelo->extensiones_join(extension_estructura: $extension_estructura, modelo_tabla: $modelo_tabla, tablas: $tablas);
     *    // Resultado esperado: Array con error indicando que falta la clave `key` en la entrada.
     *    ```
     *
     * ### Proceso de evaluación:
     * 1. Itera por cada entrada en `$extension_estructura` para validar que los datos sean un array.
     * 2. Valida las claves requeridas (`key`, `enlace`, `key_enlace`) en cada entrada utilizando `valida_keys_sql`.
     * 3. Genera el SQL JOIN utilizando `join_base` para cada tabla en la estructura.
     * 4. Ajusta y concatena las nuevas instrucciones JOIN con la cadena SQL existente.
     *
     * ### Resultado esperado:
     * - **SQL completo**: Cadena SQL que incluye todas las instrucciones JOIN generadas.
     * - **Error**: Array con detalles del error en caso de que alguna validación o proceso falle.
     */

    private function extensiones_join(array $extension_estructura, string $modelo_tabla, string $tablas): array|string
    {
        $tablas_env = $tablas;
        foreach($extension_estructura as $tabla=>$data){
            if(!is_array($data)){
                return $this->error->error(mensaje: 'Error data debe ser un array', data: $data, es_final: true);
            }
            $valida = (new validaciones())->valida_keys_sql(data: $data, tabla: $modelo_tabla);
            if(errores::$error){
                return $this->error->error(mensaje:'Error al validar data', data:$valida);
            }
            if(is_numeric($tabla)){
                return $this->error->error(mensaje:'Error $tabla debe ser un texto', data:$tabla, es_final: true);
            }

            $tablas_env = $this->join_base(data: $data, modelo_tabla: $modelo_tabla,tabla:  $tabla, tablas: $tablas);
            if(errores::$error){
                return $this->error->error(mensaje:'Error al generar join',data: $tablas);
            }
            $tablas = (string)$tablas_env;
        }
        return $tablas_env;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera una cadena que representa un identificador renombrado basado en un campo de tabla base.
     *
     * Si se proporciona un valor en `$campo_tabla_base_id`, se utiliza como parte del identificador renombrado.
     * De lo contrario, el identificador por defecto será `".id"`.
     *
     * @param string $campo_tabla_base_id Nombre del campo de la tabla base que se utilizará para generar el identificador renombrado.
     *                                    Si está vacío, se usará `".id"` como valor por defecto.
     *
     * @return string Retorna la cadena del identificador renombrado.
     *                Ejemplo: `".id"` o `".<campo_tabla_base_id>"`.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * // Caso 1: Se proporciona un campo válido.
     * $campo_tabla_base_id = 'usuario_id';
     * $resultado = $miClase->id_renombrada($campo_tabla_base_id);
     * echo $resultado; // Salida esperada: ".usuario_id"
     *
     * // Caso 2: Se proporciona una cadena vacía.
     * $campo_tabla_base_id = '';
     * $resultado = $miClase->id_renombrada($campo_tabla_base_id);
     * echo $resultado; // Salida esperada: ".id"
     * ```
     *
     * ### Detalles de validación:
     * - **Espacios en blanco:** El parámetro `$campo_tabla_base_id` será recortado para eliminar espacios al inicio y al final.
     * - Si `$campo_tabla_base_id` está vacío después del recorte, se devolverá `".id"` como valor predeterminado.
     *
     * ### Consideraciones:
     * - Útil para generar identificadores únicos y personalizados en consultas SQL o estructuras de datos.
     * - Asegura que siempre se devuelva un valor válido, incluso si el parámetro de entrada está vacío.
     *
     * ### Ejemplo de integración:
     * ```php
     * class Ejemplo {
     *     private function id_renombrada(string $campo_tabla_base_id): string {
     *         $campo_tabla_base_id = trim($campo_tabla_base_id);
     *         $id_renombrada = '.id';
     *         if ($campo_tabla_base_id !== '') {
     *             $id_renombrada = '.' . $campo_tabla_base_id;
     *         }
     *         return $id_renombrada;
     *     }
     * }
     *
     * // Uso de la función
     * $obj = new Ejemplo();
     * echo $obj->id_renombrada('orden_id'); // Salida: ".orden_id"
     * echo $obj->id_renombrada('');         // Salida: ".id"
     * ```
=======
     * Genera un identificador renombrado basado en el campo proporcionado.
     *
     * Esta función toma un nombre de campo y retorna una cadena con el identificador
     * ajustado. Si se proporciona un nombre de campo no vacío, se usa como parte del
     * identificador renombrado; de lo contrario, se usa el valor predeterminado `.id`.
     *
     * @param string $campo_tabla_base_id Nombre del campo de la tabla base utilizado para
     *                                    construir el identificador renombrado. Si está vacío,
     *                                    se utiliza el valor `.id` por defecto.
     *
     * @return string Retorna el identificador renombrado, prefijado por un punto (`.`).
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * // Caso: Se proporciona un campo no vacío.
     * $campo_tabla_base_id = 'usuario_id';
     * $resultado = $this->id_renombrada($campo_tabla_base_id);
     * echo $resultado;
     * // Resultado esperado:
     * // .usuario_id
     *
     * // Caso: Se proporciona un campo vacío.
     * $campo_tabla_base_id = '';
     * $resultado = $this->id_renombrada($campo_tabla_base_id);
     * echo $resultado;
     * // Resultado esperado:
     * // .id
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$campo_tabla_base_id`**:
     *   Este parámetro representa el nombre del campo que será utilizado para formar
     *   el identificador renombrado. Si está vacío, el valor predeterminado `.id` será usado.
     *   Ejemplo válido: `'usuario_id'`.
     *   Ejemplo vacío: `''`.
     *
     * ### Resultado esperado:
     *
     * - Si se proporciona un nombre de campo no vacío (`$campo_tabla_base_id`), el retorno será una cadena
     *   con el formato `.{campo_tabla_base_id}`.
     *   Ejemplo: `.usuario_id`.
     *
     * - Si el parámetro está vacío, la función retorna el valor predeterminado `.id`.
     *
     * ### Notas adicionales:
     *
     * - Está diseñada para ser utilizada en la construcción de identificadores renombrados en consultas SQL.
     * - Es una función auxiliar que asegura consistencia en el formato del identificador.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function id_renombrada(string $campo_tabla_base_id): string
    {
        $campo_tabla_base_id = trim($campo_tabla_base_id);
        $id_renombrada = '.id';
        if($campo_tabla_base_id!==''){
            $id_renombrada = '.'.$campo_tabla_base_id;
        }
        return $id_renombrada;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera una cláusula SQL de tipo JOIN para relacionar dos tablas, con soporte para alias y campos específicos.
     *
     * @param string $tabla Nombre de la tabla base. Este parámetro no puede estar vacío.
     * @param string $tabla_enlace Nombre de la tabla de enlace que se relaciona con la tabla base. Este parámetro no puede estar vacío.
     * @param string $campo_renombrado Nombre del campo renombrado para la relación (opcional). Si se especifica, se usa en la cláusula `ON`.
     * @param string $campo_tabla_base_id Nombre del campo de la tabla base que se utiliza en la relación (opcional). Por defecto, utiliza `.id`.
     * @param string $renombrada Alias para la tabla base en la cláusula SQL (opcional). Si se especifica, se usa como alias en el SQL.
     *
     * @return array|string Retorna la cláusula SQL generada como una cadena en caso de éxito, o un array de error si ocurre alguna falla.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * // Caso 1: Generar un JOIN básico
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     *
     * $sql = $this->genera_join(
     *     tabla: $tabla,
     *     tabla_enlace: $tabla_enlace
     * );
     *
     * // Resultado esperado:
     * // " LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id"
     *
     * // Caso 2: Generar un JOIN con alias y campos renombrados
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     * $campo_renombrado = 'id_usuario';
     * $campo_tabla_base_id = 'id';
     * $renombrada = 'u';
     *
     * $sql = $this->genera_join(
=======
     * Genera una cláusula SQL `JOIN` dinámica para combinar tablas en una consulta.
     *
     * Esta función construye una sentencia SQL `LEFT JOIN`, permitiendo el uso de alias para las tablas y
     * configuraciones personalizadas en los campos utilizados en la cláusula `ON`. Se asegura de que los
     * parámetros sean válidos antes de generar el SQL.
     *
     * @param string $tabla Nombre de la tabla principal que participa en el `JOIN`.
     *                      Ejemplo: `'usuarios'`.
     * @param string $tabla_enlace Nombre de la tabla relacionada que se une con la tabla principal.
     *                             Ejemplo: `'roles'`.
     * @param string $campo_renombrado (Opcional) Campo específico utilizado como clave en la cláusula `ON`.
     *                                  Por defecto está vacío. Ejemplo: `'id_usuario'`.
     * @param string $campo_tabla_base_id (Opcional) Campo de la tabla base que será referenciado en la condición `ON`.
     *                                     Por defecto se utiliza `'id'` si este parámetro está vacío.
     *                                     Ejemplo: `'usuario_id'`.
     * @param string $renombrada (Opcional) Alias para la tabla principal en la consulta. Si está vacío, no se aplica un alias.
     *                            Ejemplo: `'usuarios_renombrados'`.
     *
     * @return array|string Retorna la cláusula `LEFT JOIN` como una cadena en caso de éxito. En caso de error,
     *                      devuelve un array con los detalles del error.
     *
     * ### Ejemplo de uso exitoso con alias para la tabla:
     *
     * ```php
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     * $campo_renombrado = 'id_usuario';
     * $campo_tabla_base_id = 'usuario_id';
     * $renombrada = 'usuarios_renombrados';
     *
     * $resultado = $this->genera_join(
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     *     tabla: $tabla,
     *     tabla_enlace: $tabla_enlace,
     *     campo_renombrado: $campo_renombrado,
     *     campo_tabla_base_id: $campo_tabla_base_id,
     *     renombrada: $renombrada
     * );
     *
     * // Resultado esperado:
<<<<<<< HEAD
     * // " LEFT JOIN usuarios AS u ON u.id = roles.id_usuario"
     * ```
     *
     * ### Validaciones realizadas:
     * - Verifica que los parámetros `$tabla` y `$tabla_enlace` no estén vacíos.
     * - Remueve el prefijo `models\` de los nombres de tabla para garantizar un formato limpio.
     * - Valida la generación de la cláusula SQL a través de la función `sql_join`.
     *
     * ### Detalles de la generación:
     * - Si se especifican `$campo_renombrado`, `$campo_tabla_base_id` o `$renombrada`, se generan cláusulas SQL más complejas con alias y campos personalizados.
     * - Si no se especifican estos parámetros opcionales, se genera una relación básica entre las tablas.
     *
     * ### Ejemplo de integración en un sistema:
     * ```php
     * class GeneradorSQL {
     *     public function genera_query() {
     *         $sql = $this->genera_join(
     *             tabla: 'productos',
     *             tabla_enlace: 'categorias',
     *             campo_renombrado: 'id_producto',
     *             campo_tabla_base_id: 'id',
     *             renombrada: 'p'
     *         );
     *         echo $sql;
     *         // Resultado: " LEFT JOIN productos AS p ON p.id = categorias.id_producto"
     *     }
     * }
     * ```
     *
     * ### Posibles errores y sus causas:
     * - **Error `$tabla` vacía:** Ocurre si no se proporciona el nombre de la tabla base.
     * - **Error `$tabla_enlace` vacía:** Ocurre si no se proporciona el nombre de la tabla de enlace.
     * - **Error en la generación del SQL:** Si la función `sql_join` encuentra un problema al generar la cláusula SQL.
     *
     * ### Consideraciones:
     * - Útil para construir dinámicamente relaciones entre tablas con validaciones estrictas de parámetros.
     * - Simplifica la creación de consultas SQL complejas con alias y claves específicas.
=======
     * // ' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'
     * ```
     *
     * ### Ejemplo de uso exitoso sin alias:
     *
     * ```php
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     *
     * $resultado = $this->genera_join(
     *     tabla: $tabla,
     *     tabla_enlace: $tabla_enlace
     * );
     *
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$tabla`**:
     *   Nombre de la tabla principal en la consulta. No debe estar vacío.
     *   Ejemplo: `'usuarios'`.
     *
     * - **`$tabla_enlace`**:
     *   Nombre de la tabla relacionada (enlace) que será unida con la tabla principal.
     *   Ejemplo: `'roles'`.
     *
     * - **`$campo_renombrado`**:
     *   Campo específico que será utilizado como clave en la cláusula `ON`. Es opcional.
     *   Ejemplo: `'id_usuario'`.
     *
     * - **`$campo_tabla_base_id`**:
     *   Campo de la tabla base que se referenciará en la condición `ON`. Por defecto se utiliza `'id'`.
     *   Ejemplo: `'usuario_id'`.
     *
     * - **`$renombrada`**:
     *   Alias de la tabla principal para la consulta. Si está vacío, no se aplica un alias. Es opcional.
     *   Ejemplo: `'usuarios_renombrados'`.
     *
     * ### Casos de error:
     *
     * - Si `$tabla` o `$tabla_enlace` están vacíos, se genera un error indicando que son parámetros obligatorios.
     * - Si ocurre un problema al generar la cláusula SQL, se devuelve un array con los detalles del error.
     *
     * ### Resultado esperado:
     *
     * - Genera una cláusula `LEFT JOIN` con las condiciones `ON` especificadas.
     * - Ejemplo con alias:
     *   `' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'`.
     * - Ejemplo sin alias:
     *   `' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'`.
     *
     * ### Notas adicionales:
     *
     * - Esta función es útil para construir consultas SQL dinámicas en aplicaciones con múltiples relaciones entre tablas.
     * - Garantiza validaciones estrictas para evitar errores comunes al construir sentencias SQL manualmente.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */
    private function genera_join(string $tabla, string $tabla_enlace, string $campo_renombrado = '',
                                 string $campo_tabla_base_id = '', string $renombrada = '' ):array|string{

        $tabla = str_replace('models\\','',$tabla);
        $tabla_enlace = str_replace('models\\','',$tabla_enlace);

        if($tabla === ''){
            return $this->error->error(mensaje: 'La tabla no puede ir vacia', data: $tabla, es_final: true);
        }
        if($tabla_enlace === ''){
            return $this->error->error(mensaje: 'El $tabla_enlace no puede ir vacio', data: $tabla_enlace,
                es_final: true);
        }

        $sql = $this->sql_join(campo_renombrado: $campo_renombrado, campo_tabla_base_id: $campo_tabla_base_id,
            renombrada: $renombrada, tabla: $tabla, tabla_enlace: $tabla_enlace);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al genera sql',data:  $sql);
        }

        return $sql;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera una cláusula SQL de tipo JOIN con alias y condiciones renombradas para relacionar tablas.
     *
     * @param string $campo_renombrado Nombre del campo a utilizar como clave renombrada en la relación (ejemplo: `id_usuario`).
     * @param string $campo_tabla_base_id Nombre del campo de ID en la tabla base. Si está vacío, se utiliza `.id` por defecto.
     * @param string $join Tipo de JOIN a aplicar (`INNER`, `LEFT`, `RIGHT`).
     * @param string $renombrada Alias que se asignará a la tabla relacionada.
     * @param string $tabla Nombre de la tabla base para la relación.
     * @param string $tabla_enlace Nombre de la tabla de enlace con la que se relaciona la tabla base.
     *
     * @return array|string Devuelve la cláusula JOIN generada como una cadena SQL o un array con detalles del error en caso de fallos.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * // Parámetros de entrada
     * $campo_renombrado = 'id_usuario';
     * $campo_tabla_base_id = 'id';
     * $join = 'INNER';
     * $renombrada = 'u';
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles.id_usuario';
     *
     * // Llamada a la función
=======
     * Genera una cláusula `JOIN` renombrada para consultas SQL.
     *
     * Esta función construye dinámicamente una sentencia SQL `JOIN` incluyendo el alias de tabla,
     * las condiciones de unión y el tipo de `JOIN` especificado. Valida todos los parámetros de entrada
     * para asegurar que la salida sea válida y funcional.
     *
     * @param string $campo_renombrado Campo renombrado que será parte de la condición `ON` del `JOIN`.
     *                                  Ejemplo: `'id_usuario'`.
     * @param string $campo_tabla_base_id Identificador de la tabla base utilizado en la condición `ON`.
     *                                    Puede ser una clave específica o `'id'`.
     * @param string $join Tipo de `JOIN` a generar. Puede ser `INNER`, `LEFT` o `RIGHT`.
     * @param string $renombrada Alias para la tabla renombrada. Este alias se utiliza en la consulta SQL.
     * @param string $tabla Nombre de la tabla base en la consulta.
     * @param string $tabla_enlace Nombre de la tabla relacionada (enlace) para la unión.
     *
     * @return array|string Devuelve la cláusula completa de `JOIN` como una cadena en caso de éxito.
     *                      Si ocurre un error, devuelve un array con los detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $campo_renombrado = 'id_usuario';
     * $campo_tabla_base_id = 'usuario_id';
     * $join = 'LEFT';
     * $renombrada = 'usuarios_renombrados';
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     *
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     * $resultado = $this->genera_join_renombrado(
     *     campo_renombrado: $campo_renombrado,
     *     campo_tabla_base_id: $campo_tabla_base_id,
     *     join: $join,
     *     renombrada: $renombrada,
     *     tabla: $tabla,
     *     tabla_enlace: $tabla_enlace
     * );
     *
<<<<<<< HEAD
     * // Salida esperada
     * // INNER JOIN usuarios AS u ON u.id = roles.id_usuario.id_usuario
     * ```
     *
     * ### Validaciones realizadas:
     * - La función valida que `$tabla`, `$tabla_enlace`, `$campo_renombrado`, `$renombrada` y `$join` no estén vacíos.
     * - Asegura que `$join` sea uno de los valores válidos (`INNER`, `LEFT`, `RIGHT`).
     * - Verifica la estructura de los datos iniciales para evitar inconsistencias.
     *
     * ### Detalles de la generación:
     * - Llama a `init_renombre` para obtener la estructura inicial de las tablas y sus nombres renombrados.
     * - Valida las entradas con `valida_renombres`.
     * - Genera el identificador renombrado con `id_renombrada`.
     * - Construye la cláusula JOIN con `data_for_rename`.
     *
     * ### Ejemplo de integración en un sistema:
     * ```php
     * class GeneradorSQL {
     *     public function genera_query() {
     *         $campo_renombrado = 'id_usuario';
     *         $campo_tabla_base_id = 'id';
     *         $join = 'LEFT';
     *         $renombrada = 'u';
     *         $tabla = 'usuarios';
     *         $tabla_enlace = 'roles.id_usuario';
     *
     *         $query = $this->genera_join_renombrado(
     *             campo_renombrado: $campo_renombrado,
     *             campo_tabla_base_id: $campo_tabla_base_id,
     *             join: $join,
     *             renombrada: $renombrada,
     *             tabla: $tabla,
     *             tabla_enlace: $tabla_enlace
     *         );
     *
     *         echo $query;
     *         // Resultado: " LEFT JOIN usuarios AS u ON u.id = roles.id_usuario.id_usuario"
     *     }
     * }
     * ```
     *
     * ### Posibles errores y sus causas:
     * - **Error al inicializar:** Si `$tabla` o `$tabla_enlace` están vacíos, devuelve un mensaje indicando la ausencia de datos.
     * - **Error en los datos:** Si `$join` contiene un valor no válido (que no sea `INNER`, `LEFT`, `RIGHT`).
     * - **Error en datos de entrada:** Si las claves esperadas no están presentes en los datos de entrada.
     *
     * ### Consideraciones:
     * - Útil para generar dinámicamente consultas SQL con alias y relaciones claras entre tablas.
     * - Facilita la construcción de consultas complejas manteniendo consistencia y validación previa.
=======
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$campo_renombrado`**:
     *   Campo que se usará como clave en la condición `ON`.
     *   Ejemplo: `'id_usuario'`.
     *
     * - **`$campo_tabla_base_id`**:
     *   Campo de la tabla base que se usará para enlazar en la condición `ON`.
     *   Ejemplo: `'usuario_id'`. Si está vacío, se usará `.id` por defecto.
     *
     * - **`$join`**:
     *   Tipo de unión SQL. Puede ser:
     *     - `'INNER'`: Unión estricta.
     *     - `'LEFT'`: Unión que incluye todos los registros de la tabla izquierda.
     *     - `'RIGHT'`: Unión que incluye todos los registros de la tabla derecha.
     *
     * - **`$renombrada`**:
     *   Alias que se asignará a la tabla base. Este alias se utiliza en la consulta para simplificar referencias.
     *   Ejemplo: `'usuarios_renombrados'`.
     *
     * - **`$tabla`**:
     *   Nombre de la tabla base en la consulta SQL.
     *   Ejemplo: `'usuarios'`.
     *
     * - **`$tabla_enlace`**:
     *   Nombre de la tabla relacionada que se usará para enlazar con la tabla base.
     *   Ejemplo: `'roles'`.
     *
     * ### Resultado esperado:
     *
     * - Cláusula `JOIN` completa:
     *   Ejemplo: `' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'`.
     *
     * ### Casos de error:
     *
     * - Si alguno de los parámetros obligatorios está vacío, la función devolverá un array con los detalles del error.
     * - Si el tipo de `JOIN` no es válido (`INNER`, `LEFT`, `RIGHT`), se generará un error.
     * - Si las claves necesarias en `$init` no están presentes (`tabla`, `tabla_enlace`), se generará un error.
     *
     * ### Notas adicionales:
     *
     * - Esta función facilita la generación de consultas SQL complejas con múltiples uniones, asegurando consistencia y validación.
     * - Asegúrate de que las tablas y campos utilizados existan en la base de datos para evitar errores en la ejecución de la consulta.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function genera_join_renombrado(string $campo_renombrado, string $campo_tabla_base_id, string $join,
                                            string $renombrada, string $tabla, string $tabla_enlace):array|string{


        $init = $this->init_renombre(tabla: $tabla, tabla_enlace:$tabla_enlace);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar ', data: $init);
        }

        $valida = (new validaciones())->valida_renombres(campo_renombrado: $campo_renombrado,join:  $join,
            renombrada: $renombrada,tabla:  $init->tabla,
            tabla_enlace:  $init->tabla_enlace);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar ', data: $valida);
        }

        $id_renombrada = $this->id_renombrada(campo_tabla_base_id: $campo_tabla_base_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'El al obtener renombrada ',data:  $id_renombrada);
        }

        $data_rename = $this->data_for_rename(id_renombrada: $id_renombrada,init: $init,join: $join,
            renombrada: $renombrada);
        if(errores::$error){
            return $this->error->error(mensaje: 'El al obtener datos ', data: $data_rename);
        }


        return ' '.$data_rename->asignacion_tabla.' ON '.$data_rename->on_join.'.'.$campo_renombrado;
    }

    /**
     * REG
     * Genera y ajusta la cadena SQL para un JOIN en base a los datos y parámetros proporcionados.
     *
     * Esta función valida los datos de entrada, genera la instrucción SQL para el JOIN y ajusta la cadena
     * final de tablas con la nueva instrucción generada. Se asegura de manejar correctamente alias y
     * renombres de tablas en la instrucción.
     *
     * @param array $data Datos requeridos para construir el JOIN. Debe incluir:
     *  - `key`: Nombre del campo clave en la tabla base.
     *  - `enlace`: Nombre de la tabla con la que se realiza el JOIN.
     *  - `key_enlace`: Nombre del campo clave en la tabla de enlace.
     *  - `renombre` (opcional): Nombre renombrado para la tabla base en el JOIN.
     * @param string $modelo_tabla Nombre de la tabla base que se está validando.
     * @param string $tabla Nombre de la tabla base para el JOIN. No debe estar vacío.
     * @param string $tablas Cadena actual de tablas con las instrucciones SQL generadas previamente.
     *
     * @return array|string Devuelve la cadena SQL actualizada con la nueva instrucción JOIN.
     * En caso de error, retorna un array con los detalles del mismo.
     *
     * @throws errores Si:
     * - `$data` no contiene las claves requeridas (`key`, `enlace`, `key_enlace`).
     * - `$tabla` está vacía.
     * - `$tablas` no puede ser procesado correctamente.
     * - El proceso de generación de la instrucción JOIN falla en algún paso.
     *
     * ### Ejemplos de uso:
     *
     * 1. **Caso exitoso con datos completos**:
     *    ```php
     *    $data = [
     *        'key' => 'id',
     *        'enlace' => 'usuarios',
     *        'key_enlace' => 'usuario_id',
     *        'renombre' => 'usuarios_alias'
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tabla = 'usuarios';
     *    $tablas = 'pedidos AS pedidos';
     *
     *    $resultado = $modelo->join_base(data: $data, modelo_tabla: $modelo_tabla, tabla: $tabla, tablas: $tablas);
     *    // Resultado esperado: "pedidos AS pedidos LEFT JOIN usuarios AS usuarios_alias ON usuarios_alias.id = usuarios.usuario_id"
     *    ```
     *
     * 2. **Caso con tabla vacía**:
     *    ```php
     *    $data = [
     *        'key' => 'id',
     *        'enlace' => 'usuarios',
     *        'key_enlace' => 'usuario_id',
     *        'renombre' => 'usuarios_alias'
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tabla = '';
     *    $tablas = 'pedidos AS pedidos';
     *
     *    $resultado = $modelo->join_base(data: $data, modelo_tabla: $modelo_tabla, tabla: $tabla, tablas: $tablas);
     *    // Resultado esperado: Array con error indicando que $tabla está vacía.
     *    ```
     *
     * 3. **Caso sin clave `key` en `$data`**:
     *    ```php
     *    $data = [
     *        'enlace' => 'usuarios',
     *        'key_enlace' => 'usuario_id',
     *        'renombre' => 'usuarios_alias'
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tabla = 'usuarios';
     *    $tablas = 'pedidos AS pedidos';
     *
     *    $resultado = $modelo->join_base(data: $data, modelo_tabla: $modelo_tabla, tabla: $tabla, tablas: $tablas);
     *    // Resultado esperado: Array con error indicando que falta la clave `key` en $data.
     *    ```
     *
     * ### Proceso de evaluación:
     * 1. Valida que `$data` contenga las claves requeridas utilizando `valida_keys_sql`.
     * 2. Genera el string para el LEFT JOIN si `$tablas` no está vacío.
     * 3. Determina el nombre renombrado de la tabla base utilizando `tabla_renombrada`.
     * 4. Genera la instrucción SQL del JOIN con `string_sql_join`.
     * 5. Ajusta la cadena de `$tablas` agregando el nuevo JOIN generado.
     *
     * ### Resultado esperado:
     * - **SQL JOIN generado**: Una cadena SQL que incluye la instrucción JOIN completa.
     * - **Error**: Array detallando el error encontrado en caso de que alguna validación o proceso falle.
     */

    private function join_base(array $data, string $modelo_tabla, string $tabla, string $tablas): array|string
    {
        $valida = (new validaciones())->valida_keys_sql(data: $data, tabla: $modelo_tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar data',data:  $valida);
        }

        if($tabla === ''){
            return $this->error->error(mensaje:'Error $tabla no puede venir vacia', data:$tabla, es_final: true);
        }

        $left_join = $this->left_join_str(tablas: $tablas);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar join', data:$left_join);
        }

        $tablas.=$left_join;

        $tabla_renombrada = $this->tabla_renombrada(data: $data,tabla:  $tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar tabla_renombrada', data:$tabla_renombrada);
        }

        $str_join = $this->string_sql_join(data:  $data, modelo_tabla: $modelo_tabla, tabla: $tabla,
            tabla_renombrada:  $tabla_renombrada);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar sql', data:$str_join);
        }

        $tablas .= ' '.$str_join;
        return $tablas;
    }


    /**
     * REG
     * Genera un string SQL para realizar un `LEFT JOIN` entre tablas, considerando nombres originales y renombrados.
     * Valida los datos necesarios y aplica los formatos requeridos para construir el `JOIN`.
     *
     * @param array $data Datos para construir el `JOIN`. Debe incluir las claves:
     *  - `enlace`: Nombre de la tabla con la que se realiza el enlace.
     *  - `nombre_original`: Nombre original de la tabla base.
     *  - `key`: Clave primaria o de enlace de la tabla base.
     *  - `key_enlace`: Clave de la tabla con la que se realiza el enlace.
     * @param string $modelo_tabla Nombre de la tabla base del modelo.
     * @param string $tabla_renombrada Nombre renombrado que se asignará a la tabla.
     * @param string $tablas String con la acumulación de tablas y `JOIN` previos.
     *
     * @return array|string Devuelve un string SQL con el `LEFT JOIN` generado si todo es correcto.
     * En caso de error, devuelve un array con los detalles del error.
     *
     * @throws errores Si:
     * - `$data` no contiene las claves requeridas (`enlace`, `nombre_original`, `key`, `key_enlace`).
     * - `$tabla_renombrada` está vacía o no es válida.
     * - `$data['nombre_original']` o `$data['enlace']` están vacíos.
     *
     * ### Ejemplos de uso:
     *
     * 1. **Generación exitosa del `LEFT JOIN`**:
     *    ```php
     *    $data = [
     *        'enlace' => 'tabla_enlace',
     *        'nombre_original' => 'tabla_original',
     *        'key' => 'id',
     *        'key_enlace' => 'tabla_original_id'
     *    ];
     *    $modelo_tabla = 'tabla_modelo';
     *    $tabla_renombrada = 'alias_tabla';
     *    $tablas = '';
     *
     *    $resultado = $modelo->join_renombres(data: $data, modelo_tabla: $modelo_tabla, tabla_renombrada: $tabla_renombrada, tablas: $tablas);
     *    // Resultado esperado:
     *    // " LEFT JOIN tabla_original AS alias_tabla ON alias_tabla.id = tabla_enlace.tabla_original_id"
     *    ```
     *
     * 2. **Error por falta de clave en `$data`**:
     *    ```php
     *    $data = [
     *        'enlace' => 'tabla_enlace',
     *        'key' => 'id'
     *    ];
     *    $modelo_tabla = 'tabla_modelo';
     *    $tabla_renombrada = 'alias_tabla';
     *    $tablas = '';
     *
     *    $resultado = $modelo->join_renombres(data: $data, modelo_tabla: $modelo_tabla, tabla_renombrada: $tabla_renombrada, tablas: $tablas);
     *    // Resultado esperado: Array con error indicando que falta la clave `nombre_original`.
     *    ```
     *
     * 3. **Error por `$tabla_renombrada` vacía**:
     *    ```php
     *    $data = [
     *        'enlace' => 'tabla_enlace',
     *        'nombre_original' => 'tabla_original',
     *        'key' => 'id',
     *        'key_enlace' => 'tabla_original_id'
     *    ];
     *    $modelo_tabla = 'tabla_modelo';
     *    $tabla_renombrada = '';
     *    $tablas = '';
     *
     *    $resultado = $modelo->join_renombres(data: $data, modelo_tabla: $modelo_tabla, tabla_renombrada: $tabla_renombrada, tablas: $tablas);
     *    // Resultado esperado: Array con error indicando que `$tabla_renombrada` no puede estar vacía.
     *    ```
     *
     * ### Proceso de la función:
     * 1. Se valida la estructura de `$data` mediante la función `valida_keys_renombre`.
     * 2. Se eliminan prefijos del namespace (`models\\`) en nombres de tablas si existen.
     * 3. Se genera el string SQL para el `LEFT JOIN` usando los datos validados.
     *
     * ### Resultado esperado:
     * - **Éxito**: String SQL con el `LEFT JOIN` construido.
     * - **Error**: Array con los detalles del error si alguna validación falla.
     */

    private function join_renombres(array $data, string $modelo_tabla, string $tabla_renombrada,
                                    string $tablas): array|string
    {
        $namespace = 'models\\';
        $tabla_renombrada = str_replace($namespace,'',$tabla_renombrada);

        $valida = (new validaciones())->valida_keys_renombre(data:$data,tabla_renombrada:  $tabla_renombrada);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $data['nombre_original'] = trim($data['nombre_original']);
        $tabla_renombrada = trim($tabla_renombrada);

        $data['enlace'] = str_replace($namespace,'',$data['enlace'] );


        $valida = (new validaciones())->valida_keys_sql(data: $data,tabla:  $modelo_tabla);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al validar data',data: $valida);
        }


        $left_join = $this->left_join_str(tablas: $tablas);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al generar join', data:$left_join);
        }

        $tablas.=$left_join;

        $str_join = $this->string_sql_join(data:  $data, modelo_tabla: $modelo_tabla, tabla: $data['nombre_original'],
            tabla_renombrada:  $tabla_renombrada);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al generar sql',data: $str_join);
        }

        $tablas .= ' '.$str_join;
        return $tablas;
    }

    /**
     * REG
<<<<<<< HEAD
     * Inicializa y ajusta los nombres y clases asociadas a dos tablas especificadas. Verifica que los valores
     * proporcionados no estén vacíos y ajusta los nombres de las tablas y sus modelos correspondientes.
     *
     * @param string $tabla Nombre de la tabla base. Debe ser una cadena no vacía.
     * @param string $tabla_enlace Nombre de la tabla de enlace. Debe ser una cadena no vacía.
     *
     * @return stdClass|array Retorna un objeto con los nombres y clases ajustadas si el proceso es exitoso:
     * - **tabla**: Nombre ajustado de la tabla base.
     * - **class**: Nombre completo del modelo asociado a la tabla base.
     * - **tabla_enlace**: Nombre ajustado de la tabla de enlace.
     * - **class_enlace**: Nombre completo del modelo asociado a la tabla de enlace.
     *
     * En caso de error, retorna un array con los detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     *
     * $resultado = $miClase->init_renombre(tabla: $tabla, tabla_enlace: $tabla_enlace);
     *
=======
     * Inicializa los nombres y clases de modelo para las tablas base y de enlace, ajustando su estructura
     * y asegurando que ambos nombres sean válidos.
     *
     * @param string $tabla Nombre de la tabla base que se desea procesar. No puede estar vacío.
     * @param string $tabla_enlace Nombre de la tabla de enlace asociada. No puede estar vacío.
     *
     * @return stdClass|array Retorna un objeto `stdClass` con las siguientes propiedades en caso de éxito:
     *                        - `tabla`: Nombre ajustado de la tabla base.
     *                        - `class`: Nombre completo de la clase modelo para la tabla base.
     *                        - `tabla_enlace`: Nombre ajustado de la tabla de enlace.
     *                        - `class_enlace`: Nombre completo de la clase modelo para la tabla de enlace.
     *                        En caso de error, retorna un array con los detalles del error.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles_usuarios';
     *
     * $resultado = $miClase->init_renombre($tabla, $tabla_enlace);
     *
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     * print_r($resultado);
     * // Resultado esperado:
     * // stdClass Object
     * // (
     * //     [tabla] => usuarios
     * //     [class] => models\usuarios
<<<<<<< HEAD
     * //     [tabla_enlace] => roles
     * //     [class_enlace] => models\roles
=======
     * //     [tabla_enlace] => roles_usuarios
     * //     [class_enlace] => models\roles_usuarios
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     * // )
     * ```
     *
     * ### Ejemplo de error:
     *
<<<<<<< HEAD
     * 1. **Caso:** La tabla base está vacía.
     * ```php
     * $tabla = '';
     * $tabla_enlace = 'roles';
     *
     * $resultado = $miClase->init_renombre(tabla: $tabla, tabla_enlace: $tabla_enlace);
=======
     * - Caso: Uno de los nombres de las tablas está vacío.
     *
     * ```php
     * $tabla = '';
     * $tabla_enlace = 'roles_usuarios';
     *
     * $resultado = $miClase->init_renombre($tabla, $tabla_enlace);
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     *
     * print_r($resultado);
     * // Resultado esperado:
     * // Array
     * // (
     * //     [error] => 1
     * //     [mensaje] => Error tabla no puede venir vacia
     * //     [data] =>
     * // )
     * ```
     *
<<<<<<< HEAD
     * 2. **Caso:** La tabla de enlace está vacía.
     * ```php
     * $tabla = 'usuarios';
     * $tabla_enlace = '';
     *
     * $resultado = $miClase->init_renombre(tabla: $tabla, tabla_enlace: $tabla_enlace);
     *
     * print_r($resultado);
     * // Resultado esperado:
     * // Array
     * // (
     * //     [error] => 1
     * //     [mensaje] => Error $tabla_enlace no puede venir vacia
     * //     [data] =>
     * // )
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$tabla`**:
     *   - Debe ser un nombre válido de tabla como cadena.
     *   - No debe estar vacía o contener solo espacios en blanco.
     *   - Ejemplo válido: `'usuarios'`.
     *   - Ejemplo inválido: `''`.
     *
     * - **`$tabla_enlace`**:
     *   - Debe ser un nombre válido de tabla de enlace como cadena.
     *   - No debe estar vacía o contener solo espacios en blanco.
     *   - Ejemplo válido: `'roles'`.
     *   - Ejemplo inválido: `''`.
=======
     * ### Detalles de los parámetros:
     *
     * - **`$tabla`**:
     *   Nombre de la tabla base. Este valor será ajustado para generar el nombre del modelo.
     *   Ejemplo válido: `'usuarios'`.
     *   Ejemplo inválido: `''` (cadena vacía).
     *
     * - **`$tabla_enlace`**:
     *   Nombre de la tabla de enlace. Este valor será ajustado para generar el nombre del modelo.
     *   Ejemplo válido: `'roles_usuarios'`.
     *   Ejemplo inválido: `''` (cadena vacía).
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     *
     * ### Resultado esperado:
     *
     * - **Éxito**:
<<<<<<< HEAD
     *   Un objeto `stdClass` con los nombres de las tablas y sus clases ajustadas.
     *
     * - **Error**:
     *   Retorna un array con detalles del error si alguno de los parámetros está vacío o si ocurre un error
     *   al ajustar los nombres de los modelos.
     *
     * ### Notas adicionales:
     *
     * - La función utiliza internamente `ajusta_name_models` para ajustar los nombres de las tablas y las clases.
     * - Asegura que los nombres de las tablas no sean cadenas vacías o valores no válidos.
=======
     *   Retorna un objeto `stdClass` con las claves:
     *   - `tabla`: Nombre ajustado de la tabla base.
     *   - `class`: Nombre completo de la clase modelo para la tabla base.
     *   - `tabla_enlace`: Nombre ajustado de la tabla de enlace.
     *   - `class_enlace`: Nombre completo de la clase modelo para la tabla de enlace.
     *
     * - **Error**:
     *   Si alguno de los parámetros está vacío, retorna un array con los detalles del error, incluyendo
     *   un mensaje descriptivo y los datos proporcionados.
     *
     * ### Notas adicionales:
     *
     * - La función utiliza `ajusta_name_models` para procesar los nombres de las tablas y generar los nombres de las clases de modelo.
     * - Está diseñada para asegurar que las tablas base y de enlace estén correctamente estructuradas antes de su uso en otros procesos.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function init_renombre(string $tabla, string $tabla_enlace): stdClass|array
    {
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error tabla no puede venir vacia', data: $tabla,es_final: true);
        }
        $tabla_enlace = trim($tabla_enlace);
        if($tabla_enlace === ''){
            return $this->error->error(mensaje: 'Error $tabla_enlace no puede venir vacia', data: $tabla_enlace,
                es_final: true);
        }

        $data_models = $this->ajusta_name_models(tabla: $tabla, tabla_enlace: $tabla_enlace);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al ajustar nombre del modelo', data: $data_models);
        }

        $data = new stdClass();
        $data->tabla = $data_models->tabla->tabla;
        $data->class = $data_models->tabla->name_model;
        $data->tabla_enlace = $data_models->tabla_enlace->tabla;
        $data->class_enlace = $data_models->tabla_enlace->name_model;
        return $data;
    }

    /**
     * REG
     * Genera la estructura básica de una cláusula `LEFT JOIN` basada en la presencia de una cadena de tablas.
     *
     * Esta función evalúa si la cadena proporcionada en `$tablas` no está vacía. Si contiene información, retorna
     * la cadena `' LEFT JOIN '`. Si está vacía, retorna una cadena vacía.
     *
     * @param string $tablas Cadena de texto que representa tablas o información relacionada.
     *                       Si está vacía o contiene solo espacios en blanco, no genera la cláusula.
     *
     * @return string Retorna la cadena `' LEFT JOIN '` si `$tablas` no está vacía, de lo contrario retorna una cadena vacía.
     *
     * ### Ejemplos de uso:
     *
     * 1. **Caso exitoso con tablas no vacías**:
     *    ```php
     *    $tablas = 'usuarios';
     *
     *    $resultado = $modelo->left_join_str(tablas: $tablas);
     *    // Resultado esperado: ' LEFT JOIN '
     *    ```
     *
     * 2. **Caso con tablas vacías**:
     *    ```php
     *    $tablas = '';
     *
     *    $resultado = $modelo->left_join_str(tablas: $tablas);
     *    // Resultado esperado: ''
     *    ```
     *
     * 3. **Caso con tablas que contienen espacios en blanco**:
     *    ```php
     *    $tablas = '   ';
     *
     *    $resultado = $modelo->left_join_str(tablas: $tablas);
     *    // Resultado esperado: ''
     *    ```
     *
     * ### Proceso de evaluación:
     * 1. Se aplica `trim` a la cadena `$tablas` para eliminar espacios en blanco al inicio y al final.
     * 2. Si la cadena resultante no está vacía, se asigna `' LEFT JOIN '` a `$left_join`.
     * 3. Si la cadena está vacía, `$left_join` se define como una cadena vacía.
     *
     * ### Resultados esperados:
     * - **Cadena no vacía**: Retorna `' LEFT JOIN '`.
     * - **Cadena vacía o solo espacios**: Retorna `''`.
     */

    private function left_join_str(string $tablas): string
    {
        $left_join = '';
        if(trim($tablas) !== '') {
            $left_join =' LEFT JOIN ';
        }
        return $left_join;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera las tablas completas con sus respectivos alias y relaciones JOIN para una consulta SQL.
     *
     * @param array $columnas_join Arreglo que define las relaciones de las tablas (JOINs).
     *                             - Clave: nombre de la tabla base.
     *                             - Valor:
     *                               - Cadena: nombre de la tabla de enlace.
     *                               - Arreglo: detalles específicos para construir el JOIN.
     * @param string $tabla Nombre de la tabla principal que se incluirá en la consulta SQL.
     *                      - La tabla debe ser una cadena no vacía.
     *
     * @return array|string Devuelve una cadena con las tablas y sus relaciones JOIN generadas,
     *                      o un array de error en caso de fallo.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * // Caso con $columnas_join como detalles específicos
     * $columnas_join = [
     *     'usuarios' => [
     *         'tabla_base' => 'usuarios',
     *         'tabla_enlace' => 'roles',
     *         'campo_tabla_base_id' => 'id',
     *         'campo_renombrado' => 'roles_id',
     *     ],
     *     'roles' => 'permisos'
     * ];
     * $tabla = 'usuarios';
     *
     * $resultado = $this->obten_tablas_completas($columnas_join, $tabla);
     * echo $resultado;
     * // Resultado esperado:
     * // 'usuarios AS usuarios LEFT JOIN roles ON usuarios.id = roles.roles_id LEFT JOIN permisos ON roles.id = permisos.roles_id'
     * ```
     *
     * ### Proceso de la función:
     * 1. **Normalización del nombre de la tabla:**
     *    - Elimina el prefijo `models\` del nombre de la tabla.
     *    - Valida que `$tabla` no esté vacía.
     * 2. **Inicialización de la cadena de tablas:**
     *    - Comienza con el nombre de la tabla principal y su alias (`tabla AS tabla`).
     * 3. **Ajuste de tablas con JOINs:**
     *    - Llama a `ajusta_tablas` para generar los JOINs basados en `$columnas_join`.
     * 4. **Validación de errores:**
     *    - Verifica si `ajusta_tablas` produce errores y los maneja adecuadamente.
     * 5. **Retorno del resultado:**
     *    - Devuelve la cadena con las tablas y sus relaciones JOIN generadas.
     *
     * ### Ejemplo de errores:
     * **Error por tabla vacía:**
=======
     * Genera una cadena de tablas completas con alias y sentencias `JOIN` a partir de una tabla base y relaciones definidas.
     *
     * Esta función toma una tabla base y un conjunto de columnas relacionadas (joins) para construir una cadena SQL
     * que incluye la tabla base y las tablas relacionadas mediante sentencias `JOIN`.
     *
     * @param array $columnas_join Array asociativo que define las relaciones entre las tablas.
     *                             La clave es el nombre de la tabla base o relacionada, y el valor es:
     *                             - Un array con los detalles del join (relación).
     *                             - Una cadena con el nombre de la tabla relacionada.
     *                             Ejemplo:
     *                             ```php
     *                             [
     *                                 'usuarios' => [
     *                                     'tabla_base' => 'usuarios',
     *                                     'tabla_enlace' => 'roles'
     *                                 ],
     *                                 'roles' => 'permisos'
     *                             ]
     *                             ```
     * @param string $tabla Nombre de la tabla base para la consulta.
     *                      Se agrega como la tabla inicial con alias.
     *                      Ejemplo: `'usuarios'`.
     *
     * @return array|string Devuelve la cadena de tablas y relaciones formateadas para la consulta SQL.
     *                      Si ocurre un error, devuelve un array con el mensaje descriptivo del problema.
     *
     * ### Ejemplo de uso exitoso:
     *
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     * ```php
     * $columnas_join = [
     *     'usuarios' => [
     *         'tabla_base' => 'usuarios',
<<<<<<< HEAD
     *         'tabla_enlace' => 'roles',
     *         'campo_tabla_base_id' => 'id',
     *         'campo_renombrado' => 'roles_id',
     *     ]
     * ];
     * $tabla = '';
     *
     * $resultado = $this->obten_tablas_completas($columnas_join, $tabla);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'La tabla no puede ir vacia',
     * //     'data' => ''
     * // ]
     * ```
     *
     * **Error por `$columnas_join` mal estructurado:**
     * ```php
     * $columnas_join = [
     *     'usuarios' => [
     *         // Falta la clave 'tabla_base'
     *         'tabla_enlace' => 'roles',
     *         'campo_tabla_base_id' => 'id',
     *         'campo_renombrado' => 'roles_id',
=======
     *         'tabla_enlace' => 'roles'
     *     ],
     *     'roles' => [
     *         'tabla_base' => 'roles',
     *         'tabla_enlace' => 'permisos'
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     *     ]
     * ];
     * $tabla = 'usuarios';
     *
<<<<<<< HEAD
     * $resultado = $this->obten_tablas_completas($columnas_join, $tabla);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error al generar data join',
     * //     'data' => [...]
     * // ]
     * ```
     *
     * ### Casos de uso:
     * - Generación de consultas SQL dinámicas con múltiples tablas y relaciones.
     * - Uso en sistemas que requieren consultas complejas con combinaciones (JOINs).
     *
     * ### Consideraciones:
     * - El arreglo `$columnas_join` debe estar correctamente estructurado.
     * - La función depende de `ajusta_tablas` para construir las relaciones entre tablas.
=======
     * $resultado = $this->obten_tablas_completas(columnas_join: $columnas_join, tabla: $tabla);
     *
     * // Resultado esperado:
     * // 'usuarios AS usuarios
     * //  LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id
     * //  LEFT JOIN roles AS roles ON roles.id = permisos.roles_id'
     * ```
     *
     * ### Casos de uso:
     *
     * 1. **Generar tablas con múltiples relaciones**:
     *    ```php
     *    $columnas_join = [
     *        'usuarios' => [
     *            'tabla_base' => 'usuarios',
     *            'tabla_enlace' => 'roles'
     *        ],
     *        'roles' => [
     *            'tabla_base' => 'roles',
     *            'tabla_enlace' => 'permisos'
     *        ]
     *    ];
     *    $tabla = 'usuarios';
     *
     *    $resultado = $this->obten_tablas_completas(columnas_join: $columnas_join, tabla: $tabla);
     *    // Resultado esperado:
     *    // 'usuarios AS usuarios
     *    //  LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id
     *    //  LEFT JOIN roles AS roles ON roles.id = permisos.roles_id'
     *    ```
     *
     * 2. **Tabla sin relaciones**:
     *    ```php
     *    $columnas_join = [];
     *    $tabla = 'productos';
     *
     *    $resultado = $this->obten_tablas_completas(columnas_join: $columnas_join, tabla: $tabla);
     *    // Resultado esperado:
     *    // 'productos AS productos'
     *    ```
     *
     * ### Validaciones:
     *
     * - Se verifica que `$tabla` no esté vacía.
     * - Se asegura que `$tabla` esté correctamente formateada y no sea numérica.
     * - Se gestiona el ajuste de tablas y relaciones mediante la función `ajusta_tablas`.
     *
     * ### Casos de error:
     *
     * 1. **Tabla vacía**:
     *    ```php
     *    $columnas_join = [];
     *    $tabla = '';
     *
     *    $resultado = $this->obten_tablas_completas(columnas_join: $columnas_join, tabla: $tabla);
     *    // Resultado esperado: array con mensaje de error "La tabla no puede ir vacía".
     *    ```
     *
     * 2. **Error en las relaciones**:
     *    Si ocurre un error en `ajusta_tablas`, se devuelve un mensaje descriptivo con los detalles del problema.
     *
     * ### Resultado esperado:
     * - Devuelve una cadena SQL completa con tablas y relaciones (`JOIN`) para consultas complejas.
     * - Si hay errores, se devuelve un array con el mensaje y los datos relevantes.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    final public function obten_tablas_completas(array $columnas_join, string $tabla):array|string{
        $tabla = str_replace('models\\','',$tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'La tabla no puede ir vacia', data: $tabla, es_final: true);
        }

        $tablas = $tabla.' AS '.$tabla;
        $tablas_join = $columnas_join;

        $tablas = $this->ajusta_tablas(tablas: $tablas, tablas_join: $tablas_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar data join', data: $tablas);
        }
        return $tablas;
    }

    /**
     * REG
     * Genera los `JOIN` SQL para un conjunto de tablas renombradas, asegurando que cada tabla se asocie con los datos
     * proporcionados y se valide correctamente.
     *
     * @param string $modelo_tabla Nombre de la tabla base del modelo, utilizada como referencia para las validaciones.
     * @param array $renombradas Arreglo asociativo con las tablas renombradas y sus datos. Formato esperado:
     *  - Clave: Nombre renombrado de la tabla.
     *  - Valor: Arreglo con los datos necesarios para construir el `JOIN`. Debe incluir:
     *      - `enlace`: Tabla con la que se realiza el enlace.
     *      - `nombre_original`: Nombre original de la tabla base.
     *      - `key`: Clave primaria o de enlace de la tabla base.
     *      - `key_enlace`: Clave de la tabla con la que se realiza el enlace.
     * @param string $tablas String acumulado con los `JOIN` generados previamente.
     *
     * @return array|string Devuelve un string con los `JOIN` generados correctamente si no hay errores.
     * En caso de error, devuelve un array con los detalles del error.
     *
     * @throws errores Si:
     * - `$renombradas` contiene valores que no son arrays.
     * - Alguna de las claves necesarias (`enlace`, `nombre_original`, `key`, `key_enlace`) está ausente en `$renombradas`.
     * - Alguna tabla renombrada tiene valores inválidos o vacíos.
     *
     * ### Ejemplos de uso:
     *
     * 1. **Generación exitosa de `JOIN`**:
     *    ```php
     *    $modelo_tabla = 'tabla_base';
     *    $renombradas = [
     *        'alias_tabla1' => [
     *            'enlace' => 'tabla_enlace1',
     *            'nombre_original' => 'tabla_original1',
     *            'key' => 'id',
     *            'key_enlace' => 'tabla_original1_id'
     *        ],
     *        'alias_tabla2' => [
     *            'enlace' => 'tabla_enlace2',
     *            'nombre_original' => 'tabla_original2',
     *            'key' => 'id',
     *            'key_enlace' => 'tabla_original2_id'
     *        ]
     *    ];
     *    $tablas = '';
     *
     *    $resultado = $modelo->renombres_join(modelo_tabla: $modelo_tabla, renombradas: $renombradas, tablas: $tablas);
     *    // Resultado esperado:
     *    // " LEFT JOIN tabla_original1 AS alias_tabla1 ON alias_tabla1.id = tabla_enlace1.tabla_original1_id
     *    //   LEFT JOIN tabla_original2 AS alias_tabla2 ON alias_tabla2.id = tabla_enlace2.tabla_original2_id"
     *    ```
     *
     * 2. **Error por datos no válidos en `$renombradas`**:
     *    ```php
     *    $modelo_tabla = 'tabla_base';
     *    $renombradas = [
     *        'alias_tabla1' => 'dato_invalido'
     *    ];
     *    $tablas = '';
     *
     *    $resultado = $modelo->renombres_join(modelo_tabla: $modelo_tabla, renombradas: $renombradas, tablas: $tablas);
     *    // Resultado esperado: Array con error indicando que el valor debe ser un array.
     *    ```
     *
     * 3. **Error por falta de clave en `$renombradas`**:
     *    ```php
     *    $modelo_tabla = 'tabla_base';
     *    $renombradas = [
     *        'alias_tabla1' => [
     *            'enlace' => 'tabla_enlace1'
     *        ]
     *    ];
     *    $tablas = '';
     *
     *    $resultado = $modelo->renombres_join(modelo_tabla: $modelo_tabla, renombradas: $renombradas, tablas: $tablas);
     *    // Resultado esperado: Array con error indicando que falta la clave `nombre_original`.
     *    ```
     *
     * ### Proceso de la función:
     * 1. Itera sobre las tablas renombradas proporcionadas en `$renombradas`.
     * 2. Valida que los datos de cada tabla sean un array y contengan las claves requeridas.
     * 3. Genera el `LEFT JOIN` correspondiente utilizando la función `join_renombres`.
     * 4. Acumula el resultado de cada `JOIN` en el string `$tablas`.
     *
     * ### Resultado esperado:
     * - **Éxito**: String SQL con los `JOIN` generados para todas las tablas renombradas.
     * - **Error**: Array con los detalles del error si alguna validación falla.
     */

    private function renombres_join(string $modelo_tabla, array $renombradas, string $tablas): array|string
    {
        $tablas_env = $tablas;
        foreach($renombradas as $tabla_renombrada=>$data){
            if(!is_array($data)){
                return $this->error->error(mensaje: 'Error data debe ser un array', data: $data, es_final: true);
            }
            $tablas_env = $this->join_renombres(data: $data,modelo_tabla: $modelo_tabla,
                tabla_renombrada: $tabla_renombrada, tablas:  $tablas);
            if(errores::$error){
                return $this->error->error(mensaje:'Error al generar join', data:$tablas_env);
            }
            $tablas = (string)$tablas_env;

        }
        return $tablas_env;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera una cláusula SQL de tipo JOIN, ya sea básica o renombrada, para establecer relaciones entre tablas.
     *
     * @param string $campo_renombrado Nombre del campo a utilizar como clave renombrada en la relación (ejemplo: `id_usuario`).
     * @param string $campo_tabla_base_id Nombre del campo de ID en la tabla base. Si está vacío, se utiliza `.id` por defecto.
     * @param string $renombrada Alias que se asignará a la tabla relacionada. Si está vacío, no se aplica un alias.
     * @param string $tabla Nombre de la tabla base para la relación.
     * @param string $tabla_enlace Nombre de la tabla de enlace con la que se relaciona la tabla base.
     *
     * @return array|string Devuelve la cláusula SQL generada como una cadena o un array con detalles del error en caso de fallos.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * // Caso 1: Generar un JOIN con tabla renombrada
     * $campo_renombrado = 'id_usuario';
     * $campo_tabla_base_id = 'id';
     * $renombrada = 'u';
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     *
     * $resultado = $this->sql_join(
     *     campo_renombrado: $campo_renombrado,
     *     campo_tabla_base_id: $campo_tabla_base_id,
     *     renombrada: $renombrada,
     *     tabla: $tabla,
     *     tabla_enlace: $tabla_enlace
     * );
     *
     * // Resultado esperado:
     * // " LEFT JOIN usuarios AS u ON u.id = roles.id_usuario"
     *
     * // Caso 2: Generar un JOIN sin alias renombrado
     * $campo_renombrado = '';
     * $campo_tabla_base_id = '';
     * $renombrada = '';
     * $tabla = 'productos';
     * $tabla_enlace = 'categorias';
     *
=======
     * Genera una cláusula SQL `JOIN` para la construcción de consultas con alias y condiciones de unión.
     *
     * Esta función permite crear una sentencia `LEFT JOIN` con la posibilidad de agregar un alias
     * (`renombrada`) para la tabla base. Valida los parámetros y genera el SQL correspondiente, ya sea
     * renombrado o con el nombre original de la tabla.
     *
     * @param string $campo_renombrado Campo que será utilizado como clave en la condición `ON` del `JOIN`.
     *                                  Ejemplo: `'id_usuario'`.
     * @param string $campo_tabla_base_id Campo de la tabla base que será referenciado en la condición `ON`.
     *                                     Por defecto se utiliza `'id'` si este parámetro está vacío.
     * @param string $renombrada Alias para renombrar la tabla base en la consulta. Si está vacío,
     *                           no se aplica un alias.
     * @param string $tabla Nombre de la tabla base que se utilizará en el `JOIN`.
     * @param string $tabla_enlace Nombre de la tabla relacionada (enlace) que se unirá con la tabla base.
     *
     * @return array|string Devuelve la cláusula completa de `JOIN` como una cadena en caso de éxito.
     *                      Si ocurre un error, retorna un array con los detalles del error.
     *
     * ### Ejemplo de uso exitoso con tabla renombrada:
     *
     * ```php
     * $campo_renombrado = 'id_usuario';
     * $campo_tabla_base_id = 'usuario_id';
     * $renombrada = 'usuarios_renombrados';
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     *
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     * $resultado = $this->sql_join(
     *     campo_renombrado: $campo_renombrado,
     *     campo_tabla_base_id: $campo_tabla_base_id,
     *     renombrada: $renombrada,
     *     tabla: $tabla,
     *     tabla_enlace: $tabla_enlace
     * );
<<<<<<< HEAD
     *
     * // Resultado esperado:
     * // " LEFT JOIN productos AS productos ON productos.id = categorias.productos_id"
     * ```
     *
     * ### Validaciones realizadas:
     * - La función verifica que `$tabla` y `$tabla_enlace` no estén vacíos.
     * - Si `$renombrada` no está vacío, se llama a la función `genera_join_renombrado` para construir el JOIN con alias.
     * - Si `$renombrada` está vacío, se genera un JOIN básico utilizando el nombre de la tabla directamente.
     *
     * ### Detalles de la generación:
     * - Utiliza el tipo de JOIN `LEFT` de forma predeterminada.
     * - Si se proporciona un alias renombrado (`$renombrada`), se genera una cláusula JOIN con alias.
     * - En caso contrario, se genera un JOIN sin alias, utilizando el nombre de la tabla base y la tabla de enlace.
     *
     * ### Ejemplo de integración en un sistema:
     * ```php
     * class GeneradorSQL {
     *     public function genera_query() {
     *         $sql = $this->sql_join(
     *             campo_renombrado: 'id_usuario',
     *             campo_tabla_base_id: 'id',
     *             renombrada: 'u',
     *             tabla: 'usuarios',
     *             tabla_enlace: 'roles'
     *         );
     *         echo $sql;
     *         // Resultado: " LEFT JOIN usuarios AS u ON u.id = roles.id_usuario"
     *     }
     * }
     * ```
     *
     * ### Posibles errores y sus causas:
     * - **Error `$tabla` vacía:** Si `$tabla` está vacía, la función devuelve un mensaje indicando la ausencia de este parámetro.
     * - **Error `$tabla_enlace` vacía:** Si `$tabla_enlace` está vacía, se notifica el error con un mensaje descriptivo.
     * - **Error en generación de SQL:** Si ocurre un error en la generación del SQL renombrado con `genera_join_renombrado`, se devuelve el error correspondiente.
     *
     * ### Consideraciones:
     * - Esta función es útil para generar dinámicamente relaciones entre tablas, tanto básicas como con alias personalizados.
     * - Facilita la creación de consultas SQL complejas con validación previa de los parámetros.
=======
     *
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'
     * ```
     *
     * ### Ejemplo de uso exitoso sin renombrar la tabla:
     *
     * ```php
     * $campo_renombrado = 'id_usuario';
     * $campo_tabla_base_id = 'usuario_id';
     * $renombrada = ''; // No se aplica alias
     * $tabla = 'usuarios';
     * $tabla_enlace = 'roles';
     *
     * $resultado = $this->sql_join(
     *     campo_renombrado: $campo_renombrado,
     *     campo_tabla_base_id: $campo_tabla_base_id,
     *     renombrada: $renombrada,
     *     tabla: $tabla,
     *     tabla_enlace: $tabla_enlace
     * );
     *
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$campo_renombrado`**:
     *   Especifica el campo utilizado como clave en la condición `ON`.
     *   Ejemplo: `'id_usuario'`.
     *
     * - **`$campo_tabla_base_id`**:
     *   Campo de la tabla base que será referenciado en la condición `ON`.
     *   Ejemplo: `'usuario_id'`. Si está vacío, se asume como `'id'`.
     *
     * - **`$renombrada`**:
     *   Alias de la tabla base para la consulta. Si está vacío, no se aplica un alias.
     *   Ejemplo: `'usuarios_renombrados'`.
     *
     * - **`$tabla`**:
     *   Nombre de la tabla base que participa en el `JOIN`.
     *   Ejemplo: `'usuarios'`.
     *
     * - **`$tabla_enlace`**:
     *   Nombre de la tabla relacionada (enlace) que se une con la tabla base.
     *   Ejemplo: `'roles'`.
     *
     * ### Casos de error:
     *
     * - Si `$tabla` o `$tabla_enlace` están vacíos, se genera un error indicando que estos parámetros son obligatorios.
     * - Si ocurre un problema durante la generación del `JOIN` renombrado, se devuelve un array con los detalles del error.
     *
     * ### Resultado esperado:
     *
     * - La función genera una cláusula `LEFT JOIN` con las condiciones `ON` especificadas.
     * - Ejemplo con alias:
     *   `' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'`.
     * - Ejemplo sin alias:
     *   `' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'`.
     *
     * ### Notas adicionales:
     *
     * - La función es flexible y soporta tanto consultas simples como aquellas que requieren alias para tablas.
     * - Es ideal para construir consultas SQL dinámicas y evitar errores manuales al escribir cláusulas `JOIN`.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */
    private function sql_join(string $campo_renombrado, string $campo_tabla_base_id, string $renombrada, string $tabla,
                              string $tabla_enlace): array|string
    {
        $join = 'LEFT';
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error $tabla esta vacia',data:  $tabla, es_final: true);
        }
        $tabla_enlace = trim($tabla_enlace);
        if($tabla_enlace === ''){
            return $this->error->error(mensaje: 'Error $tabla_enlace esta vacia', data: $tabla_enlace, es_final: true);
        }

        if($renombrada !==''){
            $sql = $this->genera_join_renombrado(campo_renombrado: $campo_renombrado,
                campo_tabla_base_id: $campo_tabla_base_id,join: $join, renombrada: $renombrada,tabla: $tabla,
                tabla_enlace: $tabla_enlace);
            if(errores::$error ){
                return $this->error->error(mensaje: 'Error al generar sql', data: $sql);
            }
        }
        else {

            $sql = ' '.$join.' JOIN ' . $tabla . ' AS ' . $tabla . ' ON ' . $tabla . '.id = ' . $tabla_enlace . '.'
                . $tabla . '_id';
        }

        return $sql;
    }

    /**
     * REG
     * Genera una cadena SQL para una instrucción JOIN basada en los datos proporcionados.
     *
     * Esta función construye una cadena SQL para una instrucción JOIN utilizando una tabla base, una tabla renombrada,
     * y los datos que definen las claves y enlaces para la relación entre las tablas.
     *
     * @param array $data Array que debe contener las claves `key`, `enlace` y `key_enlace` para definir la relación de las tablas.
     * @param string $modelo_tabla Nombre de la tabla del modelo que está siendo validado.
     * @param string $tabla Nombre de la tabla base. No debe estar vacío ni ser numérico.
     * @param string $tabla_renombrada Nombre renombrado de la tabla. No debe estar vacío ni ser numérico.
     *
     * @return string|array Devuelve una cadena SQL con la estructura del JOIN. En caso de error, retorna un array con los detalles del mismo.
     *
     * @throws errores Si:
     * - `$tabla` o `$tabla_renombrada` están vacíos.
     * - `$tabla` o `$tabla_renombrada` son valores numéricos.
     * - `$data` no contiene las claves requeridas (`key`, `enlace`, `key_enlace`).
     *
     * ### Ejemplos de uso:
     *
     * 1. **Caso exitoso con datos válidos**:
     *    ```php
     *    $data = [
     *        'key' => 'id',
     *        'enlace' => 'usuarios',
     *        'key_enlace' => 'usuario_id'
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tabla = 'usuarios';
     *    $tabla_renombrada = 'usuarios_alias';
     *
     *    $resultado = $modelo->string_sql_join(data: $data, modelo_tabla: $modelo_tabla, tabla: $tabla, tabla_renombrada: $tabla_renombrada);
     *    // Resultado esperado: "usuarios AS usuarios_alias ON usuarios_alias.id = usuarios.usuario_id"
     *    ```
     *
     * 2. **Caso con tabla vacía**:
     *    ```php
     *    $data = [
     *        'key' => 'id',
     *        'enlace' => 'usuarios',
     *        'key_enlace' => 'usuario_id'
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tabla = '';
     *    $tabla_renombrada = 'usuarios_alias';
     *
     *    $resultado = $modelo->string_sql_join(data: $data, modelo_tabla: $modelo_tabla, tabla: $tabla, tabla_renombrada: $tabla_renombrada);
     *    // Resultado esperado: Array con error indicando que $tabla no puede estar vacía.
     *    ```
     *
     * 3. **Caso con datos faltantes en `$data`**:
     *    ```php
     *    $data = [
     *        'enlace' => 'usuarios',
     *        'key_enlace' => 'usuario_id'
     *    ];
     *    $modelo_tabla = 'pedidos';
     *    $tabla = 'usuarios';
     *    $tabla_renombrada = 'usuarios_alias';
     *
     *    $resultado = $modelo->string_sql_join(data: $data, modelo_tabla: $modelo_tabla, tabla: $tabla, tabla_renombrada: $tabla_renombrada);
     *    // Resultado esperado: Array con error indicando que falta la clave `key` en $data.
     *    ```
     *
     * ### Proceso de evaluación:
     * 1. Valida que `$data` contenga las claves necesarias (`key`, `enlace`, `key_enlace`).
     * 2. Limpia los valores de `$tabla` y `$tabla_renombrada` utilizando `trim`.
     * 3. Verifica que `$tabla` y `$tabla_renombrada` no estén vacíos ni sean valores numéricos.
     * 4. Construye y retorna la cadena SQL para el JOIN.
     *
     * ### Resultado esperado:
     * - **SQL JOIN generado**: Retorna la cadena SQL con la estructura `"$tabla AS $tabla_renombrada ON $tabla_renombrada.$data[key] = $data[enlace].$data[key_enlace]"`.
     * - **Error**: Devuelve un array con el detalle del error si alguno de los parámetros no cumple con las validaciones.
     */

    private function string_sql_join( array $data, string $modelo_tabla, string $tabla,
                                      string $tabla_renombrada): string|array
    {
        $valida = (new validaciones())->valida_keys_sql(data:$data, tabla: $modelo_tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar data', data: $valida);
        }
        $tabla = trim($tabla);
        $tabla_renombrada = trim($tabla_renombrada);

        if($tabla === ''){
            return $this->error->error(mensaje:'Error $tabla no puede venir vacia', data:$tabla, es_final: true);
        }
        if($tabla_renombrada === ''){
            return $this->error->error(mensaje:'Error $tabla_renombrada no puede venir vacia', data:$tabla_renombrada,
                es_final: true);
        }

        if(is_numeric($tabla)){
            return $this->error->error(mensaje:'Error $tabla debe ser un texto', data:$tabla, es_final: true);
        }
        if(is_numeric($tabla_renombrada)){
            return $this->error->error(mensaje:'Error $tabla debe ser un texto', data:$tabla, es_final: true);
        }

        return "$tabla AS $tabla_renombrada  ON $tabla_renombrada.$data[key] = $data[enlace].$data[key_enlace]";
    }

    /**
     * REG
     * Determina el nombre de una tabla renombrada a partir de los datos proporcionados.
     *
     * Esta función evalúa si se ha definido un nombre alternativo para una tabla mediante el índice `renombre` en el array `$data`.
     * Si existe y no está vacío, usa este nombre como el renombrado de la tabla. En caso contrario, utiliza el nombre original de `$tabla`.
     *
     * @param array $data Array de datos que puede contener el índice `renombre` con el nombre alternativo de la tabla.
     * @param string $tabla Nombre original de la tabla. Este valor no debe estar vacío.
     *
     * @return string|array Retorna el nombre de la tabla renombrada como cadena de texto. Si ocurre un error, retorna un array
     *                      con los detalles del mismo.
     *
     * @throws errores Si `$tabla` está vacío, retorna un error indicando que `$tabla` no puede estar vacío.
     *
     * ### Ejemplos de uso:
     *
     * 1. **Caso exitoso con renombre definido**:
     *    ```php
     *    $data = ['renombre' => 'usuarios_alias'];
     *    $tabla = 'usuarios';
     *
     *    $resultado = $modelo->tabla_renombrada(data: $data, tabla: $tabla);
     *    // Resultado esperado: 'usuarios_alias'
     *    ```
     *
     * 2. **Caso exitoso sin renombre definido**:
     *    ```php
     *    $data = [];
     *    $tabla = 'usuarios';
     *
     *    $resultado = $modelo->tabla_renombrada(data: $data, tabla: $tabla);
     *    // Resultado esperado: 'usuarios'
     *    ```
     *
     * 3. **Caso con tabla vacía**:
     *    ```php
     *    $data = ['renombre' => 'usuarios_alias'];
     *    $tabla = '';
     *
     *    $resultado = $modelo->tabla_renombrada(data: $data, tabla: $tabla);
     *    // Resultado esperado: Array con el error indicando que $tabla está vacía.
     *    ```
     *
     * ### Proceso de evaluación:
     * 1. Se limpia `$tabla` utilizando `trim` para eliminar espacios en blanco al inicio y al final.
     * 2. Si `$tabla` está vacío después de la limpieza, se retorna un error.
     * 3. Si `$data` contiene el índice `renombre` y su valor no está vacío después de aplicar `trim`, se utiliza como el nombre renombrado.
     * 4. Si no, se utiliza `$tabla` como el nombre de la tabla.
     *
     * ### Resultados esperados:
     * - **Renombre definido**: Retorna el valor de `$data['renombre']`.
     * - **Sin renombre definido**: Retorna el valor de `$tabla`.
     * - **Tabla vacía**: Retorna un error indicando que `$tabla` no puede estar vacía.
     */

    private function tabla_renombrada(array $data, string $tabla): string|array
    {
        $tabla = trim($tabla);

        if($tabla === ''){
            return $this->error->error(mensaje:'Error $tabla esta vacia', data:$tabla, es_final: true);
        }
        $tabla_renombrada = $tabla;
        if(isset($data['renombre'])){
            $data['renombre'] = trim($data['renombre']);
            if($data['renombre'] !== ''){
                $tabla_renombrada = $data['renombre'];
            }
        }
        return trim($tabla_renombrada);

    }

    /**
     * REG
     * Genera la cadena SQL con todas las tablas y `JOIN` necesarios para una consulta, incluyendo extensiones
     * estructurales, `JOIN` adicionales y tablas renombradas.
     *
     * @param array $columnas Columnas base necesarias para generar los `JOIN` principales.
     * @param array $extension_estructura Extensiones estructurales adicionales que requieren `JOIN`. Cada elemento
     * debe ser un array con las claves necesarias para definir los datos del `JOIN`.
     * @param array $extra_join Configuración adicional para `JOIN` externos, en el mismo formato que `$extension_estructura`.
     * @param string $modelo_tabla Nombre de la tabla base del modelo. Usada como referencia para las validaciones.
     * @param array $renombradas Tablas renombradas que requieren `JOIN`. Cada clave es el nombre renombrado y el valor es un
     * array con los datos necesarios, incluyendo:
     *  - `enlace`: Tabla con la que se realiza el enlace.
     *  - `nombre_original`: Nombre original de la tabla.
     *  - `key`: Clave primaria o de enlace de la tabla base.
     *  - `key_enlace`: Clave de la tabla enlazada.
     * @param string $tabla Nombre de la tabla principal que será incluida en la consulta.
     *
     * @return array|string Devuelve un string con la cadena de tablas y `JOIN` generados correctamente si no hay errores.
     * En caso de error, devuelve un array con los detalles del error.
     *
     * @throws errores Si:
     * - `$tabla` está vacía.
     * - Alguna de las extensiones o tablas renombradas contiene datos inválidos o vacíos.
     *
     * ### Ejemplos de uso:
     *
     * 1. **Generación exitosa de tablas y `JOIN`**:
     *    ```php
     *    $columnas = ['columna1', 'columna2'];
     *    $extension_estructura = [
     *        'tabla_extension' => [
     *            'enlace' => 'tabla_enlace',
     *            'key' => 'id',
     *            'key_enlace' => 'tabla_extension_id'
     *        ]
     *    ];
     *    $extra_join = [];
     *    $modelo_tabla = 'modelo_base';
     *    $renombradas = [
     *        'tabla_renombrada' => [
     *            'enlace' => 'tabla_enlace',
     *            'nombre_original' => 'tabla_original',
     *            'key' => 'id',
     *            'key_enlace' => 'tabla_original_id'
     *        ]
     *    ];
     *    $tabla = 'tabla_principal';
     *
     *    $resultado = $modelo->tablas(
     *        columnas: $columnas,
     *        extension_estructura: $extension_estructura,
     *        extra_join: $extra_join,
     *        modelo_tabla: $modelo_tabla,
     *        renombradas: $renombradas,
     *        tabla: $tabla
     *    );
     *    // Resultado esperado:
     *    // "tabla_principal AS tabla_principal
     *    //  LEFT JOIN tabla_extension ON tabla_extension.id = tabla_enlace.tabla_extension_id
     *    //  LEFT JOIN tabla_original AS tabla_renombrada ON tabla_renombrada.id = tabla_enlace.tabla_original_id"
     *    ```
     *
     * 2. **Error por tabla vacía**:
     *    ```php
     *    $tabla = '';
     *    $resultado = $modelo->tablas(
     *        columnas: $columnas,
     *        extension_estructura: $extension_estructura,
     *        extra_join: $extra_join,
     *        modelo_tabla: $modelo_tabla,
     *        renombradas: $renombradas,
     *        tabla: $tabla
     *    );
     *    // Resultado esperado: Array con error indicando que la tabla está vacía.
     *    ```
     *
     * 3. **Error por datos inválidos en extensiones**:
     *    ```php
     *    $extension_estructura = [
     *        'tabla_extension' => 'dato_invalido'
     *    ];
     *    $resultado = $modelo->tablas(
     *        columnas: $columnas,
     *        extension_estructura: $extension_estructura,
     *        extra_join: $extra_join,
     *        modelo_tabla: $modelo_tabla,
     *        renombradas: $renombradas,
     *        tabla: $tabla
     *    );
     *    // Resultado esperado: Array con error indicando que los datos de la extensión deben ser un array.
     *    ```
     *
     * ### Proceso de la función:
     * 1. Valida que `$tabla` no esté vacía.
     * 2. Obtiene las tablas completas y los `JOIN` básicos con `obten_tablas_completas`.
     * 3. Agrega extensiones estructurales y `JOIN` adicionales con `extensiones_join`.
     * 4. Procesa las tablas renombradas con `renombres_join`.
     * 5. Devuelve la cadena SQL completa con las tablas y `JOIN`.
     *
     * ### Resultado esperado:
     * - **Éxito**: String SQL con la cadena de tablas y `JOIN`.
     * - **Error**: Array con detalles del error si alguna validación falla.
     */

    final public function tablas(array $columnas, array $extension_estructura, array $extra_join, string $modelo_tabla,
                                 array $renombradas, string $tabla): array|string
    {
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'La tabla no puede ir vacia',data:  $tabla, es_final: true);
        }
        $tablas = $this->obten_tablas_completas(columnas_join:  $columnas, tabla: $tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tablas',data:  $tablas);
        }

        $tablas = $this->extensiones_join(extension_estructura: $extension_estructura, modelo_tabla: $modelo_tabla,
            tablas:  $tablas);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar join',data:  $tablas);
        }

        $tablas = $this->extensiones_join(extension_estructura: $extra_join, modelo_tabla: $modelo_tabla,
            tablas:  $tablas);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar join',data:  $tablas);
        }

        $tablas = $this->renombres_join(modelo_tabla:$modelo_tabla,renombradas: $renombradas, tablas: $tablas);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar join', data: $tablas);
        }
        return $tablas;
    }


    /**
     * REG
<<<<<<< HEAD
     * Genera y agrega una cláusula SQL JOIN basada en la estructura proporcionada en `$tabla_join` a la cadena `$tablas`.
     *
     * @param array $tabla_join Array que contiene la información necesaria para construir el JOIN.
     *                          Debe incluir las claves:
     *                          - `tabla_base`: Nombre de la tabla base.
     *                          - `tabla_enlace`: Nombre de la tabla de enlace.
     *                          Opcionalmente puede incluir:
     *                          - `tabla_renombrada`: Alias para la tabla base en el SQL.
     *                          - `campo_tabla_base_id`: Campo en la tabla base que se utiliza en la relación.
     *                          - `campo_renombrado`: Campo renombrado utilizado en la relación.
     * @param string $tablas Cadena que contiene las cláusulas SQL JOIN acumuladas. Se agregará el nuevo JOIN generado.
     *
     * @return array|string Retorna la cadena `$tablas` con el JOIN agregado en caso de éxito,
     *                      o un array con el detalle del error si ocurre algún problema.
     *
     * ### Ejemplo de uso exitoso:
     * ```php
     * $tabla_join = [
     *     'tabla_base' => 'usuarios',
     *     'tabla_enlace' => 'roles',
     *     'tabla_renombrada' => 'u',
     *     'campo_tabla_base_id' => 'id',
     *     'campo_renombrado' => 'usuario_id'
     * ];
     *
     * $tablas = '';
     * $tablas = $this->tablas_join_base($tabla_join, $tablas);
     *
     * // Resultado esperado:
     * // " LEFT JOIN usuarios AS u ON u.id = roles.usuario_id"
     * ```
     *
     * ### Validaciones realizadas:
     * 1. **Validación de `$tabla_join`:**
     *    - Verifica que el array `$tabla_join` contenga las claves requeridas `tabla_base` y `tabla_enlace`.
     *    - Si faltan claves o los valores no son válidos, retorna un error.
     * 2. **Generación del JOIN:**
     *    - Llama a `data_para_join` para construir la cláusula SQL JOIN con los datos proporcionados.
     * 3. **Acumulación de la cláusula JOIN:**
     *    - Agrega el resultado del JOIN generado a la cadena `$tablas`.
     *
     * ### Detalles de la implementación:
     * - **Validación de claves requeridas:** La función utiliza `valida_existencia_keys` para asegurarse de que las claves necesarias están presentes en `$tabla_join`.
     * - **Uso de `data_para_join`:** Llama a esta función para generar la cláusula JOIN basada en los datos validados.
     * - **Concatenación de JOIN:** El JOIN generado se agrega a `$tablas` para construir una cadena acumulativa de cláusulas JOIN.
     *
     * ### Ejemplo de integración en un sistema:
     * ```php
     * class GeneradorSQL {
     *     public function genera_query() {
     *         $tabla_join = [
     *             'tabla_base' => 'productos',
     *             'tabla_enlace' => 'categorias',
     *             'tabla_renombrada' => 'p',
     *             'campo_tabla_base_id' => 'id',
     *             'campo_renombrado' => 'producto_id'
     *         ];
     *
     *         $tablas = '';
     *         $tablas = $this->tablas_join_base($tabla_join, $tablas);
     *         echo $tablas;
     *         // Resultado: " LEFT JOIN productos AS p ON p.id = categorias.producto_id"
     *     }
     * }
     * ```
     *
     * ### Posibles errores y sus causas:
     * - **Faltan claves en `$tabla_join`:** Si no están presentes las claves `tabla_base` o `tabla_enlace`, se genera un error.
     * - **Error en `data_para_join`:** Si no puede generarse correctamente el JOIN debido a datos inconsistentes o inválidos.
     *
     * ### Consideraciones:
     * - Asegúrate de que `$tabla_join` esté correctamente estructurado para evitar errores.
     * - Útil para construir dinámicamente relaciones SQL JOIN entre tablas con validaciones estrictas.
=======
     * Genera y agrega la cláusula SQL necesaria para un `JOIN` entre tablas a partir de los datos proporcionados.
     *
     * Esta función valida la configuración de las tablas involucradas en un `JOIN`, genera la estructura del `JOIN`
     * y la concatena con las tablas previamente procesadas en la consulta SQL.
     *
     * @param array $tabla_join Configuración de las tablas que participan en el `JOIN`. Este array debe contener:
     *                          - `'tabla_base'`: Nombre de la tabla principal. Ejemplo: `'usuarios'`.
     *                          - `'tabla_enlace'`: Nombre de la tabla relacionada. Ejemplo: `'roles'`.
     *                          - `'tabla_renombrada'` (opcional): Alias para la tabla principal. Ejemplo: `'usuarios_renombrados'`.
     *                          - `'campo_tabla_base_id'` (opcional): Campo de la tabla principal usado en la cláusula `ON`.
     *                            Ejemplo: `'usuario_id'`.
     *                          - `'campo_renombrado'` (opcional): Campo clave de la tabla de enlace para el `JOIN`.
     *                            Ejemplo: `'id_usuario'`.
     * @param string $tablas Una cadena que contiene las tablas ya procesadas en la consulta SQL. La cláusula generada
     *                       será concatenada a esta cadena.
     *
     * @return array|string Una cadena con las tablas actualizadas incluyendo el nuevo `JOIN`, o un array con los detalles
     *                      del error si ocurre alguna falla.
     *
     * ### Ejemplo de uso exitoso:
     *
     * ```php
     * $tabla_join = array(
     *     'tabla_base' => 'usuarios',
     *     'tabla_enlace' => 'roles',
     *     'tabla_renombrada' => 'usuarios_renombrados',
     *     'campo_tabla_base_id' => 'usuario_id',
     *     'campo_renombrado' => 'id_usuario'
     * );
     * $tablas = '';
     *
     * $resultado = $this->tablas_join_base(tabla_join: $tabla_join, tablas: $tablas);
     *
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$tabla_join`**:
     *   Un array que define las configuraciones necesarias para construir la cláusula `JOIN`.
     *   Ejemplo:
     *   ```php
     *   array(
     *       'tabla_base' => 'usuarios',
     *       'tabla_enlace' => 'roles',
     *       'tabla_renombrada' => 'usuarios_renombrados',
     *       'campo_tabla_base_id' => 'usuario_id',
     *       'campo_renombrado' => 'id_usuario'
     *   );
     *   ```
     * - **`$tablas`**:
     *   Una cadena que contiene las tablas o `JOINs` previamente generados en la consulta SQL.
     *   Ejemplo:
     *   ```php
     *   'SELECT * FROM clientes LEFT JOIN direcciones ON clientes.id = direcciones.cliente_id'
     *   ```
     *
     * ### Casos de error:
     *
     * - Si el array `$tabla_join` no contiene las claves `'tabla_base'` o `'tabla_enlace'`, se devuelve un error indicando
     *   que dichas claves son obligatorias.
     * - Si ocurre un problema al generar la estructura del `JOIN` en la función `data_para_join`, se devuelve un array con
     *   los detalles del error.
     *
     * ### Resultado esperado:
     *
     * - Devuelve una cadena con las tablas y la nueva cláusula `JOIN` concatenada.
     *   Ejemplo:
     *   ```sql
     *   ' LEFT JOIN usuarios AS usuarios_renombrados ON usuarios_renombrados.usuario_id = roles.id_usuario'
     *   ```
     *
     * ### Notas adicionales:
     *
     * - Esta función es útil para construir consultas SQL dinámicas que manejan relaciones entre múltiples tablas.
     * - La validación previa asegura que los datos proporcionados cumplen con los requisitos mínimos para evitar errores en
     *   la generación del SQL.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function tablas_join_base(array $tabla_join, string $tablas): array|string
    {
        $keys = array('tabla_base','tabla_enlace');
        $valida = $this->validacion->valida_existencia_keys(keys:$keys, registro: $tabla_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar $tabla_join',data: $valida);
        }

        $data = $this->data_para_join(tabla_join: $tabla_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar data join', data: $data);
        }
        $tablas .=  $data;
        return $tablas;
    }

    /**
     * REG
<<<<<<< HEAD
     * Genera y agrega un SQL JOIN entre dos tablas a una cadena de tablas de consulta SQL.
     *
     * @param string $key Nombre de la tabla base del JOIN.
     *                    - Debe ser una cadena no vacía.
     *                    - No puede ser un número.
     * @param string $tabla_join Nombre de la tabla que se unirá en el JOIN.
     *                           - Debe ser una cadena no vacía.
     *                           - No puede ser un número.
     * @param string $tablas Cadena acumulativa que contiene los JOINs generados previamente.
     *
     * @return array|string Devuelve la cadena acumulativa con el nuevo JOIN añadido, o un array de error si falla alguna validación.
     *
     * ### Ejemplo de uso exitoso:
=======
     * Genera y agrega una sentencia SQL de tipo `JOIN` a una cadena de tablas existentes.
     *
     * Esta función valida las tablas proporcionadas (`key` y `tabla_join`) y genera una sentencia SQL `JOIN`.
     * Posteriormente, concatena esta sentencia a la cadena de tablas existente.
     *
     * @param string $key Nombre o alias de la tabla base para el `JOIN`.
     *                    Debe ser una cadena no vacía y no numérica.
     *                    Ejemplo: `'usuarios'`.
     * @param string $tabla_join Nombre de la tabla de enlace utilizada en el `JOIN`.
     *                           Debe ser una cadena no vacía y no numérica.
     *                           Ejemplo: `'roles'`.
     * @param string $tablas Cadena de tablas ya generadas, a la que se añadirá el nuevo `JOIN`.
     *                       Puede ser una cadena vacía inicial o contener una lista de tablas ya procesadas.
     *
     * @return array|string Devuelve la cadena actualizada de tablas con la sentencia `JOIN` añadida si no hay errores.
     *                      En caso de error, devuelve un array con los detalles del problema.
     *
     * ### Ejemplo de uso exitoso:
     *
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     * ```php
     * $key = 'usuarios';
     * $tabla_join = 'roles';
     * $tablas = '';
     *
<<<<<<< HEAD
     * $resultado = $this->tablas_join_esp($key, $tabla_join, $tablas);
     *
     * if (is_string($resultado)) {
     *     echo 'JOINs generados: ' . $resultado;
     * } else {
     *     print_r($resultado); // Mostrará el detalle del error si ocurre.
     * }
     * // Resultado esperado:
     * // JOINs generados: ' LEFT JOIN roles ON usuarios.id = roles.usuarios_id'
     * ```
     *
     * ### Proceso de la función:
     * 1. **Validación de parámetros (`key` y `tabla_join`):**
     *    - Se asegura de que ambos parámetros sean cadenas no vacías y no numéricas.
     *    - Utiliza la función `valida_tabla_join` para realizar estas validaciones.
     * 2. **Generación del JOIN:**
     *    - Llama a la función `data_para_join_esp` para construir el JOIN entre las tablas especificadas.
     * 3. **Acumulación del JOIN:**
     *    - Si el JOIN se genera correctamente, se concatena con la cadena `$tablas`.
     *
     * ### Ejemplo de errores:
     * **Error por `$key` vacío:**
     * ```php
     * $key = '';
     * $tabla_join = 'roles';
     * $tablas = '';
     *
     * $resultado = $this->tablas_join_esp($key, $tabla_join, $tablas);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error key esta vacio',
     * //     'data' => ''
     * // ]
     * ```
     *
     * **Error por `$tabla_join` numérico:**
     * ```php
     * $key = 'usuarios';
     * $tabla_join = '123';
     * $tablas = '';
     *
     * $resultado = $this->tablas_join_esp($key, $tabla_join, $tablas);
     * print_r($resultado);
     * // Resultado esperado:
     * // [
     * //     'error' => 1,
     * //     'mensaje' => 'Error el $tabla_join no puede ser un numero',
     * //     'data' => '123'
     * // ]
     * ```
     *
     * ### Casos de uso:
     * - Construcción dinámica de consultas SQL con múltiples JOINs.
     * - Validación robusta de los nombres de tablas antes de generar la consulta.
     *
     * ### Consideraciones:
     * - El parámetro `$tablas` debe contener previamente cualquier otro JOIN acumulado.
     * - Los nombres de las tablas deben coincidir con los definidos en la base de datos para evitar errores de sintaxis.
     *
     * ### Detalles de implementación:
     * - Llama a `valida_tabla_join` para garantizar la validez de los nombres de las tablas.
     * - Utiliza `data_para_join_esp` para obtener el JOIN generado dinámicamente.
     * - Concatena el resultado del JOIN con la cadena `$tablas`.
=======
     * $resultado = $this->tablas_join_esp(key: $key, tabla_join: $tabla_join, tablas: $tablas);
     *
     * // Resultado esperado:
     * // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     * ```
     *
     * ### Detalles de los parámetros:
     *
     * - **`$key`**:
     *   Nombre o alias de la tabla base utilizada en el `JOIN`.
     *   Requisitos:
     *   - Debe ser una cadena no vacía.
     *   - No puede ser numérico.
     *   Ejemplo válido:
     *   ```php
     *   'usuarios'
     *   ```
     *   Ejemplo inválido:
     *   ```php
     *   ''  // Está vacío.
     *   123 // Es numérico.
     *   ```
     *
     * - **`$tabla_join`**:
     *   Nombre de la tabla de enlace que participa en el `JOIN`.
     *   Requisitos:
     *   - Debe ser una cadena no vacía.
     *   - No puede ser numérico.
     *   Ejemplo válido:
     *   ```php
     *   'roles'
     *   ```
     *   Ejemplo inválido:
     *   ```php
     *   ''   // Está vacío.
     *   456  // Es numérico.
     *   ```
     *
     * - **`$tablas`**:
     *   Cadena de tablas generadas anteriormente.
     *   Se concatenará con la nueva sentencia SQL `JOIN`.
     *   Ejemplo inicial:
     *   ```php
     *   '' // Cadena vacía para una nueva construcción.
     *   ```
     *   Ejemplo con datos:
     *   ```php
     *   ' INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id'
     *   ```
     *
     * ### Casos de uso exitoso:
     *
     * 1. Generar un `JOIN` entre las tablas `'usuarios'` y `'roles'` con una cadena inicial vacía:
     *    ```php
     *    $key = 'usuarios';
     *    $tabla_join = 'roles';
     *    $tablas = '';
     *
     *    $resultado = $this->tablas_join_esp(key: $key, tabla_join: $tabla_join, tablas: $tablas);
     *
     *    // Resultado esperado:
     *    // ' LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     *    ```
     *
     * 2. Agregar un nuevo `JOIN` a una cadena existente:
     *    ```php
     *    $key = 'usuarios';
     *    $tabla_join = 'roles';
     *    $tablas = ' INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id';
     *
     *    $resultado = $this->tablas_join_esp(key: $key, tabla_join: $tabla_join, tablas: $tablas);
     *
     *    // Resultado esperado:
     *    // ' INNER JOIN departamentos ON usuarios.departamento_id = departamentos.id
     *    //   LEFT JOIN usuarios AS usuarios ON usuarios.id = roles.usuarios_id'
     *    ```
     *
     * ### Casos de error:
     *
     * 1. **`$key` está vacío**:
     *    ```php
     *    $resultado = $this->tablas_join_esp(key: '', tabla_join: 'roles', tablas: '');
     *    // Resultado esperado: array con mensaje de error "Error key esta vacio".
     *    ```
     *
     * 2. **`$tabla_join` está vacío**:
     *    ```php
     *    $resultado = $this->tablas_join_esp(key: 'usuarios', tabla_join: '', tablas: '');
     *    // Resultado esperado: array con mensaje de error "Error $tabla_join esta vacio".
     *    ```
     *
     * 3. **Error al generar el `JOIN`**:
     *    Si ocurre un error en la generación del `JOIN`, se devuelve un array con detalles del problema.
     *
     * ### Resultado esperado:
     * - Devuelve una cadena con las tablas y el nuevo `JOIN` añadido correctamente.
     * - En caso de error, devuelve un array con los detalles y el mensaje del problema.
     *
     * ### Notas adicionales:
     * - Valida las tablas con `valida_tabla_join`.
     * - Genera la sentencia `JOIN` mediante `data_para_join_esp`.
>>>>>>> 49a610360774f77119bfa2ab68481482a093b2ee
     */

    private function tablas_join_esp(string $key, string $tabla_join, string $tablas): array|string
    {
        $key = trim($key);
        $tabla_join = trim($tabla_join);

        $valida = (new validaciones())->valida_tabla_join(key: $key, tabla_join: $tabla_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar join', data: $valida);
        }
        $data = $this->data_para_join_esp(key: $key, tabla_join: $tabla_join);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar join',data:  $data);
        }
        $tablas .=  $data;
        return $tablas;
    }
}