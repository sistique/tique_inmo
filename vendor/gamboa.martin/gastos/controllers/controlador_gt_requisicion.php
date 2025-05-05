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
use gamboamartin\gastos\models\gt_autorizante;
use gamboamartin\gastos\models\gt_autorizante_requisitores;
use gamboamartin\gastos\models\gt_cotizacion;
use gamboamartin\gastos\models\gt_cotizacion_producto;
use gamboamartin\gastos\models\gt_cotizacion_requisicion;
use gamboamartin\gastos\models\gt_empleado_usuario;
use gamboamartin\gastos\models\gt_requisicion;
use gamboamartin\gastos\models\gt_requisicion_etapa;
use gamboamartin\gastos\models\gt_requisicion_producto;
use gamboamartin\gastos\models\gt_requisitores;
use gamboamartin\gastos\models\gt_solicitud;
use gamboamartin\gastos\models\gt_solicitud_etapa;
use gamboamartin\gastos\models\gt_tipo_cotizacion;
use gamboamartin\gastos\models\ModeloConstantes;
use gamboamartin\gastos\models\Stream;
use gamboamartin\gastos\models\Transaccion;
use gamboamartin\proceso\models\pr_etapa_proceso;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\actions;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\gt_autorizante_html;
use html\gt_requisicion_html;
use html\gt_solicitante_html;
use html\gt_solicitud_html;
use html\gt_centro_costo_html;
use html\gt_tipo_solicitud_html;
use PDO;
use stdClass;

class controlador_gt_requisicion extends _ctl_parent_sin_codigo {

