<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\inmuebles\controllers;

use config\generales;
use gamboamartin\compresor\compresor;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_doc_prospecto_html;
use gamboamartin\inmuebles\models\_dropbox;
use gamboamartin\inmuebles\models\inm_doc_prospecto;
use gamboamartin\inmuebles\models\inm_prospecto;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_doc_prospecto extends _ctl_formato {

    public string $ruta_doc = '';
    public bool $es_imagen = false;
    public bool $es_pdf = false;

    public string $button_inm_doc_prospecto_descarga = '';
    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_doc_prospecto(link: $link);
        $html_ = new inm_doc_prospecto_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);


    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }
        $keys_selects = array();

        $columns_ds = array('inm_prospecto_id','inm_prospecto_nss','inm_prospecto_curp','inm_prospecto_nombre',
            'inm_prospecto_apellido_paterno');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_prospecto_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Prospecto', columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $columns_ds = array('doc_tipo_documento_descripcion');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'doc_tipo_documento_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Tipo de Documento', columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }


        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $documento = $this->html->input_file(cols: 12,name:  'documento',row_upd:  new stdClass(),value_vacio:  false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento, header: $header,ws:  $ws);
        }

        $this->inputs->documento = $documento;

        return $r_alta;
    }

    public function alta_bd(bool $header, bool $ws = false): array|stdClass
    {
        $_POST['params'] = array();
        if (isset($_GET['pestana_general_actual'])) {
            $_POST['params'] = array('pestana_general_actual' => 'pestanageneral1',
                'pestana_actual' => $_GET['pestana_actual']);
        }

        $r_alta_bd = parent::alta_bd($header, $ws);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $r_alta_bd, header: $header, ws: $ws);
        }

        return $r_alta_bd;
    }


    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array();
        $keys->selects = array();

        $init_data = array();
        $init_data['inm_prospecto'] = "gamboamartin\\inmuebles";

        $init_data['doc_tipo_documento'] = "gamboamartin\\documento";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    public function descarga(bool $header, bool $ws = false): array|string
    {
        $registro = $this->modelo->registro(registro_id: $this->registro_id, retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener documento',data:  $registro,header:  $header,
                ws:  $ws);
        }
        $name_file = $this->name_file(registro: $registro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener name_file',data:  $name_file,header:  $header,
                ws:  $ws);
        }

        if((new generales())->guarda_archivo_dropbox){
            $guarda = (new _dropbox(link: $this->link))->download(dropbox_id: $registro->inm_dropbox_ruta_id_dropbox,
                archivo_local: $name_file);
            if (errores::$error) {
                return $this->retorno_error('Error al guardar archivo', $guarda,header:  $header,
                    ws:  $ws);
            }
        }

        $ruta_doc = $this->path_base."$registro->doc_documento_ruta_relativa";

        $content = file_get_contents($ruta_doc);

        if($header) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            // Define headers
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$name_file");
            header("Content-Type: application/$registro->doc_extension_descripcion");
            header("Content-Transfer-Encoding: binary");

            // Read the file
            readfile($ruta_doc);
            exit;
        }
        return $content;

    }

    public function descarga_zip(bool $header, bool $ws = false): array|string
    {

        $registro = $this->modelo->registro(registro_id: $this->registro_id, retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener documento',data:  $registro,header:  $header,
                ws:  $ws);
        }
        $ruta_doc = $this->path_base."$registro->doc_documento_ruta_relativa";

        if((new generales())->guarda_archivo_dropbox) {
            $guarda = (new _dropbox(link: $this->link))->preview(dropbox_id: $registro->inm_dropbox_ruta_id_dropbox,
                extencion: $registro->doc_extension_descripcion);
            if (errores::$error) {
                return $this->retorno_error('Error al guardar archivo', $guarda, header: $header,
                    ws: $ws);
            }

            $ruta_doc = $this->path_base.$guarda->ruta_archivo;
        }

        $name = $this->name_doc(registro: $registro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener name',data:  $name,header:  $header,
                ws:  $ws);
        }
        $name_zip  = $name.'.zip';

        $name_file = $this->name_file(registro: $registro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener name_file',data:  $name_file,header:  $header,
                ws:  $ws);
        }

        $archivos[$ruta_doc] = $name_file;
        $comprime = compresor::descarga_zip_multiple(archivos: $archivos, name_zip: $name_zip);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al comprimir file',data:  $comprime,header:  $header,
                ws:  $ws);
        }


        return $comprime;

    }

    public function elimina_bd(bool $header, bool $ws = false): array|stdClass
    {
        $_POST['params'] = array();
        if(isset($_GET['pestana_general_actual'])) {
            $_POST['params'] = array('pestana_general_actual' => $_GET['pestana_general_actual'],
                'pestana_actual' => $_GET['pestana_actual']);
        }

        $r_elimina_bd =  parent::elimina_bd($header, $ws);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $r_elimina_bd, header: $header,ws:  $ws);
        }

        return $r_elimina_bd;
    }

    public function elimina_temporal(bool $header, bool $ws = false){

        $modelo_inm_doc_prospecto = new inm_doc_prospecto(link: $this->link);
        $registro = $modelo_inm_doc_prospecto->registro(registro_id: $_POST['id']);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al comprimir file',data:  $registro,header:  $header,
                ws:  $ws);
        }

        $generales = new generales();
        $path_base = $generales->path_base;
        $archivo_local = $path_base.'archivos/temporales/'.$registro['inm_dropbox_ruta_id_dropbox'].'.'.
            $registro['doc_extension_descripcion'];

        if(file_exists($archivo_local)){
            unlink($archivo_local);
        }

        return $archivo_local;
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

    private function name_doc(stdClass $registro): string
    {
        $name = $registro->inm_prospecto_id.".".$registro->inm_prospecto_nombre;
        $name .= ".".$registro->inm_prospecto_apellido_paterno;
        $name .= ".".$registro->inm_prospecto_apellido_materno.".".$registro->doc_tipo_documento_codigo;
        return $name;
    }

    private function name_file(stdClass $registro): array|string
    {
        $name = $this->name_doc(registro: $registro);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener name',data:  $name);
        }
        $name .= ".".$registro->doc_extension_descripcion;
        return $name;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_doc_prospecto_id"]["titulo"] = "Id";
        $columns["doc_documento_id"]["titulo"] = "Id Doc";
        $columns["doc_documento_ruta_relativa"]["titulo"] = "Ruta";
        $columns["doc_tipo_documento_descripcion"]["titulo"] = "Tipo de Documento";
        $columns["inm_prospecto_nombre"]["titulo"] = "Nombre";
        $columns["inm_prospecto_apellido_paterno"]["titulo"] = "AP";
        $columns["inm_prospecto_apellido_materno"]["titulo"] = "AM";

        $filtro = array("inm_doc_prospecto.id","doc_documento.id", "doc_documento.ruta_relativa",
            'doc_tipo_documento.descripcion','inm_prospecto.nombre','inm_prospecto.apellido_paterno',
            'inm_prospecto_apellido_materno');

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    public function vista_previa(bool $header, bool $ws = false): array|string|stdClass
    {

        $registro = $this->modelo->registro(registro_id: $this->registro_id, retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener documento',data:  $registro,header:  $header,
                ws:  $ws);
        }

            $ruta_doc = $this->url_base."$registro->doc_documento_ruta_relativa";

        if((new generales())->guarda_archivo_dropbox) {
            $guarda = (new _dropbox(link: $this->link))->preview(dropbox_id: $registro->inm_dropbox_ruta_id_dropbox,
                extencion: $registro->doc_extension_descripcion);
            if (errores::$error) {
                return $this->retorno_error('Error al guardar archivo', $guarda, header: $header,
                    ws: $ws);
            }

            $ruta_doc = $guarda->ruta_mostrar;
        }
        $this->ruta_doc = $ruta_doc;

        if($registro->doc_extension_es_imagen === 'activo') {
            $this->es_imagen = true;
        }
        if($registro->doc_extension_descripcion === 'pdf'){
            $this->es_pdf = true;
        }

        $this->inputs = new stdClass();

        $button_inm_doc_prospecto_descarga = $this->html->button_href(accion: 'descarga',etiqueta:  'Descarga',
            registro_id:  $this->registro_id,
            seccion:  $this->seccion,style:  'success');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar button',data:  $button_inm_doc_prospecto_descarga,
                header: $header,ws:  $ws);
        }

        $this->button_inm_doc_prospecto_descarga = $button_inm_doc_prospecto_descarga;

        $inm_doc_prospecto_id = $this->html->hidden(name:'inm_doc_prospecto_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_doc_prospecto_id,
                header: $header,ws:  $ws);
        }
        $this->inputs->inm_doc_prospecto_id = $inm_doc_prospecto_id;

        return $registro;
    }

    public function vista_previa_expediente(bool $header, bool $ws = false): array|string|stdClass
    {
        $registro = $this->modelo->registro(registro_id: $this->registro_id, retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener documento',data:  $registro,header:  $header,
                ws:  $ws);
        }

        $ruta_doc = $this->url_base."$registro->doc_documento_ruta_relativa";

        if((new generales())->guarda_archivo_dropbox) {
            $guarda = (new _dropbox(link: $this->link))->preview(dropbox_id: $registro->inm_dropbox_ruta_id_dropbox,
                extencion: $registro->doc_extension_descripcion);
            if (errores::$error) {
                return $this->retorno_error('Error al guardar archivo', $guarda, header: $header,
                    ws: $ws);
            }

            $ruta_doc = $guarda->ruta_mostrar;
        }
        $this->ruta_doc = $ruta_doc;

        if($registro->doc_extension_es_imagen === 'activo') {
            $this->es_imagen = true;
        }
        if($registro->doc_extension_descripcion === 'pdf'){
            $this->es_pdf = true;
        }

        $this->inputs = new stdClass();

        $button_inm_doc_prospecto_descarga = $this->html->button_href(accion: 'descarga',etiqueta:  'Descarga',
            registro_id:  $this->registro_id,
            seccion:  $this->seccion,style:  'success');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar button',data:  $button_inm_doc_prospecto_descarga,
                header: $header,ws:  $ws);
        }

        $this->button_inm_doc_prospecto_descarga = $button_inm_doc_prospecto_descarga;

        $inm_doc_prospecto_id = $this->html->hidden(name:'inm_doc_prospecto_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_doc_prospecto_id,
                header: $header,ws:  $ws);
        }
        $this->inputs->inm_doc_prospecto_id = $inm_doc_prospecto_id;

        return $registro;
    }


}
