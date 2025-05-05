<?php
namespace tests\links\secciones;

use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\test;
use html\dp_pais_html;
use stdClass;


class dp_pais_htmlTest extends test {
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
    public function test_select_dp_pais_id(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new dp_pais_html($html);

        $cols = 1;
        $con_registros = false;
        $id_selected = -1;
        $resultado = $dir->select_dp_pais_id($cols, $con_registros, $id_selected, $this->link);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-1'><label class='control-label' for='dp_pais_id'>Pais</label><div class=",$resultado);


        errores::$error = false;
        $cols = 1;
        $con_registros = true;
        $id_selected = -1;
        $resultado = $dir->select_dp_pais_id($cols, $con_registros, $id_selected, $this->link);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("control-label' for='dp_pais_id'>Pais</label><div class='controls'><select class='for",$resultado);

        errores::$error = false;
    }







}

