<?php

namespace gamboamartin\direccion_postal\controllers;

use gamboamartin\errores\errores;
use gamboamartin\system\system;
use stdClass;

class _ctl_dps extends system{

    public array|stdClass $keys_selects = array();

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }



        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_alta;
    }

}
