<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;

use controllers\_init_dps;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\base\empresas;
use gamboamartin\organigrama\html\org_sucursal_html;
use gamboamartin\organigrama\links\secciones\link_org_sucursal;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\organigrama\models\org_tipo_sucursal;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_org_sucursal extends empresas {

    public array|stdClass $keys_selects = array();

    public string $link_dp_pais_alta = '';
    public string $link_dp_estado_alta = '';
    public string $link_dp_municipio_alta = '';
    public string $link_dp_cp_alta = '';
    public string $link_dp_colonia_postal_alta = '';
    public string $link_dp_calle_pertenece_alta = '';
    public string $link_org_empresa_alta = '';
    public string $link_im_registro_patronal_alta_bd = '';
    public string $link_im_registro_patronal_modifica_bd = '';

    public int $em_registro_patronal_id = -1;
    protected int $org_empresa_id = -1;

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new org_sucursal(link: $link);
        $html_ = new org_sucursal_html($html);
        $obj_link = new link_org_sucursal(link: $link, registro_id: $this->registro_id);

        $columns["org_sucursal_id"]["titulo"] = "Id";
        $columns["org_sucursal_codigo"]["titulo"] = "Codigo";
        $columns["org_sucursal_descripcion"]["titulo"] = "Descripcion";
        $columns["org_tipo_sucursal_descripcion"]["titulo"] = "Tipo Sucursal";

        $datatables = new stdClass();
        $datatables->columns = $columns;

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);


        $this->titulo_lista = 'Sucursales';


        $propiedades = $this->inicializa_propiedades();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar propiedades',data:  $propiedades);
            print_r($error);
            die('Error');
        }

        $ids = $this->inicializa_ids();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar ids',data:  $ids);
            print_r($error);
            die('Error');
        }

        $this->parents_verifica[] = new org_tipo_sucursal(link: $this->link);
        $this->parents_verifica[] = new dp_calle_pertenece(link: $this->link);
        $this->parents_verifica[] = new org_empresa(link: $this->link);

        $this->verifica_parents_alta = true;
    }
    public function alta(bool $header, bool $ws = false): array|string
    {

        $urls_js = (new _init_dps())->init_js(controler: $this);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar url js',data:  $urls_js,header: $header,ws: $ws);
        }

        $r_alta =  parent::alta(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->row_upd->fecha_inicio_operaciones = date('Y-m-d');

        $this->keys_selects['interior']->required = false;
        $this->keys_selects['telefono_2']->required = false;
        $this->keys_selects['telefono_3']->required = false;
        $this->keys_selects['codigo']->cols = 12;

        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_alta;
    }


    private function base(): array|stdClass
    {
        $r_modifica =  parent::modifica(header: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar template',data:  $r_modifica);
        }


        $this->keys_selects['org_tipo_sucursal_id']->id_selected = $this->registro['org_tipo_sucursal_id'];
        $this->keys_selects['dp_pais_id']->id_selected = $this->registro['dp_pais_id'];
        $this->keys_selects['dp_pais_id']->key_descripcion_select = 'dp_pais_descripcion';

        $this->keys_selects['dp_estado_id']->id_selected = $this->registro['dp_estado_id'];
        $this->keys_selects['dp_estado_id']->con_registros = true;
        $this->keys_selects['dp_estado_id']->filtro = array('dp_pais.id'=>$this->registro['dp_pais_id']);
        $this->keys_selects['dp_estado_id']->key_descripcion_select = 'dp_estado_descripcion';

        $this->keys_selects['dp_municipio_id']->id_selected = $this->registro['dp_municipio_id'];
        $this->keys_selects['dp_municipio_id']->con_registros = true;
        $this->keys_selects['dp_municipio_id']->filtro = array('dp_estado.id'=>$this->registro['dp_estado_id']);
        $this->keys_selects['dp_municipio_id']->key_descripcion_select = 'dp_municipio_descripcion';

        $this->keys_selects['dp_cp_id']->id_selected = $this->registro['dp_cp_id'];
        $this->keys_selects['dp_cp_id']->con_registros = true;
        $this->keys_selects['dp_cp_id']->filtro = array('dp_municipio.id'=>$this->registro['dp_municipio_id']);
        $this->keys_selects['dp_cp_id']->key_descripcion_select = 'dp_cp_descripcion';

        $this->keys_selects['dp_colonia_postal_id']->id_selected = $this->registro['dp_colonia_postal_id'];
        $this->keys_selects['dp_colonia_postal_id']->con_registros = true;
        $this->keys_selects['dp_colonia_postal_id']->filtro = array('dp_cp.id'=>$this->registro['dp_cp_id']);
        $this->keys_selects['dp_colonia_postal_id']->key_descripcion_select = 'dp_colonia_descripcion';

        $this->keys_selects['dp_calle_pertenece_id']->id_selected = $this->registro['dp_calle_pertenece_id'];
        $this->keys_selects['dp_calle_pertenece_id']->con_registros = true;
        $this->keys_selects['dp_calle_pertenece_id']->filtro = array('dp_colonia_postal.id'=>$this->registro['dp_colonia_postal_id']);
        $this->keys_selects['dp_calle_pertenece_id']->key_descripcion_select = 'dp_calle_descripcion';

        $this->keys_selects['org_empresa_id']->id_selected = $this->registro['org_empresa_id'];
        $this->keys_selects['org_empresa_id']->con_registros = true;
        $this->keys_selects['org_empresa_id']->disabled = true;
        $this->keys_selects['org_empresa_id']->filtro = array('org_empresa.id'=>$this->registro['org_empresa_id']);

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
     * Genera las propiedades
     * @return array
     * @version 5.19.7
     */
    private function datos_inputs(): array
    {
        $datos = array();

        $propiedades = array("label" => "Empresa","cols" => 12, "extra_params_keys" =>
            array("org_empresa_fecha_inicio_operaciones","dp_pais_id","dp_estado_id","dp_municipio_id","dp_cp_id",
                "dp_colonia_postal_id","dp_calle_pertenece_id"));

        $datos['org_empresa_id'] = $propiedades;

        $propiedades = array("label" => "Tipo Sucursal","cols" => 12);
        $datos['org_tipo_sucursal_id'] = $propiedades;

        $propiedades = array("label" => "Pais",'key_descripcion_select'=>'dp_pais_descripcion');
        $datos['dp_pais_id'] = $propiedades;

        $propiedades = array("label" => "Estado","con_registros"=> false);
        $datos['dp_estado_id'] = $propiedades;

        $propiedades = array("label" => "Municipio","con_registros"=> false);
        $datos['dp_municipio_id'] = $propiedades;

        $propiedades = array("label" => "CP","con_registros"=> false);
        $datos['dp_cp_id'] = $propiedades;

        $propiedades = array("label" => "Colonia Postal","con_registros"=> false);
        $datos['dp_colonia_postal_id'] = $propiedades;

        $propiedades = array("label" => "Calle","con_registros"=> false);
        $datos['dp_calle_pertenece_id'] = $propiedades;

        $propiedades = array("place_holder" => "Id","disabled" => true);
        $datos['id'] = $propiedades;

        $propiedades = array("place_holder" => "Codigo");
        $datos['codigo'] = $propiedades;

        $propiedades = array("place_holder" => "Serie");
        $datos['serie'] = $propiedades;

        $propiedades = array("place_holder" => "Exterior");
        $datos['exterior'] = $propiedades;

        $propiedades = array("place_holder" => "Interior");
        $datos['interior'] = $propiedades;

        $propiedades = array("place_holder" => "telefono 1","cols" => 4);
        $datos['telefono_1'] = $propiedades;

        $propiedades = array("place_holder" => "Telefono 2","cols" => 4);
        $datos['telefono_2'] = $propiedades;

        $propiedades = array("place_holder" => "Telefono 3","cols" => 4);
        $datos['telefono_3'] = $propiedades;

        $propiedades = array("place_holder" => "Fecha Inicio");
        $datos['fecha_inicio_operaciones'] = $propiedades;

        return $datos;
    }

    public function get_sucursal(bool $header, bool $ws = true): array|stdClass
    {
        $keys['org_empresa'] = array('id','descripcion','codigo','codigo_bis');;

        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);
        }

        return $salida;
    }

    private function inicializa_links(): array|string
    {
        $this->obj_link->genera_links($this);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar links para sucursal',data:  $this->obj_link);
        }


        $link = $this->obj_link->get_link('dp_pais',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_pais_alta',data:  $link);
        }
        $this->link_dp_pais_alta = $link;

        $link = $this->obj_link->get_link('dp_estado',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_estado_alta',data:  $link);
        }
        $this->link_dp_estado_alta = $link;

        $link = $this->obj_link->get_link('dp_municipio',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_municipio_alta',data:  $link);
        }
        $this->link_dp_municipio_alta = $link;

        $link = $this->obj_link->get_link('dp_cp',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_cp_alta',data:  $link);
        }
        $this->link_dp_cp_alta = $link;

        $link = $this->obj_link->get_link('dp_colonia_postal',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_colonia_postal_alta',data:  $link);
        }
        $this->link_dp_colonia_postal_alta = $link;

        $link = $this->obj_link->get_link('dp_calle_pertenece',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_calle_pertenece_alta',data:  $link);
        }
        $this->link_dp_calle_pertenece_alta = $link;

        $link = $this->obj_link->get_link('org_empresa',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link partida registro_patronal_alta_bd',data:  $link);
        }
        $this->link_org_empresa_alta = $link;

        $link = $this->obj_link->get_link($this->seccion,"registro_patronal_alta_bd");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link partida registro_patronal_alta_bd',data:  $link);
        }
        $this->link_im_registro_patronal_alta_bd = $link;

        $link = $this->obj_link->get_link($this->seccion,"registro_patronal_modifica_bd");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link partida conf_percepcion_alta_bd',data:  $link);
        }
        $this->link_im_registro_patronal_modifica_bd = $link;

        return $link;
    }

    /**
     * Inicializa las propiedades de un input
     * @return array
     */
    private function inicializa_propiedades(): array
    {
        $datos = $this->datos_inputs();
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializa propiedad',data:  $datos);
        }

        $data = $this->integra_data(datos: $datos);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializa propiedades',data:  $data);
        }

        return $this->keys_selects;
    }

    /**
     * Inicializa los elementos id externos
     * @return array
     */
    private function inicializa_ids(): array
    {
        if (isset($_GET['em_registro_patronal_id'])){
            $this->em_registro_patronal_id = $_GET['em_registro_patronal_id'];
        }

        return $_GET;
    }

    /**
     * Integra los datos para inputs
     * @param array $datos datos de inputs
     * @return array
     */
    private function integra_data(array $datos): array
    {
        $data = array();
        foreach ($datos as $identificador=>$propiedades){
            $prop = $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al inicializa propiedad',data:  $prop);
            }
            $data[] = $prop;
        }
        return $data;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {


        $urls_js = (new _init_dps())->init_js(controler: $this);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar url js',data:  $urls_js,header: $header,ws: $ws);
        }
        $this->keys_selects['interior']->required = false;
        $this->keys_selects['telefono_2']->required = false;
        $this->keys_selects['telefono_3']->required = false;
        $this->keys_selects['codigo']->disabled = true;
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }



        return $base->template;
    }


}
