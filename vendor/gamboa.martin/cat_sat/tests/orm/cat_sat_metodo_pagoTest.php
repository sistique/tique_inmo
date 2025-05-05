<?php
namespace gamboamartin\cat_sat\tests\orm;

use gamboamartin\cat_sat\models\cat_sat_metodo_pago;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;


class cat_sat_metodo_pagoTest extends test {
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
    public function test_existe_predeterminado(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_metodo_pago';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $modelo = new cat_sat_metodo_pago(link: $this->link);



        $resultado = $modelo->existe_predeterminado();

        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
//        $this->assertNotTrue($resultado);

        errores::$error = false;

        $modelo = new cat_sat_moneda(link: $this->link);



        //print_r($resultado);exit;
        $resultado = $modelo->existe_predeterminado();
        //print_r($resultado);exit;

        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        //$this->assertTrue($resultado);
        errores::$error = false;
    }







}

