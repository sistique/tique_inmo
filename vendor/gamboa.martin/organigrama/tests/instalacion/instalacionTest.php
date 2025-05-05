<?php
namespace tests\orm;

use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\instalacion\instalacion;
use gamboamartin\organigrama\tests\base_test;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use gamboamartin\organigrama\models\limpieza;
use JsonException;
use stdClass;


class instalacionTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/cat_sat/config/generales.php';
        $this->paths_conf->database = '/var/www/html/cat_sat/config/database.php';
        $this->paths_conf->views = '/var/www/html/cat_sat/config/views.php';


    }

    /**
     * @throws JsonException
     */
    public function test_add_org_logo(): void
    {
        errores::$error = false;

        $instalacion = new instalacion();
        $instalacion = new liberator($instalacion);

       $drop = (new _instalacion(link: $this->link))->drop_table_segura(table: 'org_logo');
       if(errores::$error){
           $error = (new errores())->error(mensaje: 'Error al eliminar logo',data: $drop);
           print_r($error);
           exit;
       }
        $resultado = $instalacion->_add_org_logo(link: $this->link);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("CREATE TABLE org_logo (
                    id bigint NOT NULL AUTO_INCREMENT,
                    codigo VARCHAR (255) NOT NULL , descripcion VARCHAR (255) NOT NULL , status VARCHAR (255) NOT NULL DEFAULT 'activo', usuario_alta_id INT (255) NOT NULL , usuario_update_id INT (255) NOT NULL , fecha_alta TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP, fecha_update TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, descripcion_select VARCHAR (255) NOT NULL , alias VARCHAR (255) NOT NULL , codigo_bis VARCHAR (255) NOT NULL , predeterminado VARCHAR (255) NOT NULL DEFAULT 'inactivo', 
                    PRIMARY KEY (id) 
                   
                    );",$resultado->create->data_sql->sql);
        errores::$error = false;

        $resultado = $instalacion->_add_org_logo(link: $this->link);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("Ya existe tabla org_logo",$resultado->create);

        errores::$error = false;

        $resultado = (new _instalacion(link: $this->link))->describe_table('org_logo');
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("id",$resultado->registros[0]['Field']);
        $this->assertEquals("codigo",$resultado->registros[1]['Field']);
        $this->assertEquals("descripcion",$resultado->registros[2]['Field']);
        $this->assertEquals("status",$resultado->registros[3]['Field']);
        $this->assertEquals("usuario_alta_id",$resultado->registros[4]['Field']);
        $this->assertEquals("usuario_update_id",$resultado->registros[5]['Field']);
        $this->assertEquals("fecha_alta",$resultado->registros[6]['Field']);
        $this->assertEquals("fecha_update",$resultado->registros[7]['Field']);
        $this->assertEquals("descripcion_select",$resultado->registros[8]['Field']);
        $this->assertEquals("alias",$resultado->registros[9]['Field']);
        $this->assertEquals("codigo_bis",$resultado->registros[10]['Field']);
        $this->assertEquals("predeterminado",$resultado->registros[11]['Field']);
        $this->assertEquals("org_empresa_id",$resultado->registros[12]['Field']);
        $this->assertEquals("doc_documento_id",$resultado->registros[13]['Field']);
        $this->assertEquals("bigint",$resultado->registros[12]['Type']);
        $this->assertEquals("MUL",$resultado->registros[12]['Key']);

        $this->assertEquals("bigint",$resultado->registros[13]['Type']);
        $this->assertEquals("MUL",$resultado->registros[13]['Key']);


        errores::$error = false;
    }



}

