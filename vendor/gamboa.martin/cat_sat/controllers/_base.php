<?php
namespace gamboamartin\cat_sat\controllers;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_parent;

class _base extends _ctl_parent {

    /**
     * Genera un formulario de alta
     * @param bool $header si header muestra resultado en web
     * @param bool $ws Si ws muestra resultado en json
     * @return array|string
     */
    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = array('codigo','descripcion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        return $r_alta;
    }


    protected function key_selects_txt(array $keys_selects): array
    {

        $place_holder_desc = $this->tabla;
        $place_holder_desc = str_replace('cat_sat_', '',  $place_holder_desc );
        $place_holder_desc = str_replace('_', ' ', $place_holder_desc);
        $place_holder_desc = ucwords($place_holder_desc);

        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion',
            keys_selects:$keys_selects, place_holder: $place_holder_desc);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }


}
