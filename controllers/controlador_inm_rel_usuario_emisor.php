<?php
namespace gamboamartin\inmuebles\controllers;

use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_rel_usuario_emisor_html;
use gamboamartin\inmuebles\models\inm_rel_usuario_emisor;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_rel_usuario_emisor extends _ctl_base {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_rel_usuario_emisor(link: $link);
        $html_ = new inm_rel_usuario_emisor_html(html: $html);
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
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = $this->keys_selects_rel(id_usuario: -1, id_emisor: -1);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

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
        $keys->inputs = array('descripcion');
        $keys->selects = array();

        $init_data = array();
        $init_data['adm_usuario'] = "gamboamartin\\administrador";
        $init_data['not_emisor'] = "gamboamartin\\notificaciones";

        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $keys_selects = $this->keys_selects_rel(id_usuario: $this->row_upd->adm_usuario_id,
            id_emisor: $this->row_upd->not_emisor_id);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        return $r_modifica;
    }

    private function keys_selects_rel(int $id_usuario, int $id_emisor): array
    {
        $keys_selects = array();

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_usuario_id',
            keys_selects: $keys_selects, id_selected: $id_usuario, label: 'Usuario',
            columns_ds: array('adm_usuario_user'));
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'not_emisor_id',
            keys_selects: $keys_selects, id_selected: $id_emisor, label: 'Emisor');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

    private function init_datatable(): stdClass
    {
        $columns["inm_rel_usuario_emisor_id"]["titulo"] = "Id";
        $columns["adm_usuario_user"]["titulo"] = "Usuario";
        $columns["not_emisor_descripcion"]["titulo"] = "Emisor";
        $columns["not_emisor_email"]["titulo"] = "Email";

        $filtro = array("inm_rel_usuario_emisor.id","adm_usuario.user","not_emisor.descripcion","not_emisor.email");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

}
