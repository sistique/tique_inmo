<?php
namespace tests\controllers;

use controllers\controlador_cat_sat_tipo_persona;
use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use html\adm_accion_html;
use html\adm_grupo_html;

use stdClass;


class adm_accion_htmlTest extends test {
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


    public function test_input_titulo(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $html = new html();
        $html = new adm_accion_html($html);
        //$html = new liberator($html);

        $cols = 1;
        $value_vacio = false;
        $row_upd = new stdClass();
        $resultado = $html->input_titulo($cols, $row_upd, $value_vacio);


        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-1'><label class='control-label' for='titulo'>Titulo</label><div class='controls'><input type='text' name='titulo' value='' class='form-control' required id='titulo' placeholder='Titulo' title='Titulo' /></div></div>", $resultado);

        errores::$error = false;
    }

    public function test_select_adm_accion_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $html = new html();
        $html = new adm_accion_html($html);
        //$html = new liberator($html);

        $cols = 1;
        $con_registros = false;
        $id_selected = 1;
        $link = $this->link;
        $resultado = $html->select_adm_accion_id($cols, $con_registros, $id_selected, $link);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }





}

