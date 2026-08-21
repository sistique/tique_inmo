<?php
/**
 * @author Módulo SAT Descarga Masiva
 * @version 1.0.0
 * @created 2026-08-17
 */
namespace gamboamartin\inmuebles\controllers;

use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_sat_solicitud_html;
use gamboamartin\inmuebles\models\inm_sat_solicitud;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_sat_solicitud extends _ctl_base
{
    public function __construct(
        PDO      $link,
        html     $html = new \gamboamartin\template_1\html(),
        stdClass $paths_conf = new stdClass()
    ) {
        $modelo   = new inm_sat_solicitud(link: $link);
        $html_    = new inm_sat_solicitud_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(
            html: $html_,
            link: $link,
            modelo: $modelo,
            obj_link: $obj_link,
            datatables: $datatables,
            paths_conf: $paths_conf
        );

        $this->lista_get_data = true;
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = array();

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys        = new stdClass();
        $keys->inputs   = array(
            'tipo_solicitud',
            'tipo_descarga',
            'tipo_comprobante',
            'rfc_tercero',
            'fecha_inicio_periodo',
            'fecha_fin_periodo',
            'uuid_solicitud',
            'estatus',
        );
        $keys->selects  = array('inm_sat_cer_id');

        $campos_view = $this->campos_view_base(init_data: array(), keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',
                data: $r_modifica,
                header: $header,
                ws: $ws
            );
        }

        $keys_selects = array();

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    private function init_datatable(): stdClass
    {
        $columns['inm_sat_solicitud_id']['titulo']                  = 'Id';
        $columns['inm_sat_solicitud_uuid_solicitud']['titulo']       = 'UUID Solicitud';
        $columns['inm_sat_solicitud_tipo_solicitud']['titulo']       = 'Tipo';
        $columns['inm_sat_solicitud_fecha_inicio_periodo']['titulo'] = 'Periodo Inicio';
        $columns['inm_sat_solicitud_fecha_fin_periodo']['titulo']    = 'Periodo Fin';
        $columns['inm_sat_solicitud_estatus']['titulo']              = 'Estatus';
        $columns['inm_sat_cer_rfc']['titulo']                        = 'RFC';

        $filtro = array(
            'inm_sat_solicitud.id',
            'inm_sat_solicitud.uuid_solicitud',
            'inm_sat_solicitud.estatus',
            'inm_sat_cer.rfc',
        );

        $datatables          = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro  = $filtro;

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(
            cols: 4, key: 'tipo_solicitud', keys_selects: $keys_selects, place_holder: 'Tipo (CFDI/Metadata)'
        );
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(
            cols: 4, key: 'tipo_descarga', keys_selects: $keys_selects, place_holder: 'Emitidos/Recibidos'
        );
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(
            cols: 4, key: 'tipo_comprobante', keys_selects: $keys_selects, place_holder: 'Tipo comprobante (I/E/T/N/P)'
        );
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(
            cols: 6, key: 'rfc_tercero', keys_selects: $keys_selects, place_holder: 'RFC tercero (opcional)'
        );
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_fecha(
            cols: 3, key: 'fecha_inicio_periodo', keys_selects: $keys_selects, place_holder: 'Fecha inicio'
        );
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_fecha(
            cols: 3, key: 'fecha_fin_periodo', keys_selects: $keys_selects, place_holder: 'Fecha fin'
        );
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }
}

