<?php
namespace tests\orm;

use gamboamartin\documento\instalacion\instalacion;
use gamboamartin\documento\models\doc_acl_tipo_documento;
use gamboamartin\errores\errores;
use gamboamartin\test\test;


class doc_acl_tipo_documentoTest extends test {
    public errores $errores;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    public function test_tipo_documento_permiso()
    {
        $_SESSION['usuario_id'] = 2;

        $init = (new instalacion())->instala(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al instalar',data:  $init);
            print_r($error);
            exit;
        }


        errores::$error = false;
        $acl_tipo_doc = new doc_acl_tipo_documento($this->link);
        //$inicializacion = new liberator($inicializacion);


        $grupo_id = -1;
        $tipo_documento_id = 1;
        $resultado = $acl_tipo_doc->tipo_documento_permiso(grupo_id: $grupo_id, tipo_documento_id: $tipo_documento_id);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error grupo id no puede ser menor a 1', $resultado['mensaje']);

        errores::$error = false;

        $grupo_id = 1;
        $tipo_documento_id = -1;
        $resultado = $acl_tipo_doc->tipo_documento_permiso(grupo_id: $grupo_id, tipo_documento_id: $tipo_documento_id);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error tipo documento id no puede ser menor a 1', $resultado['mensaje']);

        errores::$error = false;

        $grupo_id = 1;
        $tipo_documento_id = 1;
        $resultado = $acl_tipo_doc->tipo_documento_permiso(grupo_id: $grupo_id, tipo_documento_id: $tipo_documento_id);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }
}

