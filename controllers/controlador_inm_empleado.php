<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\inmuebles\controllers;

use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_empleado_html;
use gamboamartin\inmuebles\models\inm_empleado;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_empleado extends _ctl_formato {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_empleado(link: $link);
        $html_ = new inm_empleado_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }
        $keys_selects = array();
        $columns_selects = array('adm_usuario_user');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_usuario_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Usuario',columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_horario_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Horario');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }      
        
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'apellido_paterno',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }      
        
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'apellido_materno',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }      
        
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'nss',
            keys_selects: $keys_selects, place_holder: 'NSS', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'rfc',
            keys_selects: $keys_selects, place_holder: 'RFC', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'celular',
            keys_selects: $keys_selects, place_holder: 'Celular', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razon Social', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

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
        $keys->inputs = array('descripcion','nss','rfc','nombre','apellido_paterno','apellido_materno','celular',
            'razon_social');
        $keys->selects = array();

        $init_data = array();
        $init_data['adm_usuario'] = "gamboamartin\\administrador";
        $init_data['inm_horario'] = "gamboamartin\\inmuebles";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }



    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $keys_selects = array();
        $columns_selects = array('adm_usuario_user');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'adm_usuario_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->adm_usuario_id, label: 'Usuario',columns_ds: $columns_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(),
            key: 'inm_horario_id', keys_selects: $keys_selects, id_selected: $this->row_upd->inm_horario_id,
            label: 'Horario');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'apellido_paterno',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'apellido_materno',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'nss',
            keys_selects: $keys_selects, place_holder: 'NSS', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'rfc',
            keys_selects: $keys_selects, place_holder: 'RFC', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'celular',
            keys_selects: $keys_selects, place_holder: 'Celular', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razon Social', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        return $r_modifica;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_empleado_id"]["titulo"] = "Id";
        $columns["inm_empleado_razon_social"]["titulo"] = "Razon Social";
        $columns["inm_empleado_rfc"]["titulo"] = "RFC";
        $columns["inm_empleado_nss"]["titulo"] = "NSS";
        $columns["inm_horario_descripcion"]["titulo"] = "Horario";
        $columns["inm_empleado_celular"]["titulo"] = "Celular";
        $columns["adm_usuario_user"]["titulo"] = "Usuario";

        $filtro = array("inm_empleado.id","inm_empleado.razon_social","inm_horario.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }


}
