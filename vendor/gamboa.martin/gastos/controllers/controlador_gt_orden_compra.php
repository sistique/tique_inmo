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
use gamboamartin\gastos\models\gt_autorizante_ejecutores_compra;
use gamboamartin\gastos\models\gt_cotizacion_producto;
use gamboamartin\gastos\models\gt_ejecutores_compra;
use gamboamartin\gastos\models\gt_empleado_usuario;
use gamboamartin\gastos\models\gt_orden_compra;
use gamboamartin\gastos\models\gt_orden_compra_cotizacion;
use gamboamartin\gastos\models\gt_orden_compra_etapa;
use gamboamartin\gastos\models\gt_requisitores;
use gamboamartin\gastos\models\gt_solicitantes;
use gamboamartin\gastos\models\ModeloConstantes;
use gamboamartin\gastos\models\Stream;
use gamboamartin\gastos\models\Transaccion;
use gamboamartin\proceso\models\pr_etapa_proceso;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\actions;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use gamboamartin\template\html;
use html\gt_orden_compra_html;
use html\gt_requisitores_html;
use html\gt_solicitante_html;
use html\gt_solicitantes_html;
use html\gt_solicitud_html;

use PDO;
use stdClass;

class controlador_gt_orden_compra extends _ctl_base {

    public string $link_partida_bd = '';
    public string $link_autoriza_bd = '';

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new gt_orden_compra(link: $link);
        $html_ = new gt_orden_compra_html(html: $html);
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

