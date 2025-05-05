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
use gamboamartin\gastos\models\gt_autorizantes;
use gamboamartin\gastos\models\gt_requisicion;
use gamboamartin\gastos\models\gt_solicitud;
use gamboamartin\gastos\models\gt_solicitud_etapa;
use gamboamartin\gastos\models\gt_solicitud_requisicion;
use gamboamartin\gastos\models\gt_tipo_requisicion;
use gamboamartin\proceso\models\pr_etapa_proceso;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\actions;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\gt_autorizantes_html;
use PDO;
use stdClass;

class controlador_gt_autorizantes extends _ctl_base {

    public string $link_autoriza_bd = '';

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new gt_autorizantes(link: $link);
        $html_ = new gt_autorizantes_html(html: $html);
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

        $init_links = $this->init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
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

    public function autoriza(bool $header, bool $ws = false): array|stdClass
    {
        $this->accion_titulo = 'Autoriza';

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

        $keys_selects['gt_solicitud_id']->id_selected = $this->registro['gt_solicitud_id'];
        $keys_selects['gt_solicitud_id']->filtro = array("gt_solicitud.id" => $this->registro['gt_solicitud_id']);
        $keys_selects['gt_solicitud_id']->disabled = true;

        $keys_selects['gt_solicitud_id']->cols = 6;

        $this->row_upd->fecha = date("Y-m-d");

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function autoriza_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }

        $solicitud = $this->modelo->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error no se pudo obtener los datos de solicitud', data: $solicitud,
                header: $header, ws: $ws);
        }

        $etapa = constantes::PR_ETAPA_AUTORIZADO->value;
        $filtro['pr_etapa.descripcion'] = $etapa;
        $etapa_proceso = (new pr_etapa_proceso($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $etapa_proceso, header: $header, ws: $ws);
        }

        if ($etapa_proceso->n_registros <= 0){
            return $this->retorno_error(mensaje: "Error no existe la relacion de etapa proceso: $etapa",
                data: $etapa_proceso, header: $header, ws: $ws);
        }

        $registros = array();
        $registros['gt_solicitud_id'] = $solicitud['gt_solicitud_id'];
        $registros['pr_etapa_proceso_id'] = $etapa_proceso->registros[0]['pr_etapa_proceso_id'];
        $registros['fecha'] = $_POST['fecha'];
        $registros['descripcion'] = "Autorizacion";
        $registros['codigo'] = $this->modelo->get_codigo_aleatorio(12);
        $alta = (new gt_solicitud_etapa($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta solicitud etapa', data: $alta,
                header: $header, ws: $ws);
        }

        $filtro = array();
        $tipo = constantes::GT_TIPO_REQUISICION_DEFAULT->value;
        $filtro['gt_tipo_requisicion.descripcion'] = $tipo;
        $tipo_requisicion = (new gt_tipo_requisicion($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $tipo_requisicion, header: $header, ws: $ws);
        }

        if ($tipo_requisicion->n_registros <= 0){
            return $this->retorno_error(mensaje: "Error no existe el tipo de requisicion: $tipo",
                data: $etapa_proceso, header: $header, ws: $ws);
        }

        $registros = array();
        $registros['gt_solicitud_id'] = $solicitud['gt_solicitud_id'];
        $registros['gt_centro_costo_id'] = $solicitud['gt_centro_costo_id'];
        $registros['gt_tipo_requisicion_id'] = $tipo_requisicion->registros[0]['gt_tipo_requisicion_id'];
        $registros['etapa'] = 'ALTA';
        $registros['descripcion'] = "Solicitud de requisición";
        $alta = (new gt_requisicion($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta requisicion', data: $alta,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id: $this->registro_id, result: $alta,
                siguiente_view: "lista", ws: $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($alta, JSON_THROW_ON_ERROR);
            exit;
        }
        $alta->siguiente_view = "lista";

        return $alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion');
        $keys->telefonos = array();
        $keys->fechas = array('fecha');
        $keys->selects = array();

        $init_data = array();
        $init_data['gt_solicitud'] = "gamboamartin\\gastos";
        $init_data['gt_autorizante'] = "gamboamartin\\gastos";


        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->seccion_titulo = 'Autorizantes';
        $this->titulo_lista = 'Registro de Autorizantes';

        $this->lista_get_data = true;

        return $this;
    }

    protected function init_datatable(): stdClass
    {
        $columns["gt_autorizantes_id"]["titulo"] = "Id";
        $columns["em_empleado_nombre"]["titulo"] = "Autorizante";
        $columns["em_empleado_nombre"]["campos"] = array("em_empleado_ap","em_empleado_am");
        $columns["gt_solicitud_descripcion"]["titulo"] = "Solicitud";
        $columns["gt_centro_costo_descripcion"]["titulo"] = "Centro Costo";

        $filtro = array("gt_autorizantes.id","gt_autorizante.descripcion","em_empleado.nombre","em_empleado.ap",
            "em_empleado.am", "gt_solicitud.descripcion", "gt_centro_costo.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    protected function init_links(): array|string
    {
        $links = $this->obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "gt_autorizantes", accion: "autoriza_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link autoriza_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_autoriza_bd = $link;

        return $link;
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
        $keys_selects = $this->init_selects(keys_selects: array(), key: "gt_solicitud_id", label: "Solicitud", cols: 12);
        return $this->init_selects(keys_selects: $keys_selects, key: "gt_autorizante_id", label: "Autorizante", cols: 12);
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

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha',
            keys_selects: $keys_selects, place_holder: 'Fecha');
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

        $keys_selects['gt_solicitud_id']->id_selected = $this->registro['gt_solicitud_id'];
        $keys_selects['gt_autorizante_id']->id_selected = $this->registro['gt_autorizante_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }


}