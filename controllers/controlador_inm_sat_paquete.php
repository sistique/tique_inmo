<?php
/**
 * @author Módulo SAT Descarga Masiva
 * @version 1.0.0
 * @created 2026-08-17
 */
namespace gamboamartin\inmuebles\controllers;

use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_sat_paquete_html;
use gamboamartin\inmuebles\models\inm_sat_descarga_masiva;
use gamboamartin\inmuebles\models\inm_sat_paquete;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_sat_paquete extends _ctl_base
{
    public function __construct(
        PDO      $link,
        html     $html = new \gamboamartin\template_1\html(),
        stdClass $paths_conf = new stdClass()
    ) {
        $modelo   = new inm_sat_paquete(link: $link);
        $html_    = new inm_sat_paquete_html(html: $html);
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

    /**
     * Descarga un paquete SAT por su ID de registro en BD.
     * Requiere: $_GET['registro_id'] = inm_sat_paquete_id
     *           $_GET['inm_sat_cer_id']
     *           $_GET['inm_sat_key_id']
     */
    public function descarga_paquete(bool $header, bool $ws = false): stdClass|array|string
    {
        $inm_sat_paquete_id = (int)($this->registro_id ?? 0);
        $inm_sat_cer_id     = (int)($_GET['inm_sat_cer_id'] ?? 0);
        $inm_sat_key_id     = (int)($_GET['inm_sat_key_id'] ?? 0);

        if ($inm_sat_paquete_id <= 0) {
            return $this->retorno_error(
                mensaje: 'registro_id (inm_sat_paquete_id) es requerido',
                data: $inm_sat_paquete_id,
                header: $header,
                ws: $ws
            );
        }
        if ($inm_sat_cer_id <= 0 || $inm_sat_key_id <= 0) {
            return $this->retorno_error(
                mensaje: 'inm_sat_cer_id e inm_sat_key_id son requeridos',
                data: ['cer' => $inm_sat_cer_id, 'key' => $inm_sat_key_id],
                header: $header,
                ws: $ws
            );
        }

        $servicio = new inm_sat_descarga_masiva(link: $this->link);
        $result   = $servicio->descargar(
            inm_sat_cer_id: $inm_sat_cer_id,
            inm_sat_key_id: $inm_sat_key_id,
            inm_sat_paquete_id: $inm_sat_paquete_id
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al descargar paquete', data: $result, header: $header, ws: $ws);
        }

        return $result;
    }

    protected function campos_view(): array
    {
        $keys        = new stdClass();
        $keys->inputs   = array('id_paquete', 'ruta_archivo', 'total_cfdi', 'estatus');
        $keys->selects  = array('inm_sat_solicitud_id');

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
        $columns['inm_sat_paquete_id']['titulo']         = 'Id';
        $columns['inm_sat_paquete_id_paquete']['titulo'] = 'ID Paquete SAT';
        $columns['inm_sat_paquete_estatus']['titulo']    = 'Estatus';
        $columns['inm_sat_paquete_total_cfdi']['titulo'] = 'Total CFDIs';
        $columns['inm_sat_paquete_ruta_archivo']['titulo'] = 'Archivo';

        $filtro = array(
            'inm_sat_paquete.id',
            'inm_sat_paquete.id_paquete',
            'inm_sat_paquete.estatus',
        );

        $datatables          = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro  = $filtro;

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(
            cols: 6, key: 'id_paquete', keys_selects: $keys_selects, place_holder: 'ID Paquete SAT'
        );
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }
}

