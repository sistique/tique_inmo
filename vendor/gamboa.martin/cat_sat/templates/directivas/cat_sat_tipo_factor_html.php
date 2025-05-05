<?php
namespace html;

use gamboamartin\cat_sat\models\cat_sat_tipo_factor;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;


class cat_sat_tipo_factor_html extends html_controler {
    public function select_cat_sat_tipo_factor_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                                  bool $required = false): array|string
    {
        $modelo = new cat_sat_tipo_factor($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, label: "Tipo Factor",required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
