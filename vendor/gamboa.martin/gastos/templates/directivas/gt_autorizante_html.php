<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\gastos\models\gt_autorizante;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;


class gt_autorizante_html extends html_controler {
    /**
     * @param bool $disabled Si disabled el input que da inactivo
     * @param array $filtro Filtro para obtencion de datos via filtro and del modelo
     * @return array|string
     */

    public function select_gt_autorizante_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                             bool $disabled = false, array $filtro = array(),
                                             bool $required = false ): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }
        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $modelo = new gt_autorizante($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, filtro: $filtro, label: 'Autorizante', name: 'gt_autorizante_id',
            required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
