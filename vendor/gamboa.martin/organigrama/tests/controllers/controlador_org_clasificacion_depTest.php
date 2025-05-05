<?php
namespace gamboamartin\organigrama\tests\controllers;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_clasificacion_dep;
use gamboamartin\organigrama\controllers\controlador_org_empresa;
use gamboamartin\organigrama\tests\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use JsonException;

use stdClass;


class controlador_org_clasificacion_depTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/organigrama/config/generales.php';
        $this->paths_conf->database = '/var/www/html/organigrama/config/database.php';
        $this->paths_conf->views = '/var/www/html/organigrama/config/views.php';


    }

    /**
     */
    public function test_key_selects_txt(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'ubicacion';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';
        $ctl = new controlador_org_clasificacion_dep(link: $this->link, paths_conf: $this->paths_conf);
        $ctl = new liberator($ctl);

        $keys_selects = array();
        $resultado = $ctl->key_selects_txt($keys_selects);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(6,$resultado['codigo']->cols);
        $this->assertEquals('Cod',$resultado['codigo']->place_holder);

        errores::$error = false;
    }



}

