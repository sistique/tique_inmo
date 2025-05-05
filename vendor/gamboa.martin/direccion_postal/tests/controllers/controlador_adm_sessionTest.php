<?php
namespace gamboamartin\direccion_postal\tests\controllers;

use gamboamartin\administrador\instalacion\instalacion;
use gamboamartin\direccion_postal\controllers\controlador_adm_session;
use gamboamartin\direccion_postal\tests\base_test;
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
        $this->paths_conf->generales = '/var/www/html/direccion_postal/config/generales.php';
        $this->paths_conf->database = '/var/www/html/direccion_postal/config/database.php';
        $this->paths_conf->views = '/var/www/html/direccion_postal/config/views.php';
    }

    /**
     */
    public function test_denegado(): void
    {
        errores::$error = false;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'adm_session';
        $_GET['accion'] = 'login';

        $instala = (new instalacion())->instala(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al instala',data:  $instala);
            print_r($error);
            exit;
        }


        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al del',data:  $del);
            print_r($error);
            exit;
        }

        $adm_accion_id = mt_rand(10000000,99999999);
        $adm_seccion_id = mt_rand(10000000,99999999);
        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'adm_session',
            adm_seccion_id: $adm_seccion_id, descripcion: 'login', id: $adm_accion_id);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_adm_session(link: $this->link,paths_conf: $this->paths_conf);

        $_GET['dp_calle_id'] = 1;
        $resultado = $ctl->denegado(header: false,ws: false);


        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }







}

