<?php
namespace gamboamartin\notificaciones\tests\orm;


use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\facturacion\tests\base_test;
use gamboamartin\facturacion\tests\base_test2;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use gamboamartin\facturacion\models\fc_factura;


use stdClass;


class not_receptorTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/notificaciones/config/generales.php';
        $this->paths_conf->database = '/var/www/html/notificaciones/config/database.php';
        $this->paths_conf->views = '/var/www/html/notificaciones/config/views.php';
    }

    public function test_alta_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $modelo = new not_receptor($this->link);
        //$modelo = new liberator($modelo);



        $registro['email'] = '';
        $modelo->registro = $registro;

        $resultado = $modelo->alta_bd();
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error al validar registro',$resultado['mensaje_limpio']);

        errores::$error = false;

        $modelo = new not_receptor($this->link);
        //$modelo = new liberator($modelo);



        $registro['email'] = 'a@mail.co.mx';
        $modelo->registro = $registro;

        $resultado = $modelo->alta_bd();
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }






}

