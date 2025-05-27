<?php
namespace gamboamartin\documento\controllers;
use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_base;
class _parents_doc_base extends _ctl_base{

    /**
     * Genera los keys para inputs de frontend
     * @param array $keys_selects Keys predefinidos
     * @return array
     */
    protected function key_selects_txt(array $keys_selects): array
    {

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'codigo', keys_selects: $keys_selects, place_holder: 'Cod');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Extension');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'documento', keys_selects:$keys_selects, place_holder: 'Documento');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }
}
