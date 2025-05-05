<?php
namespace tests\links\secciones;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_puesto;
use gamboamartin\organigrama\html\org_puesto_html;
use gamboamartin\organigrama\tests\base_test;
use gamboamartin\template_1\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use stdClass;


class org_puesto_htmlTest extends test {
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

    /**
     */
    public function test_asigna_inputs(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $html_ = new html();
        $html = new org_puesto_html($html_);
        $html = new liberator($html);

        $del = (new base_test())->del_adm_seccion(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al del', $del);
            print_r($error);
            exit;
        }

        $alta = (new base_test())->alta_adm_seccion(link: $this->link, descripcion: 'org_empresa');
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $controler = new controlador_org_puesto(link: $this->link, paths_conf: $this->paths_conf);
        $inputs = new stdClass();
        $inputs->selects = new stdClass();
        $inputs->selects->org_empresa_id = 'x';
        $inputs->selects->org_tipo_puesto_id = 'x';
        $inputs->selects->org_departamento_id = 'x';
        $inputs->inputs = new stdClass();
        $inputs->inputs->descripcion = 'a';
        $resultado = $html->asigna_inputs($controler, $inputs);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("x", $resultado->select->org_departamento_id);
        $this->assertEquals("x", $resultado->select->org_tipo_puesto_id);
        $this->assertEquals("a", $resultado->descripcion);

        errores::$error = false;
    }

    /**
     */
    public function test_select_org_puesto_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $html_ = new html();
        $html = new org_puesto_html($html_);
        //$html = new liberator($html);

        $_SESSION['usuario_id']  = 2;
        $cols = 1;
        $con_registros = true;
        $id_selected = 1;
        $link = $this->link;

        $del = (new \gamboamartin\organigrama\tests\base_test())->del_org_puesto(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }

        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_org_puesto(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $resultado = $html->select_org_puesto_id($cols, $con_registros, $id_selected, $link);


        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("label class='control-label' for='org_puesto_id'>Puesto</label><div class=", $resultado);
        errores::$error = false;
    }

}

