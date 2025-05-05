<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\notificaciones\controllers;

use base\controller\controler;
use gamboamartin\errores\errores;
use gamboamartin\ks_ops\html\ks_cliente_html;
use gamboamartin\ks_ops\html\ks_comision_general_html;
use gamboamartin\ks_ops\html\ks_detalle_comision_html;
use gamboamartin\ks_ops\models\ks_cliente;
use gamboamartin\ks_ops\models\ks_comision_general;
use gamboamartin\ks_ops\models\ks_detalle_comision;
use gamboamartin\notificaciones\html\not_notificacion_html;
use gamboamartin\notificaciones\models\not_notificacion;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_not_notificacion extends _ctl_base {

    public array|stdClass $keys_selects = array();

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new not_notificacion(link: $link);
        $html = new not_notificacion_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $init_controladores = $this->init_controladores(paths_conf: $paths_conf);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar controladores',data:  $init_controladores);
            print_r($error);
            die('Error');
        }

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

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo');
        $keys->selects = array();

        $init_data = array();
        $init_data['adm_accion'] = "gamboamartin\\administrador";
        $init_data['adm_usuario'] = "gamboamartin\\administrador";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Notificaciones';

        return $this;
    }

    private function init_selects(array $keys_selects, string $key, string $label, int|null $id_selected = -1, int $cols = 6,
                                  bool  $con_registros = true, array $filtro = array(), array $columns_ds =  array()): array
    {
        $keys_selects = $this->key_select(cols: $cols, con_registros: $con_registros, filtro: $filtro, key: $key,
            keys_selects: $keys_selects, id_selected: $id_selected, label: $label, columns_ds: $columns_ds);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function init_selects_inputs(): array{

        $keys_selects = $this->init_selects(keys_selects: array(), key: "adm_usuario_accion_id", label: "Usuario",
            cols: 6,columns_ds: array('adm_usuario_user'));
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar selector',data:  $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "adm_usuario_notificado_id",
            label: "Usuario Notificado", cols: 6,columns_ds: array('adm_usuario_user'));
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar selector',data:  $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "adm_accion_id", label: "Acción",
            cols: 6,columns_ds: array('adm_accion_descripcion'));
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar selector',data:  $keys_selects);
        }

        return $keys_selects;
    }

    final public function init_datatable(): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['not_notificacion_id']['titulo'] = 'Id';
        $datatables->columns['adm_usuario_user']['titulo'] = 'Usuario';
        $datatables->columns['adm_usuario_user']['titulo'] = 'Usuario Notificado';
        $datatables->columns['adm_accion_descripcion']['titulo'] = 'Acción';
        $datatables->columns['not_notificacion_status']['titulo'] = 'Status';
        $datatables->columns['not_notificacion_estado']['titulo'] = 'Estado';

        $datatables->filtro = array();
        $datatables->filtro[] = 'not_notificacion.id';
        $datatables->filtro[] = 'adm_usuario.user';
        $datatables->filtro[] = 'adm_accion.descripcion';
        $datatables->filtro[] = 'not_notificacion.status';
        $datatables->filtro[] = 'not_notificacion.estado';

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Código');
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

        $keys_selects['adm_usuario_accion_id']->id_selected = $this->registro['adm_usuario_accion_id'];
        $keys_selects['adm_usuario_notificado_id']->id_selected = $this->registro['adm_usuario_notificado_id'];
        $keys_selects['adm_accion_id']->id_selected = $this->registro['adm_accion_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }
}
