<?php
namespace html;

use gamboamartin\acl\controllers\controlador_adm_seccion;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;
use stdClass;


class adm_seccion_html extends html_controler {

    private function asigna_inputs(controlador_adm_seccion $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->adm_menu_id = $inputs->selects->adm_menu_id;


        return $controler->inputs;
    }



    public function genera_inputs_alta(controlador_adm_seccion $controler, array $keys_selects,PDO $link): array|stdClass
    {
        $inputs = $this->init_alta(keys_selects: $keys_selects,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    /**
     * Inicializa un elemento para alta
     * @param array $keys_selects  Conjunto de elementos para selects
     * @param PDO $link Conexion a la base de datos
     * @return array|stdClass
     * @version 0.45.1
     */
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

    /**
     * Genera un in put de tipo select
     * @param int $cols N cols css
     * @param bool $con_registros Si con registros integra los  options validos
     * @param int|null $id_selected Identificador
     * @param PDO $link Conexion a la base de datos
     * @param bool $disabled add atributo disabled
     * @return array|string
     * @version 0.44.0
     */
    public function select_adm_seccion_id(int $cols, bool $con_registros, int|null $id_selected, PDO $link,
                                          bool $disabled = false): array|string
    {
        $modelo = new adm_seccion($link);

        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, disabled: $disabled,label: 'Seccion');
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

        $select = (new adm_menu_html(html: $this->html_base))->select_adm_menu_id(cols: 12,
            con_registros:true, id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }
        $selects->adm_menu_id = $select;

        return $selects;
    }

}
