<?php
namespace gamboamartin\im_registro_patronal\test\orm;

use gamboamartin\empleado\models\em_empleado;
use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\models\im_movimiento;
use gamboamartin\im_registro_patronal\test\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;


use stdClass;


class im_movimientoTest extends test {

    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
    }

    public function test_filtro_extra_fecha(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $html = new im_movimiento($this->link);
        $html = new liberator($html);

        $fecha = '2020-01-01';
        $resultado = $html->filtro_extra_fecha($fecha);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('>=', $resultado[0]['im_movimiento.fecha']['operador']);
        errores::$error = false;
    }

    /**
     */
    public function test_filtro_movimiento_fecha(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $html = new im_movimiento($this->link);
        //$html = new liberator($html);

        $em_empleado_id = -1;
        $fecha = "";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error id del empleado no puede ser menor a uno', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 1;
        $fecha = "";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error: ingrese una fecha valida', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 1;
        $fecha = "2022";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error: ingrese una fecha valida', $resultado['mensaje']);
        errores::$error = false;


        $em_empleado_id = 1;
        $fecha = "2022-09-13-";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error: ingrese una fecha valida', $resultado['mensaje']);
        errores::$error = false;


        $del = (new base_test())->del_em_empleado(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al ELIMINAR', $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_com_cliente(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $del);
            print_r($error);
            exit;
        }



        $alta = (new base_test())->alta_im_movimiento(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }


        $em_empleado_id = 1;
        $fecha = "2022-09-13";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);



        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_get_ultimo_movimiento_empleado(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $html = new im_movimiento($this->link);
        //$html = new liberator($html);

        $em_empleado_id = -1;
        $resultado = $html->get_ultimo_movimiento_empleado(em_empleado_id: $em_empleado_id);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error id del empleado no puede ser menor a uno', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 999;
        $resultado = $html->get_ultimo_movimiento_empleado(em_empleado_id: $em_empleado_id);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error no hay registros para el empleado', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 1;
        $resultado = $html->get_ultimo_movimiento_empleado(em_empleado_id: $em_empleado_id);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

    public function test_modifica_empleado(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $html = new im_movimiento($this->link);
        $html = new liberator($html);

        $del = (new base_test())->del_im_tipo_movimiento($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }


        $alta = (new base_test())->alta_im_tipo_movimiento($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al dar de alta', $alta);
            print_r($error);
            exit;
        }

        $registro_emp = array();
        $registro_emp['im_tipo_movimiento_id'] = 1;
        $registro_emp['em_empleado_id'] = 1;
        $resultado = $html->modifica_empleado($registro_emp);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertObjectNotHasAttribute('sql',$resultado);

        errores::$error = false;


        $alta = (new base_test())->alta_im_tipo_movimiento(
            link: $this->link,codigo: 2, codigo_bis: 2,descripcion: 2,es_alta: 'activo',id: 2);
        if(errores::$error){
            $error = (new errores())->error('Error al dar de alta', $alta);
            print_r($error);
            exit;
        }

        $registro_emp = array();
        $registro_emp['im_tipo_movimiento_id'] = 2;
        $registro_emp['em_empleado_id'] = 1;
        $resultado = $html->modifica_empleado($registro_emp);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al integrar registro',$resultado['mensaje']);

        errores::$error = false;

        $registro['fecha_inicio_rel_laboral'] = '2020-01-01';
        $r_empleado = (new em_empleado($this->link))->modifica_bd($registro, 1);
        if(errores::$error){
            $error = (new errores())->error('Error al modificar empleado', $r_empleado);
            print_r($error);
            exit;
        }

        $registro_emp = array();
        $registro_emp['im_tipo_movimiento_id'] = 2;
        $registro_emp['em_empleado_id'] = 1;
        $registro_emp['fecha'] = '2021-01-01';
        $resultado = $html->modifica_empleado($registro_emp);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("UPDATE em_empleado SET fecha_inicio_rel_laboral = '2021-01-01'",$resultado->sql);

        errores::$error = false;
    }

}

