<?php
namespace html;

use gamboamartin\empleado\models\em_clase_riesgo;
use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\models\im_codigo_clase;
use gamboamartin\system\html_controler;


use PDO;



class im_codigo_clase_html extends html_controler {

    public function select_im_codigo_clase_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new im_codigo_clase($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Codigos de Clase',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }





}
