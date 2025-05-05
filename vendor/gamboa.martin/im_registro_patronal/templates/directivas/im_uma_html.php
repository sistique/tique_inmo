<?php
namespace html;

use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\controllers\controlador_im_tipo_salario_minimo;
use gamboamartin\im_registro_patronal\controllers\controlador_im_uma;
use gamboamartin\nomina\controllers\controlador_nom_conf_nomina;

use gamboamartin\system\html_controler;
use gamboamartin\im_registro_patronal\models\im_tipo_salario_minimo;
use gamboamartin\im_registro_patronal\models\im_uma;
use gamboamartin\im_registro_patronal\models\nom_conf_nomina;
use gamboamartin\im_registro_patronal\models\nom_conf_percepcion;
use PDO;
use stdClass;

class im_uma_html extends html_controler {

    public function asigna_inputs_alta(controlador_im_uma $controler, array|stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->fecha_inicio = $inputs['dates']->fecha_inicio;
        $controler->inputs->fecha_fin = $inputs['dates']->fecha_fin;
        $controler->inputs->monto = $inputs['inputs']->monto;

        return $controler->inputs;
    }

    private function asigna_inputs_modifica(controlador_im_uma $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->fecha_inicio = $inputs->texts->fecha_inicio;
        $controler->inputs->fecha_fin = $inputs->texts->fecha_fin;
        $controler->inputs->monto = $inputs->texts->monto;

        return $controler->inputs;
    }

    public function genera_inputs_alta(controlador_im_uma $controler, modelo $modelo, PDO $link, array $keys_selects = array()): array|stdClass
    {
        $inputs = $this->init_alta2(row_upd: $controler->row_upd,modelo: $controler->modelo,keys_selects:  $keys_selects);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs_alta(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    private function genera_inputs_modifica(controlador_im_uma $controler,PDO $link,
                                            stdClass $params = new stdClass()): array|stdClass
    {
        $inputs = $this->init_modifica(link: $link, row_upd: $controler->row_upd, params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs_modifica(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    private function init_modifica(PDO $link, stdClass $row_upd, stdClass $params = new stdClass()): array|stdClass
    {
        $selects = $this->selects_modifica(link: $link, row_upd: $row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $texts = $this->texts_modifica(row_upd: $row_upd, value_vacio: false, params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->texts = $texts;
        $alta_inputs->selects = $selects;
        return $alta_inputs;
    }

    public function inputs_im_uma(controlador_im_uma $controlador,
                                  stdClass $params = new stdClass()): array|stdClass
    {
        $inputs = $this->genera_inputs_modifica(controler: $controlador,
            link: $controlador->link, params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);
        }
        return $inputs;
    }

    public function input_fecha_inicio(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false):
    array|string
    {
        $valida = $this->directivas->valida_cols(cols: $cols);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
        }

        $html = $this->directivas->fecha_required(disabled: $disabled, name: 'fecha_inicio',
            place_holder: 'Fecha Inicio', row_upd: $row_upd, value_vacio: $value_vacio);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols, html: $html);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_fecha_fin(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false):
    array|string
    {
        $valida = $this->directivas->valida_cols(cols: $cols);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
        }

        $html = $this->directivas->fecha_required(disabled: $disabled, name: 'fecha_fin',
            place_holder: 'Fecha Fin', row_upd: $row_upd, value_vacio: $value_vacio);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols, html: $html);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

   public function input_monto(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                               string $name = 'monto', string $place_holder = 'Monto', mixed $value = null): array|string
   {
       $valida = $this->directivas->valida_cols(cols: $cols);
       if (errores::$error) {
           return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
       }

       $html = $this->directivas->input_text_required(disabled: $disabled, name: 'monto',
           place_holder: 'Monto', row_upd: $row_upd, value_vacio: $value_vacio);
       if (errores::$error) {
           return $this->error->error(mensaje: 'Error al generar input', data: $html);
       }

       $div = parent::input_monto($cols, $row_upd, $value_vacio, $disabled, $name, $place_holder, $value);;
       if (errores::$error) {
           return $this->error->error(mensaje: 'Error al integrar div', data: $div);
       }

       return $div;
   }


    private function selects_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {
        $selects = new stdClass();

        return $selects;
    }

    public function select_im_uma_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                           bool $required = true): array|string
    {
        $modelo = new im_uma(link: $link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, label: 'UMA',required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    protected function texts_alta(stdClass $row_upd, bool $value_vacio, stdClass $params = new stdClass()): array|stdClass
    {
        $texts = new stdClass();

        $row_upd->fecha_inicio = date('Y-m-d');

        $in_fecha_inicio = $this->input_fecha_inicio(cols: 6, row_upd: $row_upd, value_vacio: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $in_fecha_inicio);
        }
        $texts->fecha_inicio = $in_fecha_inicio;

        $row_upd->fecha_fin = date('Y-m-d');

        $in_fecha_fin = $this->input_fecha_fin(cols: 6, row_upd: $row_upd, value_vacio: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $in_fecha_fin);
        }
        $texts->fecha_fin = $in_fecha_fin;

        $row_upd->monto = 0;

        $in_monto = $this->input_monto(cols: 6, row_upd: $row_upd, value_vacio: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $in_monto);
        }
        $texts->monto = $in_monto;


        return $texts;
    }

    protected function texts_modifica(stdClass $row_upd, bool $value_vacio, stdClass $params = new stdClass()): array|stdClass
    {
        $texts = new stdClass();

        $in_monto = $this->input_monto(cols: 6, row_upd: $row_upd, value_vacio: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $in_monto);
        }
        $texts->monto = $in_monto;

        $in_fecha_inicio = $this->input_fecha_inicio(cols: 6, row_upd: $row_upd, value_vacio: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $in_fecha_inicio);
        }
        $texts->fecha_inicio = $in_fecha_inicio;

        $in_fecha_fin = $this->input_fecha_fin(cols: 6, row_upd: $row_upd, value_vacio: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $in_fecha_fin);
        }
        $texts->fecha_fin = $in_fecha_fin;


        return $texts;
    }


}
