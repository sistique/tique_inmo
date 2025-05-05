<?php
namespace gamboamartin\direccion_postal\tests\orm;


use gamboamartin\direccion_postal\models\dp_colonia;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;



class dp_coloniaTest extends test {
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

    public function test_get_colonia(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $_SESSION['usuario_id'] = 1;
        $modelo = new dp_colonia($this->link);

        $dp_colonia_id = 1;
        $resultado = $modelo->get_colonia($dp_colonia_id);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(1,$resultado['dp_colonia_id']);

        errores::$error = false;


    }


}

