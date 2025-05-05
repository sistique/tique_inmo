<?php
namespace tests\controllers;


use gamboamartin\errores\errores;
use gamboamartin\proceso\html\pr_entidad_html;

use gamboamartin\test\liberator;
use gamboamartin\test\test;

use stdClass;


class pr_entidad_htmlTest extends test {
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

    public function test_selects_alta(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $html_ = new \gamboamartin\template_1\html();
        $html = new pr_entidad_html($html_);
        $html = new liberator($html);

        $keys_selects = array();
        $link = $this->link;
        $resultado = $html->selects_alta($keys_selects, $link);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;


    }


}

