<?php
namespace gamboamartin\direccion_postal\tests\controllers;


use gamboamartin\direccion_postal\controllers\_init_dps;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;


class _init_dpsTest extends test {
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

    public function test_asigna_data(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $childrens = array();
        $entidad_key = 'b';
        $entidad = 'd';
        $key_option = 'c';
        $seccion_limpia = 'a';
        $seccion_param = 'z';

        $resultado = $init->asigna_data($childrens, $entidad, $entidad_key, $key_option, $seccion_limpia, $seccion_param);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('let asigna_d = (z_id =',$resultado);
        errores::$error = false;
    }

    public function test_change(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad = 'a';
        $exe = '';
        $resultado = $init->change($entidad, $exe);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('let selected = sl_a.find("option:selected");',$resultado);
        errores::$error = false;

        $entidad = 'a';
        $exe = 'v';
        $resultado = $init->change($entidad, $exe);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('let selected = sl_a.find("option:selected");asigna_v(selected.val());',$resultado);
        errores::$error = false;
    }

    public function test_childrens(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $data = array();
        $data['childrens'] = array();

        $resultado = $init->childrens($data);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEmpty($resultado);
        errores::$error = false;
    }

    public function test_ejecuta_funcion(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad = 'c';
        $resultado = $init->ejecuta_funcion($entidad);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("asigna_c(selected.val());",$resultado);
        errores::$error = false;
    }

    public function test_entidad_key(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $data = array();
        $key = 'a';
        $resultado = $init->entidad_key($data, $key);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a", $resultado);
        errores::$error = false;
    }

    public function test_exe(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $data = array();
        $data['childrens'] = array();
        $data['exe'] = '  aaa';

        $resultado = $init->exe($data);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("aaa", $resultado);
        errores::$error = false;
    }

    public function test_event_change(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad = 'z';
        $exe = 'j';
        $resultado = $init->event_change($entidad, $exe);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('let selected = sl_z.find("option:selected");asigna_j(selected.val());', $resultado);
        errores::$error = false;
    }

    public function test_genera_java(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $params = new stdClass();
        $params->key = 'a';
        $params->childrens = array();
        $params->entidad_key = 'c';
        $params->key_option = 'd';
        $params->seccion_limpia = 'b';
        $params->seccion_param = 'e';
        $params->exe = '';

        $resultado = $init->genera_java($params);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('let asigna_a = (e_id = "", val_selected_id = "") => {',$resultado->update);

        errores::$error = false;
    }

    public function test_java(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $params = new stdClass();
        $params->key = 'a';
        $params->childrens = array();
        $params->entidad_key = 'c';
        $params->key_option = 'd';
        $params->seccion_limpia = 'b';
        $params->seccion_param = 'e';
        $params->exe = '';
        $resultado = $init->java($params);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

    public function test_java_compuesto(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $java = new stdClass();
        $java->css_id = '';

        $resultado = $init->java_compuesto($java);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_init_datatables(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        //$init = new liberator($init);

        $columns = array();
        $filtro = array();

        $resultado = $init->init_datatables($columns, $filtro);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_key(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $seccion_limpia = 'a';
        $resultado = $init->key($seccion_limpia);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("dp_a", $resultado);
        errores::$error = false;
    }

    public function test_key_option(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $data = array();
        $resultado = $init->key_option($data);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);
        errores::$error = false;
    }

    public function test_limpia_selector(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $css_id = 'a';
        $entidad_limpia = 'v';

        $resultado = $init->limpia_selector($css_id, $entidad_limpia);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a.empty();integra_new_option(a,"Seleccione v","-1");', $resultado);

        errores::$error = false;
    }

    public function test_limpia_selectores(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);


        $selectores = array();
        $selectores[] = 'a';

        $resultado = $init->limpia_selectores($selectores);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('$("#dp_a_id").empty();integra_new_option($("#dp_a_id"),"Seleccione a","-1");', $resultado);

        errores::$error = false;
    }

    public function test_new_option(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad_key = 'b';
        $key_option = 'c';
        $seccion = 'a';

        $resultado = $init->new_option($entidad_key, $key_option, $seccion);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('integra_new_option(sl_a,a.b_c,a.a_id);', $resultado);

        errores::$error = false;
    }

    public function test_options(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad_key = 'b';
        $key_option = 'c';
        $seccion = 'a';

        $resultado = $init->options($entidad_key, $key_option, $seccion);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('$.each(data.registros, function( index, a ) {
            integra_new_option(sl_a,a.b_c,a.a_id);
        });', $resultado);

        errores::$error = false;
    }

