<?php

namespace gamboamartin\direccion_postal\controllers;

use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use gamboamartin\system\links_menu;
use PDO;
use stdClass;

class _ctl_calles extends _ctl_dps {

    public function __construct(html_controler $html, PDO $link, modelo $modelo, links_menu $obj_link,
                                array $columns = array(), array $datatables_custom_cols = array(),
                                array $datatables_custom_cols_omite = array(), stdClass $datatables = new stdClass(),
                                array $filtro_boton_lista = array(), string $campo_busca = 'registro_id',
                                array $filtro = array(), string $valor_busca_fault = '',
                                stdClass $paths_conf = new stdClass())
    {
        $datatables = (new _init_dps())->init_datatables(columns: $columns,filtro:  $filtro);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al inicializar datatables',data:  $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $init = (new _init_dps())->init_propiedades_ctl(controler: $this);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al inicializar propiedades',data:  $init);
            print_r($error);
            die('Error');
        }
    }




}
