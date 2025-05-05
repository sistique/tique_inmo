<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\direccion_postal\controllers;


use gamboamartin\direccion_postal\models\dp_calle;
use gamboamartin\direccion_postal\models\dp_pais;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template_1\html;
use html\dp_calle_html;
use PDO;
use stdClass;

class controlador_dp_calle extends _ctl_calles {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new dp_calle(link: $link);
        $html_base = new html();
        $html = new dp_calle_html(html: $html_base);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $columns["dp_calle_id"]["titulo"] = "Id";
        $columns["dp_calle_descripcion"]["titulo"] = "Calle";

        $filtro = array("dp_calle.id","dp_calle.descripcion");


        parent::__construct(html: $html, link: $link, modelo: $modelo, obj_link: $obj_link, columns: $columns,
            filtro: $filtro, paths_conf: $paths_conf);

        $this->childrens_data['dp_calle_pertenece']['title'] = 'Calle Pertenece';


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
     * Función que obtiene los campos de dp_calle por medio de un arreglo $keys con los nombres de dichos campos.
     * La variable $salida llama a la función get_out con los parámetros $header, $keys y $ws.
     * En caso de presentarse un error, un if se encarga de capturarlo y mostrar la información correspondiente.
     * Finalmente se retorna la variable $salida.
     * @param bool $header si header da salida html
     * @param bool $ws si ws da salida json
     * @return array|stdClass
     * @version 0.139.10
     */
    public function get_calle(bool $header, bool $ws = true): array|stdClass
    {

        $keys['dp_calle'] = array('id','descripcion','codigo','codigo_bis');

        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);

        }

        return $salida;


    }

    /**
     * @return array
     * @final revisada
     */
    public function inicializa_priedades(): array
    {
        $identificador = "codigo";
        $propiedades = array("place_holder" => "Código", "cols" => 4);
        $prop = $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar propiedad',data:  $prop);
        }

        $identificador = "descripcion";
        $propiedades = array("place_holder" => "Calle", "cols" => 12);
        $prop = $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar propiedad',data:  $prop);
        }

        return $this->keys_selects;
    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true,
                             bool $muestra_btn = true): stdClass|array
    {

        $this->parents_verifica[] = (new dp_pais(link: $this->link));
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base->template;
    }


}
