<?php
namespace gamboamartin\organigrama\html;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_tipo_sucursal;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;


class org_tipo_sucursal_html extends html_controler {


    public function select_org_tipo_sucursal_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {

        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $modelo = new org_tipo_sucursal($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, label: "Tipo Sucursal",required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
