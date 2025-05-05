<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\direccion_postal\controllers;

use gamboamartin\direccion_postal\models\dp_estado;
use gamboamartin\direccion_postal\models\dp_pais;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template_1\html;
use html\dp_estado_html;

use PDO;
use stdClass;

class controlador_dp_estado extends _ctl_dps {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new dp_estado(link: $link);
        $html_base = new html();
        $html = new dp_estado_html(html: $html_base);
        $obj_link = new links_menu(link: $link,registro_id:  $this->registro_id);

        $columns["dp_estado_id"]["titulo"] = "Id";
        $columns["dp_estado_codigo"]["titulo"] = "Código";
        $columns["dp_pais_descripcion"]["titulo"] = "País";
        $columns["dp_estado_descripcion"]["titulo"] = "Estado";

        $filtro = array("dp_estado.id","dp_estado.codigo","dp_estado.descripcion","dp_pais.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Estados';

        $propiedades = $this->inicializa_priedades();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar propiedades',data:  $propiedades);
            print_r($error);
            die('Error');
        }

        $this->parents_verifica['dp_pais'] = (new dp_pais(link: $this->link));
        $this->verifica_parents_alta = true;

        $this->childrens_data['dp_municipio']['title'] = 'Municipio';



    }

    /**
     * @param bool $header If header muestra directo en aplicacion
     * @param bool $ws If ws retorna un obj en forma JSON
     * @return array|stdClass
     *@example
     * $_GET[pais_id] = 1;
     * retorna un JSON con la forma base de r_resultado_modelo
     */
    public function get_estado(bool $header, bool $ws = true): array|stdClass
    {
        $keys['dp_pais'] = array('id','descripcion','codigo','codigo_bis');
        $keys['dp_estado'] = array('id','descripcion','codigo','codigo_bis');


        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);
        }

        return $salida;
    }

    /**
     * Inicializa propiedades
     * @return array
     */
    private function inicializa_priedades(): array
    {
        $identificador = "dp_pais_id";
        $propiedades = array("label" => "País", 'key_descripcion_select'=>'dp_pais_descripcion');
        $prop = $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al asignar propiedad',data:  $prop);
        }

        $identificador = "codigo";
        $propiedades = array("place_holder" => "Código");
        $prop =$this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al asignar propiedad',data:  $prop);
        }

        $identificador = "descripcion";
        $propiedades = array("place_holder" => "Estado", "cols" => 12);
        $prop = $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al asignar propiedad',data:  $prop);
        }

        return $this->keys_selects;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica =  parent::modifica(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $this->asignar_propiedad(identificador:'dp_pais_id', propiedades: ["id_selected"=>$this->row_upd->dp_pais_id]);

        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_modifica;
    }
}
