<?php
namespace tests\links\secciones;


use gamboamartin\direccion_postal\controllers\controlador_dp_colonia;
use gamboamartin\errores\errores;
use gamboamartin\template\directivas;
use gamboamartin\template_1\html;
use gamboamartin\test\test;
use html\inputs_html;
use stdClass;


class inputs_htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/direccion_postal/config/generales.php';
        $this->paths_conf->database = '/var/www/html/direccion_postal/config/database.php';
        $this->paths_conf->views = '/var/www/html/direccion_postal/config/views.php';
    }

    /**
     */
    public function test_base_direcciones_asignacion(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_SESSION['grupo_id'] = 2;
        $_GET['seccion'] = 'dp_estado';
        $_GET['accion'] = 'lista';

        $inp = new inputs_html();

        $controler = new controlador_dp_colonia(link: $this->link,paths_conf: $this->paths_conf);
        $inputs = new stdClass();
        $inputs->selects = new stdClass();
        $inputs->selects->dp_pais_id = '';
        $inputs->selects->dp_estado_id = '';
        $inputs->selects->dp_municipio_id = '';
        $inputs->selects->dp_cp_id = '';
        $inputs->selects->dp_colonia_postal_id = '';
        $inputs->selects->dp_calle_pertenece_id = '';
        $resultado = $inp->base_direcciones_asignacion($controler, $inputs);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('',$resultado->dp_pais_id);

        errores::$error = false;
    }

    /**
     */
    public function test_input(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['seccion'] = 'dp_estado';

        $inp = new inputs_html();

        $html = new html();
        $cols = 3;
        $directivas = new directivas($html);
        $row_upd = new stdClass();
        $value_vacio = false;
        $campo = 'a';

        $resultado = $inp->input($cols, $directivas, $row_upd, $value_vacio, $campo);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group col-sm-3'><label class='control-label' for='a'>a</label><div class='controls'><input type='text' name='a' value='' class='form-control' required id='a' placeholder='a' title='a' /></div></div>",$resultado);
        errores::$error = false;
    }




}

