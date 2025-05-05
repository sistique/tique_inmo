<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 0.18.1
 * @created 2022-06-21
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;


use gamboamartin\cat_sat\models\cat_sat_tipo_contrato_nom;




use gamboamartin\system\links_menu;

use html\cat_sat_tipo_contrato_nom_html;
use PDO;
use stdClass;

class controlador_cat_sat_tipo_contrato_nom extends _base {

    public function __construct(PDO $link, \gamboamartin\template\html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new cat_sat_tipo_contrato_nom(link: $link);
        $html = new cat_sat_tipo_contrato_nom_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);



        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['cat_sat_tipo_contrato_nom_id']['titulo'] = 'Id';
        $datatables->columns['cat_sat_tipo_contrato_nom_codigo']['titulo'] = 'Codigo';
        $datatables->columns['cat_sat_tipo_contrato_nom_descripcion']['titulo'] = 'Tipo Contrato';

        $datatables->filtro = array();
        $datatables->filtro[] = 'cat_sat_tipo_contrato_nom.id';
        $datatables->filtro[] = 'cat_sat_tipo_contrato_nom.codigo';
        $datatables->filtro[] = 'cat_sat_tipo_contrato_nom.descripcion';


        parent::__construct(html:$html,link: $link,modelo:  $modelo,obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipos de Contratos';
        $this->lista_get_data = true;

    }





}
