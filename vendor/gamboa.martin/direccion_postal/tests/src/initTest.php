<?php
namespace gamboamartin\direccion_postal\tests\orm;

use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\direccion_postal\src\init;
use gamboamartin\direccion_postal\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;



class initTest extends test {
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

    public function test_limpia_data_alta(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $_SESSION['usuario_id'] = 1;

        $init = new init();
        $_POST['dp_municipio_id'] = 1;
        $resultado = $init->limpia_data_alta();

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEmpty($resultado);

        errores::$error = false;


    }




}

