<?php
namespace gamboamartin\im_registro_patronal\test\orm;

use gamboamartin\empleado\models\em_clase_riesgo;
use gamboamartin\empleado\models\em_empleado;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\models\im_clase_riesgo;
use gamboamartin\im_registro_patronal\models\im_registro_patronal;
use gamboamartin\im_registro_patronal\test\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;


use gamboamartin\im_registro_patronal\models\im_movimiento;
use gamboamartin\im_registro_patronal\models\im_uma;
use stdClass;


class im_registro_patronalTest extends test {

    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
    }

    public function test_alta_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $modelo = new im_registro_patronal($this->link);
        //$html = new liberator($html);

        $del = (new base_test())->del_im_registro_patronal(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar',data: $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_em_registro_patronal(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar',data: $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_em_clase_riesgo(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar',data: $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_im_clase_riesgo(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar',data: $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_im_clase_riesgo(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data: $alta);
            print_r($error);
            exit;
        }


        $modelo->registro['fc_csd_id'] = 1;
        $modelo->registro['em_clase_riesgo_id'] = 1;
        $modelo->registro['descripcion'] = 1;
        $modelo->registro['cat_sat_isn_id'] = 1;
        $resultado = $modelo->alta_bd();


        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        $im_clase_riesgo = (new im_clase_riesgo(link: $this->link))->registro(registro_id:$resultado->registro['em_clase_riesgo_id'] );
        if(errores::$error){
            $error = (new errores())->error('Error al obtener registro', $im_clase_riesgo);
            print_r($error);
            exit;
        }

        $em_clase_riesgo = (new em_clase_riesgo(link: $this->link))->registro(registro_id:$resultado->registro['em_clase_riesgo_id'] );
        if(errores::$error){
            $error = (new errores())->error('Error al obtener registro', $em_clase_riesgo);
            print_r($error);
            exit;
        }

        $this->assertEquals($im_clase_riesgo['im_clase_riesgo_id'],  $em_clase_riesgo['em_clase_riesgo_id']);
        $this->assertEquals($im_clase_riesgo['im_clase_riesgo_descripcion'],  $em_clase_riesgo['em_clase_riesgo_descripcion']);


        errores::$error = false;
    }

    public function test_modifica_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $modelo = new im_registro_patronal($this->link);
        //$html = new liberator($html);

        $del = (new base_test())->del_im_registro_patronal(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar',data: $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_em_registro_patronal(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar',data: $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_im_registro_patronal(link: $this->link, em_clase_riesgo_id: 2);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data: $alta);
            print_r($error);
            exit;
        }

        $im_registro_patronal_upd['em_clase_riesgo_id'] = 2;
        $resultado = $modelo->modifica_bd(registro: $im_registro_patronal_upd, id: 1);
        $this->assertNotTrue($resultado);

        $em_registro_patronal = (new em_registro_patronal(link: $this->link))->registro(registro_id:1);
        if(errores::$error){
            $error = (new errores())->error('Error al obtener registro', $em_registro_patronal);
            print_r($error);
            exit;
        }

        $im_registro_patronal = (new im_registro_patronal(link: $this->link))->registro(registro_id:1 );
        if(errores::$error){
            $error = (new errores())->error('Error al obtener registro', $im_registro_patronal);
            print_r($error);
            exit;
        }

        $this->assertEquals($im_registro_patronal['em_clase_riesgo_id'],  $em_registro_patronal['em_clase_riesgo_id']);
        $this->assertEquals($im_registro_patronal['em_clase_riesgo_descripcion'],  $em_registro_patronal['em_clase_riesgo_descripcion']);




    }



}

