<?php
namespace tests\links\secciones;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\html\org_sucursal_html;
use gamboamartin\template_1\html;

use gamboamartin\test\test;

use stdClass;


class org_sucursal_htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    /**
     */
    public function test_select_org_sucursal_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $html_ = new html();
        $html = new org_sucursal_html($html_);
        //$html = new liberator($html);

        $cols = 1;
        $con_registros = true;
        $id_selected = -1;
        $link= $this->link;

        $resultado = $html->select_org_sucursal_id($cols, $con_registros, $id_selected, $link);
        //print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-1'><label class='control-label' for='org_sucursal_id'>Sucursal</", $resultado);
        $this->assertStringContainsStringIgnoringCase("<div class='controls'><select class='form-control selectpicker color-secondary ", $resultado);
        $this->assertStringContainsStringIgnoringCase("data-live-search='true' id='org_sucursal_id' name='org_sucursal_id'", $resultado);
        $this->assertStringContainsStringIgnoringCase("required ><option value=''  >Selecciona una opcion", $resultado);

        errores::$error = false;
    }



}

