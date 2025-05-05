<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\direccion_postal\controllers;

use gamboamartin\direccion_postal\models\dp_colonia;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template_1\html;
use html\dp_colonia_html;
use html\dp_cp_html;
use html\dp_estado_html;
use html\dp_municipio_html;
use html\dp_pais_html;
use PDO;
use stdClass;

class controlador_dp_colonia extends _ctl_dps {

    public string $link_colonia_postal_alta_bd = '';
    public array|stdClass $keys_selects = array();

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new dp_colonia(link: $link);
        $html_base = new html();
        $html = new dp_colonia_html(html: $html_base);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $columns["dp_colonia_id"]["titulo"] = "Id";
        $columns["dp_colonia_descripcion"]["titulo"] = "Colonia";


        $filtro = array("dp_colonia.id","dp_colonia.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Colonias';

        $propiedades = $this->inicializa_priedades();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar propiedades',data:  $propiedades);
            print_r($error);
            die('Error');
        }

        $this->childrens_data['dp_colonia_postal']['title'] = 'Colonia Postal';

        $this->lista_get_data = true;

        $link_colonia_postal_alta_bd = $obj_link->link_alta_bd(link: $this->link,seccion:  'dp_colonia_postal');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar link',data:  $link_colonia_postal_alta_bd);
        }
        $this->link_colonia_postal_alta_bd = $link_colonia_postal_alta_bd;
    }


    public function asigna_a_cp(bool $header, bool $ws = true){

        $urls_js = (new _init_dps())->init_js(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $urls_js,header: $header,ws: $ws);

        }
        $this->inputs = new stdClass();

        $filtro['dp_colonia.id'] = $this->registro_id;

        $dp_colonia_id =  (new dp_colonia_html(html: $this->html_base))->select_dp_colonia_id(
            cols: 12, con_registros: true, id_selected: $this->registro_id, link: $this->link, filtro: $filtro);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar input',data:  $dp_colonia_id);
        }
        $this->inputs->dp_colonia_id = $dp_colonia_id;

        $dp_pais_id =  (new dp_pais_html(html: $this->html_base))->select_dp_pais_id(cols: 12,con_registros: true,id_selected: -1,link: $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar input',data:  $dp_pais_id);
        }
        $this->inputs->dp_pais_id = $dp_pais_id;

        $dp_estado_id =  (new dp_estado_html(html: $this->html_base))->select_dp_estado_id(cols: 12,con_registros: false,id_selected: -1,link: $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar input',data:  $dp_estado_id);
        }
        $this->inputs->dp_estado_id = $dp_estado_id;

        $dp_municipio_id =  (new dp_municipio_html(html: $this->html_base))->select_dp_municipio_id(cols: 12,con_registros: false,id_selected: -1,link: $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar input',data:  $dp_estado_id);
        }
        $this->inputs->dp_municipio_id = $dp_municipio_id;

        $dp_cp_id =  (new dp_cp_html(html: $this->html_base))->select_dp_cp_id(cols: 12,con_registros: false,id_selected: -1,link: $this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar input',data:  $dp_estado_id);
        }
        $this->inputs->dp_cp_id = $dp_cp_id;

    }

    private function base(): array|stdClass
    {

        $r_modifica =  parent::modifica(header: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar template',data:  $r_modifica);
        }

        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar inputs',data:  $inputs);
        }

        $data = new stdClass();
        $data->template = $r_modifica;
        $data->inputs = $inputs;

        return $data;
    }

    /**
     * Función que obtiene los campos de dp_colonia por medio de un arreglo $keys con los nombres de dichos campos.
     * La variable $salida llama a la función get_out con los parámetros $header, $keys y $ws.
     * En caso de presentarse un error, un if se encarga de capturarlo y mostrar la información correspondiente.
     * Finalmente se retorna la variable $salida.
     * @param bool $header
     * @param bool $ws
     * @return array|stdClass
     */
    public function get_colonia(bool $header, bool $ws = true): array|stdClass
    {

        $keys['dp_colonia'] = array('id','descripcion','codigo','codigo_bis');

        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);

        }
        return $salida;
    }

    private function inicializa_priedades(): array
    {
        $identificador = "codigo";
        $propiedades = array("place_holder" => "Código", "cols" => 4);
        $prop = $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar propiedad', data: $prop);
        }

        $identificador = "descripcion";
        $propiedades = array("place_holder" => "Colonia", "cols" => 12);
        $prop = $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar propiedad', data: $prop);
        }

        $identificador = "georeferencia";
        $propiedades = array("place_holder" => "Georeferencia", "cols" => 12);
        $prop =$this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar propiedad', data: $prop);
        }

        $identificador = "dp_colonia_id";
        $propiedades = array("label" => "Colonia",'key_descripcion_select' => 'dp_colonia_descripcion', "cols"=>12,'con_registros'=>false);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        return $this->keys_selects;
    }

    final public function init_datatable(): stdClass
    {
        $columns["dp_colonia_id"]["titulo"] = "Id";
        $columns["dp_colonia_descripcion"]["titulo"] = "Colonia";

        $filtro = array("dp_colonia.id","dp_colonia.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true,
                             bool $muestra_btn = true): stdClass|array
    {
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base->template;
    }
}
