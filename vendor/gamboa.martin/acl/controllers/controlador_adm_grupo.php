<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\acl\controllers;

use base\controller\init;
use config\generales;
use gamboamartin\administrador\models\adm_accion;
use gamboamartin\administrador\models\adm_accion_grupo;
use gamboamartin\administrador\models\adm_grupo;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\actions;
use gamboamartin\template_1\html;
use html\adm_accion_html;
use html\adm_grupo_html;
use html\adm_menu_html;
use html\adm_seccion_html;
use html\adm_usuario_html;
use links\secciones\link_adm_grupo;
use PDO;
use stdClass;
use Throwable;


class controlador_adm_grupo extends _ctl_parent_sin_codigo {

    public array $secciones = array();
    public stdClass|array $adm_grupo = array();
    public array $adm_acciones_grupo = array();
    public string $link_adm_usuario_alta_bd = '';
    public string $link_adm_accion_grupo_alta_bd = '';
    public string $link_asigna_permiso_seccion_bd = '';
    public array $adm_usuarios = array();

    public string $ruta_vendor_acl = '';

    public function __construct(PDO $link, html $html = new html(), array $datatables_custom_cols = array(),
                                array $datatables_custom_cols_omite = array(), stdClass $paths_conf = new stdClass()){
        $modelo = new adm_grupo(link: $link);

        $html_ = new adm_grupo_html(html: $html);
        $obj_link = new link_adm_grupo(link: $link,registro_id:  $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_grupo_id']['titulo'] = 'Id';
        $datatables->columns['adm_grupo_descripcion']['titulo'] = 'Grupo';
        $datatables->columns['adm_grupo_n_permisos']['titulo'] = 'N Permisos';
        $datatables->columns['adm_grupo_n_usuarios']['titulo'] = 'N Usuarios';

        $datatables->filtro = array();
        $datatables->filtro[] = 'adm_grupo.id';
        $datatables->filtro[] = 'adm_grupo.descripcion';


        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link,
            datatables_custom_cols: $datatables_custom_cols,
            datatables_custom_cols_omite: $datatables_custom_cols_omite, datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Grupos';

        if(isset($this->registro_id) && $this->registro_id > 0){
            $adm_grupo = (new adm_grupo($this->link))->registro(registro_id: $this->registro_id);
            if(errores::$error){
                $error = $this->errores->error(mensaje: 'Error al obtener adm_grupo',data:  $adm_grupo);
                print_r($error);
                exit;
            }
            $this->adm_grupo = $adm_grupo;
        }

        $link_adm_usuario_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'adm_usuario');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_adm_usuario_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_adm_usuario_alta_bd = $link_adm_usuario_alta_bd;

        $link_adm_accion_grupo_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'adm_accion_grupo');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_adm_accion_grupo_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_adm_accion_grupo_alta_bd = $link_adm_accion_grupo_alta_bd;

