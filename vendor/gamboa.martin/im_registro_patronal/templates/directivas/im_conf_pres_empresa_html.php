<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\controllers\controlador_im_conf_pres_empresa;
use gamboamartin\organigrama\html\org_empresa_html;
use gamboamartin\system\html_controler;
use gamboamartin\im_registro_patronal\models\im_conf_pres_empresa;
use gamboamartin\im_registro_patronal\models\im_conf_prestaciones;
use gamboamartin\system\system;
use PDO;
use stdClass;


class im_conf_pres_empresa_html extends html_controler {

    public function select_im_conf_pres_empresa_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new im_conf_pres_empresa($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Configuracion de Prestaciones Empresa',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    protected function asigna_inputs_alta(system $controler, array|stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->org_empresa_id = $inputs['selects']->org_empresa_id;
        $controler->inputs->select->im_conf_prestaciones_id = $inputs['selects']->im_conf_prestaciones_id;

        return $controler->inputs;
    }

    protected function asigna_inputs_modifica(system $controler, array|stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->org_empresa_id = $inputs->selects->org_empresa_id;
        $controler->inputs->select->im_conf_prestaciones_id = $inputs->selects->im_conf_prestaciones_id;

        return $controler->inputs;
    }

    public function genera_inputs_alta(controlador_im_conf_pres_empresa $controler, array $keys_selects,PDO $link): array|stdClass
    {
        $inputs = $this->init_alta2(
            row_upd: $controler->row_upd,modelo: $controler->modelo,keys_selects:  $keys_selects);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs_alta(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    public function genera_inputs_modifica(controlador_im_conf_pres_empresa $controler,PDO $link,
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

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;
        return $alta_inputs;
    }


    private function selects_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {
        $selects = new stdClass();

        $select = (new org_empresa_html(html:$this->html_base))->select_org_empresa_id(
            cols: 12, con_registros:true, id_selected:$row_upd->org_empresa_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }
        $selects->org_empresa_id = $select;

        $select = (new im_conf_prestaciones_html($this->html_base))->select_im_conf_prestaciones_id(cols: 12,
            con_registros:true, id_selected: $row_upd->im_conf_prestaciones_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }

        $selects->im_conf_prestaciones_id = $select;

        return $selects;
    }



}
