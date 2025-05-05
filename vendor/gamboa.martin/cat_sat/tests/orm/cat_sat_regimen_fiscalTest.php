<?php
namespace gamboamartin\cat_sat\tests\orm;

use gamboamartin\cat_sat\instalacion\instalacion;
use gamboamartin\cat_sat\models\cat_sat_conf_imps_tipo_pers;
use gamboamartin\cat_sat\models\cat_sat_conf_reg_tp;
use gamboamartin\cat_sat\models\cat_sat_metodo_pago;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\models\cat_sat_obj_imp;
use gamboamartin\cat_sat\models\cat_sat_producto;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use gamboamartin\cat_sat\models\cat_sat_unidad;
use gamboamartin\cat_sat\tests\base;
use gamboamartin\cat_sat\tests\base_test;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use stdClass;


class cat_sat_regimen_fiscalTest extends test {
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
    public function test_alta_bd(): void
    {
        errores::$error = false;



        $_GET['seccion'] = 'cat_sat_metodo_pago';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 1;
        $_GET['session_id'] = '1';

        $cat_sat_conf_imps_tipo_pers = new cat_sat_conf_imps_tipo_pers(link: $this->link,aplica_transacciones_base: true);
        $del = $cat_sat_conf_imps_tipo_pers->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error(mensaje:'Error al del',data:  $del);
            print_r($error);
            exit;
        }

        $cat_sat_conf_reg_tp = new cat_sat_conf_reg_tp(link: $this->link,aplica_transacciones_base: true);
        $del = $cat_sat_conf_reg_tp->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error(mensaje:'Error al del',data:  $del);
            print_r($error);
            exit;
        }

        $modelo = new cat_sat_regimen_fiscal(link: $this->link,aplica_transacciones_base: true);
        $del = $modelo->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error(mensaje:'Error al eliminar',data:  $del);
            print_r($error);
            exit;
        }


        $modelo = new cat_sat_regimen_fiscal(link: $this->link);

        $modelo->registro['codigo'] = '999';
        $modelo->registro['descripcion'] = 1;

        $resultado = $modelo->alta_bd();
 
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error al insertar regimen fiscal',$resultado['mensaje_limpio']);

        errores::$error = false;

        $modelo = new cat_sat_regimen_fiscal(link: $this->link,aplica_transacciones_base: true);
        $modelo->registro['codigo'] = '999';
        $modelo->registro['descripcion'] = 1;
        $resultado = $modelo->alta_bd();
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(999,$resultado->registro_id);

        $del = $modelo->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error(mensaje:'Error al eliminar',data:  $del);
            print_r($error);
            exit;
        }

        errores::$error = false;

        $instala = (new instalacion(link: $this->link))->instala(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje:'Error al reinstalar',data:  $instala);
            print_r($error);
            exit;
        }



    }







}

