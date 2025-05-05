<?php
namespace tests\links\secciones;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\html\org_empresa_html;
use gamboamartin\template_1\html;

use gamboamartin\test\test;

use stdClass;


class org_tipo_empresa_htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    /**
     */
    public function test_select_org_tipo_empresa_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $html_ = new html();
        $html = new org_empresa_html($html_);
        //$html = new liberator($html);

        $cols = 1;
        $con_registros = false;
        $id_selected = -1;
        $link = $this->link;

        $resultado = $html->select_org_empresa_id($cols, $con_registros, $id_selected, $link);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("l' for='org_empresa_id'>Empresa</label><div c", $resultado);

        errores::$error = false;
    }

}

