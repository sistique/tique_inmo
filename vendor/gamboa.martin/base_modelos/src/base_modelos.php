<?php
namespace gamboamartin\base_modelos;

use gamboamartin\validacion\validacion;


class base_modelos extends validacion
{

    /**
     * REG
     * Valida los datos de una lista de entrada asegurando que la sección y la acción sean válidas.
     *
     * Esta función verifica que los parámetros `$seccion` y `$accion` no estén vacíos.
     * Además, limpia el prefijo `'models\\'` de `$seccion` en caso de que esté presente.
     * Si alguno de estos valores es inválido, se devuelve un mensaje de error.
     *
     * @param string $accion Acción en ejecución dentro del sistema. No puede estar vacío.
     * @param string $seccion Sección en ejecución dentro del sistema. Se limpiará de prefijos `models\` si están presentes.
     *
     * @return array|bool
     *         - `true` si la validación es exitosa.
     *         - `array` con un mensaje de error si la validación falla.
     *
     * @throws array Si:
     *         - `$seccion` está vacío (`"Error seccion no puede venir vacio"`).
     *         - `$accion` está vacío (`"Error no existe la accion"`).
     *
     * @example
     * // Ejemplo 1: Validación exitosa con sección sin prefijo
     * $accion = "crear";
     * $seccion = "fc_factura";
     * $resultado = $this->valida_datos_lista_entrada($accion, $seccion);
     * var_dump($resultado);
     *
     * // Salida esperada:
     * // true
     *
     * @example
     * // Ejemplo 2: Validación exitosa con sección con prefijo models\
     * $accion = "modificar";
     * $seccion = "models\\fc_factura";
     * $resultado = $this->valida_datos_lista_entrada($accion, $seccion);
     * var_dump($resultado);
     *
     * // Salida esperada:
     * // true (Sección se limpia y queda "fc_factura")
     *
     * @example
     * // Ejemplo 3: Sección vacía
     * $accion = "eliminar";
     * $seccion = "";
     * $resultado = $this->valida_datos_lista_entrada($accion, $seccion);
     * var_dump($resultado);
     *
     * // Salida esperada:
     * // [
     * //     "error" => true,
     * //     "mensaje" => "Error seccion no puede venir vacio",
     * //     "data" => "",
     * //     "es_final" => true
     * // ]
     *
     * @example
     * // Ejemplo 4: Acción vacía
     * $accion = "";
     * $seccion = "fc_cliente";
     * $resultado = $this->valida_datos_lista_entrada($accion, $seccion);
     * var_dump($resultado);
     *
     * // Salida esperada:
     * // [
     * //     "error" => true,
     * //     "mensaje" => "Error no existe la accion",
     * //     "data" => "",
     * //     "es_final" => true
     * // ]
     */
    final public function valida_datos_lista_entrada(string $accion, string $seccion): array|bool
    {
        $seccion = str_replace('models\\', '', $seccion);
        if ($seccion === '') {
            return $this->error->error(mensaje: 'Error seccion no puede venir vacio',data:  $seccion, es_final: true);
        }
        if ($accion === '') {
            return $this->error->error(mensaje:'Error no existe la accion', data:$accion, es_final: true);
        }

        return true;
    }


