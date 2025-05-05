<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\acl\controllers;

use gamboamartin\administrador\models\adm_sistema;
use gamboamartin\errores\errores;

use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\template_1\html;
use html\adm_menu_html;
use html\adm_seccion_html;
use html\adm_sistema_html;
use links\secciones\link_adm_sistema;
use PDO;
use stdClass;


class controlador_adm_sistema extends _ctl_parent_sin_codigo {

    public stdClass|array $adm_sistema = array();
    public string $link_adm_seccion_pertenece_alta_bd = '';

    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass()){
        $modelo = new adm_sistema(link: $link);

        $html_ = new adm_sistema_html(html: $html);
        $obj_link = new link_adm_sistema(link: $link, registro_id: $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_sistema_id']['titulo'] = 'Id';
        $datatables->columns['adm_sistema_descripcion']['titulo'] = 'Sistema';

        $datatables->filtro = array();
        $datatables->filtro[] = 'adm_sistema.id';
        $datatables->filtro[] = 'adm_sistema.descripcion';
        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Sistemas';

        if(isset($this->registro_id) && $this->registro_id > 0){
            $adm_sistema = (new adm_sistema($this->link))->registro(registro_id: $this->registro_id);
            if(errores::$error){
                $error = $this->errores->error(mensaje: 'Error al obtener adm_sistema',data:  $adm_sistema);
                print_r($error);
                exit;
            }
            $this->adm_sistema = $adm_sistema;
        }

        $link_adm_seccion_pertenece_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'adm_seccion_pertenece');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_adm_seccion_pertenece_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_adm_seccion_pertenece_alta_bd = $link_adm_seccion_pertenece_alta_bd;

    }


    protected function inputs_children(stdClass $registro): stdClass|array
    {
        $keys = array('adm_sistema_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al validar registro',data:  $valida);
        }

        $select_adm_sistema_id = (new adm_sistema_html(html: $this->html_base))->select_adm_sistema_id(
            cols:12,con_registros: true,id_selected:  $registro->adm_sistema_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_sistema_id',data:  $select_adm_sistema_id);
        }

        $select_adm_menu_id = (new adm_menu_html(html: $this->html_base))->select_adm_menu_id(
            cols:6,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_menu_id',data:  $select_adm_menu_id);
        }

        $select_adm_seccion_id = (new adm_seccion_html(html: $this->html_base))->select_adm_seccion_id(
            cols:6,con_registros: false,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_seccion_id',data:  $select_adm_seccion_id);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->adm_menu_id = $select_adm_menu_id;
        $this->inputs->select->adm_seccion_id = $select_adm_seccion_id;
        $this->inputs->select->adm_sistema_id = $select_adm_sistema_id;

        return $this->inputs;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Sistema');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

    public function secciones(bool $header = true, bool $ws = false): array|stdClass|string{


        $data_view = new stdClass();
        $data_view->names = array('Id','Seccion','Sistema', 'Menu','Acciones');
        $data_view->keys_data = array('adm_seccion_pertenece_id','adm_seccion_descripcion', 'adm_sistema_descripcion','adm_menu_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\administrador\\models';
        $data_view->name_model_children = 'adm_seccion_pertenece';


        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__, not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }
        return $contenido_table;

    }


}
