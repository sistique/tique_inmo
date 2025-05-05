<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\gastos\models\gt_tipo_solicitud;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;

class gt_tipo_solicitud_html extends html_controler {

    /**
     * @param int $cols N cols css
     * @param bool $con_registros true integra rows en options
     * @param int|null $id_selected Identificador
     * @param PDO $link Conexion a la base de datos
     * @param bool $disabled atributo disabled si true
     * @param array $filtro Filtro para obtencion de datos
     * @param bool $required Atributo required si true
     * @return array|string
     * @version 0.163.4
     */

    public function select_gt_tipo_solicitud_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                                bool $disabled = false, array $filtro = array(),
                                                bool $required = false): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }
        if(is_null($id_selected)){
            $id_selected = -1;
        }
        $modelo = new gt_tipo_solicitud($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, filtro: $filtro, label: 'Tipo Solicitud',
            name: 'gt_tipo_solicitud_id', required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
