<?php
namespace tests\controllers;

use controllers\controlador_cat_sat_tipo_persona;
use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use html\adm_menu_html;
use html\nom_conf_factura_html;
use JsonException;
use models\em_cuenta_bancaria;
use models\fc_cfd_partida;
use models\fc_factura;
use models\fc_partida;
use models\nom_nomina;
use models\nom_par_deduccion;
use models\nom_par_percepcion;
use stdClass;


class adm_menu_htmlTest extends test {
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


    public function test_select_adm_menu_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $html = new html();
        $html = new adm_menu_html($html);

        //$html = new liberator($html);

        $cols = 1;
        $con_registros = false;
        $id_selected = false;
        $link = $this->link;
        $resultado = $html->select_adm_menu_id($cols, $con_registros, $id_selected, $link);
        //print_r($resultado);exit;

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        //$this->assertEquals("<div class='control-group col-sm-1'><label class='control-label' for='adm_menu_id'>Menu</label><div class='controls'><select class='form-control selectpicker color-secondary  adm_menu_id' data-live-search='true' id='adm_menu_id' name='adm_menu_id'  ><option value=''  >Selecciona una opcion</option></select></div></div>", $resultado);
        $this->assertEquals("<div class='control-group col-sm-1'><label class='control-label' for='adm_menu_id'>Menu</label><div class='controls'><select class='form-control selectpicker color-secondary adm_menu_id ' data-live-search='true' id='adm_menu_id' name='adm_menu_id'  ><option value=''  >Selecciona una opcion</option></select></div></div>", $resultado);

        errores::$error = false;
    }





}

