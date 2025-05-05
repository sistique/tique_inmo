<?php
namespace html;

use gamboamartin\cat_sat\controllers\controlador_cat_sat_grupo_producto;
use gamboamartin\cat_sat\models\cat_sat_grupo_producto;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;
use stdClass;

class cat_sat_grupo_producto_html extends html_controler {

    public function select_cat_sat_grupo_producto_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new cat_sat_grupo_producto($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Grupo producto',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
