<?php
namespace tests\controllers;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\_base;
use gamboamartin\organigrama\controllers\controlador_adm_session;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use stdClass;

class _baseTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/organigrama/config/generales.php';
        $this->paths_conf->database = '/var/www/html/organigrama/config/database.php';
        $this->paths_conf->views = '/var/www/html/organigrama/config/views.php';


    }

    public function test_data_retorno(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'ubicacion';

        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';
        $base = new _base();
        //$base = new liberator($base);


        $resultado = $base->data_retorno(tabla: '1');
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(-1,$resultado->id_retorno);
        $this->assertEquals('1',$resultado->seccion_retorno);

        errores::$error = false;

    }

    public function test_id_retorno_init(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'ubicacion';

        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';
        $base = new _base();
        $base = new liberator($base);


        $resultado = $base->id_retorno_init();
        $this->assertIsInt($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(-1,$resultado);

        errores::$error = false;

        $_POST['id_retorno'] = 10;
        $resultado = $base->id_retorno_init();
        $this->assertIsInt($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(10,$resultado);

        errores::$error = false;
    }

    /**
     */
    public function test_seccion_retorno(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'ubicacion';

        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';
        $base = new _base();
        $base = new liberator($base);

        $tabla = 'a';
        $resultado = $base->seccion_retorno($tabla);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado);

        errores::$error = false;

        $_POST['seccion_retorno'] = 'Z';
        $tabla = 'a';
        $resultado = $base->seccion_retorno($tabla);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('Z',$resultado);

        errores::$error = false;
    }


}

