<?php
namespace gamboamartin\organigrama\tests\controllers;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_empresa;
use gamboamartin\organigrama\tests\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use JsonException;

use stdClass;


class controlador_org_empresaTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/organigrama/config/generales.php';
        $this->paths_conf->database = '/var/www/html/organigrama/config/database.php';
        $this->paths_conf->views = '/var/www/html/organigrama/config/views.php';


    }

    public function test_alta_sucursal_bd(): void
    {
        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';
        errores::$error = false;

        $del = (new base_test())->del_org_empresa(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }


        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'alta_sucursal_bd';


        $_POST = array();
        $_POST['codigo'] = 2;

        $del = (new base_test())->del_org_tipo_sucursal(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al del', $del);
            print_r($error);
            exit;
        }

        $alta_org_tipo_sucursal = (new base_test())->alta_org_tipo_sucursal(link: $this->link, codigo: 'SUC',
            descripcion: 'SUC', id: 2);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta_org_tipo_sucursal);
            print_r($error);
            exit;
        }


        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link,cat_sat_regimen_fiscal_id: 601);

        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta_org_empresa);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'alta_sucursal_bd');
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }


        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->alta_sucursal_bd(header: false, ws: false);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('Registro insertado con éxito', $resultado->mensaje);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;

        (new base_test())->del_org_sucursal(link: $this->link);
        (new base_test())->del_org_empresa(link: $this->link);
    }

    /**
     */
    public function test_asigna_link_sucursal_row(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'ubicacion';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'ubicacion');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl = new liberator($ctl);
        $row = new stdClass();
        $row->org_empresa_id = 1;
        $resultado = $ctl->asigna_link_sucursal_row($row);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(1,$resultado->org_empresa_id);
        $this->assertEquals('',$resultado->link_sucursales);
        $this->assertEquals('info',$resultado->link_sucursales_style);
        errores::$error = false;
    }

    public function test_cif(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'cif';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'cif');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }




        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link, cat_sat_regimen_fiscal_id: 601);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta_org_empresa);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->cif(header: false, ws: false);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

    }

    public function test_contacto(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'contacto';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'contacto');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link);

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->contacto(header: false, ws: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

    }

    public function test_identidad(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'identidad';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link);

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'identidad');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->identidad(header: false, ws: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

    }

    /*
    public function test_modifica_departamento(): void
    {
        errores::$error = false;
        (new base_test())->del_org_departamento(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'modifica_departamento';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta_org_departamento = (new base_test())->alta_org_departamento(link: $this->link, id: 1, org_clasificacion_dep_id: 1);

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->org_departamento_id = 1;
        $resultado = $ctl->modifica_departamento(header: false, ws: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_departamento(link: $this->link);

    }*/

    public function test_modifica_departamento_bd(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'modifica_departamento_bd';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $_POST = array();
        $_POST['org_empresa_id'] = 1;
        $_POST['descripcion'] = mt_rand(100000,999999);


        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta_org_empresa);
            print_r($error);
        }

        (new base_test())->del_org_departamento(link: $this->link);

        $alta_org_departamento = (new base_test())->alta_org_departamento(link: $this->link, id: 1, org_clasificacion_dep_id: 1);

        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta_org_departamento);
            print_r($error);
        }



        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'modifica_departamento_bd');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->org_departamento_id = 1;
        $resultado = $ctl->modifica_departamento_bd(header: false, ws: false);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('exito', $resultado->salida);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;

        (new base_test())->del_org_departamento(link: $this->link);
        (new base_test())->del_org_empresa(link: $this->link);


    }

    public function test_modifica_identidad(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'modifica_identidad';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link);

        $_POST = array();
        $_POST['codigo'] = 2;
        $_POST['rfc'] = 'BBB020202DEF';

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'modifica_identidad');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->modifica_identidad(header: false, ws: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('exito', $resultado->salida);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

    }

    public function test_modifica_cif(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'modifica_cif';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link);

        $_POST = array();
        $_POST['email_sat'] = 'a@a.a';

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'modifica_cif');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->modifica_cif(header: false, ws: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('exito',$resultado->salida);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

    }

    public function test_modifica_contacto(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'modifica_contacto';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link);

        $_POST = array();
        $_POST['telefono_1'] = '1234567890';

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'modifica_contacto');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->modifica_contacto(header: false, ws: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('exito',$resultado->salida);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

    }

    public function test_modifica_generales(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'modifica_generales';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link);

        $_POST = array();
        $_POST['rfc'] = 'BBB020202DEF';

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'modifica_generales');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->modifica_generales(header: false, ws: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('exito',$resultado->salida);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

    }

    /*
    public function test_modifica_sucursal(): void
    {
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'modifica_sucursal';

        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta_org_empresa = (new base_test())->alta_org_empresa(link: $this->link);
        $alta_org_sucursal = (new base_test())->alta_org_sucursal(link: $this->link);

        $_GET = array();
        $_GET['org_sucursal_id'] = 1;



        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl->registro_id = 1;
        $resultado = $ctl->modifica_sucursal(header: false, ws: false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
        (new base_test())->del_org_empresa(link: $this->link);

    }*/

    /*   public function test_modifica_registro_patronal(): void
       {
           errores::$error = false;
           (new base_test())->del_im_registro_patronal(link: $this->link);

           $_GET['seccion'] = 'org_empresa';
           $_GET['accion'] = 'modifica_registro_patronal';

           $_SESSION['grupo_id'] = 2;
           $_GET['session_id'] = '1';
           $_SESSION['usuario_id'] = '2';

           $alta_im_registro_patronal = (new base_test())->alta_im_registro_patronal(link: $this->link);
           var_dump($alta_im_registro_patronal);exit;

           $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
           $ctl->org_departamento_id = 1;
           $resultado = $ctl->modifica_registro_patronal(header: false, ws: false);
           var_dump($alta_im_registro_patronal);
           var_dump($resultado);exit;
           $this->assertIsObject($resultado);
           $this->assertNotTrue(errores::$error);
           errores::$error = false;
           (new base_test())->del_im_registro_patronal(link: $this->link);

       }*/



    /**
     */
    public function test_params_empresa(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'ubicacion';

        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_empresa',
            descripcion: 'ubicacion');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        $ctl = new liberator($ctl);
        $resultado = $ctl->params_empresa();
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado->codigo->disabled);
        $this->assertTrue($resultado->codigo_bis->disabled);
        $this->assertTrue($resultado->razon_social->disabled);
        $this->assertEquals(6, $resultado->codigo_bis->cols);
        errores::$error = false;
    }

    /**
     */
    public function test_ubicacion(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'ubicacion';

        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';
        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);


        $del = (new base_test())->del_org_sucursal($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_org_empresa($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_org_empresa($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }


        $_GET['registro_id'] = $alta->registro_id;
        $ctl->registro_id = $alta->registro_id;

        $resultado = $ctl->ubicacion(false);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }









}

