<?php
namespace gamboamartin\template;
use base\frontend\params_inputs;
use base\orm\modelo;
use config\generales;
use config\views;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

class html{
    protected errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    /**
     * REG
     * Genera una alerta de éxito en formato HTML.
     *
     * Esta función toma un mensaje como parámetro y genera un bloque de código HTML con una alerta
     * de Bootstrap de tipo éxito. Si el mensaje proporcionado está vacío, se devolverá un error.
     * La alerta generada incluye un encabezado "Muy bien!" seguido del mensaje proporcionado.
     *
     * @param string $mensaje El mensaje que se mostrará en la alerta de éxito. Este debe ser una cadena
     *                       no vacía. Si el mensaje está vacío o contiene solo espacios, se generará
     *                       un error. El mensaje se mostrará dentro de una alerta de tipo `alert-success`
     *                       de Bootstrap.
     *
     * @return string|array Retorna una cadena con el HTML de la alerta de éxito si el mensaje es válido.
     *                      Si el mensaje está vacío, devuelve un arreglo con información sobre el error.
     *                      El arreglo contiene el mensaje de error y los datos relacionados con el error.
     *
     * @throws errores Si el mensaje está vacío, se lanza un error con el mensaje 'Error mensaje esta vacio'.
     *                 Además, se retornará un objeto `errores` con la información del error generado.
     *
     * Ejemplo de uso:
     *
     * ```php
     * $html = new Html();  // Crear una instancia de la clase Html
     * echo $html->alert_success('La operación se completó con éxito');
     * ```
     *
     * En el caso anterior, si el mensaje proporcionado es `'La operación se completó con éxito'`,
     * se generará el siguiente HTML:
     *
     * ```html
     * <div class='alert alert-success' role='alert'>
     *     <strong>Muy bien!</strong> La operación se completó con éxito.
     * </div>
     * ```
     *
     * Si el mensaje está vacío o solo contiene espacios, la función devolverá un error.
     * Por ejemplo, con el siguiente código:
     *
     * ```php
     * echo $html->alert_success('   ');  // Mensaje vacío o solo espacios
     * ```
     *
     * El resultado sería un error como el siguiente:
     * ```php
     * array(
     *     'mensaje' => 'Error mensaje esta vacio',
     *     'data' => '   ',
     *     'es_final' => true
     * )
     * ```
     *
     * En este caso, el mensaje está vacío y no se genera el HTML de la alerta.
     *
     * @version 1.0.0
     */
    final public function alert_success(string $mensaje): string|array
    {
        // Se eliminan los espacios en blanco al principio y al final del mensaje
        $mensaje = trim($mensaje);

        // Si el mensaje es vacío, se genera un error
        if ($mensaje === '') {
            return $this->error->error(
                mensaje: 'Error mensaje esta vacio',  // Mensaje de error
                data: $mensaje,                      // Datos relacionados con el error
                es_final: true                        // Indicador de que es un error final
            );
        }

        // Si el mensaje no está vacío, se genera la alerta HTML
        return "<div class='alert alert-success' role='alert'><strong>Muy bien!</strong> $mensaje.</div>";
    }


    /**
     * REG
     * Genera una alerta de advertencia en formato HTML.
     *
     * Esta función recibe un mensaje de advertencia y genera un bloque de código HTML
     * que representa una alerta de tipo 'alert-warning' utilizando Bootstrap. El mensaje
     * se presenta dentro de una alerta con el texto "Advertencia!" seguido del mensaje
     * proporcionado. Si el mensaje está vacío, la función devuelve un error.
     *
     * @param string $mensaje El mensaje de advertencia que se mostrará en la alerta.
     *                        Este parámetro debe ser una cadena no vacía.
     *                        Si el mensaje está vacío o contiene solo espacios, se generará un error.
     *
     * @return string|array Retorna una cadena con el HTML de la alerta si el mensaje es válido.
     *                      Si el mensaje está vacío, devuelve un arreglo con información sobre el error.
     *                      El arreglo contiene el mensaje de error y los datos relacionados con el error.
     *
     * @throws errores Si el mensaje está vacío, se lanza un error con el mensaje 'Error mensaje esta vacio'.
     *                 Además, se retornará un objeto `errores` con la información del error generado.
     *
     * Ejemplo de uso:
     *
     * ```php
     * $html = new Html();  // Crear una instancia de la clase Html
     * echo $html->alert_warning('Se ha producido un error en el proceso');
     * ```
     *
     * En el ejemplo anterior, si el mensaje proporcionado es `'Se ha producido un error en el proceso'`,
     * se generará el siguiente HTML:
     *
     * ```html
     * <div class='alert alert-warning' role='alert'>
     *     <strong>Advertencia!</strong> Se ha producido un error en el proceso.
     * </div>
     * ```
     *
     * Si el mensaje está vacío o solo contiene espacios, la función devolverá un error.
     * Por ejemplo, con el siguiente código:
     *
     * ```php
     * echo $html->alert_warning('   ');  // Mensaje vacío o solo espacios
     * ```
     *
     * El resultado sería un error como el siguiente:
     * ```php
     * array(
     *     'mensaje' => 'Error mensaje esta vacio',
     *     'data' => '   ',
     *     'es_final' => true
     * )
     * ```
     *
     * En este caso, el mensaje está vacío y no se genera el HTML de la alerta.
     *
     * @version 1.0.0
     */
    final public function alert_warning(string $mensaje): string|array
    {
        // Se eliminan los espacios en blanco al principio y al final del mensaje
        $mensaje = trim($mensaje);

        // Si el mensaje es vacío, se genera un error
        if ($mensaje === '') {
            return $this->error->error(
                mensaje: 'Error mensaje esta vacio',  // Mensaje de error
                data: $mensaje,                      // Datos relacionados con el error
                es_final: true                        // Indicador de que es un error final
            );
        }

        // Si el mensaje no está vacío, se genera la alerta HTML
        return "<div class='alert alert-warning' role='alert'><strong>Advertencia!</strong> $mensaje.</div>";
    }


    /**
     * REG
     * Genera un enlace HTML (`<a>`) para un botón con los parámetros proporcionados.
     *
     * Esta función recibe varios parámetros como la acción, etiqueta, ID de registro, sección, estilo y otros parámetros adicionales,
     * y genera un enlace HTML. El enlace se utilizará como un botón en la interfaz de usuario. Además, valida que los parámetros sean correctos.
     * Si los parámetros son válidos, genera un enlace HTML con los parámetros correspondientes, sino, retorna un mensaje de error.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que los parámetros `accion`, `etiqueta`, `seccion` y `style` no estén vacíos utilizando el método `valida_input`.
     * 2. Si la validación es exitosa, se obtiene el `session_id` de la sesión actual.
     * 3. Si el `session_id` está vacío, se genera un mensaje de error.
     * 4. Se construye una URL de enlace con los parámetros proporcionados y cualquier parámetro adicional.
     * 5. Se devuelve el HTML del enlace generado.
     *
     * **Parámetros:**
     *
     * @param string $accion La acción que se realizará cuando se haga clic en el botón.
     * @param string $etiqueta El texto que se mostrará en el botón.
     * @param int $registro_id El ID del registro que se utilizará para la acción.
     * @param string $seccion El nombre de la sección a la que pertenece el botón.
     * @param string $style El estilo CSS del botón.
     * @param array $params Parámetros adicionales que se agregarán a la URL como parámetros GET.
     *
     * **Retorno:**
     * - Devuelve el HTML de un enlace `<a>` con el estilo y parámetros proporcionados.
     * - Si ocurre un error durante la validación o generación, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Generación de un enlace válido**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $params = ['redirigir' => 'true'];
     * $resultado = $this->button_href($accion, $etiqueta, $registro_id, $seccion, $style, $params);
     * // Retorna: "<a href='index.php?seccion=usuarios&accion=guardar&registro_id=123&session_id=xyz&redirigir=true' class='btn-primary'>Guardar cambios</a>"
     * ```
     *
     * **Ejemplo 2: Error por `session_id` vacío**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $params = [];
     * $resultado = $this->button_href($accion, $etiqueta, $registro_id, $seccion, $style, $params);
     * // Si no hay `session_id` válido, retorna un mensaje de error.
     * ```
     *
     * **Ejemplo 3: Error por parámetro vacío**
     * ```php
     * $accion = "";
     * $etiqueta = "Guardar cambios";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $params = [];
     * $resultado = $this->button_href($accion, $etiqueta, $registro_id, $seccion, $style, $params);
     * // Retorna un mensaje de error: 'Error al validar datos'.
     * ```
     *
     * **@version 1.0.0**
     */
    public function button_href(string $accion, string $etiqueta, int $registro_id, string $seccion,
                                string $style, array $params = array()): string|array
    {

        $valida = $this->valida_input(accion: $accion,etiqueta:  $etiqueta, seccion: $seccion,style:  $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $session_id = (new generales())->session_id;

        if($session_id === ''){
            return $this->error->error(mensaje: 'Error la $session_id esta vacia', data: $session_id, es_final: true);
        }

        $params_get = '';
        foreach ($params as $key=>$value){
            $params_get .= "&$key=$value";
        }

        $link = "index.php?seccion=$seccion&accion=$accion&registro_id=$registro_id&session_id=$session_id";
        $link .= $params_get;
        return /** @lang html */ "<a |role| href='$link' |class|>$etiqueta</a>";
    }

    private function class_css_html(array $class_css): array|string
    {
        $class_html = '';
        foreach ($class_css as $class){
            $class = trim($class);
            if($class === ''){
                return $this->error->error(mensaje: 'Error class vacio',data:  $class);
            }
            $class_html.=" $class ";
        }
        return trim($class_html);
    }

    /**
     * REG
     * Concatena el valor de una columna específica con la descripción existente.
     *
     * ---
     * ### **Descripción**
     * Esta función permite agregar el contenido de una columna específica de `$row` a una cadena de texto `$descripcion_select`.
     * - Primero, valida que `$column` no esté vacío.
     * - Luego, verifica que `$row` contenga la clave `$column`.
     * - Si `$descripcion_select` tiene contenido, se añade un espacio antes de concatenar.
     * - Finalmente, devuelve la nueva descripción con la concatenación.
     *
     * ---
     * ### **Parámetros**
     * @param string $column
     *     - Nombre de la columna de `$row` cuyo valor será concatenado.
     *     - Debe existir en `$row`.
     *
     * @param string $descripcion_select
     *     - Texto base al que se concatenará el valor de `$column`.
     *     - Si no está vacío, se añade un espacio antes de la concatenación.
     *
     * @param array $row
     *     - Datos de la fila actual.
     *     - Debe contener la clave `$column`.
     *
     * ---
     * ### **Retorno**
     * - **string**: Devuelve la descripción concatenada en caso de éxito.
     * - **array**: Devuelve un array con información de error si ocurre algún problema.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Concatenar un valor a la descripción existente**
     * ```php
     * $column = 'nombre';
     * $descripcion_select = 'Cliente';
     * $row = ['nombre' => 'Juan Pérez'];
     *
     * $resultado = $this->concat_descripcion_select($column, $descripcion_select, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * "Cliente Juan Pérez"
     * ```
     *
     * ---
     * #### **Ejemplo 2: Concatenar cuando la descripción está vacía**
     * ```php
     * $column = 'nombre';
     * $descripcion_select = '';
     * $row = ['nombre' => 'María González'];
     *
     * $resultado = $this->concat_descripcion_select($column, $descripcion_select, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * "María González"
     * ```
     *
     * ---
     * #### **Ejemplo 3: Manejo de errores si la columna está vacía**
     * ```php
     * $column = '';
     * $descripcion_select = 'Cliente';
     * $row = ['nombre' => 'Pedro López'];
     *
     * $resultado = $this->concat_descripcion_select($column, $descripcion_select, $row);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error column esta vacia',
     *     'data' => ''
     * ]
     * ```
     *
     * ---
     * #### **Ejemplo 4: Manejo de errores si `$row` no contiene `$column`**
     * ```php
     * $column = 'apellido';
     * $descripcion_select = 'Cliente';
     * $row = ['nombre' => 'Carlos Ramírez'];
     *
     * $resultado = $this->concat_descripcion_select($column, $descripcion_select, $row);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error al validar row',
     *     'data' => [...]
     * ]
     * ```
     *
     * ---
     * @throws array Devuelve un array con un mensaje de error si ocurre algún problema en la validación.
     *
     * @version 1.0.0
     */
    private function concat_descripcion_select(string $column, string $descripcion_select, array $row): array|string
    {
        // Validar que `$column` no esté vacío
        $column = trim($column);
        if ($column === '') {
            return $this->error->error(mensaje: 'Error column esta vacia', data: $column);
        }

        // Validar que `$row` contenga la clave `$column`
        $keys_val = [$column];
        $valida = (new validacion())->valida_existencia_keys($keys_val, $row);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar row', data: $valida);
        }

        // Limpieza de `$descripcion_select`
        $descripcion_select = trim($descripcion_select);

        // Agregar espacio si `$descripcion_select` ya tiene contenido
        $espacio = '';
        if ($descripcion_select !== '') {
            $espacio = ' ';
        }

        // Concatenar el valor de `$column` en `$row`
        $descripcion_select .= $espacio . trim($row[$column]);

        return trim($descripcion_select);
    }


