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
use config\generales;
use gamboamartin\direccion_postal\models\dp_estado;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\documento\models\doc_tipo_documento;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_status_ubicacion_html;
use gamboamartin\inmuebles\html\inm_ubicacion_html;
use gamboamartin\inmuebles\html\inm_valuador_html;
use gamboamartin\inmuebles\models\_dropbox;
use gamboamartin\inmuebles\models\_inm_ubicacion;
use gamboamartin\inmuebles\models\inm_bitacora_status_ubicacion;
use gamboamartin\inmuebles\models\inm_cheque;
use gamboamartin\inmuebles\models\inm_conf_docs_ubicacion;
use gamboamartin\inmuebles\models\inm_doc_ubicacion;
use gamboamartin\inmuebles\models\inm_nacionalidad;
use gamboamartin\inmuebles\models\inm_ocupacion;
use gamboamartin\inmuebles\models\inm_poder;
use gamboamartin\inmuebles\models\inm_status_ubicacion;
use gamboamartin\inmuebles\models\inm_ubicacion;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\doc_tipo_documento_html;
use PDO;
use stdClass;

class controlador_inm_ubicacion extends _ctl_base {
    public stdClass $header_frontend;
    public inm_ubicacion_html $html_entidad;
    public string $link_rel_ubi_comp_alta_bd = '';
    public string $link_alta_bitacora = '';
    public string $link_opinion_valor_alta_bd = '';
    public string $link_costo_alta_bd = '';
    public string $link_validacion_bd = '';
    public string $link_solicitud_de_recurso_bd = '';
    public string $link_por_firmar_bd = '';
    public string $link_firmado_por_aprobar_bd = '';
    public string $link_firmado_bd = '';
    public string $link_inm_doc_ubicacion_alta_bd = '';

    /*  */
    public string $button_inm_doc_ubicacion_descarga = '';
    public string $button_inm_doc_ubicacion_descarga_zip = '';
    public string $button_inm_doc_ubicacion_vista_previa = '';
    public string $button_inm_doc_ubicacion_elimina_bd = '';

    /**/
    public string $button_inm_doc_ubicacion_descarga_firmado_por_aprobar = '';
    public string $button_inm_doc_ubicacion_descarga_zip_firmado_por_aprobar = '';
    public string $button_inm_doc_ubicacion_vista_previa_firmado_por_aprobar = '';
    public string $button_inm_doc_ubicacion_elimina_bd_firmado_por_aprobar = '';

    /**/
    public string $button_inm_doc_ubicacion_descarga_firmado = '';
    public string $button_inm_doc_ubicacion_descarga_zip_firmado = '';
    public string $button_inm_doc_ubicacion_vista_previa_firmado = '';
    public string $button_inm_doc_ubicacion_elimina_bd_firmado = '';


    public string $link_fotografia_bd = '';
    public array $imp_compradores = array();
    public array $fotos = array();
    public array $etapas = array();
    public array $inm_opiniones_valor = array();
    public int $n_opiniones_valor = 0;
    public float $monto_opinion_promedio = 0.0;

    public array $inm_costos = array();
    public array $status_ubicacion = array();
    public array $acciones_headers = array();
    public array $inm_conf_docs_ubicacion = array();

