<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\acl\controllers;

use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_base;
use gamboamartin\template_1\html;
use html\adm_accion_html;
use html\adm_menu_html;
use html\adm_namespace_html;
use html\adm_seccion_html;
use links\secciones\link_adm_seccion;
use PDO;
use stdClass;


class controlador_adm_seccion extends _ctl_base {

    public string $link_adm_accion_alta_bd = '';

    public function __construct(PDO $link, html $html = new html(), array $datatables_custom_cols = array(),
                                array $datatables_custom_cols_omite = array(), stdClass $paths_conf = new stdClass()){
        $modelo = new adm_seccion(link: $link);

        $html_ = new adm_seccion_html(html: $html);
        $obj_link = new link_adm_seccion(link: $link, registro_id: $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_seccion_id']['titulo'] = 'Id';
        $datatables->columns['adm_seccion_descripcion']['titulo'] = 'Seccion';
        $datatables->columns['adm_menu_descripcion']['titulo'] = 'Menu';
        $datatables->columns['adm_namespace_descripcion']['titulo'] = 'Namespace';
        $datatables->columns['adm_seccion_n_acciones']['titulo'] = 'N Acciones';


        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link,
            datatables_custom_cols: $datatables_custom_cols,
            datatables_custom_cols_omite: $datatables_custom_cols_omite, datatables: $datatables,
            paths_conf: $paths_conf);



        $this->titulo_lista = 'Secciones';

        $link_adm_accion_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'adm_accion');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_adm_accion_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_adm_accion_alta_bd = $link_adm_accion_alta_bd;

        $this->lista_get_data = true;

    }

    public function acciones(bool $header = true, bool $ws = false): array|string
    {


        $data_view = new stdClass();
        $data_view->names = array('Id','Accion', 'Titulo','CSS','Acciones');
        $data_view->keys_data = array('adm_accion_id','adm_accion_descripcion','adm_accion_titulo','adm_accion_css');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\administrador\\models';
        $data_view->name_model_children = 'adm_accion';


        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__, not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }


        return $contenido_table;


    }

    public function alta(bool $header, bool $ws = false): array|string
    {

        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_menu_id',
            keys_selects: array(), id_selected: -1, label: 'Menu');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_namespace_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Namespace');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 6;

        $keys_selects['etiqueta_label'] = new stdClass();
        $keys_selects['etiqueta_label']->cols = 6;

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }


        return $r_alta;
    }

    /**
     * Obtiene los campos de la vista en ejecucion
     * @return array
     */
    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion','etiqueta_label');
        $keys->selects = array();

        $init_data = array();
        $init_data['adm_menu'] = "gamboamartin\\administrador";
        $init_data['adm_namespace'] = "gamboamartin\\administrador";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    public function get_adm_seccion(bool $header, bool $ws = true): array|stdClass
    {

        $keys['adm_menu'] = array('id','descripcion','codigo','codigo_bis');
        $keys['adm_seccion'] = array('id','descripcion','codigo','codigo_bis');
        $keys['adm_namespace'] = array('id','descripcion','codigo','codigo_bis');


        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);

        }

        return $salida;

    }

    protected function inputs_children(stdClass $registro): stdClass|array
    {
        $select_adm_menu_id = (new adm_menu_html(html: $this->html_base))->select_adm_menu_id(
            cols:6,con_registros: true,id_selected:  $registro->adm_menu_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_menu_id',data:  $select_adm_menu_id);
        }

        $select_adm_namespace_id = (new adm_namespace_html(html: $this->html_base))->select_adm_namespace_id(
            cols:6,con_registros: true,id_selected:  $registro->adm_menu_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_namespace_id',data:  $select_adm_namespace_id);
        }


        $select_adm_seccion_id = (new adm_seccion_html(html: $this->html_base))->select_adm_seccion_id(
            cols:6,con_registros: true,id_selected:  $this->registro_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_seccion_id',data:  $select_adm_seccion_id);
        }

        $adm_accion_descripcion = (new adm_accion_html(html: $this->html_base))->input_descripcion(
            cols:12,row_upd:  new stdClass(),value_vacio:  false);
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener adm_accion_descripcion',data:  $adm_accion_descripcion);
        }

        $adm_accion_titulo = (new adm_accion_html(html: $this->html_base))->input_titulo(
            cols:12,row_upd:  new stdClass(),value_vacio:  false);
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener adm_accion_descripcion',data:  $adm_accion_descripcion);
        }



        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->adm_menu_id = $select_adm_menu_id;
        $this->inputs->select->adm_namespace_id = $select_adm_namespace_id;
        $this->inputs->select->adm_seccion_id = $select_adm_seccion_id;
        $this->inputs->adm_accion_descripcion = $adm_accion_descripcion;
        $this->inputs->adm_accion_titulo = $adm_accion_titulo;

        return $this->inputs;
    }

    protected function key_selects_txt(array $keys_selects): array
    {

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'codigo', keys_selects: $keys_selects, place_holder: 'Cod');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Seccion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'etiqueta_label', keys_selects:$keys_selects, place_holder: 'Etiqueta');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }


        return $keys_selects;
    }

    public function modifica(
        bool $header, bool $ws = false): array|stdClass
    {
        $this->not_actions[] = __FUNCTION__;
        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }


        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_menu_id',
            keys_selects: array(), id_selected: $this->registro['adm_menu_id'], label: 'Menu');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_namespace_id',
            keys_selects: $keys_selects, id_selected: $this->registro['adm_namespace_id'], label: 'Namespace');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 6;

        $keys_selects['codigo'] = new stdClass();
        $keys_selects['codigo']->disabled = true;

        $keys_selects['etiqueta_label'] = new stdClass();
        $keys_selects['etiqueta_label']->disabled = false;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }




        return $r_modifica;
    }


}