    public string $link_partidas = '';
    public string $link_autoriza_bd = '';
    public string $link_rechaza_bd = '';
    public string $link_producto_bd = '';
    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new gt_requisicion(link: $link);
        $html_ = new gt_requisicion_html(html: $html);
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

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        $existe = Transaccion::of(new gt_empleado_usuario($this->link))
            ->existe(filtro: ['gt_empleado_usuario.adm_usuario_id' => $_SESSION['usuario_id']]);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al comprobar si el usuario esta autorizado para hacer requisiciones',
                data: $existe, header: $header, ws: $ws);
        }

        if ($existe->n_registros <= 0) {
            $mensaje = 'Error el usuario no se encuentra autorizado para hacer requisiciones';
            echo "<div class='alert alert-danger alert-dismissible' role='alert'>$mensaje</div>";
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

        $requisitores = Transaccion::of(new gt_requisitores($this->link))
            ->existe(filtro: ['gt_requisitores.gt_requisicion_id' => $this->registro_id]);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al filtrar requisitores', data: $requisitores, header: $header,
                ws: $ws);
        }

        $requisitor_id = Stream::of($requisitores->registros)
            ->map(fn($requisitor) => $requisitor['gt_requisitor_id'])
            ->toArray();

        if ($requisitores->n_registros <= 0) {
            $mensaje = 'No se encontraron requisitores relacionados para esta requisicion';
            echo "<div class='alert alert-warning alert-dismissible' role='alert'>$mensaje</div>";
        }

        $keys_selects['gt_requisitor_id']->cols = 8;
        $keys_selects['gt_requisitor_id']->filtro = array('gt_requisitor.id' => $requisitor_id[0]);
        $keys_selects['gt_requisitor_id']->id_selected = $requisitor_id[0];


        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'fecha',
            keys_selects: $keys_selects, place_holder: 'Fecha');
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'observaciones',
            keys_selects: $keys_selects, place_holder: 'Observaciones');

        $this->row_upd->fecha = date("Y-m-d");

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function autoriza_bd(bool $header, bool $ws = false): array|stdClass
    {
        if ($_POST['gt_requisitor_id'] == null ){
            return $this->retorno_error(mensaje: 'Error no se ha seleccionado un requisitor', data: $_POST,
                header: $header, ws: $ws);
        }

        $existe = (new gt_empleado_usuario($this->link))->filtro_and(filtro: ['gt_empleado_usuario.adm_usuario_id' => $_SESSION['usuario_id']]);
        if (errores::$error) {
            return $this->retorno_error(mensaje: "Error al filtrar el usuario del empleado", data: $existe,header: $header, ws: $ws);
        }

        if ($existe->n_registros <= 0) {
            return $this->retorno_error(mensaje: 'Error el empleado no cuenta con un usuario relacionado para aprobar requisiciones',
                data: $existe, header: $header, ws: $ws);
        }

        $permiso_requisicion = (new gt_autorizante($this->link))->valida_permiso(gt_autorizante_id: $existe->registros[0]['em_empleado_id'],
            proceso: ModeloConstantes::PR_PROCESO_REQUISICION);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al validar permiso de requisicion', data: $permiso_requisicion,
                header: $header, ws: $ws);
        }

        if (!$permiso_requisicion) {
            return $this->retorno_error(mensaje: 'Error el empleado no cuenta con permisos para aprobar requisiciones',
                data: $permiso_requisicion, header: $header, ws: $ws);
        }

        $permiso_requisitor = (new gt_autorizante_requisitores($this->link))->valida_permisos(gt_autorizante_id: $existe->registros[0]['em_empleado_id'],
            gt_requisitor_id: $_POST['gt_requisitor_id']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al validar permisos', data: $permiso_requisitor, header: $header, ws: $ws);
        }

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

        $proceso = ModeloConstantes::PR_PROCESO_REQUISICION->value;
        $etapa = constantes::PR_ETAPA_AUTORIZADO->value;
        $filtro['pr_proceso.descripcion'] = $proceso;
        $filtro['pr_etapa.descripcion'] = $etapa;
        $etapa_proceso = (new pr_etapa_proceso($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: "Error al filtrar etapa $etapa ", data: $etapa_proceso, header: $header, ws: $ws);
        }

        if ($etapa_proceso->n_registros <= 0){
            return $this->retorno_error(mensaje: "Error la etapa '$etapa' no se encuentra registrada",
                data: $etapa_proceso, header: $header, ws: $ws);
        }

        $registro = $etapa_proceso->registros[0];

        $registros['gt_requisicion_id'] = $this->registro_id;
        $registros['pr_etapa_proceso_id'] = $registro['pr_etapa_proceso_id'];
        $registros['fecha'] = $_POST['fecha'];
        $registros['observaciones'] = $_POST['observaciones'];
        $alta = (new gt_requisicion_etapa($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta requisicion etapa', data: $alta,
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

    public function rechaza_bd(bool $header, bool $ws = false): array|stdClass
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

        $proceso = ModeloConstantes::PR_PROCESO_REQUISICION->value;
        $etapa = constantes::PR_ETAPA_RECHAZADO->value;
        $filtro['pr_proceso.descripcion'] = $proceso;
        $filtro['pr_etapa.descripcion'] = $etapa;
        $etapa_proceso = (new pr_etapa_proceso($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: "Error al filtrar etapa $etapa ", data: $etapa_proceso, header: $header, ws: $ws);
        }

        if ($etapa_proceso->n_registros <= 0){
            return $this->retorno_error(mensaje: "Error la etapa '$etapa' no se encuentra registrada",
                data: $etapa_proceso, header: $header, ws: $ws);
        }

        $filtro = array();
        $filtro['gt_requisicion_etapa.gt_requisicion_id'] = $this->registro_id;
        $filtro['gt_requisicion_etapa.pr_etapa_proceso_id'] = $etapa_proceso->registros[0]['pr_etapa_proceso_id'];
        $requisicion_etapa = (new gt_requisicion_etapa($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: "Error al validar etapa $etapa ", data: $requisicion_etapa,
                header: $header, ws: $ws);
        }

        if($requisicion_etapa->n_registros > 0){
            return $this->retorno_error(mensaje: "Error la requisicion ya se encuentra en la etapa '$etapa'",
                data: $requisicion_etapa, header: $header, ws: $ws);
        }

        $registro = $etapa_proceso->registros[0];

        $registros['gt_requisicion_id'] = $this->registro_id;
        $registros['pr_etapa_proceso_id'] = $registro['pr_etapa_proceso_id'];
        $registros['fecha'] = $_POST['fecha'];
        $registros['observaciones'] = $_POST['observaciones'];
        $alta = (new gt_requisicion_etapa($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta requisicion etapa', data: $alta,
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

    protected function campos_view(array $inputs = array()): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion', 'cantidad', 'precio', 'observaciones');
        $keys->telefonos = array();
        $keys->fechas = array('fecha');
        $keys->selects = array();

        $init_data = array();
        $init_data['gt_centro_costo'] = "gamboamartin\\gastos";
        $init_data['gt_tipo_requisicion'] = "gamboamartin\\gastos";
        $init_data['gt_requisitor'] = "gamboamartin\\gastos";
        $init_data['gt_autorizante'] = "gamboamartin\\gastos";
        $init_data['em_empleado'] = "gamboamartin\\empleado";
        $init_data['com_producto'] = "gamboamartin\\comercial";
        $init_data['cat_sat_unidad'] = "gamboamartin\\cat_sat";
        $init_data['gt_tipo_cotizacion'] = "gamboamartin\\gastos";
        $init_data['gt_proveedor'] = "gamboamartin\\gastos";
        $init_data['gt_solicitud'] = "gamboamartin\\gastos";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->seccion_titulo = 'Requisicion';
        $this->titulo_lista = 'Registro de Requisicion';

        $this->lista_get_data = true;

        return $this;
    }

    protected function init_datatable(): stdClass
    {
        $columns["gt_requisicion_id"]["titulo"] = "Id";
        $columns["gt_tipo_requisicion_descripcion"]["titulo"] = "Tipo";
        $columns["gt_centro_costo_descripcion"]["titulo"] = "Centro Costo";
        $columns["gt_requisicion_descripcion"]["titulo"] = "Descripción";
        $columns["gt_requisicion_etapa"]["titulo"] = "Etapa";

        $filtro = array("gt_requisicion.id","gt_tipo_requisicion.descripcion","gt_centro_costo.descripcion",
            "gt_requisicion.descripcion");

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

        $link = $this->obj_link->get_link(seccion: "gt_requisicion", accion: "autoriza_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link autoriza_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_autoriza_bd = $link;

        $link = $this->obj_link->get_link(seccion: "gt_requisicion", accion: "rechaza_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link rechaza_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_rechaza_bd = $link;

        $link = $this->obj_link->get_link(seccion: "gt_requisicion", accion: "producto_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link autoriza_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_producto_bd = $link;

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
        $keys_selects = $this->init_selects(keys_selects: array(), key: "gt_centro_costo_id", label: "Centro Costo", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "em_empleado_id", label: "Empleado", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "gt_requisitor_id", label: "Requisitor", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "gt_autorizante_id", label: "Autorizante", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "gt_tipo_requisicion_id", label: "Tipo Requisicion", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "com_producto_id", label: "Producto", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "gt_tipo_cotizacion_id", label: "Tipo Cotizacion", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "gt_proveedor_id", label: "Proovedor", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "gt_solicitud_id", label: "Solicitud", cols: 12);
        return $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_unidad_id", label: "Unidad", cols: 12);
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Código');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'cantidad',
            keys_selects: $keys_selects, place_holder: 'Cantidad');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'precio',
            keys_selects: $keys_selects, place_holder: 'Precio');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha',
            keys_selects: $keys_selects, place_holder: 'Fecha');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'obervaciones',
            keys_selects: $keys_selects, place_holder: 'Obervaciones');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function modifica(bool $header, bool $ws = false, array $keys_selects = array()): array|stdClass
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

        $keys_selects['gt_centro_costo_id']->id_selected = $this->registro['gt_centro_costo_id'];
        $keys_selects['gt_tipo_requisicion_id']->id_selected = $this->registro['gt_tipo_requisicion_id'];

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');
        $keys_selects['descripcion']->disabled = true;
        $keys_selects['gt_centro_costo_id']->disabled = true;
        $keys_selects['gt_tipo_requisicion_id']->disabled = true;

        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $columns = array();
        $columns["gt_requisicion_producto_id"]["titulo"] = "Id";
        $columns["com_producto_descripcion"]["titulo"] = "Producto";
        $columns["cat_sat_unidad_descripcion"]["titulo"] = "Unidad";
        $columns["gt_requisicion_producto_cantidad"]["titulo"] = "Cantidad";
        $columns["gt_requisicion_producto_precio"]["titulo"] = "Precio";
        $columns["gt_requisicion_producto_total"]["titulo"] = "Total";
        $columns["elimina_bd"]["titulo"] = "Acciones";

        $filtro = array('gt_requisicion_id');
        $data["gt_requisicion.id"] = $this->registro_id;

        $datatables = $this->datatable_init(columns: $columns, filtro: $filtro, identificador: "#gt_requisicion_producto",
            data: $data, in: array(), multi_selects: true);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar datatable', data: $datatables,
                header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function partidas(bool $header, bool $ws = false): array|stdClass
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

        $keys_selects['gt_centro_costo_id']->id_selected = $this->registro['gt_centro_costo_id'];
        $keys_selects['gt_tipo_requisicion_id']->id_selected = $this->registro['gt_tipo_requisicion_id'];

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');
        $keys_selects['descripcion']->disabled = true;
        $keys_selects['gt_centro_costo_id']->disabled = true;
        $keys_selects['gt_tipo_requisicion_id']->disabled = true;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    /**
     * Funcion que de alta una cotizacion
     * @param array $datos datos de la requisicion
     * @return array|stdClass retorna el estado de la accion
     */
    private function alta_cotizacion(array $datos) : array|stdClass
    {
        $registro = array();
        $registro['gt_requisicion_id'] = $datos['gt_requisicion_id'];
        $registro['gt_centro_costo_id'] = $datos['gt_centro_costo_id'];
        $registro['gt_tipo_cotizacion_id'] = $_POST['gt_tipo_cotizacion_id'];
        $registro['gt_proveedor_id'] = $_POST['gt_proveedor_id'];
        $registro['descripcion'] = $this->modelo->get_codigo_aleatorio(10);
        $alta = (new gt_cotizacion($this->link))->alta_registro(registro: $registro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->errores->error(mensaje: "Error al ejecutar alta",data: $alta);
        }

        return $alta;
    }

    /**
     * Funcion que de alta una cotizacion_producto
     * @param stdClass $gt_cotizacion datos de la cotizacion
     * @param int $producto id del producto a relacionar
     * @param stdClass $datos datos de la requisicion producto
     * @return array|stdClass retorna el estado de la accion
     */
    private function alta_cotizacion_producto(stdClass $gt_cotizacion, int $producto, stdClass $datos) : array|stdClass
    {
        $registro = array();
        $registro['gt_cotizacion_id'] = $gt_cotizacion->registro_id;
        $registro['com_producto_id'] = $producto;
        $registro['cat_sat_unidad_id'] = $datos->registros[0]['cat_sat_unidad_id'];
        $registro['cantidad'] = $datos->registros[0]['gt_requisicion_producto_cantidad'];
        $registro['precio'] = $datos->registros[0]['gt_requisicion_producto_precio'];
        $alta = (new gt_cotizacion_producto($this->link))->alta_registro(registro: $registro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->errores->error(mensaje: "Error al ejecutar alta",data: $alta);
        }

        return $alta;
    }

    /**
     * Funcion que de alta una cotizacion_requisicion
     * @param stdClass $gt_cotizacion datos de la cotizacion
     * @return array|stdClass retorna el estado de la accion
     */
    private function alta_cotizacion_requisicion(stdClass $gt_cotizacion) : array|stdClass
    {
        $registro = array();
        $registro['gt_cotizacion_id'] = $gt_cotizacion->registro_id;
        $registro['gt_requisicion_id'] = $this->registro_id;
        $registro['descripcion'] = $this->modelo->get_codigo_aleatorio(10);
        $registro['codigo'] = $this->modelo->get_codigo_aleatorio(10);
        $alta = (new gt_cotizacion_requisicion($this->link))->alta_registro(registro: $registro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->errores->error(mensaje: "Error al ejecutar alta",data: $alta);
        }

        return $alta;
    }

    public function producto_bd(bool $header, bool $ws = false): array|stdClass
    {
        if (!isset($_POST['agregar_producto'])) {
            return $this->retorno_error(mensaje: 'Error no existe agregar_producto', data: $_POST, header: $header,
                ws: $ws);
        }

        $productos_seleccionados = explode(",", $_POST['agregar_producto']);

        if (count($productos_seleccionados) === 0) {
            return $this->retorno_error(mensaje: 'Error no ha seleccionado un producto', data: $_POST, header: $header,
                ws: $ws);
        }

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

        $datos = (new gt_requisicion($this->link))->registro(registro_id :$this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener requisicion', data: $datos,
                header: $header, ws: $ws);
        }

        $gt_cotizacion = $this->alta_cotizacion(datos: $datos);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al dar de alta cotizacion', data: $gt_cotizacion,
                header: $header, ws: $ws);
        }

        foreach ($productos_seleccionados as $producto) {
            $filtro['gt_requisicion_id'] = $this->registro_id;
            $filtro['com_producto_id'] = $producto;
            $datos = (new gt_requisicion_producto($this->link))->filtro_and(filtro : $filtro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al obtener producto de la requisicion', data: $datos,
                    header: $header, ws: $ws);
            }

            if ($datos->n_registros <= 0){
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error no existe el producto asociado a la requisicion', data: $producto,
                    header: $header, ws: $ws);
            }

            $gt_cotizacion_producto = $this->alta_cotizacion_producto(gt_cotizacion: $gt_cotizacion,producto: $producto,datos: $datos);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al dar de alta cotizacion producto', data: $gt_cotizacion_producto,
                    header: $header, ws: $ws);
            }
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id: $this->registro_id, result: $gt_cotizacion,
                siguiente_view: "modifica", ws: $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($gt_cotizacion, JSON_THROW_ON_ERROR);
            exit;
        }
        $gt_cotizacion->siguiente_view = "modifica";

        return $gt_cotizacion;
    }

}
