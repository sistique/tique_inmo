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
use gamboamartin\inmuebles\html\inm_pago_costo_html;
use gamboamartin\inmuebles\models\inm_pago_costo;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_pago_costo extends _ctl_base {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_pago_costo(link: $link);
        $html_ = new inm_pago_costo_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->lista_get_data = true;
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $this->row_upd->inm_costo_id = -1;
        $this->row_upd->cat_sat_forma_pago_id = -1;
        $this->row_upd->fecha_pago = date('Y-m-d');

        $keys_selects = array();

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_costo_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_costo_id, label: 'Costo');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'cat_sat_forma_pago_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->cat_sat_forma_pago_id, label: 'Forma Pago');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $fecha_pago = $this->html->input_fecha(cols: 4,row_upd:  $this->row_upd,value_vacio:  false,
            value: $this->row_upd->fecha_pago);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha_pago, header: $header,ws:  $ws);
        }

        $this->inputs->fecha_pago = $fecha_pago;

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion','monto','fecha_pago','referencia');
        $keys->selects = array();

        $init_data = array();
        $init_data['inm_costo'] = "gamboamartin\\inmuebles";
        $init_data['cat_sat_forma_pago'] = "gamboamartin\\cat_sat";

        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $keys_selects = array();

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_costo_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_costo_id, label: 'Costo');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'cat_sat_forma_pago_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->cat_sat_forma_pago_id, label: 'Forma Pago');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $fecha_pago = $this->html->input_fecha(cols: 4,row_upd:  $this->row_upd,value_vacio:  false,
            value: $this->row_upd->fecha_pago);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha_pago, header: $header,ws:  $ws);
        }

        $this->inputs->fecha_pago = $fecha_pago;

        return $r_modifica;
    }

    private function init_datatable(): stdClass
    {
        $columns["inm_pago_costo_id"]["titulo"] = "Id";
        $columns["inm_costo_descripcion"]["titulo"] = "Costo";
        $columns["cat_sat_forma_pago_descripcion"]["titulo"] = "Forma Pago";
        $columns["inm_pago_costo_fecha_pago"]["titulo"] = "Fecha";
        $columns["inm_pago_costo_monto"]["titulo"] = "Monto";
        $columns["inm_pago_costo_referencia"]["titulo"] = "Referencia";

        $filtro = array("inm_pago_costo.id","inm_costo.descripcion","cat_sat_forma_pago.descripcion",
            'inm_pago_costo.fecha_pago','inm_pago_costo.monto','inm_pago_costo.referencia');

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'descripcion',
            keys_selects:$keys_selects, place_holder: 'Descripcion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'monto',
            keys_selects:$keys_selects, place_holder: 'Monto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'fecha_pago',
            keys_selects:$keys_selects, place_holder: 'Fecha Pago');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'referencia',
            keys_selects:$keys_selects, place_holder: 'Referencia');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }
}

