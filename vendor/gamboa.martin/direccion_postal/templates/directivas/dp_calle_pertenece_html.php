<?php
namespace html;

use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;
use stdClass;


class dp_calle_pertenece_html extends html_controler {

    /**
     * Genera un input de tipo entre calles
     * @param int $cols Numero de columnas css
     * @param bool $con_registros Si no con registros deja el select vacio
     * @param array $filtro filtro de datos para options
     * @param int $id_selected id seleccionado
     * @param string $key_descripcion_select
     * @param string $label Etiqueta a mostrar
     * @param PDO $link conexion a base de datos
     * @param string $name name del input
     * @param bool $disabled Si disabled deja el input deshabilitado
     * @param bool $required
     * @return array|string
     * @version 0.72.8
     * @verfuncion 0.1.0
     * @fecha 2022-08-04 13:11
     * @author mgamboa
     */
    private function entre_calles(int $cols, bool $con_registros, array $filtro, int $id_selected,
                                  string $key_descripcion_select, string $label, PDO $link, string $name,
                                  bool $disabled = false, bool $required = false): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }
        $modelo = new dp_calle_pertenece($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, filtro: $filtro, key_descripcion_select: $key_descripcion_select,
            key_id: 'dp_calle_pertenece_id', label: $label, name: $name, required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    /**
     * Genera input a partir de la variable $div, la cual toma como valor un nuevo objeto de la clase inputs_html,
     * y su función input que toma como referencias los valores de $cols, $row_upd, $value_vacio y $campo.
     * En caso de que exista algún error, un if se encarga de capturar el error y mostrar sus respectivos datos.
     * @param int $cols Numero de columnas css
     * @param stdClass $row_upd registro en proceso
     * @param bool $value_vacio Si vacio limpiar valores
     * @param string $campo Nombre del campo para name
     * @return array|string
     * @version 9.91.1
     */
    final public function input(int $cols, stdClass $row_upd, bool $value_vacio, string $campo): array|string
    {

        $valida = $this->directivas->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo debe tener info', data: $campo);
        }

        $div = (new inputs_html())->input(cols: $cols,directivas:  $this->directivas, row_upd: $row_upd,
            value_vacio:  $value_vacio,campo:  $campo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $div);
        }

        return $div;
    }

    /**
     * Genera un select de tipo calle_pertenece
     * @param int $cols Numero de columnas en css
     * @param bool $con_registros Si cno con registros deja el select sin options
     * @param int|null $id_selected Identificador para selected
     * @param PDO $link Conexion a la base de datos
     * @param bool $disabled Si disabled el input que da inactivo
     * @param array $filtro Filtro para obtencion de datos via filtro and del modelo
     * @param string $key_descripcion_select
     * @param string $name
     * @param bool $required Integra attr required html
     * @return array|string
     * @version 1.157.10
     */
    public function select_dp_calle_pertenece_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                                 bool $disabled = false, array $filtro = array(),
                                                 string $key_descripcion_select = 'dp_calle_descripcion',
                                                 string $name='dp_calle_pertenece_id',
                                                 bool $required = false): array|string
    {

        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $select = $this->entre_calles(cols: $cols, con_registros: $con_registros, filtro: $filtro,
            id_selected: $id_selected, key_descripcion_select: $key_descripcion_select, label: 'Calle',
            link: $link, name: $name, disabled: $disabled, required: $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    /**
     * Genera un selector de tipo calle pertenece
     * @param int $cols Numero de columnas en css
     * @param bool $con_registros Si con registros asigna los registros como options
     * @param int $id_selected Identificador
     * @param PDO $link Conexion a la base de datos
     * @param bool $disabled si disabled el input queda deshabilitado
     * @param array $filtro Filtro de registros
     * @param string $key_descripcion_select key de campo a mostrar
     * @param bool $required attr required
     * @return array|string
     */
    public function select_dp_calle_pertenece_entre1_id(int $cols, bool $con_registros, int $id_selected,
                                                        PDO $link, bool $disabled = false,
                                                        array $filtro = array(),
                                                        string $key_descripcion_select = 'dp_calle_descripcion',
                                                        bool $required = false): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $select = $this->entre_calles(cols: $cols, con_registros: $con_registros, filtro: $filtro,
            id_selected: $id_selected, key_descripcion_select: $key_descripcion_select, label: 'Entre calle',
            link: $link, name: 'dp_calle_pertenece_entre1_id', disabled: $disabled, required: $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    /**
     * @param int $cols Numero de columnas en css
     * @param bool $con_registros Si con registros el input queda sin options en select
     * @param int $id_selected
     * @param PDO $link
     * @param bool $disabled
     * @param array $filtro
     * @param string $key_descripcion_select
     * @param bool $required
     * @return array|string
     */
    public function select_dp_calle_pertenece_entre2_id(int $cols, bool $con_registros, int $id_selected,
                                                        PDO $link, bool $disabled = false, array $filtro = array(),
                                                        string $key_descripcion_select = 'dp_calle_descripcion',
                                                        bool $required = false): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $select = $this->entre_calles(cols: $cols, con_registros: $con_registros, filtro: $filtro,
            id_selected: $id_selected, key_descripcion_select: $key_descripcion_select, label: 'Entre calle',
            link: $link, name: 'dp_calle_pertenece_entre2_id', disabled: $disabled, required: $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
