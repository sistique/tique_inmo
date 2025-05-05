<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\html\org_puesto_html;
use gamboamartin\organigrama\html\org_tipo_puesto_html;
use gamboamartin\organigrama\models\org_tipo_puesto;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;

use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_org_tipo_puesto extends _ctl_parent_sin_codigo {

    public array|stdClass $keys_selects = array();
    public string $link_org_puesto_alta_bd = '';

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_tipo_puesto(link: $link);
        $html = new org_tipo_puesto_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['org_tipo_puesto_id']['titulo'] = 'Id';
        $datatables->columns['org_tipo_puesto_descripcion']['titulo'] = 'Tipo Puesto';
        $datatables->columns['org_tipo_puesto_n_puestos']['titulo'] = 'N Puestos';

        $datatables->filtro = array();
        $datatables->filtro[] = 'org_tipo_puesto.id';
        $datatables->filtro[] = 'org_tipo_puesto.descripcion';

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipo Puesto';

        $link_org_puesto_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'org_puesto');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_org_puesto_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_org_puesto_alta_bd = $link_org_puesto_alta_bd;

        $this->childrens_data['org_puesto']['title'] = 'Puesto';


    }

    public function puestos(bool $header = true, bool $ws = false): array|stdClass|string
    {

        $data_view = new stdClass();
        $data_view->names = array('Id','Puesto','Acciones');
        $data_view->keys_data = array('org_puesto_id','org_puesto_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\organigrama\\models';
        $data_view->name_model_children = 'org_puesto';


        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__, not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }

        return $contenido_table;



    }

    protected function inputs_children(stdClass $registro): array|stdClass{
        $select_org_tipo_puesto_id = (new org_tipo_puesto_html(html: $this->html_base))->select_org_tipo_puesto_id(
            cols:12,con_registros: true,id_selected:  $registro->org_tipo_empresa_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_tipo_puesto_id',data:  $select_org_tipo_puesto_id);
        }


        $org_puesto_descripcion = (new org_puesto_html(html: $this->html_base))->input_descripcion(cols: 12,
            row_upd: new stdClass() ,value_vacio:  false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_puesto_descripcion',data:  $org_puesto_descripcion);
        }

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_puesto_descripcion',data:  $org_puesto_descripcion);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->org_tipo_puesto_id = $select_org_tipo_puesto_id;
        $this->inputs->org_puesto_descripcion = $org_puesto_descripcion;

        return $this->inputs;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Tipo Puesto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }




}
