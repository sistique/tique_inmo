<?php
namespace gamboamartin\notificaciones\html;

use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_emisor;
use gamboamartin\system\html_controler;

use gamboamartin\template\directivas;
use PDO;
use stdClass;


class not_emisor_html extends html_controler {

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

    public function input_host(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                               string $place_holder = 'Host'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_text_required(disabled: $disabled,name: 'host',place_holder: $place_holder,
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

    public function input_port(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                                    string $place_holder = 'Port'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_text_required(disabled: $disabled,name: 'port',place_holder: $place_holder,
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

    public function input_smtp_secure(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                               string $place_holder = 'SMTP Secure'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_text_required(disabled: $disabled,name: 'smtp_secure',place_holder: $place_holder,
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

    public function input_user_name(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false,
                                 string $place_holder = 'User Name'): array|string
    {

        $valida = (new directivas(html: $this->html_base))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->directivas->input_text_required(disabled: $disabled,name: 'user_name',place_holder: $place_holder,
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

    public function select_not_emisor_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                         array $columns_ds = array('not_emisor_user_name'),
                                         bool $disabled = false, array $filtro = array(),
                                         array $registros = array()): array|string
    {
        $modelo = new not_emisor(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, columns_ds: $columns_ds, disabled: $disabled, filtro: $filtro, label: 'Emisor',
            registros: $registros, required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }



}