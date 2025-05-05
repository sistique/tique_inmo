<?php
namespace tests\controllers;

use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\banco\controllers\controlador_adm_session;
use gamboamartin\banco\controllers\controlador_bn_tipo_cuenta;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;


class controlador_bn_tipo_cuentaTest extends test {
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


    public function test_key_selects_txt(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'bn_tipo_cuenta';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $del = (new adm_seccion(link: $this->link))->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $adm_seccion['id'] = '2';
        $adm_seccion['descripcion'] = 'bn_tipo_cuenta';
        $adm_seccion['adm_menu_id'] = '1';
        $adm_seccion['adm_namespace_id'] = '1';
        $alta = (new adm_seccion(link: $this->link))->alta_registro(registro: $adm_seccion);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }


        $controler = new controlador_bn_tipo_cuenta(link: $this->link,paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $keys_selects = array();
        $resultado = $controler->key_selects_txt($keys_selects);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(6,$resultado['codigo']->cols);
        $this->assertEquals('Cod',$resultado['codigo']->place_holder);
        $this->assertEquals(true,$resultado['codigo']->required);

        $this->assertEquals(6,$resultado['descripcion']->cols);
        $this->assertEquals('Tipo Cuenta',$resultado['descripcion']->place_holder);
        $this->assertEquals(true,$resultado['descripcion']->required);

        errores::$error = false;
    }





}

