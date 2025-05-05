<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\direccion_postal\controllers;

use gamboamartin\direccion_postal\models\dp_direccion_pendiente;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\template_1\html;
use html\dp_direccion_pendiente_html;
use PDO;
use stdClass;

class controlador_dp_direccion_pendiente extends _ctl_dps {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new dp_direccion_pendiente(link: $link);
        $html = new dp_direccion_pendiente_html(html: new html());
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $columns["dp_direccion_pendiente_id"]["titulo"] = "Id";
        $columns["dp_direccion_pendiente_descripcion_pais"]["titulo"] = "Pais";
        $columns["dp_direccion_pendiente_descripcion_estado"]["titulo"] = "Estado";
        $columns["dp_direccion_pendiente_descripcion_municipio"]["titulo"] = "Municipio";
        $columns["dp_direccion_pendiente_descripcion_cp"]["titulo"] = "CP";
        $columns["dp_direccion_pendiente_descripcion_colonia"]["titulo"] = "Colonia";
        $columns["dp_direccion_pendiente_descripcion_calle_pertenece"]["titulo"] = "Calle Pertenece";

        $datatables = new stdClass();
        $datatables->columns = $columns;

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Direcciones Pendientes';

        $this->asignar_propiedad(identificador:'descripcion_pais', propiedades: ['place_holder'=> 'Pais']);
        $this->asignar_propiedad(identificador:'descripcion_estado', propiedades: ['place_holder'=> 'Estado']);
        $this->asignar_propiedad(identificador:'descripcion_municipio', propiedades: ['place_holder'=> 'Municipio']);
        $this->asignar_propiedad(identificador:'descripcion_cp', propiedades: ['place_holder'=> 'CP']);
        $this->asignar_propiedad(identificador:'descripcion_colonia', propiedades: ['place_holder'=> 'Colonia']);
        $this->asignar_propiedad(identificador:'descripcion_calle_pertenece', propiedades: ['place_holder'=> 'Calle Pertenece']);
    }


    private function base(): array|stdClass
    {
        $r_modifica =  parent::modifica(header: false,aplica_form:  false);
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
