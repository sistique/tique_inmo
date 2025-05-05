<?php
namespace gamboamartin\organigrama\tests\controllers;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_empresa;
use gamboamartin\organigrama\controllers\controlador_org_sucursal;
use gamboamartin\organigrama\tests\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use JsonException;

use stdClass;


class controlador_org_sucursalTest extends test {
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

    public function test_datos_inputs(): void
    {
        errores::$error = false;


        $_SESSION['grupo_id'] = 2;
        $_GET['session_id'] = '1';
        $_GET['seccion'] = 'org_sucursal';
        $_GET['accion'] = 'lista';
        $_SESSION['usuario_id'] = '2';

        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al del', $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_adm_seccion(link: $this->link, descripcion: 'org_sucursal');
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_sucursal',
            descripcion: 'ubicacion');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_adm_accion(link: $this->link, adm_seccion_descripcion: 'org_sucursal',
            descripcion: 'lista',id: 99);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $ctl = new controlador_org_sucursal(link: $this->link, paths_conf: $this->paths_conf);
        $ctl = new liberator($ctl);
        $ctl->registro_id = 1;
        $resultado = $ctl->datos_inputs();

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('Empresa', $resultado['org_empresa_id']['label']);
        $this->assertEquals('dp_pais_descripcion', $resultado['dp_pais_id']['key_descripcion_select']);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;

    }


}

