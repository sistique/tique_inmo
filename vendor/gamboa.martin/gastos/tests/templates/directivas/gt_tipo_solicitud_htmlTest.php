<?php
namespace tests\controllers;


use gamboamartin\errores\errores;

use gamboamartin\gastos\controllers\controlador_gt_tipo_solicitud;
use gamboamartin\template\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use html\fc_csd_html;

use html\gt_tipo_solicitud_html;
use stdClass;


class gt_tipo_solicitud_htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/gastos/config/generales.php';
        $this->paths_conf->database = '/var/www/html/gastos/config/database.php';
        $this->paths_conf->views = '/var/www/html/gastos/config/views.php';
    }

    public function test_select_gt_tipo_solicitud_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $html_ = new html();
        $html = new gt_tipo_solicitud_html($html_);
       // $ctl = new liberator($ctl);

        $cols = 1;
        $con_registros = true;
        $id_selected = false;
        $link = $this->link;
        $resultado = $html->select_gt_tipo_solicitud_id($cols, $con_registros, $id_selected, $link);


        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("selectpicker color-secondary  gt_tipo_solicitud_id' data-live-search", $resultado);


        errores::$error = false;


    }


}

