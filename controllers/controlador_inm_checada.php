<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\inmuebles\controllers;

use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_checada_html;
use gamboamartin\inmuebles\models\inm_checada;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_checada extends _ctl_formato {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_checada(link: $link);
        $html_ = new inm_checada_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        $this->lista_get_data = true;

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

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_empleado_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Empleado');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_status_asistencia_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Status Asistencia');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_periodo_asistencia_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Periodo Asistencia');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_tipo_checada_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Tipo Checada');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        
        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $this->row_upd->fecha = date('Y-m-d');
        $this->row_upd->hora = date('H:i:s');
        $this->row_upd->observaciones = date('H:i:s');

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false,
            place_holder: 'Fecha Asistencia', value: $this->row_upd->fecha);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha = $fecha;

        $hora = $this->html->input_hora(cols: 6, row_upd: $this->row_upd, value_vacio: false,
            place_holder: 'Hora Asistencia', value: $this->row_upd->hora);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input hora',data:  $hora, header: $header,ws:  $ws);
        }
        $this->inputs->hora = $hora;
        
        $observaciones = $this->html->textarea(cols: 12, row_upd: $this->row_upd, value_vacio: false,
            place_holder: 'Observaciones', required: false, value: $this->row_upd->observaciones);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input observaciones',data:  $observaciones, header: $header,ws:  $ws);
        }
        $this->inputs->observaciones = $observaciones;

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion');
        $keys->selects = array();

        $init_data = array();
        $init_data['inm_empleado'] = "gamboamartin\\inmuebles";
        $init_data['inm_status_asistencia'] = "gamboamartin\\inmuebles";
        $init_data['inm_periodo_asistencia'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_checada'] = "gamboamartin\\inmuebles";
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

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'inm_empleado_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_empleado_id, label: 'Empleado');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_status_asistencia_id', keys_selects: $keys_selects,
            id_selected: $this->row_upd->inm_status_asistencia_id, label: 'Status Asistencia');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_periodo_asistencia_id', keys_selects: $keys_selects,
            id_selected: $this->row_upd->inm_periodo_asistencia_id, label: 'Periodo Asistencia');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_tipo_checada_id', keys_selects: $keys_selects,
            id_selected: $this->row_upd->inm_tipo_checada_id, label: 'Tipo Checada');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false,
            place_holder: 'Fecha Asistencia', value: $this->row_upd->fecha);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha = $fecha;

        $hora = $this->html->input_hora(cols: 6, row_upd: $this->row_upd, value_vacio: false,
            place_holder: 'Hora Asistencia', value: $this->row_upd->hora);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input hora',data:  $hora, header: $header,ws:  $ws);
        }
        $this->inputs->hora = $hora;

        $observaciones = $this->html->textarea(cols: 12, row_upd: $this->row_upd, value_vacio: false,
            place_holder: 'Observaciones', required: false, value: $this->row_upd->observaciones);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input observaciones',data:  $observaciones, header: $header,ws:  $ws);
        }
        $this->inputs->observaciones = $observaciones;

        return $r_modifica;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_checada_id"]["titulo"] = "Id";
        $columns["inm_empleado_razon_social"]["titulo"] = "Empleado";
        $columns["inm_checada_fecha"]["titulo"] = "Fecha";
        $columns["inm_checada_hora"]["titulo"] = "hora";
        $columns["inm_periodo_asistencia_descripcion"]["titulo"] = "Periodo";
        $columns["inm_tipo_checada_descripcion"]["titulo"] = "Tipo Checada";
        $columns["inm_status_asistencia_descripcion"]["titulo"] = "Status Asistencia";

        $filtro = array("inm_checada.id","inm_empleado.razon_social",'inm_tipo_checada.descripcion');

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }


}
