<?php
namespace tests\controllers;


use gamboamartin\errores\errores;

use gamboamartin\gastos\controllers\controlador_gt_tipo_solicitud;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use html\fc_csd_html;

use stdClass;


class controlador_gt_tipo_solicitudTest extends test {
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

    public function test_key_selects_txt(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $ctl = new controlador_gt_tipo_solicitud(link: $this->link, paths_conf: $this->paths_conf);
        $ctl = new liberator($ctl);

        $keys_selects = array();
        $resultado = $ctl->key_selects_txt($keys_selects);


        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(6, $resultado['codigo']->cols);
        $this->assertEquals('Cod', $resultado['codigo']->place_holder);
        $this->assertTrue( $resultado['codigo']->required);

        $this->assertEquals(6, $resultado['descripcion']->cols);
        $this->assertEquals('Tipo de Solicitud', $resultado['descripcion']->place_holder);
        $this->assertTrue( $resultado['descripcion']->required);

        errores::$error = false;


    }


}

