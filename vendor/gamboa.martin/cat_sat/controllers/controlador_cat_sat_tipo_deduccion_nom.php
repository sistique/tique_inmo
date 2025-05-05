<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;

use gamboamartin\cat_sat\models\cat_sat_tipo_deduccion_nom;



use gamboamartin\system\links_menu;
use html\cat_sat_tipo_deduccion_nom_html;
use PDO;
use stdClass;

class controlador_cat_sat_tipo_deduccion_nom extends _base {

    public function __construct(PDO $link,  \gamboamartin\template\html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new cat_sat_tipo_deduccion_nom(link: $link);
        $html_ = new cat_sat_tipo_deduccion_nom_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);



        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['cat_sat_tipo_deduccion_nom_id']['titulo'] = 'Id';
        $datatables->columns['cat_sat_tipo_deduccion_nom_codigo']['titulo'] = 'Codigo';
        $datatables->columns['cat_sat_tipo_deduccion_nom_descripcion']['titulo'] = 'Tipo Deduccion';

        $datatables->filtro = array();
        $datatables->filtro[] = 'cat_sat_tipo_deduccion_nom.id';
        $datatables->filtro[] = 'cat_sat_tipo_deduccion_nom.codigo';
        $datatables->filtro[] = 'cat_sat_tipo_deduccion_nom.descripcion';


        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipo de deduccion';

        $this->lista_get_data = true;

    }



}
