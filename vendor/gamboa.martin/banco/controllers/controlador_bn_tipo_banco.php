<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\banco\controllers;

use gamboamartin\banco\models\bn_tipo_banco;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\bn_banco_html;
use html\bn_tipo_banco_html;


use PDO;
use stdClass;

class controlador_bn_tipo_banco extends _ctl_parent_sin_codigo {

    public string $link_bn_banco_alta_bd = '';
    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new bn_tipo_banco(link: $link);
        $html_ = new bn_tipo_banco_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['bn_tipo_banco_id']['titulo'] = 'Id';
        $datatables->columns['bn_tipo_banco_codigo']['titulo'] = 'Cod';
        $datatables->columns['bn_tipo_banco_descripcion']['titulo'] = 'Tipo Banco';
        $datatables->columns['bn_tipo_banco_n_bancos']['titulo'] = 'N Bancos';

        $datatables->filtro = array();
        $datatables->filtro[] = 'bn_tipo_banco.id';
        $datatables->filtro[] = 'bn_tipo_banco.codigo';
        $datatables->filtro[] = 'bn_tipo_banco.descripcion';


        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipo Banco';

        $link_bn_banco_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'bn_banco');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_bn_banco_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_bn_banco_alta_bd = $link_bn_banco_alta_bd;

    }

    public function bancos(bool $header = true, bool $ws = false): array|string
    {


        $data_view = new stdClass();
        $data_view->names = array('Id','Cod','Banco','Acciones');
        $data_view->keys_data = array('bn_banco_id', 'bn_banco_codigo','bn_banco_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\banco\\models';
        $data_view->name_model_children = 'bn_banco';


        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__, not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }


        return $contenido_table;


    }

    protected function inputs_children(stdClass $registro): stdClass|array
    {
        $select_bn_tipo_banco_id = (new bn_tipo_banco_html(html: $this->html_base))->select_bn_tipo_banco_id(
            cols:12,con_registros: true,id_selected:  $registro->bn_tipo_banco_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_menu_id',data:  $select_bn_tipo_banco_id);
        }

        $bn_banco_codigo = (new bn_banco_html(html: $this->html_base))->input_codigo(
            cols:6,row_upd:  new stdClass(),value_vacio:  false);
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener bn_banco_codigo',data:  $bn_banco_codigo);
        }

        $bn_banco_descripcion = (new bn_banco_html(html: $this->html_base))->input_descripcion(
            cols:12,row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Banco');
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener bn_banco_descripcion',data:  $bn_banco_descripcion);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->bn_tipo_banco_id = $select_bn_tipo_banco_id;
        $this->inputs->bn_banco_codigo = $bn_banco_codigo;
        $this->inputs->bn_banco_descripcion = $bn_banco_descripcion;

        return $this->inputs;
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

        return $keys_selects;
    }



}