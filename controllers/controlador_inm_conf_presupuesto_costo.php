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
use gamboamartin\inmuebles\html\inm_conf_presupuesto_costo_html;
use gamboamartin\inmuebles\models\inm_conf_presupuesto_costo;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_conf_presupuesto_costo extends _ctl_base {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_conf_presupuesto_costo(link: $link);
        $html_ = new inm_conf_presupuesto_costo_html(html: $html);
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

        $this->row_upd->inm_concepto_id = -1;
        $this->row_upd->gt_proveedor_id = -1;
        $this->row_upd->aplica_iva = 'inactivo';
        $this->row_upd->aplica_co_acreditado = 'inactivo';
        $this->row_upd->aplica_conyuge = 'inactivo';

        $keys_selects = array();

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_concepto_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_concepto_id, label: 'Concepto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'gt_proveedor_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->gt_proveedor_id, label: 'Proveedor');
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
        $keys->inputs = array('descripcion','monto_presupuesto','aplica_iva','aplica_co_acreditado','aplica_conyuge');
        $keys->selects = array();

        $init_data = array();
        $init_data['inm_concepto'] = "gamboamartin\\inmuebles";
        $init_data['gt_proveedor'] = "gamboamartin\\gastos";

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

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_concepto_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_concepto_id, label: 'Concepto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'gt_proveedor_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->gt_proveedor_id, label: 'Proveedor');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        return $r_modifica;
    }

    private function init_datatable(): stdClass
    {
        $columns["inm_conf_presupuesto_costo_id"]["titulo"] = "Id";
        $columns["inm_concepto_descripcion"]["titulo"] = "Concepto";
        $columns["gt_proveedor_razon_social"]["titulo"] = "Proveedor";
        $columns["inm_conf_presupuesto_costo_monto_presupuesto"]["titulo"] = "Monto";
        $columns["inm_conf_presupuesto_costo_aplica_iva"]["titulo"] = "Aplica IVA";
        $columns["inm_conf_presupuesto_costo_aplica_co_acreditado"]["titulo"] = "Co-acred";
        $columns["inm_conf_presupuesto_costo_aplica_conyuge"]["titulo"] = "Conyuge";

        $filtro = array("inm_conf_presupuesto_costo.id","inm_concepto.descripcion","gt_proveedor.razon_social",
            'inm_conf_presupuesto_costo.monto_presupuesto','inm_conf_presupuesto_costo.aplica_iva',
            'inm_conf_presupuesto_costo.aplica_co_acreditado','inm_conf_presupuesto_costo.aplica_conyuge');

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
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'monto_presupuesto',
            keys_selects:$keys_selects, place_holder: 'Monto Presupuesto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 2,key: 'aplica_iva',
            keys_selects:$keys_selects, place_holder: 'Aplica IVA');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 2,key: 'aplica_co_acreditado',
            keys_selects:$keys_selects, place_holder: 'Co-acreditado');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 2,key: 'aplica_conyuge',
            keys_selects:$keys_selects, place_holder: 'Conyuge');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }
}

