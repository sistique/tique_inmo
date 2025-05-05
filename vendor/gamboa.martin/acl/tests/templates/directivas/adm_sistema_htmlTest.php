<?php
namespace tests\controllers;


use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use html\adm_seccion_html;


use html\adm_sistema_html;
use stdClass;


class adm_sistema_htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/cat_sat/config/generales.php';
        $this->paths_conf->database = '/var/www/html/cat_sat/config/database.php';
        $this->paths_conf->views = '/var/www/html/cat_sat/config/views.php';
    }

    public function test_select_adm_sistema_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $html = new html();
        $html = new adm_sistema_html($html);
        //$html = new liberator($html);


        $cols = 1;
        $con_registros = true;
        $id_selected = -1;
        $link = $this->link;
        $resultado = $html->select_adm_sistema_id($cols, $con_registros, $id_selected, $link);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("ol-label' for='adm_sistema_id'>Sistema",$resultado);
        errores::$error = false;
    }

    public function test_select_adm_seccion_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $html = new html();
        $html = new adm_seccion_html($html);
        $cols = 1;
        $con_registros = true;
        $id_selected = null;
        $link = $this->link;
        $resultado = $html->select_adm_seccion_id($cols, $con_registros, $id_selected, $link);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("ion_id'>Seccion</label><div class='controls'><select class='form-contr", $resultado);
        errores::$error = false;
    }


    public function test_selects_alta(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $html = new html();
        $html = new adm_seccion_html($html);
        $html = new liberator($html);

        $keys_selects = array();
        $link = $this->link;
        $resultado = $html->selects_alta($keys_selects, $link);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("lass='control-group col-sm-12'><label class='control-label' for='adm_menu_id'>Menu</", $resultado->adm_menu_id);

        errores::$error = false;
    }


}

