<?php
namespace tests\controllers;


use gamboamartin\errores\errores;
use gamboamartin\proceso\html\pr_entidad_html;

use gamboamartin\proceso\models\pr_proceso;
use gamboamartin\proceso\models\pr_tipo_proceso;
use gamboamartin\proceso\tests\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use stdClass;


class pr_tipo_procesoTest extends test {
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

    public function test_alta_bd(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new pr_tipo_proceso(link: $this->link);
        //$modelo = new liberator($modelo);

        $del = (new base_test())->del_pr_tipo_proceso(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar',data:  $del);
            print_r($error);exit;
        }

        $modelo->registro['codigo'] = 'a';
        $modelo->registro['descripcion'] = 'a';

        $resultado = $modelo->alta_bd();

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado->registro['pr_tipo_proceso_descripcion']);
        $this->assertEquals('a',$resultado->registro['pr_tipo_proceso_codigo']);

        errores::$error = false;


    }


}

