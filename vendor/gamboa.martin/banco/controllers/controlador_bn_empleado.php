<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\banco\controllers;

use gamboamartin\banco\models\bn_empleado;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\bn_empleado_html;


use PDO;
use stdClass;

class controlador_bn_empleado extends _ctl_parent_sin_codigo {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new bn_empleado(link: $link);
        $html_ = new bn_empleado_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['bn_empleado_id']['titulo'] = 'Id';
        $datatables->columns['bn_empleado_nombre']['titulo'] = 'Nombre';
        $datatables->columns['bn_empleado_ap']['titulo'] = 'AP';
        $datatables->columns['bn_empleado_am']['titulo'] = 'AM';
        $datatables->columns['org_puesto_descripcion']['titulo'] = 'Puesto';
        $datatables->columns['org_departamento_descripcion']['titulo'] = 'Depto';
        $datatables->columns['org_empresa_razon_social']['titulo'] = 'Empresa';

        $datatables->filtro = array();
        $datatables->filtro[] = 'bn_empleado.id';
        $datatables->filtro[] = 'bn_empleado.nombre';
        $datatables->filtro[] = 'bn_empleado.ap';
        $datatables->filtro[] = 'bn_empleado.am';
        $datatables->filtro[] = 'org_puesto.descripcion';
        $datatables->filtro[] = 'org_departamento.descripcion';
        $datatables->filtro[] = 'org_empresa.razon_social';


        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Empleados';


    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }


        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'org_puesto_id',
            keys_selects: array(), id_selected: -1, label: 'Puesto');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects['nombre'] = new stdClass();
        $keys_selects['nombre']->cols = 12;

        $keys_selects['ap'] = new stdClass();
        $keys_selects['ap']->cols = 12;

        $keys_selects['am'] = new stdClass();
        $keys_selects['am']->cols = 12;


        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }



        return $r_alta;
    }

    protected function campos_view(array $inputs = array()): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion','nombre','descripcion','ap','am');
        $keys->selects = array();

        $init_data = array();

        $init_data['org_puesto'] = "gamboamartin\\organigrama";

        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }


    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Tipo Banco');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'nombre', keys_selects:$keys_selects, place_holder: 'Nombre');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'ap', keys_selects:$keys_selects, place_holder: 'AP');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'am', keys_selects:$keys_selects, place_holder: 'AM');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

    public function modifica(bool $header, bool $ws = false, array $keys_selects = array()): array|stdClass
    {
        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }


        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'org_puesto_id',
            keys_selects: array(), id_selected: $this->registro['org_puesto_id'], label: 'Puesto');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }


        $keys_selects['nombre'] = new stdClass();
        $keys_selects['nombre']->cols = 12;

        $keys_selects['ap'] = new stdClass();
        $keys_selects['ap']->cols = 12;

        $keys_selects['am'] = new stdClass();
        $keys_selects['am']->cols = 12;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }




        return $r_modifica;
    }



}