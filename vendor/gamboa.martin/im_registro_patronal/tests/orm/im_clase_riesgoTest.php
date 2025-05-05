<?php
namespace gamboamartin\im_registro_patronal\test\orm;

use gamboamartin\empleado\models\em_clase_riesgo;
use gamboamartin\empleado\models\em_empleado;
use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\models\im_clase_riesgo;
use gamboamartin\im_registro_patronal\test\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;


use gamboamartin\im_registro_patronal\models\im_movimiento;
use gamboamartin\im_registro_patronal\models\im_uma;
use stdClass;


class im_clase_riesgoTest extends test {

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
        $modelo = new im_clase_riesgo($this->link);
        //$html = new liberator($html);

        $del = (new base_test())->del_im_clase_riesgo(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_em_clase_riesgo(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $modelo->registro['descripcion'] = 'a';
        $modelo->registro['factor'] = '0.1';
        $resultado = $modelo->alta_bd();

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        $im_clase_riesgo = (new im_clase_riesgo(link: $this->link))->registro(registro_id:$resultado->registro_id );
        if(errores::$error){
            $error = (new errores())->error('Error al obtener registro', $im_clase_riesgo);
            print_r($error);
            exit;
        }

        $em_clase_riesgo = (new em_clase_riesgo(link: $this->link))->registro(registro_id:$resultado->registro_id );
        if(errores::$error){
            $error = (new errores())->error('Error al obtener registro', $em_clase_riesgo);
            print_r($error);
            exit;
        }

        $this->assertEquals($im_clase_riesgo['im_clase_riesgo_id'],  $em_clase_riesgo['em_clase_riesgo_id']);
        $this->assertEquals($im_clase_riesgo['im_clase_riesgo_descripcion'],  $em_clase_riesgo['em_clase_riesgo_descripcion']);


        errores::$error = false;
    }



}

