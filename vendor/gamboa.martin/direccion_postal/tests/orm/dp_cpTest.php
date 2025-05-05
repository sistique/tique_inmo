<?php
namespace gamboamartin\direccion_postal\tests\orm;

use gamboamartin\direccion_postal\instalacion\instalacion;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\direccion_postal\models\dp_cp;
use gamboamartin\direccion_postal\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;



class dp_cpTest extends test {
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
        $_SESSION['usuario_id'] = 2;
        $_SESSION['grupo_id'] = 2;

        $instala = (new instalacion())->instala(link: $this->link);
        if(errores::$error){
            $error  = (new errores())->error('Error al reinstalar', $instala);
            print_r($error);
            exit;
        }
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $_SESSION['usuario_id'] = 1;
        $modelo = new dp_cp($this->link);

        $del = (new base_test())->del_dp_municipio(link: $this->link);
        if(errores::$error){
            $error  = (new errores())->error('Error al del', $del);
            print_r($error);
            exit;
        }


        $alta = (new base_test())->alta_dp_municipio(link: $this->link, predeterminado: 'activo');
        if(errores::$error){
            $error  = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }



        $modelo->registro['descripcion'] = '01125';
        $modelo->registro['codigo'] = '01125';


        $resultado = $modelo->alta_bd();

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;


    }

    public function test_get_cp(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_cp';
        $_SESSION['usuario_id'] = 1;

        $del = (new base_test())->del_dp_cp(link: $this->link);
        if(errores::$error){
            $error  = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_dp_cp(link: $this->link);
        if(errores::$error){
            $error  = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }



        $modelo = new dp_cp($this->link);

        $dp_cp_id = 1;
        $resultado = $modelo->get_cp($dp_cp_id);


        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }


    public function test_objs_direcciones(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $_SESSION['usuario_id'] = 1;
        $modelo = new dp_calle_pertenece($this->link);


        $del = (new base_test())->del_dp_calle($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_dp_colonia($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $del = (new base_test())->del_dp_cp($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }




        $dp_calle_pertenece_id = 1;
        $resultado = $modelo->objs_direcciones($dp_calle_pertenece_id);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("Error al obtener calle pertenece",$resultado['mensaje']);

        errores::$error = false;

        $alta = (new base_test())->alta_dp_calle_pertenece(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }


        $dp_calle_pertenece_id = 1;
        $resultado = $modelo->objs_direcciones($dp_calle_pertenece_id);


        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertObjectHasProperty('pais',$resultado);

        errores::$error = false;
    }







}

