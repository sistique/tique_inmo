<?php
namespace gamboamartin\cat_sat\tests\controllers;

use gamboamartin\cat_sat\controllers\controlador_cat_sat_metodo_pago;
use gamboamartin\cat_sat\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;


class controlador_cat_sat_metodo_pagoTest extends test {
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
    public function test_init_datatable(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_metodo_pago';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $del = (new base_test())->del_adm_usuario(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar', data: $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al del', data: $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_adm_usuario(link: $this->link, id: 2);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_adm_seccion(link: $this->link, descripcion: 'cat_sat_metodo_pago', id: 2);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            print_r($error);
            exit;
        }


        $controler = new controlador_cat_sat_metodo_pago(link: $this->link, paths_conf: $this->paths_conf);
        $controler = new liberator($controler);

        $resultado = $controler->init_datatable();


        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('Id', $resultado->columns['cat_sat_metodo_pago_id']['titulo']);
        $this->assertEquals('Código', $resultado->columns['cat_sat_metodo_pago_codigo']['titulo']);
        $this->assertEquals('Método Pago', $resultado->columns['cat_sat_metodo_pago_descripcion']['titulo']);
        $this->assertEquals('cat_sat_metodo_pago.id', $resultado->filtro[0]);
        $this->assertEquals('cat_sat_metodo_pago.codigo', $resultado->filtro[1]);
        $this->assertEquals('cat_sat_metodo_pago.descripcion', $resultado->filtro[2]);


        errores::$error = false;
    }







}

