<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\banco\controllers;

use gamboamartin\banco\models\bn_tipo_cuenta;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\html\org_sucursal_html;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\bn_cuenta_html;
use html\bn_empleado_html;
use html\bn_sucursal_html;
use html\bn_tipo_cuenta_html;


use PDO;
use stdClass;

class controlador_bn_tipo_cuenta extends _ctl_parent_sin_codigo {

    public string $link_bn_cuenta_alta_bd = '';
    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new bn_tipo_cuenta(link: $link);
        $html_ = new bn_tipo_cuenta_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['bn_tipo_cuenta_id']['titulo'] = 'Id';
        $datatables->columns['bn_tipo_cuenta_codigo']['titulo'] = 'Cod';
        $datatables->columns['bn_tipo_cuenta_descripcion']['titulo'] = 'Tipo cuenta';
        $datatables->columns['bn_tipo_cuenta_n_cuentas']['titulo'] = 'N Cuentas';

        $datatables->filtro = array();
        $datatables->filtro[] = 'bn_tipo_cuenta.id';
        $datatables->filtro[] = 'bn_tipo_cuenta.codigo';
        $datatables->filtro[] = 'bn_tipo_cuenta.descripcion';


        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipo Cuenta';

        $link_bn_cuenta_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'bn_cuenta');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_bn_cuenta_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_bn_cuenta_alta_bd = $link_bn_cuenta_alta_bd;

    }

    public function cuentas(bool $header = true, bool $ws = false): array|string
    {


        $data_view = new stdClass();
        $data_view->names = array('Id','Cod','Cuenta','Acciones');
        $data_view->keys_data = array('bn_cuenta_id', 'bn_cuenta_codigo','bn_cuenta_descripcion');
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

    protected function inputs_children(stdClass $registro): stdClass|array
    {
        $select_bn_tipo_cuenta_id = (new bn_tipo_cuenta_html(html: $this->html_base))->select_bn_tipo_cuenta_id(
            cols:12,con_registros: true,id_selected:  $registro->bn_tipo_cuenta_id,link:  $this->link, disabled: true);
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_menu_id',data:  $select_bn_tipo_cuenta_id);
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
            cols:12,con_registros: true,id_selected:  -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_bn_sucursal_id',data:  $select_bn_sucursal_id);
        }


        $bn_cuenta_codigo = (new bn_cuenta_html(html: $this->html_base))->input_codigo(
            cols:6,row_upd:  new stdClass(),value_vacio:  false);
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener bn_cuenta_codigo',data:  $bn_cuenta_codigo);
        }

        $bn_cuenta_descripcion = (new bn_cuenta_html(html: $this->html_base))->input_descripcion(
            cols:12,row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Cuenta');
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener bn_cuenta_descripcion',data:  $bn_cuenta_descripcion);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->bn_tipo_cuenta_id = $select_bn_tipo_cuenta_id;
        $this->inputs->select->org_sucursal_id = $select_org_sucursal_id;
        $this->inputs->select->bn_empleado_id = $select_bn_empleado_id;
        $this->inputs->select->bn_sucursal_id = $select_bn_sucursal_id;
        $this->inputs->bn_cuenta_codigo = $bn_cuenta_codigo;
        $this->inputs->bn_cuenta_descripcion = $bn_cuenta_descripcion;

        return $this->inputs;
    }

    /**
     * Ajusta los parametros de los inputs para upd y alta
     * @param array $keys_selects Parametros precargados
     * @return array
     * @version 0.30.5
     */
    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Tipo Cuenta');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }



}
