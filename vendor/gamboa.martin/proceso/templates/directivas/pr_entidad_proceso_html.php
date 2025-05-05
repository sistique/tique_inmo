<?php
namespace gamboamartin\proceso\html;

use gamboamartin\errores\errores;
use gamboamartin\proceso\controllers\controlador_pr_entidad_proceso;
use gamboamartin\proceso\models\pr_entidad_proceso;
use gamboamartin\proceso\models\pr_etapa_proceso;
use gamboamartin\system\html_controler;
use html\adm_seccion_html;
use PDO;
use stdClass;


class pr_entidad_proceso_html extends html_controler {


    private function asigna_inputs(controlador_pr_entidad_proceso $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();
        $controler->inputs->select->adm_seccion_id = $inputs->selects->adm_seccion_id;
        $controler->inputs->select->pr_proceso_id = $inputs->selects->pr_proceso_id;
        return $controler->inputs;
    }

    public function genera_inputs_alta(controlador_pr_entidad_proceso $controler, PDO $link): array|stdClass
    {
        $inputs = $this->init_alta_base(link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    private function genera_inputs_modifica(controlador_pr_entidad_proceso $controler,PDO $link,
                                            stdClass $params = new stdClass()): array|stdClass
    {
        $inputs = $this->init_modifica(link: $link, row_upd: $controler->row_upd, params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    private function init_alta_base(PDO $link): array|stdClass
    {
        $keys_selects = array();
        $selects = $this->selects_alta(keys_selects: $keys_selects, link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $texts = $this->texts_alta(row_upd: new stdClass(), value_vacio: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;
        $alta_inputs->texts = $texts;

        return $alta_inputs;
    }

    private function init_modifica(PDO $link, stdClass $row_upd, stdClass $params = new stdClass()): array|stdClass
    {
        $selects = $this->selects_modifica(link: $link, row_upd: $row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $texts = $this->texts_alta(row_upd: $row_upd, value_vacio: false, params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->texts = $texts;
        $alta_inputs->selects = $selects;
        return $alta_inputs;
    }

    public function inputs_pr_entidad_proceso(controlador_pr_entidad_proceso $controlador,
                                    stdClass $params = new stdClass()): array|stdClass
    {
        $inputs = $this->genera_inputs_modifica(controler: $controlador,
            link: $controlador->link, params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);
        }
        return $inputs;
    }

    protected function selects_alta(array $keys_selects, PDO $link): array|stdClass
    {
        $selects = new stdClass();

        $select = (new adm_seccion_html(html:$this->html_base))->select_adm_seccion_id(
            cols: 6, con_registros:true, id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }
        $selects->adm_seccion_id = $select;

        $select = (new pr_proceso_html(html:$this->html_base))->select_pr_proceso_id(
            cols: 6, con_registros:true, id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }
        $selects->pr_proceso_id = $select;

        return $selects;
    }

    private function selects_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {
        $selects = new stdClass();

        $select = (new adm_seccion_html(html:$this->html_base))->select_adm_seccion_id(
            cols: 6, con_registros:true, id_selected:$row_upd->adm_seccion_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }
        $selects->adm_seccion_id = $select;

        $select = (new pr_proceso_html(html:$this->html_base))->select_pr_proceso_id(
            cols: 6, con_registros:true, id_selected:$row_upd->pr_proceso_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }
        $selects->pr_proceso_id = $select;

        return $selects;
    }

    public function select_pr_entidad_proceso_id(int $cols, bool $con_registros, int $id_selected, PDO $link): array|string
    {
        $modelo = new pr_entidad_proceso(link: $link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, label: 'Entidad proceso',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    protected function texts_alta(stdClass $row_upd, bool $value_vacio, stdClass $params = new stdClass()): array|stdClass
    {
        $texts = new stdClass();
        return $texts;
    }

}
