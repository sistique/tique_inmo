<?php
namespace gamboamartin\compresor\tests\src;


use config\generales;
use gamboamartin\compresor\compresor;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;


use stdClass;


class compresorTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/compresor/config/generales.php';
        $this->paths_conf->database = '/var/www/html/compresor/config/database.php';
        $this->paths_conf->views = '/var/www/html/compresor/config/views.php';
    }

    public function test_ruta_origen(): void
    {
        errores::$error = false;

        $compresor = new compresor();
        $compresor = new liberator($compresor);

        $origen = (new generales())->path_base.'tests/files/a';
        if(file_exists($origen)) {
            unlink($origen);
        }

        $resultado = $compresor->ruta_origen($origen);
        $this->assertTrue(errores::$error);
        $this->assertIsArray($resultado);
        $this->assertEquals("Error no existe el archivo",$resultado['mensaje_limpio']);
        errores::$error = false;


        file_put_contents($origen,'a');

        $resultado = $compresor->ruta_origen($origen);
        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertEquals("/var/www/html/compresor/tests/files/a",$resultado);
        errores::$error = false;

        if(file_exists($origen)) {
            unlink($origen);
        }


    }


}

