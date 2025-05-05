<?php

use gamboamartin\errores\errores;
use gamboamartin\src\validaciones;
use gamboamartin\test\test;


class validacionesTest extends test {
    public errores $errores;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    public function test_valida_data_filtro_especial(): void
    {
        errores::$error = false;
        $val = new validaciones();


        $campo = '';
        $filtro = array();

        $resultado = $val->valida_data_filtro_especial($campo, $filtro);
        $this->assertIsArray( $resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error campo vacio', $resultado['mensaje']);

        errores::$error = false;

        $campo = 'a';
        $filtro = array();

        $resultado = $val->valida_data_filtro_especial($campo, $filtro);
        $this->assertIsArray( $resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error debe existir $filtro[campo][operador]', $resultado['mensaje']);

        errores::$error = false;

        $campo = 'a';
        $filtro = array();
        $filtro['a']['operador'] = 'b';

        $resultado = $val->valida_data_filtro_especial($campo, $filtro);
        $this->assertIsBool( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;
    }




}