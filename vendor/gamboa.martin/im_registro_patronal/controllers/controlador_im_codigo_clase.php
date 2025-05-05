<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\im_registro_patronal\controllers;

use gamboamartin\system\_ctl_parent;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\im_codigo_clase_html;
use gamboamartin\im_registro_patronal\models\im_codigo_clase;
use PDO;
use stdClass;

class controlador_im_codigo_clase extends _ctl_parent {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new im_codigo_clase(link: $link);
        $html_ = new im_codigo_clase_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Codigos de Clases de Riesgo';
    }


}
