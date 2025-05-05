<?php
namespace gamboamartin\organigrama\html;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_tipo_empresa;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;


class org_tipo_empresa_html extends html_controler {

    /**
     * Genera un select de tipo org_tipo_empresa
     * @param int $cols No de columnas en css
     * @param bool $con_registros si no con registros el select queda libre de options
     * @param int $id_selected identificador del select
     * @param PDO $link conexion a la base de datos
     * @param bool $disabled Atributo disabled si true
     * @return array|string
     * @version 0.277.36
     */
    public function select_org_tipo_empresa_id(int $cols,bool $con_registros,int $id_selected, PDO $link,
                                               bool $disabled = false): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $modelo = new org_tipo_empresa($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: 'Tipo empresa', required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
