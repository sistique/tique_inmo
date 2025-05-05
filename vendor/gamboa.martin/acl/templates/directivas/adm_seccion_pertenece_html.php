<?php
namespace html;

use gamboamartin\acl\controllers\controlador_adm_seccion_pertenece;
use gamboamartin\controllers\controlador_adm_accion;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use models\adm_accion;
use models\adm_seccion_pertenece;
use PDO;
use stdClass;


class adm_seccion_pertenece_html extends html_controler {

    private function asigna_inputs(controlador_adm_seccion_pertenece $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->adm_seccion_id = $inputs->selects->adm_seccion_id;


        return $controler->inputs;
    }



    public function genera_inputs_alta(controlador_adm_seccion_pertenece $controler, array $keys_selects,PDO $link): array|stdClass
    {
        $inputs = $this->init_alta(keys_selects: $keys_selects, link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }



    protected function init_alta(array $keys_selects, PDO $link): array|stdClass
    {
        $selects = $this->selects_alta(keys_selects: $keys_selects, link:  $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;

        return $alta_inputs;
    }





    public function select_adm_seccion_pertenece_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                          bool $disabled = false): array|string
    {
        $modelo = new adm_seccion_pertenece($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, disabled: $disabled,label: 'Seccion Pertenece');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


    protected function selects_alta(array $keys_selects, PDO $link): array|stdClass
    {
        $selects = new stdClass();

        $select = (new adm_menu_html(html: $this->html_base))->select_adm_menu_id(cols: 12,
            con_registros:true, id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }
        $selects->adm_menu_id = $select;

        $select = (new adm_seccion_html(html: $this->html_base))->select_adm_seccion_id(cols: 12,
            con_registros:true, id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }
        $selects->adm_menu_id = $select;

        return $selects;
    }

}
