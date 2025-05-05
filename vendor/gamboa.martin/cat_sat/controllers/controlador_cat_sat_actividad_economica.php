<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;

use gamboamartin\cat_sat\models\cat_sat_actividad_economica;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use gamboamartin\template\html;
use html\cat_sat_actividad_economica_html;
use PDO;
use stdClass;

class controlador_cat_sat_actividad_economica extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new cat_sat_actividad_economica(link: $link);
        $html_ = new cat_sat_actividad_economica_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Actividad Economica';

        $this->path_vendor_views = 'gamboa.martin/cat_sat';
        $this->lista_get_data = true;

    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $alta = parent::alta(header: $header, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template', data: $alta,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $codigo = $this->inputs->codigo;

        $descripcion = $this->html->input_descripcion(cols: 6,row_upd: new stdClass(), value_vacio: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar descripcion', data: $descripcion,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }


        $this->inputs->codigo = $codigo;
        $this->inputs->descripcion = $descripcion;

        return $alta;

    }

    /**
     * @return stdClass
     */
    protected function init_datatable(): stdClass
    {
        $columns["cat_sat_actividad_economica_id"]["titulo"] = "Id";
        $columns["cat_sat_actividad_economica_codigo"]["titulo"] = "Código";
        $columns["cat_sat_actividad_economica_descripcion"]["titulo"] = "Descripcion";

        $filtro = array("cat_sat_actividad_economica.id", "cat_sat_actividad_economica.codigo", "
        cat_sat_actividad_economica.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }



    public function modifica(bool $header, bool $ws = false): stdClass|array
    {
        $modifica = parent::modifica(header: $header, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template', data: $modifica,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $codigo = $this->inputs->codigo;


        $descripcion = $this->html->input_descripcion(cols: 6,row_upd: $this->row_upd, value_vacio: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar descripcion', data: $descripcion,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }


        $this->inputs->codigo = $codigo;
        $this->inputs->descripcion = $descripcion;

        return $modifica;

    }





}
