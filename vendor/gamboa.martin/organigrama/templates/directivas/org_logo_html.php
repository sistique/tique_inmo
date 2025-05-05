<?php
namespace gamboamartin\organigrama\html;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_logo;
use gamboamartin\system\html_controler;
use PDO;
use stdClass;


class org_logo_html extends html_controler {

    public function input_logo(int $cols, string $name = 'logo', stdClass $row_upd = new stdClass(),
                               bool $value_vacio = false){
        $logo = $this->input_file(cols: $cols, name: $name, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar logo', data: $logo);
        }
        return $logo;
    }
    public function select_org_logo_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                              bool $disabled = false): array|string
    {
        $modelo = new org_logo($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: "Logo", required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
