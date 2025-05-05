<?php
namespace html;

use gamboamartin\acl\controllers\controlador_adm_accion_grupo;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use models\adm_accion_grupo;
use PDO;
use stdClass;


class adm_accion_grupo_html extends html_controler {

    private function asigna_inputs(controlador_adm_accion_grupo $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->adm_accion_id = $inputs->selects->adm_accion_id;
        $controler->inputs->select->adm_grupo_id = $inputs->selects->adm_grupo_id;


        return $controler->inputs;
    }



    public function genera_inputs_alta(controlador_adm_accion_grupo $controler,PDO $link): array|stdClass
    {
        $inputs = $this->init_alta(link: $link);
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
        $selects = $this->selects_alta(link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;

        return $alta_inputs;
    }


    public function select_adm_accion_grupo_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                          bool $disabled = false): array|string
    {
        $modelo = new adm_accion_grupo($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, disabled: $disabled,label: 'Permiso');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


    /**
     * Genera los selectores de una seccion
     * @param array $keys_selects keys de select
     * @param PDO $link Conexion a la base de datos
     * @return array|stdClass
     * @version 0.18.0
     */
    protected function selects_alta(array $keys_selects, PDO $link): array|stdClass
    {
        $selects = new stdClass();


        return $selects;
    }

}
