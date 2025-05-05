<?php
namespace gamboamartin\notificaciones\tests\orm;


use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\facturacion\tests\base_test;
use gamboamartin\facturacion\tests\base_test2;
use gamboamartin\notificaciones\controllers\_plantilla;
use gamboamartin\notificaciones\controllers\controlador_not_adjunto;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use gamboamartin\facturacion\models\fc_factura;


use stdClass;


class _plantillaTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/notificaciones/config/generales.php';
        $this->paths_conf->database = '/var/www/html/notificaciones/config/database.php';
        $this->paths_conf->views = '/var/www/html/notificaciones/config/views.php';
    }

    public function test_accesos_html(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'not_adjunto';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $obj = new _plantilla();
        $obj = new liberator($obj);
        $link_acceso = 'a';
        $password = 'b';
        $usuario = 'c';
        $resultado = $obj->accesos_html($link_acceso, $password, $usuario);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<br><b>a</b><br><br><b>Usuario:</b> c<br><br><b>Password:</b> b<br>",$resultado);

        errores::$error = false;
    }

    public function test_bienvenida(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'not_adjunto';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $obj = new _plantilla();
        //$obj = new liberator($obj);
        $dom_comercial = '';
        $link_acceso = 'https://link.com';
        $link_web_oficial = '';
        $nombre_comercial = '';
        $password = '*oi-*45Ijhg';
        $usuario = 'user.test';
        $resultado = $obj->bienvenida($dom_comercial, $link_acceso, $link_web_oficial, $nombre_comercial, $password, $usuario);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("Estimado cliente: <br><br>Reciba un cordial saludo, el presente documento es para poder dar inicio al proceso de implementación.<br><br>Por lo anterior, requerimos la siguiente documentación por parte de la empresa.<br><br><b>ACTA CONSITUTIVA DE LA EMPRESA </b><br><br><b>PODER DEL REPRESENTANTE LEGAL (En caso de existir un representante legal distinto al definido en el acta constitutiva, anexar el poder legal). </b><br><br><b>IDENTIFICACIÓN OFICIAL DEL REPRESENTANTE LEGAL</b><br><br><b>CONSTANCIA DE SITUACIÓN FISCAL  </b><br><br><b>COMPROBANTE DE DOMICILIO </b><br><br><b>ACUSE DE AFILIACIÓN AL IMSS, DE LOS COLABORADORES QUE SERAN INSCRITOS AL FONDO DE PENSIÓN</b><br><br>Dicha documentación es de suma importancia, pues es necesaria para la elaboración del contrato de prestación de servicio. Anexamos un ejemplo de contrato para la revisión de este.<br><br>Tambien te dejamos tus datos de accesos para subir dicha informacion: <br><br><br><br><br><b>https://link.com</b><br><br><b>Usuario:</b> user.test<br><br><b>Password:</b> *oi-*45Ijhg<br> <br><br> Quedamos a su disposicion para cualquier duda o aclaracion. <br>  <br>  <br> <br> <style> html {font-family: Arial, Helvetica, sans-serif;font-size: 12px; } li {} .pie {color: #0979AE;} </style>",$resultado);

        errores::$error = false;
    }








}

