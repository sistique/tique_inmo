<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\acl\controllers;

use base\controller\init;
use gamboamartin\administrador\models\adm_accion_basica;
use gamboamartin\errores\errores;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\template_1\html;
use html\adm_accion_basica_html;
use links\secciones\link_adm_accion_basica;
use PDO;
use stdClass;


class controlador_adm_accion_basica extends _ctl_parent_sin_codigo {


    public stdClass|array $adm_accion_basica = array();


    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass()){
        $modelo = new adm_accion_basica(link: $link);

        $html_ = new adm_accion_basica_html(html: $html);
        $obj_link = new link_adm_accion_basica(link: $link, registro_id: $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['adm_accion_basica_id']['titulo'] = 'Id';
        $datatables->columns['adm_accion_basica_descripcion']['titulo'] = 'Accion';
        $datatables->columns['adm_accion_basica_css']['titulo'] = 'CSS';
        $datatables->columns['adm_accion_basica_titulo']['titulo'] = 'Titulo';
        $datatables->columns['adm_accion_basica_icono']['titulo'] = 'Icono';

        $datatables->filtro = array();
        $datatables->filtro[] = 'adm_accion_basica.id';
        $datatables->filtro[] = 'adm_accion_basica.descripcion';
        $datatables->filtro[] = 'adm_accion_basica.css';
        $datatables->filtro[] = 'adm_accion_basica.titulo';

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Acciones Basicas';

        if(isset($this->registro_id) && $this->registro_id > 0){
            $adm_accion_basica = (new adm_accion_basica($this->link))->registro(registro_id: $this->registro_id);
            if(errores::$error){
                $error = $this->errores->error(mensaje: 'Error al obtener adm_accion_basica',data:  $adm_accion_basica);
                print_r($error);
                exit;
            }
            $this->adm_accion_basica = $adm_accion_basica;
        }

    }

    public function alta(bool $header, bool $ws = false): array|string
    {


        $r_alta = parent::alta(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects['descripcion'] = new stdClass();
        $keys_selects['descripcion']->cols = 6;

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }


        return $r_alta;
    }

    protected function campos_view(array $inputs = array()): array
    {
        $keys = new stdClass();
        $keys->inputs = array('css','descripcion','titulo','icono');
        $keys->selects = array();



        $campos_view = (new init())->model_init_campos_template(
            campos_view: array(),keys:  $keys, link: $this->link);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    public function es_lista(bool $header = true, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();
        $ejecuta = (new _ctl_permiso())->row_upd(controler: $this, header: $header, key: __FUNCTION__,ws:  $ws);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener ejecutar',data:  $ejecuta, header: $header,ws:  $ws);
        }
        $this->link->commit();

        return $ejecuta;


    }

    public function es_status(bool $header = true, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();
        $ejecuta = (new _ctl_permiso())->row_upd(controler: $this, header: $header, key: __FUNCTION__,ws:  $ws);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener ejecutar',data:  $ejecuta, header: $header,ws:  $ws);
        }
        $this->link->commit();

        return $ejecuta;


    }

    public function es_view(bool $header = true, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();
        $ejecuta = (new _ctl_permiso())->row_upd(controler: $this, header: $header, key: __FUNCTION__,ws:  $ws);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener ejecutar',data:  $ejecuta, header: $header,ws:  $ws);
        }

        $this->link->commit();
        return $ejecuta;


    }

    protected function key_selects_txt(array $keys_selects): array
    {

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Accion Base');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'css', keys_selects:$keys_selects, place_holder: 'CSS');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'titulo', keys_selects:$keys_selects, place_holder: 'Titulo');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'icono', keys_selects:$keys_selects, place_holder: 'Icono',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        return $keys_selects;
    }

    public function muestra_icono_btn(bool $header = true, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();
        $ejecuta = (new _ctl_permiso())->row_upd(controler: $this, header: $header, key: __FUNCTION__,ws:  $ws);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener ejecutar',data:  $ejecuta, header: $header,ws:  $ws);
        }
        $this->link->commit();
        return $ejecuta;


    }

    public function muestra_titulo_btn(bool $header = true, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();
        $ejecuta = (new _ctl_permiso())->row_upd(controler: $this, header: $header, key: __FUNCTION__,ws:  $ws);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener ejecutar',data:  $ejecuta, header: $header,ws:  $ws);
        }
        $this->link->commit();

        return $ejecuta;


    }


}
