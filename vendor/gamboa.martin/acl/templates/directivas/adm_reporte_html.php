<?php
namespace html;

use gamboamartin\administrador\models\adm_reporte;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;


class adm_reporte_html extends html_controler {



    public function select_adm_reporte_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                       bool $disabled = false): array|string
    {

        $valida = $this->directivas->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        if(is_null($id_selected)){
            $id_selected = -1;
        }
        $modelo = new adm_reporte($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, key_descripcion_select: 'adm_reporte_descripcion',label: 'Reporte');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
