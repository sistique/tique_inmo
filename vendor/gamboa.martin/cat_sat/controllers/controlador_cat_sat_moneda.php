<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\direccion_postal\models\dp_pais;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use html\cat_sat_moneda_html;
use PDO;
use stdClass;

class controlador_cat_sat_moneda extends _ctl_base {

    public array|stdClass $keys_selects = array();

    public function __construct(PDO $link, \gamboamartin\template\html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new cat_sat_moneda(link: $link);
        $html_ = new cat_sat_moneda_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $columns["cat_sat_moneda_id"]["titulo"] = "Id";
        $columns["cat_sat_moneda_codigo"]["titulo"] = "Código";
        $columns["dp_pais_descripcion"]["titulo"] = "País";
        $columns["cat_sat_moneda_descripcion"]["titulo"] = "Moneda";

        $filtro = array("cat_sat_moneda.id","cat_sat_moneda.codigo", "cat_sat_moneda.descripcion", "dp_pais.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Monedas';


        $this->lista_get_data = true;
        $this->parents_verifica[] = new dp_pais(link: $this->link);
        $this->verifica_parents_alta = true;

    }

    public function alta(bool $header, bool $ws = false): array|string
    {

        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'dp_pais_id',
            keys_selects: array(), id_selected: -1, label: 'Pais');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }


        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        return $r_alta;
    }

    public function alta_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $result = $this->alta_bd_base();
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar registro',data:  $result, header: $header,ws: $ws);
        }

        $this->link->commit();

        $result = $this->out_alta_bd(header: $header,data_retorno:  $result->data_retorno, result: $result->r_alta_bd, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al retornar', data: $result, header: $header,ws:  $ws);
        }

        return $result;

    }


    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion');
        $keys->selects = array();

        $init_data = array();
        $init_data['dp_pais'] = "gamboamartin\\direccion_postal";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    public function get_cat_sat_moneda(bool $header, bool $ws = false): array|stdClass
    {
        $keys['dp_pais'] = array('id','descripcion','codigo','codigo_bis');
        $keys['cat_sat_moneda'] = array('id','descripcion','codigo','codigo_bis');

        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);

        }
        return $salida;
    }

    protected function key_selects_txt(array $keys_selects): array
    {

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Moneda');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        return $keys_selects;
    }

    public function modifica(
        bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'dp_pais_id',
            keys_selects: array(), id_selected: $this->registro['dp_pais_id'], label: 'Pais');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }


        return $r_modifica;
    }

}
