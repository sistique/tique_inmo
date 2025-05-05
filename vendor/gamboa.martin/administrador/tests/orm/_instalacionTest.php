<?php
namespace gamboamartin\administrador\tests\base\controller\orm;

use base\orm\_base;
use gamboamartin\administrador\instalacion\instalacion;
use gamboamartin\administrador\models\_base_accion;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\administrador\models\adm_accion;
use gamboamartin\administrador\models\adm_accion_grupo;
use gamboamartin\administrador\models\adm_campo;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\administrador\models\adm_session;
use gamboamartin\administrador\models\adm_tipo_dato;
use gamboamartin\errores\errores;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use stdClass;


class _instalacionTest extends test {
    public errores $errores;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
    }

    public function test_add_campo_final(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create',data:  $create);
            print_r($error);
            exit;
        }

        $ins = new liberator($ins);

        $adds = array();
        $atributos = new stdClass();
        $campo = 'a';
        $existe_campo = false;
        $table = 'z';
        $valida_pep_8 = true;


        $resultado = $ins->add_campo_final($adds, $atributos, $campo, $existe_campo, $table, $valida_pep_8);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("ALTER TABLE z ADD a VARCHAR (255)  NOT NULL;",$resultado[0]->sql);


        errores::$error = false;


        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }
        errores::$error = false;

    }
    public function test_add_existente(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create',data:  $create);
            print_r($error);
            exit;
        }

        $ins = new liberator($ins);

        $adds = array();
        $atributos = new stdClass();
        $campo = 'a';
        $campos_origen[0]['Field'] = 'a';
        $table = 'z';


        $resultado = $ins->add_existente($adds, $atributos, $campo, $campos_origen, $table, true);
       // print_r($resultado);exit;
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;

        $adds = array();
        $atributos = new stdClass();
        $atributos->tipo_dato = 'BIGINT';
        $campo = 'a';
        $campos_origen[0]['Field'] = 'a';
        $table = 'z';


        $resultado = $ins->add_existente($adds, $atributos, $campo, $campos_origen, $table, true);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        //$this->assertNotTrue("ALTER TABLE z MODIFY COLUMN a BIGINT (100);",$resultado->sql);
        errores::$error = false;

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }

    }
    public function test_add_unique_base(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create',data:  $create);
            print_r($error);
            exit;
        }
        $add = $ins->add_colum('a', 'z','varchar');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al add',data:  $add);
            print_r($error);
            exit;
        }
        //$ins = new liberator($ins);

        $campo = 'a';
        $table = 'z';

        $resultado = $ins->add_unique_base($campo, $table);
        $this->assertEquals('CREATE UNIQUE INDEX z_unique_a  ON z (a);',$resultado->sql);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }


    }
    public function test_add_uniques_base(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create',data:  $create);
            print_r($error);
            exit;
        }
        $add = $ins->add_colum('a', 'z','varchar');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al add',data:  $add);
            print_r($error);
            exit;
        }
        //$ins = new liberator($ins);

        $table = 'z';
        $campos_por_integrar = new stdClass();
        $campos_por_integrar->a = new stdClass();
        $campos_por_integrar->a->unique = true;
        $resultado = $ins->add_uniques_base($campos_por_integrar, $table);
        //print_r($resultado);exit;
        $this->assertEquals('CREATE UNIQUE INDEX z_unique_a  ON z (a);',$resultado[0]->sql);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }


    }

    public function test_adds(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create',data:  $create);
            print_r($error);
            exit;
        }

        $campos = new stdClass();
        $campos->a = new stdClass();
        $campos_origen = array();
        $table = 'z';
        $resultado = $ins->adds($campos, $campos_origen, $table);

        $this->assertEquals('ALTER TABLE z ADD a VARCHAR (255)  NOT NULL;',$resultado[0]->sql);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;

        $drop = $ins->drop_table_segura('z');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }


    }
    public function test_ajusta_atributos(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $atributos = new stdClass();
        $resultado = $ins->ajusta_atributos($atributos);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('VARCHAR',$resultado->tipo_dato);
        $this->assertEmpty($resultado->default);
        $this->assertEquals('255',$resultado->longitud);
        $this->assertTrue($resultado->not_null);


        errores::$error = false;



    }
    public function test_ajusta_tipo_dato(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $atributos = new stdClass();
        $atributos->tipo_dato = 'a';
        $resultado = $ins->ajusta_tipo_dato($atributos);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('A',$resultado->tipo_dato);

        errores::$error = false;
    }
    public function test_campo_double(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);

        $campos = new stdClass();
        $name_campo = 'a';
        $resultado = $ins->campo_double($campos, $name_campo);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('double',$resultado->a->tipo_dato);
        $this->assertEquals('0',$resultado->a->default);
        $this->assertEquals('100,2',$resultado->a->longitud);

        errores::$error = false;
    }
    public function test_campos_double_default(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);

        $campos = new stdClass();
        $name_campos = array();
        $name_campos[] = 'a';
        $resultado = $ins->campos_double_default($campos, $name_campos);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('double',$resultado->a->tipo_dato);
        $this->assertEquals('0',$resultado->a->default);
        $this->assertEquals('100,2',$resultado->a->longitud);

        errores::$error = false;
    }
    public function test_campos_double(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);

        $campos = new stdClass();
        $campos_new = array();
        $resultado = $ins->campos_double($campos, $campos_new);
        //print_r($resultado);exit;

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $campos = new stdClass();
        $campos_new = array();
        $campos_new[] = 'a';
        $resultado = $ins->campos_double($campos, $campos_new);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('double',$resultado->a->tipo_dato);
        $this->assertEquals(0.0,$resultado->a->default);
        $this->assertEquals('100,2',$resultado->a->longitud);

    }
    public function test_campos_origen(): void
    {

        errores::$error = false;

        $create = (new _instalacion(link: $this->link))->create_table_new('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }

        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $table = 'a';
        $resultado = $ins->campos_origen($table);
       // print_r($resultado);exit;

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('bigint',$resultado[0]['Type']);


        errores::$error = false;
    }
    public function test_create_table(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);

        $drop = (new _instalacion(link: $this->link))->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $campos = new stdClass();
        $table = 'a';
        $resultado = $ins->create_table($campos, $table);
        //print_r($resultado);exit;
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsString("codigo VARCHAR (255) NOT NULL , descripcion VARCHAR (255) NOT NULL , status VARCHAR (255) NOT NU",$resultado->data_sql->sql);


        errores::$error = false;
        $drop = (new _instalacion(link: $this->link))->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop entidad',data:  $drop);
            print_r($error);
            exit;
        }

    }
    public function test_create_table_new(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);

        $drop = (new _instalacion(link: $this->link))->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop entidad',data:  $drop);
            print_r($error);
            exit;
        }


        $table = 'a';
        $resultado = $ins->create_table_new($table);
        //print_r($resultado);exit;
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsString("codigo VARCHAR (255) NOT NULL , descripcion VARCHAR (255) NOT NULL , status VARCHAR (255) NOT NU",$resultado->data_sql->sql);


        errores::$error = false;
        $drop = (new _instalacion(link: $this->link))->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop entidad',data:  $drop);
            print_r($error);
            exit;
        }

    }
    public function test_default(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $atributos = new stdClass();
        $resultado = $ins->default($atributos);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('',$resultado);

        errores::$error = false;

        $atributos = new stdClass();
        $atributos->default = 'a';
        $resultado = $ins->default($atributos);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado);
    }
    public function test_describe_table(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);

        $drop = (new _instalacion(link: $this->link))->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop entidad',data:  $drop);
            print_r($error);
            exit;
        }
        $create = (new _instalacion(link: $this->link))->create_table_new('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create entidad',data:  $create);
            print_r($error);
            exit;
        }

        $ins = new liberator($ins);

        $table = 'a';
        $resultado = $ins->describe_table($table);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $drop = (new _instalacion(link: $this->link))->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop entidad',data:  $drop);
            print_r($error);
            exit;
        }
    }
    public function test_exe_foreign_key(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $del = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop table',data:  $del);
            print_r($error);
            exit;
        }
        $del = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop table',data:  $del);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear table',data:  $create);
            print_r($error);
            exit;
        }
        $add = $ins->add_colum('b_id','a','BIGINT');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al add table',data:  $add);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear table',data:  $create);
            print_r($error);
            exit;
        }

        $existe_foreign = false;
        $name_indice_opt = '';
        $relacion_table = 'b';
        $table = 'a';
        $resultado = $ins->exe_foreign_key($existe_foreign, $name_indice_opt, $relacion_table, $table);
        //print_r($resultado);exit;
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("ALTER TABLE a ADD CONSTRAINT a_b_id FOREIGN KEY (b_id) REFERENCES b(id);",$resultado->sql);
        errores::$error = false;
        $del = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop table',data:  $del);
            print_r($error);
            exit;
        }
        $del = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop table',data:  $del);
            print_r($error);
            exit;
        }


    }
    public function test_existe_campo_origen(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $campo_integrar = 'a';
        $campos_origen = array();
        $resultado = $ins->existe_campo_origen($campo_integrar, $campos_origen);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotTrue($resultado);
        errores::$error = false;

        $campo_integrar = 'a';
        $campos_origen[]['Field'] = 'a';
        $resultado = $ins->existe_campo_origen($campo_integrar, $campos_origen);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;

    }
    public function test_existe_foreign(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $name_indice_opt = '';
        $relacion_table = 'b';
        $table = 'a';

        $resultado = $ins->existe_foreign($name_indice_opt, $relacion_table, $table);
        //print_r($resultado);exit;

        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotTrue($resultado);
        errores::$error = false;


    }
    public function test_existe_foreign_base(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $datas_index = new stdClass();
        $datas_index->name_indice = 'a';
        $datas_index->indices = array();
        $datas_index->indices[0] = new stdClass();
        $datas_index->indices[0]->nombre_indice = 'a';
        $resultado = $ins->existe_foreign_base($datas_index);
        //print_r($resultado);exit;

        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;


    }
    public function test_existe_indice_by_name(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);

        $table = 'adm_seccion';
        $name_index = 'PRIMARY';
        $resultado = $ins->existe_indice_by_name(name_index: $name_index,table: $table);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);


        errores::$error = false;
    }
    public function test_foraneas(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);



        $drop = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear drop',data:  $drop);
            print_r($error);
            exit;
        }

        $drop = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear drop',data:  $drop);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new('origen');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear create',data:  $create);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }
        $campo = 'b_id';
        $add_campo = $ins->add_colum($campo, 'a', 'bigint');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear add_campo',data:  $add_campo);
            print_r($error);
            exit;
        }

        $foraneas = array();
        $foraneas['b'] = '';
        $table = 'a';
        $resultado = $ins->foraneas($foraneas, $table);
        //print_r($resultado);exit;

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("ALTER TABLE a ADD CONSTRAINT a_b_id FOREIGN KEY (b_id) REFERENCES b(id);", $resultado[0]->sql);

        errores::$error = false;


    }
    public function test_foreign_key_completo(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);

        $drop = $ins->drop_table_segura('test');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $drop = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $drop = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $drop = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $table = 'b';


        $drop = $ins->drop_table_segura($table);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $campos = new stdClass();
        $create = $ins->create_table_new('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }
        $campo = 'a_id';
        $add_campo = $ins->add_colum($campo, $table, 'bigint');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear add_campo',data:  $add_campo);
            print_r($error);
            exit;
        }


        $campo = 'a';
        $table = 'b';
        $resultado = $ins->foreign_key_completo($campo, $table);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("ALTER TABLE b ADD CONSTRAINT b_a_id FOREIGN KEY (a_id) REFERENCES a(id);", $resultado->sql);

        errores::$error = false;

        $drop = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $drop = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }
    }
    public function test_foreign_no_conf(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $drop = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }

        $add = $ins->add_colum('a_id','b', 'bigint');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear campo',data:  $add);
            print_r($error);
            exit;
        }

        $campo = 'a';
        $campo_origen = array();
        $table = 'b';
        $resultado = $ins->foreign_no_conf(campo: $campo, campo_origen: $campo_origen, name_indice_opt: 'b__a_id', table: $table);
        //print_r($resultado);exit;

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("ALTER TABLE b ADD CONSTRAINT b__a_id FOREIGN KEY (a_id) REFERENCES a(id);", $resultado->sql);

        errores::$error = false;

        $drop = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $drop = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }
    }
    public function test_foreign_no_conf_integra()
    {
        $ins = new _instalacion(link: $this->link);
        $drop = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }
        $create = $ins->create_table_new('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }

        $add = $ins->add_colum('a_id','b', 'bigint');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear campo',data:  $add);
            print_r($error);
            exit;
        }

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $campo = 'a';
        $campos_origen = array();
        $table = 'b';
        $resultado = $ins->foreign_no_conf_integra(campo: $campo, campos_origen: $campos_origen, name_indice_opt: '', table: $table);
        //print_r($resultado);exit;
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEmpty($resultado);

        errores::$error = false;

        $campo = 'a';
        $campos_origen = array();
        $table = 'b';
        $campos_origen[]['Field'] = 'b';
        $resultado = $ins->foreign_no_conf_integra(campo: $campo, campos_origen: $campos_origen, name_indice_opt: '', table: $table);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEmpty($resultado);

        errores::$error = false;

        $campo = 'a';
        $campos_origen = array();
        $table = 'b';
        $campos_origen[]['Field'] = 'a';
        $resultado = $ins->foreign_no_conf_integra(campo: $campo, campos_origen: $campos_origen, name_indice_opt: 'b__a_id', table: $table);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("ALTER TABLE b ADD CONSTRAINT b__a_id FOREIGN KEY (a_id) REFERENCES a(id);", $resultado[0]->sql);

        errores::$error = false;

        $drop = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $drop = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

    }
    public function test_foreign_por_campo(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);



        $drop = $ins->drop_table_segura('test');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $table = 'b';

        $drop = $ins->drop_table_segura($table);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }

        $campos = new stdClass();

        $create = $ins->create_table_new('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }

        $create = $ins->create_table($campos, $table);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear entidad',data:  $create);
            print_r($error);
            exit;
        }
        $campo = 'a_id';
        $add_campo = $ins->add_colum($campo, $table, 'bigint');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al crear add_campo',data:  $add_campo);
            print_r($error);
            exit;
        }


        $resultado = $ins->foreign_por_campo(campo: $campo, es_renombrada: false, key_renombrada: '', referencia: '',
            table: $table, name_indice_opt: '');

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("ALTER TABLE b ADD CONSTRAINT b_a_id FOREIGN KEY (a_id) REFERENCES a(id);", $resultado->sql);

        errores::$error = false;

        $drop = $ins->drop_table_segura('b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }
        $drop = $ins->drop_table_segura('a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar entidad',data:  $drop);
            print_r($error);
            exit;
        }
    }
    public function test_get_data_indices(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $name_indice_opt = '';
        $relacion_table = 'b';
        $table = 'a';
        $resultado = $ins->get_data_indices($name_indice_opt, $relacion_table, $table);
        //print_r($resultado);exit;

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

    }
    public function test_get_foraneas(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $table = 'adm_accion';
        $resultado = $ins->get_foraneas($table);
        //print_r($resultado);exit;
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("administrador", $resultado[0]->nombre_database);
        $this->assertEquals("adm_accion_ibfk_1", $resultado[0]->nombre_indice);
        $this->assertEquals("adm_accion", $resultado[0]->nombre_tabla);
        $this->assertEquals("adm_seccion_id", $resultado[0]->columna_foranea);
        $this->assertEquals("adm_seccion", $resultado[0]->nombre_tabla_relacion);
        $this->assertEquals("id", $resultado[0]->nombre_columna_relacion);

        errores::$error = false;

    }
    public function test_inserta_adm_campos(): void
    {
        $_SESSION['usuario_id'] = 2;
        $ins = new _instalacion(link: $this->link);

        $exe = (new instalacion())->instala(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al instalar',data:  $exe);
            print_r($error);
            exit;
        }

        $modelo_integracion = new adm_session(link: $this->link);
        $resultado = $ins->inserta_adm_campos($modelo_integracion);
        //print_r($resultado);exit;
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;



    }
    public function test_integra_fc_no_conf(): void
    {
        $ins = new _instalacion(link: $this->link);

        $drop = $ins->drop_table_segura(table: 'b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }
        $drop = $ins->drop_table_segura(table: 'a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new(table: 'a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create',data:  $create);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new(table: 'b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create',data:  $create);
            print_r($error);
            exit;
        }
        $add = $ins->add_colum(campo: 'a_id',table: 'b',tipo_dato: 'BIGINT');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al add',data:  $add);
            print_r($error);
            exit;
        }

        errores::$error = false;

        $ins = new liberator($ins);

        $campo = '';
        $campo_origen = array();
        $campo_origen['Field'] = 'a';
        $fks = array();
        $name_indice_opt = '';
        $table = 'v';
        $resultado = $ins->integra_fc_no_conf($campo, $campo_origen, $fks, $name_indice_opt, $table);
        //print_r($resultado);exit;

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotTrue($resultado->break);

        errores::$error = false;

        $campo = 'a';
        $campo_origen = array();
        $campo_origen['Field'] = 'a';
        $fks = array();
        $name_indice_opt = '';
        $table = 'b';
        $resultado = $ins->integra_fc_no_conf($campo, $campo_origen, $fks, $name_indice_opt, $table);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado->break);

        $drop = $ins->drop_table_segura(table: 'b');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }
        $drop = $ins->drop_table_segura(table: 'a');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }

    }
    public function test_longitud(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);


        $atributos = new stdClass();
        $tipo_dato = '';
        $resultado = $ins->longitud($atributos, $tipo_dato);
        //($resultado);exit;

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("255", $resultado);

        errores::$error = false;

        $atributos = new stdClass();
        $tipo_dato = 'DOUBLE';
        $resultado = $ins->longitud($atributos, $tipo_dato);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("100,4", $resultado);

        errores::$error = false;

        $atributos = new stdClass();
        $tipo_dato = 'TIMESTAMP';
        $resultado = $ins->longitud($atributos, $tipo_dato);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);
        errores::$error = false;
    }
    public function test_modifica_columna(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
       // $ins = new liberator($ins);
        $table = 'b';

        $drop = $ins->drop_table_segura(table: $table);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }

        $create = $ins->create_table_new(table: $table);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al create',data:  $create);
            print_r($error);
            exit;
        }

        $campo = 'a';
        $add = $ins->add_colum($campo, $table, 'varchar');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al add',data:  $add);
            print_r($error);
            exit;
        }


        $longitud = '';

        $tipo_dato = 'int';
        $resultado = $ins->modifica_columna($campo, $longitud, $table, $tipo_dato);

        //print_r($resultado);exit;

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("ALTER TABLE b MODIFY COLUMN a int ;", $resultado->sql);

        errores::$error = false;

        $drop = $ins->drop_table_segura(table: $table);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al drop',data:  $drop);
            print_r($error);
            exit;
        }

    }
    public function test_not_null(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);


        $atributos = new stdClass();
        $resultado = $ins->not_null($atributos);

        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue( $resultado);

        errores::$error = false;
    }
    public function test_tipo_dato(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $atributos = new stdClass();
        $resultado = $ins->tipo_dato($atributos);
       // print_r($resultado);exit;

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('VARCHAR', $resultado);

        errores::$error = false;
    }

    public function test_tipo_dato_original(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        $ins = new liberator($ins);

        $columna = array();
        $columna['Type'] = 'INT(11)';
        $resultado = $ins->tipo_dato_original($columna);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('INT', $resultado);

        errores::$error = false;
    }
    public function test_ver_indices(): void
    {

        errores::$error = false;
        $ins = new _instalacion(link: $this->link);
        //$ins = new liberator($ins);

        $table = 'adm_seccion';
        $resultado = $ins->ver_indices($table);

        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('PRIMARY', $resultado->registros[0]['Key_name']);

        errores::$error = false;
    }





}

