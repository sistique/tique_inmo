<?php
namespace tests\controllers;

use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\test;
use html\cat_sat_regimen_fiscal_html;
use stdClass;


class cat_sat_regimen_fiscal_htmlTest extends test {
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

    /**
     */
    public function test_select_cat_sat_regimen_fiscal_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $html_ = new html();
        $html = new cat_sat_regimen_fiscal_html($html_);
       // $link = new liberator($link);

        $cols = 1;
        $con_registros = false;
        $id_selected = -1;

        $resultado = $html->select_cat_sat_regimen_fiscal_id($cols, $con_registros, $id_selected, $this->link);

        //print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group col-sm-1'><label class='control-label' for='cat_sat_regimen_fiscal_id'>Regimen fiscal</label><div class='controls'><select class='form-control selectpicker color-secondary cat_sat_regimen_fiscal_id ' data-live-search='true' id='cat_sat_regimen_fiscal_id' name='cat_sat_regimen_fiscal_id'  ><option value=''  >Selecciona una opcion</option></select></div></div>", $resultado);
        //$this->assertEquals("<div class='control-group col-sm-1'><label class='control-label' for='cat_sat_regimen_fiscal_id'>Regimen fiscal</label><div class='controls'><select class='form-control selectpicker color-secondary  cat_sat_regimen_fiscal_id' data-live-search='true' id='cat_sat_regimen_fiscal_id' name='cat_sat_regimen_fiscal_id'  ><option value=''  >Selecciona una opcion</option></select></div></div>", $resultado);


        errores::$error = false;
    }







}

