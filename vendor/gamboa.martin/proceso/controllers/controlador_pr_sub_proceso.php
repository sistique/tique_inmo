<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\proceso\controllers;

use gamboamartin\errores\errores;
use gamboamartin\proceso\html\pr_sub_proceso_html;
use gamboamartin\proceso\models\pr_sub_proceso;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;

use gamboamartin\template\html;


use PDO;
use stdClass;

class controlador_pr_sub_proceso extends _ctl_base {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new pr_sub_proceso(link: $link);
        $html_ = new pr_sub_proceso_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);


        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['pr_sub_proceso_id']['titulo'] = 'Id';
        $datatables->columns['pr_sub_proceso_codigo']['titulo'] = 'Cod';
        $datatables->columns['pr_sub_proceso_descripcion']['titulo'] = 'Sub Proceso';
        $datatables->columns['pr_proceso_descripcion']['titulo'] = 'Proceso';
        $datatables->columns['pr_tipo_proceso_descripcion']['titulo'] = 'Tipo de Proceso';

        $datatables->filtro = array();
        $datatables->filtro[] = 'pr_sub_proceso.id';
        $datatables->filtro[] = 'pr_sub_proceso.codigo';
        $datatables->filtro[] = 'pr_sub_proceso.descripcion';
        $datatables->filtro[] = 'pr_proceso.descripcion';
        $datatables->filtro[] = 'pr_tipo_proceso.descripcion';


        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link,
            datatables: $datatables, paths_conf: $paths_conf);

        $this->titulo_lista = 'Sub Proceso';

        $this->lista_get_data = true;
    }

    public function alta(bool $header, bool $ws = false): array|string
    {

        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'pr_proceso_id',
            keys_selects: array(), id_selected: -1, label: 'Tipo Proceso');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'adm_seccion_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Seccion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }


        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 12;


        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }


        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo','descripcion');
        $keys->selects = array();
        $keys->fechas = array();



        $init_data = array();
        $init_data['pr_proceso'] = "gamboamartin\\proceso";
        $init_data['pr_sub_proceso'] = "gamboamartin\\proceso";
        $init_data['adm_seccion'] = "gamboamartin\\administrador";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }
        return $campos_view;
    }

    protected function inputs_children(stdClass $registro): stdClass|array
    {
        $this->inputs = new stdClass();
        $this->inputs->select = new stdClass();
        return $this->inputs;
    }


    protected function key_selects_txt(array $keys_selects): array
    {

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'codigo', keys_selects: $keys_selects, place_holder: 'Cod');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Sub Proceso');
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

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'pr_proceso_id',
            keys_selects: array(), id_selected: $this->registro['pr_proceso_id'], label: 'Proceso');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'adm_seccion_id',
            keys_selects:$keys_selects, id_selected: $this->registro['adm_seccion_id'], label: 'Seccion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 12;

        $keys_selects['codigo'] = new stdClass();
        $keys_selects['codigo']->disabled = true;


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }




        return $r_modifica;
    }




}