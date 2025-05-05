<?php
namespace gamboamartin\cat_sat\tests\controllers;

use gamboamartin\cat_sat\controllers\controlador_cat_sat_actividad_economica;
use gamboamartin\cat_sat\controllers\controlador_cat_sat_metodo_pago;
use gamboamartin\cat_sat\instalacion\instalacion;
use gamboamartin\cat_sat\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;


class controlador_cat_sat_actividad_economicaTest extends test {
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
    public function test_init_datatable(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_actividad_economica';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $instala = (new instalacion(link: $this->link))->instala(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al instalar',data:  $instala);
            print_r($error);
            exit;
        }

        $controler = new controlador_cat_sat_actividad_economica(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $resultado = $controler->init_datatable();


        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('Id', $resultado->columns['cat_sat_actividad_economica_id']['titulo']);



        errores::$error = false;
    }







}

