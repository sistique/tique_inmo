<?php
namespace tests\src;

use gamboamartin\errores\errores;
use gamboamartin\template\directivas;
use gamboamartin\template\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use JetBrains\PhpStorm\NoReturn;
use JsonException;
use stdClass;


class directivasTest extends test {
    public errores $errores;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();


    }

    #[NoReturn] public function test_btn(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $ids_css = array();
        $clases_css = array();
        $extra_params = array();
        $label = '';
        $name = 'a';
        $value = 'b';


        $resultado = $html->btn($ids_css, $clases_css, $extra_params, $label, $name, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<button type='button' class='btn btn-info btn-guarda col-md-6 ' id='' name='a' value='b' >A</button>", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_btn_action_next(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $label = '1';
        $value = 'c';


        $resultado = $html->btn_action_next($label, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<button type='submit' class='btn btn-info btn-guarda col-md-12' name='btn_action_next' value='c'>1</button>", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_btn_action_next_div(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $label = 'a';
        $value = 'v';


        $resultado = $html->btn_action_next_div($label, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='col-md-6'><button type='submit' class='btn btn-info btn-guarda col-md-12' name='btn_action_next' value='v'>a</button></div>", $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_button_href(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $accion = 'd';
        $etiqueta = 'f';
        $name = 'a';
        $place_holder = 'b';
        $registro_id = '-1';
        $seccion = 'c';
        $style = 'e';


        $resultado = $html->button_href($accion, $etiqueta, $name, $place_holder, $registro_id, $seccion, $style);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><a |role| href='index.php?seccion=c&accion=d&registro_id=-1&session_id=1' |class|>f</a></div>", $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_button_href_status(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $cols = '2';
        $registro_id = '-1';
        $seccion = 'a';
        $status = 'c';


        $resultado = $html->button_href_status($cols, $registro_id, $seccion, $status);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><div |class|><a |role| href='index.php?seccion=a&accion=status&registro_id=-1&session_id=1' |class|>c</a></div></div>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_checked_default()
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $checked_default = 1;

        $resultado = $html->checked_default($checked_default);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("checked", $resultado->checked_default_v1);
        $this->assertEquals("", $resultado->checked_default_v2);
        errores::$error = false;
    }

    #[NoReturn] public function test_class_label_html(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $class_label = array();
        $class_label[] = 'a';

        $resultado = $html->class_label_html($class_label);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("class='a form-check-label'", $resultado);
       errores::$error = false;
    }

    #[NoReturn] public function test_class_radio_html(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;


        $class_radio = array();
        $class_radio[] = 'a';
        $resultado = $html->class_radio_html($class_radio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("class='a form-check-input'", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_div_label(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $html__ = '';
        $place_holder = 'b';

        $resultado = $html->div_label($html__, $name, $place_holder);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|></div>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_div_radio(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $cols = 1;
        $inputs = new stdClass();
        $label_html = '';
        $inputs->label_input_v1 = 'a';
        $inputs->label_input_v2 = 'b';

        $resultado = $html->div_radio($cols, $inputs, $label_html);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-1'>", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_email_required(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disable = false;
        $name = 'a';
        $row_upd = new stdClass();
        $value_vacio = false;
        $place_holder = 'x';


        $resultado = $html->email_required($disable, $name, $place_holder, $row_upd, $value_vacio);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='text' name='a' value='' |class| required id='a' placeholder='x' pattern='[^@\s]+@[^@\s]+[^.\s]' /></div>",$resultado);

    }

    #[NoReturn] public function test_fecha(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        // $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $disabled = false;
        $value_vacio = false;
        $place_holder = 'b';
        $row_upd = new stdClass();
        $required = false;

        $resultado = $html->fecha($disabled, $name, $place_holder, $required, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='date' name='a' value='' |class| id='a' placeholder='b' /></div>", $resultado);

        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_fecha_required(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        // $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $disable = false;
        $value_vacio = false;
        $place_holder = 'c';
        $row_upd = new stdClass();

        $resultado = $html->fecha_required($disable, $name, $place_holder, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='date' name='a' value='' |class| required id='a' placeholder='c' /></div>", $resultado);

        errores::$error = false;
    }

    #[NoReturn] public function test_ids_html(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $ids_css = array();


        $resultado = $html->ids_html($ids_css);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);

        errores::$error = false;
        $ids_css = array();
        $ids_css[] = ' a';
        $ids_css[] = 'b  ';
        $resultado = $html->ids_html($ids_css);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("id='a b'", $resultado);

        errores::$error = false;

    }

    #[NoReturn] public function test_init()
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;


        $name = 'a';
        $place_holder = 'x';
        $value = true;
        $row_upd = new stdClass();
        $value_vacio = false;

        $resultado = $html->init($name, $place_holder, $row_upd, $value, $value_vacio);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado->row_upd->a);
        errores::$error = false;
    }


    /**
     */
    #[NoReturn] public function test_init_input(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'q';
        $place_holder = 'f';
        $row_upd = new stdClass();
        $value_vacio = false;


        $resultado = $html->init_input($name, $place_holder, $row_upd, $value_vacio);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado->q);

        errores::$error = false;
    }

    #[NoReturn] public function test_init_names(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $title = '';


        $resultado = $html->init_names($name, $title);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a", $resultado->name);
        $this->assertEquals("A", $resultado->title);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_init_text(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $place_holder = 'a';
        $row_upd = new stdClass();
        $value_vacio = false;

        $resultado = $html->init_text($name, $place_holder, $row_upd, $value_vacio);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado->row_upd->a);
        $this->assertEquals("", $resultado->label);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_input_alias(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $row_upd = new stdClass();

        $value_vacio = false;


        $resultado = $html->input_alias($row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><div |class|><input type='text' name='alias' value='' |class| required id='alias' placeholder='Alias' title='Alias' /></div></div>", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_input_codigo(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $row_upd = new stdClass();
        $cols = '1';
        $value_vacio = true;

        $resultado = $html->input_codigo($cols, $row_upd, $value_vacio);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><div |class|><input type='text' name='codigo' value='' |class| required id='codigo' placeholder='Codigo' title='Codigo' /></div></div>", $resultado);
        errores::$error = false;
    }
    /**
     */
    #[NoReturn] public function test_input_codigo_bis(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $row_upd = new stdClass();
        $cols = '1';
        $value_vacio = true;

        $resultado = $html->input_codigo_bis($cols, $row_upd, $value_vacio);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><div |class|><input type='text' name='codigo_bis' value='' |class| required id='codigo_bis' placeholder='Codigo BIS' title='Codigo BIS' /></div></div>", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_input_descripcion(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $row_upd = new stdClass();
        $value_vacio = false;


        $resultado = $html->input_descripcion($row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><div |class|><input type='text' name='descripcion' value='' |class| required id='descripcion' placeholder='Descripcion' title='Descripcion' /></div></div>", $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_input_descripcion_select(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $row_upd = new stdClass();
        $value_vacio = false;


        $resultado = $html->input_descripcion_select($row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><div |class|><input type='text' name='descripcion_select' value='' |class| required id='descripcion_select' placeholder='Descripcion Select' title='Descripcion Select' /></div></div>", $resultado);

        errores::$error = false;
    }

    #[NoReturn] public function test_input_fecha_required(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $name = 'a';
        $place_holder = '';
        $row_upd = new stdClass();
        $value_vacio = false;


        $resultado = $html->input_fecha_required($disabled, $name, $place_holder, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='date' name='a' value='' |class| required id='a' placeholder='A' /></div>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_input_file(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $name = 'a';
        $place_holder = 'b';
        $required = true;
        $row_upd = new stdClass();
        $value_vacio = false;

        $resultado = $html->input_file($disabled, $name, $place_holder, $required, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='file' name='a' value='' class = 'form-control' required id='a' /></div>", $resultado);
        errores::$error = false;

    }

    /**
     */
    #[NoReturn] public function test_input_id(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        // $html = new liberator($html);
        $_GET['session_id'] = 1;

        $cols = 1;
        $value_vacio = false;
        $row_upd = new stdClass();

        $resultado = $html->input_id($cols, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><div |class|><input type='text' name='id' value='' |class| disabled id='id' placeholder='ID' title='ID' /></div></div>", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_input_password(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disable = false;
        $name = 'a';
        $place_holder = 'b';
        $row_upd = new stdClass();
        $value_vacio = false;

        $resultado = $html->input_password($disable, $name, $place_holder, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='password' name='a' value='' class='form-control' required id='a' placeholder='b' /></div>", $resultado);

        errores::$error = false;
    }

    #[NoReturn] public function test_input_monto_required(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $name = 'a';
        $row_upd = new stdClass();
        $place_holder = 'b';
        $value_vacio = false;

        $resultado = $html->input_monto_required($disabled, $name, $place_holder, $row_upd, $value_vacio, );
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='text' name='a' value='' |class| required id='a' placeholder='b' /></div>", $resultado);

        errores::$error = false;

        $disabled = false;
        $name = 'a';
        $row_upd = new stdClass();
        $place_holder = 'b';
        $value_vacio = false;

        $resultado = $html->input_monto_required($disabled, $name, $place_holder, $row_upd, $value_vacio,false );
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='text' name='a' value='' |class|  required id='a' placeholder='b' />", $resultado);

        errores::$error = false;
    }

    #[NoReturn] public function test_input_radio_doble(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $checked_default = 1;
        $campo = 'a';
        $tag = '';
        $val_1 = '';
        $val_2 = '';


        $resultado = $html->input_radio_doble($campo, $checked_default, $tag, $val_1, $val_2);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<label class='control-label' for='A'>A</label>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_input_telefono(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $name = 'a';
        $place_holder = 'b';

        $row_upd = new stdClass();
        $value_vacio = false;

        $resultado = $html->input_telefono($disabled, $name, $place_holder, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='text' name='a' value='' class='form-control' required id='a' placeholder='b' pattern='[1-9]{1}[0-9]{9}' /></div>", $resultado);
        errores::$error = false;

    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_input_text(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
       // $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $disable = false;
        $required = false;
        $value_vacio = false;
        $place_holder = 'b';
        $row_upd = new stdClass();


        $resultado = $html->input_text($disable, $name, $place_holder, $required, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='text' name='a' value='' |class| id='a' placeholder='b' title='b' /></div>", $resultado);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_input_text_required(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $row_upd = new stdClass();
        $disable = false;
        $value_vacio = true;
        $name = 'z';
        $place_holder = 'd';

        $resultado = $html->input_text_required($disable, $name, $place_holder, $row_upd, $value_vacio);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|><input type='text' name='z' value='' |class| required id='z' placeholder='d' title='d' /></div>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_label_init(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $for = 'a';
        $label_html = '';


        $resultado = $html->label_init($for, $label_html);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado->label_html);
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_label_input(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $place_holder = 'c';


        $resultado = $html->label_input($name, $place_holder);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_label_input_radio(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $checked = '';
        $class_label_html = '';
        $class_radio_html = '';
        $ids_html = '';
        $name = 'a';
        $title = '';
        $val = '';


        $resultado = $html->label_input_radio($checked, $class_label_html, $class_radio_html, $ids_html, $name, $title, $val);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<input type='radio' name='a' value=''", $resultado);
        $this->assertStringContainsStringIgnoringCase("title='A'", $resultado);
        $this->assertStringContainsStringIgnoringCase(" title='a'", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_label_radio(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $for = 'a';
        $label_html = '';

        $resultado = $html->label_radio($for, $label_html);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<label class='control-label' for='a'>a</label>", $resultado);

        errores::$error = false;
        $for = '';
        $label_html = 'b';

        $resultado = $html->label_radio($for, $label_html);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<label class='control-label' for='b'>b</label>", $resultado);

        errores::$error = false;
        $for = 'a';
        $label_html = 'b';

        $resultado = $html->label_radio($for, $label_html);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<label class='control-label' for='a'>b</label>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_labels_radios(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $params = new stdClass();
        $title = '';
        $val_1 = '';
        $val_2 = '';

        $params->checked_default =  new stdClass();
        $params->checked_default->checked_default_v1 =  '';
        $params->checked_default->checked_default_v2 =  '';
        $params->class_label_html = '';
        $params->class_radio_html = '';
        $params->ids_html = '';

        $resultado = $html->labels_radios($name, $params, $title, $val_1, $val_2);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<input type='radio' name='a' value=''", $resultado->label_input_v1);
        $this->assertStringContainsStringIgnoringCase("title='A'", $resultado->label_input_v1);
        $this->assertStringContainsStringIgnoringCase("<input type='radio' name='a' value=''", $resultado->label_input_v2);
        $this->assertStringContainsStringIgnoringCase("<label >", $resultado->label_input_v2);
        errores::$error = false;
    }

    #[NoReturn] public function test_mensaje_exito(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $mensaje_exito = '';

        $resultado = $html->mensaje_exito($mensaje_exito);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);
        errores::$error = false;

        $mensaje_exito = 'a';

        $resultado = $html->mensaje_exito($mensaje_exito);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='alert alert-success' role='alert'><strong>Muy bien!</strong> a.</div>", $resultado);
        errores::$error = false;
    }

    public function test_mensaje_warning(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $mensaje_exito = '';

        $resultado = $html->mensaje_warning($mensaje_exito);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);
        errores::$error = false;

        $mensaje_exito = 'a';

        $resultado = $html->mensaje_warning($mensaje_exito);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='alert alert-warning' role='alert'><strong>Advertencia!</strong> a.</div>", $resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_params_html(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $checked_default = 1;
        $class_label = array();
        $class_radio = array();
        $ids_css = array();
        $label_html = '';
        $for = 'a';

        $resultado = $html->params_html($checked_default, $class_label, $class_radio, $ids_css, $label_html, $for);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<label class='control-label' for='a'>a</label>", $resultado->label_html);
        $this->assertEquals("class='form-check-label'", $resultado->class_label_html);
        $this->assertEquals("class='form-check-input'", $resultado->class_radio_html);
        $this->assertEquals("", $resultado->ids_html);
        errores::$error = false;
    }

    #[NoReturn] public function test_radio_doble(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $checked_default = 1;
        $class_label = array();
        $class_radio = array();
        $cols = 1;
        $for = 'a';
        $ids_css = array();
        $label_html = '';
        $name = 'b';
        $title = '';
        $val_1 = '';
        $val_2 = '';

        $resultado = $html->radio_doble($checked_default, $class_label, $class_radio, $cols, $for, $ids_css,
            $label_html, $name, $title, $val_1, $val_2);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("title='B' checked>", $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_row_upd_name(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $value_vacio = false;


        $resultado = $html->row_upd_name($name, $value_vacio);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado->a);
        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_valida_btn_next(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $label = '';
        $value = '';
        $style = '';
        $type = '';


        $resultado = $html->valida_btn_next($label, $style, $type, $value);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar datos', $resultado['mensaje']);

        errores::$error = false;

        $label = 'a';
        $value = '';
        $style = '';
        $type = '';


        $resultado = $html->valida_btn_next($label, $style, $type, $value);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar datos', $resultado['mensaje']);

        errores::$error = false;

        $label = 'a';
        $value = 'a';
        $style = '';
        $type = '';


        $resultado = $html->valida_btn_next($label, $style, $type, $value);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $style esta vacio', $resultado['mensaje']);

        errores::$error = false;

        $label = 'a';
        $value = 'a';
        $style = 's';
        $type = '';


        $resultado = $html->valida_btn_next($label, $style, $type, $value);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $type esta vacio', $resultado['mensaje']);

        errores::$error = false;

        $label = 'a';
        $value = 'a';
        $style = 's';
        $type = 's';


        $resultado = $html->valida_btn_next($label, $style, $type, $value);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_valida_cols(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $cols = -1;


        $resultado = $html->valida_cols($cols);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error cols debe ser mayor a 0', $resultado['mensaje']);

        errores::$error = false;

        $cols = 13;


        $resultado = $html->valida_cols($cols);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error cols debe ser menor o igual a 12', $resultado['mensaje']);

        errores::$error = false;

        $cols = 1;


        $resultado = $html->valida_cols($cols);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;
    }

    #[NoReturn] public function test_valida_data_base(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        //$html = new liberator($html);
        //$_GET['session_id'] = 1;

        $label = 'a';
        $value = 'v';


        $resultado = $html->valida_data_base($label, $value);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_valida_data_label(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;
        $name = '';
        $place_holder = '';


        $resultado = $html->valida_data_label($name, $place_holder);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $name debe tener info', $resultado['mensaje']);
        errores::$error = false;

        $_GET['session_id'] = 1;
        $name = 'a';
        $place_holder = '';


        $resultado = $html->valida_data_label($name, $place_holder);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $place_holder debe tener info', $resultado['mensaje']);

        errores::$error = false;

        $_GET['session_id'] = 1;
        $name = 'a';
        $place_holder = 'c';


        $resultado = $html->valida_data_label($name, $place_holder);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_valida_etiquetas(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $place_holder = 'f';


        $resultado = $html->valida_etiquetas($name, $place_holder);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;
    }

    #[NoReturn] public function test_value_input(): void
    {
        errores::$error = false;
        $html_ = new html();
        $html = new directivas($html_);
        $html = new liberator($html);
        $_GET['session_id'] = 1;


        $init = new stdClass();
        $name = 'a';
        $value = 'x';

        $init->row_upd = new stdClass();

        $resultado = $html->value_input($init, $name, $value);
        $this->assertIsstring($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('x',$resultado);

        errores::$error = false;

    }


}

