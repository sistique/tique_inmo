<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\notificaciones\controllers;
use gamboamartin\errores\errores;

class controlador_adm_usuario extends \gamboamartin\acl\controllers\controlador_adm_usuario {




    final public function recupera_contrasena(bool $header, bool $ws = false)
    {

        $envia_mensaje = (new _plantilla())->envia_mensaje_accesos(adm_usuario_id: $this->registro_id,link:  $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar mensaje',data:  $envia_mensaje, header: $header,ws:  $ws);
        }

        $out = $this->retorno_base(registro_id: $this->registro_id, result: $envia_mensaje, siguiente_view: 'lista', ws: $ws);
        if(errores::$error){
            print_r($out);
            die('Error');
        }

        return $envia_mensaje;

    }
}