    public string $costo = '0.0';
    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_ubicacion(link: $link);
        $html_ = new inm_ubicacion_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);
        $this->html_entidad = $html_;

        $this->header_frontend = new stdClass();
        $this->lista_get_data = true;
    }

    /**
     * Genera un formulario de alta de una ubicacion
     * @param bool $header If header muestra result en web
     * @param bool $ws If ws muestra result json
     * @return array|string
     */
    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = (new _ubicacion())->init_alta(controler: $this,disableds: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener keys_selects', data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'cuenta_predial', keys_selects:$keys_selects,
            place_holder: 'Cuenta Predial', required: false);
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


    /**
     * Integra una vista para la asignacion de un comprador a una vivienda
     * @param bool $header Retorna datos via WEB
     * @param bool $ws Retorna datos vis JSON
     * @return array|stdClass
     */
    final public function asigna_comprador(bool $header, bool $ws = false): array|stdClass
    {
        $this->inputs = new stdClass();
        $disableds = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id');
        $base_data = (new _ubicacion())->base_view_accion_data(controler: $this, disableds: $disableds,
            funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener base_data',data:  $base_data,
                header: $header,ws:  $ws);
        }

        $data_compra = (new inm_ubicacion_html(html: $this->html_base))->data_comprador(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener data_compra',data:  $data_compra,
                header: $header,ws:  $ws);
        }

        return $base_data->base_html->r_modifica;
    }

    /**
     * Vista para asignacion de costo
     * @param bool $header Retorna datos via WEB
     * @param bool $ws Retorna datos vis JSON
     * @return array|stdClass
     */
    public function asigna_costo(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->detalle_costo(header: false,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar modificacion',data:  $r_modifica,
                header: $header,ws:  $ws);
        }


        $inputs = (new _ubicacion())->inputs_costo(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $link_costo_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,seccion: 'inm_costo');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener link_costo_alta_bd', data:  $link_costo_alta_bd,
                header: $header,ws:  $ws);
        }
        $this->link_costo_alta_bd = $link_costo_alta_bd;


        return $r_modifica;
    }

    public function asigna_validacion(bool $header, bool $ws = false): array|stdClass
    {

        $documento_rppc = $this->html->input_file(cols: 12, name: 'rppc', row_upd: new stdClass(), value_vacio: false,
            place_holder: 'RPPC');
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $documento_rppc, header: $header, ws: $ws);
        }

        $this->inputs->documento_rppc = $documento_rppc;

        $data_row = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $data_row,header: $header,ws: $ws);
        }

        $keys_selects = (new _ubicacion())->keys_selects_base(controler: $this,data_row:  $data_row, disableds: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener keys_selects', data:  $keys_selects, header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $link_validacion_bd = $this->obj_link->link_con_id(accion:'validacion_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_validacion_bd,
                header: $header,ws:  $ws);
        }

        $this->link_validacion_bd = $link_validacion_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        $filtro_inm_doc['inm_ubicacion.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 34;
        $r_inm_doc_ubicacion = (new inm_doc_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_ubicacion,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_ubicacion->n_registros > 0) {
            $button_inm_doc_ubicacion_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_descarga = $button_inm_doc_ubicacion_descarga;

            $button_inm_doc_ubicacion_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_vista_previa = $button_inm_doc_ubicacion_vista_previa;

            $button_inm_doc_ubicacion_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_descarga_zip = $button_inm_doc_ubicacion_descarga_zip;

            $params = array('accion_retorno'=>'proceso_ubicacion','seccion_retorno'=>'inm_ubicacion',
                'id_retorno'=>$this->registro_id);
            $button_inm_doc_ubicacion_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_ubicacion_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_elimina_bd = $button_inm_doc_ubicacion_elimina_bd;
        }

        return $base;
    }

    public function asigna_firmado_por_aprobar(bool $header, bool $ws = false): array|stdClass
    {
        $documento_poder = $this->html->input_file(cols: 12, name: 'poder', row_upd: new stdClass(), value_vacio: false,
            place_holder: 'Poder',required: false);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $documento_poder, header: $header, ws: $ws);
        }

        $this->inputs->documento_poder = $documento_poder;

        $filtro_poder['inm_ubicacion.id'] = $this->registro_id;
        $r_inm_poder = (new inm_poder(link: $this->link))->filtro_and(filtro: $filtro_poder);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $r_inm_poder,
                header: $header, ws: $ws);
        }

        $this->row_upd->inm_notaria_id = -1;
        $this->row_upd->numero_escritura_poder = '';
        $this->row_upd->fecha_poder = '';
        if($r_inm_poder->n_registros > 0){
            $this->row_upd->inm_notaria_id = $r_inm_poder->registros[0]['inm_poder_inm_notaria_id'];
            $this->row_upd->numero_escritura_poder = $r_inm_poder->registros[0]['inm_poder_numero_escritura_poder'];
            $this->row_upd->fecha_poder = $r_inm_poder->registros[0]['inm_poder_fecha_poder'];
        }

        $data_row = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $data_row,header: $header,ws: $ws);
        }

        $keys_selects = (new _ubicacion())->keys_selects_base(controler: $this,data_row:  $data_row, disableds: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener keys_selects', data:  $keys_selects, header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_notaria_id','inm_notaria_descripcion');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_notaria_id',
            keys_selects:$keys_selects, id_selected: $this->row_upd->inm_notaria_id, label: 'Notaria',
            columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'numero_escritura_poder', keys_selects:$keys_selects,
            place_holder: 'No. Escritura Poder');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false,
            name: 'fecha_poder', place_holder: 'Fecha Poder',value: $this->row_upd->fecha_poder);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar fecha',
                data:  $fecha, header: $header,ws: $ws);
        }

        $this->inputs->fecha_poder = $fecha;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $link_firmado_por_aprobar_bd = $this->obj_link->link_con_id(accion:'firmado_por_aprobar_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_firmado_por_aprobar_bd,
                header: $header,ws:  $ws);
        }

        $this->link_firmado_por_aprobar_bd = $link_firmado_por_aprobar_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        $filtro_inm_doc['inm_ubicacion.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 35;
        $r_inm_doc_ubicacion = (new inm_doc_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_ubicacion,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_ubicacion->n_registros > 0) {
            $button_inm_doc_ubicacion_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_descarga_firmado_por_aprobar = $button_inm_doc_ubicacion_descarga;

            $button_inm_doc_ubicacion_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_vista_previa_firmado_por_aprobar = $button_inm_doc_ubicacion_vista_previa;

            $button_inm_doc_ubicacion_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_descarga_zip_firmado_por_aprobar = $button_inm_doc_ubicacion_descarga_zip;

            $params = array('accion_retorno'=>'proceso_ubicacion','seccion_retorno'=>'inm_ubicacion',
                'id_retorno'=>$this->registro_id);
            $button_inm_doc_ubicacion_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_ubicacion_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_elimina_bd_firmado_por_aprobar = $button_inm_doc_ubicacion_elimina_bd;
        }

        return $base;
    }

    public function asigna_firmado(bool $header, bool $ws = false): array|stdClass
    {
        $documento_poliza_firmada = $this->html->input_file(cols: 12, name: 'poliza_firmada', row_upd: new stdClass(),
            value_vacio: false, place_holder: 'Poliza Firmada');
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $documento_poliza_firmada, header: $header, ws: $ws);
        }

        $this->inputs->documento_poliza_firmada = $documento_poliza_firmada;

        $data_row = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $data_row,header: $header,ws: $ws);
        }


        $keys_selects = (new _ubicacion())->keys_selects_base(controler: $this,data_row:  $data_row, disableds: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener keys_selects', data:  $keys_selects, header: $header,ws:  $ws);
        }


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $link_firmado_bd = $this->obj_link->link_con_id(accion:'firmado_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_firmado_bd,
                header: $header,ws:  $ws);
        }

        $this->link_firmado_bd = $link_firmado_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        $filtro_inm_doc['inm_ubicacion.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 36;
        $r_inm_doc_ubicacion = (new inm_doc_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_ubicacion,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_ubicacion->n_registros > 0) {
            $button_inm_doc_ubicacion_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_descarga_firmado = $button_inm_doc_ubicacion_descarga;

            $button_inm_doc_ubicacion_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_vista_previa_firmado = $button_inm_doc_ubicacion_vista_previa;

            $button_inm_doc_ubicacion_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_ubicacion_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_descarga_zip_firmado = $button_inm_doc_ubicacion_descarga_zip;

            $params = array('accion_retorno'=>'proceso_ubicacion','seccion_retorno'=>'inm_ubicacion',
                'id_retorno'=>$this->registro_id);
            $button_inm_doc_ubicacion_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_ubicacion->registros[0]['inm_doc_ubicacion_id'],
                seccion: 'inm_doc_ubicacion', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_ubicacion_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_ubicacion_elimina_bd_firmado = $button_inm_doc_ubicacion_elimina_bd;
        }

        return $base;
    }

    public function asigna_solicitud_de_recurso(bool $header, bool $ws = false): array|stdClass
    {

        $data_row = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $data_row,header: $header,ws: $ws);
        }

        $filtro_che['inm_ubicacion.id'] = $this->registro_id;
        $r_cheque = (new inm_cheque(link: $this->link))->filtro_and(filtro: $filtro_che);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $r_cheque,
                header: $header, ws: $ws);
        }

        if($r_cheque->n_registros > 0) {
            $this->row_upd->nombre_beneficiario = $r_cheque->registros[0]['inm_cheque_nombre_beneficiario'];
            $this->row_upd->numero_cheque = $r_cheque->registros[0]['inm_cheque_numero_cheque'];
            $this->row_upd->monto = $r_cheque->registros[0]['inm_cheque_monto'];
        }

        $keys_selects = (new _ubicacion())->keys_selects_base(controler: $this,data_row:  $data_row, disableds: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener keys_selects', data:  $keys_selects, header: $header,ws:  $ws);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'nombre_beneficiario', keys_selects:$keys_selects,
            place_holder: 'Nombre Beneficiario');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'numero_cheque', keys_selects:$keys_selects,
            place_holder: 'No. Cheque');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'monto', keys_selects:$keys_selects,
            place_holder: 'Monto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $link_solicitud_de_recurso_bd = $this->obj_link->link_con_id(accion:'solicitud_de_recurso_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_solicitud_de_recurso_bd,
                header: $header,ws:  $ws);
        }

        $this->link_solicitud_de_recurso_bd = $link_solicitud_de_recurso_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    public function asigna_por_firmar(bool $header, bool $ws = false): array|stdClass
    {

        $data_row = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $data_row,header: $header,ws: $ws);
        }

        $keys_selects = (new _ubicacion())->keys_selects_base(controler: $this,data_row:  $data_row, disableds: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener keys_selects', data:  $keys_selects, header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $link_por_firmar_bd = $this->obj_link->link_con_id(accion:'por_firmar_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_por_firmar_bd,
                header: $header,ws:  $ws);
        }

        $this->link_por_firmar_bd = $link_por_firmar_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    public function cancelado(bool $header, bool $ws = false): array|stdClass
    {
        $template = parent::modifica(header: false); // TODO: Change the autogenerated stub
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar template', data: $template, header: $header, ws: $ws);
        }

        $filtro_status['inm_status_ubicacion.es_cancelado'] = 'activo';
        $r_inm_status_ubicacion = (new inm_status_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_status);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $r_inm_status_ubicacion,
                header: $header, ws: $ws);
        }

        $id_selected = -1;
        if ($r_inm_status_ubicacion->n_registros > 0) {
            $id_selected = $r_inm_status_ubicacion->registros[0]['inm_status_ubicacion_id'];
        }
        $columns_ds[] = 'inm_status_ubicacion_descripcion';

        $inm_status_ubicacion_id = (new inm_status_ubicacion_html(html: $this->html_base))->select_inm_status_ubicacion_id(
            cols: 6, con_registros: true, id_selected: $id_selected, link: $this->link, columns_ds: $columns_ds,
            filtro: $filtro_status, label: 'Status Ubicacion');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $inm_status_ubicacion_id,
                header: $header, ws: $ws);
        }
        $this->inputs->inm_status_ubicacion_id = $inm_status_ubicacion_id;

        $filtro_status['inm_ubicacion.id'] = $this->registro_id;
        $r_inm_bitacora = (new inm_bitacora_status_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_status);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $r_inm_bitacora,
                header: $header, ws: $ws);
        }

        $hoy = date('Y-m-d\TH:i:s');
        $observaciones = "";
        if($r_inm_bitacora->n_registros){
            $hoy = $r_inm_bitacora->registros[0]['inm_bitacora_status_ubicacion_fecha_status'];
            $observaciones = $r_inm_bitacora->registros[0]['inm_bitacora_status_ubicacion_observaciones'];
        }
        $fecha = $this->html->input_fecha(cols: 6, row_upd: new stdClass(), value_vacio: false, name: 'fecha_status',
            value: $hoy, value_hora: true);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar input fecha', data: $fecha, header: $header, ws: $ws);
        }

        $this->inputs->fecha = $fecha;

        $input_observaciones = $this->html->input_text(cols: 12, disabled: false, name: 'observaciones', place_holder: 'Observaciones',
            row_upd: new stdClass(), value_vacio: false, required: false,value: $observaciones);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $input_observaciones,  header: $header, ws: $ws);
        }

        $this->inputs->observaciones = $input_observaciones;

        $inm_ubicacion_id = $this->html->hidden(name:'inm_ubicacion_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $inm_ubicacion_id,  header: $header, ws: $ws);
        }

        $this->inputs->inm_ubicacion_id = $inm_ubicacion_id;


        $link_alta_bitacora= $this->obj_link->link_alta_bd(link: $this->link, seccion:  'inm_bitacora_status_ubicacion');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar link', data: $link_alta_bitacora, header: $header, ws: $ws);
        }

        $this->link_alta_bitacora = $link_alta_bitacora;

        return $template;
    }


    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion', 'manzana', 'lote','costo_directo','numero_exterior','numero_interior',
            'calle', 'cuenta_predial','codigo','nombre_beneficiario','numero_cheque','monto','numero_escritura_poder',
            'nombre','apellido_paterno','apellido_materno','nss','curp','rfc', 'lada_com', 'numero_com', 'cel_com',
            'correo_com', 'razon_social','nivel','recamaras','metros_terreno','metros_construccion','adeudo_hipoteca',
            'adeudo_predial','cuenta_agua','adeudo_agua','adeudo_luz','monto_devolucion');
        $keys->selects = array();


        $init_data = array();
        $init_data['dp_pais'] = "gamboamartin\\direccion_postal";
        $init_data['dp_estado'] = "gamboamartin\\direccion_postal";
        $init_data['dp_municipio'] = "gamboamartin\\direccion_postal";
        $init_data['dp_cp'] = "gamboamartin\\direccion_postal";
        $init_data['dp_colonia_postal'] = "gamboamartin\\direccion_postal";
        $init_data['dp_calle_pertenece'] = "gamboamartin\\direccion_postal";

        $init_data['inm_tipo_ubicacion'] = "gamboamartin\\inmuebles";
        $init_data['inm_notaria'] = "gamboamartin\\inmuebles";
        $init_data['com_agente'] = "gamboamartin\\comercial";
        $init_data['inm_estado_vivienda'] = "gamboamartin\\inmuebles";
        $init_data['inm_prototipo'] = "gamboamartin\\inmuebles";
        $init_data['inm_complemento'] = "gamboamartin\\inmuebles";

        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    /**
     * Obtiene una vista de detalle de costo por ubicacion
     * @param bool $header retorna datos en html
     * @param bool $ws Retorna datos en ws
     * @param string $funcion Funcion para retorno de links
     * @return array|stdClass
     * @version 2.181.0
     *
     */
    public function detalle_costo(bool $header, bool $ws = false, string $funcion='detalle_costo'): array|stdClass
    {

        $params_get = (new inm_ubicacion_html(html: $this->html_base))->params_get_data(accion_retorno: $funcion,
            id_retorno: $this->registro_id,seccion_retorno: $this->tabla);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar params_get',data:  $params_get,
                header: $header,ws:  $ws);
        }

        $base = (new inm_ubicacion_html(html: $this->html_base))->base_costos(controler: $this,funcion: $funcion,
            params_get: $params_get);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }


        return $base->base->r_modifica;
    }

    final public function documentos(bool $header, bool $ws = false): array
    {
        $template = $this->modifica(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
        }

        $inm_conf_docs_ubicacion = (new _inm_ubicacion())->integra_inm_documentos(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar buttons', data: $inm_conf_docs_ubicacion, header: $header, ws: $ws);
        }

        //$keys_selects['com_tipo_ubicacion_id']->id_selected = $this->registro['com_tipo_ubicacion_id'];

        /*$base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $this->row_upd->asunto = "TU MENSAJE";
        $this->row_upd->mensaje = "TU MENSAJE";
        $this->inm_conf_docs_ubicacion = $inm_conf_docs_ubicacion;
*/
        //print_r($this->row_upd);

        return $inm_conf_docs_ubicacion;
    }

    public function etapa(bool $header, bool $ws = false): array|stdClass
    {
        /*$template = parent::modifica(header: false); // TODO: Change the autogenerated stub
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar template', data: $template, header: $header, ws: $ws);
        }*/

        $columns_ds[] = 'inm_status_ubicacion_descripcion';

        $inm_status_ubicacion_id = (new inm_status_ubicacion_html(html: $this->html_base))->select_inm_status_ubicacion_id(
            cols: 6, con_registros: true, id_selected: -1, link: $this->link, columns_ds: $columns_ds,
            label: 'Status Ubicacion',filtro: array('inm_status_ubicacion.es_cancelado'=>'inactivo'));
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $inm_status_ubicacion_id, header: $header, ws: $ws);
        }
        $this->inputs->inm_status_ubicacion_id = $inm_status_ubicacion_id;

        $hoy = date('Y-m-d\TH:i:s');
        $fecha = $this->html->input_fecha(cols: 6, row_upd: new stdClass(), value_vacio: false, name: 'fecha_status',
            value: $hoy, value_hora: true);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar input fecha', data: $fecha, header: $header, ws: $ws);
        }

        $this->inputs->fecha = $fecha;

        $observaciones = $this->html->input_text(cols: 12, disabled: false, name: 'observaciones', place_holder: 'Observaciones',
            row_upd: new stdClass(), value_vacio: false, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $observaciones,  header: $header, ws: $ws);
        }

        $this->inputs->observaciones = $observaciones;

        $inm_ubicacion_id = $this->html->hidden(name:'inm_ubicacion_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $inm_ubicacion_id,  header: $header, ws: $ws);
        }

        $this->inputs->inm_ubicacion_id = $inm_ubicacion_id;


        $link_alta_bitacora= $this->obj_link->link_alta_bd(link: $this->link, seccion:  'inm_bitacora_status_ubicacion');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar link', data: $link_alta_bitacora, header: $header, ws: $ws);
        }

        $this->link_alta_bitacora = $link_alta_bitacora;

        $etapas = (new inm_ubicacion(link: $this->link))->status_ubicacion(inm_ubicacion_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener etapas', data: $etapas, header: $header, ws: $ws);
        }

        $this->etapas = $etapas;

        $retorno = 'etapa';
        if(isset($_GET['pestana_general_actual'])){
            $retorno = 'proceso_ubicacion';
        }

        $btn_action_next = $this->html->hidden('btn_action_next', value: $retorno);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $id_retorno = $this->html->hidden('id_retorno', value: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $seccion_retorno = $this->html->hidden('seccion_retorno', value: $this->seccion);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $this->inputs->btn_action_next = $btn_action_next;
        $this->inputs->id_retorno = $id_retorno;
        $this->inputs->seccion_retorno = $seccion_retorno;

        return $this->inputs;
    }

    public function firmado_por_aprobar_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $filtro_exi['inm_ubicacion.id'] = $this->registro_id;
        $filtro_exi['inm_status_ubicacion.id'] = 5;
        $existe = (new inm_bitacora_status_ubicacion(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['inm_status_ubicacion_id'] = 5;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_ubicacion = (new inm_bitacora_status_ubicacion(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_ubicacion,
                    header: $header, ws: $ws);
            }
        }

        $filtro_doc['inm_ubicacion.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 35;
        $r_inm_doc_ubicacion_reg = (new inm_doc_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $r_inm_doc_ubicacion_reg,
                header: $header, ws: $ws);
        }

        if($r_inm_doc_ubicacion_reg->n_registros <= 0) {
            $_FILES['documento'] = $_FILES['poder'];
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['doc_tipo_documento_id'] = 35;
            $r_inm_doc_ubicacion = (new inm_doc_ubicacion(link: $this->link))->alta_registro(registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_ubicacion,
                    header: $header, ws: $ws);
            }
        }

        $filtro_poder['inm_ubicacion.id'] = $this->registro_id;
        $r_inm_poder = (new inm_poder(link: $this->link))->filtro_and(filtro: $filtro_poder);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $r_inm_poder,
                header: $header, ws: $ws);
        }

        if($r_inm_poder->n_registros <= 0) {
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['numero_escritura_poder'] = $_POST['numero_escritura_poder'];
            $registro['fecha_poder'] = $_POST['fecha_poder'];
            $registro['inm_notaria_id'] = $_POST['inm_notaria_id'];
            $result_inm_poder = (new inm_poder(link: $this->link))->alta_registro(registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $result_inm_poder,
                    header: $header, ws: $ws);
            }
        }else{
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['numero_escritura_poder'] = $_POST['numero_escritura_poder'];
            $registro['fecha_poder'] = $_POST['fecha_poder'];
            $registro['inm_notaria_id'] = $_POST['inm_notaria_id'];
            $result_inm_poder = (new inm_poder(link: $this->link))->modifica_bd(registro: $registro,
                id: $r_inm_poder->registros[0]['inm_poder_id']);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $result_inm_poder,
                    header: $header, ws: $ws);
            }
        }

        $this->link->commit();

        $link_proceso_ubicacion = $this->obj_link->link_con_id(
            accion: 'proceso_ubicacion', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_ubicacion');
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_ubicacion, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_ubicacion);
            exit;
        }

        return $this->registro_id;
    }

    public function firmado_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $filtro_exi['inm_ubicacion.id'] = $this->registro_id;
        $filtro_exi['inm_status_ubicacion.id'] = 6;
        $existe = (new inm_bitacora_status_ubicacion(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['inm_status_ubicacion_id'] = 6;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_ubicacion = (new inm_bitacora_status_ubicacion(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_ubicacion,
                    header: $header, ws: $ws);
            }
        }

        $filtro_doc['inm_ubicacion.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 36;
        $existe = (new inm_doc_ubicacion(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $_FILES['documento'] = $_FILES['poliza_firmada'];
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['doc_tipo_documento_id'] = 36;
            $r_inm_doc_ubicacion = (new inm_doc_ubicacion(link: $this->link))->alta_registro(registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_ubicacion,
                    header: $header, ws: $ws);
            }
        }

        $this->link->commit();

        $link_proceso_ubicacion = $this->obj_link->link_con_id(
            accion: 'proceso_ubicacion', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_ubicacion');
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_ubicacion, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_ubicacion);
            exit;
        }

        return $this->registro_id;
    }

    final public function fotografias(bool $header, bool $ws = false): array|stdClass
    {
        $filtro['inm_conf_docs_ubicacion.es_foto'] = 'activo';
        $inm_conf_docs_ubicacion = (new inm_conf_docs_ubicacion(link: $this->link))->filtro_and(
            columnas: ['doc_tipo_documento_id','doc_tipo_documento_descripcion'], filtro: $filtro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inm_conf_docs_prospecto',
                data:  $inm_conf_docs_ubicacion,header: $header, ws: $ws);
        }

        $inputs_fotos = array();
        foreach ($inm_conf_docs_ubicacion->registros as $registro){
            $filtro_foto['inm_doc_ubicacion.es_foto'] = 'activo';
            $filtro_foto['doc_tipo_documento.id'] = $registro['doc_tipo_documento_id'];
            $filtro_foto['inm_ubicacion.id'] = $this->registro_id;
            $inm_doc_ubicacion = (new inm_doc_ubicacion(link: $this->link))->filtro_and(
                filtro: $filtro_foto);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al obtener inm_conf_docs_prospecto',
                    data:  $inm_doc_ubicacion,header: $header, ws: $ws);
            }

            $fotos = array();
            foreach ($inm_doc_ubicacion->registros as $reg){
                $src = $reg['doc_documento_ruta_relativa'];
                if((new generales())->guarda_archivo_dropbox) {
                    $guarda = (new _dropbox(link: $this->link))->preview(
                        dropbox_id: $reg['inm_dropbox_ruta_id_dropbox'], extencion: $reg['doc_extension_descripcion']);
                    if (errores::$error) {
                        return $this->retorno_error('Error al guardar archivo', $guarda, header: $header,
                            ws: $ws);
                    }

                    //print_r($guarda);Exit;
                    $src = $guarda->ruta_mostrar;
                    //$ruta_doc = $this->path_base.$guarda->ruta_archivo;
                }
                $foto = $this->img_btn_modal(src: $src,
                    css_id: $registro['doc_tipo_documento_id'],class_css: ['imagen']);
                if(errores::$error){
                    return $this->retorno_error(mensaje: 'Error al obtener inm_conf_docs_prospecto',
                        data:  $foto,header: $header, ws: $ws);
                }

                $link_elimina_foto_bd = $this->obj_link->link_con_id(
                    accion: 'elimina_bd', link: $this->link, registro_id: $reg['inm_doc_ubicacion_id'],
                    seccion: 'inm_doc_ubicacion');
                if (errores::$error) {
                    $this->retorno_error(mensaje: 'Error al generar link', data: $link_elimina_foto_bd, header: $header, ws: $ws);
                }

                $contenedor = array();
                $contenedor['doc_documento_id'] = $reg['doc_documento_id'];
                $contenedor['input'] = $foto;
                $contenedor['inm_doc_ubicacion_id'] = $reg['inm_doc_ubicacion_id'];
                $fotos[$registro['doc_tipo_documento_id']][] = $contenedor;
            }

            $documento = $this->html->input_file(cols: 12, name: "fotos[$registro[doc_tipo_documento_id]][]",
                row_upd: new stdClass(), value_vacio: false, place_holder: $registro['doc_tipo_documento_descripcion'],
                required: false, multiple: true);
            if (errores::$error) {
                return $this->retorno_error(
                    mensaje: 'Error al obtener inputs', data: $documento, header: $header, ws: $ws);
            }

            $inputs_fotos[$registro['doc_tipo_documento_id']]['doc_tipo_documento_id'] = $registro['doc_tipo_documento_id'];
            $inputs_fotos[$registro['doc_tipo_documento_id']]['input'] = $documento;
            $inputs_fotos[$registro['doc_tipo_documento_id']]['fotos'] = $fotos;
        }

        $this->fotos = $inputs_fotos;

        $link_fotografia_bd = $this->obj_link->link_con_id(
            accion: 'fotografias_bd', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_ubicacion');
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_fotografia_bd, header: $header, ws: $ws);
        }

        $this->link_fotografia_bd = $link_fotografia_bd;

        $retorno = 'fotografias';
        if(isset($_GET['pestana_general_actual'])){
            $retorno = 'proceso_ubicacion';
        }

        $btn_action_next = $this->html->hidden('btn_action_next', value: $retorno);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $id_retorno = $this->html->hidden('id_retorno', value: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $seccion_retorno = $this->html->hidden('seccion_retorno', value: $this->seccion);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $this->inputs->btn_action_next = $btn_action_next;
        $this->inputs->id_retorno = $id_retorno;
        $this->inputs->seccion_retorno = $seccion_retorno;

        return $this->inputs;
    }

    public function fotografias_bd(bool $header, bool $ws = false): array|stdClass{
        $this->link->beginTransaction();

        $inm_doc_ubicacion =  new inm_doc_ubicacion(link: $this->link);

        $names = array();
        foreach ($_FILES['fotos']['name'] as $key => $foto){
            $names[$key]['name'] = $foto;
        }

        foreach ($_FILES['fotos']['tmp_name'] as $key => $foto){
            $names[$key]['tmp_name'] = $foto;
        }

        $result = array();
        foreach ($names as $key => $name){
            $valor = array();
            foreach ($name['name'] as $item => $value){

                $valor['name'] = $name['name'][$item];
                $valor['tmp_name'] = $name['tmp_name'][$item];

                if($name['name'][$item] !== '' && $name['tmp_name'][$item] !== '') {
                    $registro['doc_tipo_documento_id'] = $key;
                    $registro['inm_ubicacion_id'] = $this->registro_id;
                    $registro['es_foto'] = 'activo';
                    $_FILES['documento'] = $valor;
                    $result = $inm_doc_ubicacion->alta_registro(registro: $registro);
                    if (errores::$error) {
                        $this->link->rollBack();
                        return $this->retorno_error(mensaje: 'Error al insertar datos', data: $result, header: $header, ws: $ws);
                    }
                }
            }
        }

        $this->link->commit();

        return $result;
    }

    public function get_etapa_actual(bool $header, bool $ws = false){
        $pestanas = array("ALTA" => "pestana1", "VALIDACION" => "pestana2", "SOLICITUD DE RECURSO" => "pestana3",
            "POR FIRMAR" => "pestana4", "FIRMADO POR APROBAR" => "pestana5", "FIRMADO" => "pestana6",
            "CANCELADO"=> "sin_pestana");

        $r_ubicacion = (new inm_ubicacion(link: $this->link))->registro(registro_id: $_POST['id']);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al obtener registro de ubicacion', data: $r_ubicacion,
                header: $header, ws: $ws);
        }

        $pestana_actual = '';
        foreach ($pestanas as $key => $value) {
            if($key === $r_ubicacion['inm_status_ubicacion_descripcion']){
                $pestana_actual = $value;
            }
        }

        return $pestana_actual;
    }

    /**
     * Inicializa el objeto Datatables con las columnas y filtros necesarios para visualizar la ubicación de los inmuebles.
     * La funcion consigue los datos para un tabla que debe mostrar la información sobre el id, código,
     * tipo de ubicación, municipio, CP, colonia, calle, número exterior, número interior, manzana, lote, etapa,
     * cuenta predial, número de opiniones de valor, valor estimado y costo de una ubicación de inmueble.
     * @return stdClass Objeto con la configuración de Datatables.
     */
    private function init_datatable(): stdClass
    {
        // Definir los títulos de las columnas para el datatable
        $columns["inm_ubicacion_id"]["titulo"] = "Id";
        $columns["inm_tipo_ubicacion_descripcion"]["titulo"] = "Tipo de Ubicacion";
        $columns["inm_ubicacion_ubicacion"]["titulo"] = "Ubicacion";
        $columns["dp_cp_descripcion"]["titulo"] = "CP";
        $columns["inm_ubicacion_manzana"]["titulo"] = "Manzana";
        $columns["inm_ubicacion_lote"]["titulo"] = "Lote";
        $columns["inm_ubicacion_etapa"]["titulo"] = "Etapa";
        $columns["inm_ubicacion_cuenta_predial"]["titulo"] = "Predial";
        $columns["com_agente_descripcion"]["titulo"] = "Agente";
        $columns["inm_status_ubicacion_descripcion"]["titulo"] = "Status Ubicacion";

        // Definir los filtros para el datatable
        $filtro = array("inm_ubicacion.id","inm_ubicacion_ubicacion",'dp_cp.descripcion',
            'inm_ubicacion.manzana','inm_ubicacion.lote','inm_ubicacion.cuenta_predial',
            'inm_tipo_ubicacion.descripcion','com_agente.descripcion','inm_status_ubicacion.descripcion');

        // Crear el objeto Datatables y asignarle las columnas y filtros definidos
        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;
        $datatables->menu_active = true;

        return $datatables;
    }

    public function inputs_conyuge(controlador_inm_ubicacion $controler){

        $conyuge = new stdClass();

        $existe_conyuge = false;
        if($controler->registro_id > 0) {
            $existe_conyuge = $controler->modelo->existe_conyuge(
                inm_prospecto_id: $controler->registro_id);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar si existe conyuge', data: $existe_conyuge);
            }
        }



        $row_upd = new stdClass();
        $row_upd->nombre = '';
        $row_upd->apellido_paterno = '';
        $row_upd->apellido_materno = '';
        $row_upd->fecha_nacimiento = '';
        $row_upd->curp = '';
        $row_upd->rfc = '';
        $row_upd->telefono_casa = '';
        $row_upd->telefono_celular = '';
        $row_upd->dp_estado_id = -1;
        $row_upd->dp_municipio_id = -1;
        $row_upd->inm_nacionalidad_id = -1;
        $row_upd->inm_ocupacion_id = -1;
        if($existe_conyuge){
            $row_upd = $controler->modelo->inm_conyuge(columnas_en_bruto: true,
                inm_ubicacion_id: $controler->registro_id, link: $controler->link, retorno_obj: true);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener datos de conyuge',data:  $row_upd);
            }
            $dp_municipio_data = (new dp_municipio(link: $controler->link))->registro(
                registro_id: $row_upd->dp_municipio_id, columnas_en_bruto: true, retorno_obj: true);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener datos  dp_municipio_data',data:  $dp_municipio_data);
            }
            $row_upd->dp_estado_id = $dp_municipio_data->dp_estado_id;

        }

        $nombre = $controler->html->input_text(cols: 12, disabled: false, name: 'conyuge[nombre]', place_holder: 'Nombre',
            row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_nombre'), required: false, value: $row_upd->nombre);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $nombre);
        }

        $conyuge->nombre = $nombre;

        $apellido_paterno = $controler->html->input_text(cols: 6, disabled: false, name: 'conyuge[apellido_paterno]',
            place_holder: 'Apellido Pat', row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_apellido_paterno'),
            required: false, value: $row_upd->apellido_paterno);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $apellido_paterno);
        }

        $conyuge->apellido_paterno = $apellido_paterno;

        $apellido_materno = $controler->html->input_text(cols: 6, disabled: false, name: 'conyuge[apellido_materno]',
            place_holder: 'Apellido Mat', row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_apellido_materno'),
            required: false, value: $row_upd->apellido_materno);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $apellido_materno);
        }

        $conyuge->apellido_materno = $apellido_materno;

        $curp = $controler->html->input_text(cols: 6, disabled: false, name: 'conyuge[curp]', place_holder: 'CURP',
            row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_curp'), required: false, value: $row_upd->curp);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $curp);
        }

        $conyuge->curp = $curp;

        $rfc = $controler->html->input_text(cols: 6, disabled: false, name: 'conyuge[rfc]', place_holder: 'RFC',
            row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_rfc'), required: false, value: $row_upd->rfc);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $rfc);
        }

        $conyuge->rfc = $rfc;

        $telefono_casa = $controler->html->input_text(cols: 6, disabled: false, name: 'conyuge[telefono_casa]',
            place_holder: 'Tel Casa', row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_telefono_casa'), required: false, value: $row_upd->telefono_casa);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $telefono_casa);
        }

        $conyuge->telefono_casa = $telefono_casa;

        $telefono_celular = $controler->html->input_text(cols: 6, disabled: false, name: 'conyuge[telefono_celular]',
            place_holder: 'Cel', row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_telefono_celular'), required: false, value: $row_upd->telefono_celular);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $telefono_celular);
        }

        $conyuge->telefono_celular = $telefono_celular;

        $modelo = new dp_estado(link: $controler->link);
        $dp_estado_id = $controler->html->select_catalogo(cols: 6, con_registros: true,
            id_selected: $row_upd->dp_estado_id, modelo: $modelo, id_css: 'conyuge_dp_estado_id',
            label: 'Estado Nac', name: 'conyuge[dp_estado_id]');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $dp_estado_id);
        }

        $conyuge->dp_estado_id = $dp_estado_id;

        //print_r($dp_estado_id);exit;
        $modelo = new dp_municipio(link: $controler->link);
        $dp_municipio_id = $controler->html->select_catalogo(cols: 6, con_registros: true,
            id_selected: $row_upd->dp_municipio_id, modelo: $modelo, filtro: array('dp_estado.id'=>$row_upd->dp_estado_id),
            id_css: 'conyuge_dp_municipio_id', label: 'Municipio Nac', name: 'conyuge[dp_municipio_id]');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $dp_municipio_id);
        }

        $conyuge->dp_municipio_id = $dp_municipio_id;

        $modelo = new inm_nacionalidad(link: $controler->link);
        $inm_nacionalidad_id = $controler->html->select_catalogo(cols: 6, con_registros: true,
            id_selected: $row_upd->inm_nacionalidad_id, modelo: $modelo, label: 'Nacionalidad',
            name: 'conyuge[inm_nacionalidad_id]');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $inm_nacionalidad_id);
        }

        $conyuge->inm_nacionalidad_id = $inm_nacionalidad_id;

        $modelo = new inm_ocupacion(link: $controler->link);
        $inm_ocupacion_id = $controler->html->select_catalogo(cols: 12, con_registros: true,
            id_selected: $row_upd->inm_ocupacion_id, modelo: $modelo, label: 'Ocupacion',
            name: 'conyuge[inm_ocupacion_id]');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener input',data:  $inm_ocupacion_id);
        }

        $conyuge->inm_ocupacion_id = $inm_ocupacion_id;

        $fecha_nacimiento = $controler->html->input_fecha(cols: 6, row_upd: $row_upd,
            value_vacio: false, name: 'conyuge[fecha_nacimiento]', place_holder: 'Fecha Nac', required: false,
            value: $row_upd->fecha_nacimiento);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener fecha_nacimiento',data:  $fecha_nacimiento);
        }

        $conyuge->fecha_nacimiento = $fecha_nacimiento;

        return $conyuge;
    }

    public function img_btn_modal(string $src, int $css_id, array $class_css = array()): string|array
    {
        if($css_id<=0){
            return $this->errores->error('Error $css_id debe ser mayor a 0',$css_id);
        }

        $class_html = '';
        foreach ($class_css as $class){
            $class_html.=' '.$class;
        }

        $img = '<img class="img-thumbnail '.$class_html.'" src="'.$src.'" ';
        $img.= ' role="button" data-toggle="modal" data-target="#img_'.$css_id.'">';
        return $img;
    }


    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $headers = (new _ubicacion())->headers_front(controlador: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar headers', data: $headers, header: $header, ws: $ws);
        }

        $data_row = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $data_row,header: $header,ws: $ws);
        }


        $keys_selects = (new _ubicacion())->keys_selects_base(controler: $this,data_row:  $data_row, disableds: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener keys_selects', data:  $keys_selects, header: $header,ws:  $ws);
        }

        $conyuge = $this->inputs_conyuge(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener conyuge', data: $conyuge,
                header: $header, ws: $ws);
        }

        $this->inputs->conyuge = $conyuge;

        $fecha_otorgamiento_credito = $this->html->input_fecha(cols: 12, row_upd: $this->row_upd, value_vacio: false,
            name: 'fecha_otorgamiento_credito', place_holder: 'Fecha Otorgamiento Credito',
            required: false, value: $this->row_upd->fecha_otorgamiento_credito);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $fecha_otorgamiento_credito,header: $header,
                ws: $ws);
        }

        $this->inputs->fecha_otorgamiento_credito = $fecha_otorgamiento_credito;

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'cuenta_predial', keys_selects:$keys_selects,
            place_holder: 'Cuenta Predial', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $btn_collapse_all = $this->html->button_para_java(id_css: 'collapse_all',style:  'primary',
            tag:  'Ver/Ocultar Todo');
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al btn_collapse_all',data:  $btn_collapse_all, header: $header,ws:  $ws);
        }

        $this->buttons['btn_collapse_all'] = $btn_collapse_all;

        $retorno = 'modifica';
        if(isset($_GET['pestana_general_actual'])){
            $retorno = 'proceso_ubicacion';
        }

        $btn_action_next = $this->html->hidden('btn_action_next', value: $retorno);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $id_retorno = $this->html->hidden('id_retorno', value: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $seccion_retorno = $this->html->hidden('seccion_retorno', value: $this->seccion);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $this->inputs->btn_action_next = $btn_action_next;
        $this->inputs->id_retorno = $id_retorno;
        $this->inputs->seccion_retorno = $seccion_retorno;

        return $r_modifica;
    }

    public function modifica_bd(bool $header, bool $ws): array|stdClass
    {
            $this->link->beginTransaction();

            $result_conyuge = $this->modelo->transacciona_conyuge(inm_ubicacion_id: $this->registro_id,link: $this->link);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al modificar inm_prospecto',data:  $result_conyuge,
                    header: $header,ws:  $ws);
            }

            if(isset($_POST['conyuge'])){
                unset($_POST['conyuge']);
            }

            $r_modifica = parent::modifica_bd(header: false,ws:  $ws); // TODO: Change the autogenerated stub
            if(errores::$error){
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al modificar inm_prospecto',data:  $r_modifica,
                    header: $header,ws:  $ws);
            }
            $this->link->commit();

            $_SESSION[$r_modifica->salida][]['mensaje'] = $r_modifica->mensaje . ' del id ' . $this->registro_id;
            $this->header_out(result: $r_modifica, header: $header, ws: $ws);

            return $r_modifica;
    }

    public function lista(bool $header, bool $ws = false): array
    {
        $r_lista = parent::lista($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $r_lista, header: $header,ws:$ws);
        }

        $status_ubicacion = (new inm_status_ubicacion(link:$this->link))->registros();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener regitros de status', data:  $status_ubicacion,
                header: $header,ws:$ws);
        }

        $this->status_ubicacion = $status_ubicacion;

        return $r_lista;
    }

    protected function key_selects_txt(array $keys_selects): array
    {

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'descripcion', keys_selects:$keys_selects,
            place_holder: 'Descripcion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre',required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'apellido_paterno',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno',required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'apellido_materno',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'nss',
            keys_selects: $keys_selects, place_holder: 'NSS', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'curp',
            keys_selects: $keys_selects, place_holder: 'CURP', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'rfc',
            keys_selects: $keys_selects, place_holder: 'RFC', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'lada_com',
            keys_selects: $keys_selects, place_holder: 'Lada', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['lada_com']->regex = $this->validacion->patterns['lada_html'];

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'numero_com',
            keys_selects: $keys_selects, place_holder: 'Numero', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['numero_com']->regex = $this->validacion->patterns['tel_sin_lada_html'];

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'cel_com',
            keys_selects: $keys_selects, place_holder: 'Cel', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['cel_com']->regex = $this->validacion->patterns['telefono_mx_html'];

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'correo_com',
            keys_selects: $keys_selects, place_holder: 'Correo', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['correo_com']->regex = $this->validacion->patterns['correo_html5'];

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razon Social',required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'manzana', keys_selects:$keys_selects,
            place_holder: 'Manzana',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'lote', keys_selects:$keys_selects,
            place_holder: 'Lote', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'nivel',
            keys_selects: $keys_selects, place_holder: 'Nivel', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'recamaras',
            keys_selects: $keys_selects, place_holder: 'Recamaras', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'metros_terreno',
            keys_selects: $keys_selects, place_holder: 'Metros Terreno', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'metros_construccion',
            keys_selects: $keys_selects, place_holder: 'Metros Construccion', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'adeudo_hipoteca',
            keys_selects: $keys_selects, place_holder: 'Adeudo Hipoteca', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'costo_directo', keys_selects:$keys_selects,
            place_holder: 'Costo Directo', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects['value'] = 0.0;

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'adeudo_predial',
            keys_selects: $keys_selects, place_holder: 'Adeudo Predial', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'cuenta_agua',
            keys_selects: $keys_selects, place_holder: 'Cuenta Agua', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'adeudo_agua',
            keys_selects: $keys_selects, place_holder: 'Adeudo Agua', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'adeudo_luz',
            keys_selects: $keys_selects, place_holder: 'Adeudo Luz', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'monto_devolucion',
            keys_selects: $keys_selects, place_holder: 'Monto Devolucion', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'numero_exterior', keys_selects:$keys_selects,
            place_holder: 'Exterior',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'numero_interior', keys_selects: $keys_selects,
            place_holder: 'Interior',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'calle', keys_selects: $keys_selects,
            place_holder: 'Calle',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

    public function opinion_valor_alta(bool $header, bool $ws = false): array|stdClass
    {


        $base_data = (new _ubicacion())->base_view_accion_data(controler: $this, disableds: array(),
            funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener base_data',data:  $base_data, header: $header,ws:  $ws);
        }


        $inm_valuador_id = (new inm_valuador_html(html: $this->html_base))->select_inm_valuador_id(cols: 12,
            con_registros:  true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inm_valuador_id',data:  $inm_valuador_id, header: $header,ws:  $ws);
        }

        $this->inputs->inm_valuador_id = $inm_valuador_id;

        $monto_resultado = $this->html->input_monto(cols: 12,row_upd:  new stdClass(),value_vacio:  false,
            name: 'monto_resultado',place_holder: 'Monto Resultado');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener monto_resultado',data:  $monto_resultado, header: $header,ws:  $ws);
        }

        $this->inputs->monto_resultado = $monto_resultado;

        $fecha = $this->html->input_fecha(cols: 12,row_upd:  new stdClass(),value_vacio:  false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener fecha',data:  $fecha, header: $header,ws:  $ws);
        }

        $this->inputs->fecha = $fecha;

        $costo = $this->html->input_monto(cols: 12,row_upd:  new stdClass(),value_vacio:  false,name: 'costo',
            place_holder: 'Costo de opinion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener monto_resultado',data:  $monto_resultado, header: $header,ws:  $ws);
        }

        $this->inputs->costo = $costo;


        $link_opinion_valor_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,seccion: 'inm_opinion_valor');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener link_opinion_valor_lata_bd',
                data:  $link_opinion_valor_alta_bd, header: $header,ws:  $ws);
        }
        $this->link_opinion_valor_alta_bd = $link_opinion_valor_alta_bd;

        $inm_opiniones_valor = (new inm_ubicacion(link: $this->link))->opiniones_valor(inm_ubicacion_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inm_opiniones_valor', data:  $inm_opiniones_valor,
                header: $header,ws:  $ws);
        }
        $this->inm_opiniones_valor = $inm_opiniones_valor;

        $this->n_opiniones_valor = count($this->inm_opiniones_valor);

        $monto_opinion_promedio = (new inm_ubicacion(link: $this->link))->monto_opinion_promedio(inm_ubicacion_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener promedio', data:  $monto_opinion_promedio,
                header: $header,ws:  $ws);
        }

        $this->monto_opinion_promedio = $monto_opinion_promedio;


        return $base_data->base_html->r_modifica;
    }

    public function solicitud_de_recurso_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $filtro_che['inm_ubicacion.id'] = $this->registro_id;
        $r_cheque = (new inm_cheque(link: $this->link))->filtro_and(filtro: $filtro_che);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $r_cheque,
                header: $header, ws: $ws);
        }

        if($r_cheque->n_registros <= 0){
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['numero_cheque'] = $_POST['numero_cheque'];
            $registro['monto'] = $_POST['monto'];
            $registro['nombre_beneficiario'] = $_POST['nombre_beneficiario'];
            $r_inm_cheque = (new inm_cheque(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_cheque,
                    header: $header, ws: $ws);
            }
        }else{
            $registro = array();
            $registro['numero_cheque'] = $_POST['numero_cheque'];
            $registro['monto'] = $_POST['monto'];
            $registro['nombre_beneficiario'] = $_POST['nombre_beneficiario'];
            $r_inm_cheque = (new inm_cheque(link: $this->link))->modifica_bd(
                registro: $registro, id: $r_cheque->registros[0]['inm_cheque_id']);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_cheque,
                    header: $header, ws: $ws);
            }
        }

        $filtro_exi['inm_ubicacion.id'] = $this->registro_id;
        $filtro_exi['inm_status_ubicacion.id'] = 3;
        $existe = (new inm_bitacora_status_ubicacion(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['inm_status_ubicacion_id'] = 3;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_ubicacion = (new inm_bitacora_status_ubicacion(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_ubicacion,
                    header: $header, ws: $ws);
            }
        }

        $this->link->commit();

        $link_proceso_ubicacion = $this->obj_link->link_con_id(
            accion: 'proceso_ubicacion', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_ubicacion');
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_ubicacion, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_ubicacion);
            exit;
        }

        return $this->registro_id;
    }


    public function proceso_ubicacion(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $modifica = $this->modifica($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $modifica,header: $header,ws: $ws);
        }

        $documentos = $this->documentos($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $documentos,header: $header,ws: $ws);
        }

        $fotografias = $this->fotografias($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $fotografias,header: $header,ws: $ws);
        }

        $etapa = $this->etapa($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $etapa,header: $header,ws: $ws);
        }

        $asigna_validacion = $this->asigna_validacion($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_validacion,header: $header,ws: $ws);
        }

        $asigna_solicitud_recurso = $this->asigna_solicitud_de_recurso($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_solicitud_recurso,header: $header,ws: $ws);
        }

        $asigna_por_firmar = $this->asigna_por_firmar($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_por_firmar,header: $header,ws: $ws);
        }

        $asigna_firmado_por_aprobar = $this->asigna_firmado_por_aprobar($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_firmado_por_aprobar,header: $header,ws: $ws);
        }

        $asigna_firmado = $this->asigna_firmado($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_firmado,header: $header,ws: $ws);
        }

        $base = $this->base_upd(keys_selects: $this->keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        return $r_modifica;
    }

    public function por_firmar_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $filtro_exi['inm_ubicacion.id'] = $this->registro_id;
        $filtro_exi['inm_status_ubicacion.id'] = 4;
        $existe = (new inm_bitacora_status_ubicacion(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['inm_status_ubicacion_id'] = 4;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_ubicacion = (new inm_bitacora_status_ubicacion(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_ubicacion,
                    header: $header, ws: $ws);
            }
        }

        $this->link->commit();

        $link_proceso_ubicacion = $this->obj_link->link_con_id(
            accion: 'proceso_ubicacion', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_ubicacion');
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_ubicacion, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_ubicacion);
            exit;
        }

        return $this->registro_id;
    }

    final public function subir_documento(bool $header, bool $ws = false)
    {
        $inm_prospecto = (new inm_ubicacion(link: $this->link))->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_prospecto', data: $inm_prospecto,
                header: $header, ws: $ws);
        }

        $inm_conf_docs_prospecto = (new inm_conf_docs_ubicacion(link: $this->link))->filtro_and(
            columnas: ['doc_tipo_documento_id'],
            filtro: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_conf_docs_prospecto', data: $inm_conf_docs_prospecto,
                header: $header, ws: $ws);
        }

        $this->inputs = new stdClass();

        $filtro['inm_ubicacion.id'] = $this->registro_id;
        $inm_prospecto_id = (new inm_ubicacion_html(html: $this->html_base))->select_inm_ubicacion_id(
            cols: 12, con_registros: true, id_selected: $this->registro_id, link: $this->link, filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_prospecto_id, header: $header, ws: $ws);
        }
        $this->inputs->inm_ubicacion_id  = $inm_prospecto_id;

        $doc_ids = array_map(function ($registro) {
            return $registro['doc_tipo_documento_id'];
        }, $inm_conf_docs_prospecto->registros);

        $doc_tipos_documentos = array();

        if (count($doc_ids) > 0) {
            $in = array();
            if (count($doc_ids) > 0) {
                $in['llave'] = 'doc_tipo_documento.id';
                $in['values'] = $doc_ids;
            }

            $r_doc_tipo_documento = (new doc_tipo_documento(link: $this->link))->filtro_and(in: $in);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al Obtener tipos de documento',data:  $r_doc_tipo_documento,
                    header: $header, ws: $ws);
            }
            $doc_tipos_documentos = $r_doc_tipo_documento->registros;
        }

        $_doc_tipo_documento_id = -1;
        $filtro = array();
        if (isset($_GET['doc_tipo_documento_id'])) {
            $_doc_tipo_documento_id = $_GET['doc_tipo_documento_id'];
            $filtro['doc_tipo_documento.id'] = $_GET['doc_tipo_documento_id'];
        }

        $doc_tipo_documento_id = (new doc_tipo_documento_html(html: $this->html_base))->select_doc_tipo_documento_id(
            cols: 12, con_registros: true, id_selected: $_doc_tipo_documento_id, link: $this->link, filtro: $filtro,
            registros: $doc_tipos_documentos);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_prospecto_id, header: $header, ws: $ws);
        }
        $this->inputs->doc_tipo_documento_id = $doc_tipo_documento_id;

        $documento = $this->html->input_file(cols: 12, name: 'documento', row_upd: new stdClass(), value_vacio: false);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $documento, header: $header, ws: $ws);
        }

        $this->inputs->documento = $documento;

        $link_alta_doc = $this->obj_link->link_alta_bd(link: $this->link, seccion: 'inm_doc_ubicacion');
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar link', data: $link_alta_doc, header: $header, ws: $ws);
        }

        $this->link_inm_doc_ubicacion_alta_bd = $link_alta_doc;

        $retorno = 'documentos';
        if(isset($_GET['pestana_general_actual'])){
            $retorno = 'proceso_ubicacion';
        }

        $btn_action_next = $this->html->hidden('btn_action_next', value: $retorno);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $id_retorno = $this->html->hidden('id_retorno', value: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $seccion_retorno = $this->html->hidden('seccion_retorno', value: $this->seccion);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next', data: $btn_action_next, header: $header, ws: $ws);
        }

        $this->inputs->btn_action_next = $btn_action_next;
        $this->inputs->id_retorno = $id_retorno;
        $this->inputs->seccion_retorno = $seccion_retorno;
    }

    public function tipos_documentos(bool $header, bool $ws = false): array
    {
        $inm_conf_docs_prospecto = (new _inm_ubicacion())->integra_inm_documentos_ubicacion(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar buttons', data: $inm_conf_docs_prospecto,
                header: $header, ws: $ws);
        }

        $salida['draw'] = count($inm_conf_docs_prospecto);
        $salida['recordsTotal'] = count($inm_conf_docs_prospecto);
        $salida['recordsFiltered'] = count($inm_conf_docs_prospecto);
        $salida['data'] = $inm_conf_docs_prospecto;

        header('Content-Type: application/json');
        echo json_encode($salida);
        exit;
    }

    public function validacion_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $filtro_exi['inm_ubicacion.id'] = $this->registro_id;
        $filtro_exi['inm_status_ubicacion.id'] = 2;
        $existe = (new inm_bitacora_status_ubicacion(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['inm_status_ubicacion_id'] = 2;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_ubicacion = (new inm_bitacora_status_ubicacion(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_ubicacion,
                    header: $header, ws: $ws);
            }
        }

        $filtro_doc['inm_ubicacion.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 34;
        $existe = (new inm_doc_ubicacion(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $_FILES['documento'] = $_FILES['rppc'];
            $registro = array();
            $registro['inm_ubicacion_id'] = $this->registro_id;
            $registro['doc_tipo_documento_id'] = 34;
            $r_inm_doc_ubicacion = (new inm_doc_ubicacion(link: $this->link))->alta_registro(registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_ubicacion,
                    header: $header, ws: $ws);
            }
        }

        $this->link->commit();

        $link_proceso_ubicacion = $this->obj_link->link_con_id(
            accion: 'proceso_ubicacion', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_ubicacion');
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_ubicacion, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_ubicacion);
            exit;
        }

        return $this->registro_id;
    }
}
