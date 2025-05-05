<?php
namespace gamboamartin\documento\controllers;

use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_base;

use stdClass;

class _docs {
    private errores $error;

    public function __construct(){
        $this->error = new errores();
    }
    public function acl_tipo_documento(_ctl_base $controler, string $function, array $not_actions){

        $data_view = $this->data_view_acl_tipo_documento();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener data_view',data:  $data_view);
        }
        $contenido_table = $controler->contenido_children(data_view: $data_view, next_accion: $function,not_actions: $not_actions);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tbody',data:  $contenido_table);
        } 

        return $contenido_table;

    }

    public function alta_base(_ctl_base $controlador){
        $r_alta = $controlador->init_alta();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar alta',data:  $r_alta);
        }


        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 12;

        $inputs = $controlador->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inputs',data:  $inputs);
        }

        return $r_alta;
    }

    public function campos_view_base(_ctl_base $controlador){
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion');
        $keys->selects = array();

        $init_data = array();

        $campos_view = $controlador->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Este método genera y retorna un objeto con los datos necesarios para la vista de 'acl_tipo_documento'.
     *
     * @return stdClass Este método retorna un objeto stdClass con las siguientes propiedades:
     *                  - 'names': Un array con los nombres de las columnas para la tabla en la vista.
     *                  - 'keys_data': Un array con las claves de los datos para cada columna de la tabla.
     *                  - 'key_actions': Un string que representa la clave para las acciones que se pueden realizar en cada fila de la tabla.
     *                  - 'namespace_model': Un string con el namespace del modelo que se utilizará.
     *                  - 'name_model_children': Un string con el nombre del modelo de hijos o que dependen que se utilizará.
     *
     * @version 16.0.0
     */
    private function data_view_acl_tipo_documento(): stdClass
    {
        $data_view = new stdClass();
        $data_view->names = array('Id','Tipo Doc', 'Grupo','Acciones');
        $data_view->keys_data = array('doc_acl_tipo_documento_id','doc_tipo_documento_descripcion','adm_grupo_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\documento\\models';
        $data_view->name_model_children = 'doc_acl_tipo_documento';

        return $data_view;
    }

    /**
     * Genera la salida de views de docs childrens
     * @return stdClass
     */
    private function data_view_documento(): stdClass
    {
        $data_view = new stdClass();
        $data_view->names = array('Id','Tipo Doc', 'Doc','Acciones');
        $data_view->keys_data = array('doc_documento_id','doc_tipo_documento_descripcion','doc_documento_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\documento\\models';
        $data_view->name_model_children = 'doc_documento';
        return $data_view;
    }

    /**
     * Genera los datos de una vista children
     * @return stdClass
     */
    private function data_view_ext_permitida(): stdClass
    {
        $data_view = new stdClass();
        $data_view->names = array('Id','Tipo Doc', 'Extension','Acciones');
        $data_view->keys_data = array('doc_extension_permitido_id','doc_tipo_documento_descripcion','doc_extension_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\documento\\models';
        $data_view->name_model_children = 'doc_extension_permitido';
        return $data_view;
    }

    private function data_view_versiones(): stdClass
    {
        $data_view = new stdClass();
        $data_view->names = array('Id','Tipo Doc', 'Doc', 'Ext',' Fecha','Acciones');
        $data_view->keys_data = array('doc_documento_id','doc_tipo_documento_descripcion','doc_documento_descripcion', 'doc_extension_descripcion', 'doc_version_fecha_alta');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\documento\\models';
        $data_view->name_model_children = 'doc_version';
        return $data_view;
    }

    public function documentos(_ctl_base $controler, string $function, array $not_actions = array()){
        $data_view = $this->data_view_documento();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener data view',data:  $data_view);
        }


        $contenido_table = $controler->contenido_children(data_view: $data_view, next_accion: $function,
            not_actions: $not_actions);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tbody',data:  $contenido_table);
        }
        return $contenido_table;
    }

    public function download(bool $header, string $ruta_absoluta): string
    {
        if($header) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($ruta_absoluta) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($ruta_absoluta));
            flush(); // Flush system output buffer
            readfile($ruta_absoluta);
        }
        return file_get_contents($ruta_absoluta);
    }

    public function ext_permitida(_ctl_base $controler, string $function, array $not_actions){
        $data_view = $this->data_view_ext_permitida();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener data_view',data:  $data_view);
        }


        $contenido_table = $controler->contenido_children(data_view: $data_view, next_accion: $function,
            not_actions: $not_actions);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tbody',data:  $contenido_table);
        }
        return $contenido_table;
    }

    public function versiones(_ctl_base $controler, string $function, array $not_actions){

        $data_view = $this->data_view_versiones();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener data_view',data:  $data_view);
        }
        $contenido_table = $controler->contenido_children(data_view: $data_view, next_accion: $function,not_actions: $not_actions);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tbody',data:  $contenido_table);
        }

        return $contenido_table;

    }

}