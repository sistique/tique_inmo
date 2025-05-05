<?php
namespace html;

use gamboamartin\cat_sat\models\cat_sat_periodo;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;


class cat_sat_periodo_html extends html_controler {

    /**
     * Genera un select tipo regimen fiscal
     * @param int $cols Numero de columnas css
     * @param bool $con_registros si no con registros integra un select vacio
     * @param int|null $id_selected identificador para selected
     * @param PDO $link conexion a la base de datos
     * @param array $columns_ds
     * @param bool $disabled
     * @param bool $required
     * @return array|string
     * @fecha 2022-08-04 11:27
     * @author mgamboa
     */
    public function select_cat_sat_periodo_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                              array $columns_ds = array('cat_sat_periodo_descripcion'),
                                              bool $disabled = false, bool $required = false): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        if(is_null($id_selected)){
            $id_selected = -1;
        }
        
        $modelo = new cat_sat_periodo($link);


        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, columns_ds: $columns_ds, disabled: $disabled, label: 'Periodo', required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