        $ejecutores = Transaccion::of(new gt_ejecutores_compra($this->link))
            ->existe(filtro: ['gt_ejecutores_compra.gt_orden_compra_id' => $this->registro_id]);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al filtrar ejecutores', data: $ejecutores, header: $header,
                ws: $ws);
        }

        $ejecutor_id = Stream::of($ejecutores->registros)
            ->map(fn($ejecutor) => $ejecutor['gt_ejecutor_compra_id'])
            ->toArray();

        if ($ejecutores->n_registros <= 0) {
            $mensaje = 'No se encontraron ejecutores relacionados para esta orden de compra';
            echo "<div class='alert alert-warning alert-dismissible' role='alert'>$mensaje</div>";
        }

        $keys_selects['gt_ejecutor_compra_id']->cols = 8;
        $keys_selects['gt_ejecutor_compra_id']->filtro = array('gt_ejecutor_compra.id' => $ejecutor_id[0]);
        $keys_selects['gt_ejecutor_compra_id']->id_selected = $ejecutor_id[0];

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
        if ($_POST['gt_ejecutor_compra_id'] == null ){
            return $this->retorno_error(mensaje: 'Error no se ha seleccionado un ejecutor de la compra', data: $_POST,
                header: $header, ws: $ws);
        }

        $existe = (new gt_empleado_usuario($this->link))->filtro_and(filtro: ['gt_empleado_usuario.adm_usuario_id' => $_SESSION['usuario_id']]);
        if (errores::$error) {
            return $this->retorno_error(mensaje: "Error al filtrar el usuario del empleado", data: $existe,header: $header, ws: $ws);
        }

        if ($existe->n_registros <= 0) {
            return $this->retorno_error(mensaje: 'Error el empleado no cuenta con un usuario relacionado para aprobar ordenes de compra',
                data: $existe, header: $header, ws: $ws);
        }

        $permiso_ejecutor = (new gt_autorizante($this->link))->valida_permiso(gt_autorizante_id: $existe->registros[0]['em_empleado_id'],
            proceso: ModeloConstantes::PR_PROCESO_ORDEN_COMPRA);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al validar permiso de orden de compra', data: $permiso_ejecutor,
                header: $header, ws: $ws);
        }

        if (!$permiso_ejecutor) {
            return $this->retorno_error(mensaje: 'Error el empleado no cuenta con permisos para aprobar ordenes de compra',
                data: $permiso_ejecutor, header: $header, ws: $ws);
        }

        $permiso_orden = (new gt_autorizante_ejecutores_compra($this->link))->valida_permisos(gt_autorizante_id: $existe->registros[0]['em_empleado_id'],
           gt_ejecutor_compra_id: $_POST['gt_ejecutor_compra_id']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al validar permisos', data: $permiso_orden, header: $header, ws: $ws);
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

        $proceso = ModeloConstantes::PR_PROCESO_ORDEN_COMPRA->value;
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

        $registros['gt_orden_compra_id'] = $this->registro_id;
        $registros['pr_etapa_proceso_id'] = $registro['pr_etapa_proceso_id'];
        $registros['fecha'] = $_POST['fecha'];
        $registros['observaciones'] = $_POST['observaciones'];
        $alta = (new gt_orden_compra_etapa($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta orden de compra etapa', data: $alta,
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

        $proceso = ModeloConstantes::PR_PROCESO_ORDEN_COMPRA->value;
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
        $filtro['gt_orden_compra_etapa.gt_orden_compra_id'] = $this->registro_id;
        $filtro['gt_orden_compra_etapa.pr_etapa_proceso_id'] = $etapa_proceso->registros[0]['pr_etapa_proceso_id'];
        $cotizacion_etapa = (new gt_orden_compra_etapa($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: "Error al validar etapa $etapa ", data: $cotizacion_etapa,
                header: $header, ws: $ws);
        }

        if($cotizacion_etapa->n_registros > 0){
            return $this->retorno_error(mensaje: "Error la orden de compra ya se encuentra en la etapa '$etapa'",
                data: $cotizacion_etapa, header: $header, ws: $ws);
        }

        $registro = $etapa_proceso->registros[0];

        $registros['gt_orden_compra_id'] = $this->registro_id;
        $registros['pr_etapa_proceso_id'] = $registro['pr_etapa_proceso_id'];
        $registros['fecha'] = $_POST['fecha'];
        $registros['observaciones'] = $_POST['observaciones'];
        $alta = (new gt_orden_compra_etapa($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta orden de compra etapa', data: $alta,
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
        $keys->inputs = array('codigo', 'descripcion', 'cantidad', 'precio', 'observaciones');
        $keys->telefonos = array();
        $keys->fechas = array('fecha');
        $keys->selects = array();

        $init_data = array();
        $init_data['gt_cotizacion'] = "gamboamartin\\gastos";
        $init_data['gt_tipo_orden_compra'] = "gamboamartin\\gastos";
        $init_data['gt_ejecutor_compra'] = "gamboamartin\\gastos";
        $init_data['com_producto'] = "gamboamartin\\comercial";
        $init_data['cat_sat_unidad'] = "gamboamartin\\cat_sat";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->seccion_titulo = 'Ordenes de Compra';
        $this->titulo_lista = 'Registro de Ordenes de Compra';

        $this->lista_get_data = true;

        return $this;
    }

    protected function init_datatable(): stdClass
    {
        $columns["gt_orden_compra_id"]["titulo"] = "Id";
        $columns["gt_tipo_orden_compra_descripcion"]["titulo"] = "Tipo";
        $columns["gt_orden_compra_descripcion"]["titulo"] = "Descripción";
        $columns["gt_orden_compra_etapa"]["titulo"] = "Etapa";

        $filtro = array("gt_orden_compra.id","gt_orden_compra.descripcion");

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

        $link = $this->obj_link->get_link(seccion: "gt_orden_compra", accion: "autoriza_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link autoriza_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_autoriza_bd = $link;

        $link = $this->obj_link->get_link(seccion: "gt_orden_compra", accion: "partidas_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link partidas_bd', data: $link);
            print_r($error);
            exit;
        }

        $this->link_partida_bd = $link;

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
        $keys_selects = $this->init_selects(keys_selects: array(), key: "gt_cotizacion_id", label: "Cotización", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "gt_tipo_orden_compra_id", label: "Tipo Orden Compra", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "com_producto_id", label: "Producto", cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "gt_ejecutor_compra_id", label: "Ejecutor", cols: 8);
        return $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_unidad_id", label: "Unidad", cols: 12);
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

        $keys_selects['gt_tipo_orden_compra_id']->id_selected = $this->registro['gt_tipo_orden_compra_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $columns = array();
        $columns["gt_orden_compra_producto_id"]["titulo"] = "Id";
        $columns["com_producto_descripcion"]["titulo"] = "Producto";
        $columns["cat_sat_unidad_descripcion"]["titulo"] = "Unidad";
        $columns["gt_orden_compra_producto_cantidad"]["titulo"] = "Cantidad";
        $columns["gt_orden_compra_producto_precio"]["titulo"] = "Precio";
        $columns["gt_orden_compra_producto_total"]["titulo"] = "Total";
        $columns["elimina_bd"]["titulo"] = "Acciones";

        $filtro = array('gt_orden_compra_id');
        $data["gt_orden_compra.id"] = $this->registro_id;

        $datatables = $this->datatable_init(columns: $columns, filtro: $filtro, identificador: "#gt_orden_compra_producto",
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

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    private function alta_cotizacion_producto(array $datos)
    {
        $contizacion_producto['gt_cotizacion_id'] = $datos['gt_cotizacion_id'];
        $contizacion_producto['com_producto_id'] = $datos['com_producto_id'];
        $contizacion_producto['cat_sat_unidad_id'] = $datos['cat_sat_unidad_id'];
        $contizacion_producto['cantidad'] = $datos['cantidad'];
        $contizacion_producto['precio'] = $datos['precio'];
        $alta = (new gt_cotizacion_producto($this->link))->alta_registro(registro: $contizacion_producto);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->errores->error(mensaje: "Error al ejecutar alta",data: $alta);
        }

        return $alta;
    }

    private function alta_orden_compra_cotizacion(stdClass $gt_cotizacion_producto, array $datos)
    {
        $orden_compra['gt_cotizacion_id'] = $datos['gt_cotizacion_id'];
        $orden_compra['gt_orden_compra_id'] = $this->registro_id;
        $orden_compra['gt_cotizacion_producto_id'] = $gt_cotizacion_producto->registro_id;
        $orden_compra['descripcion'] = $this->modelo->get_codigo_aleatorio(10);

        $alta = (new gt_orden_compra_cotizacion($this->link))->alta_registro(registro: $orden_compra);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->errores->error(mensaje: "Error al ejecutar alta",data: $alta);
        }

        return $alta;
    }

    public function partidas_bd(bool $header, bool $ws = false): array|stdClass
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

        $gt_cotizacion_producto = $this->alta_cotizacion_producto(datos: $_POST);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al dar de alta cotizacion producto', data: $gt_cotizacion_producto,
                header: $header, ws: $ws);
        }

        $gt_orden_compra_cotizacion = $this->alta_cotizacion_producto(datos: $_POST);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al dar de alta orden compra cotizacion', data: $gt_orden_compra_cotizacion,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        $link = "./index.php?seccion=gt_orden_compra&accion=partidas&registro_id=".$this->registro_id;
        $link.="&session_id=$this->session_id";
        header('Location:' . $link);
        exit;
    }


}
