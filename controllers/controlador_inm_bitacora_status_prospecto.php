<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\inmuebles\controllers;

use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_bitacora_status_prospecto_html;
use gamboamartin\inmuebles\models\inm_bitacora_status_prospecto;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_bitacora_status_prospecto extends _ctl_formato {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_bitacora_status_prospecto(link: $link);
        $html_ = new inm_bitacora_status_prospecto_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion');
        $keys->selects = array();

        $init_data = array();
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_bitacora_status_prospecto_id"]["titulo"] = "Id";
        $columns["inm_status_prospecto_descripcion"]["titulo"] = "Descripcion";
        $columns["inm_prospecto_razon_social"]["titulo"] = "Razon Social";
        $columns["inm_bitacora_status_prospecto_fecha_status"]["titulo"] = "Fecha Status";
        $columns["inm_bitacora_status_prospecto_comentarios"]["titulo"] = "Comentarios";

        $filtro = array("inm_bitacora_status_prospecto.id","inm_status_prospecto.descripcion",
            "inm_prospecto.razon_social","inm_bitacora_status_prospecto.fecha_status",
            "inm_bitacora_status_prospecto.comentarios");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }


}
