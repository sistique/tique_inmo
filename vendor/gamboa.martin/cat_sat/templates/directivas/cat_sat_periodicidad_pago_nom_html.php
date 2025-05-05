<?php
namespace html;


use gamboamartin\cat_sat\controllers\controlador_cat_sat_periodicidad_pago_nom;
use gamboamartin\cat_sat\models\cat_sat_periodicidad_pago_nom;
use gamboamartin\errores\errores;

use PDO;
use stdClass;

class cat_sat_periodicidad_pago_nom_html extends cat_sat_html {

    private function asigna_inputs(controlador_cat_sat_periodicidad_pago_nom $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->n_dias = $inputs->texts->n_dias;

        return $controler->inputs;
    }

    public function genera_inputs_alta(controlador_cat_sat_periodicidad_pago_nom $controler,PDO $link): array|stdClass
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


    public function select_cat_sat_periodicidad_pago_nom_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new cat_sat_periodicidad_pago_nom($link);

        $extra_params_keys[] = 'cat_sat_periodicidad_pago_nom_n_dias';

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, extra_params_keys:$extra_params_keys,label: 'Periodicidad pago',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    protected function texts_alta(stdClass $row_upd, bool $value_vacio, stdClass $params = new stdClass()): array|stdClass
    {

        $texts = new stdClass();

        $in_n_dias= $this->input_n_dias(cols: 6,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_n_dias);
        }
        $texts->n_dias = $in_n_dias;

        return $texts;
    }
}
