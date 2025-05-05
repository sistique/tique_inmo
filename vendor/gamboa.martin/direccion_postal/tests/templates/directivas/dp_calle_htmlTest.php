<?php
namespace gamboamartin\direccion_postal\tests\templates\directivas;

use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use html\dp_calle_html;
use html\dp_calle_pertenece_html;
use stdClass;


class dp_calle_htmlTest extends test {
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
    public function test_select_dp_calle_id(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new dp_calle_html($html);
       // $dir = new liberator($dir);

        $cols = 1;
        $con_registros = true;
        $id_selected = -1;
        $resultado = $dir->select_dp_calle_id($cols, $con_registros, $id_selected, $this->link);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<option value='1'  >1</option></select>",$resultado);

        errores::$error = false;
    }


}

