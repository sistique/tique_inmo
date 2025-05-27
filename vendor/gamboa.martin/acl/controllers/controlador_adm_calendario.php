<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */

namespace gamboamartin\acl\controllers;

use base\controller\controler;
use base\controller\init;
use config\generales;
use config\google;
use gamboamartin\administrador\models\adm_calendario;
use gamboamartin\errores\errores;
use gamboamartin\plugins\google_calendar_api;
use gamboamartin\template_1\html;
use html\adm_calendario_html;
use links\secciones\link_adm_accion;
use PDO;
use stdClass;

class controlador_adm_calendario extends _accion_base
{
    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass())
    {

        $modelo = new adm_calendario(link: $link);
        $html_ = new adm_calendario_html(html: $html);
        $obj_link = new link_adm_accion(link: $link, registro_id: $this->registro_id);

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
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
        }

        return $r_alta;
    }

    public function alta_bd(bool $header, bool $ws = false): array|stdClass
    {
        if (!isset($_SESSION['calendario']['code'])) {
            $link_redirect = (new generales())->url_base . 'vendor/gamboa.martin/acl/google_calendar_redirect.php';
            $link_alta = str_replace('./', (new generales())->url_base, $this->link_alta_bd);

            $google_oauth_url = (new google_calendar_api())->get_oauth_url(google_client_id: google::GOOGLE_CLIENT_ID,
                google_redirect_uri: $link_redirect);

            $_SESSION['calendario'] = [
                'link_google_calendar_redirect' => $link_redirect,
                'link_proceso' => $link_alta,
                'datos' => $_POST
            ];

            header("Location: $google_oauth_url");
            exit();
        }

        $calendario = $this->crear_calendario_google();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al crear calendario en google', data: $calendario);
        }

        $_POST = $_SESSION['calendario']['datos'];
        $_POST['calendario_id'] = $calendario['id'];
        $_POST['zona_horaria'] = $calendario['timeZone'];
        unset($_SESSION['calendario']);

        $alta_bd = parent::alta_bd($header, $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al dar de alta calendario', data: $alta_bd);
        }

        return $alta_bd;
    }

    public function crear_calendario_google(): array
    {
        $token = (new google_calendar_api())->get_access_token(client_id: google::GOOGLE_CLIENT_ID,
            redirect_uri: $_SESSION['calendario']['link_google_calendar_redirect'], client_secret: google::GOOGLE_CLIENT_SECRET,
            code: $_SESSION['calendario']['code'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $timeZone = (new google_calendar_api())->get_calendar_timezone(access_token: $token['access_token'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $datos = $_SESSION['calendario']['datos'];

        $calendario = (new google_calendar_api())->crear_calendario(access_token: $token['access_token'], summary: $datos['titulo'],
            description: $datos['descripcion'], timeZone: $timeZone, ssl_verify: google::GOOGLE_SSL_VERIFY);

        return $calendario;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'titulo', 'descripcion', 'zona_horaria');
        $keys->fechas = array();
        $keys->selects = array();

        $init_data = array();
        $init_data['adm_usuario'] = "gamboamartin\\administrador";
        $init_data['adm_seccion'] = "gamboamartin\\administrador";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Calendarios';

        return $this;
    }

    protected function init_links(): array|stdClass
    {
        $links = $this->obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        return $links;
    }

    private function init_selects(array $keys_selects, string $key, string $label, int|null $id_selected = -1, int $cols = 6,
                                  bool  $con_registros = true, array $filtro = array(), array $columns_ds = array()): array
    {
        $keys_selects = $this->key_select(cols: $cols, con_registros: $con_registros, filtro: $filtro, key: $key,
            keys_selects: $keys_selects, id_selected: $id_selected, label: $label, columns_ds: $columns_ds);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function init_selects_inputs(): array
    {

        $keys_selects = $this->init_selects(keys_selects: array(), key: "adm_usuario_id", label: "Usuario",
            cols: 12, columns_ds: array('adm_usuario_user'));
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "adm_seccion_id", label: "Sección",
            cols: 12, columns_ds: array('adm_seccion_descripcion'));
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        return $keys_selects;
    }

    final public function init_datatable(): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_calendario_id']['titulo'] = 'Id';
        $datatables->columns['adm_usuario_user']['titulo'] = 'Usuario';
        $datatables->columns['adm_seccion_descripcion']['titulo'] = 'Sección';
        $datatables->columns['adm_calendario_titulo']['titulo'] = 'Titulo';
        $datatables->columns['adm_calendario_zona_horaria']['titulo'] = 'Zona Horaria';

        $datatables->filtro = array();
        $datatables->filtro[] = 'adm_calendario.id';
        $datatables->filtro[] = 'adm_usuario.user';
        $datatables->filtro[] = 'adm_seccion.descripcion';
        $datatables->filtro[] = 'adm_calendario.titulo';
        $datatables->filtro[] = 'adm_calendario.zona_horaria';

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Cod');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'titulo',
            keys_selects: $keys_selects, place_holder: 'Titulo');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'zona_horaria',
            keys_selects: $keys_selects, place_holder: 'Zona Horaria');
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

        $keys_selects['adm_usuario_id']->id_selected = $this->registro['adm_usuario_id'];
        $keys_selects['adm_seccion_id']->id_selected = $this->registro['adm_seccion_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function modifica_bd(bool $header, bool $ws): array|stdClass
    {
        if (!isset($_SESSION['calendario']['code'])) {
            $link_redirect = (new generales())->url_base . 'vendor/gamboa.martin/acl/google_calendar_redirect.php';
            $link_modifica = str_replace('./', (new generales())->url_base, $this->link_modifica_bd);

            $google_oauth_url = (new google_calendar_api())->get_oauth_url(google_client_id: google::GOOGLE_CLIENT_ID,
                google_redirect_uri: $link_redirect);

            $_SESSION['calendario'] = [
                'link_google_calendar_redirect' => $link_redirect,
                'link_proceso' => $link_modifica,
                'datos' => $_POST
            ];
            $_SESSION['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];

            header("Location: $google_oauth_url");
            exit();
        }

        $datos_calendario = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $datos_calendario, header: $header, ws: $ws);
        }

        $calendario = $this->actualizar_calendario_google(calendar_id: $this->registro['adm_calendario_calendario_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al actualizar calendario en google', data: $calendario);
        }

        $_POST = $_SESSION['calendario']['datos'];
        $_POST['zona_horaria'] = $calendario['timeZone'];
        $_SERVER['HTTP_REFERER'] = $_SESSION['HTTP_REFERER'];
        unset($_SESSION['calendario']);
        unset($_SESSION['HTTP_REFERER']);

        $modifica_bd = parent::modifica_bd($header, $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al modificar calendario', data: $modifica_bd);
        }

        return $modifica_bd;
    }

    public function actualizar_calendario_google(string $calendar_id): array
    {
        $token = (new google_calendar_api())->get_access_token(client_id: google::GOOGLE_CLIENT_ID,
            redirect_uri: $_SESSION['calendario']['link_google_calendar_redirect'], client_secret: google::GOOGLE_CLIENT_SECRET,
            code: $_SESSION['calendario']['code'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $timeZone = (new google_calendar_api())->get_calendar_timezone(access_token: $token['access_token'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $datos = $_SESSION['calendario']['datos'];

        $calendario = (new google_calendar_api())->actualizar_calendario(access_token: $token['access_token'],
            calendar_id: $calendar_id, summary: $datos['titulo'], description: $datos['descripcion'], timeZone: $timeZone,
            ssl_verify: google::GOOGLE_SSL_VERIFY);

        return $calendario;
    }

    public function elimina_bd(bool $header, bool $ws): array|stdClass
    {
        if (!isset($_SESSION['calendario']['code'])) {
            $link_redirect = (new generales())->url_base . 'vendor/gamboa.martin/acl/google_calendar_redirect.php';
            $link_elimina = str_replace('./', (new generales())->url_base, $this->link_elimina_bd);

            $google_oauth_url = (new google_calendar_api())->get_oauth_url(google_client_id: google::GOOGLE_CLIENT_ID,
                google_redirect_uri: $link_redirect);

            $_SESSION['calendario'] = [
                'link_google_calendar_redirect' => $link_redirect,
                'link_proceso' => $link_elimina,
                'datos' => $_POST
            ];

            header("Location: $google_oauth_url");
            exit();
        }

        $datos_calendario = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $datos_calendario, header: $header, ws: $ws);
        }

        $calendario = $this->elimina_calendario_google(calendar_id: $this->registro['adm_calendario_calendario_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al eliminar calendario en google', data: $calendario);
        }

        $_POST = $_SESSION['calendario']['datos'];
        unset($_SESSION['calendario']);

        $elimina_bd = parent::elimina_bd($header, $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al eliminar calendario', data: $elimina_bd);
        }

        return $elimina_bd;
    }

    public function elimina_calendario_google(string $calendar_id): bool
    {
        $token = (new google_calendar_api())->get_access_token(client_id: google::GOOGLE_CLIENT_ID,
            redirect_uri: $_SESSION['calendario']['link_google_calendar_redirect'], client_secret: google::GOOGLE_CLIENT_SECRET,
            code: $_SESSION['calendario']['code'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $calendario = (new google_calendar_api())->eliminar_calendario(access_token: $token['access_token'],
            calendar_id: $calendar_id,ssl_verify: google::GOOGLE_SSL_VERIFY);

        return $calendario;
    }

}
