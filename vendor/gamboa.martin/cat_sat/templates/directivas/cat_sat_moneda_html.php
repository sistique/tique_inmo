<?php
namespace html;

use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;


class cat_sat_moneda_html extends html_controler {

    /**
     * Genera un input de tipo select de moneda
     * @param int $cols Cols css
     * @param bool $con_registros si con registros asigna options
     * @param int|null $id_selected identificador selected
     * @param PDO $link conexion a la base de datos
     * @param bool $disabled atributo disabled
     * @param string $label Label input
     * @return array|string
     */
    public function select_cat_sat_moneda_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                             bool $disabled = false, string $label = 'Moneda'): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }
        $modelo = new cat_sat_moneda($link);

        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: $label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
