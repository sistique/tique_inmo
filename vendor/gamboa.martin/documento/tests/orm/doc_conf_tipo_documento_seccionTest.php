<?php
namespace tests\orm;

use gamboamartin\documento\instalacion\instalacion;
use gamboamartin\documento\models\doc_conf_tipo_documento_seccion;
use gamboamartin\documento\models\doc_extension;
use gamboamartin\errores\errores;

use gamboamartin\test\liberator;
use tests\base_test;


class doc_conf_tipo_documento_seccionTest extends base_test {
    public errores $errores;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
    }

    public function test_inicializa_campos()
    {

        $_SESSION['usuario_id'] = 2;
        errores::$error = false;

        $instala = (new instalacion())->instala(link: $this->link);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al instala', data: $instala);
            print_r($error);
            die('Error');
        }

        $modelo = new doc_conf_tipo_documento_seccion($this->link);
        $modelo = new liberator($modelo);

        $registros = array();
        $registros['doc_tipo_documento_id'] = 1;
        $registros['adm_seccion_id'] = 1;
        $resultado = $modelo->inicializa_campos(registros: $registros);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

}

