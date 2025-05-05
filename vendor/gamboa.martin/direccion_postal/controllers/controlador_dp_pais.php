<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\direccion_postal\controllers;

use gamboamartin\direccion_postal\models\dp_pais;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template_1\html;
use html\dp_pais_html;
use PDO;
use stdClass;

class controlador_dp_pais extends _ctl_dps {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new dp_pais(link: $link);
        $html_base = new html();
        $html = new dp_pais_html(html: $html_base);
        $obj_link = new links_menu(link: $link,registro_id:  $this->registro_id);

        $columns["dp_pais_id"]["titulo"] = "Id";
        $columns["dp_pais_codigo"]["titulo"] = "Código";
        $columns["dp_pais_descripcion"]["titulo"] = "Pais";

        $filtro = array("dp_pais.id","dp_pais.codigo","dp_pais.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Paises';

        $propiedades = $this->inicializa_priedades();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar propiedades',data:  $propiedades);
            print_r($error);
            die('Error');
        }

        $this->lista_get_data = true;

        $this->childrens_data['dp_estado']['title'] = 'Estado';
    }



    private function inicializa_priedades(): array
    {
        $identificador = "codigo";
        $propiedades = array("place_holder" => "Código", "cols" => 4);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "descripcion";
        $propiedades = array("place_holder" => "País", "cols" => 8);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        return $this->keys_selects;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica =  parent::modifica(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }



        return $r_modifica;
    }

}
