<?php
namespace tests\links\secciones;

use gamboamartin\direccion_postal\controllers\controlador_dp_estado;
use gamboamartin\direccion_postal\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;


class controlador_dp_estadoTest extends test {
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
    public function test_get_estado(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $_GET['accion'] = 'get_estado';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;

        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al del',data:  $del);
            print_r($error);
            exit;
        }

        $adm_accion_id = mt_rand(10000000,99999999);
        $adm_seccion_id = mt_rand(10000000,99999999);
        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'dp_estado',
            adm_seccion_id: $adm_seccion_id, descripcion: 'get_estado', id: $adm_accion_id);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_dp_estado(link: $this->link,paths_conf: $this->paths_conf);

        $_GET['pais_id'] = 1;
        $resultado = $ctl->get_estado(header: false,ws: false);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }







}

