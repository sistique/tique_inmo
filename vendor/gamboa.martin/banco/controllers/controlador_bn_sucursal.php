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
use gamboamartin\banco\models\bn_sucursal;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\html\org_sucursal_html;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;

use gamboamartin\template\html;
use html\bn_cuenta_html;
use html\bn_empleado_html;
use html\bn_sucursal_html;


use html\bn_tipo_cuenta_html;
use html\bn_tipo_sucursal_html;
use PDO;
use stdClass;

class controlador_bn_sucursal extends _ctl_base {

    public string $link_bn_cuenta_alta_bd = '';
    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new bn_sucursal(link: $link);
        $html_ = new bn_sucursal_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['bn_sucursal_id']['titulo'] = 'Id';
        $datatables->columns['bn_sucursal_codigo']['titulo'] = 'Cod';
        $datatables->columns['bn_sucursal_descripcion']['titulo'] = 'Sucursal';
        $datatables->columns['bn_banco_descripcion']['titulo'] = 'Banco';
        $datatables->columns['bn_cuenta_n_cuentas']['titulo'] = 'N Cuentas';


        $datatables->filtro = array();
        $datatables->filtro[] = 'bn_sucursal.id';
        $datatables->filtro[] = 'bn_sucursal.codigo';
        $datatables->filtro[] = 'bn_sucursal.descripcion';
        $datatables->filtro[] = 'bn_sucursal.bn_banco_id';


        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Sucursal';

        $link_bn_cuenta_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'bn_cuenta');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_bn_cuenta_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_bn_cuenta_alta_bd = $link_bn_cuenta_alta_bd;

    }

    public function alta(bool $header, bool $ws = false): array|string
    {

        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'bn_tipo_sucursal_id',
            keys_selects: array(), id_selected: -1, label: 'Tipo Sucursal');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'bn_banco_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Banco');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 12;



        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }



        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion');
        $keys->selects = array();

        $init_data = array();
        $init_data['bn_tipo_sucursal'] = "gamboamartin\\banco";
        $init_data['bn_banco'] = "gamboamartin\\banco";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    protected function inputs_children(stdClass $registro): stdClass|array
    {
        $select_bn_tipo_cuenta_id = (new bn_tipo_cuenta_html(html: $this->html_base))->select_bn_tipo_cuenta_id(
            cols:12,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_bn_tipo_cuenta_id',data:  $select_bn_tipo_cuenta_id);
        }

        $select_org_sucursal_id = (new org_sucursal_html(html: $this->html_base))->select_org_sucursal_id(
            cols:12,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_org_sucursal_id',data:  $select_org_sucursal_id);
        }

        $select_bn_empleado_id = (new bn_empleado_html(html: $this->html_base))->select_bn_empleado_id(
            cols:12,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_bn_empleado_id',data:  $select_bn_empleado_id);
        }

        $select_bn_sucursal_id = (new bn_sucursal_html(html: $this->html_base))->select_bn_sucursal_id(
            cols:12,con_registros: true,id_selected: $registro->bn_sucursal_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_bn_sucursal_id',data:  $select_bn_sucursal_id);
        }

        $bn_cuenta_descripcion = (new bn_cuenta_html(html: $this->html_base))->input_descripcion(
            cols:12,row_upd:  new stdClass(), value_vacio: true, place_holder: 'Cuenta');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener bn_cuenta_descripcion',
                data:  $bn_cuenta_descripcion);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->bn_tipo_cuenta_id = $select_bn_tipo_cuenta_id;
        $this->inputs->select->org_sucursal_id = $select_org_sucursal_id;
        $this->inputs->select->bn_empleado_id = $select_bn_empleado_id;
        $this->inputs->select->bn_sucursal_id = $select_bn_sucursal_id;
        $this->inputs->bn_cuenta_descripcion = $bn_cuenta_descripcion;

        return $this->inputs;
    }


    protected function key_selects_txt(array $keys_selects): array
    {

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'codigo', keys_selects: $keys_selects, place_holder: 'Cod');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Sucursal');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }


        return $keys_selects;
    }

    public function modifica(
        bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }



        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'bn_tipo_sucursal_id',
            keys_selects: array(), id_selected: $this->registro['bn_tipo_sucursal_id'], label: 'Tipo Sucursal');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'bn_banco_id',
            keys_selects: $keys_selects, id_selected: $this->registro['bn_banco_id'], label: 'Banco');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 12;

        $keys_selects['codigo'] = new stdClass();
        $keys_selects['codigo']->disabled = true;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }




        return $r_modifica;
    }

    public function cuentas(bool $header = true, bool $ws = false): array|string
    {


        $data_view = new stdClass();
        $data_view->names = array('Id','Cod','Cuenta','Banco','Nombre Em','Ap Em','AM Em','Acciones');
        $data_view->keys_data = array('bn_cuenta_id', 'bn_cuenta_codigo','bn_cuenta_descripcion','bn_banco_descripcion','bn_empleado_nombre',
            'bn_empleado_ap','bn_empleado_am');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\banco\\models';
        $data_view->name_model_children = 'bn_cuenta';


        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__, not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }


        return $contenido_table;


    }




}
