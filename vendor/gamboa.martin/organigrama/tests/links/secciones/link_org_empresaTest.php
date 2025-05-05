<?php
namespace tests\links\secciones;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\links\secciones\link_org_empresa;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use JsonException;
use stdClass;
use tests\base_test;


class link_org_empresaTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    /**
     */
    public function test_link_im_registro_patronal_alta_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $link = new link_org_empresa($this->link,-1);
        //$link = new liberator($link);

        $org_empresa_id = -1;

        $resultado = $link->link_im_registro_patronal_alta_bd($this->link, $org_empresa_id);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);
        errores::$error = false;
    }

    public function test_link_org_departamento_modifica_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $link = new link_org_empresa($this->link,-1);
        //$link = new liberator($link);

        $org_empresa_id = -1;
        $org_departamento_id = -1;
        $resultado = $link->link_org_departamento_modifica_bd($this->link, $org_empresa_id, $org_departamento_id);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('&org_departamento_id=-1', $resultado);
        errores::$error = false;
    }

    /**
     */
    public function test_link_org_sucursal_alta_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $link = new link_org_empresa($this->link,-1);
        //$link = new liberator($link);

        $org_empresa_id = -1;
        $resultado = $link->link_org_sucursal_alta_bd($this->link, $org_empresa_id);
        //print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        //$this->assertEquals('./index.php?seccion=org_empresa&accion=alta_sucursal_bd&registro_id=-1&adm_menu_id=-1&session_id=1', $resultado);
        $this->assertEquals('./index.php?seccion=org_empresa&accion=alta_sucursal_bd&adm_menu_id=-1&session_id=1', $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    public function test_link_org_sucursal_modifica_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $link = new link_org_empresa($this->link,-1);
        //$link = new liberator($link);

        $org_empresa_id = -1;
        $org_sucursal_id = -1;
        $resultado = $link->link_org_sucursal_modifica_bd($this->link, $org_empresa_id, $org_sucursal_id);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('&org_sucursal_id=-1', $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    public function test_org_empresa_alta(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $link = new link_org_empresa($this->link, -1);
        $link = new liberator($link);

        $resultado = $link->org_empresa_alta();


        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('./index.php?seccion=org_empresa&accion=alta', $resultado);


        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    public function test_org_empresa_ubicacion(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $link = new link_org_empresa($this->link, -1);
        $link = new liberator($link);

        $resultado = $link->org_empresa_ubicacion(-1);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('./index.php?seccion=org_empresa&accion=ubicacion&registro_id=-1', $resultado);
        errores::$error = false;
    }







}