    /**
     * REG
     * Procesa un conjunto de datos de una fila y genera una estructura de salida con una descripción y un valor personalizado.
     *
     * ---
     * ### **Descripción**
     * - La función toma un conjunto de columnas (`$columns_ds`), una clave personalizada (`$key_value_custom`) y una fila de datos (`$row`).
     * - Se encarga de generar una descripción compuesta a partir de las columnas de `$columns_ds`.
     * - Verifica la existencia de la clave `descripcion_select` en `$row`.
     * - Obtiene el valor asociado a `$key_value_custom` en `$row`.
     * - Devuelve un objeto con `row` (fila procesada) y `value_custom` (valor extraído).
     *
     * ---
     * ### **Parámetros**
     * @param array $columns_ds
     *     - Arreglo de nombres de columnas que se utilizarán para construir `descripcion_select`.
     *     - Debe contener al menos una columna válida.
     *
     * @param string $key_value_custom
     *     - Clave dentro de `$row` de la cual se extraerá un valor personalizado.
     *     - Si está vacía, se devuelve una cadena vacía (`''`).
     *
     * @param array $row
     *     - Fila de datos de la cual se generará la `descripcion_select` y se extraerá el valor de `$key_value_custom`.
     *     - Debe contener las claves especificadas en `$columns_ds` y `descripcion_select`.
     *
     * ---
     * ### **Retorno**
     * - **stdClass**:
     *     - **row (array)**: Fila procesada con `descripcion_select`.
     *     - **value_custom (string)**: Valor extraído de `$row` con la clave `$key_value_custom`.
     * - **array**: Devuelve un mensaje de error si alguna validación falla.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Procesar fila con descripción compuesta y clave personalizada**
     * ```php
     * $columns_ds = ['nombre', 'apellido'];
     * $key_value_custom = 'codigo';
     * $row = ['nombre' => 'Juan', 'apellido' => 'Pérez', 'codigo' => 'JP123'];
     *
     * $resultado = $this->data_option($columns_ds, $key_value_custom, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * stdClass Object (
     *     [row] => Array (
     *         [nombre] => Juan
     *         [apellido] => Pérez
     *         [codigo] => JP123
     *         [descripcion_select] => Juan Pérez
     *     )
     *     [value_custom] => JP123
     * )
     * ```
     *
     * ---
     * #### **Ejemplo 2: Fila sin `descripcion_select` (Error)**
     * ```php
     * $columns_ds = ['nombre', 'apellido'];
     * $key_value_custom = 'codigo';
     * $row = ['nombre' => 'Juan', 'codigo' => 'JP123'];
     *
     * $resultado = $this->data_option($columns_ds, $key_value_custom, $row);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error al validar row',
     *     'data' => ['descripcion_select' => null]
     * ]
     * ```
     *
     * ---
     * #### **Ejemplo 3: `$key_value_custom` no existe en `$row`**
     * ```php
     * $columns_ds = ['nombre', 'apellido'];
     * $key_value_custom = 'id_producto';
     * $row = ['nombre' => 'Ana', 'apellido' => 'Gómez'];
     *
     * $resultado = $this->data_option($columns_ds, $key_value_custom, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * stdClass Object (
     *     [row] => Array (
     *         [nombre] => Ana
     *         [apellido] => Gómez
     *         [descripcion_select] => Ana Gómez
     *     )
     *     [value_custom] => ''
     * )
     * ```
     *
     * ---
     * @throws array Devuelve un array con mensaje de error si:
     *  - `descripcion_select` no está presente en `$row`.
     *  - `$key_value_custom` está vacío o no se encuentra en `$row`.
     *
     * @version 1.0.0
     */
    private function data_option(array $columns_ds, string $key_value_custom, array $row)
    {
        // Genera la descripción compuesta y actualiza `$row`
        $row = $this->row_descripcion_select(columns_ds: $columns_ds, row: $row);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar descripcion select', data: $row);
        }

