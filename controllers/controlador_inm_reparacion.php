<?php
/**
 * @author tique_inmo
 * @version 1.0.0
 * @created 2026-04-28
 */
namespace gamboamartin\inmuebles\controllers;
use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_reparacion_html;
use gamboamartin\inmuebles\models\inm_reparacion;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;
class controlador_inm_reparacion extends _ctl_base {
    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_reparacion(link: $link);
        $html_ = new inm_reparacion_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);
        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }
        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);
    }
    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }
        $this->row_upd->inm_ubicacion_id = -1;
        $this->row_upd->responsable_id = -1;
        $this->row_upd->fecha_inicio = date('Y-m-d H:i:s');
        $this->row_upd->fecha_fin = '';
        $this->row_upd->estatus = 'pendiente';
        $this->row_upd->observaciones = '';
        $keys_selects = array();
        $keys_selects = $this->key_select(cols: 6, con_registros: true, filtro: array(),
            key: 'inm_ubicacion_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_ubicacion_id,
            label: 'Propiedad');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true, filtro: array(),
            key: 'responsable_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->responsable_id,
            label: 'Responsable');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }
        $fecha_inicio = $this->html->input_fecha(cols: 4, row_upd: $this->row_upd, value_vacio: false,
            value: $this->row_upd->fecha_inicio);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha_inicio', data: $fecha_inicio, header: $header, ws: $ws);
        }
        $this->inputs->fecha_inicio = $fecha_inicio;
        return $r_alta;
    }
    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion', 'fecha_inicio', 'fecha_fin', 'estatus', 'observaciones');
        $keys->selects = array();
        $init_data = array();
        $init_data['inm_ubicacion'] = "gamboamartin\\inmuebles";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }
        return $campos_view;
    }
    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }
        $keys_selects = array();
        $keys_selects = $this->key_select(cols: 6, con_registros: true, filtro: array(),
            key: 'inm_ubicacion_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_ubicacion_id,
            label: 'Propiedad');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true, filtro: array(),
            key: 'responsable_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->responsable_id,
            label: 'Responsable');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }
        $fecha_inicio = $this->html->input_fecha(cols: 4, row_upd: $this->row_upd, value_vacio: false,
            value: $this->row_upd->fecha_inicio);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha_inicio', data: $fecha_inicio, header: $header, ws: $ws);
        }
        $this->inputs->fecha_inicio = $fecha_inicio;
        return $r_modifica;
    }
    private function init_datatable(): stdClass
    {
        $columns["inm_reparacion_id"]["titulo"] = "Id";
        $columns["inm_ubicacion_descripcion"]["titulo"] = "Propiedad";
        $columns["inm_reparacion_descripcion"]["titulo"] = "Reparacion";
        $columns["inm_reparacion_estatus"]["titulo"] = "Estatus";
        $columns["inm_reparacion_fecha_inicio"]["titulo"] = "Fecha Inicio";
        $columns["inm_reparacion_fecha_fin"]["titulo"] = "Fecha Fin";
        $filtro = array("inm_reparacion.id", "inm_ubicacion.descripcion",
            "inm_reparacion.descripcion", "inm_reparacion.estatus",
            "inm_reparacion.fecha_inicio", "inm_reparacion.fecha_fin");
        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;
        return $datatables;
    }
    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripcion de la reparacion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'estatus',
            keys_selects: $keys_selects, place_holder: 'Estatus (pendiente, en_proceso, terminado)');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'fecha_fin',
            keys_selects: $keys_selects, place_holder: 'Fecha Fin (YYYY-MM-DD HH:MM:SS)');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'observaciones',
            keys_selects: $keys_selects, place_holder: 'Observaciones');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        return $keys_selects;
    }
}
