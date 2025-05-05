<?php
namespace tests\links\secciones;

use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use html\selects;
use stdClass;
use html\dp_cp_html;


class selectsTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = '')
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
    public function test_direcciones(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new selects();
        //$dir = new liberator($dir);

        $row = new stdClass();
        $selects = new stdClass();
        $resultado = $dir->direcciones($html, $this->link, $row, $selects);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-6'><label class='control-label' for='dp_pais_id'>Pais</label><",$resultado->dp_pais_id);
        $this->assertStringContainsStringIgnoringCase("'><label class='control-label' for='dp_calle_pertenece_entre2_id",$resultado->dp_calle_pertenece_entre2_id);
        errores::$error = false;
    }

    /**
     */
    public function test_dp_calle_pertenece_entre1_id(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new selects();
        //$dir = new liberator($dir);

        $row = new stdClass();
        $filtro =  array();
        $link = $this->link;
        $resultado = $dir->dp_calle_pertenece_entre1_id(con_registros: false, filtro: $filtro,html:  $html,
            key_filtro: '', key_id: 'dp_calle_pertenece_entre1_id', link: $link,row:  $row,
            tabla: 'dp_calle_pertenece');
        $this->assertEquals(-1,$resultado->row->dp_calle_pertenece_id);
        $this->assertEquals(-1,$resultado->row->dp_calle_pertenece_entre1_id);
        $this->assertStringContainsStringIgnoringCase("' for='dp_calle_pertenece_entre1_id'>E",$resultado->select);

        errores::$error = false;
    }

    /**
     */
    public function test_dp_calle_pertenece_id(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new selects();
        //$dir = new liberator($dir);

        $row = new stdClass();
        $filtro =  array();
        $link = $this->link;
        $disabled = false;
        $resultado = $dir->dp_calle_pertenece_id(con_registros: false, filtro: $filtro,html:  $html, key_filtro: '',
            key_id: 'dp_calle_pertenece_id', link: $link,row:  $row, tabla:'dp_calle_pertenece' ,disabled: $disabled);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        $this->assertEquals(-1,$resultado->row->dp_calle_pertenece_id);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-6'><label class='control-label' for='dp_calle_pertenece_id'>Calle</label><div class='controls'><se",$resultado->select);

        errores::$error = false;

        $row = new stdClass();
        $filtro =  array();
        $link = $this->link;
        $disabled = true;
        $resultado = $dir->dp_calle_pertenece_id(con_registros: true, filtro: $filtro,html:  $html, key_filtro: '',
            key_id: 'dp_calle_pertenece_id', link:  $link,row:  $row,tabla:'dp_calle_pertenece',disabled: $disabled);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        $this->assertEquals(-1,$resultado->row->dp_calle_pertenece_id);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-6'><label class='control-label' for='dp_calle_pertenece_id'>Calle</labe",$resultado->select);


        errores::$error = false;

        $row = new stdClass();
        $row->dp_calle_pertenece_id = 1;
        $filtro =  array();
        $link = $this->link;
        $disabled = true;
        $resultado = $dir->dp_calle_pertenece_id(con_registros: false, filtro: $filtro, html: $html,key_filtro: '',
            key_id: 'dp_calle_pertenece_id', link: $link, row: $row,tabla:'dp_calle_pertenece',disabled: $disabled);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        $this->assertEquals(1,$resultado->row->dp_calle_pertenece_id);
        $this->assertStringContainsStringIgnoringCase("abel' for='dp_calle_pertenece_id'>Calle</label><div class='controls'><select",$resultado->select);


        errores::$error = false;
    }

    public function test_dp_dp_calle_pertenece_entre1_id(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new selects();
        //$dir = new liberator($dir);

        $row = new stdClass();
        $filtro =  array();
        $link = $this->link;
        $resultado = $dir->dp_calle_pertenece_entre1_id(con_registros: true, filtro: $filtro,html:  $html,
            key_filtro: '', key_id: 'dp_calle_pertenece_entre1_id', link: $link,row:  $row, tabla: 'dp_calle_pertenece');
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(-1,$resultado->row->dp_calle_pertenece_id);
        $this->assertEquals(-1,$resultado->row->dp_calle_pertenece_entre1_id);
        $this->assertStringContainsStringIgnoringCase("e1_id'>Entre calle</label><div class='co",$resultado->select);

        errores::$error = false;

        $row = new stdClass();
        $filtro =  array();
        $link = $this->link;

        $cols = 1;
        $resultado = $dir->dp_calle_pertenece_entre1_id(con_registros: false, filtro: $filtro,html:  $html,
            key_filtro: '',key_id: 'dp_calle_pertenece_entre1_id', link: $link,row:  $row, tabla: 'dp_calle_pertenece',cols: $cols );
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-1'><l",$resultado->select);

        errores::$error = false;

        $row = new stdClass();
        $filtro =  array();
        $link = $this->link;

        $cols = 1;
        $disabled = true;
        $resultado = $dir->dp_calle_pertenece_entre1_id(con_registros: true, filtro: $filtro,html:  $html,
            key_filtro: '', key_id: 'dp_calle_pertenece_entre1_id', link: $link,row:  $row, tabla: 'dp_calle_pertenece',
            cols: $cols , disabled: $disabled);

        $this->assertStringContainsStringIgnoringCase("name='dp_calle_pertenece_entre1_id'  disabled><o",$resultado->select);


        errores::$error = false;
    }

    /**
     */
    public function test_dp_pais_id(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new selects();

        $row = new stdClass();
        $resultado = $dir->dp_pais_id(true, array(),$html,'','dp_pais_id', $this->link, $row, tabla: 'dp_pais');
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(121,$resultado->row->dp_pais_id);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-6'><label class='control-label' for='dp_pais_id'>Pais",$resultado->select);

        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new selects();

        $row = new stdClass();
        $row->dp_pais_id = 999;
        $resultado = $dir->dp_pais_id(false, array(),$html,'','dp_pais_id', $this->link, $row, tabla: 'dp_pais');
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(999,$resultado->row->dp_pais_id);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-6'><label class='control-label' for='dp_pais_id'>Pais",$resultado->select);


        errores::$error = false;
    }

    public function test_filtro_select(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';

        $dir = new selects();
        $dir = new liberator($dir);

        $filtro = array();
        $row = new stdClass();
        $key_filtro = 'a';
        $name_attr = 'b';
        $row->b = 'a';
        $resultado = $dir->filtro_select($filtro, $key_filtro, $name_attr, $row);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado['a']);
        errores::$error = false;
    }

    public function test_genera_name_attr(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $dir = new selects();
        $dir = new liberator($dir);

        $key_filtro = 'a';
        $resultado = $dir->genera_name_attr($key_filtro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado);
        errores::$error = false;
    }

    /**
     */
    public function test_genera_obj_html(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new selects();
        $dir = new liberator($dir);

        $tabla = 'dp_calle';
        $resultado = $dir->genera_obj_html($html, $tabla);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_genera_select(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';

        $dir = new selects();
        $dir = new liberator($dir);

        $tabla = 'dp_cp';
        $con_registros = false;
        $filtro = array();
        $html = new html();
        $obj_html = new dp_cp_html($html);
        $row_ = new stdClass();
        $resultado = $dir->genera_select($con_registros, $filtro, $this->link, $obj_html, $row_, $tabla);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-6'><label class='control-label' for='dp_cp_id'>", $resultado);
        errores::$error = false;
    }

    /**
     */
    public function test_key_id(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';

        $dir = new selects();
        $dir = new liberator($dir);

        $tabla = 'a';
        $resultado = $dir->key_id($tabla);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a_id",$resultado);
        errores::$error = false;
    }

    public function test_name_attr(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $dir = new selects();
        $dir = new liberator($dir);

        $key_filtro = 'a';
        $resultado = $dir->name_attr($key_filtro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a",$resultado);
        errores::$error = false;

        $key_filtro = 'a.a.a';
        $resultado = $dir->name_attr($key_filtro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a_a_a",$resultado);
        errores::$error = false;


    }

    public function test_name_function(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';

        $dir = new selects();
        $dir = new liberator($dir);

        $tabla = 'a';
        $resultado = $dir->name_function($tabla);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("select_a",$resultado);
        errores::$error = false;
    }

    /**
     */
    public function test_name_obk_html(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';

        $dir = new selects();
        $dir = new liberator($dir);

        $tabla =  'a';
        $resultado = $dir->name_obk_html($tabla);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("html\a_html",$resultado);
        errores::$error = false;
    }

    /**
     */
    public function test_obj_html(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';
        $html = new html();
        $dir = new selects();
        $dir = new liberator($dir);

        $name_obj = dp_cp_html::class;
        $resultado = $dir->obj_html($name_obj, $html);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_select_base(): void
    {
        errores::$error = false;
        $_GET['session_id'] = 1;
        $_GET['seccion'] = 'dp_estado';

        $dir = new selects();
        $dir = new liberator($dir);

        $tabla = 'dp_colonia';
        $con_registros = false;
        $filtro = array();
        $html = new html();
        $row = new stdClass();
        $row->dp_colonia_id = 10;
        $resultado = $dir->select_base($con_registros, $filtro, $html, $this->link, $row, $tabla);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(10,$resultado->row->dp_colonia_id);
        errores::$error = false;
    }







}

