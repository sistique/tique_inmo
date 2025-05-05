<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;

use base\controller\controler;
use base\controller\init;
use gamboamartin\cat_sat\models\cat_sat_periodo;
use gamboamartin\errores\errores;

use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\cat_sat_periodo_html;
use PDO;
use stdClass;

class controlador_cat_sat_periodo extends _cat_sat_base {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new cat_sat_periodo(link: $link);
        $html_ = new cat_sat_periodo_html(html: $html);
        $obj_link = new links_menu(link: $link,registro_id:  $this->registro_id);

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
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'cat_sat_periodicidad_pago_nom_id',
            keys_selects: array(), id_selected: -1, label: 'Periodicidad');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_selects_txt(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    protected function campos_view(array $inputs = array()): array
    {
        $keys = new stdClass();
        $keys->inputs = array();
        $keys->telefonos = array();
        $keys->fechas = array('fecha_inicio','fecha_fin');
        $keys->selects = array();

        $init_data = array();
        $init_data['cat_sat_periodicidad_pago_nom'] = "gamboamartin\\cat_sat";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    public function get_dias(bool $header, bool $ws = true): array|stdClass
    {
        $keys['cat_sat_periodicidad_pago_nom'] = array('id', 'descripcion', 'codigo', 'codigo_bis');

        $salida = $this->get_out(header: $header, keys: $keys, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar salida', data: $salida, header: $header, ws: $ws);
        }

        return $salida;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Periodos';

        $this->lista_get_data = true;

        return $this;
    }

    private function init_datatable(): stdClass
    {
        $columns["cat_sat_periodo_id"]["titulo"] = "Id";
        $columns["cat_sat_periodo_codigo"]["titulo"] = "Código";
        $columns["cat_sat_periodo_fecha_inicio"]["titulo"] = "Inicio";
        $columns["cat_sat_periodo_fecha_fin"]["titulo"] = "Fin";
        $columns["cat_sat_periodicidad_pago_nom_descripcion"]["titulo"] = "Periodicidad";

        $filtro = array("cat_sat_periodo.id","cat_sat_periodo.codigo","cat_sat_periodo.fecha_inicio",
            "cat_sat_periodo.fecha_fin", "cat_sat_periodicidad_pago_nom.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'fecha_inicio',
            keys_selects: $keys_selects, place_holder: 'Fecha Inicial');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'fecha_fin',
            keys_selects: $keys_selects, place_holder: 'Fecha Final');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
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

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'cat_sat_periodicidad_pago_nom_id',
            keys_selects: array(), id_selected: $this->row_upd->cat_sat_periodicidad_pago_nom_id, label: 'Periodicidad');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

}
