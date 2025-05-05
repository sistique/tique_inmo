<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;


use gamboamartin\cat_sat\models\cat_sat_periodicidad;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use gamboamartin\template_1\html;
use html\cat_sat_periodicidad_html;
use PDO;
use stdClass;

class controlador_cat_sat_periodicidad extends system {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new cat_sat_periodicidad(link: $link);
        $html_base = new html();
        $html = new cat_sat_periodicidad_html(html: $html_base);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);
        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Periodicidades';
        $this->lista_get_data = true;

    }




}
