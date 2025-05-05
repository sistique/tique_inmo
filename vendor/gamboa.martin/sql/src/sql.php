<?php
namespace gamboamartin\src;

use gamboamartin\errores\errores;

/**
 * REG
 * Clase principal para la construcción y validación de sentencias SQL simples.
 *
 * Esta clase ofrece métodos que facilitan la generación de fragmentos de una consulta SQL,
 * particularmente para la cláusula `IN` (por ejemplo, `"columna IN ('valor1','valor2')"`)
 * y para la normalización de espacios en una cadena.
 *
 * ### Responsabilidad Principal
 * - **Generación de Cláusulas `IN`:**
 *   El método {@see sql::in()} construye la cláusula `IN`, previa validación de coherencia entre la
 *   llave (columna) y los valores a incluir.
 *
 * - **Validación de Coherencia (`IN`):**
 *   El método {@see sql::valida_in()} confirma que, si la llave no está vacía, los valores no
 *   pueden estarlo y viceversa, evitando situaciones inconsistentes en la sentencia SQL.
 *
 * - **Limpieza de Espacios Múltiples:**
 *   El método {@see sql::limpia_espacios_dobles()} se encarga de eliminar espacios duplicados
 *   en iteraciones sucesivas, útil para asegurar que la sentencia final quede correctamente
 *   formateada.
 *
 * ### Uso Típico
 * 1. **Crear instancia de la clase `sql`:**
 *    ```php
 *    $sqlHelper = new sql();
 *    ```
 * 2. **Generar valores SQL formateados** (por ejemplo, `'1','2','3'`) y una llave (columna).
 * 3. **Llamar al método `in()` para obtener la cláusula IN completa**, o verificar su coherencia con `valida_in()`.
 *
 * ### Ejemplo de Flujo
 * ```php
 * use gamboamartin\src\sql;
 *
 * // Crear la instancia
 * $sqlBuilder = new sql();
 *
 * // Definir la columna y los valores (normalmente ya escapados o validados)
 * $llave = "usuario_id";
 * $values = "'10','20','30'";
 *
 * // Generar la cláusula
 * $clausulaIn = $sqlBuilder->in($llave, $values);
 *
 * // Resultado esperado: "usuario_id IN ('10','20','30')"
 * ```
 *
 * @package gamboamartin\src
 */
class sql
{
    /**
     * Utilidad para el manejo de errores en la aplicación.
     *
     * @var errores
     */
    private errores $error;

    /**
     * Constructor.
     *
     * Inicializa la instancia de {@see errores} para gestionar eventuales errores
     * que surjan durante la validación o construcción de sentencias SQL.
     */
    public function __construct()
    {
        $this->error = new errores();
    }

    /**
     * REG
     * Construye una parte de cláusula SQL `IN` en base a una llave (columna) y un conjunto de valores,
     * validando antes la consistencia de ambos parámetros.
     *
     * 1. Llama a `valida_in($llave, $values_sql)` para asegurar que:
     *    - Si `$llave` tiene información, `$values_sql` no debe estar vacío, y viceversa.
     * 2. Si la validación pasa, genera la expresión `"$llave IN ($values_sql)"`, siempre y cuando `$values_sql` no sea `''`.
     * 3. Limpia posibles espacios dobles en la cadena resultante con `limpia_espacios_dobles()`.
     *
     * - Si la validación falla en algún paso, se retorna un arreglo de error con los detalles
     *   (resultado de `$this->error->error()`).
     * - Si todo es correcto, retorna un string con la cláusula `IN` (o una cadena vacía si `$values_sql` está vacío).
     *
     * @param string $llave      Nombre de la columna a la que se aplicará la cláusula IN.
     * @param string $values_sql Cadena con los valores ya formateados para un IN, por ejemplo: "'1','2','3'".
     *
     * @return string|array Retorna:
     *   - Un `string` con la cláusula `"llave IN ('1','2','3')"` si `$values_sql` tiene datos.
     *   - Un `string` vacío si `$values_sql` está vacío.
     *   - Un `array` de error si la validación falla.
     *
     * @example
     *  Ejemplo 1: Uso con datos válidos
     *  ------------------------------------------------------------------------------------
     *  $llave = "usuario_id";
     *  $values_sql = "'10','20','30'";
     *
     *  $resultado = $this->in($llave, $values_sql);
     *  // $resultado será "usuario_id IN ('10','20','30')"
     *
     * @example
     *  Ejemplo 2: Llave vacía y valores vacíos
     *  ------------------------------------------------------------------------------------
     *  $llave = "";
     *  $values_sql = "";
     *
     *  // Dado que no hay información en ninguno de los dos, la validación pasa:
     *  // y se retorna una cadena vacía, pues no se construye la cláusula IN.
     *  $resultado = $this->in($llave, $values_sql);
     *  // $resultado => ""
     *
     * @example
     *  Ejemplo 3: Llave con contenido y valores vacíos (falla)
     *  ------------------------------------------------------------------------------------
     *  $llave = "usuario_id";
     *  $values_sql = "";
     *
     *  // valida_in detecta que si hay info en la llave, deben existir valores
     *  // Retorna un arreglo con información del error.
     *  $resultado = $this->in($llave, $values_sql);
     *  // [
     *  //   'error'   => 1,
     *  //   'mensaje' => 'Error si llave tiene info values debe tener info',
     *  //   'data'    => 'usuario_id',
     *  //   ...
     *  // ]
     */
    final public function in(string $llave, string $values_sql): string|array
    {
        // Valida la consistencia de llave y values_sql
        $valida = $this->valida_in(llave: $llave, values_sql: $values_sql);
        if(errores::$error){
            return $this->error->error(
                mensaje: 'Error al validar in',
                data: $valida
            );
        }

        $in_sql = '';
        // Si $values_sql no está vacío, construye la cláusula IN
        if($values_sql !== ''){
            $in_sql .= "$llave IN ($values_sql)";
        }

        // Limpia espacios dobles en la cadena resultante
        $in_sql = $this->limpia_espacios_dobles(txt: $in_sql);
        if(errores::$error){
            return $this->error->error(
                mensaje: 'Error al limpiar sql',
                data: $in_sql
            );
        }

        return $in_sql;
    }

