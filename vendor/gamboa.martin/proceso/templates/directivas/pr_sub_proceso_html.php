<?php
namespace gamboamartin\proceso\html;

use gamboamartin\proceso\controllers\controlador_pr_proceso;

use gamboamartin\errores\errores;
use gamboamartin\proceso\models\pr_sub_proceso;
use gamboamartin\system\html_controler;


use PDO;
use stdClass;

class pr_sub_proceso_html extends html_controler {

    private function asigna_inputs(controlador_pr_proceso $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();
        return $controler->inputs;
    }

    public function genera_inputs_alta(controlador_pr_proceso $controler, PDO $link): array|stdClass
    {
        $inputs = $this->init_alta(keys_selects: array(), link: $link);
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
        $selects = $this->selects_alta(keys_selects: $keys_selects, link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $texts = $this->texts_alta(row_upd: new stdClass(), value_vacio: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;
        $alta_inputs->texts = $texts;

        return $alta_inputs;
    }





    protected function selects_alta(array $keys_selects,PDO $link): array|stdClass
    {
        $selects = new stdClass();
        return $selects;
    }



    public function select_pr_sub_proceso_id(int $cols, bool $con_registros, int $id_selected, PDO $link): array|string
    {
        $modelo = new pr_sub_proceso(link: $link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Sub Proceso',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    protected function texts_alta(stdClass $row_upd, bool $value_vacio, stdClass $params = new stdClass()): array|stdClass
    {
        $texts = new stdClass();
        return $texts;
    }

}
