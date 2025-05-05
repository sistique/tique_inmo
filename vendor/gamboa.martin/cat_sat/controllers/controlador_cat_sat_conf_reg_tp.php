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
use gamboamartin\cat_sat\models\cat_sat_conf_reg_tp;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use gamboamartin\cat_sat\models\cat_sat_tipo_persona;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\cat_sat_conf_reg_tp_html;
use PDO;
use stdClass;

class controlador_cat_sat_conf_reg_tp extends _ctl_parent_sin_codigo {


    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new cat_sat_conf_reg_tp(link: $link);
        $html_ = new cat_sat_conf_reg_tp_html(html: $html);

        $obj_link = new links_menu(link: $link,registro_id: -1);


        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datos', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);


        $this->parents_verifica[] = new cat_sat_tipo_persona(link: $this->link);
        $this->parents_verifica[] = new cat_sat_regimen_fiscal(link: $this->link);
        $this->verifica_parents_alta = true;

    }


    /**
     * INtegra los campos de las vistas de alta y modifica
     * @param array $inputs Input previamente cargados
     * @return array
     */
    protected function campos_view(array $inputs = array()): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion');
        $keys->selects = array();
        $keys->selects['cat_sat_tipo_persona_id'] = new stdClass();
        $keys->selects['cat_sat_regimen_fiscal_id'] = new stdClass();

        $init_data = array();
        $init_data['cat_sat_tipo_persona'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_regimen_fiscal'] = "gamboamartin\\cat_sat";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }


    /**
     * Inicializa las configuraciones del controlador
     * @return controler
     */
    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Configuraciones';

        $this->path_vendor_views = 'gamboa.martin/cat_sat';
        $this->lista_get_data = true;

        return $this;
    }

    /**
     * Inicializa los elementos para lista de tipo datatable
     * @return stdClass
     * @version 7.25.1
     */
    private function init_datatable(): stdClass
    {
        $columns["cat_sat_tipo_persona_id"]["titulo"] = "Id Tipo Persona";
        $columns["cat_sat_tipo_persona_codigo"]["titulo"] = "Cod Tipo Persona";
        $columns["cat_sat_regimen_fiscal_id"]["titulo"] = "Id Regimen";
        $columns["cat_sat_regimen_fiscal_codigo"]["titulo"] = "Cod Regimen";
        $columns["cat_sat_regimen_fiscal_descripcion"]["titulo"] = "Regimen";


        $filtro = array("cat_sat_tipo_persona.id", "cat_sat_tipo_persona.codigo", "cat_sat_regimen_fiscal_id.id",
            "cat_sat_regimen_fiscal.codigo", "cat_sat_regimen_fiscal.descripcion");

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
        $keys_selects = $this->init_selects(keys_selects: array(), key: "cat_sat_regimen_fiscal_id",
            label: "Regimen Fiscal",con_registros: true);

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_tipo_persona_id",
            label: "Tipo de persona", con_registros: true);
        return $keys_selects;
    }


    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Código');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Clase');
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


        $keys_selects['cat_sat_regimen_fiscal_id']->id_selected = $this->registro['cat_sat_regimen_fiscal_id'];
        $keys_selects['cat_sat_tipo_persona_id']->id_selected = $this->registro['cat_sat_tipo_persona_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }


}
