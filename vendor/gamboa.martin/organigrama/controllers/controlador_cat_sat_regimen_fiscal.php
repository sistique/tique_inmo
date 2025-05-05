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
use html\cat_sat_regimen_fiscal_html;
use PDO;
use stdClass;


class controlador_cat_sat_regimen_fiscal extends \gamboamartin\cat_sat\controllers\controlador_cat_sat_regimen_fiscal {

    public string $link_org_empresa_alta_bd = '';
    public function __construct(PDO $link , stdClass $paths_conf = new stdClass()){


        parent::__construct(link: $link,  paths_conf: $paths_conf);

        $this->titulo_lista = 'Regimenes fiscales';

        $link_org_empresa_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'org_empresa');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_org_empresa_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_org_empresa_alta_bd = $link_org_empresa_alta_bd;

        $this->childrens_data['org_empresa']['title'] = 'Empresa';

    }

    public function empresas(bool $header = true, bool $ws = false): array|stdClass|string
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
            not_actions: $this->not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }

        return $contenido_table;



    }

    protected function inputs_children(stdClass $registro): array|stdClass{
        $select_org_tipo_empresa_id = (new org_tipo_empresa_html(html: $this->html_base))->select_org_tipo_empresa_id(
            cols:12,con_registros: true,id_selected: -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_tipo_empresa_id',data:  $select_org_tipo_empresa_id);
        }

        $select_cat_sat_regimen_fiscal_id = (new cat_sat_regimen_fiscal_html(html: $this->html_base))->select_cat_sat_regimen_fiscal_id(
            cols:12,con_registros: true,id_selected: $registro->cat_sat_regimen_fiscal_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_tipo_empresa_id',data:  $select_org_tipo_empresa_id);
        }

        $org_empresa_rfc = (new org_empresa_html(html: $this->html_base))->input_rfc(cols: 12,row_upd: new stdClass() ,value_vacio:  false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_rfc',data:  $org_empresa_rfc);
        }

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_tipo_empresa_id',data:  $select_org_tipo_empresa_id);
        }

        $org_empresa_razon_social = (new org_empresa_html(html: $this->html_base))->input_razon_social(
            cols: 12,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_razon_social',data:  $org_empresa_razon_social);
        }

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_tipo_empresa_id',data:  $select_org_tipo_empresa_id);
        }

        $org_empresa_nombre_comercial = (new org_empresa_html(html: $this->html_base))->input_nombre_comercial(
            cols: 12,  row_upd: new stdClass(), value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener org_empresa_nombre_comercial',data:  $org_empresa_nombre_comercial);
        }

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_org_tipo_empresa_id',data:  $select_org_tipo_empresa_id);
        }

        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        $this->inputs->select->cat_sat_regimen_fiscal_id = $select_cat_sat_regimen_fiscal_id;
        $this->inputs->select->org_tipo_empresa_id = $select_org_tipo_empresa_id;
        $this->inputs->org_empresa_rfc = $org_empresa_rfc;
        $this->inputs->org_empresa_razon_social = $org_empresa_razon_social;
        $this->inputs->org_empresa_nombre_comercial = $org_empresa_nombre_comercial;

        return $this->inputs;
    }




}
