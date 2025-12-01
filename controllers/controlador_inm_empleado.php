<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\inmuebles\controllers;

use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_empleado_html;
use gamboamartin\inmuebles\models\inm_empleado;
use gamboamartin\inmuebles\models\inm_rel_emp_emp;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_empleado extends _ctl_formato {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_empleado(link: $link);
        $html_ = new inm_empleado_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }
        $keys_selects = array();
        $columns_selects = array('adm_usuario_user');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_usuario_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Usuario',columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_horario_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Horario');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }      
        
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'apellido_paterno',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }      
        
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'apellido_materno',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }      
        
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'nss',
            keys_selects: $keys_selects, place_holder: 'NSS', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'rfc',
            keys_selects: $keys_selects, place_holder: 'RFC', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'celular',
            keys_selects: $keys_selects, place_holder: 'Celular', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razon Social', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion','nss','rfc','nombre','apellido_paterno','apellido_materno','celular',
            'razon_social','calle','numero_exterior','numero_interior','salario_diario',
            'salario_diario_integrado','curp');
        $keys->selects = array();
        $keys->fechas = array('fecha_inicio_rel_laboral');

        $init_data = array();
        $init_data['adm_usuario'] = "gamboamartin\\administrador";
        $init_data['inm_horario'] = "gamboamartin\\inmuebles";
        $init_data['dp_pais'] = "gamboamartin\\direccion_postal";
        $init_data['dp_estado'] = "gamboamartin\\direccion_postal";
        $init_data['dp_municipio'] = "gamboamartin\\direccion_postal";
        $init_data['dp_cp'] = "gamboamartin\\direccion_postal";
        $init_data['dp_colonia_postal'] = "gamboamartin\\direccion_postal";
        $init_data['cat_sat_regimen_fiscal'] = "gamboamartin\\cat_sat";
        $init_data['im_registro_patronal'] = "gamboamartin\\im_registro_patronal";
        $init_data['cat_sat_tipo_regimen_nom'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_tipo_jornada_nom'] = "gamboamartin\\cat_sat";
        $init_data['org_puesto'] = "gamboamartin\\organigrama";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }



    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $keys_selects = array();
        $columns_selects = array('adm_usuario_user');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_usuario_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->adm_usuario_id, label: 'Usuario',columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $r_rel_emp = (new inm_rel_emp_emp(link: $this->link))->filtro_and(filtro:
            array('inm_empleado.id'=>$this->registro_id));
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $r_rel_emp);
        }

        $this->row_upd->dp_pais_id = -1;
        $this->row_upd->dp_estado_id = -1;
        $this->row_upd->dp_municipio_id = -1;
        $this->row_upd->dp_cp_id = -1;
        $this->row_upd->dp_colonia_postal_id = -1;
        $this->row_upd->im_registro_patronal_id = -1;
        $this->row_upd->org_puesto_id = -1;
        $this->row_upd->cat_sat_regimen_fiscal_id = -1;
        $this->row_upd->cat_sat_tipo_regimen_nom_id = -1;
        $this->row_upd->cat_sat_tipo_jornada_nom_id = -1;

        $this->row_upd->fecha_inicio_rel_laboral = date("Y-m-d");
        $this->row_upd->calle = '';
        $this->row_upd->numero_exterior = '';
        $this->row_upd->numero_interior = '';
        $this->row_upd->salario_diario = '';
        $this->row_upd->salario_diario_integrado = '';

        if($r_rel_emp->n_registros > 0){
            $this->row_upd->dp_pais_id = $r_rel_emp->registros[0]['dp_pais_id'];
            $this->row_upd->dp_estado_id = $r_rel_emp->registros[0]['dp_estado_id'];
            $this->row_upd->dp_municipio_id = $r_rel_emp->registros[0]['dp_municipio_id'];
            $this->row_upd->dp_cp_id = $r_rel_emp->registros[0]['dp_cp_id'];
            $this->row_upd->dp_colonia_postal_id = $r_rel_emp->registros[0]['dp_colonia_postal_id'];
            $this->row_upd->im_registro_patronal_id = $r_rel_emp->registros[0]['im_registro_patronal_id'];
            $this->row_upd->org_puesto_id = $r_rel_emp->registros[0]['org_puesto_id'];
            $this->row_upd->cat_sat_regimen_fiscal_id = $r_rel_emp->registros[0]['cat_sat_regimen_fiscal_id'];
            $this->row_upd->cat_sat_tipo_regimen_nom_id = $r_rel_emp->registros[0]['cat_sat_tipo_regimen_nom_id'];
            $this->row_upd->cat_sat_tipo_jornada_nom_id = $r_rel_emp->registros[0]['cat_sat_tipo_jornada_nom_id'];

            $this->row_upd->fecha_inicio_rel_laboral = $r_rel_emp->registros[0]['inm_empleado_fecha_inicio_rel_laboral'];
            $this->row_upd->calle = $r_rel_emp->registros[0]['inm_empleado_calle'];
            $this->row_upd->numero_exterior = $r_rel_emp->registros[0]['inm_empleado_numero_exterior'];
            $this->row_upd->numero_interior = $r_rel_emp->registros[0]['inm_empleado_numero_interior'];
            $this->row_upd->salario_diario = $r_rel_emp->registros[0]['inm_empleado_salario_diario'];
            $this->row_upd->salario_diario_integrado = $r_rel_emp->registros[0]['inm_empleado_salario_diario_integrado'];
        }

        $columns_selects = array('dp_pais_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'dp_pais_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->dp_pais_id, label: 'Pais',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $columns_selects = array('dp_estado_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'dp_estado_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->dp_estado_id, label: 'Estado',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $columns_selects = array('dp_municipio_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'dp_municipio_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->dp_municipio_id, label: 'Municipio',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $columns_selects = array('dp_cp_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'dp_cp_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->dp_cp_id, label: 'CP',columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $columns_selects = array('dp_colonia_postal_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'dp_colonia_postal_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->dp_colonia_postal_id, label: 'Colonia Postal',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $columns_selects = array('im_registro_patronal_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'im_registro_patronal_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->im_registro_patronal_id, label: 'Registro Patronal',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        
        $columns_selects = array('org_puesto_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'org_puesto_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->org_puesto_id, label: 'Puesto',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $columns_selects = array('cat_sat_regimen_fiscal_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'cat_sat_regimen_fiscal_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->cat_sat_regimen_fiscal_id, label: 'Regimen Fiscal',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $columns_selects = array('cat_sat_tipo_regimen_nom_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'cat_sat_tipo_regimen_nom_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->cat_sat_tipo_regimen_nom_id, label: 'Tipo Regimen',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }       
        
        $columns_selects = array('cat_sat_tipo_jornada_nom_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'cat_sat_tipo_jornada_nom_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->cat_sat_tipo_jornada_nom_id, label: 'Jornada',
            columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'calle',
            keys_selects: $keys_selects, place_holder: 'Calle', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'numero_exterior',
            keys_selects: $keys_selects, place_holder: 'Numero Exterior', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'numero_interior',
            keys_selects: $keys_selects, place_holder: 'Numero Interior', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'fecha_inicio_rel_laboral',
            keys_selects: $keys_selects, place_holder: 'Inicio Relacion Laboral', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'salario_diario',
            keys_selects: $keys_selects, place_holder: 'Salario Diario', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'salario_diario_integrado',
            keys_selects: $keys_selects, place_holder: 'Salario Diario Int.', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'curp',
            keys_selects: $keys_selects, place_holder: 'CURP', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_horario_id', keys_selects: $keys_selects, id_selected: $this->row_upd->inm_horario_id,
            label: 'Horario');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'apellido_paterno',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'apellido_materno',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'nss',
            keys_selects: $keys_selects, place_holder: 'NSS', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'rfc',
            keys_selects: $keys_selects, place_holder: 'RFC', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'celular',
            keys_selects: $keys_selects, place_holder: 'Celular', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razon Social', required: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        return $r_modifica;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_empleado_id"]["titulo"] = "Id";
        $columns["inm_empleado_razon_social"]["titulo"] = "Razon Social";
        $columns["inm_empleado_rfc"]["titulo"] = "RFC";
        $columns["inm_empleado_nss"]["titulo"] = "NSS";
        $columns["inm_horario_descripcion"]["titulo"] = "Horario";
        $columns["inm_empleado_celular"]["titulo"] = "Celular";
        $columns["adm_usuario_user"]["titulo"] = "Usuario";

        $filtro = array("inm_empleado.id","inm_empleado.razon_social","inm_horario.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }


}
