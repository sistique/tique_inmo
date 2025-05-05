<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\acl\controllers;

use base\controller\init;
use gamboamartin\administrador\models\adm_reporte;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\template_1\html;
use html\adm_reporte_html;
use links\secciones\link_adm_reporte;
use PDO;
use stdClass;


class controlador_adm_reporte extends _ctl_parent_sin_codigo {

    public array $secciones = array();
    public stdClass|array $adm_reporte = array();

    public string $html_trs = '';
    public string $html_ths = '';

    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass()){
        $modelo = new adm_reporte(link: $link);

        $html_ = new adm_reporte_html(html: $html);
        $obj_link = new link_adm_reporte(link: $link, registro_id: $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_reporte_id']['titulo'] = 'Id';
        $datatables->columns['adm_reporte_descripcion']['titulo'] = 'Reporte';


        $datatables->filtro = array();
        $datatables->filtro[] = 'adm_reporte.id';
        $datatables->filtro[] = 'adm_reporte.descripcion';

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Menus';

        if(isset($this->registro_id) && $this->registro_id > 0){
            $adm_reporte = (new adm_reporte($this->link))->registro(registro_id: $this->registro_id);
            if(errores::$error){
                $error = $this->errores->error(mensaje: 'Error al obtener adm_reporte',data:  $adm_reporte);
                print_r($error);
                exit;
            }
            $this->adm_reporte = $adm_reporte;
        }

        $link_adm_seccion_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'adm_seccion');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_adm_seccion_alta_bd);
            print_r($error);
            exit;
        }


        $this->path_vendor_views = 'gamboa.martin/acl';

    }


    protected function campos_view(array $inputs = array()): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion');
        $keys->selects = array();


        $campos_view = (new init())->model_init_campos_template(
            campos_view: array(),keys:  $keys, link: $this->link);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Reporte');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }


        return $keys_selects;
    }


}
