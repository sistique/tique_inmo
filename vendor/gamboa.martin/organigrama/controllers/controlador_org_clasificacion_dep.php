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
use gamboamartin\organigrama\html\org_clasificacion_dep_html;
use gamboamartin\organigrama\html\org_departamento_html;
use gamboamartin\organigrama\html\org_empresa_html;
use gamboamartin\organigrama\models\org_clasificacion_dep;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_org_clasificacion_dep extends _ctl_parent_sin_codigo {

    public array|stdClass $keys_selects = array();

    public int $org_departamento_id = -1;
    public string $link_org_departamento_alta_bd = '';

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_clasificacion_dep(link: $link);
        $html_ = new org_clasificacion_dep_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['org_clasificacion_dep_id']['titulo'] = 'Id';
        $datatables->columns['org_clasificacion_dep_descripcion']['titulo'] = 'Clasificacion Depto';
        $datatables->columns['org_clasificacion_dep_n_departamentos']['titulo'] = 'Departamentos';

        $datatables->filtro = array();
        $datatables->filtro[] = 'org_clasificacion_dep.id';
        $datatables->filtro[] = 'org_clasificacion_dep.descripcion';

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Clasificacion de departamentos';

        $link_org_departamento_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'org_departamento');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_org_departamento_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_org_departamento_alta_bd = $link_org_departamento_alta_bd;

        $this->childrens_data['org_departamento']['title'] = 'Departamento';

        $this->verifica_parents_alta = true;

    }

    public function departamentos(bool $header = true, bool $ws = false): array|stdClass|string
    {

        $data_view = new stdClass();
        $data_view->names = array('Id','Departamento','Clasificacion','Empresa','Tipo Emp','Acciones');
        $data_view->keys_data = array('org_departamento_id','org_departamento_descripcion',
            'org_clasificacion_dep_descripcion','org_empresa_rfc', 'org_tipo_empresa_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\organigrama\\models';
        $data_view->name_model_children = 'org_departamento';

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }


        return $contenido_table;

    }

    protected function inputs_children(stdClass $registro): array|stdClass{
        $select_org_clasificacion_dep_id = (new org_clasificacion_dep_html(html: $this->html_base))->select_org_clasificacion_dep_id(
            cols:12,con_registros: true,id_selected:  $registro->org_clasificacion_dep_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_clasificacion_dep_id',data:  $select_org_clasificacion_dep_id);
        }

        $select_org_empresa_id = (new org_empresa_html(html: $this->html_base))->select_org_empresa_id(
            cols:12,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_empresa_id',data:  $select_org_empresa_id);
        }

        $org_departamento_descripcion = (new org_departamento_html(html: $this->html_base))->input_descripcion(
            cols:12,row_upd:  new stdClass(), value_vacio: true, place_holder: 'Departamento');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_departamento_descripcion',
                data:  $org_departamento_descripcion);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->org_clasificacion_dep_id = $select_org_clasificacion_dep_id;
        $this->inputs->select->org_empresa_id = $select_org_empresa_id;
        $this->inputs->org_departamento_descripcion = $org_departamento_descripcion;

        return $this->inputs;
    }


    /**
     * Integra los keys para parametros de un select
     * @param array $keys_selects Keys precargados
     * @return array
     * @version 0.369.48
     */
    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 12,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Clas Depto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

}
