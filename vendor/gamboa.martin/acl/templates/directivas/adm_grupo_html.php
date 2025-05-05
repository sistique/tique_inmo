<?php
namespace html;

use gamboamartin\acl\controllers\controlador_adm_grupo;
use gamboamartin\administrador\models\adm_grupo;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use PDO;
use stdClass;


class adm_grupo_html extends html_controler {

    private function asigna_inputs(controlador_adm_grupo $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->adm_menu_id = $inputs->selects->adm_menu_id;


        return $controler->inputs;
    }



    public function genera_inputs_alta(controlador_adm_grupo $controler,PDO $link): array|stdClass
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

    /**
     * Obtiene un select de grupos por accion
     * @param int $cols n columnas css
     * @param bool $con_registros si con registros genera options
     * @param int|null $id_selected Identificador seleccionado
     * @param PDO $link conexion a la base de datos
     * @param bool $disabled si disabled deja el elemento deshabilitado
     * @param array $not_in Filtro para omision de not in
     * @param bool $required Si required
     * @return array|string
     * @version 1.32.0
     */
    public function select_adm_grupo_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                        bool $disabled = false, array $not_in = array(),
                                        bool $required = true): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $modelo = new adm_grupo($link);

        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, disabled: $disabled,label: 'Grupo', not_in: $not_in, required: $required);
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
