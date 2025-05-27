<?php
namespace gamboamartin\src;
use gamboamartin\validacion\validacion;


class validaciones extends validacion{

    /**
     * REG
     * Valida la data del filtro especial proporcionado para un campo específico.
     *
     * Este método se encarga de comprobar que el arreglo de filtro contenga la información necesaria para
     * la validación de un filtro especial asociado a un campo. En particular, realiza las siguientes verificaciones:
     *
     * 1. Verifica que el parámetro `$campo` no sea una cadena vacía. Si está vacío, retorna un error.
     * 2. Si el campo es numérico y no está definido el indicador `'valor_es_campo'` en el filtro, se considera
     *    un error, ya que se espera que el campo sea un string.
     * 3. Comprueba que en el arreglo `$filtro[$campo]` exista la clave `'operador'`. Si no existe, retorna un error.
     * 4. Si la clave `'valor'` no está definida en `$filtro[$campo]`, se asigna automáticamente una cadena vacía.
     * 5. Verifica que el valor asociado a `$filtro[$campo]['valor']` no sea un array, ya que se espera un dato escalar.
     *
     * Si todas las condiciones se cumplen, el método retorna `true`. En caso contrario, utiliza el método
     * `$this->error->error()` para retornar un array con los detalles del error.
     *
     * @param string $campo  El nombre del campo a validar en el filtro. Este valor se usará como clave en el arreglo `$filtro`.
     * @param array  $filtro El arreglo que contiene la configuración del filtro especial. Se espera que tenga una estructura similar a:
     *                       ```php
     *                       [
     *                           'nombre_del_campo' => [
     *                               'valor_es_campo' => (bool),   // Opcional: indica si el valor es un campo (true) o un dato (false)
     *                               'operador'       => (string), // Operador de comparación, por ejemplo: '=', '>', '<'
     *                               'valor'          => (mixed)   // Valor a comparar (se espera un dato escalar)
     *                           ]
     *                       ]
     *                       ```
     *
     * @return true|array Devuelve `true` si la validación es correcta. En caso de error, retorna un array con
     *                    información detallada del error (utilizando `$this->error->error()`).
     *
     * @example Ejemplo 1: Validación exitosa
     * ```php
     * // Supongamos que se quiere validar el filtro para el campo 'precio'
     * $campo = 'precio';
     * $filtro = [
     *     'precio' => [
     *         'valor_es_campo' => false,
     *         'operador' => '>',
     *         'valor' => 100
     *     ]
     * ];
     *
     * $resultado = $obj->valida_data_filtro_especial($campo, $filtro);
     * // Resultado esperado: true
     * ```
     *
     * @example Ejemplo 2: Error por campo vacío
     * ```php
     * $campo = '';
     * $filtro = [
     *     'precio' => [
     *         'valor_es_campo' => false,
     *         'operador' => '>',
     *         'valor' => 100
     *     ]
     * ];
     *
     * $resultado = $obj->valida_data_filtro_especial($campo, $filtro);
     * // Resultado esperado: Array de error indicando "Error campo vacio"
     * ```
     *
     * @example Ejemplo 3: Error por falta de operador
     * ```php
     * $campo = 'precio';
     * $filtro = [
     *     'precio' => [
     *         // Falta la clave 'operador'
     *         'valor_es_campo' => false,
     *         'valor' => 100
     *     ]
     * ];
     *
     * $resultado = $obj->valida_data_filtro_especial($campo, $filtro);
     * // Resultado esperado: Array de error indicando "Error debe existir $filtro[campo][operador]"
     * ```
     *
     * @example Ejemplo 4: Asignación de valor por defecto
     * ```php
     * $campo = 'precio';
     * $filtro = [
     *     'precio' => [
     *         'valor_es_campo' => false,
     *         'operador' => '<',
     *         // La clave 'valor' no está definida, se asignará como ""
     *     ]
     * ];
     *
     * $resultado = $obj->valida_data_filtro_especial($campo, $filtro);
     * // Resultado esperado: true y $filtro['precio']['valor'] queda establecido como ""
     * ```
     *
     * @example Ejemplo 5: Error si el valor es un array
     * ```php
     * $campo = 'precio';
     * $filtro = [
     *     'precio' => [
     *         'valor_es_campo' => false,
     *         'operador' => '=',
     *         'valor' => [100, 200] // Error: se espera un dato escalar
     *     ]
     * ];
     *
     * $resultado = $obj->valida_data_filtro_especial($campo, $filtro);
     * // Resultado esperado: Array de error indicando que "$filtro['precio']['valor'] debe ser un dato"
     * ```
     */
    final public function valida_data_filtro_especial(string $campo, array $filtro): true|array
    {
        if ($campo === '') {
            return $this->error->error(
                mensaje: "Error campo vacio",
                data: $campo,
                es_final: true
            );
        }
        if (!isset($filtro[$campo]['valor_es_campo']) && is_numeric($campo)) {
            return $this->error->error(
                mensaje: 'Error el campo debe ser un string $filtro[campo]',
                data: $filtro,
                es_final: true
            );
        }
        if (!isset($filtro[$campo]['operador'])) {
            return $this->error->error(
                mensaje: 'Error debe existir $filtro[campo][operador]',
                data: $filtro,
                es_final: true
            );
        }
        if (!isset($filtro[$campo]['valor'])) {
            $filtro[$campo]['valor'] = '';
        }
        if (is_array($filtro[$campo]['valor'])) {
            return $this->error->error(
                mensaje: "Error \$filtro['$campo']['valor'] debe ser un dato",
                data: $filtro,
                es_final: true
            );
        }
        return true;
    }



}

