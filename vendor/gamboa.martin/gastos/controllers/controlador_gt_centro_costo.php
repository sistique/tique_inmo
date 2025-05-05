<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\gastos\controllers;


use base\controller\controler;
use gamboamartin\errores\errores;
use gamboamartin\gastos\models\gt_centro_costo;
use gamboamartin\gastos\models\gt_cotizacion;
use gamboamartin\gastos\models\gt_orden_compra_producto;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\gt_centro_costo_html;
use PDO;
use stdClass;
use Throwable;

class controlador_gt_centro_costo extends _ctl_base {

    public $saldos_cotizacion;
    public $saldos_orden_compra;
    public float $saldos_solicitud;
    public float $saldos_requisicion;

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new gt_centro_costo(link: $link);
        $html_ = new gt_centro_costo_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $configuraciones = $this->init_configuraciones();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar configuraciones', data: $configuraciones);
            print_r($error);
            die('Error');
        }

        $this->lista_get_data = true;
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion');
        $keys->telefonos = array();
        $keys->fechas = array();
        $keys->selects = array();

        $init_data = array();
        $init_data['gt_tipo_centro_costo'] = "gamboamartin\\gastos";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->seccion_titulo = 'Centro Costo';
        $this->titulo_lista = 'Registro de Centro Costo';

        $this->lista_get_data = true;

        return $this;
    }

    protected function init_datatable(): stdClass
    {
        $columns["gt_centro_costo_id"]["titulo"] = "Id";
        $columns["gt_tipo_centro_costo_descripcion"]["titulo"] = "Tipo";
        $columns["gt_centro_costo_descripcion"]["titulo"] = "Descripción";

        $filtro = array("gt_centro_costo.id","gt_tipo_centro_costo.descripcion","gt_centro_costo.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    private function init_selects(array $keys_selects, string $key, string $label, int $id_selected = -1, int $cols = 6,
                                  bool  $con_registros = true, array $filtro = array()): array
    {
        $keys_selects = $this->key_select(cols: $cols, con_registros: $con_registros, filtro: $filtro, key: $key,
            keys_selects: $keys_selects, id_selected: $id_selected, label: $label);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function init_selects_inputs(): array
    {
        return $this->init_selects(keys_selects: array(), key: "gt_tipo_centro_costo_id", label: "Tipo", cols: 12);
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Código');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 8, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $keys_selects['gt_tipo_centro_costo_id']->id_selected = $this->registro['gt_tipo_centro_costo_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function saldos(bool $header, bool $ws = false): array|stdClass
    {
        $this->accion_titulo = 'Ordenes de Compra';

        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $base = $this->base_upd(keys_selects: array(), params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $saldos_ordenes = (new gt_centro_costo($this->link))->total_saldos_orden_compra(gt_centro_costo_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo', data: $saldos_ordenes, header: $header, ws: $ws);
        }

        $saldos_solicitud = (new gt_centro_costo($this->link))->total_saldos_solicitud(gt_centro_costo_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo', data: $saldos_solicitud, header: $header, ws: $ws);
        }

        $saldos_requisicion = (new gt_centro_costo($this->link))->total_saldos_requisicion(gt_centro_costo_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo', data: $saldos_requisicion, header: $header, ws: $ws);
        }

        $saldos_cotizacion = (new gt_centro_costo($this->link))->total_saldos_cotizacion(gt_centro_costo_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo', data: $saldos_cotizacion, header: $header, ws: $ws);
        }

        $this->saldos_cotizacion = $saldos_cotizacion['total'];
        $this->saldos_orden_compra = $saldos_ordenes['total'];
        $this->saldos_solicitud = $saldos_solicitud['total'];
        $this->saldos_requisicion = $saldos_requisicion['total'];

        return $r_modifica;
    }

    /**
     * API para obtener los saldos de cotización de un centro de costo.
     *
     * @param bool $header Indicador para incluir o no encabezados en la respuesta.
     * @param bool $ws Indicador para identificar si la solicitud se hace desde un servicio web.
     * @param array $not_actions Un array de acciones que no se deben realizar durante el procesamiento de la solicitud.
     *
     * @return array|void Un array asociativo con los totales de cotizaciones o un mensaje de error en caso de fallo.
     */
    public function api_sados_cotizacion(bool $header, bool $ws = false, array $not_actions = array())
    {
        $saldos_cotizacion = (new gt_centro_costo($this->link))->total_saldos_cotizacion(gt_centro_costo_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo', data: $saldos_cotizacion, header: $header, ws: $ws);
        }

        $labels = ['Alta', 'Autorizado'];

        $salida = [
            'labels' => $labels,
            'data' => [
                $saldos_cotizacion['total_alta'],
                $saldos_cotizacion['total_autorizado']
            ]
        ];

        header('Content-Type: application/json');
        try {
            echo json_encode($salida, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las cotizaciones', data: $salida,
                header: $header, ws: $ws);
        }
        if (!$header) {
            exit;
        }

        return $salida;
    }

    /**
     * API para obtener los saldos de ordenes de compra de un centro de costo.
     *
     * @param bool $header Indicador para incluir o no encabezados en la respuesta.
     * @param bool $ws Indicador para identificar si la solicitud se hace desde un servicio web.
     * @param array $not_actions Un array de acciones que no se deben realizar durante el procesamiento de la solicitud.
     *
     * @return array|void Un array asociativo con los totales de ordenes de compra o un mensaje de error en caso de fallo.
     */
    public function api_sados_orden_compra(bool $header, bool $ws = false, array $not_actions = array())
    {
        $saldos_ordenes = (new gt_centro_costo($this->link))->total_saldos_orden_compra(gt_centro_costo_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo', data: $saldos_ordenes, header: $header, ws: $ws);
        }

        $labels = ['Alta', 'Autorizado'];

        $salida = [
            'labels' => $labels,
            'data' => [
                $saldos_ordenes['total_alta'],
                $saldos_ordenes['total_autorizado']
            ]
        ];

        header('Content-Type: application/json');
        try {
            echo json_encode($salida, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las las ordenes de compra', data: $salida,
                header: $header, ws: $ws);
        }
        if (!$header) {
            exit;
        }

        return $salida;
    }

    /**
     * API para obtener los saldos de requisiciones de un centro de costo.
     *
     * @param bool $header Indicador para incluir o no encabezados en la respuesta.
     * @param bool $ws Indicador para identificar si la solicitud se hace desde un servicio web.
     * @param array $not_actions Un array de acciones que no se deben realizar durante el procesamiento de la solicitud.
     *
     * @return array|void Un array asociativo con los totales de requisiciones o un mensaje de error en caso de fallo.
     */
    public function api_sados_requisicion(bool $header, bool $ws = false, array $not_actions = array())
    {
        $saldos_requisicion = (new gt_centro_costo($this->link))->total_saldos_requisicion(gt_centro_costo_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo', data: $saldos_requisicion, header: $header, ws: $ws);
        }

        $labels = ['Alta', 'Autorizado'];

        $salida = [
            'labels' => $labels,
            'data' => [
                $saldos_requisicion['total_alta'],
                $saldos_requisicion['total_autorizado']
            ]
        ];

        header('Content-Type: application/json');
        try {
            echo json_encode($salida, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las requisiciones', data: $salida,
                header: $header, ws: $ws);
        }
        if (!$header) {
            exit;
        }

        return $salida;
    }

    /**
     * API para obtener los saldos de solicitudes de un centro de costo.
     *
     * @param bool $header Indicador para incluir o no encabezados en la respuesta.
     * @param bool $ws Indicador para identificar si la solicitud se hace desde un servicio web.
     * @param array $not_actions Un array de acciones que no se deben realizar durante el procesamiento de la solicitud.
     *
     * @return array|void Un array asociativo con los totales de solicitudes o un mensaje de error en caso de fallo.
     */
    public function api_sados_solicitud(bool $header, bool $ws = false, array $not_actions = array())
    {
        $saldos_solicitud = (new gt_centro_costo($this->link))->total_saldos_solicitud(gt_centro_costo_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo', data: $saldos_solicitud, header: $header, ws: $ws);
        }

        $labels = ['Alta', 'Autorizado'];

        $salida = [
            'labels' => $labels,
            'data' => [
                $saldos_solicitud['total_alta'],
                $saldos_solicitud['total_autorizado']
            ]
        ];

        header('Content-Type: application/json');
        try {
            echo json_encode($salida, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las solicitudes', data: $salida,
                header: $header, ws: $ws);
        }
        if (!$header) {
            exit;
        }

        return $salida;
    }
}