        // Verifica que `descripcion_select` exista en `$row`
        $keys = array('descripcion_select');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys, registro: $row);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar row', data: $valida);
        }

        // Obtiene el valor personalizado
        $value_custom = $this->value_custom(key_value_custom: $key_value_custom, row: $row);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar value custom', data: $value_custom);
        }

        // Retorna el objeto con la fila procesada y el valor personalizado
        $data = new stdClass();
        $data->row = $row;
        $data->value_custom = $value_custom;

        return $data;
    }


    /**
     * REG
     * Genera una descripción concatenada a partir de múltiples columnas de un array de datos.
     *
     * ---
     * ### **Descripción**
     * Esta función recorre un conjunto de columnas definidas en `$columns_ds`, extrae sus valores de `$row`,
     * y los concatena en una sola cadena `$descripcion_select`.
     * - Si una columna está vacía, devuelve un error.
     * - Si una columna no existe en `$row`, devuelve un error.
     * - Usa la función `concat_descripcion_select` para estructurar la descripción.
     * - Retorna una cadena concatenada con los valores de las columnas.
     *
     * ---
     * ### **Parámetros**
     * @param array $columns_ds
     *     - Lista de nombres de columnas que se concatenarán.
     *     - Cada columna debe existir en `$row`.
     *
     * @param array $row
     *     - Datos de la fila de donde se extraerán los valores de las columnas.
     *     - Debe contener todas las claves especificadas en `$columns_ds`.
     *
     * ---
     * ### **Retorno**
     * - **string**: Devuelve la descripción concatenada en caso de éxito.
     * - **array**: Devuelve un array con información de error si ocurre algún problema.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Concatenar varias columnas**
     * ```php
     * $columns_ds = ['nombre', 'apellido', 'edad'];
     * $row = ['nombre' => 'Juan', 'apellido' => 'Pérez', 'edad' => '30'];
     *
     * $resultado = $this->descripcion_select($columns_ds, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * "Juan Pérez 30"
     * ```
     *
     * ---
     * #### **Ejemplo 2: Manejo de error si una columna está vacía**
     * ```php
     * $columns_ds = ['', 'apellido'];
     * $row = ['nombre' => 'Carlos', 'apellido' => 'Ramírez'];
     *
     * $resultado = $this->descripcion_select($columns_ds, $row);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error column esta vacia',
     *     'data' => ''
     * ]
     * ```
     *
     * ---
     * #### **Ejemplo 3: Manejo de error si `$row` no contiene una clave de `$columns_ds`**
     * ```php
     * $columns_ds = ['nombre', 'apellido', 'direccion'];
     * $row = ['nombre' => 'Lucía', 'apellido' => 'Gómez'];
     *
     * $resultado = $this->descripcion_select($columns_ds, $row);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error al validar row',
     *     'data' => [...]
     * ]
     * ```
     *
     * ---
     * @throws array Devuelve un array con un mensaje de error si ocurre un problema en la validación.
     *
     * @version 1.0.0
     */
    private function descripcion_select(array $columns_ds, array $row): string|array
    {
        // Inicializar la cadena de descripción
        $descripcion_select = '';

        // Recorrer las columnas definidas en `$columns_ds`
        foreach ($columns_ds as $column) {
            // Limpiar espacios en blanco en la columna
            $column = trim($column);

            // Validar que `$column` no esté vacío
            if ($column === '') {
                return $this->error->error(mensaje: 'Error column esta vacia', data: $column, es_final: true);
            }

            // Validar que `$row` contenga la clave `$column`
            $keys_val = [$column];
            $valida = (new validacion())->valida_existencia_keys($keys_val, $row);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar row', data: $valida);
            }

            // Concatenar el valor de la columna en la descripción
            $descripcion_select = $this->concat_descripcion_select(column: $column,
                descripcion_select: $descripcion_select, row: $row);

            // Verificar errores en la concatenación
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar descripcion select', data: $descripcion_select);
            }
        }

        return $descripcion_select;
    }


    /**
     * REG
     * Genera un contenedor `<div>` con una clase `control-group col-sm-{cols}` que encapsula el contenido proporcionado.
     *
     * Esta función valida que el número de columnas sea válido y luego construye un `div` con la clase `control-group` y
     * `col-sm-{cols}`, integrando el contenido especificado dentro del contenedor.
     *
     * ### Validaciones realizadas:
     * - Se valida que `$cols` sea un número válido de columnas (usando `valida_cols`).
     * - Se elimina cualquier espacio en blanco del contenido.
     *
     * ### Parámetros:
     * @param int $cols Número de columnas a utilizar en el `div`. Debe ser un número entero positivo (por ejemplo, 1 a 12 en Bootstrap).
     * @param string $contenido El contenido HTML que se insertará dentro del `div`. Puede ser texto, imágenes u otros elementos HTML.
     *
     * ### Retorno:
     * - Devuelve una cadena con la estructura del `div` si los parámetros son válidos.
     * - Si hay un error en la validación, devuelve un array con el mensaje de error correspondiente.
     *
     * ---
     * ### Ejemplo de uso:
     * #### Ejemplo 1: Generación exitosa de un `div`
     * ```php
     * $cols = 6;
     * $contenido = "<p>Contenido del div</p>";
     * $resultado = $this->div_control_group_cols($cols, $contenido);
     * // Salida esperada:
     * // "<div class='control-group col-sm-6'><p>Contenido del div</p></div>"
     * ```
     *
     * #### Ejemplo 2: Error por `$cols` inválido
     * ```php
     * $cols = -1;  // Número de columnas inválido
     * $contenido = "<p>Contenido</p>";
     * $resultado = $this->div_control_group_cols($cols, $contenido);
     * // Salida esperada:
     * // array(
     * //     "mensaje" => "Error al validar cols",
     * //     "data" => [detalles del error]
     * // )
     * ```
     *
     * #### Ejemplo 3: Contenido con espacios innecesarios
     * ```php
     * $cols = 4;
     * $contenido = "   <p>Texto con espacios extra</p>   ";
     * $resultado = $this->div_control_group_cols($cols, $contenido);
     * // Salida esperada:
     * // "<div class='control-group col-sm-4'><p>Texto con espacios extra</p></div>"
     * ```
     *
     * @version 1.0.0
     */
    final protected function div_control_group_cols(int $cols, string $contenido): string|array
    {
        // Validar que el número de columnas sea correcto
        $valida = (new directivas(html:$this))->valida_cols(cols:$cols);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        // Limpiar espacios innecesarios en el contenido
        $contenido = trim($contenido);

        // Construcción del div con clases Bootstrap
        $div_contenedor_ini = "<div class='control-group col-sm-$cols'>";
        $div_contenedor_fin = "</div>";

        return $div_contenedor_ini . $contenido . $div_contenedor_fin;
    }


    /**
     * REG
     * Genera un contenedor `<div>` con una clase `control-group col-sm-{cols}` que encapsula un label y el contenido proporcionado.
     *
     * Esta función crea un `div` con una etiqueta (`label`) y contenido asociado dentro de una estructura de `control-group`.
     * Primero, valida los parámetros proporcionados, luego genera el `label` y, finalmente, construye el `div` con la estructura HTML.
     *
     * ---
     * ### Validaciones realizadas:
     * - Se valida que `$cols` sea un número válido de columnas (usando `valida_input_select`).
     * - Se asegura que `$label` y `$name` no estén vacíos.
     * - Se genera la etiqueta HTML (`label`) correspondiente.
     * - Se encapsula el `label` y el contenido en un `div`.
     *
     * ---
     * ### Parámetros:
     * @param int $cols Número de columnas a utilizar en el `div`. Debe ser un número entero positivo (generalmente entre 1 y 12 en Bootstrap).
     * @param string $contenido El contenido HTML que se insertará dentro del `div`. Puede ser texto, inputs, imágenes u otros elementos HTML.
     * @param string $label Texto que se mostrará en la etiqueta asociada al contenido dentro del `div`.
     * @param string $name Nombre del input asociado con la etiqueta, también se usa como `id` en el `label`.
     *
     * ---
     * ### Retorno:
     * - Devuelve una cadena con la estructura del `div` si los parámetros son válidos.
     * - Si hay un error en la validación, devuelve un array con el mensaje de error correspondiente.
     *
     * ---
     * ### Ejemplo de uso:
     * #### Ejemplo 1: Generación exitosa de un `div` con `label` y contenido
     * ```php
     * $cols = 6;
     * $contenido = "<input type='text' name='usuario' />";
     * $label = "Nombre de Usuario";
     * $name = "usuario";
     *
     * $resultado = $this->div_control_group_cols_label($cols, $contenido, $label, $name);
     * ```
     * **Salida esperada:**
     * ```html
     * <div class='control-group col-sm-6'>
     *     <label for='usuario'>Nombre de Usuario</label>
     *     <input type='text' name='usuario' />
     * </div>
     * ```
     *
     * ---
     * #### Ejemplo 2: Error por `$cols` inválido
     * ```php
     * $cols = -1;  // Número de columnas inválido
     * $contenido = "<input type='text' name='usuario' />";
     * $label = "Nombre de Usuario";
     * $name = "usuario";
     *
     * $resultado = $this->div_control_group_cols_label($cols, $contenido, $label, $name);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     "mensaje" => "Error al validar input",
     *     "data" => [detalles del error]
     * )
     * ```
     *
     * ---
     * #### Ejemplo 3: Error por `$label` vacío
     * ```php
     * $cols = 4;
     * $contenido = "<input type='password' name='clave' />";
     * $label = "";
     * $name = "clave";
     *
     * $resultado = $this->div_control_group_cols_label($cols, $contenido, $label, $name);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     "mensaje" => "Error al validar input",
     *     "data" => [detalles del error]
     * )
     * ```
     *
     * ---
     * @version 1.0.0
     */
    private function div_control_group_cols_label(
        int $cols, string $contenido, string $label, string $name): string|array
    {
        // Limpiar los valores de $label y $name
        $label = trim($label);
        $name = trim($name);

        // Validar los parámetros de entrada
        $valida = $this->valida_input_select(cols: $cols, label: $label, name: $name);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar input', data: $valida);
        }

        // Generar la etiqueta HTML para el input
        $label_html = $this->label(id_css: $name, place_holder: $label);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar label', data: $label_html);
        }

        // Construcción del div con la etiqueta y el contenido
        $html = $this->div_control_group_cols(cols: $cols, contenido: $label_html . $contenido);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $html);
        }

        return $html;
    }


    /**
     * Integra el contenido de divs de tipo input
     * @param string $contenido Contenido a integrar en el div
     * @return string
     * @version 0.68.4
     * @verfuncion 0.1.0
     * @fecha 2022-08-03 15:13
     * @author mgamboa
     */
    private function div_controls(string $contenido): string
    {
        $div_controls_ini = "<div class='controls'>";
        $div_controls_fin = "</div>";

        return $div_controls_ini.$contenido.$div_controls_fin;
    }

    /**
     * REG
     * Crea un contenedor `div` con un contenido HTML, validando el número de columnas y limpiando el resultado.
     *
     * Esta función genera un `div` que contiene el contenido HTML proporcionado, el cual está envuelto en un número de columnas
     * determinado por el parámetro `$cols`. La función valida que el número de columnas sea adecuado y limpia el HTML generado
     * para asegurar que no haya espacios extra innecesarios.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que el número de columnas `$cols` sea válido utilizando el método `valida_cols` de la clase `directivas`.
     * 2. Se crea un contenedor `div` con el contenido HTML proporcionado.
     * 3. Se pasa el HTML generado a través de la función `limpia_salida` para eliminar espacios extra y corregir posibles errores de formato.
     * 4. Si ocurre un error durante la validación o la limpieza, se devuelve un mensaje de error con detalles sobre el problema.
     * 5. Si la limpieza es exitosa, se devuelve el HTML del contenedor `div` con el contenido.
     *
     * **Parámetros:**
     *
     * @param int $cols El número de columnas que se utilizarán en el contenedor `div`. Este parámetro es obligatorio y se valida.
     * @param string $html El contenido HTML que se incluirá dentro del `div`. Este parámetro es obligatorio y debe ser una cadena de texto.
     *
     * **Retorno:**
     * - Devuelve el HTML de un contenedor `div` que contiene el contenido HTML proporcionado si todo es válido.
     * - Si ocurre un error durante la validación o la limpieza, devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Creación de un contenedor div válido**
     * ```php
     * $cols = 6;
     * $html = "<p>Texto dentro del div</p>";
     * $resultado = $this->div_group($cols, $html);
     * // Retorna: "<div class='col-6'><p>Texto dentro del div</p></div>"
     * ```
     *
     * **Ejemplo 2: Error por número de columnas inválido**
     * ```php
     * $cols = -1;
     * $html = "<p>Texto dentro del div</p>";
     * $resultado = $this->div_group($cols, $html);
     * // Retorna un mensaje de error: 'Error al validar cols'.
     * ```
     *
     * **Ejemplo 3: Error durante la limpieza del HTML**
     * ```php
     * $cols = 6;
     * $html = "<p>Texto con problemas</p>";
     * $resultado = $this->div_group($cols, $html);
     * // Si ocurre un error durante la limpieza, se retorna un mensaje de error.
     * ```
     *
     * **@version 1.0.0**
     */
    public function div_group(int $cols, string $html): string|array
    {
        // Validación del número de columnas
        $valida = (new directivas(html: $this))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        // Creación del HTML del div con el contenido proporcionado
        $html_r = /** @lang html */
            "<div |class|>$html</div>";

        // Limpiar el HTML generado
        $html_r = $this->limpia_salida(html: $html_r);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar salida', data: $html_r);
        }

        // Retorno del HTML limpio del div
        return $html_r;
    }


    /**
     * REG
     * Genera un div que contiene un HTML proporcionado y una etiqueta.
     *
     * Esta función recibe un fragmento de HTML y una etiqueta, y genera un contenedor `<div>` que envuelve el HTML.
     * Luego, limpia el resultado utilizando la función `limpia_salida` para asegurar que no haya espacios extra innecesarios en el HTML generado.
     *
     * **Pasos de procesamiento:**
     * 1. Se crea una estructura de `div` con el contenido de la etiqueta y el HTML proporcionado.
     * 2. El resultado se pasa a través de la función `limpia_salida` para eliminar espacios adicionales y corregir posibles problemas de formato.
     * 3. Si hay un error al limpiar la salida, se devuelve un error con el mensaje correspondiente.
     * 4. Si la limpieza es exitosa, se devuelve el HTML limpio y formateado correctamente.
     *
     * **Parámetros:**
     *
     * @param string $html El contenido HTML que se incluirá dentro del `<div>`. Este parámetro es obligatorio y debe ser una cadena de texto con el HTML a mostrar.
     * @param string $label El contenido que se mostrará como una etiqueta antes del contenido HTML. Este parámetro también es obligatorio.
     *
     * **Retorno:**
     * - Devuelve un string con el código HTML de un `<div>` que incluye la etiqueta proporcionada y el contenido HTML.
     * - Si ocurre algún error durante el proceso de limpieza del HTML, devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Crear un div con contenido HTML**
     * ```php
     * $html = "<p>Texto dentro del div</p>";
     * $label = "<label>Etiqueta del div</label>";
     * $resultado = $this->div_label($html, $label);
     * // Retorna: "<label>Etiqueta del div</label><div |class|><p>Texto dentro del div</p></div>"
     * ```
     *
     * **Ejemplo 2: Error durante la limpieza de la salida**
     * ```php
     * $html = "<p>Texto con problemas</p>";
     * $label = "<label>Etiqueta</label>";
     * $resultado = $this->div_label($html, $label);
     * // Si la función limpia_salida devuelve un error, se retorna un mensaje de error.
     * ```
     *
     * **@version 1.0.0**
     */
    public function div_label(string $html, string $label): string
    {
        // Crear el contenido del div con la etiqueta proporcionada
        $div_r = /** @lang html */
            $label."<div |class|>$html</div>";

        // Limpiar el HTML resultante utilizando la función limpia_salida
        $div_r = $this->limpia_salida(html: $div_r);
        if(errores::$error){
            // Si ocurre un error durante la limpieza, retornar un mensaje de error
            return $this->error->error(mensaje: 'Error al limpiar salida', data: $div_r);
        }

        // Si todo va bien, retornar el HTML limpio
        return $div_r;
    }


    /**
     * Genera un div de tipo select
     * @param string $name Name input
     * @param string $options_html Options en html
     * @param array $class_css Class css nuevas
     * @param bool $disabled Si disabled el input quedara disabled
     * @param string $id_css Si existe lo cambia por el name
     * @param bool $required si required integra requieren en select
     * @return array|string
     */
    public function div_select(string $name, string $options_html, array $class_css = array(), bool $disabled = false,
                               string $id_css = '', bool $required = false): array|string
    {
        $required_html = (new params_inputs())->required_html(required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'La asignacion de required es incorrecta', data: $required_html);
        }

        $disabled_html = (new params_inputs())->disabled_html(disabled: $disabled);
        if(errores::$error){
            return $this->error->error(mensaje: 'La asignacion de disabled es incorrecta', data: $disabled_html);
        }

        $class_html = $this->class_css_html(class_css: $class_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar class css', data: $class_html);
        }

        $id_css = trim($id_css);
        if($id_css === ''){
            $id_css = trim($name);
        }


        $select_in  ="<select class='form-control selectpicker color-secondary $name $class_html' ";
        $select_in  .="data-live-search='true' id='$id_css' name='$name' ";
        $select_in  .="$required_html $disabled_html>";

        $select_fin = '</select>';
        return $select_in.$options_html.$select_fin;
    }

    /**
     * REG
     * Genera el HTML de un input de tipo texto con validación para correos electrónicos.
     *
     * Este método construye un campo `input` con atributos configurables, incluyendo `name`, `id`, `placeholder`,
     * `required`, `disabled`, y una validación basada en una expresión regular para correos electrónicos (HTML5 compatible).
     *
     * La validación se realiza contra un patrón predefinido llamado `correo_html5` definido en la clase `validacion`.
     * Si este patrón no existe o alguno de los datos requeridos no es válido, se devolverá un array de error.
     *
     * @version 1.0.0
     * @author Gamboa
     * @package base\frontend
     *
     * @param bool $disabled Define si el campo estará deshabilitado (`true`) o habilitado (`false`).
     * @param string $id_css ID principal para el input.
     * @param string $name Nombre del campo (atributo `name` y `id` del input).
     * @param string $place_holder Texto guía mostrado dentro del campo cuando está vacío.
     * @param bool $required Si el campo es obligatorio (`required` en HTML).
     * @param mixed $value Valor predefinido para el input (texto del correo electrónico actual).
     *
     * @return string|array Devuelve el HTML generado como cadena si todo fue correcto.
     *                      Si ocurre un error en las validaciones o generación, devuelve un array con los detalles del error.
     *
     * @example Generación de input email:
     * ```php
     * $html = $this->email(
     *     disabled: false,
     *     id_css: 'correo_usuario',
     *     name: 'email',
     *     place_holder: 'Ingrese su correo',
     *     required: true,
     *     value: 'usuario@ejemplo.com'
     * );
     *
     * echo $html;
     * // Salida esperada:
     * // <input type='text' name='email' value='usuario@ejemplo.com' |class| id='correo_usuario'
     * //  placeholder='Ingrese su correo' required pattern='[expresión_reg]' />
     * ```
     *
     * @example En caso de error:
     * ```php
     * $html = $this->email(
     *     disabled: false,
     *     id_css: '', // error: vacío
     *     name: 'email',
     *     place_holder: 'Ingrese su correo',
     *     required: true,
     *     value: ''
     * );
     * if (is_array($html)) {
     *     print_r($html['mensaje']); // Error al validar datos
     * }
     * ```
     *
     * @see params_txt() Utilizado para generar parámetros HTML comunes (required, class, title, etc.)
     * @see validacion::$patterns['correo_html5'] Expresión regular utilizada para validar el email.
     */

    public function email(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                          mixed $value): array|string
    {

        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params, es_final: true);
        }

        $val = new validacion();
        if (!isset($val->patterns['correo_html5'])) {
            return $this->error->error(mensaje: 'No existe el regex para email', data: $params, es_final: true);
        }

        $html = "<input type='text' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= "id='$params->id_css' placeholder='$params->place_holder' pattern='".$val->patterns['correo_html_base']."' />";
        return $html;
    }

    /**
     * REG
     * Genera una cadena de atributos `data-*` en HTML a partir de un array de parámetros adicionales.
     *
     * Esta función recibe un array asociativo donde las claves representan los nombres de los atributos
     * `data-*` en HTML y los valores representan el contenido de esos atributos.
     * Si alguna clave del array es un número, se devuelve un error, ya que los nombres de atributos deben ser strings válidos.
     *
     * ---
     * ### Validaciones realizadas:
     * - Se verifica que todas las claves del array `$extra_params` sean cadenas de texto y no números.
     * - Se construye una cadena con los atributos `data-*` en formato HTML.
     *
     * ---
     * ### Parámetros:
     * @param array $extra_params Un array asociativo donde:
     *   - La clave representa el nombre del atributo `data-*`.
     *   - El valor representa el contenido del atributo.
     *
     * ---
     * ### Retorno:
     * - Devuelve una cadena con los atributos `data-*` formateados correctamente si los datos son válidos.
     * - Si alguna clave es un número, devuelve un array con un mensaje de error.
     *
     * ---
     * ### Ejemplo de uso:
     * #### Ejemplo 1: Generación exitosa de atributos `data-*`
     * ```php
     * $extra_params = [
     *     'id' => '123',
     *     'name' => 'producto',
     *     'categoria' => 'electrónica'
     * ];
     *
     * $resultado = $this->extra_params($extra_params);
     * ```
     * **Salida esperada:**
     * ```php
     * " data-id = '123' data-name = 'producto' data-categoria = 'electrónica'"
     * ```
     *
     * ---
     * #### Ejemplo 2: Error por clave numérica en `$extra_params`
     * ```php
     * $extra_params = [
     *     0 => 'valor_invalido',
     *     'tipo' => 'general'
     * ];
     *
     * $resultado = $this->extra_params($extra_params);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     "mensaje" => "Error $data bede ser un texto valido",
     *     "data" => [
     *         0 => "valor_invalido",
     *         "tipo" => "general"
     *     ],
     *     "es_final" => true
     * )
     * ```
     *
     * ---
     * @version 1.0.0
     */
    private function extra_params(array $extra_params): array|string
    {
        $extra_params_html = '';

        foreach ($extra_params as $data => $val) {
            // Verifica si la clave es numérica
            if (is_numeric($data)) {
                return $this->error->error(
                    mensaje: 'Error $data bede ser un texto valido',
                    data: $extra_params,
                    es_final: true
                );
            }

            // Construye la cadena de atributos HTML
            $extra_params_html .= " data-$data = '$val'";
        }

        return $extra_params_html;
    }


    /**
     * REG
     * Genera un conjunto de parámetros adicionales a partir de claves proporcionadas y un registro (fila de datos).
     *
     * Esta función toma un array de claves (`$extra_params_key`) y un array de datos (`$row`).
     * Luego, busca cada clave en `$row` y genera un nuevo array con los valores encontrados.
     * Si alguna clave no está presente en `$row`, se asigna el valor `"SIN DATOS"` por defecto.
     *
     * ---
     * ### **Validaciones realizadas**:
     * 1. Se recorre `$extra_params_key` y se validan las claves:
     *    - Si una clave está vacía, se devuelve un error.
     * 2. Se verifica si la clave existe en `$row`:
     *    - Si no existe, se asigna `"SIN DATOS"`.
     * 3. Se retorna el array `$extra_params` con los valores obtenidos.
     *
     * ---
     * ### **Parámetros**:
     * @param array $extra_params_key Array de claves que se buscarán dentro de `$row`.
     *                                Cada clave representa un índice del array `$row`.
     * @param array $row Array asociativo que contiene los datos de entrada.
     *                   Puede ser un resultado de una consulta a la base de datos u otro conjunto de datos.
     *
     * ---
     * ### **Retorno**:
     * - **array**: Un array con las claves proporcionadas y sus respectivos valores extraídos de `$row`.
     * - **array (error)**: Si una clave en `$extra_params_key` está vacía, se devuelve un array con el mensaje de error.
     *
     * ---
     * ### **Ejemplos de uso**:
     *
     * #### **Ejemplo 1: Obtener valores de claves existentes**
     * ```php
     * $extra_params_key = ['nombre', 'apellido', 'email'];
     * $row = [
     *     'nombre' => 'Juan',
     *     'apellido' => 'Pérez',
     *     'email' => 'juan@example.com'
     * ];
     * $resultado = $this->extra_param_data($extra_params_key, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * [
     *     'nombre' => 'Juan',
     *     'apellido' => 'Pérez',
     *     'email' => 'juan@example.com'
     * ]
     * ```
     *
     * ---
     * #### **Ejemplo 2: Claves no existentes en `$row`**
     * ```php
     * $extra_params_key = ['telefono', 'direccion'];
     * $row = [
     *     'nombre' => 'Juan',
     *     'email' => 'juan@example.com'
     * ];
     * $resultado = $this->extra_param_data($extra_params_key, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * [
     *     'telefono' => 'SIN DATOS',
     *     'direccion' => 'SIN DATOS'
     * ]
     * ```
     *
     * ---
     * #### **Ejemplo 3: Error por clave vacía en `$extra_params_key`**
     * ```php
     * $extra_params_key = ['nombre', '', 'email'];
     * $row = [
     *     'nombre' => 'Juan',
     *     'email' => 'juan@example.com'
     * ];
     * $resultado = $this->extra_param_data($extra_params_key, $row);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     'mensaje' => 'Error key_extra_param esta vacio',
     *     'data' => '',
     *     'es_final' => true
     * )
     * ```
     *
     * ---
     * @version 1.0.0
     */
    private function extra_param_data(array $extra_params_key, array $row): array
    {
        $extra_params = array();

        // Recorre las claves proporcionadas en $extra_params_key
        foreach ($extra_params_key as $key_extra_param) {
            $key_extra_param = trim($key_extra_param);

            // Si la clave está vacía, retorna un error
            if ($key_extra_param === '') {
                return $this->error->error(mensaje: 'Error key_extra_param esta vacio',
                    data: $key_extra_param, es_final: true);
            }

            // Si la clave no existe en $row, se asigna "SIN DATOS"
            if (!isset($row[$key_extra_param])) {
                $row[$key_extra_param] = 'SIN DATOS';
            }

            // Se almacena el valor correspondiente en el array final
            $extra_params[$key_extra_param] = $row[$key_extra_param];
        }

        return $extra_params;
    }


    /**
     * REG
     * Genera un elemento HTML `<input>` de tipo `date` o `datetime-local`.
     *
     * Este método construye un input HTML para fechas o fechas con hora, según el valor del parámetro `$value_hora`.
     * Valida los parámetros de entrada y genera el HTML con atributos `disabled`, `required`, `placeholder`, `id` y `name`.
     *
     * @param bool        $disabled     Indica si el input estará deshabilitado (`true`) o no (`false`).
     * @param string      $id_css       ID y clase CSS del input. Debe ser un texto no vacío.
     * @param string      $name         Nombre del input (atributo `name`). No puede estar vacío ni ser numérico.
     * @param string      $place_holder Texto que aparece como placeholder del input. No puede estar vacío.
     * @param bool        $required     Indica si el campo será obligatorio (`required`) o no.
     * @param mixed       $value        Valor que se mostrará en el input (por ejemplo: `2025-03-21` o `2025-03-21T14:30`).
     * @param bool        $value_hora   Si es `true`, se usará el tipo `datetime-local`. Por defecto es `false` (`date`).
     *
     * @return array|string Retorna el HTML del input como string si no hay errores, o un array con el error en caso contrario.
     *
     * @example
     * ```php
     * $html = new html();
     * echo $html->fecha(
     *     disabled: false,
     *     id_css: 'fecha_inicio',
     *     name: 'fecha_inicio',
     *     place_holder: 'Selecciona una fecha',
     *     required: true,
     *     value: '2025-03-21'
     * );
     * // Salida:
     * // <input type='date' name='fecha_inicio' value='2025-03-21' |class|  required id='fecha_inicio' placeholder='Selecciona una fecha' />
     * ```
     *
     * @example
     * ```php
     * echo $html->fecha(
     *     disabled: false,
     *     id_css: 'fecha_hora_evento',
     *     name: 'fecha_hora_evento',
     *     place_holder: 'Fecha y hora',
     *     required: true,
     *     value: '2025-03-21T14:30',
     *     value_hora: true
     * );
     * // Salida:
     * // <input type='datetime-local' name='fecha_hora_evento' value='2025-03-21T14:30' |class|  required id='fecha_hora_evento' placeholder='Fecha y hora' />
     * ```
     *
     * @see html::valida_params_txt() Para la validación de parámetros.
     * @see html::params_txt() Para la generación de los parámetros del input.
     */
     public function fecha(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                          mixed $value, bool $value_hora = false): array|string
     {

        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $type = 'date';
        if($value_hora){
            $type = 'datetime-local';
        }

        $html = "<input type='$type' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= "id='$params->id_css' placeholder='$params->place_holder' />";
        return $html;
    }

    /**
     * Genera un input de tipo file
     * @param bool $disabled attr disabled
     * @param string $id_css identificador css
     * @param string $name Name input
     * @param string $place_holder attr place holder
     * @param bool $required attr required
     * @param mixed $value value input
     * @return string|array
     */
    final public function file(bool $disabled, string $id_css, string $name,
                               string $place_holder, bool $required, mixed $value, bool $multiple = false): string|array
    {

        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required, multiple: $multiple);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='file' name='$params->name' value='$value' class = 'form-control' $params->disabled $params->required ";
        $html.= "id='$id_css' $params->multiple />";
        return $html;
    }

    /**
     * REG
     * Integra una nueva opción `<option>` dentro del conjunto de opciones de un `<select>`.
     *
     * Esta función genera un nuevo `<option>` a partir de la descripción, valor y atributos adicionales,
     * y lo concatena a un string de opciones existentes (`$options_html`).
     *
     * ---
     * ### **Validaciones realizadas**:
     * - Se llama a `option_html()` para validar los datos de entrada y generar el nuevo `<option>`.
     * - Si `option_html()` devuelve un error, se propaga el error.
     * - Si la generación es exitosa, el nuevo `<option>` se concatena a `$options_html`.
     *
     * ---
     * ### **Parámetros**:
     * @param string $descripcion_select Texto que se mostrará dentro del `<option>`.
     * @param int|null|string|float $id_selected Valor actualmente seleccionado en el `<select>`, usado para marcar el `<option>` como `selected`.
     * @param string $options_html String con las opciones ya generadas, al cual se añadirá el nuevo `<option>`.
     * @param int|null|string|float $value Valor del `<option>`, el cual se enviará al servidor cuando el usuario lo seleccione.
     * @param array $extra_params Atributos adicionales para el `<option>`, en formato `clave => valor` (Ejemplo: `['data-id' => '123']`).
     *
     * ---
     * ### **Retorno**:
     * - **string**: Si todo es correcto, devuelve un string con `$options_html` incluyendo la nueva opción agregada.
     * - **array**: Si ocurre un error, devuelve un array con el mensaje de error correspondiente.
     *
     * ---
     * ### **Ejemplos de uso**:
     *
     * #### **Ejemplo 1: Agregar una opción a un select vacío**
     * ```php
     * $descripcion_select = "Opción 1";
     * $id_selected = null;
     * $options_html = "";
     * $value = 1;
     *
     * $resultado = $this->integra_options_html($descripcion_select, $id_selected, $options_html, $value);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="1">Opción 1</option>
     * ```
     *
     * ---
     * #### **Ejemplo 2: Agregar una opción a un conjunto de opciones existente**
     * ```php
     * $descripcion_select = "Opción 2";
     * $id_selected = 2;
     * $options_html = "<option value='1'>Opción 1</option>";
     * $value = 2;
     *
     * $resultado = $this->integra_options_html($descripcion_select, $id_selected, $options_html, $value);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="1">Opción 1</option><option value="2" selected>Opción 2</option>
     * ```
     *
     * ---
     * #### **Ejemplo 3: Agregar una opción con atributos adicionales**
     * ```php
     * $descripcion_select = "Opción con datos";
     * $id_selected = 3;
     * $options_html = "<option value='1'>Opción 1</option><option value='2'>Opción 2</option>";
     * $value = 3;
     * $extra_params = ['data-extra' => 'info'];
     *
     * $resultado = $this->integra_options_html($descripcion_select, $id_selected, $options_html, $value, $extra_params);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="1">Opción 1</option><option value="2">Opción 2</option>
     * <option value="3" selected data-extra="info">Opción con datos</option>
     * ```
     *
     * ---
     * #### **Ejemplo 4: Error por descripción vacía**
     * ```php
     * $descripcion_select = "";
     * $id_selected = 1;
     * $options_html = "<option value='1'>Opción 1</option>";
     * $value = 2;
     *
     * $resultado = $this->integra_options_html($descripcion_select, $id_selected, $options_html, $value);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     'mensaje' => 'Error $descripcion_select no puede venir vacio',
     *     'data' => ''
     * )
     * ```
     *
     * ---
     * @version 1.0.0
     */
    private function integra_options_html(string $descripcion_select, int|null|string|float $id_selected,
                                          string $options_html, int|null|string|float $value,
                                          array $extra_params = array()): array|string
    {
        // Generar la opción utilizando `option_html`
        $option_html = $this->option_html(descripcion_select: $descripcion_select, id_selected: $id_selected,
            value: $value, extra_params: $extra_params);

        // Si hubo un error, retornarlo
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar option', data: $option_html);
        }

        // Concatenar la opción generada al conjunto de opciones existentes
        $options_html .= $option_html;

        return $options_html;
    }


    /**
     * REG
     * Valida los parámetros `$id_css` y `$place_holder`, asegurándose de que ambos no estén vacíos.
     *
     * Esta función valida los valores proporcionados para los parámetros `$id_css` y `$place_holder`. Ambos parámetros
     * deben ser cadenas no vacías. Si alguno de los parámetros está vacío, se genera un error con un mensaje descriptivo.
     * Si ambos parámetros son válidos, la función genera un HTML de etiqueta (aunque actualmente solo retorna una cadena vacía).
     *
     * **Pasos de validación:**
     * 1. Se elimina cualquier espacio en blanco al principio y al final de los valores de `$id_css` y `$place_holder`.
     * 2. Se valida que `$id_css` no esté vacío.
     * 3. Se valida que `$place_holder` no esté vacío.
     * 4. Si alguna de las validaciones falla, se genera un error con un mensaje descriptivo.
     * 5. Si ambas validaciones son correctas, se retorna una cadena vacía, ya que el código actual no genera la etiqueta HTML.
     *
     * **Parámetros:**
     *
     * @param string $id_css El identificador CSS de la etiqueta. Este parámetro es obligatorio y no debe estar vacío.
     *                       Se utiliza para generar un identificador único en el HTML.
     * @param string $place_holder El texto que se mostrará como marcador de posición dentro del campo de entrada.
     *                             Este parámetro es obligatorio y no debe estar vacío.
     *
     * **Retorno:**
     * - Devuelve una cadena vacía si ambos parámetros no están vacíos y son válidos.
     * - Si alguno de los parámetros está vacío, devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Validación exitosa**
     * ```php
     * $id_css = "usuario_id";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->label($id_css, $place_holder);
     * // Retorna "" porque ambos parámetros son válidos.
     * ```
     *
     * **Ejemplo 2: Error por $id_css vacío**
     * ```php
     * $id_css = "";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->label($id_css, $place_holder);
     * // Retorna un arreglo de error: 'Error el $id_css esta vacio'.
     * ```
     *
     * **Ejemplo 3: Error por $place_holder vacío**
     * ```php
     * $id_css = "usuario_id";
     * $place_holder = "";
     * $resultado = $this->label($id_css, $place_holder);
     * // Retorna un arreglo de error: 'Error el $place_holder esta vacio'.
     * ```
     *
     * **@version 1.0.0**
     */
    public function label(string $id_css, string $place_holder): string|array
    {
        // Eliminar espacios en blanco al principio y al final de los valores
        $id_css = trim($id_css);
        if($id_css === ''){
            return $this->error->error(mensaje: 'Error el $id_css esta vacio', data: $id_css, es_final: true);
        }

        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error el $place_holder esta vacio', data: $place_holder, es_final: true);
        }

        // Actualmente no se genera ninguna etiqueta HTML, solo se retorna una cadena vacía
        return "";
    }


    /**
     * REG
     * Limpia el contenido HTML eliminando los espacios adicionales.
     *
     * Esta función recibe un fragmento de HTML y reemplaza múltiples espacios consecutivos por un solo espacio.
     * Además, realiza un reemplazo específico para corregir las secuencias de `  /` a ` /` en el HTML.
     * Esto asegura que el HTML sea más limpio y consistente para su uso posterior, evitando problemas de formato.
     *
     * **Pasos de procesamiento:**
     * 1. Reemplaza los espacios consecutivos (dos o más espacios) por un solo espacio, haciendo que el HTML sea más compacto.
     * 2. Realiza un reemplazo adicional para corregir las secuencias de `  /` en los atributos HTML, convirtiéndolas a ` /`.
     * 3. El resultado es un HTML más limpio con menos espacio innecesario.
     *
     * **Parámetros:**
     *
     * @param string $html El fragmento de HTML que se va a limpiar. Este parámetro es obligatorio y debe contener una cadena de texto que represente el HTML a procesar.
     *
     * **Retorno:**
     * - Devuelve el HTML limpio con los espacios consecutivos reducidos y las secuencias `  /` corregidas.
     * - Si se pasa un HTML vacío o mal formado, la función devolverá el HTML tal cual sin modificaciones.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Limpiar HTML con espacios consecutivos**
     * ```php
     * $html = "<div  class='container'>  <p>Texto de ejemplo</p>  </div>";
     * $resultado = $this->limpia_salida($html);
     * // Retorna: "<div class='container'> <p>Texto de ejemplo</p> </div>"
     * ```
     *
     * **Ejemplo 2: Limpiar HTML con secuencias de `  /`**
     * ```php
     * $html = "<img src='image.jpg'  / >";
     * $resultado = $this->limpia_salida($html);
     * // Retorna: "<img src='image.jpg' / >"
     * ```
     *
     * **Ejemplo 3: HTML sin cambios**
     * ```php
     * $html = "<p>Texto limpio</p>";
     * $resultado = $this->limpia_salida($html);
     * // Retorna: "<p>Texto limpio</p>"
     * ```
     *
     * **@version 1.0.0**
     */
    final public function limpia_salida(string $html): array|string
    {
        // Reemplaza múltiples espacios consecutivos por un solo espacio
        $html_r = str_replace('  ', ' ', $html);
        $html_r = str_replace('  ', ' ', $html_r);
        $html_r = str_replace('  ', ' ', $html_r);
        $html_r = str_replace('  ', ' ', $html_r);
        $html_r = str_replace('  ', ' ', $html_r);

        // Realiza un reemplazo específico para corregir las secuencias de "  /" a " /"
        return str_replace('  /', ' /', $html_r);
    }


    /**
     * Genera un link en el menu lateral con un numero
     * @param string $etiqueta Etiqueta a mostrar del menu
     * @param string $number Numero de etiqueta
     * @return array|string
     */
    final public function link_menu_lateral(string $etiqueta, string $number): array|string
    {
        $number_html = $this->number_menu_lateral(number: $number);
        if(errores::$error){
            return $this->error->error(mensaje:  'Error al obtener numero ', data: $number_html);
        }
        $txt_link = $this->menu_lateral(etiqueta: $etiqueta);
        if(errores::$error){
            return $this->error->error(mensaje:  'Error al generar link', data: $txt_link);
        }

        return $number_html.$txt_link;

    }

    /**
     * Genera un texto de menu lateral
     * @param string $etiqueta Etiqueta del menu
     * @return string|array
     * @version 0.96.4
     */
    final public function menu_lateral(string $etiqueta): string|array
    {
        $etiqueta = trim($etiqueta);
        if($etiqueta === ''){
            return $this->error->error(mensaje: 'Error la etiqueta esta vacia', data: $etiqueta);
        }
        return "<span class='texto-menu-lateral'>$etiqueta</span>";
    }

    /**
     *  Integra un input de tipo monto
     * @param bool $disabled Atributo disabled si true
     * @param string $id_css Css
     * @param string $name Atributo name
     * @param string $place_holder Atributo place holder1
     * @param bool $required Atributo required si true
     * @param mixed $value Value input
     * @return array|string
     * @final rev
     * @version 6.25.2
     */
    public function monto(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                          mixed $value): array|string
    {
        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= "id='$params->id_css' placeholder='$params->place_holder' />";
        return $html;
    }

    /**
     * Genera un numero en img para menu lateral
     * @param string $number numero
     * @return string|array
     * @version 0.100.4
     */
    private function number_menu_lateral(string $number): string|array
    {
        $number = trim($number);
        if($number === ''){
            return $this->error->error(mensaje: 'Error number vacio', data: $number);
        }
        $img =  (new views())->url_assets."img/numeros/$number.svg";
        return "<img src='$img' class='numero'>";
    }

    /**
     * REG
     * Genera una etiqueta `<option>` en HTML para un elemento `<select>`, permitiendo atributos adicionales.
     *
     * Esta función recibe una descripción, un estado de selección (`selected`), un valor (`value`) y un array
     * de parámetros adicionales (`extra_params`). Si los datos son válidos, genera un `<option>` en formato HTML.
     *
     * ---
     * ### Validaciones realizadas:
     * - Se verifica que `descripcion` no esté vacío.
     * - Se valida que `value` no esté vacío o mal formado.
     * - Se genera el atributo `selected` si la opción está marcada como seleccionada.
     * - Se procesan parámetros adicionales como atributos `data-*` en HTML.
     *
     * ---
     * ### Parámetros:
     * @param string $descripcion La descripción que aparecerá dentro de la etiqueta `<option>`. Debe ser un string no vacío.
     * @param bool $selected Si es `true`, la opción se marcará como seleccionada (`selected`).
     * @param int|string $value El valor del atributo `value` del `<option>`. Si es `-1`, se convierte en un valor vacío.
     * @param array $extra_params Un array asociativo con atributos adicionales en formato `data-*`.
     *                            La clave representa el nombre del atributo y el valor su contenido.
     *
     * ---
     * ### Retorno:
     * - Devuelve un string con la etiqueta `<option>` correctamente generada.
     * - Si hay un error, devuelve un array con detalles del error.
     *
     * ---
     * ### Ejemplo de uso:
     *
     * #### Ejemplo 1: Generación exitosa de una opción seleccionada
     * ```php
     * $descripcion = "Opción 1";
     * $selected = true;
     * $value = 10;
     * $extra_params = ["categoria" => "electronica", "tipo" => "premium"];
     *
     * $resultado = $this->option($descripcion, $selected, $value, $extra_params);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value='10' selected data-categoria='electronica' data-tipo='premium'>Opción 1</option>
     * ```
     *
     * ---
     * #### Ejemplo 2: Generación de opción sin atributos adicionales
     * ```php
     * $descripcion = "Opción 2";
     * $selected = false;
     * $value = 5;
     *
     * $resultado = $this->option($descripcion, $selected, $value);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value='5'>Opción 2</option>
     * ```
     *
     * ---
     * #### Ejemplo 3: Opción con `value=-1` (se convierte en vacío)
     * ```php
     * $descripcion = "Opción Vacía";
     * $selected = false;
     * $value = -1;
     *
     * $resultado = $this->option($descripcion, $selected, $value);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value=''>Opción Vacía</option>
     * ```
     *
     * ---
     * #### Ejemplo 4: Error por descripción vacía
     * ```php
     * $descripcion = "";
     * $selected = false;
     * $value = 10;
     *
     * $resultado = $this->option($descripcion, $selected, $value);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     "mensaje" => "Error $descripcion no puede venir vacio",
     *     "data" => ""
     * )
     * ```
     *
     * ---
     * @version 1.0.0
     */
    private function option(
        string $descripcion, bool $selected, int|string $value, array $extra_params = array()
    ): string|array
    {
        // Validar que la descripción y el valor sean correctos
        $valida = $this->valida_option(descripcion: $descripcion, value: $value);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar option', data: $valida);
        }

        // Determinar si la opción debe estar seleccionada
        $selected_html = $selected ? 'selected' : '';

        // Generar los atributos `data-*` adicionales
        $extra_params_html = $this->extra_params(extra_params: $extra_params);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar extra params', data: $extra_params_html);
        }

        // Si el valor es `-1`, lo convertimos en una cadena vacía
        if ((int)$value === -1) {
            $value = '';
        }

        // Retornar el `<option>` generado
        return "<option value='$value' $selected_html $extra_params_html>$descripcion</option>";
    }


    /**
     * REG
     * Genera una opción (`<option>`) para un elemento `<select>` con atributos adicionales personalizados.
     *
     * ---
     * ### **Descripción**
     * Esta función construye dinámicamente una opción (`<option>`) con datos provenientes de `$row`.
     * - Primero, valida que `$row` contenga la clave `'descripcion_select'`.
     * - Luego, obtiene parámetros extra (`$extra_params_key`) del array `$row`.
     * - Se determina el valor final de la opción (`$value`) con `value_select()`.
     * - Finalmente, se integra la opción dentro del conjunto `$options_html_` mediante `integra_options_html()`.
     *
     * ---
     * ### **Parámetros**
     * @param array $extra_params_key
     *     - Claves de `$row` que se usarán como atributos `data-*` en la opción.
     *     - Cada clave extra será convertida en `data-clave='valor'`.
     *
     * @param int|string|float|null $id_selected
     *     - Valor que debe coincidir con `$value` para marcar la opción como `selected`.
     *     - Si `$value` coincide con este parámetro, la opción tendrá `selected`.
     *
     * @param string $options_html_
     *     - Contenido HTML previo de opciones `<option>`.
     *     - Se le agregará la nueva opción generada.
     *
     * @param array $row
     *     - Datos de la fila actual.
     *     - Debe contener la clave `'descripcion_select'`.
     *
     * @param int|string|float|null $row_id
     *     - Identificador de la fila (puede ser `null` si no hay ID).
     *     - Se usará a menos que `$value_custom` tenga un valor válido.
     *
     * @param string|int|float $value_custom
     *     - Valor personalizado para la opción.
     *     - Si no está vacío, reemplaza a `$row_id` como valor del `<option>`.
     *
     * ---
     * ### **Retorno**
     * - **string**: Devuelve el HTML actualizado con la nueva opción.
     * - **array**: Devuelve un array con información de error si ocurre algún problema.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Generar una opción sin parámetros extra**
     * ```php
     * $extra_params_key = [];
     * $id_selected = 2;
     * $options_html_ = "";
     * $row = ['descripcion_select' => 'Opción 1'];
     * $row_id = 1;
     * $value_custom = '';
     *
     * $resultado = $this->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
     * ```
     * **Salida esperada:**
     * ```php
     * "<option value='1'>Opción 1</option>"
     * ```
     *
     * ---
     * #### **Ejemplo 2: Generar una opción con atributos extra**
     * ```php
     * $extra_params_key = ['data-category', 'data-type'];
     * $id_selected = 2;
     * $options_html_ = "";
     * $row = [
     *     'descripcion_select' => 'Producto A',
     *     'data-category' => 'Electrónica',
     *     'data-type' => 'Accesorios'
     * ];
     * $row_id = 3;
     * $value_custom = '';
     *
     * $resultado = $this->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
     * ```
     * **Salida esperada:**
     * ```php
     * "<option value='3' data-category='Electrónica' data-type='Accesorios'>Producto A</option>"
     * ```
     *
     * ---
     * #### **Ejemplo 3: Opción con `selected` activado**
     * ```php
     * $extra_params_key = [];
     * $id_selected = 5;
     * $options_html_ = "";
     * $row = ['descripcion_select' => 'Seleccionado'];
     * $row_id = 5;
     * $value_custom = '';
     *
     * $resultado = $this->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
     * ```
     * **Salida esperada:**
     * ```php
     * "<option value='5' selected>Seleccionado</option>"
     * ```
     *
     * ---
     * @throws array Devuelve un array con un mensaje de error si ocurre algún problema en la validación.
     *
     * @version 1.0.0
     */
    private function option_con_extra_param(array $extra_params_key, int|null|string|float $id_selected,
                                            string $options_html_, array $row, int|string|float|null $row_id,
                                            string|int|float $value_custom): string|array
    {
        // Validar que el array `$row` contenga la clave necesaria
        $keys = ['descripcion_select'];
        $valida = (new validacion())->valida_existencia_keys(keys: $keys, registro: $row);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar row', data: $valida, es_final: true);
        }

        // Generar los atributos extra desde `$extra_params_key`
        $extra_params = $this->extra_param_data(extra_params_key: $extra_params_key, row: $row);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar extra params', data: $extra_params);
        }

        // Determinar el valor final (`row_id` o `value_custom`)
        $value = $this->value_select(row_id: $row_id, value_custom: $value_custom);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar value', data: $value);
        }

        // Construir la opción y añadirla a `$options_html_`
        $options_html_ = $this->integra_options_html(
            descripcion_select: $row['descripcion_select'],
            id_selected: $id_selected,
            options_html: $options_html_,
            value: $value,
            extra_params: $extra_params
        );
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar option', data: $options_html_);
        }

        return $options_html_;
    }


    /**
     * REG
     * Genera un elemento `<option>` en formato HTML para un `<select>`, con validaciones de entrada y atributos adicionales.
     *
     * Esta función toma una descripción (`$descripcion_select`), un valor (`$value`), y el valor seleccionado (`$id_selected`),
     * y genera un `<option>` en HTML. También permite incluir atributos adicionales en el `<option>` a través del array `$extra_params`.
     *
     * ---
     * ### **Validaciones realizadas**:
     * - Se verifica que `$descripcion_select` no esté vacío.
     * - Se limpia el valor `$value` y se asigna `-1` si está vacío.
     * - Se determina si la opción debe estar seleccionada (`selected`).
     * - Se genera el HTML del `<option>`, incluyendo los atributos extra proporcionados.
     *
     * ---
     * ### **Parámetros**:
     * @param string $descripcion_select Texto que se mostrará dentro del `<option>`.
     * @param int|null|string|float $id_selected Valor actualmente seleccionado en el `<select>`, usado para marcar el `<option>` como `selected`.
     * @param int|null|string|float $value Valor del `<option>`, el cual se enviará al servidor cuando el usuario lo seleccione.
     * @param array $extra_params Atributos adicionales para el `<option>`, en formato `clave => valor` (Ejemplo: `['data-id' => '123']`).
     *
     * ---
     * ### **Retorno**:
     * - Retorna un **string** con el HTML del `<option>` si la operación es exitosa.
     * - Retorna un **array** con un mensaje de error si ocurre alguna falla en la validación.
     *
     * ---
     * ### **Ejemplos de uso**:
     *
     * #### **Ejemplo 1: Generar un `<option>` sin atributos adicionales**
     * ```php
     * $descripcion_select = "Opción 1";
     * $id_selected = 2;
     * $value = 1;
     *
     * $resultado = $this->option_html($descripcion_select, $id_selected, $value);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="1">Opción 1</option>
     * ```
     *
     * ---
     * #### **Ejemplo 2: Generar un `<option>` con `selected`**
     * ```php
     * $descripcion_select = "Seleccionado";
     * $id_selected = 3;
     * $value = 3;
     *
     * $resultado = $this->option_html($descripcion_select, $id_selected, $value);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="3" selected>Seleccionado</option>
     * ```
     *
     * ---
     * #### **Ejemplo 3: Generar un `<option>` con atributos adicionales**
     * ```php
     * $descripcion_select = "Con Atributos";
     * $id_selected = 5;
     * $value = 5;
     * $extra_params = ['data-extra' => 'valor-extra', 'data-another' => 'otro-valor'];
     *
     * $resultado = $this->option_html($descripcion_select, $id_selected, $value, $extra_params);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="5" selected data-extra="valor-extra" data-another="otro-valor">Con Atributos</option>
     * ```
     *
     * ---
     * #### **Ejemplo 4: Error por descripción vacía**
     * ```php
     * $descripcion_select = "";
     * $id_selected = 1;
     * $value = 1;
     *
     * $resultado = $this->option_html($descripcion_select, $id_selected, $value);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     'mensaje' => 'Error $descripcion_select no puede venir vacio',
     *     'data' => ''
     * )
     * ```
     *
     * ---
     * #### **Ejemplo 5: Si el `$value` está vacío, se asigna `-1`**
     * ```php
     * $descripcion_select = "Sin valor";
     * $id_selected = 2;
     * $value = "";
     *
     * $resultado = $this->option_html($descripcion_select, $id_selected, $value);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="-1">Sin valor</option>
     * ```
     *
     * ---
     * @version 1.0.0
     */
    private function option_html(string $descripcion_select, int|null|string|float $id_selected,
                                 int|null|string|float $value, array $extra_params = array()): array|string
    {
        // Validación: `descripcion_select` no puede estar vacío
        $descripcion_select = trim($descripcion_select);
        if ($descripcion_select === '') {
            return $this->error->error(mensaje: 'Error $descripcion_select no puede venir vacio',
                data: $descripcion_select);
        }

        // Si `$value` está vacío, se asigna `-1`
        $value = trim($value);
        if ($value === '') {
            $value = -1;
        }

        // Determinar si la opción debe estar marcada como `selected`
        $selected = $this->selected(value: $value, id_selected: $id_selected);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al verificar selected', data: $selected);
        }

        // Generar el HTML del `<option>`
        $option_html = $this->option(descripcion: $descripcion_select, selected: $selected, value: $value,
            extra_params: $extra_params);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar option', data: $option_html);
        }

        return $option_html;
    }


    /**
     * REG
     * Genera el HTML de un conjunto de opciones `<option>` para un `<select>`, incluyendo una opción predeterminada.
     *
     * ---
     * ### **Descripción**
     * - Agrega una opción inicial por defecto con el valor `-1` y la descripción `"Selecciona una opcion"`.
     * - Genera opciones adicionales basadas en los valores proporcionados en `$values`.
     * - Usa `$columns_ds` para construir la descripción de cada opción.
     * - Integra atributos `data-*` usando `$extra_params_key`.
     * - Resalta la opción seleccionada con `$id_selected`.
     *
     * ---
     * ### **Parámetros**
     * @param array $columns_ds
     *     - Lista de nombres de columnas utilizadas para construir la `descripcion_select`.
     *     - Debe contener al menos un campo válido.
     *
     * @param array $extra_params_key
     *     - Lista de claves que se agregarán como atributos `data-*` en cada opción generada.
     *     - Cada clave debe existir en `$values` para evitar errores.
     *
     * @param int|float|string|null $id_selected
     *     - Valor de la opción que debe marcarse como `selected` en el `<select>`.
     *
     * @param string $key_value_custom
     *     - Clave dentro de `$values` que se usará para obtener un valor personalizado en cada opción.
     *
     * @param array $values
     *     - Conjunto de filas de datos de las cuales se generarán las opciones.
     *     - Cada fila (`$row`) debe ser un array asociativo con las claves necesarias.
     *
     * ---
     * ### **Retorno**
     * - **string**: Devuelve el HTML generado con las opciones `<option>` para un `<select>`.
     * - **array**: Devuelve un mensaje de error si alguna validación falla.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Generación de `<option>` a partir de datos de usuarios**
     * ```php
     * $columns_ds = ['nombre', 'apellido'];
     * $extra_params_key = ['email', 'telefono'];
     * $id_selected = 2;
     * $key_value_custom = 'id_usuario';
     * $values = [
     *     1 => ['nombre' => 'Juan', 'apellido' => 'Pérez', 'id_usuario' => 1, 'email' => 'juan@example.com', 'telefono' => '555-1234'],
     *     2 => ['nombre' => 'Ana', 'apellido' => 'Gómez', 'id_usuario' => 2, 'email' => 'ana@example.com', 'telefono' => '555-5678']
     * ];
     *
     * $resultado = $this->options($columns_ds, $extra_params_key, $id_selected, $key_value_custom, $values);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="-1">Selecciona una opcion</option>
     * <option value="1" data-email="juan@example.com" data-telefono="555-1234">Juan Pérez</option>
     * <option value="2" selected data-email="ana@example.com" data-telefono="555-5678">Ana Gómez</option>
     * ```
     *
     * ---
     * #### **Ejemplo 2: Datos incompletos en `$values`**
     * ```php
     * $values = [
     *     1 => ['nombre' => 'Carlos', 'id_usuario' => 1] // Falta 'apellido'
     * ];
     * $resultado = $this->options($columns_ds, $extra_params_key, $id_selected, $key_value_custom, $values);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error al validar row',
     *     'data' => ['descripcion_select' => null]
     * ]
     * ```
     *
     * ---
     * @throws array Devuelve un array con mensaje de error si:
     *  - `$values` no es un array válido.
     *  - `descripcion_select` no está presente en `$row`.
     *  - Alguna clave de `$extra_params_key` está vacía o no existe en `$row`.
     *
     * @version 1.0.0
     */
    private function options(
        array $columns_ds, array $extra_params_key, int|float|string|null $id_selected,
        string $key_value_custom, array $values): array|string
    {
        // Genera la opción predeterminada
        $options_html = $this->option(descripcion: 'Selecciona una opcion', selected: false, value: -1);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar option', data: $options_html);
        }

        // Genera el resto de las opciones basadas en `$values`
        $options_html = $this->options_html_data(columns_ds: $columns_ds, extra_params_key: $extra_params_key,
            id_selected: $id_selected, key_value_custom: $key_value_custom, options_html: $options_html,
            values: $values);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar options', data: $options_html);
        }

        return $options_html;
    }


    /**
     * REG
     * Genera el HTML de opciones `<option>` para un `<select>` a partir de un conjunto de valores.
     *
     * ---
     * ### **Descripción**
     * - La función itera sobre un conjunto de valores (`$values`), procesando cada fila (`$row`).
     * - Genera la `descripcion_select` combinando valores de `$columns_ds`.
     * - Obtiene un valor personalizado (`value_custom`) usando la clave `$key_value_custom`.
     * - Integra parámetros adicionales (`$extra_params_key`).
     * - Retorna el HTML completo de las opciones `<option>`.
     *
     * ---
     * ### **Parámetros**
     * @param array $columns_ds
     *     - Lista de nombres de columnas utilizadas para construir la `descripcion_select`.
     *     - Debe contener al menos un campo válido.
     *
     * @param array $extra_params_key
     *     - Lista de claves que se agregarán como atributos `data-*` en cada opción generada.
     *     - Cada clave se validará antes de agregarse a los `extra_params`.
     *
     * @param int|string|float|null $id_selected
     *     - Valor de la opción que debe marcarse como `selected` en el `<select>`.
     *
     * @param string $key_value_custom
     *     - Clave dentro de `$row` que se usará para obtener un valor personalizado en cada opción.
     *
     * @param string $options_html
     *     - HTML acumulado de opciones `<option>` previo a la ejecución de la función.
     *
     * @param array $values
     *     - Conjunto de filas de datos de las cuales se generarán las opciones.
     *     - Cada fila (`$row`) debe ser un array asociativo con las claves necesarias.
     *
     * ---
     * ### **Retorno**
     * - **string**: Devuelve el HTML generado con las opciones `<option>` para un `<select>`.
     * - **array**: Devuelve un mensaje de error si alguna validación falla.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Generación de `<option>` a partir de datos de usuarios**
     * ```php
     * $columns_ds = ['nombre', 'apellido'];
     * $extra_params_key = ['email', 'telefono'];
     * $id_selected = 2;
     * $key_value_custom = 'id_usuario';
     * $options_html = '';
     * $values = [
     *     1 => ['nombre' => 'Juan', 'apellido' => 'Pérez', 'id_usuario' => 1, 'email' => 'juan@example.com', 'telefono' => '555-1234'],
     *     2 => ['nombre' => 'Ana', 'apellido' => 'Gómez', 'id_usuario' => 2, 'email' => 'ana@example.com', 'telefono' => '555-5678']
     * ];
     *
     * $resultado = $this->options_html_data($columns_ds, $extra_params_key, $id_selected, $key_value_custom, $options_html, $values);
     * ```
     * **Salida esperada:**
     * ```html
     * <option value="1" data-email="juan@example.com" data-telefono="555-1234">Juan Pérez</option>
     * <option value="2" selected data-email="ana@example.com" data-telefono="555-5678">Ana Gómez</option>
     * ```
     *
     * ---
     * #### **Ejemplo 2: Datos incompletos en `$values`**
     * ```php
     * $values = [
     *     1 => ['nombre' => 'Carlos', 'id_usuario' => 1], // Falta 'apellido'
     * ];
     * $resultado = $this->options_html_data($columns_ds, $extra_params_key, $id_selected, $key_value_custom, $options_html, $values);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error al validar row',
     *     'data' => ['descripcion_select' => null]
     * ]
     * ```
     *
     * ---
     * @throws array Devuelve un array con mensaje de error si:
     *  - `$row` no es un array válido.
     *  - `descripcion_select` no está presente en `$row`.
     *  - Alguna clave de `$extra_params_key` está vacía o no existe en `$row`.
     *
     * @version 1.0.0
     */
    private function options_html_data(array $columns_ds, array $extra_params_key, int|null|string|float $id_selected,
                                       string $key_value_custom, string $options_html, array $values): array|string
    {
        $options_html_ = $options_html;

        foreach ($values as $row_id => $row) {
            // Validación: Cada fila debe ser un array asociativo
            if (!is_array($row)) {
                return $this->error->error(mensaje: 'Error el row debe ser un array', data: $row);
            }

            // Genera `descripcion_select` y `value_custom`
            $data_option = $this->data_option(columns_ds: $columns_ds, key_value_custom: $key_value_custom, row: $row);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar data option', data: $data_option);
            }

            // Genera las opciones `<option>` con parámetros extra
            $options_html_ = $this->option_con_extra_param(extra_params_key: $extra_params_key,
                id_selected: $id_selected, options_html_: $options_html_, row: $data_option->row, row_id: $row_id,
                value_custom: $data_option->value_custom);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al generar option', data: $options_html_);
            }
        }

        return $options_html_;
    }


    /**
     * Genera in input de tipo password
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador de tipo css
     * @param string $name Nombre del input
     * @param string $place_holder Contenido a mostrar previo a la captura del input
     * @param bool $required Si required aplica required en html
     * @param mixed $value Valor precargado
     * @return string|array
     * @version 0.108.4
     */
    final public function password(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                         mixed $value): string|array
    {
        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='password' name='$params->name' value='$value' class='form-control' ";
        $html .= " $params->disabled $params->required ";
        $html.= "id='$id_css' placeholder='$params->place_holder' />";
        return $html;
    }


    /**
     * REG
     * Genera un conjunto de parámetros HTML para un input tipo texto.
     *
     * Este método construye dinámicamente los atributos HTML requeridos para renderizar un campo de texto,
     * incluyendo `disabled`, `required`, `multiple`, `pattern`, `title`, `class`, `id`, entre otros.
     * Todos los atributos se integran en un objeto `stdClass` para su fácil uso en vistas.
     * Además, realiza validaciones previas sobre los datos requeridos, devolviendo errores detallados si algo falla.
     *
     * @version 1.0.0
     * @author Gamboa
     * @package base\frontend
     *
     * @param bool $disabled Si el input debe estar deshabilitado (`true`) o no (`false`).
     * @param string $id_css Identificador CSS principal del input (se añadirá también a la lista de IDs).
     * @param string $name Nombre del input (`name` HTML y referencia en `$row_upd`).
     * @param string $place_holder Texto guía que aparecerá como `placeholder`.
     * @param bool $required Si el campo es obligatorio.
     * @param array $class_css Clases CSS adicionales a aplicar. Ej: `['form-control', 'input-lg']`
     * @param bool $multiple Si el input puede aceptar múltiples valores.
     * @param array $ids_css IDs adicionales a incluir en el atributo `id`.
     * @param string $regex Expresión regular para validar el contenido (se aplica en `pattern`).
     * @param string $title Texto para mostrar como tooltip (`title` HTML). Si se omite, se usa `place_holder`.
     *
     * @return stdClass|array Devuelve un objeto con los atributos HTML generados, o un array de error en caso de fallar alguna validación.
     *
     * @example Ejemplo de uso básico:
     * ```php
     * $params = $this->params_txt(
     *     disabled: false,
     *     id_css: 'nombre_input',
     *     name: 'nombre',
     *     place_holder: 'Ingrese su nombre',
     *     required: true,
     *     class_css: ['form-control'],
     *     multiple: false,
     *     ids_css: ['extra-id'],
     *     regex: '[A-Za-z]{3,}',
     *     title: 'Nombre del usuario'
     * );
     *
     * if(is_array($params)) {
     *     echo "Error: " . $params['mensaje'];
     * } else {
     *     echo "<input type='text' name='{$params->name}' {$params->class} {$params->ids_css_html} {$params->disabled} {$params->required} {$params->regex} {$params->title}>";
     * }
     * ```
     *
     * @see valida_params_txt() Valida los parámetros básicos del input.
     * @see params_inputs::disabled_html()
     * @see params_inputs::required_html()
     * @see params_inputs::regex_html()
     * @see params_inputs::title_html()
     * @see params_inputs::class_html()
     * @see params_inputs::ids_html()
     */

    private function params_txt(
        bool $disabled, string $id_css, string $name,string $place_holder, bool $required, array $class_css = array(),
        bool $multiple = false, array $ids_css = array(), string $regex = '', string $title = ''): array|stdClass
    {

        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $disabled_html = (new params_inputs())->disabled_html(disabled:$disabled);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar $disabled_html', data: $disabled_html);
        }

        $required_html = (new params_inputs())->required_html(required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar $required_html', data: $required_html);
        }

        $multiple_html = (new params_inputs())->multiple_html(multiple: $multiple);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar $multiple_html', data: $multiple_html);
        }

        $regex_html = (new params_inputs())->regex_html(regex: $regex);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar regex_html', data: $regex_html);
        }

        $title_html = (new params_inputs())->title_html(place_holder: $place_holder, title: $title);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar title_html', data: $title_html);
        }

        $class_html = (new params_inputs())->class_html(class_css: $class_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar class_html', data: $class_html);
        }
        $ids_css[] = $id_css;
        $ids_css_html = (new params_inputs())->ids_html($ids_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar id_css', data: $ids_css_html);
        }

        $params = new stdClass();
        $params->name = $name;
        $params->id_css = $id_css;
        $params->place_holder = $place_holder;
        $params->disabled = $disabled_html;
        $params->required = $required_html;
        $params->regex = $regex_html;
        $params->title = $title_html;
        $params->class = $class_html;
        $params->ids_css_html = $ids_css_html;
        $params->multiple = $multiple_html;

        return $params;
    }

    /**
     * REG
     * Agrega una clave `descripcion_select` a `$row` concatenando valores de columnas definidas en `$columns_ds`.
     *
     * ---
     * ### **Descripción**
     * - La función toma un array de nombres de columnas `$columns_ds` y un array de datos `$row`.
     * - Si `$columns_ds` contiene columnas, genera una descripción concatenada a partir de los valores en `$row`.
     * - Utiliza la función `descripcion_select()` para construir la cadena.
     * - Agrega la clave `'descripcion_select'` a `$row` con el valor generado.
     * - Si `$columns_ds` está vacío, retorna `$row` sin modificaciones.
     *
     * ---
     * ### **Parámetros**
     * @param array $columns_ds
     *     - Lista de nombres de columnas a concatenar.
     *     - Cada columna debe existir en `$row`.
     *
     * @param array $row
     *     - Datos de la fila de donde se extraerán los valores de `$columns_ds`.
     *     - Se espera que contenga todas las claves mencionadas en `$columns_ds`.
     *
     * ---
     * ### **Retorno**
     * - **array**: Devuelve `$row` con la nueva clave `'descripcion_select'` si `$columns_ds` tiene datos.
     * - **array**: Devuelve un array con información de error si ocurre un problema.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Agregar `descripcion_select` concatenando varias columnas**
     * ```php
     * $columns_ds = ['nombre', 'apellido', 'edad'];
     * $row = ['nombre' => 'Juan', 'apellido' => 'Pérez', 'edad' => '30'];
     *
     * $resultado = $this->row_descripcion_select($columns_ds, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * [
     *     'nombre' => 'Juan',
     *     'apellido' => 'Pérez',
     *     'edad' => '30',
     *     'descripcion_select' => 'Juan Pérez 30'
     * ]
     * ```
     *
     * ---
     * #### **Ejemplo 2: Sin columnas en `$columns_ds`**
     * ```php
     * $columns_ds = [];
     * $row = ['nombre' => 'Carlos', 'apellido' => 'Ramírez'];
     *
     * $resultado = $this->row_descripcion_select($columns_ds, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * [
     *     'nombre' => 'Carlos',
     *     'apellido' => 'Ramírez'
     * ]
     * ```
     *
     * ---
     * #### **Ejemplo 3: Manejo de error si `$row` no contiene una clave de `$columns_ds`**
     * ```php
     * $columns_ds = ['nombre', 'apellido', 'direccion'];
     * $row = ['nombre' => 'Lucía', 'apellido' => 'Gómez'];
     *
     * $resultado = $this->row_descripcion_select($columns_ds, $row);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error al integrar descripcion select',
     *     'data' => [...]
     * ]
     * ```
     *
     * ---
     * @throws array Devuelve un array con un mensaje de error si ocurre un problema en la validación.
     *
     * @version 1.0.0
     */
    private function row_descripcion_select(array $columns_ds, array $row): array|string
    {
        // Verifica si hay columnas para generar la descripción
        if (count($columns_ds) > 0) {
            // Genera la descripción concatenada
            $descripcion_select = $this->descripcion_select(columns_ds: $columns_ds, row: $row);

            // Verifica errores en la concatenación
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar descripcion select', data: $descripcion_select);
            }

            // Agrega la descripción generada al array `$row`
            $row['descripcion_select'] = trim($descripcion_select);
        }

        return $row;
    }


    /**
     * Genera un input de tipo select
     * @param int $cols Numero de columnas css
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param string $label Etiqueta a mostrar
     * @param string $name Name input
     * @param array $values Valores para options
     * @param array $class_css Class estar para css
     * @param array $columns_ds Columnas a integrar a descripcion de option
     * @param bool $disabled Si disabled el input quedara disabled
     * @param array $extra_params_key keys de extra params para integrar valor
     * @param string $id_css Identificador css si esta vacio integra en name
     * @param string $key_value_custom
     * @param bool $required if required integra required a select
     * @return array|string
     * @author mgamboa
     */
    final public function select(int $cols, int|float|string|null $id_selected, string $label, string $name,
                                 array $values, array $class_css = array(), array $columns_ds = array(),
                                 bool $disabled = false, array $extra_params_key = array(), string $id_css = '',
                                 string $key_value_custom = '', bool $required = false): array|string
    {

        $label = trim($label);
        $name = trim($name);
        $valida = $this->valida_input_select(cols: $cols, label: $label, name: $name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar input', data: $valida);
        }

        $options_html = $this->options(columns_ds: $columns_ds, extra_params_key: $extra_params_key,
            id_selected: $id_selected, key_value_custom: $key_value_custom, values: $values);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar options', data: $options_html);
        }

        $select = $this->select_html(cols: $cols, label: $label, name: $name, options_html: $options_html,
            class_css: $class_css, disabled: $disabled, id_css: $id_css, required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }

        return $select;

    }

    /**
     * Genera un select en forma de html completo
     * @param int $cols Numero de columnas css
     * @param string $label Etiqueta a mostrar
     * @param string $name Name input
     * @param string $options_html Options precargados para select
     * @param array $class_css Class extra
     * @param bool $disabled Si disabled el input quedara inactivo
     * @param string $id_css Si existe lo integra en lugar del name
     * @param bool $required Si required se integra required como atributo del input
     * @return array|string
     */
    private function select_html(int $cols, string $label, string $name, string $options_html,
                                 array $class_css = array(), bool $disabled = false, string $id_css = '',
                                 bool $required = false): array|string
    {

        $label = trim($label);
        $name = trim($name);
        $valida = $this->valida_input_select(cols: $cols, label: $label, name: $name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar input', data: $valida);
        }

        $select = $this->div_select(name: $name, options_html: $options_html, class_css: $class_css,
            disabled: $disabled, id_css: $id_css, required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }

        $select = $this->div_controls(contenido: $select);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }

        $select = $this->div_control_group_cols_label(cols: $cols,contenido: $select,label: $label,name: $name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }
        return $select;
    }
    /**
     * REG
     * Verifica si un valor debe estar marcado como seleccionado en un elemento `<select>`.
     *
     * Esta función compara dos valores (`value` e `id_selected`) y determina si deben considerarse iguales.
     * Si los valores coinciden (convertidos a `string`), la función retorna `true`, indicando que la opción
     * debe estar marcada como `selected`. Si los valores no coinciden, devuelve `false`.
     *
     * ---
     * ### Validaciones realizadas:
     * - Convierte ambos valores a `string` antes de compararlos para evitar problemas de tipo.
     * - Retorna `true` si los valores coinciden y `false` en caso contrario.
     *
     * ---
     * ### Parámetros:
     * @param int|null|string|float $value El valor de la opción en el `<option>`. Puede ser de tipo `int`, `string`, `float` o `null`.
     * @param int|null|string|float $id_selected El valor seleccionado en el `<select>`, que se compara con `$value`.
     *
     * ---
     * ### Retorno:
     * - Retorna `true` si `$value` e `$id_selected` son iguales tras la conversión a `string`.
     * - Retorna `false` si los valores no coinciden.
     *
     * ---
     * ### Ejemplo de uso:
     *
     * #### Ejemplo 1: Coincidencia exacta entre valores enteros
     * ```php
     * $value = 5;
     * $id_selected = 5;
     * $resultado = $this->selected($value, $id_selected);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * ---
     * #### Ejemplo 2: Comparación entre `string` y `int` (coincidencia)
     * ```php
     * $value = "10";
     * $id_selected = 10;
     * $resultado = $this->selected($value, $id_selected);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * ---
     * #### Ejemplo 3: Diferentes valores numéricos (no coinciden)
     * ```php
     * $value = 3;
     * $id_selected = 8;
     * $resultado = $this->selected($value, $id_selected);
     * ```
     * **Salida esperada:**
     * ```php
     * false
     * ```
     *
     * ---
     * #### Ejemplo 4: `null` contra un valor numérico (no coinciden)
     * ```php
     * $value = null;
     * $id_selected = 7;
     * $resultado = $this->selected($value, $id_selected);
     * ```
     * **Salida esperada:**
     * ```php
     * false
     * ```
     *
     * ---
     * #### Ejemplo 5: Ambos valores son `null` (coincidencia)
     * ```php
     * $value = null;
     * $id_selected = null;
     * $resultado = $this->selected($value, $id_selected);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * ---
     * @version 1.0.0
     */
    final protected function selected(int|null|string|float $value, int|null|string|float $id_selected): bool
    {
        // Comparación convertida a string para asegurar coincidencias en diferentes tipos de datos
        return (string)$value === (string)$id_selected;
    }


    final public function submit(string $css, string $label): string|array
    {
        $css = trim($css);
        if($css === ''){
            return $this->error->error(mensaje: 'Error css esta vacio', data: $css);
        }
        $label = trim($label);
        if($label === ''){
            return $this->error->error(mensaje: 'Error label esta vacio', data: $label);
        }
        $btn = "<div class='control-group btn-modifica'>";
        $btn .= "<div class='controls'>";
        $btn .= "<button type='submit' class='btn btn-$css'>$label</button><br>";
        $btn .= "</div>";
        $btn .= "</div>";

        return $btn;

    }

    /**
     * Genera un input de tipo telefono
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador css
     * @param string $name Name input html
     * @param string $place_holder Muestra elemento en input
     * @param bool $required indica si es requerido o no
     * @param mixed $value Valor en caso de que exista
     * @return string|array
     * @version 0.112.4
     */
    final public function telefono(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                             mixed $value): string|array
    {
        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $valida = (new validacion());
        $keys = array('telefono_mx_html');
        $valida = (new validacion())->valida_existencia_keys(keys:$keys,registro:  $valida->patterns);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar patterns', data: $valida);
        }

        $regex = (new validacion())->patterns['telefono_mx_html'];

        $html = "<input type='text' name='$params->name' value='$value' class='form-control' ";
        $html .= " $params->disabled $params->required ";
        $html.= "id='$id_css' placeholder='$params->place_holder' pattern='$regex' />";
        return $html;
    }


    /**
     * Genera um input text basado en los parametros enviados
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador css
     * @param string $name Name input html
     * @param string $place_holder Muestra elemento en input
     * @param bool $required indica si es requerido o no
     * @param mixed $value Valor en caso de que exista
     * @param mixed $regex Integra regex a pattern
     * @param array $ids_css Integra los identificadores css
     * @return string|array Html en forma de input text
     * @version 0.9.0
     * @final rev
     */
    public function text(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                         mixed $value, array $ids_css = array(), string $regex = '', string $title = ''): string|array
    {



        $params = $this->params_txt(disabled: $disabled, id_css: $id_css, name: $name, place_holder: $place_holder,
            required: $required, ids_css: $ids_css, regex: $regex, title: $title);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= $params->ids_css_html." placeholder='$params->place_holder' $params->regex $params->title />";

        $html_r = $this->limpia_salida(html: $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }

    public function textarea(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                             mixed $value, array $ids_css = array()): string|array
    {
        $params = $this->params_txt(disabled: $disabled, id_css: $id_css, name: $name, place_holder: $place_holder,
            required: $required, ids_css: $ids_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<textarea name='$params->name' class='form-control' $params->disabled $params->required ";
        $html.= $params->ids_css_html." placeholder='$params->place_holder'/>";
        $html.= $value . "</textarea>";

        $html_r = $this->limpia_salida(html: $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }


    final public function text_base(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                                    mixed $value, array $class_css = array(), array $ids_css = array(),
                                    string $regex = '', string $title = ''): string|array
    {

        $params = $this->params_txt(disabled: $disabled, id_css: $id_css, name: $name, place_holder: $place_holder,
            required: $required, class_css: $class_css, ids_css: $ids_css, regex: $regex, title: $title);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' $params->class $params->disabled $params->required ";
        $html.= $params->ids_css_html." placeholder='$params->place_holder' $params->regex $params->title />";

        $html_r = $this->limpia_salida(html: $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }

    /**
     * Genera un input type de texto con clases asignadas
     * @param array $class_css Clases css a integrar
     * @param bool $disabled if disabled input disabled
     * @param string $id_css Ids a integrar
     * @param string $name name input
     * @param string $place_holder marca agua input
     * @param bool $required atributo required si es verdadero
     * @param mixed $value Valor
     * @param string $regex validacion de input
     * @param string $title titulo input
     * @return string|array
     * @version 8.25.0
     */
    final public function text_class(
        array $class_css, bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
        mixed $value, string $regex = '', string $title = ''): string|array
    {

        $name = trim($name);
        $place_holder = trim($place_holder);
        $id_css = trim($id_css);
        if($place_holder === ''){
            $place_holder = $name;
            $place_holder = str_replace('_', $place_holder, $place_holder);
            $place_holder = ucwords($place_holder);
        }
        if($id_css === ''){
            $id_css = $name;
        }

        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $params = $this->params_txt(disabled: $disabled, id_css: $id_css, name: $name, place_holder: $place_holder,
            required: $required, class_css: $class_css, regex: $regex, title: $title);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' $params->class $params->disabled $params->required ";
        $html.= "id='$id_css' placeholder='$params->place_holder' $params->regex $params->title />";

        $html_r = $this->limpia_salida(html: $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }



    /**
     * REG
     * Valida los datos de entrada para los campos `accion`, `etiqueta`, `seccion` y `style`.
     *
     * Esta función asegura que los valores proporcionados para los parámetros `accion`, `etiqueta`, `seccion` y `style`
     * no estén vacíos. Si alguno de estos valores está vacío, se genera un error con un mensaje específico.
     *
     * **Pasos de validación:**
     * 1. Se elimina cualquier espacio en blanco al principio y al final de los valores de los parámetros.
     * 2. Se valida que cada parámetro no esté vacío.
     * 3. Si alguno de los parámetros está vacío, se genera un error con un mensaje detallado.
     * 4. Si todas las validaciones son exitosas, se devuelve `true`.
     *
     * **Parámetros:**
     *
     * @param string $accion La acción que se va a realizar. Este parámetro es obligatorio y no debe estar vacío.
     *                       Representa la acción que se llevará a cabo en la interfaz de usuario.
     * @param string $etiqueta El texto que se mostrará como etiqueta del botón o campo. Este parámetro también es obligatorio
     *                         y no debe estar vacío.
     * @param string $seccion El nombre de la sección donde se llevará a cabo la acción. Este parámetro es obligatorio y no puede
     *                        estar vacío.
     * @param string $style El estilo CSS asociado al botón o campo. Este parámetro es obligatorio y no debe estar vacío.
     *
     * **Retorno:**
     * - Devuelve `true` si todos los parámetros están presentes y no están vacíos.
     * - Si alguno de los parámetros está vacío, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Validación exitosa**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $seccion = "usuarios";
     * $style = "btn-primary";
     *
     * $resultado = $this->valida_input($accion, $etiqueta, $seccion, $style);
     * // Retorna true porque todos los parámetros son válidos.
     * ```
     *
     * **Ejemplo 2: Error por parámetro vacío**
     * ```php
     * $accion = "";
     * $etiqueta = "Guardar cambios";
     * $seccion = "usuarios";
     * $style = "btn-primary";
     *
     * $resultado = $this->valida_input($accion, $etiqueta, $seccion, $style);
     * // Retorna un arreglo de error: 'Error la $accion esta vacia'.
     * ```
     *
     * **Ejemplo 3: Error por parámetro vacío (etiqueta)**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "";
     * $seccion = "usuarios";
     * $style = "btn-primary";
     *
     * $resultado = $this->valida_input($accion, $etiqueta, $seccion, $style);
     * // Retorna un arreglo de error: 'Error la $etiqueta esta vacia'.
     * ```
     *
     * **@version 1.0.0**
     */
    final public function valida_input(string $accion, string $etiqueta, string $seccion, string $style): true|array
    {
        $seccion = trim($seccion);
        if($seccion === ''){
            return $this->error->error(mensaje: 'Error la $seccion esta vacia', data: $seccion, es_final: true);
        }
        $accion = trim($accion);
        if($accion === ''){
            return $this->error->error(mensaje: 'Error la $accion esta vacia', data: $accion, es_final: true);
        }
        $style = trim($style);
        if($style === ''){
            return $this->error->error(mensaje: 'Error la $style esta vacia', data: $style, es_final: true);
        }
        $etiqueta = trim($etiqueta);
        if($etiqueta === ''){
            return $this->error->error(mensaje: 'Error la $etiqueta esta vacia', data: $etiqueta, es_final: true);
        }
        return true;
    }


    /**
     * REG
     * Valida los parámetros de entrada para un elemento `<select>` en un formulario HTML.
     *
     * Esta función se encarga de validar los parámetros que se utilizarán para la generación de un
     * elemento `<select>` en HTML. Se aseguran las siguientes condiciones:
     * - Que el número de columnas `$cols` sea un valor válido.
     * - Que la etiqueta `$label` no esté vacía.
     * - Que el nombre `$name` no esté vacío.
     *
     * Si alguno de los valores no es válido, la función devolverá un arreglo con el mensaje de error
     * correspondiente. En caso de éxito, retorna `true`.
     *
     * @param int $cols Número de columnas que ocupará el `select` en un formulario (valores esperados: 1 a 12).
     * @param string $label La etiqueta que se mostrará para el `select` (por ejemplo, "Selecciona una opción").
     * @param string $name El atributo `name` del `select`, que se utilizará en el formulario para la captura de datos.
     *
     * @return true|array Devuelve `true` si todos los parámetros son válidos.
     *                    Si hay algún error, devuelve un arreglo con un mensaje de error y los datos inválidos.
     *
     * @example Ejemplo 1: Validación exitosa
     * ```php
     * $cols = 6;
     * $label = "Categoría";
     * $name = "categoria_id";
     * $resultado = $this->valida_input_select($cols, $label, $name);
     * // Salida esperada: true
     * ```
     *
     * @example Ejemplo 2: Error por `$label` vacío
     * ```php
     * $cols = 6;
     * $label = "";
     * $name = "categoria_id";
     * $resultado = $this->valida_input_select($cols, $label, $name);
     * // Salida esperada:
     * // array(
     * //     "mensaje" => "Error el label está vacío",
     * //     "data" => "",
     * // )
     * ```
     *
     * @example Ejemplo 3: Error por `$name` vacío
     * ```php
     * $cols = 6;
     * $label = "Categoría";
     * $name = "";
     * $resultado = $this->valida_input_select($cols, $label, $name);
     * // Salida esperada:
     * // array(
     * //     "mensaje" => "Error el name está vacío",
     * //     "data" => "",
     * // )
     * ```
     *
     * @example Ejemplo 4: Error en la validación de columnas
     * ```php
     * $cols = -2;  // Número de columnas inválido
     * $label = "Categoría";
     * $name = "categoria_id";
     * $resultado = $this->valida_input_select($cols, $label, $name);
     * // Salida esperada:
     * // array(
     * //     "mensaje" => "Error al validar cols",
     * //     "data" => [detalles del error en la validación de columnas]
     * // )
     * ```
     */
    final protected function valida_input_select(int $cols, string $label, string $name): true|array
    {
        // Eliminar espacios en blanco en los extremos de los valores
        $label = trim($label);
        if ($label === '') {
            return $this->error->error(mensaje: "Error el label $label está vacío", data: $label, es_final: true);
        }

        $name = trim($name);
        if ($name === '') {
            return $this->error->error(mensaje: "Error el name $name está vacío", data: $name, es_final: true);
        }

        // Validar el número de columnas
        $valida = (new directivas(html: $this))->valida_cols(cols: $cols);
        if (errores::$error) {
            return $this->error->error(mensaje: "Error al validar cols", data: $valida);
        }

        return true;
    }


    /**
     * REG
     * Valida los valores de una opción antes de ser utilizada en un `select` o una lista desplegable.
     *
     * Esta función valida que los parámetros `$descripcion` y `$value` no estén vacíos.
     * Asegura que `$value` sea un valor válido y que `$descripcion` tenga un contenido no vacío.
     * Si alguno de los valores es inválido, la función devuelve un error detallado.
     *
     * ---
     * ### Validaciones realizadas:
     * - Se verifica que `$value` no sea una cadena vacía o contenga solo espacios.
     * - Se asegura que `$descripcion` no esté vacío.
     *
     * ---
     * ### Parámetros:
     * @param string $descripcion La descripción de la opción. Debe ser una cadena de texto no vacía.
     * @param int|string $value El valor de la opción. Puede ser un número entero o una cadena de texto válida.
     *
     * ---
     * ### Retorno:
     * - Devuelve `true` si ambos parámetros son válidos.
     * - Si `$descripcion` o `$value` están vacíos, devuelve un array con el mensaje de error correspondiente.
     *
     * ---
     * ### Ejemplo de uso:
     * #### Ejemplo 1: Validación exitosa
     * ```php
     * $descripcion = "Opción válida";
     * $value = 1;
     *
     * $resultado = $this->valida_option($descripcion, $value);
     * ```
     * **Salida esperada:**
     * ```php
     * true
     * ```
     *
     * ---
     * #### Ejemplo 2: Error por `$value` vacío
     * ```php
     * $descripcion = "Opción sin valor";
     * $value = "";
     *
     * $resultado = $this->valida_option($descripcion, $value);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     "mensaje" => "Error value no puede venir vacio",
     *     "data" => ""
     * )
     * ```
     *
     * ---
     * #### Ejemplo 3: Error por `$descripcion` vacío
     * ```php
     * $descripcion = "";
     * $value = "A1";
     *
     * $resultado = $this->valida_option($descripcion, $value);
     * ```
     * **Salida esperada (error):**
     * ```php
     * array(
     *     "mensaje" => "Error $descripcion no puede venir vacio",
     *     "data" => ""
     * )
     * ```
     *
     * ---
     * @version 1.0.0
     */
    final protected function valida_option(string $descripcion, int|string $value): true|array
    {
        // Limpiar espacios en blanco alrededor de los valores
        $value = trim((string)$value);
        if ($value === '') {
            return $this->error->error(mensaje: 'Error value no puede venir vacio', data: $value, es_final: true);
        }

        $descripcion = trim($descripcion);
        if ($descripcion === '') {
            return $this->error->error(mensaje: 'Error $descripcion no puede venir vacio', data: $descripcion,
                es_final: true);
        }

        return true;
    }


    /**
     * REG
     * Valida los parámetros necesarios para un input de tipo texto.
     *
     * Esta función se asegura de que los parámetros `$id_css`, `$name` y `$place_holder` no estén vacíos
     * después de aplicar `trim()`. Si alguno de ellos está vacío, se genera un error utilizando el
     * manejador de errores definido en la clase (`$this->error`).
     *
     * @param string $id_css ID o clase CSS asociada al input.
     *                       Ejemplo: 'form-control'
     *
     * @param string $name Nombre del campo del formulario.
     *                     Ejemplo: 'correo_electronico'
     *
     * @param string $place_holder Texto que se muestra como guía en el input.
     *                              Ejemplo: 'Ingrese su correo electrónico'
     *
     * @return true|array Retorna `true` si todos los parámetros son válidos.
     *                    Si alguno es inválido, retorna un arreglo con el error estructurado,
     *                    generado por `$this->error->error()`.
     *
     * @example Ejemplo de uso exitoso:
     * ```php
     * $resultado = $this->valida_params_txt('form-control', 'usuario', 'Nombre de usuario');
     * // Resultado:
     * // true
     * ```
     *
     * @example Ejemplo de uso con error:
     * ```php
     * $resultado = $this->valida_params_txt('form-control', '', 'Nombre de usuario');
     * // Resultado:
     * // [
     * //     'error' => true,
     * //     'mensaje' => 'Error name es necesario',
     * //     'data' => ''
     * // ]
     * ```
     *
     * @version 1.0.0
     */
    final protected function valida_params_txt(string $id_css, string $name, string $place_holder): true|array
    {
        $name = trim($name);
        if ($name === '') {
            return $this->error->error(mensaje: 'Error name es necesario', data: $name, es_final: true);
        }

        $id_css = trim($id_css);
        if ($id_css === '') {
            return $this->error->error(mensaje: 'Error $id_css es necesario', data: $id_css, es_final: true);
        }

        $place_holder = trim($place_holder);
        if ($place_holder === '') {
            return $this->error->error(mensaje: 'Error $place_holder es necesario', data: $place_holder,
                es_final: true);
        }

        return true;
    }


    /**
     * REG
     * Obtiene el valor personalizado de una clave en un array `$row`.
     *
     * ---
     * ### **Descripción**
     * - La función toma un nombre de clave (`$key_value_custom`) y un array de datos (`$row`).
     * - Si la clave está vacía, devuelve una cadena vacía (`''`).
     * - Si la clave no está vacía, obtiene el valor asociado en `$row` llamando a `value_custom_row()`.
     * - En caso de error, devuelve un mensaje de error.
     *
     * ---
     * ### **Parámetros**
     * @param string $key_value_custom
     *     - Nombre de la clave en `$row` de la que se desea obtener el valor.
     *     - Puede estar vacía (lo que devuelve `''`).
     *
     * @param array $row
     *     - Array de datos donde se buscará el valor de la clave.
     *
     * ---
     * ### **Retorno**
     * - **string**: Si la clave existe en `$row`, devuelve su valor sin espacios adicionales.
     * - **array**: Devuelve un mensaje de error si `value_custom_row()` detecta una clave vacía.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Clave existente en `$row`**
     * ```php
     * $row = ['codigo' => 'ABC123', 'precio' => 50];
     * $key_value_custom = 'codigo';
     *
     * $resultado = $this->value_custom($key_value_custom, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * 'ABC123'
     * ```
     *
     * ---
     * #### **Ejemplo 2: Clave no existente en `$row`**
     * ```php
     * $row = ['codigo' => 'XYZ789'];
     * $key_value_custom = 'descripcion';
     *
     * $resultado = $this->value_custom($key_value_custom, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * ''
     * ```
     *
     * ---
     * #### **Ejemplo 3: `$key_value_custom` vacío**
     * ```php
     * $row = ['codigo' => 'XYZ789'];
     * $key_value_custom = '';
     *
     * $resultado = $this->value_custom($key_value_custom, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * ''
     * ```
     *
     * ---
     * #### **Ejemplo 4: Manejo de error cuando `value_custom_row` detecta un problema**
     * ```php
     * $row = ['codigo' => 'XYZ789'];
     * $key_value_custom = '   '; // Clave vacía después de `trim()`
     *
     * $resultado = $this->value_custom($key_value_custom, $row);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error key_value_custom esta vacio',
     *     'data' => ''
     * ]
     * ```
     *
     * ---
     * @throws array Devuelve un array con mensaje de error si `$key_value_custom` es inválido.
     *
     * @version 1.0.0
     */
    private function value_custom(string $key_value_custom, array $row): array|string
    {
        // Elimina espacios en blanco de la clave
        $key_value_custom = trim($key_value_custom);

        // Inicializa con un valor vacío por defecto
        $value_custom = '';

        // Si la clave no está vacía, intenta obtener su valor en `$row`
        if ($key_value_custom !== '') {
            $value_custom = $this->value_custom_row(key_value_custom: $key_value_custom, row: $row);

            // Verifica si ocurrió un error en `value_custom_row`
            if (errores::$error) {
                return $this->error->error(
                    mensaje: 'Error al integrar value custom',
                    data: $value_custom
                );
            }
        }

        return $value_custom;
    }


    /**
     * REG
     * Obtiene el valor de una clave específica en un array `$row` y lo retorna como string.
     *
     * ---
     * ### **Descripción**
     * - La función recibe una clave (`$key_value_custom`) y un array de datos (`$row`).
     * - Si la clave está vacía, retorna un error.
     * - Si la clave no existe en `$row`, la inicializa con un valor vacío (`''`).
     * - Devuelve el valor de `$row[$key_value_custom]` en formato string, sin espacios en los extremos.
     *
     * ---
     * ### **Parámetros**
     * @param string $key_value_custom
     *     - Nombre de la clave a buscar en `$row`.
     *     - Debe ser un string no vacío.
     *
     * @param array $row
     *     - Array de datos en el que se buscará la clave `$key_value_custom`.
     *
     * ---
     * ### **Retorno**
     * - **string**: Devuelve el valor correspondiente a `$key_value_custom`, sin espacios adicionales.
     * - **array**: Retorna un array de error si `$key_value_custom` es inválido.
     *
     * ---
     * ### **Ejemplos de uso**
     *
     * #### **Ejemplo 1: Clave existente en `$row`**
     * ```php
     * $row = ['codigo' => 'ABC123', 'precio' => 50];
     * $key_value_custom = 'codigo';
     *
     * $resultado = $this->value_custom_row($key_value_custom, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * 'ABC123'
     * ```
     *
     * ---
     * #### **Ejemplo 2: Clave no existente en `$row`**
     * ```php
     * $row = ['codigo' => 'XYZ789'];
     * $key_value_custom = 'descripcion';
     *
     * $resultado = $this->value_custom_row($key_value_custom, $row);
     * ```
     * **Salida esperada:**
     * ```php
     * ''
     * ```
     *
     * ---
     * #### **Ejemplo 3: Manejo de error con `$key_value_custom` vacío**
     * ```php
     * $row = ['codigo' => 'XYZ789'];
     * $key_value_custom = '';
     *
     * $resultado = $this->value_custom_row($key_value_custom, $row);
     * ```
     * **Salida esperada (Error):**
     * ```php
     * [
     *     'mensaje' => 'Error key_value_custom esta vacio',
     *     'data' => ''
     * ]
     * ```
     *
     * ---
     * @throws array Devuelve un array con mensaje de error si `$key_value_custom` está vacío.
     *
     * @version 1.0.0
     */
    private function value_custom_row(string $key_value_custom, array $row): array|string
    {
        // Elimina espacios en blanco de la clave
        $key_value_custom = trim($key_value_custom);

        // Valida que la clave no esté vacía
        if ($key_value_custom === '') {
            return $this->error->error(
                mensaje: 'Error key_value_custom esta vacio',
                data: $key_value_custom,
                es_final: true
            );
        }

        // Si la clave no existe en `$row`, se inicializa con una cadena vacía
        if (!isset($row[$key_value_custom])) {
            $row[$key_value_custom] = '';
        }

        // Retorna el valor de la clave sin espacios adicionales
        return trim($row[$key_value_custom]);
    }


    /**
     * REG
     * Determina el valor final a utilizar en un `<option>` dentro de un `select`,
     * priorizando el valor personalizado (`$value_custom`) sobre el ID de la fila (`$row_id`).
     *
     * ---
     * ### **Descripción**
     * Esta función evalúa dos valores de entrada:
     * - `$row_id`: Identificador original del registro (puede ser `int`, `string`, `float`, `null`).
     * - `$value_custom`: Valor personalizado, que si está presente reemplaza a `$row_id`.
     *
     * Si `$value_custom` **no está vacío**, se usa en lugar de `$row_id`.
     * De lo contrario, se devuelve `$row_id`.
     *
     * ---
     * ### **Parámetros**:
     * @param int|string|float|null $row_id
     *     - Identificador del registro.
     *     - Puede ser `int`, `string`, `float` o `null`.
     *     - Se usará este valor si `$value_custom` está vacío.
     *
     * @param int|string|float $value_custom
     *     - Valor personalizado opcional.
     *     - Si no está vacío, reemplaza a `$row_id`.
     *
     * ---
     * ### **Retorno**:
     * - **string**: Devuelve el valor final en formato `string`, listo para ser usado en un `<option>`.
     *
     * ---
     * ### **Ejemplos de uso**:
     *
     * #### **Ejemplo 1: `$value_custom` vacío, se usa `$row_id`**
     * ```php
     * $row_id = 123;
     * $value_custom = '';
     * $resultado = $this->value_select($row_id, $value_custom);
     * ```
     * **Salida esperada:**
     * ```php
     * "123"
     * ```
     *
     * ---
     * #### **Ejemplo 2: `$value_custom` tiene valor, se usa en lugar de `$row_id`**
     * ```php
     * $row_id = 123;
     * $value_custom = 'codigo_ABC';
     * $resultado = $this->value_select($row_id, $value_custom);
     * ```
     * **Salida esperada:**
     * ```php
     * "codigo_ABC"
     * ```
     *
     * ---
     * #### **Ejemplo 3: `$row_id` es `null`, `$value_custom` vacío**
     * ```php
     * $row_id = null;
     * $value_custom = '';
     * $resultado = $this->value_select($row_id, $value_custom);
     * ```
     * **Salida esperada:**
     * ```php
     * ""
     * ```
     *
     * ---
     * #### **Ejemplo 4: `$row_id` es numérico pero `$value_custom` tiene prioridad**
     * ```php
     * $row_id = 500;
     * $value_custom = 'REF-500A';
     * $resultado = $this->value_select($row_id, $value_custom);
     * ```
     * **Salida esperada:**
     * ```php
     * "REF-500A"
     * ```
     *
     * ---
     * @version 1.0.0
     */
    private function value_select(int|string|float|null $row_id, int|string|float $value_custom): string
    {
        // Convertir a string y eliminar espacios en blanco
        $value = trim((string) $row_id);
        $value_custom = trim((string) $value_custom);

        // Si `$value_custom` tiene un valor, se usa en lugar de `$row_id`
        if ($value_custom !== '') {
            $value = $value_custom;
        }

        return $value;
    }

}
