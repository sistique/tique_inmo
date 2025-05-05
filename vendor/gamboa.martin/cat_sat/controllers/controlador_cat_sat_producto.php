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
use gamboamartin\cat_sat\models\cat_sat_clase_producto;
use gamboamartin\cat_sat\models\cat_sat_producto;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\cat_sat_producto_html;
use PDO;
use stdClass;

class controlador_cat_sat_producto extends _cat_sat_base {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new cat_sat_producto(link: $link);
        $html_ = new cat_sat_producto_html(html: $html);
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

        $this->parents_verifica[] = new cat_sat_clase_producto(link: $this->link);
        $this->verifica_parents_alta = true;


    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion');
        $keys->selects = array();

        $init_data = array();
        $init_data['cat_sat_tipo_producto'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_division_producto'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_grupo_producto'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_clase_producto'] = "gamboamartin\\cat_sat";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    public function get_productos(bool $header, bool $ws = true): array|stdClass
    {
        $keys['cat_sat_clase_producto'] = array('id','descripcion','codigo','codigo_bis');

        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);
        }

        return $salida;
    }

    /**
     * Inicializa las configuraciones base de un controller
     * @return controler
     * @version 8.15.0
     */
    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Productos';

        $this->lista_get_data = true;

        return $this;
    }

    /**
     * Inicializa los elementos de datatables
     * @return stdClass
     * @version 7.44.1
     */
    private function init_datatable(): stdClass
    {
        $columns["cat_sat_producto_id"]["titulo"] = "Id";
        $columns["cat_sat_producto_codigo"]["titulo"] = "Código";
        $columns["cat_sat_tipo_producto_descripcion"]["titulo"] = "Tipo";
        $columns["cat_sat_division_producto_descripcion"]["titulo"] = "División";
        $columns["cat_sat_grupo_producto_descripcion"]["titulo"] = "Grupo";
        $columns["cat_sat_clase_producto_descripcion"]["titulo"] = "Clase";
        $columns["cat_sat_producto_descripcion"]["titulo"] = "Producto";

        $filtro = array("cat_sat_producto.id", "cat_sat_producto.codigo", "cat_sat_producto.descripcion",
            "cat_sat_tipo_producto.descripcion", "cat_sat_division_producto.descripcion"
        , "cat_sat_grupo_producto.descripcion", "cat_sat_clase_producto.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    /**
     * Inicializa los selects de productos
     * @param array $keys_selects
     * @param string $key
     * @param string $label
     * @param int $id_selected
     * @param int $cols
     * @param bool $con_registros
     * @param array $filtro
     * @return array
     */
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
        $keys_selects = $this->init_selects(keys_selects: array(), key: "cat_sat_tipo_producto_id",
            label: "Tipo de Producto");
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_division_producto_id",
            label: "División", con_registros: false);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_grupo_producto_id", label: "Grupo",
            con_registros: false);
        return $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_clase_producto_id", label: "Clase",
            con_registros: false);
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Código');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 8, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Producto');
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

        $producto = (new cat_sat_producto(link: $this->link))->get_producto(cat_sat_producto_id: $this->registro_id);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener producto', data: $producto);
        }

        $keys_selects['cat_sat_tipo_producto_id']->id_selected = $this->registro['cat_sat_tipo_producto_id'];

        $keys_selects['cat_sat_division_producto_id']->con_registros = true;
        $keys_selects['cat_sat_division_producto_id']->filtro = array("cat_sat_tipo_producto.id" =>
            $producto['cat_sat_tipo_producto_id']);
        $keys_selects['cat_sat_division_producto_id']->id_selected = $producto['cat_sat_division_producto_id'];
        $keys_selects['cat_sat_division_producto_id']->extra_params_keys = array('cat_sat_division_producto_codigo');

        $keys_selects['cat_sat_grupo_producto_id']->con_registros = true;
        $keys_selects['cat_sat_grupo_producto_id']->filtro = array("cat_sat_division_producto.id" =>
            $producto['cat_sat_division_producto_id']);
        $keys_selects['cat_sat_grupo_producto_id']->id_selected = $producto['cat_sat_grupo_producto_id'];
        $keys_selects['cat_sat_grupo_producto_id']->extra_params_keys = array('cat_sat_grupo_producto_codigo');

        $keys_selects['cat_sat_clase_producto_id']->con_registros = true;
        $keys_selects['cat_sat_clase_producto_id']->filtro = array("cat_sat_grupo_producto.id" =>
            $producto['cat_sat_grupo_producto_id']);
        $keys_selects['cat_sat_clase_producto_id']->id_selected = $producto['cat_sat_clase_producto_id'];
        $keys_selects['cat_sat_clase_producto_id']->extra_params_keys = array('cat_sat_clase_producto_codigo');


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }
}
