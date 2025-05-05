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
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\errores\errores;
use gamboamartin\gastos\models\gt_proveedor;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\gt_proveedor_html;

use PDO;
use stdClass;
use Throwable;

class controlador_gt_proveedor extends _ctl_base
{

    public $saldos_cotizacion;
    public $saldos_orden_compra;

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new gt_proveedor(link: $link);
        $html_ = new gt_proveedor_html(html: $html);
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
        $keys->inputs = array('codigo', 'descripcion', 'rfc', 'exterior', 'interior', 'contacto_1', 'contacto_2',
            'contacto_3', 'pagina_web', 'razon_social', 'telefono_1', 'telefono_2', 'telefono_3');
        $keys->telefonos = array();
        $keys->fechas = array();
        $keys->selects = array();

        $init_data = array();
        $init_data['dp_pais'] = "gamboamartin\\direccion_postal";
        $init_data['dp_estado'] = "gamboamartin\\direccion_postal";
        $init_data['dp_municipio'] = "gamboamartin\\direccion_postal";
        $init_data['dp_cp'] = "gamboamartin\\direccion_postal";
        $init_data['dp_colonia_postal'] = "gamboamartin\\direccion_postal";
        $init_data['dp_calle_pertenece'] = "gamboamartin\\direccion_postal";
        $init_data['cat_sat_regimen_fiscal'] = "gamboamartin\\cat_sat";
        $init_data['gt_tipo_proveedor'] = "gamboamartin\\gastos";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->seccion_titulo = 'Proveedor';
        $this->titulo_lista = 'Registro de Proveedores';

        $this->lista_get_data = true;

