<?php
namespace gamboamartin\notificaciones\html;

use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\system\html_controler;

use gamboamartin\template\directivas;
use PDO;
use stdClass;


class not_receptor_html extends html_controler {

    public function input_email(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                                string $place_holder = 'Email'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->email_required(disabled: $disabled,name: 'email',place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }


    public function select_not_receptor_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                         array $columns_ds = array('not_receptor_email'),
                                         bool $disabled = false, array $filtro = array(),
                                         array $registros = array()): array|string
    {
        $modelo = new not_receptor(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, columns_ds: $columns_ds, disabled: $disabled, filtro: $filtro, label: 'Receptor',
            registros: $registros, required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }



}