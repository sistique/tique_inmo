<?php
namespace gamboamartin\cat_sat\tests\orm;

use gamboamartin\cat_sat\models\_validacion;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\tests\base;
use gamboamartin\direccion_postal\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;


class _validacionTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/cat_sat/config/generales.php';
        $this->paths_conf->database = '/var/www/html/cat_sat/config/database.php';
        $this->paths_conf->views = '/var/www/html/cat_sat/config/views.php';
    }

    public function test_cat_sat_metodo_pago_codigo(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion = new liberator($_validacion);


        $data = new stdClass();;
        $data->cat_sat = new stdClass();
        $data->cat_sat_metodo_pago = new stdClass();
        $data->cat_sat_metodo_pago->codigo = 'A';
        $resultado = $_validacion->cat_sat_metodo_pago_codigo($data);
        $this->assertIsString($resultado);
        $this->assertEquals('A',$resultado);
        $this->assertNotTrue(errores::$error);
    }

    public function test_data(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion = new liberator($_validacion);


        $cat_sat_forma_pago = array();
        $cat_sat_metodo_pago = array();

        $cat_sat_metodo_pago['codigo'] = 'A';
        $cat_sat_forma_pago['codigo'] = 'B';

        $resultado = $_validacion->data($cat_sat_forma_pago, $cat_sat_metodo_pago);
        $this->assertIsObject($resultado);
        $this->assertIsObject($resultado->cat_sat_forma_pago);
        $this->assertEquals('A',$resultado->cat_sat_metodo_pago->codigo);
        $this->assertEquals('B',$resultado->cat_sat_forma_pago->codigo);
        $this->assertNotTrue(errores::$error);
    }

    public function test_init_codigo_forma_pago(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion = new liberator($_validacion);


        $data = new stdClass();;
        $data->cat_sat_forma_pago = new stdClass();
        $data->cat_sat_forma_pago->codigo = 'A';
        $resultado = $_validacion->init_codigo_forma_pago($data);
        $this->assertIsObject($resultado);
        $this->assertIsObject($resultado->cat_sat_forma_pago);
        $this->assertEquals('A',$resultado->cat_sat_forma_pago->codigo);
        $this->assertNotTrue(errores::$error);
    }

    public function test_init_codigo_metodo_pago(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion = new liberator($_validacion);


        $data = new stdClass();;
        $data->cat_sat_metodo_pago = new stdClass();
        $data->cat_sat_metodo_pago->codigo = 'A';
        $resultado = $_validacion->init_codigo_metodo_pago($data);
        $this->assertIsObject($resultado);
        $this->assertIsObject($resultado->cat_sat_metodo_pago);
        $this->assertEquals('A',$resultado->cat_sat_metodo_pago->codigo);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;
    }

    public function test_get_data(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion = new liberator($_validacion);


        $cat_sat_forma_pago = array();
        $cat_sat_metodo_pago = array();

        $cat_sat_metodo_pago['codigo'] = 'A';
        $cat_sat_forma_pago['codigo'] = 'B';

        $resultado = $_validacion->get_data($cat_sat_forma_pago, $cat_sat_metodo_pago);
        $this->assertIsObject($resultado);
        $this->assertIsObject($resultado->cat_sat_metodo_pago);
        $this->assertIsObject($resultado->cat_sat_forma_pago);
        $this->assertEquals('A',$resultado->cat_sat_metodo_pago->codigo);
        $this->assertEquals('B',$resultado->cat_sat_forma_pago->codigo);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_init_data(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion = new liberator($_validacion);

        $cat_sat_forma_pago = array();
        $cat_sat_metodo_pago = new stdClass();

        $resultado = $_validacion->init_data($cat_sat_forma_pago, $cat_sat_metodo_pago);
        $this->assertIsObject($resultado);
        $this->assertIsObject($resultado->cat_sat_metodo_pago);
        $this->assertIsObject($resultado->cat_sat_forma_pago);
        $this->assertNotTrue(errores::$error);



        errores::$error = false;
    }

    public function test_valida_conf_tipo_persona(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        //$_validacion = new liberator($_validacion);



        $registro = array();
        $registro['cat_sat_regimen_fiscal_id'] = 601;
        $registro['cat_sat_tipo_persona_id'] = 4;

        $resultado = $_validacion->valida_conf_tipo_persona(link: $this->link,registro:  $registro);
        $this->assertTrue($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

    public function test_valida_metodo_pago(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion->metodo_pago_permitido['1'] = array('1');


        $registro = array();
        $registro['cat_sat_metodo_pago_id'] = 2;
        $registro['cat_sat_forma_pago_id'] = 99;
        $registro['cat_sat_metodo_pago_codigo'] = '99';
        $resultado = $_validacion->valida_metodo_pago($this->link, $registro);
        $this->assertTrue($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

    }

    public function test_valida_si_existe(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion->metodo_pago_permitido['a'] = 'x';
        $_validacion = new liberator($_validacion);
        $cat_sat_metodo_pago_codigo = 'a';


        $resultado = $_validacion->valida_si_existe($cat_sat_metodo_pago_codigo);
        $this->assertTrue($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

    public function test_valida_si_existe_en_array(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion->metodo_pago_permitido['z'] = array('p');
        $_validacion = new liberator($_validacion);
        $cat_sat_metodo_pago_codigo = 'z';
        $data = new stdClass();
        $data->cat_sat_forma_pago = new stdClass();
        $data->cat_sat_forma_pago->codigo = 'p';
        $registro = array();


        $resultado = $_validacion->valida_si_existe_en_array($cat_sat_metodo_pago_codigo, $data, $registro);
        $this->assertTrue($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

    /**
     */
    public function test_verifica_forma_pago(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $_validacion = new _validacion();
        $_validacion = new liberator($_validacion);

        $cat_sat_forma_pago = new stdClass();
        $cat_sat_forma_pago->codigo = '99';
        $cat_sat_metodo_pago = new stdClass();
        $cat_sat_metodo_pago->codigo = 'PPD';
        $registro = new stdClass();
        $resultado = $_validacion->verifica_forma_pago($cat_sat_forma_pago, $cat_sat_metodo_pago, $registro);

        $this->assertTrue($resultado);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;
    }


}

