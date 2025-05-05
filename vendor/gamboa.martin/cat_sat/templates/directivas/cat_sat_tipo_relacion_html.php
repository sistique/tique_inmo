<?php
namespace html;

use gamboamartin\cat_sat\models\cat_sat_tipo_relacion;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;

class cat_sat_tipo_relacion_html extends html_controler {


    public function select_cat_sat_tipo_relacion_id(int $cols,bool $con_registros,int|null $id_selected, PDO $link,
                                                    bool $disabled = false, bool $required = false): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $modelo = new cat_sat_tipo_relacion($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: 'Tipo relacion', required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
