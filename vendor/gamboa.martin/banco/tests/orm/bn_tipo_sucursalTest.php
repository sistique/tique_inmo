<?php
namespace tests\controllers;

use gamboamartin\banco\models\bn_tipo_sucursal;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;
use tests\base_test;


class bn_tipo_sucursalTest extends test {
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


    public function test_alta_registro(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new bn_tipo_sucursal($this->link);

        $del = (new \gamboamartin\banco\tests\base_test())->del_bn_tipo_sucursal($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $registro = array();
        $registro['codigo'] = 'a';
        $registro['descripcion'] = 'a';
        $registro['descripcion_select'] = 'a';
        $registro['alias'] = 'a';
        $registro['codigo_bis'] = 'a';
        $resultado = $modelo->alta_registro($registro);


        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;


        /**
         * Asegurar que le verificacion de duplicidad no se vaya hast ala base de datos
         */
        $registro = array();
        $registro['codigo'] = 'a';
        $registro['descripcion'] = 'd';
        $registro['descripcion_select'] = 'd';
        $registro['alias'] = 'd';
        $registro['codigo_bis'] = 'd';
        $resultado = $modelo->alta_registro($registro);


        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error al dar de alta registro en database banco  en modelo bn_tipo_sucursal', $resultado['mensaje_limpio']);
        $this->assertEquals('Error al insertar', $resultado['data']['mensaje_limpio']);
        $this->assertEquals('Error al validar alta', $resultado['data']['data']['mensaje_limpio']);
        $this->assertEquals('Error al verificar duplicado', $resultado['data']['data']['data']['mensaje_limpio']);
        $this->assertEquals('Error al verificar duplicado', $resultado['data']['data']['data']['data']['mensaje_limpio']);
        errores::$error = false;
    }





}

