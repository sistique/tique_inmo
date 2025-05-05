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
use gamboamartin\organigrama\html\org_tipo_sucursal_html;
use gamboamartin\organigrama\models\org_tipo_sucursal;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;

use gamboamartin\template\html;

use PDO;
use stdClass;

class controlador_org_tipo_sucursal extends _ctl_parent_sin_codigo {

    public array|stdClass $keys_selects = array();

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_tipo_sucursal(link: $link);
        $html = new org_tipo_sucursal_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);

        $columns["org_tipo_sucursal_id"]["titulo"] = "Id";
        $columns["org_tipo_sucursal_codigo"]["titulo"] = "Código";
        $columns["org_tipo_sucursal_descripcion"]["titulo"] = "Tipo Sucursal";

        $filtro = array("org_tipo_sucursal.id","org_tipo_sucursal.codigo","org_tipo_sucursal.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipo Sucursal';

        $this->childrens_data['org_sucursal']['title'] = 'Sucursal';


    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Tipo Sucursal');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }




}
