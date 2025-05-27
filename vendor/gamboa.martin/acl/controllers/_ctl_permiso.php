<?php
namespace gamboamartin\acl\controllers;

use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\system;
use stdClass;

class _ctl_permiso{

    private errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    public function asigna_permiso(_ctl_base $controler): array|string
    {
        $data_view = new stdClass();
        $data_view->names = array('Id','Accion','Grupo','Seccion','Menu','Acciones');
        $data_view->keys_data = array('adm_accion_id','adm_accion_descripcion','adm_grupo_descripcion','adm_seccion_descripcion','adm_menu_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\administrador\\models';
        $data_view->name_model_children = 'adm_accion_grupo';


        $contenido_table = $controler->contenido_children(data_view: $data_view, next_accion: __FUNCTION__, not_actions: $controler->not_actions);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tbody',data:  $contenido_table);
        }

        return $contenido_table;
    }



    public function row_upd(system $controler, bool $header, string $key, bool $ws): array|stdClass
    {
        $en_transaccion = false;
        if($controler->link->inTransaction()){
            $en_transaccion = true;
        }

        if(!$en_transaccion){
            $controler->link->beginTransaction();
        }

        $upd = $controler->row_upd(key: $key);
        if(errores::$error){
            $controler->link->rollBack();
            return $controler->retorno_error(mensaje: 'Error al obtener row upd',data:  $upd, header: $header,ws:  $ws);
        }
        $controler->link->commit();

        $_SESSION[$upd->salida][]['mensaje'] = $upd->mensaje.' del id '.$controler->registro_id;
        $controler->header_out(result: $upd, header: $header,ws:  $ws);

        return $upd;
    }

}
