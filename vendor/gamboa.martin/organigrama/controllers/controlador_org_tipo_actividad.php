<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\html\org_tipo_actividad_html;
use gamboamartin\organigrama\models\org_tipo_actividad;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;

use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_org_tipo_actividad extends _ctl_parent_sin_codigo {

    public array|stdClass $keys_selects = array();
    public string $link_org_empresa_alta_bd = '';

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_tipo_actividad(link: $link);
        $html = new org_tipo_actividad_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['org_tipo_actividad_id']['titulo'] = 'Id';
        $datatables->columns['org_tipo_actividad_descripcion']['titulo'] = 'Tipo Actividad';

        $datatables->filtro = array();
        $datatables->filtro[] = 'org_tipo_actividad.id';
        $datatables->filtro[] = 'org_tipo_actividad.descripcion';

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipo Actividad';
        $this->childrens_data['org_actividad']['title'] = 'Actividad';


    }

    /**
     * Integra los params de selectores
     * @param array $keys_selects Parametros de keys
     * @return array
     */
    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo',
            keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion',
            keys_selects:$keys_selects, place_holder: 'Tipo Actividad');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }



        return $keys_selects;
    }

}
