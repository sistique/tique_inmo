<?php
namespace gamboamartin\cat_sat\tests\orm;

use gamboamartin\cat_sat\models\cat_sat_conf_reg_tp;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;


class cat_sat_conf_reg_tpTest extends test {
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
    public function test_datos_base_alta(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_metodo_pago';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $modelo = new cat_sat_conf_reg_tp(link: $this->link);
        $modelo = new liberator($modelo);




        $registro = array();
        $registro['cat_sat_regimen_fiscal_id'] = 601;
        $registro['cat_sat_tipo_persona_id'] = 4;
        $resultado = $modelo->datos_base_alta($registro);
 
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(601,$resultado->cat_sat_regimen_fiscal['cat_sat_regimen_fiscal_id']);
        $this->assertEquals(4,$resultado->cat_sat_tipo_persona['cat_sat_tipo_persona_id']);

        errores::$error = false;


    }







}

