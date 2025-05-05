<?php
namespace gamboamartin\organigrama\tests\orm;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\test\test;
use stdClass;
use tests\base_test;


class org_empresaTest extends test {
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

        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;



        $del = (new \gamboamartin\organigrama\tests\base_test())->del_dp_calle_pertenece(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $modelo = new org_empresa(link: $this->link);
        //$lim = new liberator($lim);

        $modelo->registro['org_tipo_empresa_id'] = 1;
        $modelo->registro['rfc'] = 'AAA010101AAA';
        $modelo->registro['razon_social'] = 1;
        $modelo->registro['nombre_comercial'] = 1;
        $modelo->registro['dp_calle_pertenece_id'] = 1;
        $modelo->registro['cat_sat_regimen_fiscal_id'] = 1;
        $modelo->registro['cat_sat_tipo_persona_id'] = 1;

        $resultado = $modelo->alta_bd();

        $this->assertTrue(errores::$error);
        $this->assertEquals('Error al verificar si existe configuracion de regimen',$resultado['mensaje_limpio']);

        errores::$error = false;



        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_dp_calle_pertenece(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $modelo->registro['cat_sat_regimen_fiscal_id'] = 601;
        $modelo->registro['cat_sat_tipo_persona_id'] = 4;

        $resultado = $modelo->alta_bd();
        //print_r($resultado);exit;
        $this->assertNotTrue(errores::$error);
        $this->assertIsObject($resultado);



        errores::$error = false;

    }

    public function test_modifica_bd(): void
    {
        errores::$error = false;

        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;


        $del = (new \gamboamartin\organigrama\tests\base_test())->del_dp_calle_pertenece(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }


        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_org_empresa(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al alta', $alta);
            print_r($error);
            exit;
        }



        $modelo = new org_empresa(link: $this->link);
        //$lim = new liberator($lim);

        $registro['org_tipo_empresa_id'] = 1;
        $registro['rfc'] = 'AAA010101AAA';
        $registro['razon_social'] = 1;
        $registro['nombre_comercial'] = 1;
        $registro['dp_calle_pertenece_id'] = 1;
        $registro['cat_sat_regimen_fiscal_id'] = 601;
        $registro['cat_sat_tipo_persona_id'] = 4;

        $resultado = $modelo->modifica_bd(registro: $registro, id: 1);
       // print_r($resultado);exit;
        $this->assertNotTrue(errores::$error);
        $this->assertIsObject($resultado);



        errores::$error = false;

    }




}

