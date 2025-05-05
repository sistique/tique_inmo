<?php
namespace gamboamartin\cat_sat\tests\orm;

use gamboamartin\cat_sat\models\cat_sat_isr;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;


class cat_sat_isrTest extends test {
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

    public function test_activa_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $modelo = new cat_sat_isr(link: $this->link);

        $del = (new base_test())->del_cat_sat_isr(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al del',data: $del,file: __FILE__);
            print_r($error);
            exit;
        }


        $alta = (new base_test())->alta_cat_sat_isr(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar',$alta);
            print_r($error);
            exit;
        }

        $resultado = $modelo->activa_bd(registro_id: 1);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    /**
     */
    public function test_registro(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $modelo = new cat_sat_isr(link: $this->link);


        $resultado = $modelo->registro(1);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $resultado = $modelo->registro(registro_id: 1,retorno_obj: true);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $resultado = $modelo->registro(registro_id: 1, columnas_en_bruto: true, retorno_obj: true);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;
    }



}