    public function test_params(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $data = array();
        $seccion_limpia = 'a';
        $resultado = $init->params($data, $seccion_limpia);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('let sl_dp_a = $("#dp_a_id");', $resultado->css_id);

        errores::$error = false;

    }

    public function test_refresh_selectores(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $selectores = array();
        $selectores[] = 'c';
        $selectores[] = 'd';

        $resultado = $init->refresh_selectores($selectores);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('$("#dp_c_id").selectpicker("refresh");$("#dp_d_id").selectpicker("refresh");', $resultado);

        errores::$error = false;
    }

    public function test_refresh_selectpicker(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $css_id = 'a';

        $resultado = $init->refresh_selectpicker($css_id);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a.selectpicker("refresh");', $resultado);
        errores::$error = false;
    }

    public function test_seccion_param(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $data = array();
        $data['seccion_param'] = 'a';
        $resultado = $init->seccion_param($data);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a", $resultado);

        errores::$error = false;

        $data = array();
        $data['seccion_param'] = '';
        $resultado = $init->seccion_param($data);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);

        errores::$error = false;
    }

    public function test_select(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad = 'z';
        $resultado = $init->select($entidad);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('let sl_z = $("#z_id");', $resultado);

        errores::$error = false;
    }

    public function test_selected(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad = 'a';


        $resultado = $init->selected($entidad);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('let selected = sl_a.find("option:selected");', $resultado);

        errores::$error = false;
    }

    public function test_selector(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad = 'a';
        $resultado = $init->selector($entidad);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('$("#a_id")', $resultado);

        errores::$error = false;
    }

    public function test_update(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $childrens = array();
        $entidad_key = 'v';
        $key = 'a';
        $key_option = 'f';

        $resultado = $init->update($childrens, $entidad_key, $key, $key_option);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('$.each(data.registros, function( index, a ) {
            integra_new_option(sl_a,a.v_f,a.a_id);', $resultado);

        errores::$error = false;
    }

    public function test_update_data(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $childrens = array();
        $entidad_key = 'b';
        $key = 'a';
        $key_option = 'c';

        $resultado = $init->update_data($childrens, $entidad_key, $key, $key_option);
        $this->assertStringContainsStringIgnoringCase('get_data(url, function (data) {
        $.each(data.registros, function( index, a ) {
            integra_new_option(sl_a,a.b_c,a.a_id);
        });$("#a_id").val(val_selected_id);', $resultado);

        errores::$error = false;

    }

    public function test_update_ejecuta(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $childrens = array();
        $entidad_key = 'b';
        $key_option = 'c';
        $seccion_limpia = 'a';
        $seccion_param = '';

        $resultado = $init->update_ejecuta($childrens, $entidad_key, $key_option, $seccion_limpia, $seccion_param);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("let url = get_url('dp_a','get_a', {});;get_data(url, function (data) {
        $.each(data.registros, function( index, dp_a ) {
            integra_new_option(sl_dp_a,dp_a.b_c,dp_a.dp_a_id);
        });", $resultado);

        errores::$error = false;
    }

    public function test_url_servicio(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $accion = 'a';
        $seccion = 'c';
        $resultado = $init->url_servicio($accion, $seccion);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("get_url('c','a', {});", $resultado);

        errores::$error = false;
    }

    public function test_url_servicio_extra_param(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $accion = 'a';
        $seccion = 'v';
        $seccion_param = '';
        $resultado = $init->url_servicio_extra_param($accion, $seccion, $seccion_param);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("get_url('v','a', {});", $resultado);
    }

    public function test_url_servicio_get(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $seccion_limpia = 'a';
        $seccion_param = '';
        $resultado = $init->url_servicio_get($seccion_limpia, $seccion_param);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("get_url('dp_a','get_a', {});",$resultado);
        errores::$error = false;

    }

    public function test_valida_base(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $entidad_key = 'b';
        $key_option = 'c';
        $seccion = 'a';

        $resultado = $init->valida_base($entidad_key, $key_option, $seccion);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;
    }

    public function test_valida_pep_8_base(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_calle';
        $_SESSION['grupo_id'] = '1';
        $init = new _init_dps();
        $init = new liberator($init);

        $accion = 'a';
        $seccion = 'b';

        $resultado = $init->valida_pep_8_base($accion, $seccion);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;
    }







}