        if((new generales())->sistema !== 'acl'){
            $this->ruta_vendor_acl = 'vendor/gamboa.martin/acl/';
        }




    }

    public function asigna_permiso(bool $header = true, bool $ws = false): array|string{


        $contenido = (new _ctl_permiso())->asigna_permiso(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener contenido',data:  $contenido, header: $header,ws:  $ws);
        }


        return $contenido;
    }

    public function asigna_permiso_seccion(bool $header = true, bool $ws = false): array|stdClass
    {

        $link_asigna_permiso_seccion_bd = $this->obj_link->link_con_id(accion: 'asigna_permiso_seccion_bd',
            link:  $this->link,registro_id:  $this->registro_id, seccion: $this->seccion);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener link',data:  $link_asigna_permiso_seccion_bd, header: $header,ws:  $ws);
        }
        $this->link_asigna_permiso_seccion_bd = $link_asigna_permiso_seccion_bd;

        $registro = $this->modelo->registro(registro_id: $this->registro_id, retorno_obj: true);
        if(errores::$error){
            return  $this->retorno_error(mensaje: 'Error al obtener registro',data:  $registro, header: $header, ws: $ws);
        }

        $inputs = $this->inputs_children(registro: $registro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $inputs_returns = $this->input_retornos();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_returns',data:  $inputs_returns, header: $header,ws:  $ws);
        }


        return $inputs;

    }

    public function asigna_permiso_seccion_bd(bool $header = true, bool $ws = false): array|stdClass
    {

        $siguiente_view = (new actions())->init_alta_bd();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header:  $header, ws: $ws);
        }
        $seccion_retorno = $this->tabla;
        if(isset($_POST['seccion_retorno'])){
            $seccion_retorno = $_POST['seccion_retorno'];
            unset($_POST['seccion_retorno']);
        }

        $id_retorno = -1;
        if(isset($_POST['id_retorno'])){
            $id_retorno = $_POST['id_retorno'];
            unset($_POST['id_retorno']);
        }

        $acciones = (new adm_accion(link: $this->link))->acciones_by_seccion_id(adm_seccion_id: $_POST['adm_seccion_id']);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener acciones',data:  $acciones, header: $header,ws:  $ws);
        }

        $ins = array();
        foreach ($acciones as $accion){
            $adm_accion_grupo_ins['adm_accion_id'] = $accion['adm_accion_id'];
            $adm_accion_grupo_ins['adm_grupo_id'] = $_POST['adm_grupo_id'];
            $adm_accion_grupo_ins['status'] = 'activo';

            $filtro['adm_accion.id'] = $accion['adm_accion_id'];
            $filtro['adm_grupo.id'] = $_POST['adm_grupo_id'];

            $existe = (new adm_accion_grupo(link: $this->link))->existe(filtro: $filtro);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al validar si existe',data:  $existe, header: $header,ws:  $ws);
            }

            if(!$existe){
                $r_adm_accion_grupo = (new adm_accion_grupo(link: $this->link))->alta_registro(registro: $adm_accion_grupo_ins);
                if(errores::$error){
                    return $this->retorno_error(mensaje: 'Error al insertar permiso',data:  $r_adm_accion_grupo, header: $header,ws:  $ws);
                }
                $ins[] = $r_adm_accion_grupo;
            }
        }
        if($header){

            $this->retorno_base(registro_id:$id_retorno, result: $ins, siguiente_view: $siguiente_view,
                ws:  $ws,seccion_retorno: $seccion_retorno);
        }
        if($ws){
            header('Content-Type: application/json');
            try {
                echo json_encode($ins, JSON_THROW_ON_ERROR);
            }
            catch (Throwable $e){
                $error = (new errores())->error(mensaje: 'Error al maquetar JSON' , data: $e);
                print_r($error);
            }
            exit;
        }



        return $acciones;

    }

    protected function inputs_children(stdClass $registro): stdClass|array
    {
        $select_adm_grupo_id = (new adm_grupo_html(html: $this->html_base))->select_adm_grupo_id(
            cols:12,con_registros: true,id_selected: $this->registro_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_accion_id',data:  $select_adm_grupo_id);
        }

        $select_adm_menu_id = (new adm_menu_html(html: $this->html_base))->select_adm_menu_id(
            cols:6,con_registros: true,id_selected: -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_accion_id',data:  $select_adm_grupo_id);
        }

        $select_adm_seccion_id = (new adm_seccion_html(html: $this->html_base))->select_adm_seccion_id(
            cols:6,con_registros: false,id_selected: -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_accion_id',data:  $select_adm_grupo_id);
        }

        $select_adm_accion_id = (new adm_accion_html(html: $this->html_base))->select_adm_accion_id(
            cols:12,con_registros: false,id_selected: -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_accion_id',data:  $select_adm_grupo_id);
        }

        $adm_usuario_user = (new adm_usuario_html(html: $this->html_base))->input_user(6, new stdClass(), false);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_accion_id',data:  $select_adm_grupo_id);
        }

        $adm_usuario_password = (new adm_usuario_html(html: $this->html_base))->input_password(6, new stdClass(), false);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_accion_id',data:  $select_adm_grupo_id);
        }

        $adm_usuario_email = (new adm_usuario_html(html: $this->html_base))->input_email(6, new stdClass(), false);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_accion_id',data:  $select_adm_grupo_id);
        }

        $adm_usuario_telefono = (new adm_usuario_html(html: $this->html_base))->input_telefono(6, new stdClass(), false);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener select_adm_accion_id',data:  $select_adm_grupo_id);
        }

        $adm_usuario_nombre = (new adm_usuario_html(html: $this->html_base))->input_nombre(12, new stdClass(), false);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener adm_usuario_nombre',data:  $adm_usuario_nombre);
        }

        $adm_usuario_ap = (new adm_usuario_html(html: $this->html_base))->input_ap(6, new stdClass(), false);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener adm_usuario_ap',data:  $adm_usuario_ap);
        }

        $adm_usuario_am = (new adm_usuario_html(html: $this->html_base))->input_am(6, new stdClass(), false);

        if(errores::$error){
            return $this->errores->error(
                mensaje: 'Error al obtener adm_usuario_am',data:  $adm_usuario_am);
        }


        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();

        $this->inputs->select->adm_grupo_id = $select_adm_grupo_id;
        $this->inputs->select->adm_menu_id = $select_adm_menu_id;
        $this->inputs->select->adm_seccion_id = $select_adm_seccion_id;
        $this->inputs->select->adm_accion_id = $select_adm_accion_id;

        $this->inputs->adm_usuario_user = $adm_usuario_user;
        $this->inputs->adm_usuario_password = $adm_usuario_password;
        $this->inputs->adm_usuario_email = $adm_usuario_email;
        $this->inputs->adm_usuario_telefono = $adm_usuario_telefono;
        $this->inputs->adm_usuario_nombre = $adm_usuario_nombre;
        $this->inputs->adm_usuario_ap = $adm_usuario_ap;
        $this->inputs->adm_usuario_am = $adm_usuario_am;
        return $this->inputs;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Grupo');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }


        return $keys_selects;
    }

    final public function root(bool $header = true, bool $ws = false): array|stdClass
    {
        $upd = $this->modelo->status(campo: 'root', registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al cambiar status', data: $upd, header: $header, ws: $ws);
        }
        $_SESSION['exito'][]['mensaje'] = 'Se ajusto el estatus de root manera el registro con el id ' .
            $this->registro_id;

        $this->header_out(result: $upd, header: $header, ws: $ws);


        return $upd;

    }

    final public function solo_mi_info(bool $header = true, bool $ws = false): array|stdClass
    {
        $upd = $this->modelo->status(campo: __FUNCTION__, registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al cambiar status', data: $upd, header: $header, ws: $ws);
        }
        $_SESSION['exito'][]['mensaje'] = 'Se ajusto el estatus solo_mi_info manera el registro con el id ' .
            $this->registro_id;

        $this->header_out(result: $upd, header: $header, ws: $ws);


        return $upd;

    }

    public function usuarios(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $data_view = new stdClass();
        $data_view->names = array('Id','User','Email','Telefono','Grupo','Acciones');
        $data_view->keys_data = array('adm_usuario_id','adm_usuario_user','adm_usuario_email',
            'adm_usuario_telefono','adm_grupo_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\administrador\\models';
        $data_view->name_model_children = 'adm_usuario';

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }


        return $contenido_table;


    }


}
