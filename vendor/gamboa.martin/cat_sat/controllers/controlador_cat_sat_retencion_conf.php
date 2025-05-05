<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;

use base\controller\controler;
use gamboamartin\cat_sat\models\cat_sat_retencion_conf;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\cat_sat_retencion_conf_html;
use PDO;
use stdClass;

class controlador_cat_sat_retencion_conf extends _cat_sat_impuestos {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new cat_sat_retencion_conf(link: $link);
        $html_ = new cat_sat_retencion_conf_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $configuraciones = $this->init_configuraciones();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar configuraciones', data: $configuraciones);
            print_r($error);
            die('Error');
        }
    }




    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Configuraciones de Retenciones';

        $this->lista_get_data = true;

        return $this;
    }

    /**
     * Inicializa los elementos para datatable
     * @return stdClass
     * @version 7.32.1
     */
    private function init_datatable(): stdClass
    {
        $columns["cat_sat_retencion_conf_id"]["titulo"] = "Id";
        $columns["cat_sat_retencion_conf_codigo"]["titulo"] = "Código";
        $columns["cat_sat_retencion_conf_descripcion"]["titulo"] = "Configuraciones";

        $filtro = array("cat_sat_retencion_conf.id","cat_sat_retencion_conf.codigo","cat_sat_retencion_conf.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }




}