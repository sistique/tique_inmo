<?php
namespace gamboamartin\direccion_postal\src;

use gamboamartin\errores\errores;

class init extends \gamboamartin\system\init {

    /**
     * limpia los elementos de un POST para dependencias de altas
     * @return array
     * @version 0.163.10
     */
    public function limpia_data_alta(): array
    {
        $keys = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id');
        $_POST = $this->limpia_rows(keys: $keys,row:  $_POST);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar datos',data:  $_POST);
        }
        return $_POST;
    }

}


