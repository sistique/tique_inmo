<?php
namespace tests\controllers;

use controllers\controlador_cat_sat_tipo_persona;
use gamboamartin\acl\controllers\_ctl_base\init;
use gamboamartin\acl\controllers\controlador_adm_menu;
use gamboamartin\acl\instalacion\instalacion;
use gamboamartin\administrador\models\adm_menu;
use gamboamartin\errores\errores;

use gamboamartin\test\liberator;
use gamboamartin\test\test;

use stdClass;


class initTest extends test {
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

    public function test_init_data_retornos(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_GET['registro_id'] = 1;
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $del = (new adm_menu(link: $this->link))->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $instala = (new \gamboamartin\administrador\instalacion\instalacion())->instala(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al instalar adm', $instala);
            print_r($error);
            exit;
        }



        $controler = new controlador_adm_menu(link: $this->link, paths_conf: $this->paths_conf);
        $init = (new \gamboamartin\system\_ctl_base\init());
        $init = (new liberator($init));

        $resultado = $init->init_data_retornos($controler);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('adm_menu',$resultado->next_seccion);
        $this->assertEquals('lista',$resultado->next_accion);
        $this->assertEquals('1',$resultado->id_retorno);
        errores::$error = false;
    }


}

