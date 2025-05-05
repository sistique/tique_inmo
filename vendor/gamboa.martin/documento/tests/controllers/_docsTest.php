<?php
namespace tests\controllers;

use gamboamartin\documento\controllers\_docs;
use gamboamartin\documento\models\doc_acl_tipo_documento;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;


class _docsTest extends test {
    public errores $errores;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    public function test_data_view_acl_tipo_documento()
    {
        errores::$error = false;
        $docs = new _docs();
        $docs = new liberator($docs);

        $resultado = $docs->data_view_acl_tipo_documento();

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('Id', $resultado->names[0]);
        $this->assertEquals('Tipo Doc', $resultado->names[1]);
        $this->assertEquals('Grupo', $resultado->names[2]);
        $this->assertEquals('Acciones', $resultado->names[3]);
        $this->assertEquals('doc_acl_tipo_documento_id', $resultado->keys_data[0]);
        $this->assertEquals('doc_tipo_documento_descripcion', $resultado->keys_data[1]);
        $this->assertEquals('adm_grupo_descripcion', $resultado->keys_data[2]);
        $this->assertEquals('acciones', $resultado->key_actions);
        $this->assertEquals('gamboamartin\documento\models', $resultado->namespace_model);
        $this->assertEquals('doc_acl_tipo_documento', $resultado->name_model_children);


        errores::$error = false;
    }
}

