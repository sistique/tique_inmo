<?php
/**
 * @author Kevin Acuña Vega
 * @version 1.0.0
 * @created 2022-07-07
 * @final En proceso
 *
 */
namespace gamboamartin\proceso\controllers;

use base\controller\controler;
use gamboamartin\errores\errores;
use gamboamartin\proceso\html\pr_etapa_proceso_html;
use gamboamartin\proceso\models\pr_etapa_proceso;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;

use gamboamartin\template_1\html;
use PDO;
use stdClass;

class controlador_pr_etapa_proceso extends _ctl_base {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new pr_etapa_proceso(link: $link);
        $html_ = new pr_etapa_proceso_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $configuraciones = $this->init_configuraciones();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar configuraciones', data: $configuraciones);
            print_r($error);
            die('Error');
        }
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
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
        $keys->inputs = array('codigo','descripcion');
        $keys->selects = array();
        $keys->fechas = array();

        $init_data = array();
        $init_data['pr_proceso'] = "gamboamartin\\proceso";
        $init_data['pr_etapa'] = "gamboamartin\\proceso";
        $init_data['adm_accion'] = "gamboamartin\\administrador";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }
        return $campos_view;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'codigo', keys_selects:
            $keys_selects, place_holder: 'Código');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        return $keys_selects;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de etapas de proceso';

        return $this;
    }

    private function init_selects(array $keys_selects, string $key, string $label, int $id_selected = -1, int $cols = 6,
                                  bool  $con_registros = true, array $filtro = array(), array $columns_ds = array()): array
    {
        $keys_selects = $this->key_select(cols: $cols, con_registros: $con_registros, filtro: $filtro, key: $key,
            keys_selects: $keys_selects, id_selected: $id_selected, label: $label,columns_ds: $columns_ds);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function init_selects_inputs(): array
    {
        $keys_selects = $this->init_selects(keys_selects: array(), key: "pr_proceso_id", label: "Proceso", cols: 12,
            columns_ds: array("pr_proceso_descripcion"));
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "pr_etapa_id", label: "Etapa", cols: 12,
            columns_ds: array("pr_etapa_descripcion"));
        return $this->init_selects(keys_selects: $keys_selects, key: "adm_accion_id", label: "Acción", cols: 12,
            columns_ds: array("adm_accion_descripcion_select"));
    }

    final public function init_datatable(): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['pr_etapa_proceso_id']['titulo'] = 'Id';
        $datatables->columns['pr_proceso_descripcion']['titulo'] = 'Proceso';
        $datatables->columns['pr_etapa_descripcion']['titulo'] = 'Etapa';
        $datatables->columns['adm_accion_descripcion']['titulo'] = 'Acción';

        $datatables->filtro = array();
        $datatables->filtro[] = 'pr_etapa_proceso.id';
        $datatables->filtro[] = 'pr_proceso.descripcion';
        $datatables->filtro[] = 'pr_etapa.descripcion';
        $datatables->filtro[] = 'adm_accion.descripcion';

        return $datatables;
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

        $keys_selects['pr_proceso_id']->id_selected = $this->registro['pr_proceso_id'];
        $keys_selects['pr_etapa_id']->id_selected = $this->registro['pr_etapa_id'];
        $keys_selects['adm_accion_id']->id_selected = $this->registro['adm_accion_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

}