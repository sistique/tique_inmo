<?php
namespace tests\src;

use gamboamartin\administrador\models\adm_accion;
use gamboamartin\errores\errores;
use gamboamartin\template\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use JetBrains\PhpStorm\NoReturn;
use JsonException;
use stdClass;


class htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();

    }

    /**
     */
    #[NoReturn] public function test_alert_success(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $mensaje = 'a';

        $resultado = $html->alert_success($mensaje);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='alert alert-success' role='alert'><strong>Muy bien!</strong> a.</div>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_alert_warning(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $mensaje = 'a';
        $resultado = $html->alert_warning($mensaje);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='alert alert-warning' role='alert'><strong>Advertencia!</strong> a.</div>", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_button_href(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $accion = 'b';
        $etiqueta = 'd';
        $registro_id = '-1';
        $seccion = 'a';
        $style = 'c';



        $resultado = $html->button_href($accion, $etiqueta, $registro_id, $seccion, $style);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<a |role| href='index.php?seccion=a&accion=b&registro_id=-1&session_id=1' |class|>d</a>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_concat_descripcion_select(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $column = "z";
        $descripcion_select = "";
        $row = array();
        $row['z'] = 'x';
        $resultado = $html->concat_descripcion_select(column: $column,descripcion_select:  $descripcion_select,row:  $row);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("x", $resultado);
        errores::$error = false;

        $column = "z";
        $descripcion_select = "q";
        $row = array();
        $row['z'] = 'x';
        $resultado = $html->concat_descripcion_select(column: $column,descripcion_select:  $descripcion_select,row:  $row);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("q x", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_descripcion_select(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $columns_ds = array();
        $row = array();
        $row['a'] = 'q';
        $columns_ds[] = 'a';
        $resultado = $html->descripcion_select($columns_ds, $row);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("q", $resultado);
        errores::$error = false;
        $columns_ds = array();
        $row = array();
        $row['a'] = 'q';
        $row['b'] = 'r';
        $columns_ds[] = 'a';
        $columns_ds[] = 'b';
        $resultado = $html->descripcion_select($columns_ds, $row);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("q r", $resultado);

        errores::$error = false;
    }

    #[NoReturn] public function test_data_option(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $columns_ds = array();
        $row = array();
        $key_value_custom = '';
        $row['descripcion_select'] = 'x';
        $resultado = $html->data_option($columns_ds, $key_value_custom, $row);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado->value_custom);
        errores::$error = false;

        $columns_ds = array();
        $row = array();
        $row['z'] = 'd';
        $key_value_custom = 'z';
        $row['descripcion_select'] = 'x';
        $resultado = $html->data_option($columns_ds, $key_value_custom, $row);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("d", $resultado->value_custom);
        errores::$error = false;
    }

    #[NoReturn] public function test_div_controls(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $contenido = "";

        $resultado = $html->div_controls($contenido);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='controls'></div>", $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_div_control_group_cols(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $contenido = 'x';
        $cols = 5;


        $resultado = $html->div_control_group_cols($cols, $contenido);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group col-sm-5'>x</div>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_div_control_group_cols_label(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $contenido = "";
        $cols = 1;
        $label = 'a';
        $name = 'b';
        $resultado = $html->div_control_group_cols_label($cols, $contenido, $label, $name);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group col-sm-1'></div>", $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_div_group(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;
        $cols = 1;
        $html_txt = '';


        $resultado = $html->div_group($cols, $html_txt);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|></div>", $resultado);

        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_div_label(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $html_ = 'b';
        $label = 'd';


        $resultado = $html->div_label($html_, $label);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("d<div |class|>b</div>", $resultado);

    }

    /**
     */
    #[NoReturn] public function test_div_select(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'b';
        $options_html = 'd';

        $resultado = $html->div_select($name, $options_html);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<select class='form-control selectpicker color-secondary b ' data-live-search='true' id='b' name='b'  >d</select>",$resultado);

        errores::$error = false;

        $name = 'b';
        $options_html = 'd';
        $required = "required";

        $resultado = $html->div_select($name, $options_html, array(), $required);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<select class='form-control selectpicker color-secondary b ' data-live-search='true' id='b' name='b'  disabled>d</select>",$resultado);

        errores::$error = false;

        $name = 'b';
        $options_html = 'd';
        $required = true;

        $resultado = $html->div_select($name, $options_html, array(), $required);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;

        $name = 'b';
        $options_html = 'd';
        $required = true;

        $resultado = $html->div_select($name, $options_html, array('j','k'), $required);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<select class='form-control selectpicker color-secondary b j  k' data-live-search='true' id='b' name='b'  disabled>d</select>",$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_extra_param_data(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $row = array();
        $extra_params_key = array();
        $extra_params_key[] = 'x';

        $resultado = $html->extra_param_data($extra_params_key, $row);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("SIN DATOS",$resultado['x']);

        errores::$error = false;

        $row = array();
        $row['x'] = 'd';
        $extra_params_key = array();
        $extra_params_key[] = 'x';

        $resultado = $html->extra_param_data($extra_params_key, $row);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("d",$resultado['x']);
        errores::$error = false;

    }

    public function test_label(): void
    {
        errores::$error = false;
        $html = new html();
        //$inicializacion = new liberator($inicializacion);

        $id_css = 'a';
        $place_holder = 'c';
        $resultado = $html->label($id_css, $place_holder);


        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);


        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_params_txt(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;
        $disabled = false;
        $id_css = 'b';
        $name = 'a';
        $place_holder = 'c';
        $required = false;


        $resultado = $html->params_txt($disabled, $id_css, $name, $place_holder, $required);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

    }

    #[NoReturn] public function test_email(): void
    {
        errores::$error = false;
        $html = new html();
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = 'c';
        $name = 'a';
        $place_holder = 'c';
        $required = false;
        $value = '';

        $resultado = $html->email($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='text' name='a' value='' |class|   id='c' placeholder='c' pattern='[^@\s]+@[^@\s]+[^.\s]' />",$resultado);

    }

    #[NoReturn] public function test_extra_params(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;


        $extra_params = array();
        $extra_params['a'] = '-1';
        $resultado = $html->extra_params($extra_params);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(" data-a = '-1'",$resultado);

        errores::$error = false;

        $extra_params = array();
        $extra_params['a'] = '-1';
        $extra_params['b'] = '2';
        $resultado = $html->extra_params($extra_params);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(" data-a = '-1' data-b = '2'",$resultado);
        errores::$error = false;

    }

    #[NoReturn] public function test_fecha(): void
    {
        errores::$error = false;
        $html = new html();
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = 'a';
        $name = 'a';
        $place_holder = 'a';
        $required = false;
        $value = '';

        $resultado = $html->fecha($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='date' name='a' value='' |class|   id='a' placeholder='a' />",$resultado);

        errores::$error = false;
    }

    #[NoReturn] public function test_file(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $required = false;
        $id_css = 'b';
        $place_holder = 'c';
        $name = 'a';
        $value = '';
        $resultado = $html->file($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='file' name='a' value='' class = 'form-control'   id='b'  />",$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_integra_options_html(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $descripcion_select = "a";
        $id_selected = 1;
        $value = "1";
        $options_html = "";

        $resultado = $html->integra_options_html(descripcion_select: $descripcion_select, id_selected: $id_selected,
            options_html: $options_html, value: $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='1' selected >a</option>",$resultado);
        errores::$error = false;

        $descripcion_select = "a";
        $id_selected = 'x';
        $value = "1";
        $options_html = "";

        $resultado = $html->integra_options_html(descripcion_select: $descripcion_select, id_selected: $id_selected,
            options_html: $options_html, value: $value);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='1'  >a</option>",$resultado);
        errores::$error = false;

        $descripcion_select = "a";
        $id_selected = 'x';
        $value = "x";
        $options_html = "";

        $resultado = $html->integra_options_html(descripcion_select: $descripcion_select, id_selected: $id_selected,
            options_html: $options_html, value: $value);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='x' selected >a</option>",$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_limpia_salida(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $html_ = '/  /    /  /';


        $resultado = $html->limpia_salida($html_);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("/ / / /",$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_menu_lateral(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);


        $etiqueta = 'a';
        $resultado = $html->menu_lateral($etiqueta);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<span class='texto-menu-lateral'>a</span>",$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_monto(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = 'd';
        $required = true;
        $name = 'd';
        $place_holder = 'd';
        $value = '';
        $resultado = $html->monto($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='text' name='d' value='' |class|  required id='d' placeholder='d' />", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_number_menu_lateral(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $number = 'a';

        $resultado = $html->number_menu_lateral($number);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<img src='https://localhost/facturacion/vendor/gamboa.martin/template_1/assets/img/numeros/a.svg' class='numero'>", $resultado);
        errores::$error = false;
    }


    #[NoReturn] public function test_option(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $descripcion = "a";
        $selected = false;
        $value = "-1";

        $resultado = $html->option($descripcion, $selected, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value=''  >a</option>", $resultado);

        errores::$error = false;
        $descripcion = "campo";
        $selected = false;
        $value = "campo";
        $resultado = $html->option($descripcion, $selected, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='campo'  >campo</option>", $resultado);

        errores::$error = false;
        $descripcion = "campo";
        $selected = false;
        $value = 1;
        $resultado = $html->option($descripcion, $selected, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='1'  >campo</option>", $resultado);

        errores::$error = false;
        $descripcion = "campo";
        $selected = false;
        $value = -1;
        $resultado = $html->option($descripcion, $selected, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value=''  >campo</option>", $resultado);

        errores::$error = false;
        $descripcion = "campo";
        $selected = false;
        $value = -1;
        $extra_params = array();
        $resultado = $html->option($descripcion, $selected, $value, $extra_params);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value=''  >campo</option>", $resultado);

        errores::$error = false;
        $descripcion = "campo";
        $selected = false;
        $value = -1;
        $extra_params = array();
        $extra_params['a'] = '';
        $extra_params['b'] = '';
        $resultado = $html->option($descripcion, $selected, $value, $extra_params);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value=''   data-a = '' data-b = ''>campo</option>", $resultado);



        errores::$error = false;
    }

    #[NoReturn] public function test_option_con_extra_param(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $options_html_ = "";
        $id_selected = '';
        $extra_params_key = array();
        $row = array();
        $row['descripcion_select'] = 'A';
        $row_id = '';
        $value_custom = '';

        $resultado = $html->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value=''  >A</option>", $resultado);

        errores::$error = false;

        $options_html_ = "";
        $id_selected = '';
        $extra_params_key = array();
        $row = array();
        $row['descripcion_select'] = 'A';
        $row_id = '1';
        $value_custom = '';

        $resultado = $html->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='1'  >A</option>", $resultado);

        errores::$error = false;

        $options_html_ = "";
        $id_selected = '';
        $extra_params_key = array();
        $row = array();
        $row['descripcion_select'] = 'A';
        $row_id = '1';
        $value_custom = 'C';

        $resultado = $html->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='C'  >A</option>", $resultado);
        errores::$error = false;


        $options_html_ = "";
        $id_selected = null;
        $extra_params_key = array();
        $row = array();
        $row['descripcion_select'] = 'A';
        $row_id = '1';
        $value_custom = 'C';

        $resultado = $html->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='C'  >A</option>", $resultado);

        errores::$error = false;

        $options_html_ = "";
        $id_selected = 'fd';
        $extra_params_key = array();
        $row = array();
        $row['descripcion_select'] = 'A';
        $row_id = '1';
        $value_custom = 'C';

        $resultado = $html->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='C'  >A</option>", $resultado);

        errores::$error = false;

        $options_html_ = "";
        $id_selected = 'fd';
        $extra_params_key = array();
        $row = array();
        $row['descripcion_select'] = 'A';
        $row_id = '1';
        $value_custom = 'fd';

        $resultado = $html->option_con_extra_param($extra_params_key, $id_selected, $options_html_, $row, $row_id, $value_custom);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='fd' selected >A</option>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_option_html(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $descripcion_select = "a";
        $id_selected = -1;
        $value = "";


        $resultado = $html->option_html(descripcion_select: $descripcion_select, id_selected: $id_selected,
            value: $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='' selected >a</option>",$resultado);

        errores::$error = false;

        $descripcion_select = "a";
        $id_selected = 'x';
        $value = "";


        $resultado = $html->option_html(descripcion_select: $descripcion_select, id_selected: $id_selected,
            value: $value);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value=''  >a</option>",$resultado);


        errores::$error = false;

        $descripcion_select = "a";
        $id_selected = 'x';
        $value = "x";


        $resultado = $html->option_html(descripcion_select: $descripcion_select, id_selected: $id_selected,
            value: $value);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='x' selected >a</option>",$resultado);
        errores::$error = false;

    }

    #[NoReturn] public function test_options(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $id_selected = 1;
        $values = array();
        $values[1]['descripcion_select'] = 'x';
        $resultado = $html->options(
            columns_ds: array(), extra_params_key: array(), id_selected: $id_selected, key_value_custom: '', values: $values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value=''  >Selecciona una opcion</option><option value='1' selected >x</option>",$resultado);
        errores::$error = false;

        $id_selected = 1;
        $values = array();
        $values[1]['descripcion_select'] = 'x';
        $values[1]['test'] = 'abc';
        $key_value_custom = 'test';
        $resultado = $html->options(
            columns_ds: array(), extra_params_key: array(), id_selected: $id_selected, key_value_custom: $key_value_custom, values: $values);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value=''  >Selecciona una opcion</option><option value='abc'  >x</option>",$resultado);
        errores::$error = false;


    }

    #[NoReturn] public function test_options_html_data(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $id_selected = 1;
        $options_html = "";
        $values = array();
        $values[0]['descripcion_select'] = 'x';

        $resultado = $html->options_html_data(columns_ds: array(), extra_params_key: array(),
            id_selected: $id_selected, key_value_custom: '', options_html: $options_html, values: $values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='0'  >x</option>",$resultado);
        errores::$error = false;

        $id_selected = 'a';
        $options_html = "";
        $values = array();
        $values[1]['descripcion_select'] = 'x';
        $key_value_custom = '';

        $resultado = $html->options_html_data(columns_ds: array(), extra_params_key: array(),
            id_selected: $id_selected, key_value_custom: $key_value_custom, options_html: $options_html, values: $values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='1'  >x</option>",$resultado);
        errores::$error = false;

        $id_selected = 'a';
        $options_html = "";
        $values = array();
        $values[1]['descripcion_select'] = 'x';
        $values[1]['test'] = 'zzz';
        $key_value_custom = 'test';

        $resultado = $html->options_html_data(columns_ds: array(), extra_params_key: array(),
            id_selected: $id_selected, key_value_custom: $key_value_custom, options_html: $options_html, values: $values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='zzz'  >x</option>",$resultado);
        errores::$error = false;


        errores::$error = false;

        $id_selected = 'a';
        $options_html = "";
        $values = array();
        $values[1]['descripcion_select'] = 'x';
        $values[1]['test'] = 'zzz';
        $values[1]['z'] = 'w';
        $values[1]['t'] = 'y';
        $key_value_custom = '';
        $columns_ds[] = 'z';
        $columns_ds[] = 't';


        $resultado = $html->options_html_data(columns_ds: $columns_ds, extra_params_key: array(),
            id_selected: $id_selected, key_value_custom: $key_value_custom, options_html: $options_html, values: $values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='1'  >w y</option>",$resultado);
        errores::$error = false;


    }

    #[NoReturn] public function test_password(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = 'c';
        $required = true;
        $name = 'a';
        $place_holder = 'd';
        $value = '';
        $resultado = $html->password($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='password' name='a' value='' class='form-control'   required id='c' placeholder='d' />",$resultado);

        errores::$error = false;
    }

    #[NoReturn] public function test_row_descripcion_select(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $columns_ds = array();
        $row = array();
        $row['a'] = ' j  ';
        $columns_ds[] = 'a';
        $resultado = $html->row_descripcion_select($columns_ds, $row);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(" j  ",$resultado['a']);
        $this->assertEquals("j",$resultado['descripcion_select']);

        errores::$error = false;

        $columns_ds = array();
        $row = array();
        $row['a'] = ' j  ';
        $row['b'] = ' u  ';
        $columns_ds[] = 'a';
        $columns_ds[] = 'b';
        $resultado = $html->row_descripcion_select($columns_ds, $row);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(" j  ",$resultado['a']);
        $this->assertEquals(" u  ",$resultado['b']);
        $this->assertEquals("j u",$resultado['descripcion_select']);

        errores::$error = false;
    }

    #[NoReturn] public function test_select(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $id_selected = -1;
        $cols = 12;
        $label = 'a';

        $name = 'z';
        $values = array();

        $resultado = $html->select(cols: $cols, id_selected: $id_selected, label: $label,  name: $name,
            values: $values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group col-sm-12'><div class='controls'><select class='form-control selectpicker color-secondary z ' data-live-search='true' id='z' name='z'  ><option value=''  >Selecciona una opcion</option></select></div></div>",$resultado);
        errores::$error = false;

        $id_selected = -1;
        $cols = 12;
        $label = 'a';

        $name = 'z';
        $values = array();
        $values[1]['descripcion_select'] = 'A';
        $values[1]['test'] = 'weq';
        $key_value_custom = 'test';

        $resultado = $html->select(cols: $cols, id_selected: $id_selected, label: $label,  name: $name,
            values: $values, key_value_custom: $key_value_custom);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group col-sm-12'><div class='controls'><select class='form-control selectpicker color-secondary z ' data-live-search='true' id='z' name='z'  ><option value=''  >Selecciona una opcion</option><option value='weq'  >A</option></select></div></div>",$resultado);
        errores::$error = false;


    }

    #[NoReturn] public function test_select_html(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $options_html = "";
        $cols = 12;
        $label = 'a';
        $name = 'b';
        $resultado = $html->select_html($cols, $label, $name, $options_html);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group col-sm-12'><div class='controls'><select class='form-control selectpicker color-secondary b ' data-live-search='true' id='b' name='b'  ></select></div></div>",$resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_selected(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $value = '5';
        $id_selected = 5;


        $resultado = $html->selected($value, $id_selected);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;

        $value = '6';
        $id_selected = 5;


        $resultado = $html->selected($value, $id_selected);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotTrue($resultado);
        errores::$error = false;
        $value = '';
        $id_selected = '1';

        $resultado = $html->selected($value, $id_selected);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotTrue($resultado);

        errores::$error = false;
        $value = 'x';
        $id_selected = 'x';

        $resultado = $html->selected($value, $id_selected);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;

    }

    #[NoReturn] public function test_submit(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);

        $css = 'a';
        $label = 'v';
        $resultado = $html->submit($css, $label);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group btn-modifica'><div class='controls'><button type='submit' class='btn btn-a'>v</button><br></div></div>",$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_telefono(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $required = false;
        $id_css = 'g';
        $place_holder = 'd';
        $name = 'a';
        $value = '';
        $resultado = $html->telefono($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='text' name='a' value='' class='form-control'    id='g' placeholder='d' pattern='[1-9]{1}[0-9]{9}' />",$resultado);
        errores::$error = false;
    }


    /**
     */
    #[NoReturn] public function test_text(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = 'c';
        $name = 'a';
        $place_holder = 'c';
        $required = false;
        $value = '';


        $resultado = $html->text($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='text' name='a' value='' |class| id='c' placeholder='c' title='c' />",$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_text_class(): void
    {
        errores::$error = false;
        $html = new html();
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = '';
        $name = 'a';
        $place_holder = '';
        $required = false;
        $value = '';
        $class_css = array();
        $resultado = $html->text_class($class_css, $disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='text' name='a' value='' id='a' placeholder='A' title='A' />",$resultado);
        errores::$error = false;
    }


    public function test_valida_input(): void
    {
        errores::$error = false;
        $html = new html();
        //$inicializacion = new liberator($inicializacion);

        $accion = '';
        $etiqueta = '';
        $seccion = '';
        $style = '';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error la $seccion esta vacia', $resultado['mensaje']);

        errores::$error = false;

        $accion = '';
        $etiqueta = '';
        $seccion = 'a';
        $style = '';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error la $accion esta vacia', $resultado['mensaje']);

        errores::$error = false;

        $accion = 'a';
        $etiqueta = '';
        $seccion = 'a';
        $style = '';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error la $style esta vacia', $resultado['mensaje']);

        errores::$error = false;

        $accion = 'a';
        $etiqueta = '';
        $seccion = 'a';
        $style = 'a';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error la $etiqueta esta vacia', $resultado['mensaje']);

        errores::$error = false;

        $accion = 'a';
        $etiqueta = 'a';
        $seccion = 'a';
        $style = 'a';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;

    }

    /**
     */
    #[NoReturn] public function test_valida_input_select(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $cols = -1;
        $label = '';
        $name = '';

        $resultado = $html->valida_input_select($cols, $label, $name);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error el label  está vacío', $resultado['mensaje']);

        errores::$error = false;

        $cols = -1;
        $label = 'a';
        $name = '';

        $resultado = $html->valida_input_select($cols, $label, $name);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error el name  está vacío', $resultado['mensaje']);

        errores::$error = false;

        $cols = -1;
        $label = 'a';
        $name = 'b';

        $resultado = $html->valida_input_select($cols, $label, $name);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar cols', $resultado['mensaje']);

        errores::$error = false;

        $cols = 13;
        $label = 'a';
        $name = 'b';

        $resultado = $html->valida_input_select($cols, $label, $name);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar cols', $resultado['mensaje']);

        errores::$error = false;

        $cols = 12;
        $label = 'a';
        $name = 'b';

        $resultado = $html->valida_input_select($cols, $label, $name);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_valida_option(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);

        $descripcion = 'b';
        $value = 'a';
        $resultado = $html->valida_option($descripcion, $value);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_valida_params_txt(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);

        $id_css = 'b';
        $name = 'a';
        $place_holder = 'c';
        $resultado = $html->valida_params_txt($id_css, $name, $place_holder);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;

    }

    #[NoReturn] public function test_value_custom(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $row = array();
        $key_value_custom = 'z';
        $row['z'] = 'rr';
        $resultado = $html->value_custom($key_value_custom, $row);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("rr",$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_value_custom_row(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $row = array();
        $key_value_custom = 'a';
        $row['a'] = 'x';
        $resultado = $html->value_custom_row($key_value_custom, $row);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('x',$resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_value_select(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);

        $row_id = 'a';
        $value_custom = '';

        $resultado = $html->value_select($row_id, $value_custom);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado);
        errores::$error = false;

        $row_id = 'a';
        $value_custom = 'dd';

        $resultado = $html->value_select($row_id, $value_custom);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('dd',$resultado);
        errores::$error = false;


    }



}

