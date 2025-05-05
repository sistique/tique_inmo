<?php
namespace html;

use gamboamartin\cat_sat\models\cat_sat_tipo_deduccion_nom;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;

class cat_sat_tipo_deduccion_nom_html extends html_controler {

    /**
     * Genera un select de tipo deduccion nomina
     * @param int $cols No de columnas css
     * @param bool $con_registros si con registros integra todos los options disponibles
     * @param int|null $id_selected id seleccionado
     * @param PDO $link conexion a la base de datos
     * @return array|string
     * @version 0.77.9
     *
     */
    public function select_cat_sat_tipo_deduccion_nom_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link): array|string
    {

        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $modelo = new cat_sat_tipo_deduccion_nom($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, label: 'Tipo deduccion', required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
