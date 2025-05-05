<?php
namespace tests\controllers;


use gamboamartin\acl\controllers\controlador_adm_seccion;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\errores\errores;
use gamboamartin\test\test;

use stdClass;


class controlador_adm_seccion_Test extends test {
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

    public function test_alta_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_GET['registro_id'] = 1;
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';



        $del = (new adm_seccion($this->link))->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $seccion_ins = array();
        $seccion_ins['id'] = 2;
        $seccion_ins['adm_menu_id'] = 1;
        $seccion_ins['descripcion'] = 'adm_menu';
        $seccion_ins['adm_namespace_id'] = '1';
        $r_alta = (new adm_seccion(link: $this->link))->alta_registro($seccion_ins);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $r_alta);
            print_r($error);
            exit;
        }

        $controler = new controlador_adm_seccion(link: $this->link, paths_conf: $this->paths_conf);
        //$controler = new liberator($controler);

        $_POST = array();
        $_POST['descripcion'] = 'a';
        $_POST['adm_menu_id'] = 1;
        $_POST['adm_namespace_id'] = 1;
        $resultado = $controler->alta_bd(header: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;

    }


}

