<?php

namespace gamboamartin\documento\controllers;


use gamboamartin\documento\models\doc_documento;
use gamboamartin\documento\models\doc_documento_etapa;
use gamboamartin\documento\models\doc_extension;
use gamboamartin\documento\models\doc_tipo_documento;
use gamboamartin\errores\errores;

use gamboamartin\system\actions;
use gamboamartin\system\links_menu;
use gamboamartin\template_1\html;
use html\doc_documento_etapa_html;
use html\doc_documento_html;
use PDO;
use stdClass;
use Throwable;

class controlador_doc_documento_etapa extends _parents_doc_base
{
    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass())
    {
        $modelo = new doc_documento_etapa($link);
        $html_ = new doc_documento_etapa_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['doc_documento_etapa_id']['titulo'] = 'Id';
        $datatables->columns['doc_documento_descripcion']['titulo'] = 'Documento';
        $datatables->columns['pr_etapa_proceso_descripcion']['titulo'] = 'Etapa';
        $datatables->columns['doc_documento_etapa_fecha']['titulo'] = 'Fecha';

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Documentos';

        $this->lista_get_data = true;

        $this->modelo = $modelo;

        $this->parents_verifica['doc_documento'] = (new doc_documento(link: $this->link));
        $this->verifica_parents_alta = true;

    }


    public function alta(bool $header, bool $ws = false): array|string
    {

        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $this->row_upd->fecha = date("Y-m-d");

        $inputs = $this->inputs_base_alta(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion');
        $keys->selects = array();
        $keys->fechas = array('fecha');

        $init_data = array();
        $init_data['doc_documento'] = "gamboamartin\\documento";
        $init_data['pr_etapa_proceso'] = "gamboamartin\\proceso";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);

        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
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
        $keys_selects = $this->init_selects(keys_selects: array(), key: "doc_documento_id", label: "Documento", cols: 12);
        return $this->init_selects(keys_selects: $keys_selects, key: "pr_etapa_proceso_id", label: "Proceso", cols: 12);
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha',
            keys_selects: $keys_selects, place_holder: 'Fecha');
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

        $keys_selects['doc_documento_id']->id_selected = $this->registro['doc_documento_id'];
        $keys_selects['pr_etapa_proceso_id']->id_selected = $this->registro['pr_etapa_proceso_id'];

        $partes = explode(" ", $this->row_upd->fecha);
        $this->row_upd->fecha = $partes[0];

        $base = $this->upd_base_template(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

}