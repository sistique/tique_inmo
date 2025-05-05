<?php
namespace gamboamartin\acl\controllers;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_base;
use stdClass;

class _accion_base extends _ctl_base{

    public function alta_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();
        if(isset($_POST['adm_menu_id'])){
            unset($_POST['adm_menu_id']);
        }

        $result = $this->alta_bd_base();
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar registro',data:  $result, header: $header,ws: $ws);
        }
        $this->link->commit();

        $result = $this->out_alta_bd(header: $header,data_retorno:  $result->data_retorno, result: $result->r_alta_bd, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al retornar', data: $result, header: $header,ws:  $ws);
        }


        return $result;
    }

}
