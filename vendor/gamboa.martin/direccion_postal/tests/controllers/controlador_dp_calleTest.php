<?php
namespace gamboamartin\direccion_postal\tests\controllers;


use gamboamartin\direccion_postal\controllers\controlador_dp_calle;
use gamboamartin\direccion_postal\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;


class controlador_dp_calleTest extends test {
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
    public function test_get_calle(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_GET['accion'] = 'get_calle';
        $_SESSION['grupo_id'] = '2';
        $_SESSION['usuario_id'] = '2';

        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al del',data:  $del);
            print_r($error);
            exit;
        }

        $adm_accion_id = mt_rand(10000000,99999999);
        $adm_seccion_id = mt_rand(10000000,99999999);
        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'dp_calle',
            adm_seccion_id: $adm_seccion_id, descripcion: 'get_calle', id: $adm_accion_id);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_dp_calle(link: $this->link,paths_conf: $this->paths_conf);

        $_GET['dp_calle_id'] = 1;
        $resultado = $ctl->get_calle(header: false,ws: false);


        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

    public function test_inicializa_priedades(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_GET['accion'] = 'get_calle';
        $_SESSION['grupo_id'] = '2';
        $_SESSION['usuario_id'] = '2';

        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al del',data:  $del);
            print_r($error);
            exit;
        }

        $adm_accion_id = mt_rand(10000000,99999999);
        $adm_seccion_id = mt_rand(10000000,99999999);
        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'dp_calle',
            adm_seccion_id: $adm_seccion_id, descripcion: 'get_calle', id: $adm_accion_id);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_dp_calle(link: $this->link,paths_conf: $this->paths_conf);


        $resultado = $ctl->inicializa_priedades();


        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("Código",$resultado['codigo']->place_holder);
        $this->assertEquals(4,$resultado['codigo']->cols);

        $this->assertEquals("Calle",$resultado['descripcion']->place_holder);
        $this->assertEquals(12,$resultado['descripcion']->cols);

        errores::$error = false;
    }







}

