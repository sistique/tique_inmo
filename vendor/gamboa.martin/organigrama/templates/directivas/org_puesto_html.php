<?php
namespace gamboamartin\organigrama\html;

use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_puesto;
use gamboamartin\organigrama\models\org_puesto;
use gamboamartin\system\html_controler;
use gamboamartin\template\directivas;
use gamboamartin\validacion\validacion;


use PDO;
use stdClass;

class org_puesto_html extends html_controler {

    /**
     * Asigna los valores de un conjunto de inputs para se mostrados en front
     * @param controlador_org_puesto $controler Controlador en ejecucion
     * @param array|stdClass $inputs Inputs precargados
     * @return array|stdClass
     * @version 0.280.36
     */
    private function asigna_inputs(controlador_org_puesto $controler, array|stdClass $inputs): array|stdClass
    {
        if(is_object($inputs)){
            $inputs = (array)$inputs;
        }
        $keys = array('selects');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys, registro: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar inputs',data:  $valida);
        }

        $keys = array('org_tipo_puesto_id','org_departamento_id');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys, registro: $inputs['selects']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar inputs',data:  $valida);
        }

        $keys = array('inputs');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys, registro: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar inputs',data:  $valida);
        }

        $keys = array('descripcion');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys, registro: $inputs['inputs']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar inputs',data:  $valida);
        }

        if(is_array($controler->inputs)){
            $controler->inputs = new stdClass();
        }



        $controler->inputs->descripcion = $inputs['inputs']->descripcion;
        $controler->inputs->select = new stdClass();
        $controler->inputs->select->org_tipo_puesto_id = $inputs['selects']->org_tipo_puesto_id;
        $controler->inputs->select->org_departamento_id = $inputs['selects']->org_departamento_id;

        return $controler->inputs;
    }

    public function genera_inputs_alta(controlador_org_puesto $controler, modelo $modelo, PDO $link, array $keys_selects = array()): array|stdClass
    {
        $inputs = $this->init_alta2(row_upd: $controler->row_upd,modelo: $controler->modelo,keys_selects: $keys_selects);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }


    private function genera_inputs_modifica(controlador_org_puesto $controler,PDO $link): array|stdClass
    {
        $inputs = $this->init_modifica(link: $link, row_upd: $controler->row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);
        }

        $inputs_asignados = $this->asigna_inputs(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    private function init_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {

        $selects = $this->selects_modifica(link: $link, row_upd: $row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $texts = $this->texts_modifica_base(row_upd: $row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }

        $alta_inputs['inputs'] = $texts;
        $alta_inputs['selects'] = $selects;

        return $alta_inputs;
    }

    private function texts_modifica_base(stdClass $row_upd): array|stdClass
    {
        $texts = new stdClass();

        $in_descripcion = $this->input_descripcion(cols: 12,row_upd:  $row_upd,value_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_descripcion);
        }
        $texts->descripcion = $in_descripcion;

        return $texts;
    }

    public function inputs_org_puesto(controlador_org_puesto $controlador_org_puesto): array|stdClass
    {
        $inputs = $this->genera_inputs_modifica(controler: $controlador_org_puesto, link: $controlador_org_puesto->link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);
        }
        return $inputs;
    }

    /**
     * Genera un select de puesto
     * @param int $cols columnas css
     * @param bool $con_registros si con registros integra options
     * @param int|null $id_selected Id seleccionado
     * @param PDO $link Conexion a la base de datos
     * @param bool $disabled si disabled integra attr disabled a input
     * @param bool $required si required integra attr required a input
     * @return array|string
     * @version 0.311.41
     */
    public function select_org_puesto_id(int $cols, bool $con_registros, int|null $id_selected,
                                         PDO $link, bool $disabled = false,  string $label = "Puesto",
                                         bool $required = false): array|string
    {
        $valida = (new directivas(html:$this->html_base))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        if(is_null($id_selected)){
            $id_selected = -1;
        }

        $modelo = new org_puesto($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, disabled: $disabled,label: $label,required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    private function selects_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {

        $selects = new stdClass();

        $select = (new org_tipo_puesto_html(html:$this->html_base))->select_org_tipo_puesto_id(
            cols: 12, con_registros:true, id_selected:$row_upd->org_tipo_puesto_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }

        $selects->org_tipo_puesto_id = $select;

        $select = (new org_departamento_html(html:$this->html_base))->select_org_departamento_id(
            cols: 12, con_registros:true, id_selected:$row_upd->org_departamento_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }

        $selects->org_departamento_id = $select;

        return $selects;
    }

}
