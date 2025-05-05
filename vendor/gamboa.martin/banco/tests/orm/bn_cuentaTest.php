<?php
namespace tests\controllers;

use gamboamartin\banco\models\bn_cuenta;
use gamboamartin\banco\models\bn_tipo_sucursal;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;
use tests\base_test;


class bn_cuentaTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/banco/config/generales.php';
        $this->paths_conf->database = '/var/www/html/banco/config/database.php';
        $this->paths_conf->views = '/var/www/html/banco/config/views.php';
    }


    public function test_elimina_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new bn_cuenta($this->link);

        $del = (new \gamboamartin\banco\tests\base_test())->del_bn_tipo_banco($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $del = (new \gamboamartin\banco\tests\base_test())->del_bn_tipo_cuenta($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $del = (new \gamboamartin\banco\tests\base_test())->del_org_puesto($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $resultado = $modelo->elimina_bd(1);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        errores::$error = false;

        $alta = (new \gamboamartin\banco\tests\base_test())->alta_bn_cuenta($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }


        errores::$error = false;

        $resultado = $modelo->elimina_bd(1);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(1,$resultado->registro['bn_cuenta_id']);

       
        errores::$error = false;
    }





}

