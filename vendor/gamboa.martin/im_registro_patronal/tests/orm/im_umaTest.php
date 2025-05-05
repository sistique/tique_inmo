<?php
namespace gamboamartin\im_registro_patronal\test\orm;

use gamboamartin\empleado\models\em_empleado;
use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\test\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;


use gamboamartin\im_registro_patronal\models\im_movimiento;
use gamboamartin\im_registro_patronal\models\im_uma;
use stdClass;


class im_umaTest extends test {

    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
    }

    public function test_regisitro(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $modelo = new im_uma($this->link);
        //$html = new liberator($html);

        $del = (new base_test())->del_im_uma($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }


        $resultado = $modelo->registro(1);

        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);

        errores::$error = false;
        $alta = (new base_test())->alta_im_uma($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }


        $resultado = $modelo->registro(1);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('1',$resultado['im_uma_id']);


        errores::$error = false;
    }



}