        return $this;
    }

    protected function init_datatable(): stdClass
    {
        $columns["gt_proveedor_id"]["titulo"] = "Id";
        $columns["gt_tipo_proveedor_descripcion"]["titulo"] = "Tipo";
        $columns["cat_sat_regimen_fiscal_descripcion"]["titulo"] = "Régimen Fiscal";
        $columns["gt_proveedor_rfc"]["titulo"] = "RFC";
        $columns["gt_proveedor_razon_social"]["titulo"] = "Razón Social";
        $columns["gt_proveedor_descripcion"]["titulo"] = "Descripción";

        $filtro = array("gt_proveedor.id", "gt_tipo_proveedor.descripcion", "cat_sat_regimen_fiscal.descripcion",
            "gt_proveedor.rfc", "gt_proveedor.razon_social", "gt_proveedor.descripcion");

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
        $this->keys_selects = $this->init_selects(keys_selects: array(), key: "dp_pais_id", label: "País");
        $this->keys_selects = $this->init_selects(keys_selects: $this->keys_selects, key: "dp_estado_id", label: "Estado",
            con_registros: false);
        $this->keys_selects = $this->init_selects(keys_selects: $this->keys_selects, key: "dp_municipio_id", label: "Municipio",
            con_registros: false);
        $this->keys_selects = $this->init_selects(keys_selects: $this->keys_selects, key: "dp_cp_id", label: "CP",
            con_registros: false);
        $this->keys_selects = $this->init_selects(keys_selects: $this->keys_selects, key: "dp_colonia_postal_id", label: "Colonia",
            con_registros: false);
        $this->keys_selects = $this->init_selects(keys_selects: $this->keys_selects, key: "dp_calle_pertenece_id", label: "Calle",
            con_registros: false);
        $this->keys_selects = $this->init_selects(keys_selects: $this->keys_selects, key: "gt_tipo_proveedor_id", label: "Tipo");
        $this->keys_selects = $this->init_selects(keys_selects: $this->keys_selects, key: "cat_sat_regimen_fiscal_id",
            label: "Régimen Fiscal");

        return $this->keys_selects;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Código');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'rfc',
            keys_selects: $keys_selects, place_holder: 'RFC');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'exterior',
            keys_selects: $keys_selects, place_holder: 'Exterior');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'interior',
            keys_selects: $keys_selects, place_holder: 'Interior', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'telefono_1',
            keys_selects: $keys_selects, place_holder: 'Teléfono 1');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'telefono_2',
            keys_selects: $keys_selects, place_holder: 'Teléfono 2', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'telefono_3',
            keys_selects: $keys_selects, place_holder: 'Teléfono 3', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'contacto_1',
            keys_selects: $keys_selects, place_holder: 'Contacto 1');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'contacto_2',
            keys_selects: $keys_selects, place_holder: 'Contacto 2', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'contacto_3',
            keys_selects: $keys_selects, place_holder: 'Contacto 3', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'contacto_3',
            keys_selects: $keys_selects, place_holder: 'Contacto 3');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'pagina_web',
            keys_selects: $keys_selects, place_holder: 'Pagina web');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 8, key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razón social');
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

        $calle = (new dp_calle_pertenece($this->link))->get_calle_pertenece($this->registro['dp_calle_pertenece_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener calle', data: $calle);
        }

        $identificador = "dp_pais_id";
        $propiedades = array("id_selected" => $calle['dp_pais_id']);
        $this->asignar_propiedad(identificador: $identificador, propiedades: $propiedades);

        $identificador = "dp_estado_id";
        $propiedades = array("id_selected" => $calle['dp_estado_id'], "con_registros" => true,
            "filtro" => array('dp_pais.id' => $calle['dp_pais_id']));
        $this->asignar_propiedad(identificador: $identificador, propiedades: $propiedades);

        $identificador = "dp_municipio_id";
        $propiedades = array("id_selected" => $calle['dp_municipio_id'], "con_registros" => true,
            "filtro" => array('dp_estado.id' => $calle['dp_estado_id']));
        $this->asignar_propiedad(identificador: $identificador, propiedades: $propiedades);

        $identificador = "dp_cp_id";
        $propiedades = array("id_selected" => $calle['dp_cp_id'], "con_registros" => true,
            "filtro" => array('dp_estado.id' => $calle['dp_estado_id']));
        $this->asignar_propiedad(identificador: $identificador, propiedades: $propiedades);

        $identificador = "dp_colonia_postal_id";
        $propiedades = array("id_selected" => $calle['dp_colonia_postal_id'], "con_registros" => true,
            "filtro" => array('dp_cp.id' => $calle['dp_cp_id']));
        $this->asignar_propiedad(identificador: $identificador, propiedades: $propiedades);

        $identificador = "dp_calle_pertenece_id";
        $propiedades = array("id_selected" => $this->row_upd->dp_calle_pertenece_id, "con_registros" => true,
            "filtro" => array('dp_colonia_postal.id' => $calle['dp_colonia_postal_id']));
        $this->asignar_propiedad(identificador: $identificador, propiedades: $propiedades);

        $identificador = "cat_sat_regimen_fiscal_id";
        $propiedades = array("id_selected" => $this->registro['cat_sat_regimen_fiscal_id']);
        $this->asignar_propiedad(identificador: $identificador, propiedades: $propiedades);

        $identificador = "gt_tipo_proveedor_id";
        $propiedades = array("id_selected" => $this->registro['gt_tipo_proveedor_id']);
        $this->asignar_propiedad(identificador: $identificador, propiedades: $propiedades);

        $base = $this->base_upd(keys_selects: $this->keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }


    public function saldos(bool $header, bool $ws = false): array|stdClass
    {
        $this->accion_titulo = 'Cotizaciones';

        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $base = $this->base_upd(keys_selects: array(), params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $saldos = (new gt_proveedor($this->link))->total_saldos_cotizacion(gt_proveedor_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las cotizaciones', data: $saldos,
                header: $header, ws: $ws);
        }

        $orden_compra = (new gt_proveedor($this->link))->total_saldos_orden_compra(gt_proveedor_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las ordenes de compra', data: $saldos,
                header: $header, ws: $ws);
        }

        $this->saldos_cotizacion = $saldos['total'];
        $this->saldos_orden_compra = $orden_compra['total'];

        return $r_modifica;
    }

    // APIS para consumo de datos

    /**
     * API para obtener los saldos de cotización de un proveedor.
     *
     * @param bool $header Indicador para incluir o no encabezados en la respuesta.
     * @param bool $ws Indicador para identificar si la solicitud se hace desde un servicio web.
     * @param array $not_actions Un array de acciones que no se deben realizar durante el procesamiento de la solicitud.
     *
     * @return array|void Un array asociativo con los totales de cotizaciones o un mensaje de error en caso de fallo.
     */
    public function api_sados_cotizacion(bool $header, bool $ws = false, array $not_actions = array())
    {
        $saldos = (new gt_proveedor($this->link))->total_saldos_cotizacion(gt_proveedor_id: $_GET['registro_id']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las cotizaciones', data: $saldos,
                header: $header, ws: $ws);
        }

        $labels = ['Alta', 'Autorizado'];

        $salida = [
            'labels' => $labels,
            'data' => [
                $saldos['total_alta'],
                $saldos['total_autorizado']
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
     * API para obtener los saldos de órdenes de compra de un proveedor.
     *
     * @param bool $header Indicador para incluir o no encabezados en la respuesta.
     * @param bool $ws Indicador para identificar si la solicitud se hace desde un servicio web.
     * @param array $not_actions Un array de acciones que no se deben realizar durante el procesamiento de la solicitud.
     *
     * @return array|void Un array asociativo con los totales de órdenes de compra o un mensaje de error en caso de fallo.
     */
    public function api_sados_orden_compra(bool $header, bool $ws = false, array $not_actions = array())
    {
        $saldos = (new gt_proveedor($this->link))->total_saldos_orden_compra(gt_proveedor_id: $_GET['registro_id']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las ordenes de compra', data: $saldos,
                header: $header, ws: $ws);
        }

        $labels = ['Alta', 'Autorizado'];

        $salida = [
            'labels' => $labels,
            'data' => [
                $saldos['total_alta'],
                $saldos['total_autorizado']
            ]
        ];

        header('Content-Type: application/json');
        try {
            echo json_encode($salida, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            return $this->retorno_error(mensaje: 'Error al obtener saldo de las ordenes de compra', data: $salida,
                header: $header, ws: $ws);
        }
        if (!$header) {
            exit;
        }

        return $salida;
    }
}