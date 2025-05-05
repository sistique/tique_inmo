<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\im_registro_patronal\models\im_detalle_conf_prestaciones;
use PDO;
use stdClass;


class im_detalle_conf_prestaciones_html extends html_controler {

    public function select_im_detalle_conf_prestaciones_id(int $cols,bool $con_registros,int $id_selected,
                                                           PDO $link): array|string
    {
        $modelo = new im_detalle_conf_prestaciones($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Detalle Configuracion Prestaciones',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }



}
