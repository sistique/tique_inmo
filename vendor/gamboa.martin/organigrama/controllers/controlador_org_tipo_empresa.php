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
use gamboamartin\organigrama\html\org_empresa_html;
use gamboamartin\organigrama\html\org_tipo_empresa_html;
use gamboamartin\organigrama\models\org_tipo_empresa;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;

use gamboamartin\template\html;
use html\cat_sat_regimen_fiscal_html;
use html\dp_calle_pertenece_html;
use html\dp_colonia_postal_html;
use html\dp_cp_html;
use html\dp_estado_html;
use html\dp_municipio_html;
use html\dp_pais_html;
use PDO;
use stdClass;

class controlador_org_tipo_empresa extends _ctl_parent_sin_codigo {

    public array|stdClass $keys_selects = array();
    public string $link_org_empresa_alta_bd = '';

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_tipo_empresa(link: $link);
        $html = new org_tipo_empresa_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['org_tipo_empresa_id']['titulo'] = 'Id';
        $datatables->columns['org_tipo_empresa_descripcion']['titulo'] = 'Tipo Empresa';
        $datatables->columns['org_tipo_empresa_n_empresas']['titulo'] = 'N Empresas';

        $datatables->filtro = array();
        $datatables->filtro[] = 'org_tipo_empresa.id';
        $datatables->filtro[] = 'org_tipo_empresa.descripcion';

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipo Empresa';

