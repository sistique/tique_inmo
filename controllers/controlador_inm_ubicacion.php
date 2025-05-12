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
use gamboamartin\inmuebles\html\inm_ubicacion_html;
use gamboamartin\inmuebles\html\inm_valuador_html;
use gamboamartin\inmuebles\models\inm_status_ubicacion;
use gamboamartin\inmuebles\models\inm_ubicacion;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_ubicacion extends _ctl_base {
    public stdClass $header_frontend;
    public inm_ubicacion_html $html_entidad;
    public string $link_rel_ubi_comp_alta_bd = '';
    public string $link_opinion_valor_alta_bd = '';
    public string $link_costo_alta_bd = '';
    public string $link_asigna_validacion_bd = '';
    public array $imp_compradores = array();

    public array $inm_opiniones_valor = array();
    public int $n_opiniones_valor = 0;
    public float $monto_opinion_promedio = 0.0;

    public array $inm_costos = array();
    public array $status_ubicacion = array();
    public array $acciones_headers = array();

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
        $disableds = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id',
            'dp_calle_pertenece_id');
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

        $link_asigna_validacion_bd = $this->obj_link->link_con_id(accion:'asigna_validacion_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_asigna_validacion_bd,
                header: $header,ws:  $ws);
        }

        $this->link_asigna_validacion_bd = $link_asigna_validacion_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    public function asigna_firmado(bool $header, bool $ws = false): array|stdClass
    {
        $documento_poder = $this->html->input_file(cols: 12, name: 'poder', row_upd: new stdClass(), value_vacio: false,
            place_holder: 'Poder');
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $documento_poder, header: $header, ws: $ws);
        }

        $this->inputs->documento_poder = $documento_poder;

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
            keys_selects:$keys_selects, id_selected: -1, label: 'Notaria',
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
            name: 'fecha_poder', place_holder: 'Fecha Poder');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar fecha',
                data:  $fecha, header: $header,ws: $ws);
        }

        $this->inputs->fecha_poder = $fecha;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $link_asigna_validacion_bd = $this->obj_link->link_con_id(accion:'asigna_validacion_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_asigna_validacion_bd,
                header: $header,ws:  $ws);
        }

        $this->link_asigna_validacion_bd = $link_asigna_validacion_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    public function asigna_firmado_aprobado(bool $header, bool $ws = false): array|stdClass
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

        $link_asigna_validacion_bd = $this->obj_link->link_con_id(accion:'asigna_validacion_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_asigna_validacion_bd,
                header: $header,ws:  $ws);
        }

        $this->link_asigna_validacion_bd = $link_asigna_validacion_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);


        return $base;
    }

    public function asigna_solicitud_recurso(bool $header, bool $ws = false): array|stdClass
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

        $link_asigna_validacion_bd = $this->obj_link->link_con_id(accion:'asigna_validacion_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_ubicacion');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_asigna_validacion_bd,
                header: $header,ws:  $ws);
        }

        $this->link_asigna_validacion_bd = $link_asigna_validacion_bd;
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
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
            $rename = "gamboamartin\\inmuebles\\models\\".$class_upd;
            $class = new $rename();

            $row_upd = $class->inm_conyuge(columnas_en_bruto: true,
                inm_prospecto_id: $controler->registro_id, link: $controler->link, retorno_obj: true);

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


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

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
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'cuenta_predial', keys_selects:$keys_selects,
            place_holder: 'Cuenta Predial', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

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


    public function proceso_ubicacion(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $asigna_validacion = $this->asigna_validacion($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_validacion,header: $header,ws: $ws);
        }

        $asigna_solicitud_recurso = $this->asigna_solicitud_recurso($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_solicitud_recurso,header: $header,ws: $ws);
        }

        $asigna_firmado = $this->asigna_firmado($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_firmado,header: $header,ws: $ws);
        }

        $asigna_firmado_aprobado = $this->asigna_firmado_aprobado($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_firmado_aprobado,header: $header,ws: $ws);
        }

        $base = $this->base_upd(keys_selects: $this->keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        return $r_modifica;
    }


}
