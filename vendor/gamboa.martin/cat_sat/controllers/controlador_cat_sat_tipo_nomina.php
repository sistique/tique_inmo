<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;


use gamboamartin\cat_sat\models\cat_sat_tipo_nomina;




use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\cat_sat_tipo_nomina_html;
use PDO;
use stdClass;

class controlador_cat_sat_tipo_nomina extends _base {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new cat_sat_tipo_nomina(link: $link);
        $html_ = new cat_sat_tipo_nomina_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['cat_sat_tipo_nomina_id']['titulo'] = 'Id';
        $datatables->columns['cat_sat_tipo_nomina_codigo']['titulo'] = 'Cod';
        $datatables->columns['cat_sat_tipo_nomina_descripcion']['titulo'] = 'Tipo Nomina';

        $datatables->filtro = array();
        $datatables->filtro[] = 'cat_sat_tipo_nomina.id';
        $datatables->filtro[] = 'cat_sat_tipo_nomina.codigo';
        $datatables->filtro[] = 'cat_sat_tipo_nomina.descripcion';


        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipos de Nomina';
        $this->lista_get_data = true;

    }




}