    /**
     * REG
     * Valida si una transacción en un registro está activa y puede ser manipulada.
     *
     * Esta función verifica si un registro en una tabla específica puede ser modificado.
     * Si la opción `$aplica_transaccion_inactivo` es `false`, se verifica que el registro
     * no esté marcado como "inactivo". En caso de que lo esté, se genera un error.
     *
     * @param bool $aplica_transaccion_inactivo Indica si se permite manipular registros inactivos.
     *        - `true`: Se permite la manipulación de registros inactivos.
     *        - `false`: Se debe verificar el estado del registro antes de manipularlo.
     * @param array $registro Datos del registro a validar. Debe contener la clave `{tabla}_status`.
     * @param int $registro_id Identificador del registro en la base de datos.
     *        - Debe ser un número entero positivo mayor a 0.
     * @param string $tabla Nombre de la tabla en la base de datos. No puede estar vacío.
     *
     * @return array|true
     *         - `true` si el registro puede ser manipulado.
     *         - `array` con un mensaje de error si la validación falla.
     *
     * @throws array Si:
     *         - `$tabla` está vacío (`"Error la tabla esta vacia"`).
     *         - `$registro_id` es menor o igual a 0 (`"Error el id debe ser mayor a 0"`).
     *         - `$registro` no contiene la clave `{tabla}_status` (`"Error no existe el registro con el campo {tabla}_status"`).
     *         - `$registro[{tabla}_status]` es `"inactivo"` (`"Error el registro no puede ser manipulado"`).
     *
     * @example
     * // Ejemplo 1: Registro válido (transacción permitida)
     * $aplica_transaccion_inactivo = true;
     * $registro = [
     *     "fc_factura_status" => "activo"
     * ];
     * $registro_id = 10;
     * $tabla = "fc_factura";
     * $resultado = $this->valida_transaccion_activa($aplica_transaccion_inactivo, $registro, $registro_id, $tabla);
     * var_dump($resultado);
     *
     * // Salida esperada:
     * // true
     *
     * @example
     * // Ejemplo 2: Registro con transacción inactiva y permitido
     * $aplica_transaccion_inactivo = true;
     * $registro = [
     *     "fc_factura_status" => "inactivo"
     * ];
     * $registro_id = 15;
     * $tabla = "fc_factura";
     * $resultado = $this->valida_transaccion_activa($aplica_transaccion_inactivo, $registro, $registro_id, $tabla);
     * var_dump($resultado);
     *
     * // Salida esperada:
     * // true (ya que `aplica_transaccion_inactivo` es `true`, se permite)
     *
     * @example
     * // Ejemplo 3: Registro inactivo y no se permite manipulación
     * $aplica_transaccion_inactivo = false;
     * $registro = [
     *     "fc_factura_status" => "inactivo"
     * ];
     * $registro_id = 20;
     * $tabla = "fc_factura";
     * $resultado = $this->valida_transaccion_activa($aplica_transaccion_inactivo, $registro, $registro_id, $tabla);
     * var_dump($resultado);
     *
     * // Salida esperada:
     * // [
     * //     "error" => true,
     * //     "mensaje" => "Error el registro no puede ser manipulado",
     * //     "data" => ["fc_factura_status" => "inactivo"],
     * //     "es_final" => true
     * // ]
     *
     * @example
     * // Ejemplo 4: Registro sin el campo {tabla}_status
     * $aplica_transaccion_inactivo = false;
     * $registro = [];
     * $registro_id = 25;
     * $tabla = "fc_factura";
     * $resultado = $this->valida_transaccion_activa($aplica_transaccion_inactivo, $registro, $registro_id, $tabla);
     * var_dump($resultado);
     *
     * // Salida esperada:
     * // [
     * //     "error" => true,
     * //     "mensaje" => "Error no existe el registro con el campo fc_factura_status",
     * //     "data" => [],
     * //     "es_final" => true
     * // ]
     */
    final public function valida_transaccion_activa(bool  $aplica_transaccion_inactivo, array $registro,
                                              int $registro_id, string $tabla): array|true
    {
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error la tabla esta vacia', data: $tabla, es_final: true);
        }
        if (!$aplica_transaccion_inactivo) {
            if ($registro_id <= 0) {
                return $this->error->error(mensaje:'Error el id debe ser mayor a 0',data: $registro_id,
                    es_final: true);
            }
            $key = $tabla . '_status';
            if (!isset($registro[$key])) {
                return $this->error->error(mensaje:'Error no existe el registro con el campo ' . $tabla . '_status',
                    data:$registro, es_final: true);
            }
            if ($registro[$tabla . '_status'] === 'inactivo') {
                return $this->error->error(mensaje:'Error el registro no puede ser manipulado',data: $registro,
                    es_final: true);
            }
        }

        return true;
    }



}