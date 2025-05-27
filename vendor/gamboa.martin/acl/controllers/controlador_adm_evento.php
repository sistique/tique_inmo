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
use gamboamartin\administrador\models\adm_evento;
use gamboamartin\errores\errores;
use gamboamartin\plugins\google_calendar_api;
use gamboamartin\template_1\html;
use html\adm_calendario_html;
use html\adm_evento_html;
use links\secciones\link_adm_accion;
use PDO;
use stdClass;

class controlador_adm_evento extends _accion_base
{
    public string $hora_inicio;
    public string $hora_fin;

    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass())
    {

        $modelo = new adm_evento(link: $link);
        $html_ = new adm_evento_html(html: $html);
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

        $calendario = (new adm_calendario(link: $this->link))->registro(registro_id: $_SESSION['calendario']['datos']['adm_calendario_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener calendario', data: $calendario);
        }

        $evento = $this->crear_evento_google(calendar_id: $calendario['adm_calendario_calendario_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al crear evento en google', data: $evento);
        }

        $_POST = $_SESSION['calendario']['datos'];
        $_POST['evento_id'] = $evento['id'];
        $_POST['zona_horaria'] = $evento['start']['timeZone'];
        unset($_SESSION['calendario']);

        $alta_bd = parent::alta_bd($header, $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al dar de alta evento', data: $alta_bd);
        }

        return $alta_bd;
    }

    public function crear_evento_google(string $calendar_id): array
    {
        $token = (new google_calendar_api())->get_access_token(client_id: google::GOOGLE_CLIENT_ID,
            redirect_uri: $_SESSION['calendario']['link_google_calendar_redirect'], client_secret: google::GOOGLE_CLIENT_SECRET,
            code: $_SESSION['calendario']['code'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $timeZone = (new google_calendar_api())->get_calendar_timezone(access_token: $token['access_token'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $datos = $_SESSION['calendario']['datos'];

        $fecha_inicio = $datos['fecha_inicio'] . ' ' . $datos['hora_inicio'];
        $fecha_fin = $datos['fecha_fin'] . ' ' . $datos['hora_fin'];
        $_SESSION['calendario']['datos']['fecha_inicio'] = $datos['fecha_inicio'] . ' ' . $datos['hora_inicio'];
        $_SESSION['calendario']['datos']['fecha_fin'] = $datos['fecha_fin'] . ' ' . $datos['hora_fin'];

        $start_datetime['dateTime'] = (new \DateTime($fecha_inicio))->format(\DateTime::ATOM);
        $start_datetime['timeZone'] = $timeZone;
        $end_datetime['dateTime'] = (new \DateTime($fecha_fin))->format(\DateTime::ATOM);
        $end_datetime['timeZone'] = $timeZone;
        $location = '';

        $calendario = (new google_calendar_api())->crear_evento_calendario(access_token: $token['access_token'],
            calendar_id: $calendar_id, summary: $datos['titulo'], description: $datos['descripcion'], start_datetime: $start_datetime,
            end_datetime: $end_datetime, location: $location,
            timeZone: $timeZone, ssl_verify: google::GOOGLE_SSL_VERIFY);

        return $calendario;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'titulo', 'descripcion', 'zona_horaria');
        $keys->fechas = array('fecha_inicio', 'fecha_fin');
        $keys->selects = array();

        $init_data = array();
        $init_data['adm_calendario'] = "gamboamartin\\administrador";
        $init_data['adm_tipo_evento'] = "gamboamartin\\administrador";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Eventos';

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
        $keys_selects = $this->init_selects(keys_selects: array(), key: "adm_calendario_id", label: "Calendario",
            cols: 12, columns_ds: array('adm_calendario_descripcion'));
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "adm_tipo_evento_id", label: "Tipo Evento",
            cols: 12, columns_ds: array('adm_tipo_evento_descripcion'));
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        return $keys_selects;
    }

    final public function init_datatable(): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_evento_id']['titulo'] = 'Id';
        $datatables->columns['adm_calendario_titulo']['titulo'] = 'Calendario';
        $datatables->columns['adm_evento_titulo']['titulo'] = 'Evento';
        $datatables->columns['adm_evento_fecha_inicio']['titulo'] = 'Fecha Inicio';
        $datatables->columns['adm_evento_fecha_fin']['titulo'] = 'Fecha Fin';
        $datatables->columns['adm_evento_zona_horaria']['titulo'] = 'Zona Horaria';

        $datatables->filtro = array();
        $datatables->filtro[] = 'adm_evento.id';
        $datatables->filtro[] = 'adm_calendario.titulo';
        $datatables->filtro[] = 'adm_evento.titulo';
        $datatables->filtro[] = 'adm_evento.fecha_inicio';
        $datatables->filtro[] = 'adm_evento.fecha_fin';
        $datatables->filtro[] = 'adm_evento.zona_horaria';

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

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'fecha_inicio',
            keys_selects: $keys_selects, place_holder: 'Fecha Inicio');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'fecha_fin',
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

        $this->row_upd->fecha_inicio = (new \DateTime($this->registro['adm_evento_fecha_inicio']))->format('Y-m-d');
        $this->row_upd->fecha_fin = (new \DateTime($this->registro['adm_evento_fecha_fin']))->format('Y-m-d');

        $this->hora_inicio = (new \DateTime($this->registro['adm_evento_fecha_inicio']))->format('H:i');
        $this->hora_fin = (new \DateTime($this->registro['adm_evento_fecha_fin']))->format('H:i');

        $keys_selects['adm_calendario_id']->id_selected = $this->registro['adm_calendario_id'];
        $keys_selects['adm_tipo_evento_id']->id_selected = $this->registro['adm_tipo_evento_id'];

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

        $calendario = (new adm_calendario(link: $this->link))->registro(registro_id: $_SESSION['calendario']['datos']['adm_calendario_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener calendario', data: $calendario);
        }

        $datos_calendario = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $datos_calendario, header: $header, ws: $ws);
        }

        $evento = $this->actualizar_evento_google(calendar_id: $calendario['adm_calendario_calendario_id'], event_id: $this->registro['adm_evento_evento_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al actualizar evento en google', data: $evento);
        }

        $_POST = $_SESSION['calendario']['datos'];
        $_POST['zona_horaria'] = $evento['start']['timeZone'];
        $_SERVER['HTTP_REFERER'] = $_SESSION['HTTP_REFERER'];
        unset($_SESSION['calendario']);
        unset($_SESSION['HTTP_REFERER']);

        $modifica_bd = parent::modifica_bd($header, $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al modificar calendario', data: $modifica_bd);
        }

        return $modifica_bd;
    }

    public function actualizar_evento_google(string $calendar_id, string $event_id): array
    {
        $token = (new google_calendar_api())->get_access_token(client_id: google::GOOGLE_CLIENT_ID,
            redirect_uri: $_SESSION['calendario']['link_google_calendar_redirect'], client_secret: google::GOOGLE_CLIENT_SECRET,
            code: $_SESSION['calendario']['code'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $timeZone = (new google_calendar_api())->get_calendar_timezone(access_token: $token['access_token'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $datos = $_SESSION['calendario']['datos'];

        $fecha_inicio = $datos['fecha_inicio'] . ' ' . $datos['hora_inicio'];
        $fecha_fin = $datos['fecha_fin'] . ' ' . $datos['hora_fin'];
        $_SESSION['calendario']['datos']['fecha_inicio'] = $datos['fecha_inicio'] . ' ' . $datos['hora_inicio'];
        $_SESSION['calendario']['datos']['fecha_fin'] = $datos['fecha_fin'] . ' ' . $datos['hora_fin'];

        $start_datetime['dateTime'] = (new \DateTime($fecha_inicio))->format(\DateTime::ATOM);
        $start_datetime['timeZone'] = $timeZone;
        $end_datetime['dateTime'] = (new \DateTime($fecha_fin))->format(\DateTime::ATOM);
        $end_datetime['timeZone'] = $timeZone;
        $location = '';

        $calendario = (new google_calendar_api())->actualizar_evento_calendario(access_token: $token['access_token'],
            calendar_id: $calendar_id, event_id: $event_id, summary: $datos['titulo'], description: $datos['descripcion'],
            location: $location,start_datetime: $start_datetime, end_datetime: $end_datetime,timeZone: $timeZone,
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

        $calendario = (new adm_calendario(link: $this->link))->registro(registro_id: $this->registro['adm_evento_adm_calendario_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener calendario', data: $calendario);
        }

        $calendario = $this->elimina_evento_google(calendar_id: $calendario['adm_calendario_calendario_id'], event_id: $this->registro['adm_evento_evento_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al eliminar evento en google', data: $calendario);
        }

        $_POST = $_SESSION['calendario']['datos'];
        unset($_SESSION['calendario']);

        $elimina_bd = parent::elimina_bd($header, $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al eliminar evento', data: $elimina_bd);
        }

        return $elimina_bd;
    }

    public function elimina_evento_google(string $calendar_id, string $event_id): bool
    {
        $token = (new google_calendar_api())->get_access_token(client_id: google::GOOGLE_CLIENT_ID,
            redirect_uri: $_SESSION['calendario']['link_google_calendar_redirect'], client_secret: google::GOOGLE_CLIENT_SECRET,
            code: $_SESSION['calendario']['code'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $calendario = (new google_calendar_api())->eliminar_evento_calendario(access_token: $token['access_token'],
            calendar_id: $calendar_id, event_id: $event_id, ssl_verify: google::GOOGLE_SSL_VERIFY);

        return $calendario;
    }
}
