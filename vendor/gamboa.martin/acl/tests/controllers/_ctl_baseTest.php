<?php
namespace tests\controllers;


use gamboamartin\acl\controllers\controlador_adm_menu;
use gamboamartin\acl\controllers\controlador_adm_sistema;
use gamboamartin\errores\errores;

use gamboamartin\test\liberator;
use gamboamartin\test\test;



use stdClass;


class _ctl_baseTest extends test {
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

    public function test_base(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $controler = new controlador_adm_menu(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $resultado = $controler->base();
        $this->assertNotTrue(errores::$error);
        $this->assertIsObject($resultado);
        errores::$error = false;
    }

    public function test_campos_view(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $controler = new controlador_adm_menu(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $resultado = $controler->campos_view();

        $this->assertNotTrue(errores::$error);
        $this->assertIsArray($resultado);

        errores::$error = false;
    }

    public function test_init_alta(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $controler = new controlador_adm_menu(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $resultado = $controler->init_alta();
        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        errores::$error = false;
    }

    public function test_init_data_children(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_GET['registro_id'] = 1;
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $controler = new controlador_adm_menu(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);


        $resultado = $controler->init_data_children();
        $this->assertNotTrue(errores::$error);
        $this->assertIsObject($resultado);
        errores::$error = false;

    }

    public function test_init_modifica(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_GET['registro_id'] = 1;
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $controler = new controlador_adm_menu(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $resultado = $controler->init_modifica();
        $this->assertNotTrue(errores::$error);
        $this->assertIsObject($resultado);
        errores::$error = false;
    }

    public function test_inputs_children(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_GET['registro_id'] = 1;
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $controler = new controlador_adm_sistema(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $registro = new stdClass();
        $registro->adm_sistema_id = 1;
        $resultado = $controler->inputs_children($registro);

        $this->assertNotTrue(errores::$error);
        $this->assertIsObject($resultado);
        $this->assertStringContainsStringIgnoringCase("ol-label' for='adm_menu_id'>Me",$resultado->select->adm_menu_id);
        $this->assertStringContainsStringIgnoringCase("ass='control-label' for='adm_seccion_id'>Seccio",$resultado->select->adm_seccion_id);
        $this->assertStringContainsStringIgnoringCase("l' for='adm_sistema_id'>Sistema</label><div class='control",$resultado->select->adm_sistema_id);
        errores::$error = false;
    }

    public function test_key_select(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_menu';
        $_GET['accion'] = 'lista';
        $_GET['registro_id'] = 1;
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $controler = new controlador_adm_menu(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $cols = 1;
        $con_registros = false;
        $filtro = array();
        $key = 'a';
        $keys_selects = array();
        $id_selected = null;
        $label = '';
        $resultado = $controler->key_select(cols:$cols, con_registros: $con_registros,filtro:  $filtro,key:  $key,
            keys_selects:  $keys_selects,id_selected:  $id_selected,label:  $label);


        $this->assertNotTrue(errores::$error);
        $this->assertIsArray($resultado);
        $this->assertIsObject($resultado['a']);
        errores::$error = false;
    }



}

