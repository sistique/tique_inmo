<?php
namespace html;

use gamboamartin\acl\controllers\controlador_adm_usuario;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use models\adm_usuario;
use PDO;
use stdClass;


class adm_usuario_html extends html_controler {

    private function asigna_inputs(controlador_adm_usuario $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->adm_menu_id = $inputs->selects->adm_menu_id;


        return $controler->inputs;
    }



    public function genera_inputs_alta(controlador_adm_usuario $controler,PDO $link): array|stdClass
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
        $selects = $this->selects_alta(keys_selects: $keys_selects, link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;

        return $alta_inputs;
    }

    public function input_am(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                             string $place_holder = 'Apellido Materno'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_text(disabled: $disabled, name: 'ap', place_holder: $place_holder,
            required: false, row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_ap(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                                 string $place_holder = 'Apellido Paterno'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_text_required(disabled: $disabled,name: 'ap',place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_email(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                                   string $place_holder = 'Email'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->email_required(disabled: $disabled,name: 'email',place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_nombre(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                               string $place_holder = 'Nombre'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_text_required(disabled: $disabled,name: 'nombre',place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_password(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                               string $place_holder = 'Password'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_password(disabled: $disabled,name: 'password',place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_telefono(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                                string $place_holder = 'Telefono'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_telefono(disabled: $disabled,name: 'telefono',place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo user
     * @param int $cols N cols css
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio si vacio acepta null
     * @param bool $disabled attr disabled
     * @param string $place_holder attr place holder
     * @return array|string
     * @version 0.34.0
     */
    public function input_user(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                                      string $place_holder = 'User'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_text_required(disabled: $disabled,name: 'user',place_holder: $place_holder,
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }




    public function select_adm_usuario_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                          bool $disabled = false): array|string
    {
        $modelo = new adm_usuario($link);

        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, disabled: $disabled,label: 'Usuario');
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
     */
    protected function selects_alta(array $keys_selects, PDO $link): array|stdClass
    {
        $selects = new stdClass();

        $select = (new adm_grupo_html(html: $this->html_base))->select_adm_grupo_id(cols: 12,
            con_registros:true, id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }
        $selects->adm_menu_id = $select;

        return $selects;
    }

}
