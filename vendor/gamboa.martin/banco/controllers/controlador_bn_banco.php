<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\banco\controllers;

use gamboamartin\banco\models\bn_banco;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;

use gamboamartin\template\html;
use html\bn_banco_html;


use html\bn_sucursal_html;
use html\bn_tipo_sucursal_html;
use PDO;
use stdClass;

class controlador_bn_banco extends _ctl_base {

    public string $link_bn_sucursal_alta_bd = '';
    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new bn_banco(link: $link);
        $html_ = new bn_banco_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['bn_banco_id']['titulo'] = 'Id';
        $datatables->columns['bn_banco_codigo']['titulo'] = 'Cod';
        $datatables->columns['bn_banco_descripcion']['titulo'] = 'Banco';
        $datatables->columns['bn_banco_n_sucursales']['titulo'] = 'N Sucursales';

        $datatables->filtro = array();
        $datatables->filtro[] = 'bn_banco.id';
        $datatables->filtro[] = 'bn_banco.codigo';
        $datatables->filtro[] = 'bn_banco.descripcion';


        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Banco';

        $link_bn_sucursal_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'bn_sucursal');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_bn_sucursal_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_bn_sucursal_alta_bd = $link_bn_sucursal_alta_bd;

    }

    /**
     * @param bool $header
     * @param bool $ws
     * @return array|string
     */
    public function alta(bool $header, bool $ws = false): array|string
    {

        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'bn_tipo_banco_id',
            keys_selects: array(), id_selected: -1, label: 'Tipo Banco');
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

    /**
     * Initializes the campos_view array.
     *
     * @return array The initialized campos_view array.
     */
    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion');
        $keys->selects = array();

        $init_data = array();
        $init_data['bn_tipo_banco'] = "gamboamartin\\banco";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    protected function inputs_children(stdClass $registro): stdClass|array
    {
        $select_bn_tipo_sucursal_id = (new bn_tipo_sucursal_html(html: $this->html_base))->select_bn_tipo_sucursal_id(
            cols:6,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_bn_tipo_sucursal_id',data:  $select_bn_tipo_sucursal_id);
        }

        $select_bn_banco_id = (new bn_banco_html(html: $this->html_base))->select_bn_banco_id(
            cols:6,con_registros: true,id_selected: $registro->bn_banco_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_bn_tipo_sucursal_id',data:  $select_bn_banco_id);
        }

        $bn_sucursal_descripcion = (new bn_sucursal_html(html: $this->html_base))->input_descripcion(
            cols:12,row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Sucursal');
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener bn_banco_descripcion',data:  $bn_sucursal_descripcion);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->bn_tipo_sucursal_id = $select_bn_tipo_sucursal_id;
        $this->inputs->select->bn_banco_id = $select_bn_banco_id;
        $this->inputs->bn_sucursal_descripcion = $bn_sucursal_descripcion;

        return $this->inputs;
    }


    protected function key_selects_txt(array $keys_selects): array
    {

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'codigo', keys_selects: $keys_selects, place_holder: 'Cod');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Banco');
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


        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'bn_tipo_banco_id',
            keys_selects: array(), id_selected: $this->registro['bn_tipo_banco_id'], label: 'Tipo Banco');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 6;

        $keys_selects['codigo'] = new stdClass();
        $keys_selects['codigo']->disabled = true;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }




        return $r_modifica;
    }

    public function sucursales(bool $header = true, bool $ws = false): array|string
    {


        $data_view = new stdClass();
        $data_view->names = array('Id','Cod','Sucursal','Tipo Sucursal','Acciones');
        $data_view->keys_data = array('bn_sucursal_id', 'bn_sucursal_codigo','bn_sucursal_descripcion','bn_tipo_sucursal_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\banco\\models';
        $data_view->name_model_children = 'bn_sucursal';


        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__, not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }


        return $contenido_table;


    }




}
