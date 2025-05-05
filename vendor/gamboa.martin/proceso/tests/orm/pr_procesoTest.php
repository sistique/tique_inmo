<?php
namespace tests\controllers;


use gamboamartin\administrador\instalacion\instalacion;
use gamboamartin\errores\errores;
use gamboamartin\proceso\html\pr_entidad_html;

use gamboamartin\proceso\models\pr_proceso;
use gamboamartin\proceso\tests\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use stdClass;


class pr_procesoTest extends test {
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

    public function test_data_etapa(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $instala = (new instalacion())->instala(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al instala',data:  $instala);
            print_r($error);exit;
        }

        $modelo = new pr_proceso(link: $this->link);
        $modelo = new liberator($modelo);

        $adm_accion = 'a';
        $adm_seccion = 'b';
        $valida_existencia_etapa = false;
        $resultado = $modelo->data_etapa($adm_accion, $adm_seccion, $valida_existencia_etapa);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $del = (new base_test())->del_pr_etapa_proceso(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $del);
            print_r($error);exit;
        }

        $del = (new base_test())->del_pr_sub_proceso(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $del);
            print_r($error);exit;
        }

        $alta = (new base_test())->alta_pr_etapa_proceso(link: $this->link,adm_accion_descripcion: 'test');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            print_r($error);exit;
        }

        $adm_accion = 'test';
        $adm_seccion = 'adm_accion';
        $valida_existencia_etapa = true;
        $resultado = $modelo->data_etapa($adm_accion, $adm_seccion,'', $valida_existencia_etapa);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;
    }

    public function test_data_insert_etapa(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new pr_proceso(link: $this->link);
        $modelo = new liberator($modelo);

        $fecha = '';
        $key_id = 'a';
        $pr_etapa_proceso_id = 1;
        $registro_id = 1;
        $resultado = $modelo->data_insert_etapa($fecha, $key_id, $pr_etapa_proceso_id, $registro_id);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(1,$resultado['pr_etapa_proceso_id']);
        $this->assertEquals(1,$resultado['a']);
        $this->assertEquals(date('Y-m-d'),$resultado['fecha']);
        errores::$error = false;
    }

    public function test_data_row_insert(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new pr_proceso(link: $this->link);
        $modelo = new liberator($modelo);

        $fecha = '';
        $key_id = 'a';
        $r_pr_etapa_proceso = new stdClass();
        $r_pr_etapa_proceso->registros[0]['pr_etapa_proceso_id'] = 1;
        $registro_id = 1;
        $resultado = $modelo->data_row_insert($fecha, $key_id, $r_pr_etapa_proceso, $registro_id);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(1,$resultado['pr_etapa_proceso_id']);
        $this->assertEquals(1,$resultado['a']);
        $this->assertEquals(date('Y-m-d'),$resultado['fecha']);
        errores::$error = false;
    }

    public function test_filtro_etapa_proceso(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new pr_proceso(link: $this->link);
        $modelo = new liberator($modelo);

        $adm_accion = 'a';
        $adm_seccion = 'b';
        $resultado = $modelo->filtro_etapa_proceso($adm_accion, $adm_seccion);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado['adm_accion.descripcion']);
        $this->assertEquals('b',$resultado['adm_seccion.descripcion']);
        errores::$error = false;


    }

    public function test_inserta_data_etapa(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new pr_proceso(link: $this->link);
        $modelo = new liberator($modelo);

        $fecha = '';
        $r_pr_etapa_proceso = new stdClass();
        $registro_id = -1;
        $modelo_etapa = new pr_proceso(link: $this->link);
        $resultado = $modelo->inserta_data_etapa($fecha, $modelo_etapa, $modelo_etapa, $r_pr_etapa_proceso, $registro_id);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_inserta_etapa(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new pr_proceso(link: $this->link);
        //$modelo = new liberator($modelo);

        $adm_accion = 'a';
        $fecha = '';
        $registro_id = -1;
        $modelo_etapa = new pr_proceso(link: $this->link);
        $resultado = $modelo->inserta_etapa($adm_accion, $fecha, $modelo, $modelo_etapa, $registro_id,'',false);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }


}