        $link_org_empresa_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'org_empresa');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_org_empresa_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_org_empresa_alta_bd = $link_org_empresa_alta_bd;

        $this->childrens_data['org_empresa']['title'] = 'Empresa';


    }

    public function empresas(bool $header = true, bool $ws = false, array $not_actions = array()): array|stdClass|string
    {

        $data_view = new stdClass();
        $data_view->names = array('Id','Rfc', 'Razon Social','Regimen Fiscal','Edo','Mun','Col','CP','Calle','Ext','Int','Acciones');
        $data_view->keys_data = array('org_empresa_id','org_empresa_rfc','org_empresa_razon_social','cat_sat_regimen_fiscal_codigo',
            'dp_estado_descripcion','dp_municipio_descripcion','dp_colonia_descripcion','dp_cp_descripcion','dp_calle_descripcion',
            'org_empresa_exterior','org_empresa_interior');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\organigrama\\models';
        $data_view->name_model_children = 'org_empresa';


        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }

        return $contenido_table;



    }

    protected function inputs_children(stdClass $registro): array|stdClass{
        $select_org_tipo_empresa_id = (new org_tipo_empresa_html(html: $this->html_base))->select_org_tipo_empresa_id(
            cols:12,con_registros: true,id_selected:  $registro->org_tipo_empresa_id,link:  $this->link, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_tipo_empresa_id',data:  $select_org_tipo_empresa_id);
        }

        $select_cat_sat_regimen_fiscal_id = (new cat_sat_regimen_fiscal_html(html: $this->html_base))->select_cat_sat_regimen_fiscal_id(
            cols:12,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_cat_sat_regimen_fiscal_id',data:  $select_cat_sat_regimen_fiscal_id);
        }

        $select_dp_pais_id = (new dp_pais_html(html: $this->html_base))->select_dp_pais_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_dp_pais_id',data:  $select_dp_pais_id);
        }

        $select_dp_estado_id = (new dp_estado_html(html: $this->html_base))->select_dp_estado_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_dp_estado_id',data:  $select_dp_estado_id);
        }

        $select_dp_municipio_id = (new dp_municipio_html(html: $this->html_base))->select_dp_municipio_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_dp_municipio_id',data:  $select_dp_municipio_id);
        }

        $select_dp_cp_id = (new dp_cp_html(html: $this->html_base))->select_dp_cp_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_dp_cp_id',data:  $select_dp_cp_id);
        }

        $select_dp_colonia_postal_id = (new dp_colonia_postal_html(html: $this->html_base))->select_dp_colonia_postal_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_dp_colonia_postal_id',data:  $select_dp_colonia_postal_id);
        }

        $select_dp_calle_pertenece_id = (new dp_calle_pertenece_html(html: $this->html_base))->select_dp_calle_pertenece_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_dp_calle_pertenece_id',data:  $select_dp_calle_pertenece_id);
        }

        $select_dp_calle_pertenece_entre1_id = (new dp_calle_pertenece_html(html: $this->html_base))->select_dp_calle_pertenece_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener dp_calle_pertenece_entre1_id',data:  $select_dp_calle_pertenece_entre1_id);
        }

        $select_dp_calle_pertenece_entre2_id = (new dp_calle_pertenece_html(html: $this->html_base))->select_dp_calle_pertenece_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener dp_calle_pertenece_entre2_id',data:  $select_dp_calle_pertenece_entre2_id);
        }

        $org_empresa_codigo = (new org_empresa_html(html: $this->html_base))->input_codigo(cols: 6,row_upd: new stdClass() ,value_vacio:  false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_codigo',data:  $org_empresa_codigo);
        }

        $org_empresa_rfc = (new org_empresa_html(html: $this->html_base))->input_rfc(cols: 6,row_upd: new stdClass() ,value_vacio:  false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_rfc',data:  $org_empresa_rfc);
        }

        $org_empresa_razon_social = (new org_empresa_html(html: $this->html_base))->input_razon_social(
            cols: 12,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_razon_social',data:  $org_empresa_razon_social);
        }

        $org_empresa_nombre_comercial = (new org_empresa_html(html: $this->html_base))->input_nombre_comercial(
            cols: 12,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_nombre_comercial',data:  $org_empresa_nombre_comercial);
        }

        $org_empresa_email = (new org_empresa_html(html: $this->html_base))->em_email_sat(
            cols: 12,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_email',data:  $org_empresa_email);
        }

        $org_empresa_fecha_inicio_operaciones = (new org_empresa_html(html: $this->html_base))->fec_fecha_inicio_operaciones(cols: 6,
            row_upd:  $registro, value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener fecha_inicio_operaciones', data:  $org_empresa_fecha_inicio_operaciones);
        }

        $org_empresa_fecha_ultimo_cambio_sat = (new org_empresa_html(html: $this->html_base))->fec_fecha_ultimo_cambio_sat(cols: 6,
            row_upd:  $registro, value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener fecha_ultimo_cambio_sat', data:  $org_empresa_fecha_ultimo_cambio_sat);
        }

        $org_empresa_exterior = (new org_empresa_html(html: $this->html_base))->input_exterior(
            cols: 6,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_exterior',data:  $org_empresa_exterior);
        }

        $org_empresa_interior = (new org_empresa_html(html: $this->html_base))->input_interior(
            cols: 6,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_interior',data:  $org_empresa_interior);
        }

        $org_empresa_telefono1 = (new org_empresa_html(html: $this->html_base))->telefono_1(
            cols: 4,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_telefono1',data:  $org_empresa_telefono1);
        }

        $org_empresa_telefono2 = (new org_empresa_html(html: $this->html_base))->telefono_2(
            cols: 4,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_telefono2',data:  $org_empresa_telefono2);
        }

        $org_empresa_telefono3 = (new org_empresa_html(html: $this->html_base))->telefono_3(
            cols: 4,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_telefono3',data:  $org_empresa_telefono3);
        }

        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->cat_sat_regimen_fiscal_id = $select_cat_sat_regimen_fiscal_id;
        $this->inputs->select->org_tipo_empresa_id = $select_org_tipo_empresa_id;
        $this->inputs->select->dp_pais_id = $select_dp_pais_id;
        $this->inputs->select->dp_estado_id = $select_dp_estado_id;
        $this->inputs->select->dp_municipio_id = $select_dp_municipio_id;
        $this->inputs->select->dp_cp_id = $select_dp_cp_id;
        $this->inputs->select->dp_colonia_postal_id = $select_dp_colonia_postal_id;
        $this->inputs->select->dp_calle_pertenece_id = $select_dp_calle_pertenece_id;
        $this->inputs->select->dp_calle_pertenece_entre1_id = $select_dp_calle_pertenece_entre1_id;
        $this->inputs->select->dp_calle_pertenece_entre2_id = $select_dp_calle_pertenece_entre2_id;

        $this->inputs->exterior = $org_empresa_exterior;
        $this->inputs->interior = $org_empresa_interior;
        $this->inputs->telefono_1 = $org_empresa_telefono1;
        $this->inputs->telefono_2 = $org_empresa_telefono2;
        $this->inputs->telefono_3 = $org_empresa_telefono3;

        $this->inputs->codigo = $org_empresa_codigo;
        $this->inputs->rfc = $org_empresa_rfc;
        $this->inputs->razon_social = $org_empresa_razon_social;
        $this->inputs->nombre_comercial = $org_empresa_nombre_comercial;
        $this->inputs->email_sat = $org_empresa_email;
        $this->inputs->fecha_inicio_operaciones = $org_empresa_fecha_inicio_operaciones;
        $this->inputs->fecha_ultimo_cambio_sat = $org_empresa_fecha_ultimo_cambio_sat;

        return $this->inputs;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Tipo Empresa');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }




}
