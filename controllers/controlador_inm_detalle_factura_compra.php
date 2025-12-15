<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\inmuebles\controllers;

use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_detalle_factura_compra_html;
use gamboamartin\inmuebles\models\inm_detalle_factura_compra;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_detalle_factura_compra extends _ctl_base {

    public string $link_descuento_bd = '';
    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_detalle_factura_compra(link: $link);
        $html_ = new inm_detalle_factura_compra_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->lista_get_data = true;


        $init_links = $this->init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
            print_r($error);
            die('Error');
        }
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }
        $keys_selects = array();
        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion','descuento');
        $keys->selects = array();

        $init_data = array();
        $init_data['inm_factura_compra'] = "gamboamartin\\inmuebles";

        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    public function descuento(bool $header, bool $ws = false): array|stdClass
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $r_detalle = (new inm_detalle_factura_compra(link: $this->link))->registro(registro_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = array();
        $filtro['inm_factura_compra.id'] = $r_detalle['inm_factura_compra_id'];
        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: $filtro, key: 'inm_factura_compra_id',
            keys_selects: $keys_selects, id_selected: $r_detalle['inm_factura_compra_id'], label: 'Factura',
            required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $retorno = 'productos_factura';
        $btn_action_next = $this->html->hidden('btn_action_next', value: $retorno);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $id_retorno = $this->html->hidden('id_retorno', value: $r_detalle['inm_factura_compra_id']);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $seccion_retorno = $this->html->hidden('seccion_retorno', value: 'inm_factura_compra');
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $this->inputs->btn_action_next = $btn_action_next;
        $this->inputs->id_retorno = $id_retorno;
        $this->inputs->seccion_retorno = $seccion_retorno;

        return $inputs;
    }


    public function elimina_bd(bool $header, bool $ws = false): array|stdClass
    {
        $r_elimina_bd =  parent::elimina_bd($header, $ws);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $r_elimina_bd, header: $header,ws:  $ws);
        }

        return $r_elimina_bd;
    }


    protected function init_links(): array|string
    {
        $links = $this->obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "inm_detalle_factura_compra", accion: "descuento_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link modifica_direccion', data: $link);
            print_r($error);
            exit;
        }

        $this->link_descuento_bd = $link;

        return $link;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $keys_selects = array();
        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        return $r_modifica;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_detalle_factura_compra_id"]["titulo"] = "Id";
        $columns["inm_detalle_factura_compra_descripcion"]["titulo"] = "Descripcion";

        $filtro = array("inm_detalle_factura_compra.id","inm_detalle_factura_compra.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {


        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'descripcion',
            keys_selects:$keys_selects, place_holder: 'Descripcion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'descuento',
            keys_selects:$keys_selects, place_holder: 'Descuento');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }


        return $keys_selects;
    }


}
