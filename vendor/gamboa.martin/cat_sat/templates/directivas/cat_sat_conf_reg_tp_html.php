<?php
namespace html;

use gamboamartin\cat_sat\controllers\controlador_cat_sat_conf_reg_tp;
use gamboamartin\cat_sat\models\cat_sat_conf_reg_tp;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;
use stdClass;

class cat_sat_conf_reg_tp_html extends html_controler {

    private function asigna_inputs(controlador_cat_sat_conf_reg_tp $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->cat_sat_tipo_persona_id = $inputs->selects->cat_sat_tipo_persona_id;
        $controler->inputs->select->cat_sat_regimen_fiscal_id = $inputs->selects->cat_sat_regimen_fiscal_id;

        return $controler->inputs;
    }

    public function genera_inputs_alta(
        controlador_cat_sat_conf_reg_tp $controler, array $keys_selects,PDO $link): array|stdClass
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


    public function select_cat_sat_reg_tp_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new cat_sat_conf_reg_tp($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Configuracion de Regimen',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
