<?php
namespace tests\controllers;

use gamboamartin\administrador\instalacion\instalacion;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\banco\controllers\controlador_adm_session;
use gamboamartin\banco\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;


class controlador_adm_sessionTest extends test {
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


    public function test_denegado(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'bn_tipo_sucursal';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $instala = (new instalacion())->instala(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al instala', $instala);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }


        $adm_seccion['id'] = '1';
        $adm_seccion['descripcion'] = 'bn_tipo_sucursal';
        $adm_seccion['adm_menu_id'] = '1';
        $adm_seccion['adm_namespace_id'] = '1';
        $alta = (new adm_seccion(link: $this->link))->alta_registro(registro: $adm_seccion);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $controler = new controlador_adm_session(link: $this->link,paths_conf: $this->paths_conf);

        $resultado = $controler->denegado(header: false);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }





}

