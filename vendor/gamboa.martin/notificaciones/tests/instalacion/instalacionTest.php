<?php
namespace gamboamartin\notificaciones\tests\orm;


use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\facturacion\tests\base_test;
use gamboamartin\facturacion\tests\base_test2;
use gamboamartin\notificaciones\controllers\controlador_not_adjunto;
use gamboamartin\notificaciones\instalacion\instalacion;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use gamboamartin\facturacion\models\fc_factura;


use stdClass;


class instalacionTest extends test {
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

    public function test__add_not_tipo_medio(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'not_adjunto';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $ins = new instalacion();
        $ins = new liberator($ins);

        $resultado = $ins->_add_not_tipo_medio(link: $this->link);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $resultado = (new _instalacion(link: $this->link))->describe_table(table: 'not_tipo_medio');
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('id',$resultado->registros[0]['Field']);
        $this->assertEquals('codigo',$resultado->registros[1]['Field']);
        $this->assertEquals('codigo_bis',$resultado->registros[10]['Field']);
        errores::$error = false;

    }






}

