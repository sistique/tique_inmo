<?php
namespace html;

use gamboamartin\cat_sat\models\cat_sat_actividad_economica;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;

class cat_sat_actividad_economica_html extends html_controler {

    public function select_cat_sat_actividad_economica_id(int $cols, bool $con_registros, int|null $id_selected,
                                                          PDO $link, string $label = ''): array|string
    {
        if(is_null($id_selected)){
            $id_selected = -1;
        }
        $modelo = new cat_sat_actividad_economica($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: $label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
