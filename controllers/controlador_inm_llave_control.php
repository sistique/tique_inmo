<?php
/**
 * @author tique_inmo
 * @version 1.0.0
 * @created 2026-04-28
 */
namespace gamboamartin\inmuebles\controllers;
use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_llave_control_html;
use gamboamartin\inmuebles\models\inm_llave_control;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;
class controlador_inm_llave_control extends _ctl_base {
    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_llave_control(link: $link);
        $html_ = new inm_llave_control_html(html: $html);
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
        $this->row_upd->inm_llave_id = -1;
        $this->row_upd->responsable_id = -1;
        $this->row_upd->fecha_entrega = date('Y-m-d H:i:s');
        $this->row_upd->fecha_devolucion = '';
        $this->row_upd->observaciones = '';
        $keys_selects = array();
        $keys_selects = $this->key_select(cols: 6, con_registros: true, filtro: array(),
            key: 'inm_llave_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_llave_id,
            label: 'Llave');
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
        $fecha_entrega = $this->html->input_fecha(cols: 4, row_upd: $this->row_upd, value_vacio: false,
            value: $this->row_upd->fecha_entrega);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha_entrega', data: $fecha_entrega, header: $header, ws: $ws);
        }
        $this->inputs->fecha_entrega = $fecha_entrega;
        return $r_alta;
    }
    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion', 'fecha_entrega', 'fecha_devolucion', 'observaciones');
        $keys->selects = array();
        $init_data = array();
        $init_data['inm_llave'] = "gamboamartin\\inmuebles";
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
            key: 'inm_llave_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_llave_id,
            label: 'Llave');
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
        $fecha_entrega = $this->html->input_fecha(cols: 4, row_upd: $this->row_upd, value_vacio: false,
            value: $this->row_upd->fecha_entrega);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha_entrega', data: $fecha_entrega, header: $header, ws: $ws);
        }
        $this->inputs->fecha_entrega = $fecha_entrega;
        return $r_modifica;
    }
    private function init_datatable(): stdClass
    {
        $columns["inm_llave_control_id"]["titulo"] = "Id";
        $columns["inm_llave_descripcion"]["titulo"] = "Llave";
        $columns["inm_llave_control_descripcion"]["titulo"] = "Descripcion";
        $columns["inm_llave_control_fecha_entrega"]["titulo"] = "Fecha Entrega";
        $columns["inm_llave_control_fecha_devolucion"]["titulo"] = "Fecha Devolucion";
        $filtro = array("inm_llave_control.id", "inm_llave.descripcion",
            "inm_llave_control.descripcion", "inm_llave_control.fecha_entrega",
            "inm_llave_control.fecha_devolucion");
        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;
        return $datatables;
    }
    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripcion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'fecha_devolucion',
            keys_selects: $keys_selects, place_holder: 'Fecha Devolucion (YYYY-MM-DD HH:MM:SS)');
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