    /**
     * REG
     * Reemplaza los espacios dobles en una cadena por espacios sencillos, iterando hasta reducirlos a uno.
     *
     * - Utiliza un bucle `while` que se ejecuta hasta `$n_iteraciones` veces (por defecto, 10).
     * - En cada iteración, busca y reemplaza secuencias de dos espacios (`"  "`) por uno (`" "`).
     * - Si la cadena contiene demasiados espacios consecutivos, el ciclo repetirá el proceso hasta limpiar
     *   la mayoría de ellos. Aunque no garantiza la eliminación de secuencias extremadamente largas, usualmente
     *   10 iteraciones son suficientes para la mayoría de los casos.
     *
     * @param string $txt           Cadena a limpiar.
     * @param int    $n_iteraciones Número máximo de iteraciones que realizará el bucle para reemplazar
     *                              espacios dobles por sencillos. Por defecto 10.
     *
     * @return string Retorna la misma cadena `$txt` tras haber reemplazado los espacios dobles
     *                hasta que no queden más (o hasta agotar el número de iteraciones).
     *
     * @example
     *  // Ejemplo 1: Cadena con varios espacios consecutivos
     *  ----------------------------------------------------------------------------
     *  $texto = "Este   es   un   texto   con   espacios   extras";
     *  $resultado = $this->limpia_espacios_dobles($texto);
     *  // $resultado podría ser "Este es un texto con espacios extras".
     *
     * @example
     *  // Ejemplo 2: Ajustar el número de iteraciones
     *  ----------------------------------------------------------------------------
     *  // Si se sospecha que hay demasiados espacios o se desea mayor control, se puede
     *  // aumentar el valor de $n_iteraciones.
     *  $texto = "Muchos       espacios             aquí";
     *  $resultado = $this->limpia_espacios_dobles($texto, 20);
     *  // Se limpia la mayoría de los espacios, retornando algo más uniforme.
     */
    private function limpia_espacios_dobles(string $txt, int $n_iteraciones = 10): string
    {
        $iteracion = 0;
        while ($iteracion <= $n_iteraciones){
            $txt = str_replace('  ', ' ', $txt);
            $iteracion++;
        }
        return $txt;
    }

    /**
     * REG
     * Valida la coherencia entre una llave (`$llave`) y un conjunto de valores (`$values_sql`) para un filtro `IN`:
     *
     * - Si `$llave` no está vacío, entonces `$values_sql` **debe** tener contenido (no puede estar vacío).
     * - Si `$values_sql` no está vacío, entonces `$llave` **debe** tener contenido (no puede estar vacío).
     *
     * Esto asegura que, si se indica una llave para el filtro, haya valores, y viceversa.
     * Si alguna de las condiciones no se cumple, se genera un error a través de `$this->error->error()` y
     * se retorna un arreglo con el detalle.
     *
     * @param string $llave      Nombre de la llave o columna a filtrar (por ejemplo "usuario_id").
     * @param string $values_sql Cadena con los valores que se usarían en una cláusula IN (por ejemplo "'10','20','30'").
     *
     * @return bool|array Retorna:
     *   - `true` si la coherencia es correcta.
     *   - Un arreglo de error si las validaciones fallan (e.g., `$llave` está lleno pero `$values_sql` vacío, o viceversa).
     *
     * @example
     *  Ejemplo 1: Ambos datos vacíos
     *  --------------------------------------------------------------------
     *  $resultado = $this->valida_in("", "");
     *  // $resultado será true, ya que ninguno requiere la presencia del otro.
     *
     * @example
     *  Ejemplo 2: Llave con contenido, pero sin valores
     *  --------------------------------------------------------------------
     *  $resultado = $this->valida_in("usuario_id", "");
     *  // Se retornará un arreglo de error indicando que si `$llave` tiene info, `$values_sql` no puede estar vacío.
     *
     * @example
     *  Ejemplo 3: Valores con contenido, pero sin llave
     *  --------------------------------------------------------------------
     *  $resultado = $this->valida_in("", "'1','2','3'");
     *  // Se retornará un arreglo de error indicando que si `$values_sql` tiene info, `$llave` no puede estar vacío.
     *
     * @example
     *  Ejemplo 4: Ambos con contenido
     *  --------------------------------------------------------------------
     *  $resultado = $this->valida_in("usuario_id", "'1','2','3'");
     *  // $resultado será true.
     */
    final public function valida_in(string $llave, string $values_sql): bool|array
    {
        $llave = trim($llave);
        $values_sql = trim($values_sql);

        // Si la llave tiene contenido, pero $values_sql está vacío, es un error
        if ($llave !== '') {
            if ($values_sql === '') {
                return $this->error->error(
                    mensaje: 'Error: si la llave tiene contenido, $values_sql no puede estar vacío',
                    data: $llave,
                    es_final: true
                );
            }
        }

        // Si $values_sql tiene contenido, pero la llave está vacía, es un error
        if ($values_sql !== '') {
            if ($llave === '') {
                return $this->error->error(
                    mensaje: 'Error: si $values_sql tiene contenido, la llave no puede estar vacía',
                    data: $values_sql,
                    es_final: true
                );
            }
        }

        return true;
    }
}
