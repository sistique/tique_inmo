<?php
namespace html;

use gamboamartin\cat_sat\models\cat_sat_cve_prod;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;


class cat_sat_cve_prod_html extends html_controler {

    public function cat_sat_cve_prod_id(int $cols, bool $con_registros, int $id_selected, PDO $link): array|string
    {
        $modelo = new cat_sat_cve_prod($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, label: "Productos SAT");
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
