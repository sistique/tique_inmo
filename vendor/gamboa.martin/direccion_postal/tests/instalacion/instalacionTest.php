<?php
namespace gamboamartin\direccion_postal\tests\orm;

use gamboamartin\direccion_postal\instalacion\instalacion;
use gamboamartin\direccion_postal\models\_model_base;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\direccion_postal\models\dp_cp;
use gamboamartin\direccion_postal\models\dp_pais;
use gamboamartin\direccion_postal\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;



class instalacionTest extends test {
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

    public function test__add_dp_pais(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $_SESSION['usuario_id'] = 1;
        $instalacion = new instalacion();
        $instalacion = new liberator($instalacion);



        $resultado = $instalacion->_add_dp_pais(link: $this->link);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;




    }


}

