<?php
namespace tests\controllers;

use DOMException;
use gamboamartin\errores\errores;
use gamboamartin\test\test;
use gamboamartin\xml_cfdi_4\xml;
use stdClass;

class xmlTest extends test {
    public errores $errores;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();

    }

    public function test_cfdi_comprobante(): void
    {
        errores::$error = false;

        $xml = new xml();
        //$modelo = new liberator($modelo);

        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'P';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';

        $resultado = $xml->cfdi_comprobante($comprobante);
        $resultado = $resultado->saveXML();

        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase('<?xml version="1.0" encoding="utf-8"?>', $resultado);
        $this->assertStringContainsStringIgnoringCase('<cfdi:Comprobante xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:pago20="http://www.sat.gob.mx/Pagos20"', $resultado);
        $this->assertStringContainsStringIgnoringCase('xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:pago20', $resultado);
        $this->assertStringContainsStringIgnoringCase('http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd" Moneda="XXX"', $resultado);
        $this->assertStringContainsStringIgnoringCase('Moneda="XXX" Total="0" Exportacion="01" TipoDeComprobante="P', $resultado);
        $this->assertStringContainsStringIgnoringCase('eda="XXX" Total="0" Exportacion="01" TipoDeComprobante="P" SubTotal="0" ', $resultado);
        $this->assertStringContainsStringIgnoringCase('" Folio="01" Version="4.0"/>', $resultado);

        errores::$error = false;

        $comprobante->serie = 'NCV4.0';

        $resultado = $xml->cfdi_comprobante($comprobante);
        $resultado = $resultado->saveXML();

        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);

        $this->assertStringContainsStringIgnoringCase('" Folio="01" Version="4.0" Serie="NCV4.0"/>', $resultado);

        errores::$error = false;
    }

    /**
     */
    public function test_cfdi_emisor(): void
    {
        errores::$error = false;

        $xml = new xml();
        //$modelo = new liberator($modelo);


        $emisor = new stdClass();
        $resultado = $xml->cfdi_emisor(emisor: $emisor);
        $this->assertTrue(errores::$error);
        $this->assertIsArray($resultado);
        $this->assertStringContainsStringIgnoringCase('Error al validar $emisor',$resultado['mensaje']);

        errores::$error = false;

        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'P';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';
        $comprobante = $xml->cfdi_comprobante($comprobante);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al generar comprobante', data: $comprobante);
            print_r($error);
            exit;
        }

        $emisor = new stdClass();

        $resultado = $xml->cfdi_emisor(emisor: $emisor);
        $this->assertTrue(errores::$error);
        $this->assertIsArray($resultado);
        $this->assertStringContainsStringIgnoringCase('Error al validar $emisor',$resultado['mensaje']);

        errores::$error = false;


        $emisor = new stdClass();
        $emisor->rfc = 'a';
        $emisor->nombre = 'a';
        $emisor->regimen_fiscal = 'a';

        $resultado = $xml->cfdi_emisor(emisor: $emisor);
        $resultado = $resultado->saveXML();
        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase('<?xml version="1.0" encoding="utf-8"?>',$resultado);
        $this->assertStringContainsStringIgnoringCase('<cfdi:Comprobante xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:pago20="http://www.sat.gob.mx/Pagos20"',$resultado);
        $this->assertStringContainsStringIgnoringCase('xsi:schemaLocation="http://www.sat.gob.mx/cfd/4',$resultado);
        $this->assertStringContainsStringIgnoringCase('cfdi:Emisor Rfc="a" Nombre="a" Regim',$resultado);
        $this->assertStringContainsStringIgnoringCase('a" RegimenFiscal="a"/></cfdi:Comprobante>',$resultado);
    }

    public function test_cfdi_conceptos(): void
    {
        errores::$error = false;

        $xml = new xml();
        //$modelo = new liberator($modelo);

        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'I';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';

        $conceptos = array();

        $comprobante = $xml->cfdi_comprobante($comprobante );
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al generar comprobante', data: $comprobante);
            print_r($error);
            exit;
        }

        $conceptos = array();
        $conceptos[0] = new stdClass();
        $conceptos[0]->clave_prod_serv = '1';
        $conceptos[0]->cantidad = '0';
        $conceptos[0]->descripcion = 'c';
        $conceptos[0]->valor_unitario = '0';
        $conceptos[0]->importe = '0';
        $conceptos[0]->objeto_imp = 'f';
        $conceptos[0]->no_identificacion = 'f';
        $conceptos[0]->clave_unidad = 'f';
        $conceptos[0]->impuestos = array();

        $conceptos[1] = new stdClass();
        $conceptos[1]->clave_prod_serv = '1';
        $conceptos[1]->cantidad = '0';
        $conceptos[1]->descripcion = 'c';
        $conceptos[1]->valor_unitario = '0';
        $conceptos[1]->importe = '0';
        $conceptos[1]->objeto_imp = 'f';
        $conceptos[1]->no_identificacion = 'f';
        $conceptos[1]->clave_unidad = 'f';
        $conceptos[1]->impuestos = array();



        $resultado = $xml->cfdi_conceptos($conceptos);

        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase('<cfdi:Comprobante xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',$resultado);
        $this->assertStringContainsStringIgnoringCase('xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd" M',$resultado);
        $this->assertStringContainsStringIgnoringCase('Moneda="XXX" Total="0" Exportacion="01" TipoDeComprobante="I" SubTotal="0"',$resultado);
        $this->assertStringContainsStringIgnoringCase('<cfdi:Concepto ClaveProdServ="1" NoIdentificacion="f" ',$resultado);
        $this->assertStringContainsStringIgnoringCase('Folio="01" Version="4.0"><cfdi:Conceptos>',$resultado);
        $this->assertStringContainsStringIgnoringCase('l="0" LugarExpedicion="44110" Fecha="2',$resultado);
        $this->assertStringContainsStringIgnoringCase('" Folio="01" Version="4.0"><cfdi:Co',$resultado);
        $this->assertStringContainsStringIgnoringCase('i:Conceptos><cfdi:Concepto ClaveProdServ="1" NoIdent',$resultado);
        $this->assertStringContainsStringIgnoringCase('NoIdentificacion="f" Cantidad="0" ClaveUnidad="f" Descripcion="c" ValorUnitario="0" Im',$resultado);
        $this->assertStringContainsStringIgnoringCase('nitario="0" Importe="0" ObjetoImp="f"/><cfdi:Concepto ClaveProdServ="1" NoIdent',$resultado);
        $this->assertStringContainsStringIgnoringCase('NoIdentificacion="f" Cantidad="0" ClaveUnidad="f" Descripcion="c" Valo',$resultado);
        $this->assertStringContainsStringIgnoringCase('ValorUnitario="0" Importe="0" ObjetoImp="f"/></cfdi:Co',$resultado);
        $this->assertStringContainsStringIgnoringCase('oImp="f"/></cfdi:Conceptos></cfdi:Comprobante>',$resultado);


        errores::$error = false;

        $xml = new xml();
        //$modelo = new liberator($modelo);

        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'I';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';


        $comprobante = $xml->cfdi_comprobante($comprobante );
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al generar comprobante', data: $comprobante);
            print_r($error);
            exit;
        }

        $conceptos = array();
        $conceptos[0] = new stdClass();
        $conceptos[0]->clave_prod_serv = '1';
        $conceptos[0]->cantidad = '0';
        $conceptos[0]->descripcion = 'c';
        $conceptos[0]->valor_unitario = '0';
        $conceptos[0]->importe = '0';
        $conceptos[0]->objeto_imp = 'f';
        $conceptos[0]->no_identificacion = 'f';
        $conceptos[0]->clave_unidad = 'f';
        $conceptos[0]->impuestos = array();
        $conceptos[0]->impuestos[0] = new stdClass();
        $conceptos[0]->impuestos[0]->traslados = array();
        $conceptos[0]->impuestos[0]->traslados[0] = new stdClass();
        $conceptos[0]->impuestos[0]->traslados[0]->base = '0';
        $conceptos[0]->impuestos[0]->traslados[0]->impuesto = 'b';
        $conceptos[0]->impuestos[0]->traslados[0]->tipo_factor = 'c';
        $conceptos[0]->impuestos[0]->traslados[0]->tasa_o_cuota = '1';
        $conceptos[0]->impuestos[0]->traslados[0]->importe = '2';


        $resultado = $xml->cfdi_conceptos($conceptos);
        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase('<cfdi:Comprobante xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cfdi="http://www.sat.gob.mx/cfd/4"',$resultado);
        $this->assertStringContainsStringIgnoringCase('Moneda="XXX" Total="0" Exportacion="01" TipoDeComproba',$resultado);
        $this->assertStringContainsStringIgnoringCase('Folio="01" Version="4.0"><cfdi:Conceptos><cfdi:Con',$resultado);
        $this->assertStringContainsStringIgnoringCase('f" Cantidad="0" ClaveUnidad="f" Descripcion="c" ValorUnitar',$resultado);
        $this->assertStringContainsStringIgnoringCase('di:Impuestos><cfdi:Traslados><cfdi:Traslado Base="0" Impuesto="b" TipoFactor="c" TasaOCuo',$resultado);

        errores::$error = false;
    }

    public function test_cfdi_impuestos(): void
    {
        errores::$error = false;

        $xml = new xml();
        //$modelo = new liberator($modelo);

        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'I';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';
        $comprobante = $xml->cfdi_comprobante($comprobante);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al generar comprobante', data: $comprobante);
            print_r($error);
            exit;
        }

        $impuestos = new stdClass();
        $impuestos->total_impuestos_trasladados = 'x';
        $impuestos->traslados = array();
        $impuestos->traslados[0] = new stdClass();
        $impuestos->traslados[0]->base = '0';
        $impuestos->traslados[0]->impuesto = '0';
        $impuestos->traslados[0]->tipo_factor = '0';
        $impuestos->traslados[0]->tasa_o_cuota = '0';
        $impuestos->traslados[0]->importe = '0';
        $resultado = $xml->cfdi_impuestos($impuestos);
        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase('<cfdi:Impuestos TotalImpuestosTrasladados="x"',$resultado);


        errores::$error = false;
        $xml = new xml();
        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'I';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';
        $comprobante = $xml->cfdi_comprobante($comprobante);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al generar comprobante', data: $comprobante);
            print_r($error);
            exit;
        }
        $impuestos = new stdClass();
        $resultado = $xml->cfdi_impuestos($impuestos);
        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase('<?xml version="1.0" encoding="utf-8"?>',$resultado);


        errores::$error = false;
        $xml = new xml();
        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'I';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';
        $comprobante = $xml->cfdi_comprobante($comprobante);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al generar comprobante', data: $comprobante);
            print_r($error);
            exit;
        }
        $impuestos = new stdClass();
        $impuestos->total_impuestos_retenidos = 'x';
        $impuestos->retenciones = array();
        $impuestos->retenciones[0] = new stdClass();
        $impuestos->retenciones[0]->base = '10';
        $impuestos->retenciones[0]->impuesto = '10';
        $impuestos->retenciones[0]->tipo_factor = '10';
        $impuestos->retenciones[0]->tasa_o_cuota = '10';
        $impuestos->retenciones[0]->importe = '10';

        $resultado = $xml->cfdi_impuestos($impuestos);

        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase('idos="x"><cfdi:Retenciones><cfdi:Retencio',$resultado);

        errores::$error = false;
        $xml = new xml();
        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'I';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';
        $comprobante = $xml->cfdi_comprobante($comprobante);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al generar comprobante', data: $comprobante);
            print_r($error);
            exit;
        }
        $impuestos = new stdClass();
        $impuestos->total_impuestos_retenidos = 'x';
        $impuestos->retenciones = array();
        $impuestos->retenciones[0] = new stdClass();
        $impuestos->retenciones[0]->base = '10';
        $impuestos->retenciones[0]->impuesto = '10';
        $impuestos->retenciones[0]->tipo_factor = '10';
        $impuestos->retenciones[0]->tasa_o_cuota = '10';
        $impuestos->retenciones[0]->importe = '10';

        $impuestos->total_impuestos_trasladados = 'x';
        $impuestos->traslados = array();
        $impuestos->traslados[0] = new stdClass();
        $impuestos->traslados[0]->base = '10';
        $impuestos->traslados[0]->impuesto = '10';
        $impuestos->traslados[0]->tipo_factor = '10';
        $impuestos->traslados[0]->tasa_o_cuota = '10';
        $impuestos->traslados[0]->importe = '10';

        $resultado = $xml->cfdi_impuestos($impuestos);
        //print_r($resultado);exit;
        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase(':Retencion Imp',$resultado);
        $this->assertStringContainsStringIgnoringCase('<cfdi:Traslados><cfdi:Traslado Base="10',$resultado);

        errores::$error = false;


    }

    /**
     */
    public function test_cfdi_receptor(): void
    {
        errores::$error = false;

        $xml = new xml();
        //$modelo = new liberator($modelo);

        $comprobante = new stdClass();
        $comprobante->tipo_de_comprobante = 'I';
        $comprobante->moneda = 'XXX';
        $comprobante->exportacion = '01';
        $comprobante->total = 0;
        $comprobante->sub_total = 0;
        $comprobante->lugar_expedicion = 44110;
        $comprobante->fecha = '2021-01-01';
        $comprobante->folio = '01';
        $comprobante = $xml->cfdi_comprobante($comprobante);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al generar comprobante', data: $comprobante);
            print_r($error);
            exit;
        }

        $receptor = new stdClass();
        $receptor->rfc = 'a';
        $receptor->nombre = 'b';
        $receptor->domicilio_fiscal_receptor = 'c';
        $receptor->regimen_fiscal_receptor = 'd';
        $receptor->uso_cfdi = 'f';
        $resultado = $xml->cfdi_receptor(receptor: $receptor);


        $this->assertNotTrue(errores::$error);
        $this->assertIsString($resultado);
        $this->assertStringContainsStringIgnoringCase('xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd"',$resultado);
        $this->assertStringContainsStringIgnoringCase('Exportacion="01" TipoDeComprobante="I"',$resultado);
        $this->assertStringContainsStringIgnoringCase('<cfdi:Receptor Rfc="a" Nombre="b"',$resultado);
        $this->assertStringContainsStringIgnoringCase('DomicilioFiscalReceptor="c" RegimenFis',$resultado);
        $this->assertStringContainsStringIgnoringCase('FiscalReceptor="d" UsoCfdi="f"/>',$resultado);
        $this->assertStringContainsString('FiscalReceptor="d" UsoCFDI="f"/>',$resultado);
    }

    public function test_cfdi_relacionados_json(): void
    {
        errores::$error = false;

        $xml = new xml();
        //$modelo = new liberator($modelo);

        $relacionados = array();
        $json = array();

        $relacionados['01'][] = 'a';
        $relacionados['02'][] = 'b';
        $relacionados['03'][] = '1';
        $relacionados['03'][] = '2';


        $resultado = $xml->cfdi_relacionados_json($relacionados, $json);
        $this->assertNotTrue(errores::$error);
        $this->assertIsArray($resultado);
        $this->assertEquals(1,$resultado['Comprobante']['CfdiRelacionados'][2]['CfdiRelacionado'][0]);
        errores::$error = false;
    }


}

