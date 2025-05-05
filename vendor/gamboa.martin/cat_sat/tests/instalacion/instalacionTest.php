<?php
namespace gamboamartin\cat_sat\tests\instalacion;

use gamboamartin\administrador\models\_instalacion;
use gamboamartin\cat_sat\instalacion\instalacion;
use gamboamartin\cat_sat\models\cat_sat_isn;
use gamboamartin\cat_sat\models\cat_sat_isr;
use gamboamartin\cat_sat\tests\base_test;
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

    public function test__add_cat_sat_forma_pago(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $ins = new instalacion(link: $this->link);
        $ins = new liberator($ins);


        $resultado = $ins->_add_cat_sat_forma_pago(link: $this->link);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $resultado = (new _instalacion(link: $this->link))->describe_table(table: 'cat_sat_forma_pago');
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('id',$resultado->registros[0]['Field']);
        $this->assertEquals('descripcion',$resultado->registros[1]['Field']);
        $this->assertEquals('codigo',$resultado->registros[2]['Field']);
        $this->assertEquals('status',$resultado->registros[3]['Field']);
        $this->assertEquals('usuario_alta_id',$resultado->registros[4]['Field']);
        $this->assertEquals('usuario_update_id',$resultado->registros[5]['Field']);
        $this->assertEquals('fecha_alta',$resultado->registros[6]['Field']);
        $this->assertEquals('fecha_update',$resultado->registros[7]['Field']);
        $this->assertEquals('descripcion_select',$resultado->registros[8]['Field']);
        $this->assertEquals('alias',$resultado->registros[9]['Field']);
        $this->assertEquals('codigo_bis',$resultado->registros[10]['Field']);
        $this->assertEquals('predeterminado',$resultado->registros[11]['Field']);
       errores::$error = false;

    }
    public function test_cat_sat_cve_prod(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';
        $ins = new instalacion(link: $this->link);
        $ins = new liberator($ins);


        $resultado = $ins->cat_sat_cve_prod(link: $this->link);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }
    public function test_instala(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $ins = new instalacion(link: $this->link);


        $resultado = $ins->instala(link: $this->link);
        //print_r($resultado);exit;
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
       // $this->assertEquals(1,$resultado->registro_id);
        errores::$error = false;
    }


}

