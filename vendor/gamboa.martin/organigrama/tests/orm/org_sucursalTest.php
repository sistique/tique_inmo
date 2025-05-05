<?php
namespace tests\orm;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\test\test;
use stdClass;
use tests\base_test;


class org_sucursalTest extends test {
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
    public function test_alta_bd(): void
    {
        errores::$error = false;

        $modelo = new org_sucursal(link: $this->link);
        //$lim = new liberator($lim);

        $resultado = $modelo->alta_bd();
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar registro',$resultado['mensaje']);

        errores::$error = false;

        $modelo->registro['org_empresa_id'] = 1;

        $resultado = $modelo->alta_bd();
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar registro',$resultado['mensaje']);

        errores::$error = false;
        unset($_SESSION['usuario_id']);

        $modelo->registro['org_empresa_id'] = 1;
        $modelo->registro['codigo'] = 1;

        $resultado = $modelo->alta_bd();
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error SESSION no iniciada',$resultado['mensaje']);

        errores::$error = false;

        $_SESSION['usuario_id'] =  2;

        $del = (new \gamboamartin\organigrama\tests\base_test())->del_org_empresa($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_org_empresa(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }



        $_SESSION['usuario_id'] = 1;
        $modelo->registro['org_empresa_id'] = 1;
        $modelo->registro['codigo'] = 1;

        $resultado = $modelo->alta_bd();

        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al dar de alta empresa',$resultado['mensaje']);

        errores::$error = false;

        $_SESSION['usuario_id'] = 1;
        $modelo->registro['org_empresa_id'] = 1;
        $modelo->registro['codigo'] = mt_rand(100000000,9999999999);
        $modelo->registro['codigo_bis'] = mt_rand(100000000,9999999999);
        $modelo->registro['descripcion'] = mt_rand(100000000,9999999999);




        $resultado = $modelo->alta_bd();
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('activo',$resultado->registro['org_sucursal_status']);

        errores::$error = false;

        $_SESSION['usuario_id'] = 1;
        $modelo->registro['org_empresa_id'] = 1;
        $modelo->registro['codigo'] = mt_rand(100000000,9999999999);
        $modelo->registro['codigo_bis'] = mt_rand(100000000,9999999999);
        $modelo->registro['descripcion'] = mt_rand(100000000,9999999999);
        $modelo->registro['status'] = 'x';


        $resultado = $modelo->alta_bd();
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al dar de alta empresa',$resultado['mensaje']);

        errores::$error = false;

        $_SESSION['usuario_id'] = 1;
        $modelo->registro['org_empresa_id'] = 1;
        $modelo->registro['codigo'] = mt_rand(100000000,9999999999);
        $modelo->registro['codigo_bis'] = mt_rand(100000000,9999999999);
        $modelo->registro['descripcion'] = mt_rand(100000000,9999999999);
        $modelo->registro['status'] = 'inactivo';


        $resultado = $modelo->alta_bd();
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('inactivo',$resultado->registro['org_sucursal_status']);

        errores::$error = false;

    }

    public function test_es_matriz(): void
    {
        $_SESSION['usuario_id'] = 1;
        errores::$error = false;

        $del = (new \gamboamartin\organigrama\tests\base_test())->del_org_empresa(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al del', $del);
            print_r($error);
            exit;
        }

        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_org_sucursal(
            link: $this->link,cat_sat_regimen_fiscal_id: 601,cat_sat_tipo_persona_id: 4,org_tipo_sucursal_id: 2);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $modelo = new org_sucursal(link: $this->link);
        //$lim = new liberator($lim);

        $org_sucursal_id = 1;
        $resultado = $modelo->es_matriz($org_sucursal_id);
        //print_r($resultado);exit;
        $this->assertNotTrue($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

        /**
     */
    public function test_sucursales(): void
    {
        errores::$error = false;



        $modelo = new org_sucursal(link: $this->link);
        //$lim = new liberator($lim);

        $org_empresa_id = 1;
        $resultado = $modelo->sucursales($org_empresa_id);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertObjectHasProperty('registros',$resultado);
        $this->assertObjectHasProperty('n_registros',$resultado);


        errores::$error = false;
    }


}

