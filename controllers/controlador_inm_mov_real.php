<?php
/**
 * @author Módulo Financiero
 * @version 1.0.0
 * @created 2026-04-15
 */
namespace gamboamartin\inmuebles\controllers;

use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_mov_real_html;
use gamboamartin\inmuebles\models\inm_mov_real;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_mov_real extends _ctl_base {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_mov_real(link: $link);
        $html_ = new inm_mov_real_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
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
        $this->row_upd->inm_categoria_financiera_id = -1;
        $this->row_upd->inm_tipo_movimiento_id = -1;
        $this->row_upd->fecha = date('Y-m-d');
        $this->row_upd->monto = 0;
        $this->row_upd->es_ingreso = 'activo';
        $this->row_upd->referencia = '';
        $this->row_upd->observaciones = '';

        $keys_selects = array();

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_categoria_financiera_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_categoria_financiera_id,
            label: 'Categoria Financiera');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_tipo_movimiento_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_tipo_movimiento_id,
            label: 'Tipo Movimiento');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $fecha = $this->html->input_fecha(cols: 4,row_upd:  $this->row_upd,value_vacio:  false,
            value: $this->row_upd->fecha);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha = $fecha;

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion','monto','fecha','es_ingreso','referencia','observaciones');
        $keys->selects = array();

        $init_data = array();
        $init_data['inm_categoria_financiera'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_movimiento'] = "gamboamartin\\inmuebles";

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

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_categoria_financiera_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_categoria_financiera_id,
            label: 'Categoria Financiera');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_tipo_movimiento_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_tipo_movimiento_id,
            label: 'Tipo Movimiento');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $fecha = $this->html->input_fecha(cols: 4,row_upd:  $this->row_upd,value_vacio:  false,
            value: $this->row_upd->fecha);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha = $fecha;

        return $r_modifica;
    }

    /**
     * Reporte mensual de movimientos reales
     */
    public function reporte_mensual(bool $header, bool $ws = false): array|stdClass
    {
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('n');

        $modelo = new inm_mov_real(link: $this->link);
        $totales = $modelo->totales_mes(anio: $anio, mes: $mes);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener totales',data:  $totales, header: $header,ws:  $ws);
        }

        $this->reporte_data = $totales;
        $this->anio_filtro = $anio;
        $this->mes_filtro = $mes;

        return $totales;
    }

    private function init_datatable(): stdClass
    {
        $columns["inm_mov_real_id"]["titulo"] = "Id";
        $columns["inm_mov_real_fecha"]["titulo"] = "Fecha";
        $columns["inm_categoria_financiera_descripcion"]["titulo"] = "Categoria";
        $columns["inm_tipo_movimiento_descripcion"]["titulo"] = "Tipo";
        $columns["inm_mov_real_es_ingreso"]["titulo"] = "Es Ingreso";
        $columns["inm_mov_real_monto"]["titulo"] = "Monto";
        $columns["inm_mov_real_referencia"]["titulo"] = "Referencia";

        $filtro = array("inm_mov_real.id","inm_mov_real.fecha",
            'inm_categoria_financiera.descripcion','inm_tipo_movimiento.descripcion',
            'inm_mov_real.es_ingreso','inm_mov_real.monto','inm_mov_real.referencia');

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
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'fecha',
            keys_selects:$keys_selects, place_holder: 'Fecha');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'es_ingreso',
            keys_selects:$keys_selects, place_holder: 'Es Ingreso (activo/inactivo)');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'referencia',
            keys_selects:$keys_selects, place_holder: 'Referencia');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'observaciones',
            keys_selects:$keys_selects, place_holder: 'Observaciones');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

}

