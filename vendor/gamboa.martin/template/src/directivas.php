<?php
namespace gamboamartin\template;
use base\frontend\params_inputs;
use config\views;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

class directivas{
    protected errores $error;
    public html $html;
    public function __construct(html $html){
        $this->error = new errores();
        $this->html = $html;
    }

    /**
     * REG
     * Genera un botón HTML dinámicamente con los parámetros proporcionados.
     *
     * Este método permite generar un botón en HTML con diversos atributos configurables, como el estilo, el tipo,
     * los identificadores CSS, las clases CSS, los parámetros adicionales, etc.
     * Además, realiza una validación de los datos de entrada para garantizar que los valores proporcionados sean válidos
     * antes de generar el código HTML del botón.
     *
     * @param array $ids_css Un array de identificadores CSS que se asignarán al botón.
     *                       Cada valor del array será agregado como un identificador del atributo `id`.
     *
     * @param array $clases_css Un array de clases CSS que se aplicarán al botón.
     *                           Cada valor del array se agregará como una clase CSS al botón.
     *
     * @param array $extra_params Un array de parámetros adicionales que se agregarán al botón como atributos `data-*`.
     *                             El array debe contener claves como el nombre del atributo y sus respectivos valores.
     *
     * @param string $label El texto que se mostrará en el botón. Si está vacío, se usará el nombre del botón
     *                      con un formato adecuado.
     *
     * @param string $name El nombre del botón. Este valor será usado como el atributo `name` del botón en HTML.
     *
     * @param string $value El valor que se asignará al botón. Este valor será utilizado como el atributo `value` del botón.
     *
     * @param int $cols (Opcional) El número de columnas que ocupará el botón en el sistema de grillas de Bootstrap.
     *                  Por defecto, se asigna 6.
     *
     * @param string $style (Opcional) El estilo del botón según las clases de Bootstrap.
     *                       Por defecto, se usa el estilo 'info'. Otros valores posibles incluyen 'primary', 'danger', etc.
     *
     * @param string $type (Opcional) El tipo del botón, como 'button', 'submit', etc.
     *                       Por defecto, se usa el tipo 'button'.
     *
     * @return array|string Devuelve el código HTML del botón generado si la validación es exitosa.
     *                      Si ocurre un error durante la validación, se devuelve un array con el mensaje de error.
     *
     * @throws errores Si la validación de los datos falla.
     *
     * @example
     * // Ejemplo de uso del método para generar un botón con estilo 'primary', tipo 'submit', y con un ID específico.
     * $ids_css = ['btn_submit'];
     * $clases_css = ['extra-class'];
     * $extra_params = ['onclick' => 'alert("Botón presionado")'];
     * $label = 'Enviar';
     * $name = 'submit_form';
     * $value = 'submit';
     * $cols = 4;
     * $style = 'primary';
     * $type = 'submit';
     *
     * $boton_html = $directiva->btn($ids_css, $clases_css, $extra_params, $label, $name, $value, $cols, $style, $type);
     * echo $boton_html;
     * // Salida esperada: <button type='submit' class='btn btn-primary btn-guarda col-md-4 extra-class' id='btn_submit' name='submit_form' value='submit' data-onclick='alert("Botón presionado")'>Enviar</button>
     *
     * @version 1.0.0
     */
    final public function btn(array $ids_css, array $clases_css, array $extra_params, string $label, string $name,
                              string $value, int $cols = 6 , string $style = 'info', string $type = 'button'): array|string
    {
        // Se recortan los valores de los parámetros
        $label = trim($label);
        $name = trim($name);

        // Si la etiqueta está vacía, se usa el nombre como etiqueta
        if($label === ''){
            $label = $name;
            $label = str_replace('_', ' ', $label); // Reemplaza guiones bajos por espacios
            $label = ucwords($label); // Convierte la primera letra de cada palabra en mayúscula
        }

        // Validación de los datos antes de generar el HTML
        $valida = $this->valida_btn_next(label: $label, style: $style, type: $type, value: $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Generación de los identificadores CSS
        $ids_css_html = '';
        foreach ($ids_css as $id_css){
            $ids_css_html .= ' ' . $id_css;
        }

        // Generación de las clases CSS
        $clases_css_html = '';
        foreach ($clases_css as $class_css){
            $clases_css_html .= ' ' . $class_css;
        }

        // Generación de los parámetros adicionales
        $extra_params_data = '';
        foreach ($extra_params as $key => $value_param){
            $extra_params_data = " data-$key='$value_param' ";
        }

        // Construcción del botón en HTML
        $btn = "<button type='$type' class='btn btn-$style btn-guarda col-md-$cols $clases_css_html' id='$ids_css_html' ";
        $btn .= "name='$name' value='$value' $extra_params_data>$label</button>";

        return $btn; // Retorna el HTML del botón generado
    }



    /**
     * REG
     * Genera un botón HTML de acción con los parámetros proporcionados.
     *
     * Este método genera un botón en HTML, con diversos atributos configurables como el estilo, tipo,
     * valor y etiqueta. Utiliza el método `valida_btn_next` para validar los datos de entrada antes de
     * generar el código HTML. Si alguna validación falla, se devuelve un mensaje de error con detalles.
     *
     * @param string $label La etiqueta del botón. Este texto es lo que aparecerá dentro del botón en la interfaz de usuario.
     *                      No puede estar vacío.
     * @param string $value El valor del botón, que se asigna al atributo `value` del botón HTML. Este valor será enviado
     *                      al servidor cuando el botón sea presionado.
     * @param string $style El estilo del botón. Especifica la clase CSS de Bootstrap que se aplicará al botón.
     *                      El valor por defecto es `'info'`. Otros posibles valores son `'primary'`, `'danger'`, etc.
     * @param string $type El tipo de botón. Puede ser `'submit'`, `'button'`, entre otros. El valor por defecto es `'submit'`.
     *
     * @return string|array Devuelve el HTML del botón generado si las validaciones son exitosas. Si ocurre un error
     *                      durante la validación, devuelve un array con el mensaje de error y los detalles de la causa del error.
     *
     * @throws errores Si la validación de los datos falla, se lanzará un error.
     *
     * @example
     * // Caso exitoso: Generación de un botón de tipo submit con estilo 'primary' y valor 'submit'.
     * $label = "Enviar";
     * $value = "submit";
     * $style = "primary";
     * $type = "submit";
     * $boton_html = $directiva->btn_action_next($label, $value, $style, $type);
     * echo $boton_html;  // Resultado esperado: <button type='submit' class='btn btn-primary btn-guarda col-md-12' name='btn_action_next' value='submit'>Enviar</button>
     *
     * @example
     * // Caso de error: Si ocurre un error al validar los datos.
     * $label = "";
     * $value = "submit";
     * $boton_html = $directiva->btn_action_next($label, $value);
     * if (is_array($boton_html)) {
     *     echo $boton_html['mensaje'];  // Resultado: "Error al validar datos"
     * }
     *
     * @version 1.0.0
     */
    private function btn_action_next(
        string $label, string $value, string $style = 'info', string $type = 'submit'): string|array
    {
        // Validación de los datos antes de generar el HTML
        $valida = $this->valida_btn_next(label: $label, style: $style, type: $type, value: $value);
        if (errores::$error) {
            // Si hay un error en la validación, se devuelve el error
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Generación del HTML del botón
        $btn = "<button type='$type' class='btn btn-$style btn-guarda col-md-12' ";
        $btn .= "name='btn_action_next' value='$value'>$label</button>";

        // Retorna el HTML del botón generado
        return $btn;
    }


    /**
     * REG
     * Genera un botón dentro de un contenedor `div` con un número de columnas especificado.
     *
     * Este método crea un botón HTML utilizando la función `btn_action_next` y lo envuelve dentro de un contenedor `div`
     * con la clase Bootstrap correspondiente al número de columnas (`$cols`) especificado. Primero valida los parámetros
     * proporcionados, incluyendo el estilo, tipo, valor y número de columnas. Si alguna validación falla, devuelve un mensaje de error.
     * Si todo es válido, genera el botón y lo envuelve en un `div` con la clase `col-md-` correspondiente al número de columnas.
     *
     * @param string $label El texto que se mostrará en el botón. Este es el texto que aparece en el botón HTML.
     * @param string $value El valor que se asignará al botón como atributo `value` en HTML.
     * @param int $cols El número de columnas que el `div` ocupará en el sistema de grillas de Bootstrap. Por defecto, es 6.
     * @param string $style El estilo del botón, que se corresponde con las clases de Bootstrap (por ejemplo, 'info', 'primary').
     *                      Por defecto, es 'info'.
     * @param string $type El tipo del botón (por ejemplo, 'submit', 'button'). Por defecto, es 'submit'.
     *
     * @return array|string Devuelve el código HTML del `div` con el botón dentro si la validación es exitosa. Si ocurre un error,
     *                      devuelve un array con el mensaje de error correspondiente.
     *
     * @throws errores Si alguna de las validaciones de los parámetros falla, se lanza un error.
     *
     * @example
     * // Caso exitoso: Se genera un botón con estilo 'primary', tipo 'submit', y un número de columnas 4.
     * $label = "Enviar";
     * $value = "submit";
     * $cols = 4;
     * $style = 'primary';
     * $type = 'submit';
     * $resultado = $directiva->btn_action_next_div($label, $value, $cols, $style, $type);
     * echo $resultado;  // Resultado esperado: <div class='col-md-4'><button type='submit' class='btn btn-primary btn-guarda col-md-12' name='btn_action_next' value='submit'>Enviar</button></div>
     *
     * @example
     * // Caso de error: Se pasa un número de columnas inválido (por ejemplo, 13).
     * $label = "Enviar";
     * $value = "submit";
     * $cols = 13;  // Este valor es inválido ya que debe estar entre 1 y 12.
     * $resultado = $directiva->btn_action_next_div($label, $value, $cols);
     * if (is_array($resultado)) {
     *     echo $resultado['mensaje'];  // Resultado: "Error al validar columnas"
     * }
     *
     * @version 1.0.0
     */
    final public function btn_action_next_div(string $label, string $value, int $cols = 6, string $style = 'info',
                                              string $type = 'submit'): array|string
    {
        // Validación de los parámetros del botón
        $valida = $this->valida_btn_next(label: $label, style:  $style, type:  $type, value:  $value);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Validación del número de columnas
        $valida = $this->valida_cols(cols: $cols);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
        }

        // Generación del botón
        $btn = $this->btn_action_next(label: $label, value:  $value, style: $style, type: $type);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar btn datos', data: $btn);
        }

        // Retorna el HTML con el botón envuelto en un div con el número de columnas especificado
        return "<div class='col-md-$cols'>$btn</div>";
    }


