<?php

namespace gamboamartin\tests\src;

use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use gamboamartin\where\where;
use stdClass;


class whereTest extends test
{
    public errores $errores;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    public function test_and_filtro_fecha()
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);


        $filtro_fecha_sql = '';
        $resultado = $wh->and_filtro_fecha($filtro_fecha_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);

        errores::$error = false;


        $filtro_fecha_sql = 'a';
        $resultado = $wh->and_filtro_fecha($filtro_fecha_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(' AND ', $resultado);
        errores::$error = false;
    }

    public function test_asigna_data_filtro(): void
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);

        $filtro_especial_sql = '';
        $filtro_extra_sql = '';
        $filtro_rango_sql = '';
        $filtro_fecha_sql = '';
        $not_in_sql = '';
        $sentencia = '';
        $sql_extra = '';
        $in = '';
        $resultado = $wh->asigna_data_filtro('', $filtro_especial_sql, $filtro_extra_sql, $filtro_fecha_sql,
            $filtro_rango_sql, $in, $not_in_sql, $sentencia, $sql_extra);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $filtro_rango_sql = 'c';
        $in = 'a';
        $resultado = $wh->asigna_data_filtro('', $filtro_especial_sql, $filtro_extra_sql, $filtro_fecha_sql,
            $filtro_rango_sql, $in, $not_in_sql, $sentencia, $sql_extra);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado->in);
        $this->assertEquals('c', $resultado->filtro_rango);


        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $filtro_especial_sql = '';
        $filtro_extra_sql = '';
        $filtro_rango_sql = 'c';
        $filtro_fecha_sql = '';
        $not_in_sql = '';
        $sentencia = '';
        $sql_extra = '';
        $in = 'a';
        $diferente_de_sql = 'dif';
        $resultado = $wh->asigna_data_filtro($diferente_de_sql, $filtro_especial_sql, $filtro_extra_sql,
            $filtro_fecha_sql, $filtro_rango_sql, $in, $not_in_sql, $sentencia, $sql_extra);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado->in);
        $this->assertEquals('c', $resultado->filtro_rango);
        $this->assertEquals('dif', $resultado->diferente_de);


        errores::$error = false;

        $filtro_especial_sql = '';
        $filtro_extra_sql = 'xxx';
        $filtro_rango_sql = 'c';
        $filtro_fecha_sql = '';
        $not_in_sql = '';
        $sentencia = '';
        $sql_extra = '';
        $in = 'a';
        $diferente_de_sql = 'dif';
        $resultado = $wh->asigna_data_filtro($diferente_de_sql, $filtro_especial_sql, $filtro_extra_sql,
            $filtro_fecha_sql, $filtro_rango_sql, $in, $not_in_sql, $sentencia, $sql_extra);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado->in);
        $this->assertEquals('c', $resultado->filtro_rango);
        $this->assertEquals('dif', $resultado->diferente_de);
        $this->assertEquals('xxx', $resultado->filtro_extra);
        errores::$error = false;
    }

    public function test_campo(): void
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $data = '';
        $key = '';
        $resultado = $wh->campo(data: $data, key: $key);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error key vacio', $resultado['mensaje']);

        errores::$error = false;

        $data = '';
        $key = 'a';
        $resultado = $wh->campo(data: $data, key: $key);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado);

        errores::$error = false;

        $data = array();
        $key = 'a';
        $data['b'] = '';

        $resultado = $wh->campo(data: $data, key: $key);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado);

        errores::$error = false;

        $data = array();
        $key = 'a';
        $data['a'] = '';

        $resultado = $wh->campo(data: $data, key: $key);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado);

        $data = array();
        $key = 'a';
        $data['campo'] = 'x';

        $resultado = $wh->campo(data: $data, key: $key);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('x', $resultado);
        errores::$error = false;
    }

    public function test_campo_data_filtro()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $data_filtro = array();
        $data_filtro['a'] = '';
        $resultado = $wh->campo_data_filtro($data_filtro);
        //print_r($resultado);exit;
        $this->assertEquals('a', $resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_campo_filtro_especial(): void
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $campo = 'a';
        $columnas_extra = array();
        $columnas_extra['a'] = 'x';
        $resultado = $wh->campo_filtro_especial($campo, $columnas_extra);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('x', $resultado);

        errores::$error = false;
    }

    public function test_comparacion()
    {

        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);
        $data = array();
        $resultado = $wh->comparacion(data: $data, default: '');
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEmpty($resultado);
        errores::$error = false;
    }

    public function test_comparacion_pura()
    {

        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);
        $data = array();
        $columnas_extra = array();
        $key = '';
        $resultado = $wh->comparacion_pura($columnas_extra, $data, $key);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error key vacio', $resultado['mensaje']);

        errores::$error = false;


        $resultado = $wh->comparacion_pura($columnas_extra, $data, $key);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error key vacio', $resultado['mensaje']);

        errores::$error = false;

        $data[] = '';
        $resultado = $wh->comparacion_pura($columnas_extra, $data, $key);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error key vacio', $resultado['mensaje']);


        errores::$error = false;
        $data = array();
        $data['value'] = '';
        $key = 'x';
        $resultado = $wh->comparacion_pura($columnas_extra, $data, $key);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('x', $resultado->campo);
        $this->assertEquals('', $resultado->value);


        errores::$error = false;

        $resultado = $wh->comparacion_pura($columnas_extra, $data, $key);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('x', $resultado->campo);
        $this->assertEquals('', $resultado->value);

        errores::$error = false;


    }

    public function test_condicion_entre()
    {

        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);
        $valor_campo = true;
        $filtro = array();
        $filtro['valor1'] = 'a';
        $filtro['valor2'] = 'a';
        $campo = 'campo';
        $resultado = $wh->condicion_entre($campo, $filtro, $valor_campo);
        //print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("'campo' BETWEEN a AND a", $resultado);

        errores::$error = false;


    }

    public function test_data_filtro_fecha()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $fil_fecha = array('campo_1' => 'a', 'campo_2' => 2, 'fecha' => '2020-01-01');
        $resultado = $wh->data_filtro_fecha($fil_fecha);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado->campo_1);
        $this->assertEquals(2, $resultado->campo_2);
        $this->assertEquals('2020-01-01', $resultado->fecha);
        errores::$error = false;
    }

    public function test_data_in()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $in = array();
        $resultado = $wh->data_in($in);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al validar not_in', $resultado['mensaje']);

        errores::$error = false;

        $in = array();
        $in['llave'] = 'a';
        $in['values'] = array();
        $resultado = $wh->data_in($in);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a', $resultado->llave);
        errores::$error = false;
    }

    public function test_data_sql()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $campo = 'z';
        $campo_filtro = 'x';
        $filtro = array();
        $filtro['x']['operador'] = 's';
        $filtro['x']['valor'] = 's';

        $resultado = $wh->data_sql($campo, $campo_filtro, $filtro);

        //print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(" z s 's' ", $resultado);
        errores::$error = false;
    }

    public function test_data_sql_base()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $campo = 'c';
        $campo_filtro = 'a';
        $filtro = array();
        $filtro['a']['operador'] = '=>';
        $filtro['a']['valor'] = '';
        $resultado = $wh->data_sql_base($campo, $campo_filtro, $filtro);
        //print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(" c => '' ", $resultado);
        errores::$error = false;
    }

    public function test_data_sql_campo()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $campo = 'v';
        $campo_filtro = 'a';
        $filtro = array();
        $filtro['a']['operador'] = 'c';
        $filtro['a']['valor'] = '';
        $resultado = $wh->data_sql_campo($campo, $campo_filtro, $filtro);
        // print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("'v'c", $resultado);

        errores::$error = false;
    }

    public function test_datos_filtro_especial()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $data_filtro = array();
        $data_filtro['a']['operador'] = 'operador';
        $data_filtro['a']['valor'] = 'valor';
        $data_filtro['a']['comparacion'] = 'comparacion';
        $resultado = $wh->datos_filtro_especial($data_filtro);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a", $resultado->campo);
        $this->assertEquals("operador", $resultado->operador);
        $this->assertEquals("valor", $resultado->valor);
        $this->assertEquals("comparacion", $resultado->comparacion);
        $this->assertEquals("a operador 'valor'", $resultado->condicion);

        errores::$error = false;

        $data_filtro = array();
        $data_filtro['a']['operador'] = '=';
        $data_filtro['a']['valor'] = 'valor';
        $data_filtro['a']['comparacion'] = 'comparacion';
        $resultado = $wh->datos_filtro_especial($data_filtro);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a", $resultado->campo);
        $this->assertEquals("=", $resultado->operador);
        $this->assertEquals("valor", $resultado->valor);
        $this->assertEquals("comparacion", $resultado->comparacion);
        $this->assertEquals("a = 'valor'", $resultado->condicion);
        errores::$error = false;

    }

    public function test_es_subquery()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $campo = 'a';
        $columnas_extra = array();
        $resultado = $wh->es_subquery($campo, $columnas_extra);
        //print_r($resultado);exit;
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotTrue($resultado);

        errores::$error = false;

        $campo = 'a';
        $columnas_extra['a'] = '';
        $resultado = $wh->es_subquery($campo, $columnas_extra);
        //print_r($resultado);exit;
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);


        errores::$error = false;
    }

    public function test_filtro_especial_sql()
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);


        $filtro_especial = array();
        $filtro_especial[] = '';
        $resultado = $wh->filtro_especial_sql(array(), $filtro_especial);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error filtro debe ser un array filtro_especial[] = array()', $resultado['mensaje']);

        errores::$error = false;

        $filtro_especial = array();
        $filtro_especial[]['campo'] = array();
        $resultado = $wh->filtro_especial_sql(array(), $filtro_especial);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error filtro', $resultado['mensaje']);

        errores::$error = false;

        $filtro_especial = array();
        $filtro_especial[0]['x']['operador'] = 'x';
        $filtro_especial[0]['x']['valor'] = 'x';
        $resultado = $wh->filtro_especial_sql(array(), $filtro_especial);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;

    }

    public function test_filtro_extra_sql(): void
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);

        $filtro_extra = array();
        $resultado = $wh->filtro_extra_sql($filtro_extra);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);

        errores::$error = false;

        $filtro_extra = array();
        $filtro_extra[] = '';
        $resultado = $wh->filtro_extra_sql($filtro_extra);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $data_filtro debe ser un array', $resultado['mensaje']);

        errores::$error = false;

        $filtro_extra = array();
        $filtro_extra[] = array();
        $resultado = $wh->filtro_extra_sql($filtro_extra);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al generar filtro', $resultado['mensaje']);

        errores::$error = false;

        $filtro_extra = array();
        $filtro_extra[]['operador'] = 'a';
        $resultado = $wh->filtro_extra_sql($filtro_extra);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error al generar filtro', $resultado['mensaje']);

        errores::$error = false;

        $filtro_extra = array();
        $filtro_extra[0]['a']['operador'] = '=';
        $filtro_extra[0]['a']['valor'] = '1';
        $filtro_extra[0]['a']['comparacion'] = 'AND';
        $resultado = $wh->filtro_extra_sql($filtro_extra);
        //print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a = '1'", $resultado);
        errores::$error = false;




        $filtro_extra = array();
        $filtro_extra[0]['a']['operador'] = 'LIKE';
        $filtro_extra[0]['a']['valor'] = '%TXT%';
        $filtro_extra[0]['a']['comparacion'] = 'X';

        $filtro_extra[1]['a']['operador'] = 'LIKE';
        $filtro_extra[1]['a']['valor'] = '%TXT%';
        $filtro_extra[1]['a']['comparacion'] = 'AND';

        $filtro_extra[2]['a']['operador'] = 'LIKE';
        $filtro_extra[2]['a']['valor'] = '%TXT%';
        $filtro_extra[2]['a']['comparacion'] = 'OR';
        $resultado = $wh->filtro_extra_sql($filtro_extra);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a LIKE '%TXT%' AND a LIKE '%TXT%' OR a LIKE '%TXT%'", $resultado);
        errores::$error = false;

    }

    public function test_filtro_extra_sql_genera(): void
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $comparacion = '';
        $condicion = '';
        $filtro_extra_sql = '';
        $resultado = $wh->filtro_extra_sql_genera($comparacion, $condicion, $filtro_extra_sql);
        //print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);

        errores::$error = false;

        $comparacion = 'comparacion';
        $condicion = 'condicion';
        $filtro_extra_sql = '';
        $resultado = $wh->filtro_extra_sql_genera($comparacion, $condicion, $filtro_extra_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('condicion', $resultado);

        errores::$error = false;

        $comparacion = 'comparacion';
        $condicion = 'condicion';
        $filtro_extra_sql = 'fes';
        $resultado = $wh->filtro_extra_sql_genera($comparacion, $condicion, $filtro_extra_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('fes comparacion condicion', $resultado);

        errores::$error = false;

        $comparacion = 'comparacion';
        $condicion = 'condicion';
        $filtro_extra_sql = 'previo';
        $resultado = $wh->filtro_extra_sql_genera($comparacion, $condicion, $filtro_extra_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('previo comparacion condicion', $resultado);

        errores::$error = false;

    }


    public function test_filtro_fecha(): void
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);

        $filtro_fecha = array();
        $filtro_fecha[0]['campo_1'] = 'a';
        $filtro_fecha[0]['campo_2'] = 'a';
        $filtro_fecha[0]['fecha'] = '2020-01-01';
        $resultado = $wh->filtro_fecha($filtro_fecha);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("(('2020-01-01' >= a AND '2020-01-01' <= a))", $resultado);
        errores::$error = false;
    }

    public function test_filtro_fecha_base(): void
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $filtro_fecha = array();
        $filtro_fecha[0]['campo_1'] = 'a';
        $filtro_fecha[0]['campo_2'] = 'a';
        $filtro_fecha[0]['fecha'] = '2020-01-01';
        $resultado = $wh->filtro_fecha_base($filtro_fecha);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("('2020-01-01' >= a AND '2020-01-01' <= a)", $resultado);
        errores::$error = false;
    }

    public function test_filtro_rango_sql(): void
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);

        $filtro_rango = array();
        $resultado = $wh->filtro_rango_sql($filtro_rango);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $filtro_rango = array();
        $filtro_rango[] = '';
        $resultado = $wh->filtro_rango_sql($filtro_rango);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $filtro debe ser un array', $resultado['mensaje']);

        errores::$error = false;

        $filtro_rango = array();
        $filtro_rango[] = array();
        $resultado = $wh->filtro_rango_sql($filtro_rango);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $filtro[valor1] debe existir', $resultado['mensaje']);

        errores::$error = false;

        $filtro_rango = array();
        $filtro_rango[0]['valor1'] = 1;
        $filtro_rango[0]['valor2'] = 1;
        $resultado = $wh->filtro_rango_sql($filtro_rango);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error campo debe ser un string', $resultado['mensaje']);

        errores::$error = false;

        $filtro_rango = array();
        $filtro_rango['a']['valor1'] = 1;
        $filtro_rango['a']['valor2'] = 1;
        $resultado = $wh->filtro_rango_sql($filtro_rango);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a BETWEEN '1' AND '1'", $resultado);
        errores::$error = false;
    }

    public function test_genera_and()
    {

        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);
        $filtro = array();
        $columnas_extra = array();
        $resultado = $wh->genera_and($columnas_extra, $filtro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);

        errores::$error = false;

        $filtro[] = '';
        $resultado = $wh->genera_and($columnas_extra, $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Los key deben de ser campos asoci', $resultado['mensaje']);

        errores::$error = false;

        $filtro = array();
        $filtro['x'] = '';
        $resultado = $wh->genera_and($columnas_extra, $filtro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("x = ''", $resultado);
        errores::$error = false;
    }


    public function test_genera_and_textos()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $columnas_extra = array();
        $filtro = array();
        $resultado = $wh->genera_and_textos($columnas_extra, $filtro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);
        errores::$error = false;
    }

    public function test_genera_filtro_especial(): void
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $campo = '';
        $data_sql = '';
        $filtro_esp = array();
        $filtro_especial_sql = '';
        $resultado = $wh->genera_filtro_especial($campo, $data_sql, $filtro_esp, $filtro_especial_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);

        errores::$error = false;

        $campo = 'a';
        $data_sql = 'z';
        $filtro_esp = array();
        $filtro_especial_sql = 'a';
        $filtro_esp['a']['comparacion'] = 'b';
        $resultado = $wh->genera_filtro_especial($campo, $data_sql, $filtro_esp, $filtro_especial_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a b z", $resultado);
        errores::$error = false;
    }

    public function test_genera_filtro_rango_base()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $campo = '';
        $filtro_rango_sql = 'a';
        $filtro = array();
        $resultado = $wh->genera_filtro_rango_base($campo, $filtro, $filtro_rango_sql);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $campo no puede venir vacio', $resultado['mensaje']);

        errores::$error = false;
        $campo = 'a';
        $filtro_rango_sql = 'a';
        $filtro = array();
        $filtro['valor1'] = 1;
        $filtro['valor2'] = 1;
        $resultado = $wh->genera_filtro_rango_base($campo, $filtro, $filtro_rango_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a AND a BETWEEN '1' AND '1'", $resultado);
        errores::$error = false;
    }

    public function test_genera_in()
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);


        $in = array();
        $in['llave'] = 'a';
        $in['values'] = array('z');
        $resultado = $wh->genera_in($in);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a IN ('z')", $resultado);
        errores::$error = false;
    }

    public function test_genera_not_in(): void
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $not_in = array();
        $not_in['llave'] = 'a';
        $not_in['values'] = array('z', 'f');
        $resultado = $wh->genera_not_in($not_in);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a NOT IN ('z' ,'f')", $resultado);
        errores::$error = false;
    }

    public function test_genera_not_in_sql(): void
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);


        $not_in = array();
        $not_in['llave'] = 'a';
        $not_in['values'] = array('z', 'f', 'd');
        $resultado = $wh->genera_not_in_sql($not_in);


        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("a NOT IN ('z' ,'f' ,'d')", $resultado);
        errores::$error = false;
    }

    public function test_genera_sentencia_base()
    {
        errores::$error = false;
        $wh = new where();
        //$wh = new liberator($wh);


        $columnas_extra = array();
        $filtro = array();
        $tipo_filtro = 'numeros';
        $resultado = $wh->genera_sentencia_base($columnas_extra, $filtro, $tipo_filtro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);

    }

    public function test_genera_sql_filtro_fecha()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $filtro_fecha_sql = '';
        $fil_fecha = array();
        $fil_fecha['campo_1'] = 'a';
        $fil_fecha['campo_2'] = 'b';
        $fil_fecha['fecha'] = '2020-01-01';
        $resultado = $wh->genera_sql_filtro_fecha($fil_fecha, $filtro_fecha_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("('2020-01-01' >= a AND '2020-01-01' <= b)", $resultado);
        errores::$error = false;

        $filtro_fecha_sql = 's';
        $fil_fecha = array();
        $fil_fecha['campo_1'] = 'a';
        $fil_fecha['campo_2'] = 'b';
        $fil_fecha['fecha'] = '2020-01-01';
        $resultado = $wh->genera_sql_filtro_fecha($fil_fecha, $filtro_fecha_sql);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(" AND ('2020-01-01' >= a AND '2020-01-01' <= b)", $resultado);
        errores::$error = false;

    }

    public function test_in_sql()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $values = array();
        $values[] = '1';
        $values[] = '2';
        $llave = 'a';
        $resultado = $wh->in_sql($llave, $values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("a IN ('1' ,'2')", $resultado);
        errores::$error = false;

    }

    public function test_integra_filtro_extra()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $data_filtro = array();
        $data_filtro['z']['operador'] = 'operador';
        $data_filtro['z']['valor'] = 'valor';
        $data_filtro['z']['comparacion'] = 'comparacion';
        $filtro_extra_sql = '';
        $resultado = $wh->integra_filtro_extra($data_filtro, $filtro_extra_sql);
        // print_r($resultado);exit;
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("z operador 'valor'", $resultado);
        errores::$error = false;

        $data_filtro = array();
        $data_filtro['campo']['operador'] = 'LIKE';
        $data_filtro['campo']['valor'] = 'valor';
        $data_filtro['campo']['comparacion'] = 'comparacion';
        $filtro_extra_sql = 'X';
        $resultado = $wh->integra_filtro_extra($data_filtro, $filtro_extra_sql);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("X comparacion campo LIKE 'valor'", $resultado);
        errores::$error = false;




    }

    public function test_maqueta_filtro_especial(): void
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $campo = '';
        $filtro = array();
        $resultado = $wh->maqueta_filtro_especial($campo, array(), $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("Error al validar filtro", $resultado['mensaje']);

        errores::$error = false;

        $campo = 'a';
        $filtro = array();
        $filtro['a']['operador'] = 'b';
        $resultado = $wh->maqueta_filtro_especial($campo, array(), $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("Error al validar filtro", $resultado['mensaje']);

        errores::$error = false;

        $campo = 'a';
        $filtro = array();
        $filtro['a']['operador'] = 'b';
        $filtro['a']['valor'] = 'b';
        $resultado = $wh->maqueta_filtro_especial($campo, array(), $filtro);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("a b 'b'", $resultado);

        errores::$error = false;
    }

    public function test_not_in_sql()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $llave = '';
        $values[] = '';
        $resultado = $wh->not_in_sql($llave, $values);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("Error la llave esta vacia", $resultado['mensaje']);

        errores::$error = false;
        $values = array();
        $llave = 'z';
        $values[] = 'a';
        $resultado = $wh->not_in_sql($llave, $values);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("z NOT IN ('a')", $resultado);

        errores::$error = false;
        $values = array();
        $llave = 'z';
        $values[] = 'a';
        $values[] = 'b';
        $resultado = $wh->not_in_sql($llave, $values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("z NOT IN ('a' ,'b')", $resultado);
        errores::$error = false;
    }

    public function test_obten_filtro_especial()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $filtro_esp = array();
        $filtro_especial_sql = '';
        $resultado = $wh->obten_filtro_especial(array(), $filtro_esp, $filtro_especial_sql);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error en filtro', $resultado['mensaje']);

        errores::$error = false;

        $filtro_esp = array();
        $filtro_especial_sql = '';
        $filtro_esp['x']['operador'] = 'x';
        $filtro_esp['x']['valor'] = 'x';
        $resultado = $wh->obten_filtro_especial(array(), $filtro_esp, $filtro_especial_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

    public function test_setea_filtro_rango()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $condicion = '';
        $filtro_rango_sql = 'a';
        $resultado = $wh->setea_filtro_rango($condicion, $filtro_rango_sql);


        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error if filtro_rango tiene info $condicion no puede venir vacio', $resultado['mensaje']);

        errores::$error = false;

        $condicion = 'z';
        $filtro_rango_sql = 'a';
        $resultado = $wh->setea_filtro_rango($condicion, $filtro_rango_sql);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a AND z', $resultado);
        errores::$error = false;
    }

    public function test_sql_fecha()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $and = '';
        $data = new stdClass();
        $data->fecha = '2020-01-01';
        $data->campo_1 = 'a';
        $data->campo_2 = 'a';
        $resultado = $wh->sql_fecha($and, $data);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("('2020-01-01' >= a AND '2020-01-01' <= a)", $resultado);
        errores::$error = false;
    }

    public function test_valida_campo_filtro()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $campo = '';
        $campo_filtro = '';
        $filtro = array();
        $resultado = $wh->valida_campo_filtro($campo, $campo_filtro, $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error campo_filtro esta vacio', $resultado['mensaje_limpio']);
        errores::$error = false;

        $campo = '';
        $campo_filtro = 'a';
        $filtro = array();
        $resultado = $wh->valida_campo_filtro($campo, $campo_filtro, $filtro);

        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error campo esta vacio', $resultado['mensaje_limpio']);
        errores::$error = false;

        $campo = 'b';
        $campo_filtro = 'a';
        $filtro = array();
        $resultado = $wh->valida_campo_filtro($campo, $campo_filtro, $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error no existe $filtro[a]', $resultado['mensaje_limpio']);

        errores::$error = false;

        $campo = 'b';
        $campo_filtro = 'a';
        $filtro = array();
        $filtro['a'] = '';
        $resultado = $wh->valida_campo_filtro($campo, $campo_filtro, $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error no es un array $filtro[a]', $resultado['mensaje_limpio']);

        errores::$error = false;

        $campo = 'b';
        $campo_filtro = 'a';
        $filtro = array();
        $filtro['a'] = array();
        $resultado = $wh->valida_campo_filtro($campo, $campo_filtro, $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error no existe $filtro[a][operador]', $resultado['mensaje_limpio']);

        errores::$error = false;

        $campo = 'b';
        $campo_filtro = 'a';
        $filtro = array();
        $filtro['a']['operador'] = '';
        $resultado = $wh->valida_campo_filtro($campo, $campo_filtro, $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error no existe $filtro[a][valor]', $resultado['mensaje_limpio']);

        errores::$error = false;

        $campo = 'b';
        $campo_filtro = 'a';
        $filtro = array();
        $filtro['a']['operador'] = '';
        $filtro['a']['valor'] = '';
        $resultado = $wh->valida_campo_filtro($campo, $campo_filtro, $filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertEquals('Error esta vacio $filtro[a][operador]', $resultado['mensaje_limpio']);

        errores::$error = false;

        $campo = 'b';
        $campo_filtro = 'a';
        $filtro = array();
        $filtro['a']['operador'] = 'g';
        $filtro['a']['valor'] = '';
        $resultado = $wh->valida_campo_filtro($campo, $campo_filtro, $filtro);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;
    }

    public function test_valida_data_filtro_fecha()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $fil_fecha = array();
        $fil_fecha['campo_1'] = 'a';
        $fil_fecha['campo_2'] = array('z', 'f');
        $fil_fecha['fecha'] = '2019-01-01';
        $resultado = $wh->valida_data_filtro_fecha($fil_fecha);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;

    }

    public function test_valida_filtro_fecha(): void
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $fil_fecha = array();
        $fil_fecha['campo_1'] = 'a';
        $fil_fecha['campo_2'] = array('z', 'f');
        $fil_fecha['fecha'] = '2020-01-01';
        $resultado = $wh->valida_filtro_fecha($fil_fecha);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);


        errores::$error = false;
    }

    public function test_value()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $data = '';
        $resultado = $wh->value($data);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);

        errores::$error = false;
    }

    public function test_value_coma()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $value = '';
        $values_sql = '';
        $resultado = $wh->value_coma($value, $values_sql);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error value esta vacio', $resultado['mensaje']);

        errores::$error = false;

        $value = ' z   ';
        $values_sql = '';
        $resultado = $wh->value_coma($value, $values_sql);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('z', $resultado->value);
        $this->assertEquals('', $resultado->coma);

        errores::$error = false;

        $value = ' z   ';
        $values_sql = 'x';
        $resultado = $wh->value_coma($value, $values_sql);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('z', $resultado->value);
        $this->assertEquals(' ,', $resultado->coma);
        errores::$error = false;
    }

    public function test_values_sql_in()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);


        $values = array();
        $values[] = '';
        $resultado = $wh->values_sql_in($values);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error value esta vacio', $resultado['mensaje']);

        errores::$error = false;

        $values = array();
        $values[] = 'a';
        $resultado = $wh->values_sql_in($values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("'a'", $resultado);

        errores::$error = false;

        $values = array();
        $values[] = 'a';
        $values[] = 'b';
        $resultado = $wh->values_sql_in($values);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("'a' ,'b'", $resultado);
        errores::$error = false;
    }

    public function test_verifica_tipo_filtro()
    {
        errores::$error = false;
        $wh = new where();
        $wh = new liberator($wh);

        $tipo_filtro = '';
        $resultado = $wh->verifica_tipo_filtro(tipo_filtro: $tipo_filtro);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;
        $tipo_filtro = 'a';
        $resultado = $wh->verifica_tipo_filtro(tipo_filtro: $tipo_filtro);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error el tipo filtro no es correcto', $resultado['mensaje']);

        errores::$error = false;
        $tipo_filtro = 'textos';
        $resultado = $wh->verifica_tipo_filtro(tipo_filtro: $tipo_filtro);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;

    }


}