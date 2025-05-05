<?php
namespace tests\base\frontend;

use base\frontend\params_inputs;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;


class params_inputsTest extends test {
    public errores $errores;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    public function test_class_html()
    {
        errores::$error = false;
        $params = new params_inputs();
        //$params = new liberator($params);

        $class_css = array();
        $resultado = $params->class_html($class_css);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('',$resultado);

        errores::$error = false;
        $class_css = array();
        $class_css[] = ' a';
        $resultado = $params->class_html($class_css);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("class='a'",$resultado);
        errores::$error = false;
    }


    public function test_disabled_html()
    {
        errores::$error = false;
        $params = new params_inputs();
        //$params = new liberator($params);
        $disabled = false;

        $resultado = $params->disabled_html($disabled);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('',$resultado);

        errores::$error = false;

        $disabled = true;

        $resultado = $params->disabled_html($disabled);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('disabled',$resultado);

        errores::$error = false;
    }

    public function test_ids_html()
    {
        errores::$error = false;
        $params = new params_inputs();
        //$params = new liberator($params);
        $ids_css = array();
        $ids_css[] = 'x';
        $ids_css[] = 'y';

        $resultado = $params->ids_html($ids_css);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("id='x  y'",$resultado);


        errores::$error = false;
    }

    public function test_multiple_html()
    {
        errores::$error = false;
        $params = new params_inputs();
        //$params = new liberator($params);
        $multiple = false;

        $resultado = $params->multiple_html($multiple);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('',$resultado);

        errores::$error = false;

        $multiple = true;

        $resultado = $params->multiple_html($multiple);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('multiple',$resultado);


        errores::$error = false;
    }

    public function test_params_base_chk()
    {
        errores::$error = false;
        $params = new params_inputs();
        //$params = new liberator($params);
        $campo = '_a_b';
        $tag = '';

        $resultado = $params->params_base_chk($campo, $tag);
        $this->assertIsObject( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('form-check-label', $resultado->class_label[0]);
        $this->assertEquals('chk', $resultado->class_label[1]);
        $this->assertEquals('form-check-input', $resultado->class_radio[0]);
        $this->assertEquals('_a_b', $resultado->class_radio[1]);
        $this->assertEquals('A B', $resultado->for);
        errores::$error = false;
    }

    public function test_regex_html()
    {
        errores::$error = false;
        $params = new params_inputs();
        //$params = new liberator($params);
        $regex = true;

        $resultado = $params->regex_html($regex);

        $this->assertIsString( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("pattern='1'", $resultado);

        errores::$error = false;
    }


    public function test_required_html(): void
    {
        errores::$error = false;
        $params = new params_inputs();
        //$params = new liberator($params);

        $resultado = $params->required_html(required: false);
        $this->assertIsString( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);

        errores::$error = false;

        $resultado = $params->required_html(required: true);
        $this->assertIsString( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('required', $resultado);
        errores::$error = false;

    }

    public function test_title_html(): void
    {
        errores::$error = false;
        $params = new params_inputs();
        //$params = new liberator($params);

        $place_holder = 'x';
        $title = '';
        $resultado = $params->title_html($place_holder,$title);

        $this->assertIsString( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("title='x'", $resultado);


        errores::$error = false;

    }

    
}