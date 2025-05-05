<?php
namespace html;

use gamboamartin\administrador\models\adm_menu;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;


class adm_menu_html extends html_controler {


    /**
     * Genera un selector de tipo adm_menu
     * @param int $cols N cols css
     * @param bool $con_registros Si con registros asigna options
     * @param int|null $id_selected identificador precargado
     * @param PDO $link Conexion a la bd
     * @param bool $disabled si disabled el input queda disabled
     * @return array|string
     * @version 0.16.0
     */
    public function select_adm_menu_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                       bool $disabled = false): array|string
    {

        $valida = $this->directivas->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        if(is_null($id_selected)){
            $id_selected = -1;
        }
        $modelo = new adm_menu($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, key_descripcion_select: 'adm_menu_descripcion',label: 'Menu');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
