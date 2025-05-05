<?php
namespace tests\orm;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\tests\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use gamboamartin\organigrama\models\limpieza;
use JsonException;
use stdClass;


class limpiezaTest extends test {
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
     * @throws JsonException
     */
    public function test_asigna_si_existe(): void
    {
        errores::$error = false;

        $lim = new limpieza();
        $lim = new liberator($lim);

        $key = 'a';
        $row_destino = array();
        $row_origen = array();

        $row_origen['a'] = 's';
        $resultado = $lim->asigna_si_existe($key, $row_destino, $row_origen);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('s',$resultado['a']);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    public function test_init_data_base_org_empresa(): void
    {
        errores::$error = false;

        $_SESSION['usuario_id'] = 2;

        $lim = new limpieza();
        //$lim = new liberator($lim);

        $registro = array();
        $registro['razon_social'] = 'a';
        $registro['rfc'] = 'b';

        $del = (new base_test())->del_dp_calle_pertenece(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_dp_calle_pertenece(link: $this->link, predeterminado : 'activo');
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $resultado = $lim->init_data_base_org_empresa($this->link, $registro);

        //print_r($resultado);exit;

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado['razon_social']);
        $this->assertEquals('b',$resultado['rfc']);
        $this->assertEquals('a',$resultado['descripcion']);
        $this->assertEquals('b',$resultado['codigo_bis']);
        $this->assertEquals('a',$resultado['descripcion_select']);
        $this->assertEquals('a',$resultado['alias']);

        errores::$error = false;
    }

    public function test_descripcion_sucursal(): void
    {
        errores::$error = false;

        $lim = new limpieza();
        $lim = new liberator($lim);

        $dp_calle_pertenece = array();
        $org_empresa = array();
        $registro = array();

        $org_empresa['org_empresa_descripcion'] = 'a';
        $dp_calle_pertenece['dp_municipio_descripcion'] = 'b';
        $dp_calle_pertenece['dp_estado_descripcion'] = 'b';
        $dp_calle_pertenece['dp_cp_descripcion'] = 'b';
        $registro['codigo'] = 'c';


        $resultado = $lim->descripcion_sucursal($dp_calle_pertenece, $org_empresa, $registro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a b b b c',$resultado);
        errores::$error = false;
    }

    public function test_init_org_empresa_alta_bd(): void
    {
        errores::$error = false;

        $lim = new limpieza();
        //$lim = new liberator($lim);

        $registro = array();
        $registro['razon_social'] = 'a';
        $registro['rfc'] = 'b';
        $resultado = $lim->init_org_empresa_alta_bd($this->link, $registro);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado['razon_social']);
        $this->assertEquals('b',$resultado['rfc']);
        $this->assertEquals('a',$resultado['descripcion']);
        $this->assertEquals('b',$resultado['codigo_bis']);
        $this->assertEquals('a',$resultado['descripcion_select']);
        $this->assertEquals('a',$resultado['alias']);
        errores::$error = false;
    }

    public function test_limpia_domicilio_con_calle(): void
    {
        errores::$error = false;

        $lim = new limpieza();
        $lim = new liberator($lim);

        $registro = array('dp_pais_id'=>'1');

        $resultado = $lim->limpia_domicilio_con_calle($registro);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEmpty($resultado);
        errores::$error = false;
    }

    public function test_limpia_foraneas_org_empresa(): void
    {
        errores::$error = false;

        $lim = new limpieza();
        $lim = new liberator($lim);

        $registro = array();
        $registro['razon_social'] = 'a';
        $registro['rfc'] = 'b';
        $registro['cat_sat_regimen_fiscal_id'] = 'b';

        $resultado = $lim->limpia_foraneas_org_empresa($registro);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('b',$resultado['cat_sat_regimen_fiscal_id']);

        errores::$error = false;

        $registro = array();
        $registro['razon_social'] = 'a';
        $registro['rfc'] = 'b';
        $registro['cat_sat_regimen_fiscal_id'] = '1';

        $resultado = $lim->limpia_foraneas_org_empresa($registro);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(1,$resultado['cat_sat_regimen_fiscal_id']);

        errores::$error = false;

        $registro = array();
        $registro['razon_social'] = 'a';
        $registro['rfc'] = 'b';
        $registro['cat_sat_regimen_fiscal_id'] = '-1';

        $resultado = $lim->limpia_foraneas_org_empresa($registro);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertArrayNotHasKey('cat_sat_regimen_fiscal_id',$resultado);
        errores::$error = false;

    }








}

