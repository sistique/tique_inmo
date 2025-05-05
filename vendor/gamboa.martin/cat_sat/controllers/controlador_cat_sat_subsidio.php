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
use gamboamartin\cat_sat\models\cat_sat_subsidio;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\cat_sat_subsidio_html;
use PDO;
use stdClass;

class controlador_cat_sat_subsidio extends _cat_sat_base {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new cat_sat_subsidio(link: $link);
        $html_ = new cat_sat_subsidio_html(html: $html);
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



    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion', 'limite_inferior', 'limite_superior', 'cuota_fija',
            'porcentaje_excedente', 'fecha_inicio', 'fecha_fin');
        $keys->selects = array();

        $init_data = array();
        $init_data['cat_sat_periodicidad_pago_nom'] = "gamboamartin\\cat_sat";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Subsidios';

        return $this;
    }

    private function init_datatable(): stdClass
    {
        $columns["cat_sat_subsidio_id"]["titulo"] = "Id";
        $columns["cat_sat_subsidio_codigo"]["titulo"] = "Código";
        $columns["cat_sat_subsidio_descripcion"]["titulo"] = "Descripción";
        $columns["cat_sat_subsidio_limite_inferior"]["titulo"] = "Lim. Inferior";
        $columns["cat_sat_subsidio_limite_superior"]["titulo"] = "Lim. Superior";
        $columns["cat_sat_subsidio_cuota_fija"]["titulo"] = "Cuota Fija";
        $columns["cat_sat_subsidio_porcentaje_excedente"]["titulo"] = "% Excedente";
        $columns["cat_sat_periodicidad_pago_nom_descripcion"]["titulo"] = "Periodicidad";
        $columns["cat_sat_subsidio_fecha_inicio"]["titulo"] = "Fecha Inicio";
        $columns["cat_sat_subsidio_fecha_fin"]["titulo"] = "Fecha Fin";

        $filtro = array("cat_sat_subsidio.id", "cat_sat_subsidio.codigo", "cat_sat_subsidio.descripcion",
            "cat_sat_subsidio.limite_inferior", "cat_sat_subsidio.limite_superior",
            "cat_sat_periodicidad_pago_nom.descripcion");

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
        return $this->init_selects(keys_selects: array(), key: "cat_sat_periodicidad_pago_nom_id", label: "Periodicidad",
            cols: 12);
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

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'limite_inferior',
            keys_selects: $keys_selects, place_holder: 'Lim. Inferior');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'limite_superior',
            keys_selects: $keys_selects, place_holder: 'Lim. Superior');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'cuota_fija',
            keys_selects: $keys_selects, place_holder: 'Cuota Fija');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'porcentaje_excedente',
            keys_selects: $keys_selects, place_holder: 'Porc. Excedente');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_inicio',
            keys_selects: $keys_selects, place_holder: 'Fecha Inicio');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_fin',
            keys_selects: $keys_selects, place_holder: 'Fecha Fin');
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

        $keys_selects['cat_sat_periodicidad_pago_nom_id']->id_selected = $this->registro['cat_sat_periodicidad_pago_nom_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }



}
