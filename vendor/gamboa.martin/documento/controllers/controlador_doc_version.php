<?php
namespace gamboamartin\documento\controllers;

use gamboamartin\documento\models\doc_version;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template_1\html;
use html\doc_version_html;
use PDO;
use stdClass;

class controlador_doc_version extends _parents_doc_base {
    public function __construct(PDO $link,  html $html = new html(), stdClass $paths_conf = new stdClass()){
        $modelo = new doc_version($link);

        $html_ = new doc_version_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['doc_version_id']['titulo'] = 'Id';
        $datatables->columns['doc_documento_id']['titulo'] = 'Id Doc';
        $datatables->columns['doc_extension_descripcion']['titulo'] = 'Extension';
        $datatables->columns['doc_tipo_documento_descripcion']['titulo'] = 'Tipo Doc';
        $datatables->columns['doc_version_fecha_alta']['titulo'] = 'F Alta';


        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Versiones';


        $this->lista_get_data = true;

        $this->modelo = $modelo;

    }


    public function alta(bool $header, bool $ws = false): array|string
    {
        return $this->retorno_error(
                mensaje: 'Error esta accion no se puede ejecutar desde esta parte',data:  array(), header: $header,ws:  $ws);

    }


    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion');
        $keys->selects = array();


        $init_data = array();
        $init_data['doc_documento'] = "gamboamartin\\documento";

        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);


        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    public function descarga(bool $header, bool $ws = false){
        ob_clean();
        $doc_version = $this->modelo->registro(registro_id: $this->registro_id, retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar obtener documento',data:  $doc_version,header: $header,ws: $ws);
        }
        $ruta_absoluta = $doc_version->doc_version_ruta_absoluta;
        if(file_exists($ruta_absoluta)) {

            $download = (new _docs())->download(header: $header, ruta_absoluta: $ruta_absoluta);
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al generar descargar documento',data:  $download,header: $header,ws: $ws);
            }


        }
        exit;

    }


    public function modifica(
        bool $header, bool $ws = false): array|stdClass
    {
        return $this->retorno_error(
            mensaje: 'Error esta accion no se puede ejecutar desde esta parte',data:  array(), header: $header,ws:  $ws);
    }
}