    /**
     * REG
     * Genera un enlace HTML (`<a>`) para un botón con una etiqueta y los parámetros proporcionados.
     *
     * Esta función recibe varios parámetros como la acción, etiqueta, nombre, marcador de posición, ID de registro, sección, estilo y otros,
     * y genera un enlace HTML que funciona como un botón. Además, valida que todos los parámetros sean correctos. Si alguno de los parámetros es inválido,
     * se devuelve un mensaje de error.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que los parámetros `name` y `place_holder` no estén vacíos utilizando el método `valida_data_label`.
     * 2. Se valida que los parámetros `accion`, `etiqueta`, `seccion`, y `style` no estén vacíos utilizando el método `valida_input`.
     * 3. Si la validación es exitosa, se genera una etiqueta `label` utilizando `label_input`.
     * 4. Se genera el HTML del enlace utilizando el método `button_href` de la clase `html`.
     * 5. Se integra el HTML del enlace y la etiqueta en un `div` con el método `div_label`.
     * 6. Si ocurre algún error en cualquiera de los pasos anteriores, se devuelve un mensaje de error.
     * 7. Si todo es exitoso, se retorna el HTML generado para el `div` con el enlace y la etiqueta.
     *
     * **Parámetros:**
     *
     * @param string $accion La acción que se realizará cuando se haga clic en el botón.
     * @param string $etiqueta El texto que se mostrará en el botón.
     * @param string $name El nombre del campo que se utilizará para generar el identificador de la etiqueta.
     * @param string $place_holder El texto que se muestra como marcador de posición en el campo asociado a la etiqueta.
     * @param int $registro_id El ID del registro que se utilizará para la acción.
     * @param string $seccion El nombre de la sección donde se encuentra el botón.
     * @param string $style El estilo CSS del botón.
     *
     * **Retorno:**
     * - Devuelve el HTML de un `div` que contiene un enlace `<a>` con la etiqueta asociada si todos los parámetros son válidos.
     * - Si ocurre un error durante la validación o la generación del HTML, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Generación exitosa de un enlace**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $name = "guardar_id";
     * $place_holder = "Ingrese ID del usuario";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $resultado = $this->button_href($accion, $etiqueta, $name, $place_holder, $registro_id, $seccion, $style);
     * // Retorna un div con el HTML del enlace y la etiqueta.
     * ```
     *
     * **Ejemplo 2: Error debido a un `place_holder` vacío**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $name = "guardar_id";
     * $place_holder = "";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $resultado = $this->button_href($accion, $etiqueta, $name, $place_holder, $registro_id, $seccion, $style);
     * // Retorna un mensaje de error: 'Error $place_holder debe tener info'.
     * ```
     *
     * **Ejemplo 3: Error debido a un parámetro de entrada vacío**
     * ```php
     * $accion = "";
     * $etiqueta = "Guardar cambios";
     * $name = "guardar_id";
     * $place_holder = "Ingrese ID del usuario";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $resultado = $this->button_href($accion, $etiqueta, $name, $place_holder, $registro_id, $seccion, $style);
     * // Retorna un mensaje de error: 'Error al validar datos'.
     * ```
     *
     * **@version 1.0.0**
     */
    public function button_href(string $accion, string $etiqueta, string $name, string $place_holder,
                                int $registro_id, string $seccion, string $style): array|string
    {
        // Validación de los parámetros name y place_holder
        $valida = $this->valida_data_label(name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        // Validación de los parámetros de entrada (accion, etiqueta, seccion, style)
        $valida = $this->html->valida_input(accion: $accion,etiqueta:  $etiqueta, seccion: $seccion,style:  $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Generación de la etiqueta label
        $label = $this->label_input(name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        // Verificación de que el place_holder no esté vacío
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder debe tener info', data: $place_holder, es_final: true);
        }

        // Generación del HTML para el botón
        $html = $this->html->button_href(accion: $accion,etiqueta:  $etiqueta, registro_id: $registro_id,
            seccion:  $seccion, style: $style);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar html', data: $html);
        }

        // Integración de la etiqueta label con el HTML del botón en un div
        $div = $this->html->div_label(html: $html, label: $label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        // Retornar el div generado
        return $div;
    }


    /**
     * REG
     * Crea un botón de enlace (`<a>`) con un estado determinado y un estilo dinámico.
     *
     * Esta función genera un botón de enlace HTML con un estado determinado (activo o inactivo),
     * y un estilo dinámico. El estilo del botón será `danger` si el estado es inactivo y `info`
     * si el estado es activo. Además, valida que los parámetros proporcionados sean correctos
     * y genera un contenedor `div` con el botón correspondiente.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que el parámetro `$seccion` no esté vacío.
     * 2. Se valida que el parámetro `$status` no esté vacío.
     * 3. Se valida el número de columnas (`$cols`) usando el método `valida_cols`.
     * 4. Si el estado es 'activo', el estilo se ajusta a `info`, de lo contrario, se establece como `danger`.
     * 5. Se genera el enlace HTML utilizando el método `button_href` con los parámetros dados.
     * 6. Se genera un contenedor `div` que contiene el botón de enlace.
     * 7. Si ocurre un error en alguno de los pasos, se retorna un mensaje de error detallado.
     * 8. Si todo es exitoso, se retorna el contenedor `div` con el botón de enlace.
     *
     * **Parámetros:**
     *
     * @param int $cols El número de columnas que se utilizarán en el contenedor `div`. Este parámetro es obligatorio.
     * @param int $registro_id El ID del registro que se utilizará para la acción del botón.
     * @param string $seccion El nombre de la sección donde se llevará a cabo la acción.
     * @param string $status El estado del botón, que puede ser 'activo' o 'inactivo'.
     *
     * **Retorno:**
     * - Devuelve el HTML de un `div` que contiene el botón de enlace si todo es válido.
     * - Si ocurre un error durante la validación o la generación, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Generación de un botón de enlace válido**
     * ```php
     * $cols = 6;
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $status = "activo";
     * $resultado = $this->button_href_status($cols, $registro_id, $seccion, $status);
     * // Retorna el HTML de un div con un botón de enlace con estilo 'info'.
     * ```
     *
     * **Ejemplo 2: Error por estado vacío**
     * ```php
     * $cols = 6;
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $status = "";
     * $resultado = $this->button_href_status($cols, $registro_id, $seccion, $status);
     * // Retorna un mensaje de error: 'Error el $status esta vacio'.
     * ```
     *
     * **Ejemplo 3: Error por número de columnas inválido**
     * ```php
     * $cols = -1;
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $status = "activo";
     * $resultado = $this->button_href_status($cols, $registro_id, $seccion, $status);
     * // Retorna un mensaje de error: 'Error al validar cols'.
     * ```
     *
     * **@version 1.0.0**
     */
    public function button_href_status(int $cols, int $registro_id, string $seccion, string $status): array|string
    {
        // Validación del parámetro 'seccion'
        $seccion = trim($seccion);
        if($seccion === ''){
            return $this->error->error(mensaje: 'Error la $seccion esta vacia', data: $seccion, es_final: true);
        }

        // Validación del parámetro 'status'
        $status = trim($status);
        if($status === ''){
            return $this->error->error(mensaje: 'Error el $status esta vacio', data: $status, es_final: true);
        }

        // Validación de las columnas
        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        // Determinación del estilo en base al estado
        $style = 'danger';
        if($status === 'activo'){
            $style = 'info';
        }

        // Generación del HTML para el enlace
        $html = $this->button_href(accion: 'status',etiqueta: $status,name: 'status',
            place_holder: 'Status',registro_id: $registro_id,seccion: $seccion, style: $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $html);
        }

        // Generación del contenedor 'div' que contiene el enlace
        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        // Retorno del contenedor 'div' generado
        return $div;
    }


    /**
     * REG
     * Valida el valor de `checked_default` y genera indicadores `checked` para dos opciones.
     *
     * Este método recibe un valor entero (`1` o `2`) e inicializa un objeto con propiedades
     * `checked_default_v1` y `checked_default_v2`, las cuales contienen la cadena `'checked'`
     * si el valor corresponde a la opción seleccionada. Si el valor es inválido (<= 0 o > 2),
     * se devuelve un arreglo de error con información detallada.
     *
     * @param int $checked_default Valor que indica cuál opción debe marcarse como seleccionada:
     *                              - 1: Marca `checked_default_v1`.
     *                              - 2: Marca `checked_default_v2`.
     *
     * @return stdClass|array Objeto con propiedades `checked_default_v1` y `checked_default_v2`, o
     *                        un array de error si el valor no está en el rango permitido.
     *
     * @example
     * ```php
     * $checked_data = $this->checked_default(1);
     * if (is_array($checked_data)) {
     *     // Manejar error
     *     var_dump($checked_data);
     * } else {
     *     echo $checked_data->checked_default_v1; // "checked"
     *     echo $checked_data->checked_default_v2; // ""
     * }
     * ```
     */
    private function checked_default(int $checked_default): stdClass|array
    {
        if($checked_default <=0){
            return $this->error->error(mensaje: 'Error checked_default debe ser mayor a 0', data: $checked_default,
                es_final: true);
        }
        if($checked_default > 2){
            return $this->error->error(mensaje: 'Error checked_default debe ser menor a 3', data: $checked_default,
                es_final: true);
        }
        $checked_default_v1 = '';
        $checked_default_v2 = '';

        if($checked_default === 1){
            $checked_default_v1 = 'checked';
        }
        if($checked_default === 2){
            $checked_default_v2 = 'checked';
        }

        $data = new stdClass();
        $data->checked_default_v1 = $checked_default_v1;
        $data->checked_default_v2 = $checked_default_v2;
        return $data;
    }

    /**
     * REG
     * Genera una cadena de clases CSS para un elemento `<label>` de tipo `form-check`, asegurando que incluya
     * la clase `form-check-label` y procesando adecuadamente el array de clases recibido.
     *
     * Este método utiliza la clase `params_inputs` para generar el string de clases HTML a partir de un array.
     * También realiza una limpieza básica de espacios dobles.
     * En caso de error, retorna un arreglo con detalles del mismo.
     *
     * @param array $class_label Arreglo de clases CSS personalizadas a incluir en el label.
     *
     * @return array|string Retorna un string con las clases CSS concatenadas o un arreglo de error si falla el procesamiento.
     *
     * @example
     * ```php
     * $clases = $this->class_label_html(['text-primary', 'mb-2']);
     * if (is_array($clases)) {
     *     // Manejo del error
     *     var_dump($clases);
     * } else {
     *     echo $clases;
     *     // Salida: class="text-primary mb-2 form-check-label"
     * }
     * ```
     */
    private function class_label_html(array $class_label): array|string
    {
        $class_label[] = 'form-check-label';

        $class_label_html = (new params_inputs())->class_html(class_css: $class_label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar class_label', data: $class_label_html);
        }
        return str_replace('  ', ' ', $class_label_html);
    }

    /**
     * REG
     * Genera una cadena de clases CSS para un input tipo radio (`<input type="radio">`),
     * asegurando que contenga la clase `form-check-input` y procesando correctamente cualquier clase adicional proporcionada.
     *
     * Este método utiliza la clase `params_inputs` para construir el string de clases HTML a partir del array de entrada.
     * También realiza una limpieza de espacios dobles en el string final.
     * Si ocurre un error durante el procesamiento, se retorna un arreglo con la información del error.
     *
     * @param array $class_radio Arreglo de clases CSS personalizadas a incluir en el radio input.
     *
     * @return array|string Retorna el string con las clases HTML para el radio o un arreglo de error si algo falla.
     *
     * @example
     * ```php
     * $clases = $this->class_radio_html(['border', 'rounded']);
     * if (is_array($clases)) {
     *     // Manejar error
     *     var_dump($clases);
     * } else {
     *     echo $clases;
     *     // Salida esperada: class="border rounded form-check-input"
     * }
     * ```
     */
    private function class_radio_html(array $class_radio): array|string
    {
        $class_radio[] = 'form-check-input';
        $class_radio_html = (new params_inputs())->class_html(class_css: $class_radio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar class_radio_html', data: $class_radio_html);
        }
        return str_replace('  ', ' ', $class_radio_html);

    }


    /**
     * REG
     * Genera un contenedor `<div>` con una etiqueta `<label>` y un campo HTML proporcionado.
     *
     * Este método se encarga de validar los datos recibidos, generar la etiqueta (`<label>`)
     * correspondiente usando el `id` y el `placeholder`, encapsular el contenido HTML (`$html`)
     * junto con la etiqueta dentro de un `<div>`, y limpiar la salida antes de devolverla.
     *
     * Se utiliza comúnmente para formar estructuras consistentes en formularios,
     * especialmente inputs con sus etiquetas asociadas y estilos definidos.
     *
     * @param string $html Contenido HTML que se desea encapsular dentro del `div` (normalmente un `<input>`).
     * @param string $name Nombre del campo que será utilizado como `id` para el `label`.
     * @param string $place_holder Texto descriptivo que se usará como contenido del `label`.
     *
     * @return array|string Devuelve el HTML del `div` generado como string si no hay errores.
     *                      En caso de error, devuelve un array con detalles del mismo.
     *
     * @example Ejemplo con input text básico:
     * ```php
     * $input_html = "<input type='text' id='nombre' name='nombre' placeholder='Nombre completo' />";
     * echo $directivas->div_label(html: $input_html, name: 'nombre', place_holder: 'Nombre completo');
     *
     * // Salida esperada:
     * // <div class='form-group'>
     * //   <label for='nombre'>Nombre completo</label>
     * //   <input type='text' id='nombre' name='nombre' placeholder='Nombre completo' />
     * // </div>
     * ```
     *
     * @example Error cuando `$name` está vacío:
     * ```php
     * $resultado = $directivas->div_label(html: "<input ... />", name: '', place_holder: 'Texto');
     * // Resultado:
     * // ['mensaje' => 'Error el name esta vacio', ...]
     * ```
     *
     * @example Error cuando `$place_holder` está vacío:
     * ```php
     * $resultado = $directivas->div_label(html: "<input ... />", name: 'email', place_holder: '');
     * // Resultado:
     * // ['mensaje' => 'Error el $place_holder esta vacio', ...]
     * ```
     *
     * @see html::label() Genera la etiqueta HTML `<label>` con el texto del `placeholder`.
     * @see html::div_label() Genera el contenedor `<div>` con estructura y clases definidas.
     * @see html::limpia_salida() Limpia y normaliza el HTML generado antes de mostrarlo.
     */
    private function div_label(string $html, string $name, string $place_holder): array|string
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error el name esta vacio', data: $name, es_final: true);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error el $place_holder esta vacio', data: $place_holder,
                es_final: true);
        }

        $label = $this->html->label(id_css: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        $div = $this->html->div_label(html:  $html,label:$label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        $html_r = (new html())->limpia_salida(html: $div);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }

    /**
     * REG
     * Genera un contenedor HTML `<div>` con clase Bootstrap y radios integrados.
     *
     * Este método construye un bloque `div` con la clase `control-group col-sm-{cols}` que incluye una etiqueta
     * de texto (`$label_html`) y dos inputs tipo radio, previamente generados y entregados dentro de `$inputs`.
     *
     * Realiza validaciones de columnas y de las claves necesarias dentro del objeto `$inputs`.
     *
     * @param int $cols Cantidad de columnas Bootstrap (1-12) que ocupará el `div`. Debe ser un valor válido.
     * @param stdClass $inputs Objeto con las propiedades:
     *  - `label_input_v1`: string HTML del primer radio
     *  - `label_input_v2`: string HTML del segundo radio
     * @param string $label_html Texto de la etiqueta que acompaña a los radios (puede incluir HTML).
     *
     * @return string|array Devuelve un string con el HTML del `div` contenedor o un array con detalles del error.
     *
     * @example
     * ```php
     * $inputs = $this->labels_radios(
     *     name: 'tipo_pago',
     *     params: $params,
     *     title: 'Forma de Pago',
     *     val_1: 'Efectivo',
     *     val_2: 'Transferencia'
     * );
     * $html = $this->div_radio(cols: 6, inputs: $inputs, label_html: '<strong>Forma de Pago</strong>');
     * echo $html;
     * ```
     */
    private function div_radio(int $cols, stdClass $inputs, string $label_html): string|array
    {
        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error validar cols', data: $valida);
        }

        $keys = array('label_input_v1','label_input_v2');

        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error validar inputs', data: $valida);
        }

        $label_html = trim($label_html);

        return "<div class='control-group col-sm-$cols'>
            $label_html
            $inputs->label_input_v1
            $inputs->label_input_v2
        </div>";
    }

    /**
     * REG
     * Genera un input de tipo email requerido con estructura y validación integrada.
     *
     * Este método valida los datos de entrada, inicializa el valor del campo desde `$row_upd`, y construye un input HTML
     * de tipo email dentro de un `div` con etiqueta (label) usando clases CSS. El campo generado será requerido
     * (`required`) y podrá estar deshabilitado según el parámetro `$disabled`.
     *
     * @param bool $disabled Indica si el input estará deshabilitado.
     * @param string $name Nombre del campo, usado como `name`, `id`, y para obtener el valor desde `$row_upd`.
     * @param string $place_holder Texto del placeholder del input.
     * @param stdClass $row_upd Objeto con los valores para precargar el input (por ejemplo, en una edición).
     * @param bool $value_vacio Si se establece en true, el valor del input se generará vacío incluso si hay valor en `$row_upd`.
     *
     * @return array|string HTML del `div` con el input y la etiqueta correspondiente, o un array de error si ocurre algún fallo.
     *
     * @example
     * ```php
     * $email_html = $directivas->email_required(
     *     disabled: false,
     *     name: 'correo',
     *     place_holder: 'Ingresa tu correo',
     *     row_upd: $registro,
     *     value_vacio: false
     * );
     *
     * if (is_array($email_html)) {
     *     // manejar error
     *     var_dump($email_html);
     * } else {
     *     echo $email_html;
     * }
     * ```
     */
    public function email_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                   bool $value_vacio ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,
            value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $html= $this->html->email(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $init->row_upd->$name);

        $div = $this->html->div_label(html:  $html,label:$init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * REG
     * Genera un campo de fecha requerido con su respectiva etiqueta (label) encapsulado dentro de un div.
     *
     * Este método utiliza los datos proporcionados (nombre del campo, placeholder, y datos para actualización)
     * para crear un input HTML de tipo fecha (`<input type="date">`) con atributo `required`.
     * También agrega la etiqueta asociada al input y lo envuelve en un contenedor `<div>` con clases predefinidas.
     *
     * @param bool        $disabled     Indica si el campo estará deshabilitado (`true`) o no (`false`).
     * @param string      $name         Nombre del input y del identificador HTML (`id`). No puede estar vacío.
     * @param string      $place_holder Texto que se mostrará como `placeholder` del input.
     * @param stdClass    $row_upd      Objeto que contiene los valores actuales del formulario (por ejemplo, al editar).
     * @param bool        $value_vacio  Si `true`, el valor se deja vacío aunque exista en `$row_upd`.
     *
     * @return array|string HTML generado como string si no hay errores, o un array con información del error.
     *
     * @example Ejemplo de uso con valores desde un formulario:
     * ```php
     * $html = new html();
     * $row_upd = new stdClass();
     * $row_upd->fecha_nacimiento = '1990-05-15';
     *
     * echo $directivas->fecha_required(
     *     disabled: false,
     *     name: 'fecha_nacimiento',
     *     place_holder: 'Fecha de nacimiento',
     *     row_upd: $row_upd,
     *     value_vacio: false
     * );
     * // Salida:
     * // <div class="form-group">
     * //     <label for="fecha_nacimiento">Fecha de nacimiento</label>
     * //     <input type='date' name='fecha_nacimiento' value='1990-05-15' |class|  required id='fecha_nacimiento' placeholder='Fecha de nacimiento' />
     * // </div>
     * ```
     *
     * @example Si `$value_vacio` es `true`, el campo se inicializa sin valor:
     * ```php
     * echo $directivas->fecha_required(
     *     disabled: false,
     *     name: 'fecha_registro',
     *     place_holder: 'Fecha de registro',
     *     row_upd: $row_upd,
     *     value_vacio: true
     * );
     * // Salida:
     * // <div class="form-group">
     * //     <label for="fecha_registro">Fecha de registro</label>
     * //     <input type='date' name='fecha_registro' value='' |class|  required id='fecha_registro' placeholder='Fecha de registro' />
     * // </div>
     * ```
     *
     * @see html::fecha() para la generación del input de fecha.
     * @see html::div_label() para envolver el campo con su etiqueta.
     * @see directivas::init_text() para inicializar el valor y etiqueta del campo.
     * @see directivas::valida_data_label() para validar `name` y `placeholder`.
     */
    final public function fecha_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                   bool $value_vacio ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $data_init = $this->init_text(name: $name, place_holder: $place_holder,row_upd:  $row_upd,
            value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar row_upd div', data: $data_init);
        }

        $html= $this->html->fecha(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $data_init->row_upd->$name);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar input fecha', data: $html);
        }

        $div = $this->html->div_label(html:  $html,label:$data_init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * REG
     * Genera un input HTML de tipo `date` dentro de un `div` con su respectiva etiqueta (label),
     * validando los datos necesarios para construir el campo. Permite especificar si el campo
     * está deshabilitado, si es requerido, y si se desea mostrar vacío su valor.
     *
     * Este método está diseñado para ser utilizado en formularios donde se necesita capturar
     * una fecha (sin hora), con controles de validación y personalización del comportamiento.
     *
     * ---
     * ### Ejemplo de entrada
     * ```php
     * $row_upd = new stdClass();
     * $row_upd->fecha_fin = '2025-06-15';
     *
     * $html = $directivas->fecha(
     *     disabled: false,
     *     name: 'fecha_fin',
     *     place_holder: 'Fecha de Fin',
     *     required: true,
     *     row_upd: $row_upd,
     *     value_vacio: false
     * );
     * ```
     *
     * ---
     * ### Ejemplo de salida
     * ```html
     * <div class="control-group col-sm-12">
     *   <label class="control-label" for="fecha_fin">Fecha de Fin</label>
     *   <input type="date" name="fecha_fin" value="2025-06-15" class="form-control"
     *          id="fecha_fin" placeholder="Fecha de Fin" required>
     * </div>
     * ```
     *
     * ---
     * @param bool $disabled Indica si el input debe estar deshabilitado (`true` para agregar `disabled` al campo).
     * @param string $name Nombre del campo. Se utiliza como atributo `name` e `id` del input.
     * @param string $place_holder Texto que se mostrará como placeholder y en la etiqueta.
     *                              Si está vacío, se construye automáticamente a partir de `$name`.
     * @param bool $required Si es `true`, se añade el atributo `required` al input.
     * @param stdClass $row_upd Objeto con datos del formulario. Se usa para obtener el valor del campo si existe.
     * @param bool $value_vacio Si es `true`, fuerza a que el valor del input sea vacío, ignorando `$row_upd`.
     *
     * @return array|string Retorna el HTML del input con su `div` y etiqueta incluidos,
     *                      o un array con detalles de error si ocurre algún problema.
     */
    final public function fecha(bool $disabled, string $name, string $place_holder, bool $required, stdClass $row_upd,
                                   bool $value_vacio ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $data_init = $this->init_text(name: $name, place_holder: $place_holder,row_upd:  $row_upd,
            value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar row_upd div', data: $data_init);
        }

        $html= $this->html->fecha(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $data_init->row_upd->$name);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar input fecha', data: $html);
        }

        $div = $this->html->div_label(html:  $html,label:$data_init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * REG
     * Genera un atributo HTML `id` concatenando múltiples identificadores CSS contenidos en un arreglo.
     *
     * Este método recorre el arreglo `$ids_css`, validando que cada valor no esté vacío. Si algún identificador
     * está vacío, se retorna un error. Si todos son válidos, los concatena con espacios y construye un string
     * con el atributo HTML `id='...'`.
     *
     * Este método es útil cuando deseas asignar múltiples identificadores CSS combinados en un solo `id` HTML.
     *
     * @param array $ids_css Arreglo de identificadores CSS que se concatenarán para formar el atributo `id`.
     *
     * @return string|array Retorna un string con el atributo `id='...'` o un arreglo de error si falla alguna validación.
     *
     * @example
     * ```php
     * $ids = $this->ids_html(['input-email', 'user-field']);
     * if (is_array($ids)) {
     *     // Manejar error
     *     var_dump($ids);
     * } else {
     *     echo $ids;
     *     // Salida esperada: id='input-email user-field'
     * }
     * ```
     */
    private function ids_html(array $ids_css): string|array
    {
        $ids_html = '';
        foreach ($ids_css as $id_css){
            $ids_html = trim($ids_html);
            if($id_css === ''){
                return $this->error->error(mensaje: 'Error ids_html', data: $id_css, es_final: true);
            }
            $ids_html.=" $id_css ";
        }
        $ids_html = trim($ids_html);

        if($ids_html!==''){
            $ids_html = "id='$ids_html'";
        }

        return $ids_html;
    }

    /**
     * REG
     * Inicializa los datos para la creación de un input HTML. Este método valida los datos de entrada,
     * prepara los datos base y obtiene el valor final que se usará para poblar el campo del input.
     *
     * Utiliza `valida_data_label` para asegurar que el nombre y el `placeholder` sean válidos,
     * luego genera la estructura base con `init_text` y finalmente determina el valor del input
     * con `value_input`.
     *
     * @param string $name Nombre del campo del input. No debe estar vacío ni ser numérico.
     * @param string $place_holder Texto que se mostrará como ayuda o guía en el input.
     * @param stdClass $row_upd Objeto con los datos a utilizar para prellenar el input.
     * @param mixed $value Valor que se utilizará para sobrescribir el valor de `$row_upd->$name`
     *                     si dicho valor no es `null`.
     * @param bool $value_vacio Si es `true`, se permite que el valor del input sea vacío.
     *
     * @return array|stdClass Retorna un objeto con los datos necesarios para renderizar el input,
     *                        incluyendo el valor final (`value_input`). Si hay errores, retorna un array con el error.
     *
     * @example Entrada válida con valor en $row_upd:
     * ```php
     * $row_upd = new stdClass();
     * $row_upd->correo = 'correo@ejemplo.com';
     * $resultado = $this->init('correo', 'Ingrese su correo', $row_upd, 'nuevo@correo.com', false);
     * // $resultado->value_input será 'nuevo@correo.com'
     * ```
     *
     * @example Campo no existente en $row_upd:
     * ```php
     * $row_upd = new stdClass();
     * $resultado = $this->init('telefono', 'Teléfono de contacto', $row_upd, '1234567890', false);
     * // $resultado->value_input será '' (porque $row_upd->telefono no existe inicialmente)
     * ```
     *
     * @example Validación fallida por nombre vacío:
     * ```php
     * $row_upd = new stdClass();
     * $resultado = $this->init('', 'Nombre', $row_upd, 'Juan', false);
     * // Devuelve: ['error' => true, 'mensaje' => 'Error al validar datos', ...]
     * ```
     *
     * @example Validación fallida por $name numérico:
     * ```php
     * $row_upd = new stdClass();
     * $resultado = $this->init('123', 'Edad', $row_upd, 25, false);
     * // Devuelve: ['error' => true, 'mensaje' => 'Error name debe ser un texto no un numero', ...]
     * ```
     */
    private function init(string $name, string $place_holder, stdClass $row_upd, mixed $value,
                          bool $value_vacio): array|stdClass
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(
            name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $value_input = $this->value_input(init: $init,name:  $name,value:  $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener value_input', data: $value_input);
        }

        $init->value_input = $value_input;
        return $init;
    }

    /**
     * REG
     * Inicializa y valida los nombres para un campo HTML.
     *
     * Este método se encarga de:
     * - Validar que el nombre (`$name`) no esté vacío.
     * - Asignar un título (`$title`) amigable si no fue proporcionado, generándolo a partir del nombre.
     *   El título generado convierte guiones bajos en espacios y capitaliza cada palabra.
     *
     * Si el valor de `$name` está vacío, devuelve un array con error. Si no, retorna un objeto `stdClass`
     * con los valores formateados.
     *
     * @param string $name Nombre del campo. No debe estar vacío.
     * @param string $title Título del campo. Si está vacío, se genera automáticamente a partir del nombre.
     *
     * @return stdClass|array Retorna un objeto con los atributos `name` y `title`, o un array de error si ocurre alguna falla.
     *
     * @example
     * ```php
     * $nombres = $this->init_names(name: 'email_usuario', title: '');
     * if (is_array($nombres)) {
     *     // Manejo de error
     * } else {
     *     echo $nombres->title; // Output: "Email Usuario"
     * }
     * ```
     */
    private function init_names(string $name, string $title): array|stdClass
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio',data:  $name, es_final: true);
        }
        if($title === ''){
            $title = $name;
            $title = str_replace('_', ' ', $title);
            $title = ucwords($title);
            $title = trim($title);
        }

        $data = new stdClass();
        $data->name = $name;
        $data->title = $title;
        return $data;
    }

    /**
     * REG
     * Inicializa y valida los valores de entrada para un campo de formulario.
     *
     * Esta función valida los textos de `name` y `place_holder`, y asegura que el campo `$name`
     * exista dentro del objeto `$row_upd`. Si `$value_vacio` es `true`, se fuerza la creación de un nuevo objeto
     * con la propiedad `$name` vacía. Este método es útil para preparar datos de formularios antes de renderizar
     * inputs (por ejemplo: text, email, fecha, etc.).
     *
     * @param string $name Nombre del campo a inicializar en el objeto de actualización (`row_upd`).
     * @param string $place_holder Texto que se mostrará como placeholder en el input HTML.
     * @param stdClass $row_upd Objeto que contiene los valores actuales del formulario (datos de actualización).
     * @param bool $value_vacio Indica si se debe forzar un valor vacío en el campo `$name`.
     *
     * @return stdClass|array Devuelve el objeto `row_upd` con el campo `$name` inicializado,
     *                        o un arreglo con mensaje de error si falla la validación.
     *
     * @example Ejemplo de uso con valor existente:
     * ```php
     * $row_upd = new stdClass();
     * $row_upd->nombre = 'Juan Pérez';
     * $resultado = $this->init_input('nombre', 'Nombre completo', $row_upd, false);
     * // Resultado: stdClass con propiedad 'nombre' => 'Juan Pérez'
     * ```
     *
     * @example Forzando valor vacío:
     * ```php
     * $resultado = $this->init_input('telefono', 'Número de teléfono', new stdClass(), true);
     * // Resultado: stdClass con propiedad 'telefono' => ''
     * ```
     *
     * @example Error por name vacío:
     * ```php
     * $resultado = $this->init_input('', 'Nombre completo', new stdClass(), false);
     * // Resultado:
     * // [
     * //   'mensaje' => 'Error el $name esta vacio',
     * //   'data' => '',
     * //   'es_final' => true
     * // ]
     * ```
     *
     * @see row_upd_name() Método utilizado para asegurar la existencia de la propiedad en el objeto.
     * @see valida_etiquetas() Método que valida los textos de `name` y `place_holder`.
     */
    private function init_input(string $name, string $place_holder, stdClass $row_upd,
                                bool $value_vacio): array|stdClass
    {
        $valida = $this->valida_etiquetas(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar etiquetas', data: $valida);
        }
        $row_upd_ =$row_upd;
        $row_upd_ = $this->row_upd_name(name: $name, value_vacio: $value_vacio, row_upd: $row_upd_);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }
        return $row_upd_;
    }


    /**
     * Genera un input de tipo alias
     * @version 0.49.1
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     * @finalrev
     */
    public function input_alias(stdClass $row_upd, bool $value_vacio): array|string
    {
        $html =$this->input_text_required(disabled: false,name: 'alias',
            place_holder: 'Alias', row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: 6,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo codigo
     * @version 0.35.1
     * @param int $cols Numero de columnas boostrap
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     */
    final public function input_codigo(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->input_text_required(disabled: false,name: 'codigo',place_holder: 'Codigo',row_upd: $row_upd,
            value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo codigo bis
     * @version 0.36.1
     * @param int $cols Numero de columnas boostrap
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     */
    final public function input_codigo_bis(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->input_text_required(disabled: false,name: 'codigo_bis',
            place_holder: 'Codigo BIS', row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }
        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un text de tipo descripcion
     * @param stdClass $row_upd Objeto con datos del row
     * @param bool $value_vacio si value vacia no integra valor en el input
     * @return array|string
     * @version 0.106.4
     */
    final public function input_descripcion(stdClass $row_upd, bool $value_vacio): array|string
    {
        $html =$this->input_text_required(disabled: false,name: 'descripcion', place_holder: 'Descripcion',
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: 12,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un input text de descripcion_select
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     * @version 0.94.4
     */
    final public function input_descripcion_select(stdClass $row_upd, bool $value_vacio): array|string
    {
        $html =$this->input_text_required(disabled: false,name: 'descripcion_select',
            place_holder: 'Descripcion Select', row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: 6,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo id
     * @param int $cols Numero de columnas css
     * @param stdClass $row_upd Registro en operacion
     * @param bool $value_vacio si value vacio deja limpio el input
     * @return array|string
     * @version 0.103.4
     */
    final public function input_id(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {
        $valida = (new directivas(html: $this->html))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->input_text(disabled: true,name: 'id',place_holder: 'ID',
            required: false, row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input tipo required
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $disabled si disabled retorna el input como disabled
     * @param string $name Usado para identificador css name input y place holder
     * @param string $place_holder Texto a mostrar en el input
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     * @version 1.110.4
     */
    final public function input_password(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                        bool $value_vacio ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,
            value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $html= $this->html->password(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $init->row_upd->$name);

        $div = $this->html->div_label(html:  $html,label:$init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * REG
     * Genera un input compuesto por dos botones tipo radio con estructura Bootstrap.
     *
     * Este método es una interfaz pública para construir rápidamente un grupo de botones tipo radio con dos opciones,
     * utilizando parámetros estandarizados a través de `params_inputs`. Ideal para integrarse en formularios.
     *
     * Internamente, este método:
     * - Valida que el campo y la selección predeterminada (`checked_default`) sean válidos.
     * - Obtiene parámetros estandarizados (como clases, `for`, IDs, etc.) desde `params_inputs`.
     * - Construye el HTML mediante el método `radio_doble`.
     *
     * @param string $campo Nombre del campo, que se usará como `name`, `id` y base del `label`.
     * @param int $checked_default Define cuál radio estará marcado por defecto:
     *  - `1`: Marca `$val_1` como seleccionado.
     *  - `2`: Marca `$val_2` como seleccionado.
     * @param string $tag Etiqueta HTML que agrupa el campo (ej. "div", "section"). Se utiliza para la estructura de envoltura.
     * @param string $val_1 Valor de la primera opción del radio (por ejemplo, "Sí").
     * @param string $val_2 Valor de la segunda opción del radio (por ejemplo, "No").
     *
     * @return array|string Devuelve el HTML del grupo de radios o un array con el error si ocurre alguno.
     *
     * @example
     * ```php
     * echo $this->input_radio_doble(
     *     campo: 'activo',
     *     checked_default: 1,
     *     tag: 'div',
     *     val_1: 'Sí',
     *     val_2: 'No'
     * );
     * ```
     */
    final public function input_radio_doble(string $campo, int $checked_default, string $tag, string $val_1,
                                            string $val_2): array|string
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo vacio',data:  $campo, es_final: true);
        }
        if($checked_default <=0){
            return $this->error->error(mensaje: 'Error checked_default debe ser mayor a 0', data: $checked_default,
                es_final: true);
        }
        if($checked_default > 2){
            return $this->error->error(mensaje: 'Error checked_default debe ser menor a 3', data: $checked_default,
                es_final: true);
        }

        $params_chk = (new params_inputs())->params_base_chk(campo: $campo,tag:  $tag);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener params_chk',data:  $params_chk);
        }

        $radio = $this->radio_doble(checked_default: $checked_default,
            class_label:  $params_chk->class_label,class_radio:  $params_chk->class_radio,cols:6,
            for: $params_chk->for, ids_css: $params_chk->ids_css,label_html:  $params_chk->label_html,
            name:  $params_chk->name,title:  $params_chk->title,val_1: $val_1,val_2: $val_2);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener radio',data:  $radio);
        }
        return $radio;

    }

    /**
     * Genera un input de tipo telefono
     * @param bool $disabled atributo disabled
     * @param string $name Name input
     * @param string $place_holder Tag Input
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si vacio deja sin value
     * @param bool $required Indica si es requerido
     * @param mixed|null $value Valor prioritario a integracion en caso de que este seteado
     * @return array|string
     * @version 0.126.5
     */
    final public function input_telefono(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                   bool $value_vacio, bool $required = true, mixed $value = null ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $value_input = $row_upd->$name;
        if(!is_null($value)){
            $value_input = $value;
        }

        $html= $this->html->telefono(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $value_input);

        $div = $this->html->div_label(html:  $html,label:$init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }


    /**
     * REG
     * Inicializa un campo de texto (`input`) estableciendo su etiqueta (`label`) y valor,
     * útil para formularios dinámicos en procesos de alta o modificación.
     *
     * Este método valida el nombre del campo y el placeholder, genera la etiqueta HTML
     * correspondiente y asegura que el valor esté presente en el objeto `row_upd`.
     * Si se indica que el valor debe estar vacío o si no existe en el objeto, se inicializa como cadena vacía.
     *
     * En caso de error en la validación o generación de la etiqueta, se retorna un objeto de error con detalles.
     *
     * @param string    $name           Nombre del campo que se usará como clave y como base del `name`, `id` e `input`.
     * @param string    $place_holder   Texto que se mostrará como sugerencia dentro del campo de texto.
     * @param stdClass  $row_upd        Objeto que contiene los valores actuales del formulario (puede venir de la base de datos).
     * @param bool      $value_vacio    Indica si se debe forzar el valor del campo a vacío (true) o mantener el existente (false).
     *
     * @return array|stdClass Retorna un objeto con:
     *   - `label` (string): La etiqueta generada para el campo.
     *   - `row_upd` (stdClass): El objeto actualizado con el valor del campo asignado.
     *   En caso de error, retorna un array con los detalles del mismo.
     *
     * @example Ejemplo de uso:
     * ```php
     * $row_upd = new stdClass();
     * $row_upd->descripcion = 'Texto actual';
     * $data = $this->init_text(name: 'descripcion', place_holder: 'Ingrese descripción', row_upd: $row_upd, value_vacio: false);
     * ```
     *
     * @example Resultado esperado (sin errores):
     * ```php
     * stdClass Object
     * (
     *     [row_upd] => stdClass Object
     *         (
     *             [descripcion] => Texto actual
     *         )
     *     [label] => <label for='descripcion' class='form-label'>Ingrese descripción</label>
     * )
     * ```
     *
     * @example Resultado esperado (con value_vacio = true):
     * ```php
     * stdClass Object
     * (
     *     [row_upd] => stdClass Object
     *         (
     *             [descripcion] =>
     *         )
     *     [label] => <label for='descripcion' class='form-label'>Ingrese descripción</label>
     * )
     * ```
     *
     * @example Resultado en caso de error:
     * ```php
     * Array
     * (
     *     [error] => 1
     *     [mensaje] => 'Error al validar datos'
     *     [data] => [... detalle del error ...]
     * )
     * ```
     */
    final protected function init_text(
        string $name, string $place_holder, stdClass $row_upd, bool $value_vacio): array|stdClass
    {
        $valida = $this->valida_data_label(name: $name, place_holder: $place_holder);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $label = $this->label_input(name: $name, place_holder: $place_holder);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        if ($value_vacio || !(isset($row_upd->$name))) {
            $row_upd->$name = '';
        }

        $data = new stdClass();
        $data->row_upd = $row_upd;
        $data->label = $label;

        return $data;
    }


    /**
     * REG
     * Genera un input HTML de tipo fecha (o datetime-local) dentro de un `div` con etiqueta,
     * validando los datos requeridos, con opción a deshabilitado y campos vacíos controlados.
     *
     * Este método es útil para construir inputs de tipo fecha o fecha y hora en formularios dinámicos,
     * permitiendo especificar si el campo es requerido, si debe estar deshabilitado, y si el valor debe ser vacío.
     *
     * ### Ejemplo de entrada
     * ```php
     * $row_upd = new stdClass();
     * $row_upd->fecha_inicio = '2025-04-01';
     *
     * $html = $directivas->input_fecha_required(
     *     disabled: false,
     *     name: 'fecha_inicio',
     *     place_holder: 'Fecha de Inicio',
     *     row_upd: $row_upd,
     *     value_vacio: false,
     *     required: true,
     *     value: null,
     *     value_hora: false
     * );
     * ```
     *
     * ### Ejemplo de salida
     * ```html
     * <div class="control-group col-sm-12">
     *   <label class="control-label" for="fecha_inicio">Fecha de Inicio</label>
     *   <input type="date" name="fecha_inicio" value="2025-04-01" class="form-control"
     *          id="fecha_inicio" placeholder="Fecha de Inicio" required>
     * </div>
     * ```
     *
     * @param bool $disabled Indica si el campo estará deshabilitado.
     * @param string $name Nombre del campo (usado como `name`, `id`, y para el valor).
     * @param string $place_holder Texto que aparecerá como placeholder; si está vacío se genera a partir del nombre.
     * @param stdClass $row_upd Objeto con los datos del formulario para obtener el valor actual del campo.
     * @param bool $value_vacio Si es `true`, se fuerza el valor del campo a vacío.
     * @param bool $required Si es `true`, se agrega el atributo `required` al input.
     * @param mixed $value Valor explícito a mostrar en el input (anula el valor de `$row_upd->$name` si no es `null`).
     * @param bool $value_hora Si es `true`, el tipo del input será `datetime-local` en vez de `date`.
     *
     * @return array|string HTML generado del input tipo fecha dentro de un div con su etiqueta correspondiente,
     *                      o un array con mensaje de error si ocurre una falla en alguna validación.
     */
    final public function input_fecha_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                               bool $value_vacio, bool $required = true, mixed $value = null,
                                               bool $value_hora = false ): array|string
    {

        $name = trim($name);
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            $place_holder = $name;
            $place_holder = str_replace('_', ' ',$place_holder);
            $place_holder = ucwords($place_holder);
        }

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init(
            name: $name,place_holder:  $place_holder,row_upd:  $row_upd,value:  $value,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }


        $html= $this->html->fecha(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $init->value_input, value_hora: $value_hora);

        $div = $this->html->div_label(html:  $html,label:$init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un input de tipo file
     * @param bool $disabled atributo disabled
     * @param string $name Name input
     * @param string $place_holder Tag input
     * @param bool $required Atributo required
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si vacio deja limpio el input
     * @return array|string
     */
    final public function input_file(bool $disabled, string $name, string $place_holder, bool $required, stdClass $row_upd,
                               bool $value_vacio, bool $multiple = false): array|string
    {

        $valida = $this->valida_etiquetas(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar etiquetas', data: $valida);
        }

        $row_upd_ = $this->init_input(name:$name,place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }

        $html= $this->html->file(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $row_upd_->$name,multiple: $multiple);



        $div = $this->div_label(html:$html, name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo monto requerido
     * @param bool $disabled Si disabled deja el input deshabilitado
     * @param string $name Name del input
     * @param string $place_holder Se muestra en input
     * @param stdClass $row_upd Registro base en proceso
     * @param bool $value_vacio si vacio deja vacio
     * @param bool $con_label Si con label integra la etiqueta
     * @param mixed|null $value Valor
     * @return array|string
     * @version 7.8.0
     */
    final public function input_monto_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                         bool $value_vacio, bool $con_label = true , mixed $value = null): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init(
            name: $name,place_holder:  $place_holder,row_upd:  $row_upd,value:  $value,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $html= $this->html->monto(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $init->value_input);

        if($con_label) {
            $html = $this->html->div_label(html: $html, label: $init->label);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar div', data: $html);
            }
        }

        return $html;

    }

    /**
     * Genera un input text en html
     * @param bool $disabled si disabled el elemento queda deshabilitado
     * @param string $name Nombre de input
     * @param string $place_holder Label a mostrar dentro de input
     * @param bool $required si required integra attr required
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si vacio deja input sin value
     * @param array $ids_css Identificadores css
     * @param string $regex Regex
     * @param string $title Titulo on over
     * @return array|string
     * @version 0.101.4
     */
    final public function input_text(bool $disabled, string $name, string $place_holder, bool $required,
                                     stdClass $row_upd, bool $value_vacio, array $ids_css = array(),
                                     string $regex = '', string $title = ''): array|string
    {


        $row_upd_ = $this->init_input(name:$name,place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }


        $html= $this->html->text(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $row_upd_->$name, ids_css: $ids_css, regex: $regex, title: $title);


        $div = $this->div_label(html:$html, name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    final public function input_text_sin_label(array $class_css,int $cols, bool $disabled, string $name,
                                               string $place_holder, bool $required, stdClass $row_upd,
                                               bool $value_vacio): array|string
    {


        $row_upd_ = $this->init_input(name:$name,place_holder:  $place_holder,row_upd:  $row_upd,
            value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }

        $html= $this->html->text_class(class_css: $class_css, disabled:$disabled, id_css: $name, name: $name,
            place_holder: $place_holder, required: $required, value: $row_upd_->$name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar html', data: $html);
        }

        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }


        return $div;

    }


    /**
     * Genera un input tipo required
     * @param bool $disabled si disabled retorna el input como disabled
     * @param string $name Usado para identificador css name input y place holder
     * @param string $place_holder Texto a mostrar en el input
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @param bool $con_label Integra el label en el input
     * @param array $ids_css Identificadores extra
     * @param string $regex regex a integrar en pattern
     * @param string $title title a integrar a input
     * @return array|string
     * @version 0.48.1
     */
    final public function input_text_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                        bool $value_vacio, bool $con_label = true, array $ids_css = array(),
                                              string $regex = '', string $title = '' ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $html= $this->html->text(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $init->row_upd->$name, ids_css: $ids_css, regex: $regex, title: $title);

        if($con_label) {
            $html = $this->html->div_label(html: $html, label: $init->label);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar div', data: $html);
            }
        }

        return $html;

    }

    final public function input_text_base(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                          bool $value_vacio, array $class_css = array(), bool $con_label = true,
                                          array $ids_css = array(), string $regex = '', bool $required = true,
                                          string $title = '', string|null $value = '' ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $value_input = $init->row_upd->$name;

        if(is_null($value)){
            $value = '';
        }

        $value = trim($value);
        if($value!==''){
            $value_input = $value;
        }

        $html= $this->html->text_base(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $value_input, class_css: $class_css, ids_css: $ids_css, regex: $regex,
            title: $title);

        if($con_label) {
            $html = $this->html->div_label(html: $html, label: $init->label);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar div', data: $html);
            }
        }

        return $html;

    }

    /**
     * REG
     * Inicializa y valida los valores del atributo `for` y el contenido de una etiqueta `label`.
     *
     * Este método asegura que tanto el atributo `for` como el contenido HTML del label (`label_html`)
     * estén correctamente definidos. Si alguno está vacío, intenta asignar el valor del otro.
     * Realiza limpieza de espacios y valida que al final ambos campos no estén vacíos.
     *
     * Si las validaciones fallan, retorna un arreglo de error. En caso exitoso, retorna
     * un objeto con las propiedades `for` y `label_html`.
     *
     * @param string $for Atributo `for` de la etiqueta `label`, normalmente asociado al `id` de un input.
     * @param string $label_html Texto visible que se mostrará en la etiqueta `label`.
     *
     * @return stdClass|array Objeto con las propiedades `for` y `label_html`, o arreglo de error si la validación falla.
     *
     * @example
     * ```php
     * $label = $this->label_init('email', 'Correo Electrónico');
     * if (is_array($label)) {
     *     // Manejo de error
     *     var_dump($label);
     * } else {
     *     echo "<label for='{$label->for}'>{$label->label_html}</label>";
     * }
     * ```
     */
    private function label_init(string $for, string $label_html): stdClass|array
    {
        $for = trim($for);
        if($for === ''){
            $for = $label_html;
        }

        $label_html = trim($label_html);
        if($label_html === ''){
            $label_html = $for;
        }
        $for = trim($for);
        $label_html = trim($label_html);

        if($for === ''){
            return $this->error->error(mensaje: 'Error for esta vacio',data:  $for, es_final: true);
        }
        if($label_html === ''){
            return $this->error->error(mensaje: 'Error label_html esta vacio',data:  $label_html, es_final: true);
        }

        $data = new stdClass();
        $data->for = $for;
        $data->label_html = $label_html;

        return $data;
    }


    /**
     * REG
     * Genera una etiqueta (`label`) HTML basada en el nombre y el marcador de posición proporcionados.
     *
     * Esta función recibe un nombre y un marcador de posición (place holder) y genera una etiqueta HTML (`label`) utilizando
     * esos valores. Antes de generar la etiqueta, la función valida que los datos proporcionados sean correctos. Si algún dato es
     * inválido, se genera un error con un mensaje descriptivo. Si la validación es exitosa, se procede a generar el HTML de la etiqueta.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que los parámetros `$name` y `$place_holder` no estén vacíos.
     * 2. Se genera una etiqueta `label` utilizando el método `label` de la clase `html`.
     * 3. Si ocurre un error en cualquiera de estos pasos, se devuelve un mensaje de error con detalles sobre el problema.
     * 4. Si todo es exitoso, se retorna el HTML de la etiqueta generada.
     *
     * **Parámetros:**
     *
     * @param string $name El nombre del campo. Este parámetro es obligatorio y se usa para generar el identificador CSS de la etiqueta.
     * @param string $place_holder El texto que se mostrará como marcador de posición (place holder) en el campo asociado a la etiqueta.
     *
     * **Retorno:**
     * - Devuelve el HTML de la etiqueta `label` si los parámetros son válidos.
     * - Si ocurre un error durante la validación o generación, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Validación exitosa**
     * ```php
     * $name = "usuario_id";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->label_input($name, $place_holder);
     * // Retorna: "<label for='usuario_id'>Ingrese ID del usuario</label>"
     * ```
     *
     * **Ejemplo 2: Error por parámetro vacío**
     * ```php
     * $name = "";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->label_input($name, $place_holder);
     * // Retorna un arreglo con el mensaje de error: 'Error $name debe tener info'.
     * ```
     *
     * **Ejemplo 3: Error por parámetro vacío (place_holder)**
     * ```php
     * $name = "usuario_id";
     * $place_holder = "";
     * $resultado = $this->label_input($name, $place_holder);
     * // Retorna un arreglo con el mensaje de error: 'Error $place_holder debe tener info'.
     * ```
     *
     * **@version 1.0.0**
     */
    final protected function label_input(string $name, string $place_holder): array|string
    {
        // Validación de los parámetros name y place_holder
        $valida = $this->valida_data_label(name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        // Generar la etiqueta label utilizando los valores proporcionados
        $label = $this->html->label(id_css: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        // Retornar el HTML de la etiqueta generada
        return $label;
    }


    /**
     * REG
     * Genera un elemento HTML tipo radio con su respectiva etiqueta `<label>`.
     *
     * Este método genera una estructura HTML que representa un botón de opción (`<input type="radio">`)
     * envuelto en una etiqueta `<label>`, incluyendo clases CSS, atributos `id`, y título accesible.
     * Valida y limpia los parámetros antes de generar el HTML. También utiliza `init_names` para asegurar
     * que el `name` y `title` estén correctamente formateados.
     *
     * @param string $checked Atributo "checked" si este botón debe estar seleccionado.
     *                        Debe estar vacío o tener el valor `"checked"`.
     * @param string $class_label_html Clases CSS para la etiqueta `<label>`, generado previamente.
     * @param string $class_radio_html Clases CSS para el input radio, generado previamente.
     * @param string $ids_html Atributo ID completo para el input (ej. `id='mi_id'`), generado previamente.
     * @param string $name Nombre del grupo de radios. Es validado y no debe estar vacío.
     * @param string $title Título (atributo HTML `title`) para el input. Si está vacío, se genera desde `$name`.
     * @param string $val Valor del input radio (`value="..."`) y también el texto mostrado dentro de la etiqueta.
     *
     * @return string|array Devuelve el HTML generado como string o un array con información de error si ocurre una falla.
     *
     * @example
     * ```php
     * $html = $this->label_input_radio(
     *     checked: 'checked',
     *     class_label_html: "class='form-check-label'",
     *     class_radio_html: "class='form-check-input'",
     *     ids_html: "id='opcion1'",
     *     name: 'tipo_pago',
     *     title: '',
     *     val: 'Efectivo'
     * );
     * echo $html;
     * // <label class='form-check-label'>
     * //   <input type='radio' name='tipo_pago' value='Efectivo' class='form-check-input' id='opcion1' title='Tipo Pago' checked>
     * //   Efectivo
     * // </label>
     * ```
     */
    private function label_input_radio(string $checked, string $class_label_html,string $class_radio_html,
                                       string $ids_html, string $name, string $title, string $val): string|array
    {
        $checked = trim($checked);
        $class_label_html = trim($class_label_html);
        $class_radio_html = trim($class_radio_html);
        $ids_html = trim($ids_html);
        $name = trim($name);
        $title = trim($title);
        $val = trim($val);



        $init = $this->init_names(name: $name,title:  $title);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error integrar datos',data:  $init);
        }

        return trim("
            <label $class_label_html>
                <input type='radio' name='$init->name' value='$val' $class_radio_html $ids_html 
                title='$init->title' $checked>
                $val
            </label>");
    }

    /**
     * REG
     * Genera una etiqueta HTML `<label>` para un input tipo radio, utilizando clases CSS estándar.
     *
     * Este método invoca `label_init()` para validar y preparar los valores de los atributos `for` y `label_html`.
     * Si la validación es exitosa, retorna un string con la etiqueta `<label>` formateada y con clase `control-label`.
     * En caso de error en la validación, retorna un arreglo con detalles del error.
     *
     * @param string $for El atributo `for` del label, que debe coincidir con el `id` del input radio asociado.
     * @param string $label_html El texto visible que se mostrará en el label.
     *
     * @return string|array Retorna la etiqueta `<label>` generada o un arreglo de error si ocurre una falla en la validación.
     *
     * @example
     * ```php
     * $label = $this->label_radio('sexo_m', 'Masculino');
     * if (is_array($label)) {
     *     // Manejo de error
     *     var_dump($label);
     * } else {
     *     echo $label;
     *     // Salida: <label class='control-label' for='sexo_m'>Masculino</label>
     * }
     * ```
     */
    private function label_radio(string $for, string $label_html): string|array
    {

        $params = $this->label_init(for: $for, label_html: $label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar params',data:  $params);
        }

        return "<label class='control-label' for='$params->for'>$params->label_html</label>";
    }

    /**
     * REG
     * Genera dos elementos `<label>` con inputs tipo radio integrados, a partir de parámetros recibidos.
     *
     * Este método construye dos radios (`<input type="radio">`) con sus respectivas etiquetas `<label>`,
     * utilizando los valores proporcionados como texto (`$val_1`, `$val_2`) y marca cuál está seleccionado
     * por defecto, según los parámetros de configuración.
     *
     * Realiza validaciones estrictas sobre la estructura de `$params` y sus claves internas.
     *
     * @param string $name Nombre del grupo de radios (atributo `name`). No debe estar vacío.
     * @param stdClass $params Objeto con los parámetros necesarios, generado por `params_html`.
     *                         Debe incluir:
     *  - `checked_default` (objeto con `checked_default_v1`, `checked_default_v2`)
     *  - `class_label_html` (string)
     *  - `class_radio_html` (string)
     *  - `ids_html` (string)
     * @param string $title Título del grupo de radios, usado como atributo `title`. Si está vacío, se genera a partir del `name`.
     * @param string $val_1 Texto y valor del primer radio.
     * @param string $val_2 Texto y valor del segundo radio.
     *
     * @return array|stdClass Devuelve un objeto con las etiquetas generadas:
     *   - `label_input_v1`: string con HTML del primer radio
     *   - `label_input_v2`: string con HTML del segundo radio
     *   O bien un array con detalles del error si ocurre una falla en la validación o construcción.
     *
     * @example
     * ```php
     * $params = $this->params_html(
     *     checked_default: 1,
     *     class_label: [],
     *     class_radio: [],
     *     ids_css: ['tipo_pago_id'],
     *     label_html: 'Forma de Pago',
     *     for: 'tipo_pago'
     * );
     * $radios = $this->labels_radios(
     *     name: 'tipo_pago',
     *     params: $params,
     *     title: 'Forma de Pago',
     *     val_1: 'Efectivo',
     *     val_2: 'Transferencia'
     * );
     * echo $radios->label_input_v1;
     * echo $radios->label_input_v2;
     * ```
     */
    private function labels_radios(
        string $name, stdClass $params, string $title, string $val_1, string $val_2): array|stdClass
    {
        $keys = array('checked_default','class_label_html','class_radio_html','ids_html');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $params,valida_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar params', data: $valida);
        }

        $keys = array('checked_default_v1','checked_default_v2');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $params->checked_default,
            valida_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar params', data: $valida);
        }
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio',data:  $name, es_final: true);
        }


        $init = $this->init_names(name: $name,title:  $title);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error integrar datos',data:  $init);
        }


        $label_input_v1 = $this->label_input_radio(checked: $params->checked_default->checked_default_v1,
            class_label_html:  $params->class_label_html, class_radio_html:  $params->class_radio_html,
            ids_html:  $params->ids_html,name:  $init->name,title:  $init->title,val:  $val_1);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar label_input_v1', data: $label_input_v1);
        }

        $label_input_v2 = $this->label_input_radio(checked: $params->checked_default->checked_default_v2,
            class_label_html:  $params->class_label_html, class_radio_html:  $params->class_radio_html,
            ids_html:  $params->ids_html,name:  $name,title:  $title,val:  $val_2);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar label_input_v2', data: $label_input_v2);
        }

        $data = new stdClass();
        $data->label_input_v1 = $label_input_v1;
        $data->label_input_v2 = $label_input_v2;
        return $data;
    }

    /**
     * REG
     * Genera un mensaje de éxito en formato HTML usando una alerta de Bootstrap.
     *
     * Este método recibe un mensaje de éxito y genera una alerta de éxito si el mensaje no está vacío.
     * Si el mensaje está vacío, no se generará ninguna alerta. Si hay un error durante la creación
     * de la alerta, se devolverá un mensaje de error con detalles.
     *
     * @param string $mensaje_exito El mensaje de éxito que se mostrará en la alerta. Este parámetro debe
     *                              ser una cadena de texto que describa el éxito de una operación.
     *
     * @return array|string Si el mensaje no está vacío, devuelve una cadena de texto que contiene el HTML
     *                      de una alerta de éxito. Si ocurre un error al generar la alerta, devuelve
     *                      un array con un mensaje de error y los datos del error.
     *
     * @example
     * // Caso exitoso: Generación de una alerta de éxito
     * $mensaje = "La operación se completó exitosamente.";
     * $alerta = $directiva->mensaje_exito($mensaje);
     * echo $alerta;  // Resultado: <div class="alert alert-success" role="alert"><strong>Muy bien!</strong> La operación se completó exitosamente.</div>
     *
     * @example
     * // Caso de error: Si ocurre un error al generar la alerta
     * $mensaje = "";
     * $alerta = $directiva->mensaje_exito($mensaje);
     * if (is_array($alerta)) {
     *     echo $alerta['mensaje'];  // Resultado: "Error al generar alerta"
     * }
     *
     * @version 1.0.0
     */
    final public function mensaje_exito(string $mensaje_exito): array|string
    {
        $alert_exito = '';

        // Comprobar si el mensaje de éxito no está vacío
        if ($mensaje_exito !== '') {
            // Generar la alerta de éxito utilizando el método alert_success de la clase html
            $alert_exito = $this->html->alert_success(mensaje: $mensaje_exito);

            // Verificar si hubo un error al generar la alerta
            if (errores::$error) {
                // Si hubo un error, devolver el mensaje de error
                return $this->error->error(mensaje: 'Error al generar alerta', data: $alert_exito);
            }
        }

        // Si todo está bien, devolver el HTML de la alerta de éxito
        return $alert_exito;
    }


    /**
     * REG
     * Genera un mensaje de advertencia en formato HTML utilizando una alerta de Bootstrap.
     *
     * Este método recibe un mensaje de advertencia y genera una alerta de advertencia en formato HTML
     * utilizando el método `alert_warning` de la clase `html`. Si el mensaje no está vacío, se genera
     * la alerta. Si ocurre algún error al generar la alerta, se devuelve un mensaje de error con detalles.
     * Si no hay error, se devuelve el código HTML de la alerta generada.
     *
     * @param string $mensaje_warning El mensaje de advertencia que se mostrará en la alerta.
     *                                Este parámetro debe ser una cadena de texto que describa el
     *                                problema o la advertencia que se está notificando.
     *
     * @return array|string Devuelve el HTML de la alerta de advertencia generada si el mensaje no está vacío.
     *                      Si ocurre un error durante la generación de la alerta, devuelve un array con
     *                      un mensaje de error y los detalles de la causa del error.
     *
     * @throws errores Si ocurre un error durante la generación del mensaje de advertencia.
     *
     * @example
     * // Ejemplo de uso exitoso:
     * $mensaje_warning = "Advertencia: El formulario no se ha enviado correctamente.";
     * $alerta = $directiva->mensaje_warning($mensaje_warning);
     * echo $alerta;
     * // Salida esperada: <div class="alert alert-warning" role="alert">
     * //                   <strong>Advertencia!</strong> El formulario no se ha enviado correctamente.
     * //                   </div>
     *
     * @example
     * // Ejemplo de error: Si ocurre un error al generar la alerta:
     * $mensaje_warning = "";
     * $alerta = $directiva->mensaje_warning($mensaje_warning);
     * if (is_array($alerta)) {
     *     echo $alerta['mensaje'];  // Resultado: "Error al generar alerta"
     * }
     *
     * @version 1.0.0
     */
    final public function mensaje_warning( string $mensaje_warning): array|string
    {
        $alert_warning = '';

        // Comprobar si el mensaje de advertencia no está vacío
        if ($mensaje_warning !== '') {
            // Generar la alerta de advertencia utilizando el método alert_warning de la clase html
            $alert_warning = $this->html->alert_warning(mensaje: $mensaje_warning);

            // Verificar si hubo un error al generar la alerta
            if (errores::$error) {
                // Si hubo un error, devolver el mensaje de error
                return $this->error->error(mensaje: 'Error al generar alerta', data: $alert_warning);
            }
        }

        // Si todo está bien, devolver el HTML de la alerta de advertencia
        return $alert_warning;
    }


    /**
     * Genera un numero para menu lateral
     * @param string $number Numero svg
     * @return string
     */
    public function number_menu_lateral(string $number): string
    {
        $img =  (new views())->url_assets."img/numeros/$number.svg";
        return "<img src='$img' class='numero'>";
    }


    /**
     * REG
     * Genera un conjunto de parámetros necesarios para construir un grupo de radio buttons HTML.
     *
     * Este método realiza múltiples tareas:
     * - Inicializa los textos del label asociados al radio (`label_init`)
     * - Genera el HTML del label (`label_radio`)
     * - Integra las clases CSS para el label y el radio button (`class_label_html`, `class_radio_html`)
     * - Valida e integra los IDs CSS como atributo HTML (`ids_html`)
     * - Valida y genera los valores `checked` por defecto (`checked_default`)
     *
     * Si alguna validación o integración falla, se devuelve un array con el error detallado.
     *
     * @param int $checked_default Valor que indica cuál radio debe estar marcado por defecto (1 o 2).
     *                             Debe estar en el rango [1, 2].
     * @param array $class_label Arreglo de clases CSS para el elemento `<label>`.
     * @param array $class_radio Arreglo de clases CSS para el input tipo radio.
     * @param array $ids_css Arreglo de IDs CSS que serán concatenados para formar el atributo `id`.
     * @param string $label_html Texto visible del label.
     * @param string $for Valor del atributo `for` del label, que debe coincidir con el id del input.
     *
     * @return stdClass|array Retorna un objeto con los parámetros listos para generar el HTML, o un array de error.
     *
     * El objeto retornado contiene:
     * - `label_html` (string): HTML del label
     * - `class_label_html` (string): clases CSS para el label
     * - `class_radio_html` (string): clases CSS para el input
     * - `ids_html` (string): atributo `id='...'` concatenado
     * - `checked_default` (stdClass): valores `checked` para cada opción del radio
     *
     * @example
     * ```php
     * $params = $this->params_html(
     *     checked_default: 1,
     *     class_label: ['mb-2', 'text-primary'],
     *     class_radio: ['ms-2'],
     *     ids_css: ['radio-opcion-a'],
     *     label_html: '¿Desea activar?',
     *     for: 'activar_radio'
     * );
     *
     * if (is_array($params)) {
     *     // Manejar error
     *     var_dump($params);
     * } else {
     *     echo $params->label_html;
     *     // Renderiza el label con sus atributos e identificadores.
     * }
     * ```
     */
    private function params_html(int $checked_default, array $class_label, array $class_radio, array $ids_css,
                                 string $label_html, string $for): array|stdClass
    {

        $params_radio = $this->label_init(for: $for, label_html: $label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar params',data:  $params_radio);
        }
        if($checked_default <=0){
            return $this->error->error(mensaje: 'Error checked_default debe ser mayor a 0', data: $checked_default,
                es_final: true);
        }
        if($checked_default > 2){
            return $this->error->error(mensaje: 'Error checked_default debe ser menor a 3', data: $checked_default,
                es_final: true);
        }

        $label_html = $this->label_radio(for: $params_radio->for,label_html:  $params_radio->label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label_html', data: $label_html);
        }

        $class_label_html = $this->class_label_html(class_label: $class_label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar class_label', data: $class_label_html);
        }

        $class_radio_html = $this->class_radio_html(class_radio: $class_radio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar class_radio_html', data: $class_radio_html);
        }

        $ids_html = $this->ids_html(ids_css: $ids_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar ids_html', data: $ids_html);
        }

        $checked_default = $this->checked_default(checked_default: $checked_default);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar checked_default', data: $checked_default);
        }

        $params = new stdClass();
        $params->label_html = $label_html;
        $params->class_label_html = $class_label_html;
        $params->class_radio_html = $class_radio_html;
        $params->ids_html = $ids_html;
        $params->checked_default = $checked_default;

        return $params;


    }

    /**
     * REG
     * Genera un grupo de dos inputs tipo radio integrados en un `div` con clases Bootstrap.
     *
     * Este método construye un grupo visualmente estructurado de dos radios con su respectiva etiqueta (`label`),
     * clases personalizadas, control de selección por defecto (`checked`), y validación de entradas. El resultado es
     * un bloque HTML listo para insertarse en formularios.
     *
     * @param int $checked_default Define cuál radio estará seleccionado por defecto:
     *  - `1`: El primer radio (`$val_1`)
     *  - `2`: El segundo radio (`$val_2`)
     * @param array $class_label Clases CSS que se aplicarán a las etiquetas (`label`) de los radios.
     * @param array $class_radio Clases CSS que se aplicarán a los inputs tipo radio.
     * @param int $cols Número de columnas Bootstrap que ocupará el contenedor del grupo (1-12).
     * @param string $for Valor del atributo `for` de la etiqueta HTML principal.
     * @param array $ids_css IDs HTML que se asignarán a los radios.
     * @param string $label_html Texto (o HTML) que se mostrará como etiqueta del grupo de radios.
     * @param string $name Nombre del input (atributo `name`) común a ambos radios.
     * @param string $title Título que se mostrará como `title` en los inputs.
     * @param string $val_1 Valor que representará el primer radio (por ejemplo, "Sí").
     * @param string $val_2 Valor que representará el segundo radio (por ejemplo, "No").
     *
     * @return array|string Devuelve el HTML del grupo de radios como string o un array en caso de error.
     *
     * @example
     * ```php
     * $html = $this->radio_doble(
     *     checked_default: 1,
     *     class_label: ['text-primary'],
     *     class_radio: ['mx-1'],
     *     cols: 6,
     *     for: 'activo',
     *     ids_css: ['id_radio_si', 'id_radio_no'],
     *     label_html: '¿Está activo?',
     *     name: 'activo',
     *     title: 'Estado del registro',
     *     val_1: 'Sí',
     *     val_2: 'No'
     * );
     * echo $html;
     * ```
     */
    private function radio_doble(int $checked_default,array $class_label, array $class_radio, int $cols,string $for,
                                 array $ids_css, string $label_html, string $name, string $title, string $val_1,
                                 string $val_2): array|string
    {

        $params_radio = $this->label_init(for: $for, label_html: $label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar params',data:  $params_radio);
        }
        if($checked_default <=0){
            return $this->error->error(mensaje: 'Error checked_default debe ser mayor a 0', data: $checked_default,
                es_final: true);
        }
        if($checked_default > 2){
            return $this->error->error(mensaje: 'Error checked_default debe ser menor a 3', data: $checked_default,
                es_final: true);
        }
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio',data:  $name, es_final: true);
        }
        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error validar cols', data: $valida);
        }


        $params = $this->params_html(checked_default: $checked_default,class_label:  $class_label,
            class_radio:  $class_radio, ids_css: $ids_css,label_html:  $params_radio->label_html,
            for:  $params_radio->for);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar params', data: $params);
        }


        $inputs = $this->labels_radios(name: $name,params:  $params,title:  $title,val_1: $val_1,val_2:  $val_2);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar inputs', data: $inputs);
        }


        $radios = $this->div_radio(cols: $cols,inputs:  $inputs,label_html:  $params->label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar radios', data: $radios);
        }

        return $radios;
    }

    /**
     * REG
     * Inicializa y asegura que la propiedad `$name` exista en el objeto `$row_upd`.
     *
     * Esta función sirve para preparar un objeto `stdClass` que contiene valores por campo, comúnmente utilizado
     * en formularios para actualizar registros. Si `$value_vacio` es `true`, sobrescribe el objeto con uno nuevo
     * y asigna una cadena vacía en la propiedad `$name`. Si la propiedad no existe, también la inicializa vacía.
     *
     * @param string $name Nombre del campo a validar o inicializar como propiedad del objeto `$row_upd`.
     * @param bool $value_vacio Si es `true`, se fuerza la creación de un nuevo objeto `$row_upd` con la propiedad `$name` vacía.
     * @param stdClass $row_upd Objeto que contiene los valores del registro a modificar. Por defecto, se inicializa vacío.
     *
     * @return stdClass|array Retorna el objeto `$row_upd` actualizado con la propiedad `$name` inicializada si es necesario.
     *                        En caso de error (como `$name` vacío), se retorna un arreglo con detalles del error.
     *
     * @example Entrada válida con valor existente:
     * ```php
     * $row_upd = new stdClass();
     * $row_upd->email = 'usuario@example.com';
     * $resultado = $this->row_upd_name('email', false, $row_upd);
     * // Resultado: stdClass con propiedad 'email' => 'usuario@example.com'
     * ```
     *
     * @example Valor vacío forzado:
     * ```php
     * $resultado = $this->row_upd_name('telefono', true);
     * // Resultado: stdClass con propiedad 'telefono' => ''
     * ```
     *
     * @example Inicializa propiedad si no existe:
     * ```php
     * $row_upd = new stdClass();
     * $resultado = $this->row_upd_name('nombre', false, $row_upd);
     * // Resultado: stdClass con propiedad 'nombre' => ''
     * ```
     *
     * @example Error por `$name` vacío:
     * ```php
     * $resultado = $this->row_upd_name('', false);
     * // Resultado:
     * // [
     * //     'mensaje' => 'Error name esta vacio',
     * //     'data' => '',
     * //     'es_final' => true
     * // ]
     * ```
     *
     * @see init_text() Utiliza esta función como parte de la preparación de datos de formulario.
     */
    private function row_upd_name(string $name, bool $value_vacio, stdClass $row_upd = new stdClass()): stdClass|array
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio', data: $name, es_final: true);
        }
        if($value_vacio){
            $row_upd = new stdClass();
            $row_upd->$name = '';
        }
        if(!isset($row_upd->$name)){
            $row_upd->$name = '';
        }

        return $row_upd;
    }

    public function textarea(bool $disabled, string $name, string $place_holder, bool $required,
                                   stdClass $row_upd, bool $value_vacio, array $ids_css = array()): array|string
    {
        $row_upd_ = $this->init_input(name:$name,place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }

        $html= $this->html->textarea(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $row_upd_->$name, ids_css: $ids_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar textarea', data: $html);
        }

        $div = $this->div_label(html:$html, name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * REG
     * Valida los datos de entrada de un botón, incluyendo su etiqueta, estilo, tipo y valor.
     *
     * Este método realiza una serie de validaciones para los parámetros `$label`, `$style`, `$type` y `$value`.
     * Primero, valida que el valor y la etiqueta no estén vacíos utilizando la función `valida_data_base`.
     * Luego verifica que los parámetros `$style` y `$type` no estén vacíos, ya que son cruciales para la correcta
     * generación y comportamiento del botón.
     *
     * Si alguna de las validaciones falla, el método devolverá un error con detalles. Si todas las validaciones
     * son exitosas, devolverá `true`.
     *
     * @param string $label La etiqueta que describe el botón. Esta etiqueta se utiliza para identificar el botón
     *                      y también se muestra en la interfaz de usuario. No puede estar vacía.
     *
     * @param string $style El estilo del botón, que normalmente corresponde a una clase de Bootstrap u otra
     *                      librería de estilos. Debe contener un valor válido (por ejemplo, 'primary', 'danger').
     *                      No puede estar vacío.
     *
     * @param string $type El tipo de botón, como 'submit', 'button', etc. Este valor es esencial para determinar
     *                     el comportamiento del botón. No debe estar vacío.
     *
     * @param string $value El valor que el botón enviará al servidor cuando sea presionado. No puede ser vacío.
     *
     * @return true|array Devuelve `true` si todas las validaciones son exitosas. Si alguna validación falla,
     *                    devuelve un array con el mensaje de error correspondiente.
     *
     * @example
     * // Caso exitoso: Todos los parámetros son válidos
     * $label = "Guardar";
     * $style = "primary";
     * $type = "submit";
     * $value = "save";
     * $resultado = $directiva->valida_btn_next($label, $style, $type, $value);
     * var_dump($resultado); // Resultado: true
     *
     * @example
     * // Caso de error: El estilo está vacío
     * $label = "Guardar";
     * $style = "";
     * $type = "submit";
     * $value = "save";
     * $resultado = $directiva->valida_btn_next($label, $style, $type, $value);
     * var_dump($resultado); // Resultado: array('mensaje' => 'Error $style esta vacio', 'data' => '')
     *
     * @example
     * // Caso de error: El tipo está vacío
     * $label = "Guardar";
     * $style = "primary";
     * $type = "";
     * $value = "save";
     * $resultado = $directiva->valida_btn_next($label, $style, $type, $value);
     * var_dump($resultado); // Resultado: array('mensaje' => 'Error $type esta vacio', 'data' => '')
     *
     * @version 1.0.0
     */
    final public function valida_btn_next(string $label, string $style, string $type, string $value): true|array
    {
        // Se valida que la etiqueta y el valor no estén vacíos
        $valida = $this->valida_data_base(label: $label, value:  $value);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Se valida que el estilo no esté vacío
        $style = trim($style);
        if ($style === '') {
            return $this->error->error(mensaje: 'Error $style esta vacio', data: $style, es_final: true);
        }

        // Se valida que el tipo no esté vacío
        $type = trim($type);
        if ($type === '') {
            return $this->error->error(mensaje: 'Error $type esta vacio', data: $type, es_final: true);
        }

        // Si todas las validaciones son exitosas, se devuelve true
        return true;
    }


    /**
     * REG
     * Valida los datos de entrada asegurándose de que tanto la etiqueta como el valor no estén vacíos.
     *
     * Este método realiza una validación básica de los parámetros `$label` y `$value`. Verifica que ambos parámetros
     * sean cadenas de texto no vacías. Si alguno de ellos está vacío, devuelve un error detallado.
     * Si ambos parámetros contienen datos válidos, devuelve `true` indicando que la validación fue exitosa.
     *
     * @param string $label La etiqueta a validar. Este parámetro debe ser una cadena de texto no vacía.
     *                      La etiqueta generalmente se usa para describir el campo o la acción que se está realizando.
     *
     * @param string $value El valor a validar. Este parámetro debe ser una cadena de texto no vacía.
     *                      El valor representaría, por ejemplo, el contenido que un usuario ha ingresado.
     *
     * @return true|array Retorna `true` si ambos parámetros son válidos (no vacíos). Si algún parámetro está vacío,
     *                    retorna un array con un mensaje de error y los datos que causaron el error.
     *
     * @example
     * // Caso exitoso: Ambas entradas no están vacías
     * $label = "Nombre";
     * $value = "Juan";
     * $resultado = $directiva->valida_data_base($label, $value);
     * var_dump($resultado); // Resultado: true
     *
     * @example
     * // Caso de error: La etiqueta está vacía
     * $label = "";
     * $value = "Juan";
     * $resultado = $directiva->valida_data_base($label, $value);
     * var_dump($resultado); // Resultado: array('mensaje' => 'Error label esta vacio', 'data' => '')
     *
     * @example
     * // Caso de error: El valor está vacío
     * $label = "Nombre";
     * $value = "";
     * $resultado = $directiva->valida_data_base($label, $value);
     * var_dump($resultado); // Resultado: array('mensaje' => 'Error $value esta vacio', 'data' => '')
     *
     * @version 1.0.0
     */
    final public function valida_data_base(string $label, string $value): true|array
    {
        // Eliminamos espacios en blanco al inicio y al final de la etiqueta
        $label = trim($label);

        // Validamos si la etiqueta está vacía
        if ($label === '') {
            return $this->error->error(mensaje: 'Error label esta vacio', data: $label, es_final: true);
        }

        // Eliminamos espacios en blanco al inicio y al final del valor
        $value = trim($value);

        // Validamos si el valor está vacío
        if ($value === '') {
            return $this->error->error(mensaje: 'Error $value esta vacio', data: $value, es_final: true);
        }

        // Si ambos parámetros son válidos, devolvemos true
        return true;
    }




    /**
     * REG
     * Valida el número de columnas proporcionado asegurando que esté dentro de un rango válido.
     *
     * Este método valida que el número de columnas (`$cols`) sea un valor entero dentro del rango permitido
     * de 1 a 12. Si el valor proporcionado es menor o igual a 0 o mayor o igual a 13, el método devuelve un error
     * indicando que el valor de las columnas no es válido. Si el valor es válido, devuelve `true`.
     *
     * @param int $cols El número de columnas a validar. Debe ser un valor entero entre 1 y 12 (inclusive).
     *
     * @return true|array Devuelve `true` si el número de columnas está dentro del rango aceptado (de 1 a 12).
     *                    Si el valor no es válido (menor o igual a 0 o mayor o igual a 13), se devuelve un array
     *                    con el mensaje de error y los detalles de la causa del error.
     *
     * @throws errores Si el número de columnas no está dentro del rango válido, se lanzará un error.
     *
     * @example
     * // Caso exitoso: El número de columnas es válido (por ejemplo, 6)
     * $cols = 6;
     * $resultado = $directiva->valida_cols($cols);
     * var_dump($resultado); // Resultado: true
     *
     * @example
     * // Caso de error: El número de columnas es inválido (por ejemplo, 0)
     * $cols = 0;
     * $resultado = $directiva->valida_cols($cols);
     * if (is_array($resultado)) {
     *     echo $resultado['mensaje'];  // Resultado: "Error cols debe ser mayor a 0"
     * }
     *
     * @example
     * // Caso de error: El número de columnas es demasiado grande (por ejemplo, 15)
     * $cols = 15;
     * $resultado = $directiva->valida_cols($cols);
     * if (is_array($resultado)) {
     *     echo $resultado['mensaje'];  // Resultado: "Error cols debe ser menor o igual a 12"
     * }
     *
     * @version 1.0.0
     */
    final public function valida_cols(int $cols): true|array
    {
        // Si el número de columnas es menor o igual a 0, se genera un error
        if ($cols <= 0) {
            return $this->error->error(mensaje: 'Error cols debe ser mayor a 0', data: $cols, es_final: true);
        }

        // Si el número de columnas es mayor o igual a 13, se genera un error
        if ($cols >= 13) {
            return $this->error->error(mensaje: 'Error cols debe ser menor o igual a 12', data: $cols, es_final: true);
        }

        // Si todo es válido, se devuelve true
        return true;
    }


    /**
     * REG
     * Valida los datos de un nombre y un marcador de lugar (place_holder) para asegurarse de que ambos no estén vacíos.
     *
     * Esta función verifica que los valores de los parámetros `$name` y `$place_holder` no estén vacíos, eliminando cualquier
     * espacio en blanco al principio y al final de los valores antes de hacer la validación. Si alguno de los dos parámetros
     * está vacío, se genera un error con un mensaje específico. Si ambos son válidos, la función devuelve `true`.
     *
     * **Pasos de validación:**
     * 1. Se elimina cualquier espacio en blanco al principio y al final de los valores `$name` y `$place_holder`.
     * 2. Se valida que `$name` no esté vacío.
     * 3. Se valida que `$place_holder` no esté vacío.
     * 4. Si alguna de las validaciones falla, se genera un error con un mensaje descriptivo.
     * 5. Si ambas validaciones pasan correctamente, se devuelve `true`.
     *
     * **Parámetros:**
     *
     * @param string $name El nombre del campo de entrada. Este parámetro es obligatorio y no debe estar vacío.
     *                     Representa el nombre del campo que se utilizará en el formulario.
     * @param string $place_holder El texto que se muestra como marcador de posición en el campo de entrada.
     *                             Este parámetro es obligatorio y no debe estar vacío.
     *
     * **Retorno:**
     * - Devuelve `true` si ambos parámetros no están vacíos y son válidos.
     * - Si alguno de los parámetros está vacío, devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Validación exitosa**
     * ```php
     * $name = "usuario_id";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->valida_data_label($name, $place_holder);
     * // Retorna true porque ambos parámetros son válidos.
     * ```
     *
     * **Ejemplo 2: Error por $name vacío**
     * ```php
     * $name = "";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->valida_data_label($name, $place_holder);
     * // Retorna un arreglo con el mensaje de error: 'Error $name debe tener info'.
     * ```
     *
     * **Ejemplo 3: Error por $place_holder vacío**
     * ```php
     * $name = "usuario_id";
     * $place_holder = "";
     * $resultado = $this->valida_data_label($name, $place_holder);
     * // Retorna un arreglo con el mensaje de error: 'Error $place_holder debe tener info'.
     * ```
     *
     * **@version 1.0.0**
     */
    final public function valida_data_label(string $name, string $place_holder): true|array
    {
        // Eliminar espacios en blanco al principio y al final de los valores
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error $name debe tener info', data: $name, es_final: true);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder debe tener info', data: $place_holder, es_final: true);
        }
        return true;
    }


    /**
     * REG
     * Valida que los parámetros de nombre (`$name`) y placeholder (`$place_holder`) no estén vacíos.
     *
     * Este método es útil como validación previa a la construcción de etiquetas HTML (`<label>`, `placeholder`)
     * y asegura que los valores de entrada sean cadenas no vacías después de aplicar `trim()`.
     *
     * Si alguno de los valores está vacío, devuelve un arreglo de error con detalles del problema.
     *
     * @param string $name Nombre del campo o identificador del input, usado generalmente como atributo `id` y `for`.
     * @param string $place_holder Texto descriptivo, normalmente usado como etiqueta visible en formularios.
     *
     * @return true|array Retorna `true` si ambos valores son válidos. En caso contrario, retorna un arreglo de error
     *                    generado por `$this->error->error()` indicando cuál campo está vacío.
     *
     * @example Validación exitosa:
     * ```php
     * $resultado = $this->valida_etiquetas(name: 'email', place_holder: 'Correo electrónico');
     * // Resultado: true
     * ```
     *
     * @example Error por `$name` vacío:
     * ```php
     * $resultado = $this->valida_etiquetas(name: '', place_holder: 'Nombre completo');
     * // Resultado:
     * // [
     * //     'mensaje' => 'Error el $name esta vacio',
     * //     'data' => '',
     * //     'es_final' => true
     * // ]
     * ```
     *
     * @example Error por `$place_holder` vacío:
     * ```php
     * $resultado = $this->valida_etiquetas(name: 'telefono', place_holder: '');
     * // Resultado:
     * // [
     * //     'mensaje' => 'Error el $place_holder esta vacio',
     * //     'data' => '',
     * //     'es_final' => true
     * // ]
     * ```
     *
     * @see div_label() Este método puede usarse como validación previa a la creación de un `div` con etiquetas.
     */
    private function valida_etiquetas(string $name, string $place_holder): true|array
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error el $name esta vacio', data: $name, es_final: true);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error el $place_holder esta vacio', data: $place_holder
                , es_final: true);
        }
        return true;
    }

    /**
     * REG
     * Obtiene el valor que será utilizado para un input HTML a partir de los datos de actualización
     * contenidos en un objeto estándar `$init`. Si el campo especificado no existe, se inicializa
     * con una cadena vacía. Si existe y su valor no es `null`, se sobreescribe con el valor proporcionado
     * en el parámetro `$value`.
     *
     * @param stdClass $init Objeto que contiene el atributo `row_upd`, el cual es un objeto con los datos
     *                       que están siendo actualizados.
     * @param string $name Nombre del campo a evaluar. Este nombre debe ser un string no vacío y no numérico.
     * @param float|int|string|null $value Valor alternativo que se usará si el valor actual del campo
     *                                     en `row_upd` no es null.
     *
     * @return float|int|string|null|array Retorna el valor del campo si está definido o el valor alternativo
     *                                     si no es null. Si se produce un error, se retorna un array con el detalle.
     *
     * @example Ejemplo de uso básico:
     * ```php
     * $init = new stdClass();
     * $init->row_upd = new stdClass();
     * $init->row_upd->email = 'ejemplo@correo.com';
     * $valor = $this->value_input($init, 'email', 'nuevo@correo.com');
     * // Resultado: 'nuevo@correo.com' (porque el valor original no es null)
     * ```
     *
     * @example Campo no existe:
     * ```php
     * $init = new stdClass();
     * $init->row_upd = new stdClass(); // No contiene 'telefono'
     * $valor = $this->value_input($init, 'telefono', '555-1234');
     * // Resultado: '' (el campo no existe, pero se inicializa como string vacío y no se reemplaza)
     * ```
     *
     * @example Campo con valor null:
     * ```php
     * $init = new stdClass();
     * $init->row_upd = new stdClass();
     * $init->row_upd->direccion = null;
     * $valor = $this->value_input($init, 'direccion', 'Calle Falsa 123');
     * // Resultado: null (porque el valor original es null, no se sobreescribe)
     * ```
     *
     * @example Validación de errores:
     * ```php
     * $init = new stdClass(); // No contiene row_upd
     * $valor = $this->value_input($init, 'correo', 'test@test.com');
     * // Resultado: ['error' => true, 'mensaje' => 'Error $init->row_upd no existe', ...]
     * ```
     *
     * @example Parámetro $name inválido:
     * ```php
     * $init = new stdClass();
     * $init->row_upd = new stdClass();
     * $valor = $this->value_input($init, '', 'valor');
     * // Resultado: ['error' => true, 'mensaje' => 'Error name esta vacio', ...]
     * ```
     */
    private function value_input(
        stdClass $init, string $name, string|null|int|float $value): float|int|string|null|array
    {
        if(!isset($init->row_upd)){
            return $this->error->error(mensaje: 'Error $init->row_upd no existe', data: $init, es_final: true);
        }
        if(!is_object($init->row_upd)){
            return $this->error->error(mensaje: 'Error $init->row_upd debe ser un objeto', data: $init, es_final: true);
        }
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio', data: $name, es_final: true);
        }
        if(is_numeric($name)){
            return $this->error->error(mensaje: 'Error name debe ser un texto no un numero', data: $name,
                es_final: true);
        }
        if(!isset($init->row_upd->$name)){
            $init->row_upd->$name = '';
        }
        $value_input = $init->row_upd->$name;
        if(!is_null($value_input)){
            $value_input = $value;
        }
        return $value_input;
    }
}
