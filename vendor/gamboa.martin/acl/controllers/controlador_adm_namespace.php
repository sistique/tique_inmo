<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\acl\controllers;

use base\controller\init;
use gamboamartin\administrador\models\adm_namespace;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;
use gamboamartin\template_1\html;
use html\adm_menu_html;
use html\adm_namespace_html;
use html\adm_seccion_html;
use PDO;
use stdClass;


class controlador_adm_namespace extends _ctl_parent_sin_codigo {

    public array $secciones = array();
    public stdClass|array $adm_namespace = array();
    public string $link_adm_seccion_alta_bd = '';


    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass()){
        $modelo = new adm_namespace(link: $link);

        $html_ = new adm_namespace_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_namespace_id']['titulo'] = 'Id';
        $datatables->columns['adm_namespace_descripcion']['titulo'] = 'Namespace';
        $datatables->columns['adm_namespace_name']['titulo'] = 'Name';
        $datatables->columns['adm_namespace_n_secciones']['titulo'] = 'Secciones';

        $datatables->filtro = array();
        $datatables->filtro[] = 'adm_namespace.id';
        $datatables->filtro[] = 'adm_namespace.descripcion';
        $datatables->filtro[] = 'adm_namespace.name';


        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Namespaces';

        if(isset($this->registro_id) && $this->registro_id > 0){
            $adm_namespace = (new adm_namespace($this->link))->registro(registro_id: $this->registro_id);
            if(errores::$error){
                $error = $this->errores->error(mensaje: 'Error al obtener adm_namespace',data:  $adm_namespace);
                print_r($error);
                exit;
            }
            $this->adm_namespace = $adm_namespace;
        }

        $link_adm_seccion_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'adm_seccion');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_adm_seccion_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_adm_seccion_alta_bd = $link_adm_seccion_alta_bd;

        $this->path_vendor_views = 'gamboa.martin/acl';

    }

    /**
     * Integra los campos de una vista al front
     * @param array $inputs
     * @return array
     * @version 7.12.0
     */
    protected function campos_view(array $inputs = array()): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion','name');
        $keys->selects = array();



        $campos_view = (new init())->model_init_campos_template(
            campos_view: array(),keys:  $keys, link: $this->link);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    protected function inputs_children(stdClass $registro): array|stdClass{
        $select_adm_namespace_id = (new adm_namespace_html(html: $this->html_base))->select_adm_namespace_id(
            cols:12,con_registros: true,id_selected:  $registro->adm_namespace_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_adm_namespace_id',data:  $select_adm_namespace_id);
        }
        $select_adm_menu_id = (new adm_menu_html(html: $this->html_base))->select_adm_menu_id(
            cols:6,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_adm_namespace_id',data:  $select_adm_namespace_id);
        }

        $adm_seccion_descripcion = (new adm_seccion_html(html: $this->html_base))->input_descripcion(
            cols:6,row_upd:  new stdClass(), value_vacio: true, place_holder: 'Seccion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener adm_seccion_descripcion',
                data:  $adm_seccion_descripcion);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->adm_namespace_id = $select_adm_namespace_id;
        $this->inputs->select->adm_menu_id = $select_adm_menu_id;
        $this->inputs->adm_seccion_descripcion = $adm_seccion_descripcion;

        return $this->inputs;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Namespace');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12,key: 'name', keys_selects:$keys_selects, place_holder: 'Name');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }


        return $keys_selects;
    }

    public function secciones(bool $header = true, bool $ws = false): array|stdClass|string
    {

        $data_view = new stdClass();
        $data_view->names = array('Id','Seccion', 'N Acciones','Acciones');
        $data_view->keys_data = array('adm_seccion_id','adm_seccion_descripcion','adm_seccion_n_acciones');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\administrador\\models';
        $data_view->name_model_children = 'adm_seccion';


        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__, not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }


        return $contenido_table;

    }

}
