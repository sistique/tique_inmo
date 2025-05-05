<?php
namespace gamboamartin\documento\models;
use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;


class doc_tipo_documento extends _modelo_parent{ //FINALIZADAS
    /**
     * DEBUG INI
     * accion constructor.
     * @param PDO $link
     */
    public function __construct(PDO $link){
        $tabla = 'doc_tipo_documento';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        $doc_documento_etapa = "(SELECT pr_etapa.descripcion FROM pr_etapa 
            LEFT JOIN pr_etapa_proceso ON pr_etapa_proceso.pr_etapa_id = pr_etapa.id 
            LEFT JOIN doc_documento_etapa ON doc_documento_etapa.pr_etapa_proceso_id = pr_etapa_proceso.id 
            LEFT JOIN doc_documento ON doc_documento_etapa.doc_documento_id = doc_documento.id 
			WHERE doc_documento.doc_tipo_documento_id = doc_tipo_documento.id ORDER BY doc_documento_etapa.id DESC LIMIT 1)";

        $columnas_extra['doc_etapa'] = "IFNULL($doc_documento_etapa,'SIN ETAPA')";

        $columnas_extra['doc_tipo_documento_n_permisos'] = /** @lang sql */
            "(SELECT COUNT(*) FROM doc_acl_tipo_documento 
            WHERE doc_acl_tipo_documento.doc_tipo_documento_id = doc_tipo_documento.id)";

        $columnas_extra['doc_tipo_documento_n_documentos'] = /** @lang sql */
            "(SELECT COUNT(*) FROM doc_documento 
            WHERE doc_documento.doc_tipo_documento_id = doc_tipo_documento.id)";

        $columnas_extra['doc_tipo_documento_n_extensiones'] = /** @lang sql */
            "(SELECT COUNT(*) FROM doc_extension_permitido 
            WHERE doc_extension_permitido.doc_tipo_documento_id = doc_tipo_documento.id)";

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra);
        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Documento';

        $this->id_code = true;

    }

    /**
     * REG
     * Verifica si una extensión está permitida para un tipo de documento.
     *
     * Esta función recibe una extensión y un listado de extensiones permitidas, y verifica si la extensión proporcionada
     * está dentro de la lista de extensiones permitidas.
     *
     * ### Flujo de ejecución:
     * 1. **Validación de la extensión:**
     *    - Si `$extension` está vacía, retorna un error.
     * 2. **Recorre la lista de extensiones permitidas:**
     *    - Compara `$extension` con el valor de `doc_extension_descripcion` en `$extensiones_permitidas`.
     *    - Si encuentra una coincidencia, retorna `true` y detiene la búsqueda.
     * 3. **Si no encuentra coincidencia, retorna `false`.**
     *
     * @param string $extension Extensión a verificar (sin el punto inicial, ej. `"pdf"`).
     * @param array $extensiones_permitidas Lista de extensiones permitidas en formato de array asociativo.
     *
     * @return bool|array Retorna `true` si la extensión está permitida, `false` si no lo está.
     * En caso de error, retorna un array con el mensaje del problema.
     *
     * ### Ejemplos de uso:
     *
     * #### Ejemplo 1: Extensión permitida
     * **Entrada:**
     * ```php
     * $extensiones_permitidas = [
     *     ["doc_extension_descripcion" => "pdf"],
     *     ["doc_extension_descripcion" => "docx"],
     *     ["doc_extension_descripcion" => "xlsx"]
     * ];
     * $resultado = es_extension_permitida("pdf", $extensiones_permitidas);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * #### Ejemplo 2: Extensión no permitida
     * **Entrada:**
     * ```php
     * $resultado = es_extension_permitida("exe", $extensiones_permitidas);
     * ```
     * **Salida esperada:**
     * ```php
     * false
     * ```
     *
     * #### Ejemplo 3: Extensión vacía
     * **Entrada:**
     * ```php
     * $resultado = es_extension_permitida("", $extensiones_permitidas);
     * ```
     * **Salida esperada (error):**
     * ```php
     * [
     *     "error" => true,
     *     "mensaje" => "Error extension no puede venir vacio",
     *     "data" => "",
     *     "es_final" => true
     * ]
     * ```
     *
     * ### Notas:
     * - La extensión debe ingresarse sin el punto inicial (ej. `"pdf"`, no `".pdf"`).
     * - Si `$extensiones_permitidas` está vacío, la función siempre retornará `false`.
     * - Si `$extension` está vacía, la función retornará un error.
     *
     * @throws errores Si `$extension` está vacía.
     * @version 6.4.0
     */

    private function es_extension_permitida(string $extension, array $extensiones_permitidas): bool|array
    {
        if($extension === '') {
            return $this->error->error(mensaje: 'Error extension no puede venir vacio', data: $extension,
                es_final: true);
        }

        $es_extension_permitida = false;
        foreach ($extensiones_permitidas as $extension_permitida){
            if($extension_permitida['doc_extension_descripcion'] === $extension){
                $es_extension_permitida = true;
                break;
            }
        }

        return  $es_extension_permitida;
    }

    /**
     * REG
     * Obtiene todas las extensiones permitidas para un tipo de documento específico.
     *
     * Esta función consulta la base de datos para recuperar las extensiones permitidas asociadas a un tipo de documento.
     * Si el ID del tipo de documento es inválido (menor o igual a 0), se genera un error.
     *
     * ### Flujo de ejecución:
     * 1. **Validación del ID de tipo de documento:**
     *    - Si `$tipo_documento_id` es menor o igual a 0, retorna un error.
     * 2. **Generación del filtro de búsqueda:**
     *    - Se crea un array de filtro para buscar extensiones asociadas al tipo de documento.
     * 3. **Consulta de extensiones permitidas en la base de datos:**
     *    - Se utiliza la clase `doc_extension_permitido` para filtrar los registros.
     *    - Si ocurre un error en la consulta, se retorna un mensaje de error.
     * 4. **Retorno de las extensiones encontradas.**
     *
     * @param int $tipo_documento_id ID del tipo de documento para el cual se desean obtener las extensiones permitidas.
     *
     * @return array Retorna un array con las extensiones permitidas para el tipo de documento.
     * En caso de error, retorna un array con el mensaje del problema.
     *
     * ### Ejemplos de uso:
     *
     * #### Ejemplo 1: Obtener extensiones para un tipo de documento válido
     * **Entrada:**
     * ```php
     * $resultado = extensiones_permitidas(5);
     * ```
     * **Salida esperada:**
     * ```php
     * [
     *     ["id" => 1, "doc_extension_descripcion" => "pdf"],
     *     ["id" => 2, "doc_extension_descripcion" => "docx"]
     * ]
     * ```
     *
     * #### Ejemplo 2: ID de tipo de documento inválido (<= 0)
     * **Entrada:**
     * ```php
     * $resultado = extensiones_permitidas(0);
     * ```
     * **Salida esperada (error):**
     * ```php
     * [
     *     "error" => true,
     *     "mensaje" => "Error tipo_documento_id debe ser mayor a 0",
     *     "data" => 0
     * ]
     * ```
     *
     * #### Ejemplo 3: Tipo de documento sin extensiones asociadas
     * **Entrada:**
     * ```php
     * $resultado = extensiones_permitidas(10);
     * ```
     * **Salida esperada:**
     * ```php
     * []
     * ```
     *
     * ### Notas:
     * - Si el tipo de documento no tiene extensiones asociadas, retorna un array vacío sin errores.
     * - La función usa `doc_extension_permitido->filtro_and()` para consultar la base de datos.
     * - Se recomienda validar previamente el ID del tipo de documento antes de llamar a esta función.
     *
     * @throws errores Si el ID del tipo de documento es inválido o hay un error en la consulta.
     * @version 3.6.0
     */

    private function extensiones_permitidas(int $tipo_documento_id): array
    {
        if($tipo_documento_id<=0){
            return $this->error->error(mensaje: 'Error tipo_documento_id debe ser mayor a 0', data: $tipo_documento_id);
        }
        $filtro['doc_tipo_documento.id'] = $tipo_documento_id;

        $extension_permitido = (new doc_extension_permitido($this->link))->filtro_and(filtro: $filtro);
        if(errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener extensiones', data: $extension_permitido);
        }

        return $extension_permitido->registros;
    }

    /**
     * REG
     * Valida si una extensión es permitida para un tipo de documento específico.
     *
     * Esta función verifica si la extensión proporcionada está dentro de las extensiones permitidas
     * para un determinado tipo de documento. Primero, obtiene la lista de extensiones permitidas
     * y luego verifica si la extensión ingresada se encuentra en esa lista.
     *
     * ### Flujo de ejecución:
     * 1. **Validación de parámetros:**
     *    - Si `$tipo_documento_id` es menor o igual a 0, retorna un error.
     *    - Si `$extension` está vacía, retorna un error.
     * 2. **Obtiene la lista de extensiones permitidas:**
     *    - Se llama a `extensiones_permitidas($tipo_documento_id)`.
     *    - Si la obtención falla, se retorna un error.
     * 3. **Verifica si la extensión está permitida:**
     *    - Se llama a `es_extension_permitida($extension, $extensiones_permitidas)`.
     *    - Si la verificación falla, se retorna un error.
     * 4. **Retorna `true` si la extensión está permitida o `false` si no lo está.**
     *
     * @param string $extension Extensión a verificar (sin el punto inicial, ej. `"pdf"`).
     * @param int $tipo_documento_id ID del tipo de documento al que pertenece la extensión.
     *
     * @return bool|array Retorna `true` si la extensión es válida, `false` si no lo es.
     * En caso de error, retorna un array con el mensaje del problema.
     *
     * ### Ejemplos de uso:
     *
     * #### Ejemplo 1: Validar una extensión permitida
     * **Entrada:**
     * ```php
     * $resultado = valida_extension_permitida("pdf", 5);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * #### Ejemplo 2: Validar una extensión no permitida
     * **Entrada:**
     * ```php
     * $resultado = valida_extension_permitida("exe", 5);
     * ```
     * **Salida esperada:**
     * ```php
     * false
     * ```
     *
     * #### Ejemplo 3: Tipo de documento inválido
     * **Entrada:**
     * ```php
     * $resultado = valida_extension_permitida("pdf", 0);
     * ```
     * **Salida esperada (error):**
     * ```php
     * [
     *     "error" => true,
     *     "mensaje" => "Error tipo_documento_id debe ser mayor a 0",
     *     "data" => 0,
     *     "es_final" => true
     * ]
     * ```
     *
     * #### Ejemplo 4: Extensión vacía
     * **Entrada:**
     * ```php
     * $resultado = valida_extension_permitida("", 5);
     * ```
     * **Salida esperada (error):**
     * ```php
     * [
     *     "error" => true,
     *     "mensaje" => "Error extension no puede venir vacio",
     *     "data" => "",
     *     "es_final" => true
     * ]
     * ```
     *
     * ### Notas:
     * - La extensión debe ingresarse sin el punto inicial (ej. `"pdf"`, no `".pdf"`).
     * - Si `$tipo_documento_id` es inválido, la función devuelve un error.
     * - Si `$extension` está vacía, la función devuelve un error.
     * - Utiliza `extensiones_permitidas()` para recuperar las extensiones asociadas al tipo de documento.
     * - Utiliza `es_extension_permitida()` para verificar si la extensión ingresada está en la lista de permitidas.
     *
     * @throws errores Si los parámetros son inválidos o hay un problema al obtener las extensiones permitidas.
     * @version 6.4.0
     */

    final public function valida_extension_permitida(string $extension, int $tipo_documento_id): bool|array
    {
        if($tipo_documento_id<=0){
            return $this->error->error(mensaje: 'Error tipo_documento_id debe ser mayor a 0', data: $tipo_documento_id,
                es_final: true);
        }
        if($extension === '') {
            return $this->error->error(mensaje: 'Error extension no puede venir vacio', data: $extension,
                es_final: true);
        }

        $extensiones_permitidas = $this->extensiones_permitidas(tipo_documento_id: $tipo_documento_id);
        if(errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener extensiones', data: $extensiones_permitidas);
        }

        $es_extension_permitida = $this->es_extension_permitida(extension: $extension,
            extensiones_permitidas: $extensiones_permitidas);
        if(errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener extensiones', data: $es_extension_permitida);
        }

        return $es_extension_permitida;
    }
}