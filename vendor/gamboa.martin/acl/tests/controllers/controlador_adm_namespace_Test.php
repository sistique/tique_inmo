<?php
namespace tests\controllers;


use gamboamartin\acl\controllers\controlador_adm_namespace;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use stdClass;


class controlador_adm_namespace_Test extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/acl/config/generales.php';
        $this->paths_conf->database = '/var/www/html/acl/config/database.php';
        $this->paths_conf->views = '/var/www/html/acl/config/views.php';
    }

    public function test_campos_view(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_GET['registro_id'] = 1;
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';



        $controler = new controlador_adm_namespace(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $_POST = array();
        $_POST['descripcion'] = 'a';
        $_POST['adm_menu_id'] = 1;
        $resultado = $controler->campos_view();
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('inputs',$resultado['codigo']['type']);
        $this->assertEquals('inputs',$resultado['descripcion']['type']);
        $this->assertEquals('inputs',$resultado['name']['type']);
        errores::$error = false;

    }


}

