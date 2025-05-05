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
use gamboamartin\administrador\models\adm_tipo_evento;
use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use html\adm_tipo_evento_html;
use links\secciones\link_adm_accion;
use PDO;
use stdClass;

class controlador_adm_tipo_evento extends _accion_base
{

    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass())
    {

        $modelo = new adm_tipo_evento(link: $link);
        $html_ = new adm_tipo_evento_html(html: $html);
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

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion');
        $keys->fechas = array();
        $keys->selects = array();

        $init_data = array();
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Tipos de Eventos';

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

    final public function init_datatable(): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_tipo_evento_id']['titulo'] = 'Id';
        $datatables->columns['adm_tipo_evento_descripcion']['titulo'] = 'Tipo Evento';

        $datatables->filtro = array();
        $datatables->filtro[] = 'adm_tipo_evento.id';
        $datatables->filtro[] = 'adm_tipo_evento.descripcion';

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Cod');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
       
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }
}
