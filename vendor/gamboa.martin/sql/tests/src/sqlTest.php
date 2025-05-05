<?php

use gamboamartin\errores\errores;
use gamboamartin\src\sql;
use gamboamartin\test\liberator;
use gamboamartin\test\test;


class sqlTest extends test {
    public errores $errores;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    public function test_in(): void
    {
        errores::$error = false;
        $sql = new sql();
        //$sql = new liberator($sql);

        $llave = '';
        $values_sql = '';
        $resultado = $sql->in($llave, $values_sql);
        $this->assertIsString( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('',$resultado);

        errores::$error = false;

        $llave = 'a';
        $values_sql = '';
        $resultado = $sql->in($llave, $values_sql);
        $this->assertIsArray( $resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar in',$resultado['mensaje']);

        errores::$error = false;

        $llave = '';
        $values_sql = 'a';
        $resultado = $sql->in($llave, $values_sql);
        $this->assertIsArray( $resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar in',$resultado['mensaje']);

        errores::$error = false;

        $llave = 'a';
        $values_sql = 'a';
        $resultado = $sql->in($llave, $values_sql);

        $this->assertIsString( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('a IN (a)',$resultado);

        errores::$error = false;
    }

    public function test_limpia_espacios_dobles(): void
    {
        errores::$error = false;
        $sql = new sql();
        $sql = new liberator($sql);

        $txt = '     ';
        $resultado = $sql->limpia_espacios_dobles($txt);
        //print_r($resultado);exit;
        $this->assertIsString( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(' ',$resultado);


        errores::$error = false;
    }

    public function test_valida_in(): void
    {
        errores::$error = false;
        $sql = new sql();
        //$sql = new liberator($sql);

        $llave = '';
        $values_sql = '';
        $resultado = $sql->valida_in($llave, $values_sql);
        $this->assertIsBool( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;

        $llave = 'a';
        $values_sql = '';
        $resultado = $sql->valida_in($llave, $values_sql);
        $this->assertIsArray( $resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error: si la llave tiene contenido, $values_sql no puede estar vacío',$resultado['mensaje']);

        errores::$error = false;

        $llave = 'a';
        $values_sql = 'b';
        $resultado = $sql->valida_in($llave, $values_sql);
        $this->assertIsBool( $resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;

        $llave = '';
        $values_sql = 'b';
        $resultado = $sql->valida_in($llave, $values_sql);
        $this->assertIsArray( $resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error: si $values_sql tiene contenido, la llave no puede estar vacía',$resultado['mensaje']);
        errores::$error = false;

    }






}