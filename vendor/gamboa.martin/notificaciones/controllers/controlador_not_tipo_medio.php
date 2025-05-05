<?php
/**
 * @author Kevin Acuña Vega
 * @version 1.0.0
 * @created 2022-07-07
 * @final En proceso
 *
 */
namespace gamboamartin\notificaciones\controllers;


use gamboamartin\errores\errores;
use gamboamartin\notificaciones\html\not_tipo_medio_html;
use gamboamartin\notificaciones\models\not_tipo_medio;
use gamboamartin\system\_ctl_parent;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;

use PDO;
use stdClass;

class controlador_not_tipo_medio extends _ctl_parent {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new not_tipo_medio(link: $link);
        $html_ = new not_tipo_medio_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipos de Medios';
    }

    final public function init_datatable(): stdClass
    {

        $columns["not_tipo_medio_id"]["titulo"] = "Id";
        $columns["not_tipo_medio_codigo"]["titulo"] = "Cod";
        $columns["not_tipo_medio_descripcion"]["titulo"] = "Descripcion";


        $filtro[] = array("not_tipo_medio.id");
        $filtro[] = array("not_tipo_medio.codigo");
        $filtro[] = array("not_tipo_medio.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }




}