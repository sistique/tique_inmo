<?php
namespace html;

use gamboamartin\administrador\models\adm_accion_basica;
use gamboamartin\administrador\models\adm_menu;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;


class adm_accion_basica_html extends html_controler {



    public function select_adm_accion_basica_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                       bool $disabled = false): array|string
    {

        $valida = $this->directivas->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        if(is_null($id_selected)){
            $id_selected = -1;
        }
        $modelo = new adm_accion_basica($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, key_descripcion_select: 'adm_accion_basica_descripcion',
            label: 'Accion Basica');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
