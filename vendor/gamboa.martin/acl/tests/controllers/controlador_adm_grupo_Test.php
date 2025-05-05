<?php
namespace tests\controllers;


use gamboamartin\acl\controllers\controlador_adm_grupo;
use gamboamartin\acl\controllers\controlador_adm_seccion;
use gamboamartin\administrador\models\adm_grupo;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\administrador\models\adm_usuario;
use gamboamartin\errores\errores;
use gamboamartin\test\test;

use stdClass;


class controlador_adm_grupo_Test extends test {
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

    public function test_status(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_GET['registro_id'] = 2;
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $del = (new adm_grupo($this->link))->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }


        $adm_grupo_ins = array();
        $adm_grupo_ins['id'] = 2;
        $adm_grupo_ins['descripcion'] = 2;


        $alta = (new adm_grupo($this->link))->alta_registro($adm_grupo_ins);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $adm_usuario_ins = array();
        $adm_usuario_ins['id'] = 2;
        $adm_usuario_ins['user'] = 2;
        $adm_usuario_ins['password'] = 2;
        $adm_usuario_ins['email'] = 'm@hh.com';
        $adm_usuario_ins['adm_grupo_id'] = 2;
        $adm_usuario_ins['telefono'] = 4444444444;
        $adm_usuario_ins['nombre'] = 4444444444;
        $adm_usuario_ins['ap'] = 4444444444;

        $alta = (new adm_usuario($this->link))->alta_registro($adm_usuario_ins);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $controler = new controlador_adm_grupo(link: $this->link, paths_conf: $this->paths_conf);
        //$controler = new liberator($controler);

       // $_POST = array();
       // $_POST['descripcion'] = 'a';
       //_POST['adm_menu_id'] = 1;

        $resultado = $controler->status(false, false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;

    }


}

