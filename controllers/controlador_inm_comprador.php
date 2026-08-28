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
use gamboamartin\banco\models\bn_cuenta;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\models\cat_sat_tipo_de_comprobante;
use gamboamartin\cat_sat\models\cat_sat_tipo_relacion;
use gamboamartin\cat_sat\models\cat_sat_uso_cfdi;
use gamboamartin\comercial\models\com_producto;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\comercial\models\com_tipo_cambio;
use gamboamartin\direccion_postal\models\dp_estado;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_complemento_pago;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\facturacion\models\fc_docto_relacionado;
use gamboamartin\facturacion\models\fc_factura;
use gamboamartin\facturacion\models\fc_nc_rel;
use gamboamartin\facturacion\models\fc_nota_credito;
use gamboamartin\facturacion\models\fc_pago;
use gamboamartin\facturacion\models\fc_pago_pago;
use gamboamartin\facturacion\models\fc_partida;
use gamboamartin\facturacion\models\fc_partida_cp;
use gamboamartin\facturacion\models\fc_partida_nc;
use gamboamartin\facturacion\models\fc_relacion_nc;
use gamboamartin\inmuebles\html\_base;
use gamboamartin\inmuebles\html\inm_comprador_html;
use gamboamartin\inmuebles\html\inm_notaria_html;
use gamboamartin\inmuebles\html\inm_referencia_html;
use gamboamartin\inmuebles\html\inm_status_comprador_html;
use gamboamartin\inmuebles\models\_base_paquete;
use gamboamartin\inmuebles\models\_inm_comprador;
use gamboamartin\inmuebles\models\_inm_prospecto;
use gamboamartin\inmuebles\models\_upd_prospecto;
use gamboamartin\inmuebles\models\inm_avaluo;
use gamboamartin\inmuebles\models\inm_beneficiario;
use gamboamartin\inmuebles\models\inm_bitacora_status_comprador;
use gamboamartin\inmuebles\models\inm_comprador;
use gamboamartin\inmuebles\models\inm_conf_docs_comprador;
use gamboamartin\inmuebles\models\inm_doc_comprador;
use gamboamartin\inmuebles\models\inm_doc_ubicacion;
use gamboamartin\inmuebles\models\inm_escritura;
use gamboamartin\inmuebles\models\inm_firma;
use gamboamartin\inmuebles\models\inm_nacionalidad;
use gamboamartin\inmuebles\models\inm_ocupacion;
use gamboamartin\inmuebles\models\inm_referencia;
use gamboamartin\inmuebles\models\inm_rel_beneficiario_comprador;
use gamboamartin\inmuebles\models\inm_rel_cheque_comprador;
use gamboamartin\inmuebles\models\inm_rel_doc_cheque;
use gamboamartin\inmuebles\models\inm_rel_doc_transferencia;
use gamboamartin\inmuebles\models\inm_rel_efectivo_comprador;
use gamboamartin\inmuebles\models\inm_rel_referencia_comprador;
use gamboamartin\inmuebles\models\inm_rel_referencia_prospecto;
use gamboamartin\inmuebles\models\inm_rel_cliente_valuador;
use gamboamartin\inmuebles\models\inm_rel_comprador_com_cliente;
use gamboamartin\inmuebles\models\inm_rel_transferencia_comprador;
use gamboamartin\inmuebles\models\inm_rel_ubi_comp;
use gamboamartin\inmuebles\models\inm_status_comprador;
use gamboamartin\inmuebles\models\inm_status_prospecto;
use gamboamartin\inmuebles\models\inm_ubicacion;
use gamboamartin\plugins\exportador;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use gamboamartin\validacion\validacion;
use html\doc_tipo_documento_html;
use html\dp_estado_html;
use html\dp_municipio_html;
use NumberFormatter;
use PDO;
use setasign\Fpdi\Fpdi;
use stdClass;

class controlador_inm_comprador extends _ctl_base {

    use _registro_proceso;

    public array $comprobante_exento = array();
    public array $xml_exento = array();
    public array $inm_ubicaciones = array();
    public array $inm_clientes_valuadores = array();
    public array $inm_co_acreditados = array();
    public array $inm_conf_docs_comprador = array();
    public array $etapas = array();

    public array $cheques = array();
    public array $transferencias = array();
    public array $efectivos = array();

    public string $buttons_base = '';

    public string $link_documento_bd ='';
    public string $link_exportar_xls ='';
    public string $link_inm_doc_comprador_alta_bd = '';
    public string $link_alta_bitacora = '';

    public string $link_rel_ubi_comp_alta_bd = '';
    public string $link_inm_firma_alta_bd = '';
    public string $link_genera_factura_bd = '';

    /**/
    public string $link_ingresado_bd = '';
    public string $link_autorizado_bd = '';
    public string $link_por_firmar_bd = '';
    public string $link_escriturado_bd = '';
    public string $link_cotejado_bd = '';
    public string $link_cobrado_bd = '';
    public string $link_cancelado_bd = '';
    public string $link_nota_credito_bd = '';
    public string $link_complemento_pago_bd = '';

    /**/
    public string $link_inm_avaluo_alta_bd = '';
    public string $link_inm_escritura_alta_bd = '';
    public string $link_inm_rel_cliente_valuador_alta_bd = '';
    public string $link_inm_rel_co_acred_alta_bd = '';
    public string $link_asigna_nuevo_co_acreditado_bd = '';
    
    /* DOCUMENTO AVALUO */
    public string $descripcion_doc_comprador = '';
    public string $button_inm_doc_comprador_descarga = '';
    public string $button_inm_doc_comprador_descarga_zip = '';
    public string $button_inm_doc_comprador_vista_previa = '';
    public string $button_inm_doc_comprador_elimina_bd = '';

    public string $descripcion_doc_ubicacion_escritura = '';
    public string $button_inm_doc_ubicacion_escritura_descarga = '';
    public string $button_inm_doc_ubicacion_escritura_descarga_zip = '';
    public string $button_inm_doc_ubicacion_escritura_vista_previa = '';
    public string $button_inm_doc_ubicacion_escritura_elimina_bd = '';

    /* DOCUMENTO AUTORIZADO */
    public string $descripcion_sic = '';
    public string $button_inm_doc_comprador_descarga_sic = '';
    public string $button_inm_doc_comprador_descarga_zip_sic = '';
    public string $button_inm_doc_comprador_vista_previa_sic = '';
    public string $button_inm_doc_comprador_elimina_bd_sic = '';


    public string $descripcion_constancia_credito = '';
    public string $button_inm_doc_comprador_descarga_constancia_credito = '';
    public string $button_inm_doc_comprador_descarga_zip_constancia_credito = '';
    public string $button_inm_doc_comprador_vista_previa_constancia_credito = '';
    public string $button_inm_doc_comprador_elimina_bd_constancia_credito = '';

    /* DOCUMENTO POR FIRMAR */
    public string $descripcion_anexos = '';
    public string $button_inm_doc_comprador_descarga_anexos = '';
    public string $button_inm_doc_comprador_descarga_zip_anexos = '';
    public string $button_inm_doc_comprador_vista_previa_anexos = '';
    public string $button_inm_doc_comprador_elimina_bd_anexos = '';


    public string $descripcion_instruccion_credito = '';
    public string $button_inm_doc_comprador_descarga_instruccion_credito = '';
    public string $button_inm_doc_comprador_descarga_zip_instruccion_credito = '';
    public string $button_inm_doc_comprador_vista_previa_instruccion_credito = '';
    public string $button_inm_doc_comprador_elimina_bd_instruccion_credito = '';

    
    public string $descripcion_notificacion_descuento = '';
    public string $button_inm_doc_comprador_descarga_notificacion_descuento = '';
    public string $button_inm_doc_comprador_descarga_zip_notificacion_descuento = '';
    public string $button_inm_doc_comprador_vista_previa_notificacion_descuento = '';
    public string $button_inm_doc_comprador_elimina_bd_notificacion_descuento = '';

    public string $descripcion_notificacion_descuento_sec = '';
    public string $button_inm_doc_comprador_descarga_notificacion_descuento_sec = '';
    public string $button_inm_doc_comprador_descarga_zip_notificacion_descuento_sec = '';
    public string $button_inm_doc_comprador_vista_previa_notificacion_descuento_sec = '';
    public string $button_inm_doc_comprador_elimina_bd_notificacion_descuento_sec = '';

    
    public string $descripcion_isr_notaria = '';
    public string $button_inm_doc_comprador_descarga_isr_notaria = '';
    public string $button_inm_doc_comprador_descarga_zip_isr_notaria = '';
    public string $button_inm_doc_comprador_vista_previa_isr_notaria = '';
    public string $button_inm_doc_comprador_elimina_bd_isr_notaria = '';
    
    
    public string $descripcion_isr = '';
    public string $button_inm_doc_comprador_descarga_isr = '';
    public string $button_inm_doc_comprador_descarga_zip_isr = '';
    public string $button_inm_doc_comprador_vista_previa_isr = '';
    public string $button_inm_doc_comprador_elimina_bd_isr = '';



    /* DOCUMENTO ESCRITURADO */
    public string $descripcion_validacion_poder = '';
    public string $button_inm_doc_comprador_descarga_validacion_poder = '';
    public string $button_inm_doc_comprador_descarga_zip_validacion_poder = '';
    public string $button_inm_doc_comprador_vista_previa_validacion_poder = '';
    public string $button_inm_doc_comprador_elimina_bd_validacion_poder = '';


    public string $descripcion_acuse_patron = '';
    public string $button_inm_doc_comprador_descarga_acuse_patron = '';
    public string $button_inm_doc_comprador_descarga_zip_acuse_patron = '';
    public string $button_inm_doc_comprador_vista_previa_acuse_patron = '';
    public string $button_inm_doc_comprador_elimina_bd_acuse_patron = '';


    public string $descripcion_escritura = '';
    public string $button_inm_doc_comprador_descarga_escritura = '';
    public string $button_inm_doc_comprador_descarga_zip_escritura = '';
    public string $button_inm_doc_comprador_vista_previa_escritura = '';
    public string $button_inm_doc_comprador_elimina_bd_escritura = '';


    /* BOTON DE DESCARGA SOLICITUD INFONAVIT */
    public string $button_solicitud_infonavit = '';

    /* BOTON DE DESCARGA SOLICITUD AVALUO */
    public string $button_solicitud_avaluo = '';

    public inm_comprador_html $html_entidad;

    public stdClass $header_frontend;

    public bool $aplica_seccion_co_acreditado = false;

    public array $inm_referencias = array();
    public array $beneficiarios = array();
    public array $referencias = array();
    public array $status_comprador = array();
    public array $notas_credito = array();
    public array $complementos_pago = array();



    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_comprador(link: $link);
        $html_ = new inm_comprador_html(html: $html);
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

        $link_exportar_xls = $this->obj_link->link_con_id(accion: 'exportar_xls',link: $this->link,
            registro_id:  $this->registro_id,seccion:  $this->tabla);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar link', data: $link_exportar_xls);
            print_r($error);
            die('Error');
        }

        $this->link_exportar_xls = $link_exportar_xls;
    }

    /**
     * Integra formulario de alta
     * @param bool $header Si header retorna resultado en web
     * @param bool $ws Si ws muestra resultado en json
     * @return array|string
     */
    public function alta(bool $header, bool $ws = falseok ): array|string
    {
        // Restaura $this->row_upd desde sesión antes de init_alta() para que
        // tanto los inputs de texto como los selects muestren los valores previos
        // cuando el formulario se re-renderiza tras un error en alta_bd.
        $this->init_row_upd_desde_proceso();

        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $inputs = (new _base(html: $this->html_base))->data_front_alta(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        return $r_alta;
    }

    /**
     * Formulario para la integracion de un co acreditado
     * @param bool $header Si header retorna resultado en web
     * @param bool $ws Si ws muestra resultado en json
     * @return array|stdClass
     * @version 1.150.1
     */
    public function asigna_co_acreditado(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }


        $inputs = (new _keys_selects())->base_plantilla(controler: $this,function: __FUNCTION__);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,
                ws:  $ws);
        }

        $link_inm_rel_co_acred_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,seccion: 'inm_rel_co_acred');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_inm_rel_co_acred_alta_bd,
                header: $header,ws:  $ws);
        }

        $this->link_inm_rel_co_acred_alta_bd = $link_inm_rel_co_acred_alta_bd;

        $inm_co_acreditados = (new _inm_comprador())->inm_co_acreditados(inm_comprador_id: $this->registro_id,
            link:  $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener compradores',data:  $inm_co_acreditados,
                header: $header,ws:  $ws);
        }

        $this->inm_co_acreditados = $inm_co_acreditados;

        return $r_modifica;
    }

    public function asigna_nuevo_co_acreditado(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = (new _keys_selects())->base_co_acreditado(controler: $this,function: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }


        return $r_modifica;
    }

    final public function asigna_nuevo_co_acreditado_bd(bool $header, bool $ws = false): array|stdClass{

        $this->link->beginTransaction();

        $retorno = (new \gamboamartin\inmuebles\controllers\_base())->init_retorno();
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de retorno', data: $retorno,
                header: true, ws: false);
        }

        $inm_comprador_id = $this->registro_id;
        $inm_co_acreditado_ins['nss'] = $_POST['nss'];
        $inm_co_acreditado_ins['curp'] = $_POST['curp'];
        $inm_co_acreditado_ins['rfc'] = $_POST['rfc'];
        $inm_co_acreditado_ins['apellido_paterno'] = $_POST['apellido_paterno'];
        $inm_co_acreditado_ins['apellido_materno'] = $_POST['apellido_materno'];
        $inm_co_acreditado_ins['nombre'] = $_POST['nombre'];
        $inm_co_acreditado_ins['lada'] = $_POST['lada'];
        $inm_co_acreditado_ins['numero'] = $_POST['numero'];
        $inm_co_acreditado_ins['celular'] = $_POST['celular'];
        $inm_co_acreditado_ins['genero'] = $_POST['genero'];
        $inm_co_acreditado_ins['correo'] = $_POST['correo'];
        $inm_co_acreditado_ins['nombre_empresa_patron'] = $_POST['nombre_empresa_patron'];
        $inm_co_acreditado_ins['nrp'] = $_POST['nrp'];
        $inm_co_acreditado_ins['lada_nep'] = $_POST['lada_nep'];
        $inm_co_acreditado_ins['numero_nep'] = $_POST['numero_nep'];
        $inm_co_acreditado_ins['extension_nep'] = $_POST['extension_nep'];

        $result = (new inm_comprador(link: $this->link))->asigna_nuevo_co_acreditado_bd(
            inm_comprador_id: $inm_comprador_id, inm_co_acreditado: $inm_co_acreditado_ins);

        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos',data:  $result,header:  $header,ws:  $ws);
        }
        $this->link->commit();


        $out = (new \gamboamartin\inmuebles\controllers\_base())->out(controlador: $this,header:  $header,
            result:  $result,retorno:  $retorno, ws: $ws);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar salida', data: $out,
                header: true, ws: false);
        }

        $result->siguiente_view = $retorno->siguiente_view;


        return $result;
    }

    /**
     * Integra un formulario para la asignacion de una ubicacion
     * @param bool $header Si header retorna resultado en web
     * @param bool $ws Si ws muestra resultado en json
     * @return array|stdClass
     * @version 1.105.1
     */
    public function asigna_ubicacion(bool $header, bool $ws = false): array|stdClass
    {

        if(isset($_GET['accion']) && $_GET['accion'] == 'asigna_ubicacion') {
            $template = $this->modifica(header: false);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
            }
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }

        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }

        $filtro_ubi['inm_ubicacion.status'] = 'activo';

        $in_ubi = array();
        $in_ubi['llave'] = 'inm_status_ubicacion.id';
        $in_ubi['values'] = array('6','7');

        $r_ubicacion_etapa = (new inm_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_ubi, in: $in_ubi);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener ubicacones firmadas',data:  $r_ubicacion_etapa,
                header: $header,ws:  $ws);
        }

        $rel_ubis_fir = array();
        foreach ($r_ubicacion_etapa->registros as $registro){
            $rel_ubis_fir[] = $registro['inm_ubicacion_id'];
        }

        $filtro_rel_ubi['inm_rel_ubi_comp.status'] = 'activo';

        $in_ubi = array();
        $in_ubi['llave'] = 'inm_ubicacion.id';
        $in_ubi['values'] = $rel_ubis_fir;
        $r_inm_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->filtro_and(filtro: $filtro_rel_ubi, in: $in_ubi);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }

        $temporal = array();
        foreach ($r_inm_rel_ubi_comp->registros as $registro){
            $temporal[] = $registro['inm_ubicacion_id'];
        }

        $not_in = array();
        $not_in['llave'] = 'inm_ubicacion.id';
        $not_in['values'] = $temporal;
        $inm_ubicacion_id = (new _inm_comprador())->inm_ubicacion_id_input(controler: $this, in: $in_ubi,
            not_in: $not_in);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al inm_ubicacion_id',data:  $inm_ubicacion_id,
                header: $header,ws:  $ws);
        }

        $this->inputs->inm_ubicacion_util_id = $inm_ubicacion_id;

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] == 'proceso_cliente') {
            $params = array('pestana_general_actual' => 'pestanageneral2', 'pestana_actual' => 'pestana2');
        }
        $link_rel_ubi_comp_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,seccion: 'inm_rel_ubi_comp',
            params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_rel_ubi_comp_alta_bd,
                header: $header,ws:  $ws);
        }

        $this->link_rel_ubi_comp_alta_bd = $link_rel_ubi_comp_alta_bd;

        $inm_ubicaciones = (new _inm_comprador())->inm_ubicaciones(inm_comprador_id: $this->registro_id,
            link:  $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener compradores',data:  $inm_ubicaciones,
                header: $header,ws:  $ws);
        }

        $this->inm_ubicaciones = $inm_ubicaciones;

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    public function asigna_autorizado(bool $header, bool $ws = false): array|stdClass
    {
        $filtro['inm_comprador.id']= $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $documento_sic = $this->html->input_file(cols: 12,name: 'sic',row_upd:  new stdClass(),value_vacio:  false,
            place_holder: 'SIC', required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_sic, header: $header,ws:  $ws);
        }

        $this->inputs->documento_sic = $documento_sic;

        $documento_constancia_credito = $this->html->input_file(cols: 12,name: 'constancia_credito',
            row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Constancia de Credito',  required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_constancia_credito, header: $header,ws:  $ws);
        }

        $this->inputs->documento_constancia_credito = $documento_constancia_credito;

        $columns_ds = array('com_cliente_rfc','com_cliente_razon_social');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'com_cliente_id',
            keys_selects:$keys_selects, id_selected: $registro->registros[0]['com_cliente_id'], label: 'Cliente',
            columns_ds : $columns_ds,disabled: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_notaria_descripcion');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_notaria_id',
            keys_selects: $keys_selects, id_selected:  $this->registro['inm_notaria_id'], label: 'Notaria',
            columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_notaria_descripcion');
        $extra_params_keys[] = 'inm_notaria_select_id';
        $inm_notaria_id = (new inm_notaria_html(html: $this->html_base))->select_inm_notaria_id(
            cols: 12, con_registros: true, id_selected: $this->registro['inm_notaria_id'], link: $this->link,
            columns_ds: $columns_ds, disabled: true, extra_params_keys: $extra_params_keys);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al inm_notaria_id',data:  $inm_notaria_id,
                header: $header,ws:  $ws);
        }

        $this->inputs->inm_notaria_select_id = $inm_notaria_id;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }

        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_autorizado_bd = $this->obj_link->link_con_id(accion:'autorizado_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_comprador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_autorizado_bd,
                header: $header,ws:  $ws);
        }

        $this->link_autorizado_bd = $link_autorizado_bd;

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 39;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_sic = 'SIC';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_sic = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_sic = $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_sic = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana6');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_sic = $button_inm_doc_comprador_elimina_bd;
        }

        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 40;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_constancia_credito = 'Constancia de Credito';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_constancia_credito = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_constancia_credito = $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_constancia_credito = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana4');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_constancia_credito = $button_inm_doc_comprador_elimina_bd;
        }


        return $base;
    }

    public function asigna_por_firma(bool $header, bool $ws = false): array|stdClass
    {
        $filtro['inm_comprador.id']= $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $filtro_che['inm_comprador.id'] = $this->registro_id;
        $r_firma = (new inm_firma(link: $this->link))->filtro_and(filtro: $filtro_che);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $r_firma,
                header: $header, ws: $ws);
        }

        if($r_firma->n_registros > 0) {
            $this->row_upd->isr = $r_firma->registros[0]['inm_firma_isr'];
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $documento_anexos = $this->html->input_file(cols: 12,name: 'anexos',row_upd:  new stdClass(),value_vacio:  false,
            place_holder: 'Anexos',required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_anexos, header: $header,ws:  $ws);
        }

        $this->inputs->documento_anexos = $documento_anexos;

        $documento_instruccion_credito = $this->html->input_file(cols: 12,name: 'instruccion_credito',
            row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Instruccion de Credito',required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_instruccion_credito, header: $header,ws:  $ws);
        }

        $this->inputs->documento_instruccion_credito = $documento_instruccion_credito;

        $documento_notificacion_descuento = $this->html->input_file(cols: 12,name: 'notificacion_descuento',
            row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Notificacion de Descuento',required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_notificacion_descuento, header: $header,ws:  $ws);
        }

        $this->inputs->documento_notificacion_descuento = $documento_notificacion_descuento;

        $input_not_sec = '';
        $keys = array(2,3,4);
        if(in_array($this->registro['inm_tipo_credito_id'],$keys)) {
            $input_not_sec = $this->html->input_file(cols: 12,name: 'notificacion_descuento_sec',
                row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Notificacion de Descuento Sec.',
                required: false);
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener inputs',data:  $input_not_sec, header: $header,
                    ws:  $ws);
            }
        }

        $this->inputs->documento_notificacion_descuento_sec = $input_not_sec;

        $documento_isr_notaria = $this->html->input_file(cols: 12,name: 'isr_notaria',
            row_upd:  new stdClass(),value_vacio:  false,place_holder: 'ISR Notaria',required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_isr_notaria, header: $header,ws:  $ws);
        }

        $this->inputs->documento_isr_notaria = $documento_isr_notaria;

        $documento_comprobante_exento = $this->html->input_file(cols: 12,name: 'comprobante_exento[]',
            row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Comprobante Exento',required: false,
            multiple: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_comprobante_exento, header: $header,ws:  $ws);
        }

        $this->inputs->documento_comprobante_exento = $documento_comprobante_exento;

        $documento_xml_exento = $this->html->input_file(cols: 12,name: 'xml_exento[]',
            row_upd:  new stdClass(),value_vacio:  false,place_holder: 'XML Exento',required: false, multiple: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_xml_exento, header: $header,ws:  $ws);
        }

        $this->inputs->documento_xml_exento = $documento_xml_exento;

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'isr',
            keys_selects:$keys_selects, place_holder: 'ISR',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'numero_credito',
            keys_selects: $keys_selects, place_holder: 'Numero de Credito',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'pago_precio_compra_venta',
            keys_selects:$keys_selects, place_holder: 'Precio Compra-Venta',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'pago_parcial_precio_compra_venta',
            keys_selects:$keys_selects, place_holder: 'Parcial Compra-Venta',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'pago_propio_peculio',
            keys_selects:$keys_selects, place_holder: 'Pago Propio Peculio',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'pago_cuv',
            keys_selects:$keys_selects, place_holder: 'Pago CUV',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $columns_ds = array('inm_tipo_exento_descripcion');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_tipo_exento_id',
            keys_selects: $keys_selects, id_selected:  $this->registro['inm_tipo_exento_id'], label: 'Exento',
            columns_ds : $columns_ds, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }

        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }

        $checked_default_isr = 1;
        if($this->row_upd->aplica_isr === 'NO'){
            $checked_default_isr = 2;
        }

        $aplica_isr = $this->html->directivas->input_radio_doble(campo: 'aplica_isr',
            checked_default: $checked_default_isr, tag: 'Aplica ISR', val_1: 'SI',val_2: 'NO', cols: 12);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener aplica_isr',data:  $aplica_isr, header: $header,
                ws:  $ws);
        }

        $this->inputs->aplica_isr = $aplica_isr;

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] == 'proceso_cliente') {
            $params = array('pestana_general_actual' => 'pestanageneral2', 'pestana_actual' => 'pestana7');
        }
        $link_inm_firma_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,
            seccion: 'inm_firma',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_inm_firma_alta_bd,
                header: $header,ws:  $ws);
        }
        $this->link_inm_firma_alta_bd = $link_inm_firma_alta_bd;

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);


        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 41;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_anexos = 'Anexos';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_anexos = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_anexos = $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_anexos = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana7');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_anexos = $button_inm_doc_comprador_elimina_bd;
        }

        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 42;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_instruccion_credito = 'Instruccion de Credito';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_instruccion_credito = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_instruccion_credito= $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_instruccion_credito = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana7');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_instruccion_credito = $button_inm_doc_comprador_elimina_bd;
        }
        
        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 43;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_notificacion_descuento = 'Notificacion de Descuento';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_notificacion_descuento = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_notificacion_descuento= $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_notificacion_descuento = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana7');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_notificacion_descuento = $button_inm_doc_comprador_elimina_bd;
        }
        
        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 44;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_isr_notaria = 'ISR Notaria';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_isr_notaria = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_isr_notaria= $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_isr_notaria = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana7');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_isr_notaria = $button_inm_doc_comprador_elimina_bd;
        }

        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 68;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_notificacion_descuento_sec = 'Notificacion de Descuento Sec.';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_notificacion_descuento_sec = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_notificacion_descuento_sec = $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_notificacion_descuento_sec = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana7');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_notificacion_descuento_sec = $button_inm_doc_comprador_elimina_bd;
        }

        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 66;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        $comprobante_exento = array();
        if($r_inm_doc_comprador->n_registros > 0) {
            $cont = 1;
            foreach ($r_inm_doc_comprador->registros as $registro) {
                $temp = new stdClass();
                $temp->descripcion = ucwords(strtolower($registro['doc_tipo_documento_descripcion']))." ".$cont;

                $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                    registro_id: $registro['inm_doc_comprador_id'], seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
                }

                $temp->button_inm_doc_comprador_descarga = $button_inm_doc_comprador_descarga;

                $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                    etiqueta: 'Vista Previa', registro_id: $registro['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
                }

                $temp->button_inm_doc_comprador_vista_previa = $button_inm_doc_comprador_vista_previa;

                $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                    etiqueta: 'Descarga ZIP', registro_id: $registro['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
                }

                $temp->button_inm_doc_comprador_descarga_zip = $button_inm_doc_comprador_descarga_zip;

                $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                    'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                    'pestana_actual' => 'pestana7');
                $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                    etiqueta: 'Elimina', registro_id: $registro['inm_doc_comprador_id'], seccion: 'inm_doc_comprador',
                    style: 'danger',params: $params);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_comprador_elimina_bd, header: $header, ws: $ws);
                }

                $temp->button_inm_doc_comprador_elimina_bd = $button_inm_doc_comprador_elimina_bd;

                $comprobante_exento[] = $temp;
                $cont++;
            }
        }

        $this->comprobante_exento = $comprobante_exento;

        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 67;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        $xml_exento = array();
        if($r_inm_doc_comprador->n_registros > 0) {
            $cont = 1;
            foreach ($r_inm_doc_comprador->registros as $registro) {
                $temp = new stdClass();
                $temp->descripcion = ucwords(strtolower($registro['doc_tipo_documento_descripcion']))." ".$cont;

                $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                    registro_id: $registro['inm_doc_comprador_id'], seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
                }

                $temp->button_inm_doc_comprador_descarga = $button_inm_doc_comprador_descarga;

                $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                    etiqueta: 'Vista Previa', registro_id: $registro['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
                }

                $temp->button_inm_doc_comprador_vista_previa = $button_inm_doc_comprador_vista_previa;

                $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                    etiqueta: 'Descarga ZIP', registro_id: $registro['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
                }

                $temp->button_inm_doc_comprador_descarga_zip = $button_inm_doc_comprador_descarga_zip;

                $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                    'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                    'pestana_actual' => 'pestana7');
                $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                    etiqueta: 'Elimina', registro_id: $registro['inm_doc_comprador_id'], seccion: 'inm_doc_comprador',
                    style: 'danger',params: $params);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_comprador_elimina_bd, header: $header, ws: $ws);
                }

                $temp->button_inm_doc_comprador_elimina_bd = $button_inm_doc_comprador_elimina_bd;

                $xml_exento[] = $temp;
                $cont++;
            }
        }

        $this->xml_exento = $xml_exento;

        return $base;
    }

    public function asigna_escriturado(bool $header, bool $ws = false): array|stdClass
    {
        $filtro['inm_comprador.id']= $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $filtro_che['inm_comprador.id'] = $this->registro_id;
        $r_escritura = (new inm_escritura(link: $this->link))->filtro_and(filtro: $filtro_che);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $r_escritura,
                header: $header, ws: $ws);
        }

        $hoy = date('Y-m-d');
        if($r_escritura->n_registros > 0) {
            $this->row_upd->numero_escritura = $r_escritura->registros[0]['inm_escritura_numero_escritura'];
            $hoy = $r_escritura->registros[0]['inm_escritura_fecha_escritura'];
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $documento_validacion_poder = $this->html->input_file(cols: 12,name: 'validacion_poder',
            row_upd:  new stdClass(),value_vacio:  false, place_holder: 'Validacion Poder',required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_validacion_poder, header: $header,ws:  $ws);
        }
        $this->inputs->documento_validacion_poder = $documento_validacion_poder;

        $documento_acuse_patron = $this->html->input_file(cols: 12,name: 'acuse_patron',
            row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Acuse de Patron', required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_acuse_patron, header: $header,ws:  $ws);
        }
        $this->inputs->documento_acuse_patron = $documento_acuse_patron;

        $documento_escrituras = $this->html->input_file(cols: 12,name: 'escritura',
            row_upd:  new stdClass(),value_vacio:  false,place_holder: 'Escrituras', required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento_escrituras, header: $header,ws:  $ws);
        }
        $this->inputs->documento_escrituras = $documento_escrituras;

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd,value_vacio:  false,name: 'fecha_escritura',
            place_holder: 'Fecha Escritura',value: $hoy);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar fecha',
                data:  $fecha, header: $header,ws: $ws);
        }
        $this->inputs->fecha_escritura = $fecha;

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'numero_escritura',
            keys_selects: $keys_selects, place_holder: 'No. Escritura');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }

        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] == 'proceso_cliente') {
            $params = array('pestana_general_actual' => 'pestanageneral2', 'pestana_actual' => 'pestana8');
        }
        $link_inm_escritura_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,
            seccion: 'inm_escritura',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_inm_escritura_alta_bd,
                header: $header,ws:  $ws);
        }
        $this->link_inm_escritura_alta_bd = $link_inm_escritura_alta_bd;

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);
        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 46;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_validacion_poder = 'Validacion Poder';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_validacion_poder = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_validacion_poder = $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_validacion_poder = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana8');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_validacion_poder = $button_inm_doc_comprador_elimina_bd;
        }

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);
        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 47;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_acuse_patron = 'Acuse de Patron';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_acuse_patron = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_acuse_patron = $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_acuse_patron = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana8');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_acuse_patron = $button_inm_doc_comprador_elimina_bd;
        }

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);
        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 37;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_escritura = 'Escritura';

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_escritura = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa_escritura = $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip_escritura = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana8');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd_escritura = $button_inm_doc_comprador_elimina_bd;
        }

        return $base;
    }

    public function asigna_cotejado(bool $header, bool $ws = false): array|stdClass
    {

        $filtro['inm_comprador.id']= $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }

        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_cotejado_bd = $this->obj_link->link_con_id(accion:'cotejado_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_comprador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_cotejado_bd,
                header: $header,ws:  $ws);
        }

        $this->link_cotejado_bd = $link_cotejado_bd;

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    public function asigna_cobrado(bool $header, bool $ws = false): array|stdClass
    {

        $filtro['inm_comprador.id']= $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        if(!isset($this->row_upd->nombre_beneficiario) || $this->row_upd->nombre_beneficiario === ''){
            $this->row_upd->nombre_beneficiario = $registro->registros[0]['com_cliente_razon_social'];
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_tipo_gasto_id','inm_tipo_gasto_descripcion');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_tipo_gasto_id',
            keys_selects:$keys_selects, id_selected:-1, label: 'Tipo Gasto',
            columns_ds : $columns_ds,required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_tipo_cheque_id','inm_tipo_cheque_descripcion');
        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'inm_tipo_cheque_id',
            keys_selects:$keys_selects, id_selected:-1, label: 'Tipo Cheque',
            columns_ds : $columns_ds,required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_conf_cuenta_notaria_descripcion');
        $filtro_conf['inm_notaria.id'] = $this->registro['inm_notaria_id'];
        $extra_params_keys[] = 'inm_conf_cuenta_notaria_beneficiario';

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro:  $filtro_conf,
            key: 'inm_conf_cuenta_notaria_id', keys_selects:$keys_selects, id_selected:-1, label: 'Cuenta Notaria',
            columns_ds : $columns_ds,required: false, extra_params_keys: $extra_params_keys);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'nombre_beneficiario', keys_selects:$keys_selects,
            place_holder: 'Nombre Beneficiario',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'monto', keys_selects:$keys_selects,
            place_holder: 'Monto',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'monto_cheque_secundario', keys_selects:$keys_selects,
            place_holder: 'Monto Secundario',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'monto_transferencia', keys_selects:$keys_selects,
            place_holder: 'Monto Transferencia',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'monto_comision', keys_selects:$keys_selects,
            place_holder: 'Monto Comision', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'efectivo', keys_selects:$keys_selects,
            place_holder: 'Efectivo', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'numero_cheque', keys_selects:$keys_selects,
            place_holder: 'Numero Cheque', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'transferencia', keys_selects:$keys_selects,
            place_holder: 'Concepto Transferencia', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $checked_default_gasto = 2;

        $genera_gasto = $this->html->directivas->input_radio_doble(campo: 'genera_gasto',
            checked_default: $checked_default_gasto, tag: 'Genera Gasto Notaria', val_1: 'SI',val_2: 'NO', cols: 12);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener genera_gasto',data:  $genera_gasto, header: $header,
                ws:  $ws);
        }

        $this->inputs->genera_gasto = $genera_gasto;

        $modelo = new bn_cuenta(link: $this->link);
        $bn_cuenta_id = $this->html->select_catalogo(cols: 6, con_registros: true, id_selected: -1, modelo: $modelo,
            id_css: 'bn_cuenta_sl_id', label: 'Cuenta', name: 'bn_cuenta_sl_id');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $bn_cuenta_id,header: $header, ws:$ws);
        }

        $this->inputs->bn_cuenta_sl_id = $bn_cuenta_id;

        $bn_cuenta_id = $this->html->select_catalogo(cols: 6, con_registros: true, id_selected: -1, modelo: $modelo,
            id_css: 'bn_cuenta_sl_trs_id', label: 'Cuenta', name: 'bn_cuenta_sl_trs_id');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $bn_cuenta_id,header: $header, ws:$ws);
        }

        $this->inputs->bn_cuenta_sl_trs_id = $bn_cuenta_id;

        $filtro['inm_comprador.id'] = $this->registro_id;
        $order = array('inm_cheque.fecha_alta'=>'DESC');
        $r_inm_cheque = (new inm_rel_cheque_comprador(link: $this->link))->filtro_and(filtro: $filtro,order: $order);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener etapas', data: $r_inm_cheque,header: $header,
                ws:  $ws);
        }

        $registros = array();
        $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>$this->seccion,
            'id_retorno'=>$this->registro_id);

        if(isset($_GET['pestana_general_actual'])){
            $params['pestana_general_actual'] = 'pestanageneral2';
            $params['pestana_actual'] = 'pestana10';
        }

        foreach ($r_inm_cheque->registros as $inm_cheque) {
            $button = $this->html->button_href(accion: 'elimina_bd', etiqueta: 'Elimina',
                registro_id: $inm_cheque['inm_rel_cheque_comprador_id'], seccion: 'inm_rel_cheque_comprador',
                style: 'danger', params: $params);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al integrar button',data:  $button,header: $header,
                    ws:  $ws);
            }
            $inm_cheque['elimina_bd'] = $button;

            $button = $this->html->button_href(accion: 'solicitud_gasto', etiqueta: 'Solicitud de Gasto',
                registro_id: $inm_cheque['inm_cheque_id'], seccion: 'inm_cheque', style: 'info  ',
                params: $params);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al integrar button',data:  $button,header: $header,
                    ws:  $ws);
            }
            $inm_cheque['solicitud_gasto'] = $button;

            $check = "<input type='checkbox'  class='checkbox_reg' 
                        data-movimiento = 'cheque'
                        data-nombre_beneficiario = '$inm_cheque[inm_cheque_nombre_beneficiario]'
                        data-numero_cheque = '$inm_cheque[inm_cheque_numero_cheque]'
                        data-monto = '$inm_cheque[inm_cheque_monto]'
                        data-inm_tipo_cheque_id = '$inm_cheque[inm_tipo_cheque_id]'
                        data-bn_cuenta_id = '$inm_cheque[bn_cuenta_id]'
                        data-inm_tipo_gasto_id = '1'
                        name='cheque_id' value='$inm_cheque[inm_cheque_id]'>";
            $inm_cheque['checkbox'] = $check;

            $filtro_rel_doc_che['inm_cheque.id'] = $inm_cheque['inm_cheque_id'];
            $r_rel_doc_cheque = (new inm_rel_doc_cheque(link: $this->link))->filtro_and(filtro: $filtro_rel_doc_che);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al obtener inputs', data: $r_rel_doc_cheque,header: $header,
                    ws:  $ws);
            }

            if($r_rel_doc_cheque->n_registros > 0){
                $button_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                    registro_id: $r_rel_doc_cheque->registros[0]['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_descarga, header: $header, ws: $ws);
                }

                $button_vista_previa = $this->html->button_href(accion: 'vista_previa',
                    etiqueta: 'Vista Previa', registro_id: $r_rel_doc_cheque->registros[0]['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_vista_previa, header: $header, ws: $ws);
                }

                $button_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                    etiqueta: 'Descarga ZIP', registro_id: $r_rel_doc_cheque->registros[0]['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_descarga_zip, header: $header, ws: $ws);
                }

                $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                    'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                    'pestana_actual' => 'pestana10');
                $button_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                    etiqueta: 'Elimina', registro_id: $r_rel_doc_cheque->registros[0]['inm_rel_doc_cheque_id'],
                    seccion: 'inm_rel_doc_cheque', style: 'danger',params: $params);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_elimina_bd,
                        header: $header, ws: $ws);
                }

                $res = "<tr>
                            <td colspan='7'>
                                <div class='content_btns'>
                                    <div class='content_btn'>
                                        $button_descarga
                                    </div>
                                    <div class='content_btn'>
                                        $button_vista_previa
                                    </div>
                                    <div class='content_btn'>
                                        $button_descarga_zip
                                    </div>
                                    <div class='content_btn'>
                                        $button_elimina_bd
                                    </div>
                                </div>
                            </td>
                        </tr>";
            }else{
                $name = "documentos_cheques[$inm_cheque[inm_cheque_id]][36][]";

                $button = $this->html->input_file(cols: 12, name: $name, row_upd: $this->row_upd,
                    value_vacio: false, place_holder: 'Subir Documento',required: false, con_label: false);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al obtener inputs', data: $button,header: $header,
                        ws:  $ws);
                }
                $res = "<tr>
                <td colspan='7'>$button</td>
                </tr>";
            }

            $inm_cheque['documento'] = $res;

            $registros[] = $inm_cheque;
        }

        $this->cheques = $registros;

        $filtro['inm_comprador.id'] = $this->registro_id;
        $order = array('inm_transferencia.fecha_alta'=>'DESC');
        $r_inm_transferencia = (new inm_rel_transferencia_comprador(link: $this->link))->filtro_and(filtro: $filtro,
            order: $order);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener etapas', data: $r_inm_transferencia,header: $header,
                ws:  $ws);
        }

        $registros = array();
        $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>$this->seccion,
            'id_retorno'=>$this->registro_id);

        if(isset($_GET['pestana_general_actual'])){
            $params['pestana_general_actual'] = 'pestanageneral2';
            $params['pestana_actual'] = 'pestana10';
        }
        foreach ($r_inm_transferencia->registros as $inm_transferencia) {
            $button = $this->html->button_href(accion: 'elimina_bd', etiqueta: 'Elimina',
                registro_id: $inm_transferencia['inm_rel_transferencia_comprador_id'],
                seccion: 'inm_rel_transferencia_comprador', style: 'danger', params: $params);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al integrar button',data:  $button,header: $header,
                    ws:  $ws);
            }
            $inm_transferencia['elimina_bd'] = $button;

            $filtro_rel_doc_trns['inm_transferencia.id'] = $inm_transferencia['inm_transferencia_id'];
            $r_rel_doc_transferencia = (new inm_rel_doc_transferencia(link: $this->link))->filtro_and(
                filtro: $filtro_rel_doc_trns);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al obtener inputs', data: $r_rel_doc_transferencia,
                    header: $header, ws:  $ws);
            }

            if($r_rel_doc_transferencia->n_registros > 0){
                $button_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                    registro_id: $r_rel_doc_transferencia->registros[0]['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_descarga, header: $header, ws: $ws);
                }

                $button_vista_previa = $this->html->button_href(accion: 'vista_previa',
                    etiqueta: 'Vista Previa', registro_id: $r_rel_doc_transferencia->registros[0]['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_vista_previa, header: $header, ws: $ws);
                }

                $button_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                    etiqueta: 'Descarga ZIP', registro_id: $r_rel_doc_transferencia->registros[0]['inm_doc_comprador_id'],
                    seccion: 'inm_doc_comprador', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_descarga_zip, header: $header, ws: $ws);
                }

                $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                    'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                    'pestana_actual' => 'pestana10');
                $button_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                    etiqueta: 'Elimina', registro_id: $r_rel_doc_transferencia->registros[0]['inm_rel_doc_transferencia_id'],
                    seccion: 'inm_rel_doc_transferencia', style: 'danger',params: $params);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_elimina_bd,
                        header: $header, ws: $ws);
                }

                $res = "<tr>
                            <td colspan='7'>
                                <div class='content_btns'>
                                    <div class='content_btn'>
                                        $button_descarga
                                    </div>
                                    <div class='content_btn'>
                                        $button_vista_previa
                                    </div>
                                    <div class='content_btn'>
                                        $button_descarga_zip
                                    </div>
                                    <div class='content_btn'>
                                        $button_elimina_bd
                                    </div>
                                </div>
                            </td>
                        </tr>";
            }else{
                $name = "documentos_transferencias[$inm_transferencia[inm_transferencia_id]][49][]";

                $button = $this->html->input_file(cols: 12, name: $name, row_upd: new stdClass(),
                    value_vacio: false, place_holder: 'Subir Documento',required: false, con_label: false);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al obtener inputs', data: $button,header: $header,
                        ws:  $ws);
                }
                $res = "<tr>
                <td colspan='7'>$button</td>
                </tr>";
            }

            $inm_transferencia['documento'] = $res;

            $registros[] = $inm_transferencia;
        }


        $this->transferencias = $registros;


        $filtro['inm_comprador.id'] = $this->registro_id;
        $order = array('inm_efectivo.fecha_alta'=>'DESC');
        $r_inm_efectivo = (new inm_rel_efectivo_comprador(link: $this->link))->filtro_and(filtro: $filtro,order: $order);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener etapas', data: $r_inm_efectivo,header: $header,
                ws:  $ws);
        }

        $registros = array();
        $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>$this->seccion,
            'id_retorno'=>$this->registro_id);

        if(isset($_GET['pestana_general_actual'])){
            $params['pestana_general_actual'] = 'pestanageneral2';
            $params['pestana_actual'] = 'pestana10';
        }
        foreach ($r_inm_efectivo->registros as $inm_efectivo) {
            $button = $this->html->button_href(accion: 'elimina_bd', etiqueta: 'Elimina',
                registro_id: $inm_efectivo['inm_rel_efectivo_comprador_id'], seccion: 'inm_rel_efectivo_comprador',
                style: 'danger', params: $params);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al integrar button',data:  $button,header: $header,
                    ws:  $ws);
            }
            $inm_efectivo['elimina_bd'] = $button;

            $registros[] = $inm_efectivo;
        }

        $this->efectivos = $registros;

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }

        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_cobrado_bd = $this->obj_link->link_con_id(accion:'cobrado_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_comprador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_cobrado_bd,
                header: $header,ws:  $ws);
        }

        $this->link_cobrado_bd = $link_cobrado_bd;

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    public function proceso_cliente(bool $header, bool $ws = false): array|stdClass
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

        $etapa = $this->etapa($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $etapa,header: $header,ws: $ws);
        }

        $asigna_ubicacion = $this->asigna_ubicacion($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_ubicacion,header: $header,ws: $ws);
        }

        $asigna_en_avaluo = $this->asigna_en_avaluo($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_en_avaluo,header: $header,ws: $ws);
        }

        $asigna_por_ingresar = $this->asigna_por_ingresar($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_por_ingresar,header: $header,ws: $ws);
        }

        $asigna_ingresado = $this->asigna_ingresado($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_ingresado,header: $header,ws: $ws);
        }

        $asigna_autorizado = $this->asigna_autorizado($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_autorizado,header: $header,ws: $ws);
        }

        $asigna_por_firma = $this->asigna_por_firma($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_por_firma,header: $header,ws: $ws);
        }

        $asigna_escriturado = $this->asigna_escriturado($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_escriturado,header: $header,ws: $ws);
        }

        $asigna_cotejado = $this->asigna_cotejado($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_cotejado,header: $header,ws: $ws);
        }

        $asigna_cobrado = $this->asigna_cobrado($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $asigna_cobrado,header: $header,ws: $ws);
        }

        $base = $this->base_upd(keys_selects: $this->keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_comprador_nss','inm_comprador_razon_social');
        $filtro['inm_comprador.id'] = $this->registro_id;
        $inm_prospecto_id = (new inm_comprador_html(html: $this->html_base))->select_inm_comprador_id(
            cols: 6, con_registros: true, id_selected: $this->registro_id, link: $this->link, columns_ds: $columns_ds,
            filtro: $filtro,label: "Cliente");
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_prospecto_id, header: $header, ws: $ws);
        }
        $this->inputs->inm_comprador_seleccionado_id  = $inm_prospecto_id;

        $columns_ds = array('inm_status_comprador_descripcion');
        $filtro_status['inm_status_comprador.id'] = $this->registro['inm_status_comprador_id'];
        $inm_status_comprador_id = (new inm_status_comprador_html(html: $this->html_base))->
        select_inm_status_comprador_id(cols: 6, con_registros: true,
            id_selected: $this->registro['inm_status_comprador_id'], link: $this->link, columns_ds: $columns_ds,
            filtro: $filtro_status, label: "Status Actual");
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_status_comprador_id, header: $header, ws: $ws);
        }
        $this->inputs->actual_inm_status_comprador_id  = $inm_status_comprador_id;

        $btn_action_next = $this->html->hidden('btn_action_next', value: 'proceso_cliente');
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


    public function asigna_por_ingresar(bool $header, bool $ws = false): array|stdClass
    {

        $filtro['inm_comprador.id']= $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $filtro_che['inm_comprador.id'] = $this->registro_id;
        $r_avaluo = (new inm_avaluo(link: $this->link))->filtro_and(filtro: $filtro_che);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $r_avaluo,
                header: $header, ws: $ws);
        }

        if($r_avaluo->n_registros > 0) {
            $this->row_upd->metros_terreno = $r_avaluo->registros[0]['inm_avaluo_metros_terreno'];
            $this->row_upd->metros_construidos = $r_avaluo->registros[0]['inm_avaluo_metros_construidos'];
            $this->row_upd->valor_avaluo = $r_avaluo->registros[0]['inm_avaluo_valor_avaluo'];
        }


        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'metros_terreno',
            keys_selects:$keys_selects, place_holder: 'Metros de Terreno');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'metros_construidos',
            keys_selects:$keys_selects, place_holder: 'Metros de Construidos');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'valor_avaluo',
            keys_selects:$keys_selects, place_holder: 'Valor Avaluo');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $documento = $this->html->input_file(cols: 12,name: 'avaluo',row_upd:  new stdClass(),value_vacio:  false,
            place_holder: 'Avaluo',required: false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento, header: $header,ws:  $ws);
        }
        $this->inputs->avaluo = $documento;

        $documento_poder = $this->html->input_file(cols: 12, name: 'poder', row_upd: new stdClass(), value_vacio: false,
            place_holder: 'Escritura Poder',required: false);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $documento_poder, header: $header, ws: $ws);
        }

        $this->inputs->documento_poder = $documento_poder;

        $columns_ds = array('com_cliente_rfc','com_cliente_razon_social');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'com_cliente_id',
            keys_selects:$keys_selects, id_selected: $registro->registros[0]['com_cliente_id'], label: 'Cliente',
            columns_ds : $columns_ds,disabled: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }

        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }
        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] == 'proceso_cliente') {
            $params = array('pestana_general_actual' => 'pestanageneral2', 'pestana_actual' => 'pestana4');
        }
        $link_inm_avaluo_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,
            seccion: 'inm_avaluo',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_inm_avaluo_alta_bd,
                header: $header,ws:  $ws);
        }
        $this->link_inm_avaluo_alta_bd = $link_inm_avaluo_alta_bd;

        $filtro_rel_ubi['inm_rel_ubi_comp.status'] = 'activo';
        $filtro_rel_ubi['inm_comprador.id'] = $this->registro['inm_comprador_id'];
        $r_inm_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->filtro_and(filtro: $filtro_rel_ubi);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $r_inm_rel_ubi_comp,
                header: $header,ws:  $ws);
        }

        if($r_inm_rel_ubi_comp->n_registros > 0) {
            $filtro_doc['inm_ubicacion.id'] = $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_id'];
            $filtro_doc['doc_tipo_documento.id'] = 35;
            $r_inm_doc_ubicacion_reg = (new inm_doc_ubicacion(link: $this->link))->filtro_and(filtro: $filtro_doc);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al generar link', data: $r_inm_doc_ubicacion_reg,
                    header: $header, ws: $ws);
            }

            if ($r_inm_doc_ubicacion_reg->n_registros > 0) {
                $this->descripcion_doc_ubicacion_escritura = "Escritura Poder";

                $button_inm_doc_ubicacion_escritura_descarga = $this->html->button_href(accion: 'descarga',
                    etiqueta: 'Descarga', registro_id: $r_inm_doc_ubicacion_reg->registros[0]['inm_doc_ubicacion_id'],
                    seccion: 'inm_doc_ubicacion', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_ubicacion_escritura_descarga, header: $header, ws: $ws);
                }

                $this->button_inm_doc_ubicacion_escritura_descarga = $button_inm_doc_ubicacion_escritura_descarga;

                $button_inm_doc_ubicacion_escritura_vista_previa = $this->html->button_href(accion: 'vista_previa',
                    etiqueta: 'Vista Previa', registro_id: $r_inm_doc_ubicacion_reg->registros[0]['inm_doc_ubicacion_id'],
                    seccion: 'inm_doc_ubicacion', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_ubicacion_escritura_vista_previa, header: $header, ws: $ws);
                }

                $this->button_inm_doc_ubicacion_escritura_vista_previa = $button_inm_doc_ubicacion_escritura_vista_previa;

                $button_inm_doc_ubicacion_escritura_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                    etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_ubicacion_reg->registros[0]['inm_doc_ubicacion_id'],
                    seccion: 'inm_doc_ubicacion', style: 'success');
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_ubicacion_escritura_descarga_zip, header: $header, ws: $ws);
                }

                $this->button_inm_doc_ubicacion_escritura_descarga_zip = $button_inm_doc_ubicacion_escritura_descarga_zip;

                $params = array('accion_retorno' => 'proceso_cliente', 'seccion_retorno' => 'inm_comprador',
                    'id_retorno' => $this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                    'pestana_actual' => 'pestana4');
                $button_inm_doc_ubicacion_escritura_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                    etiqueta: 'Elimina', registro_id: $r_inm_doc_ubicacion_reg->registros[0]['inm_doc_ubicacion_id'],
                    seccion: 'inm_doc_ubicacion', style: 'danger', params: $params);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al integrar button',
                        data: $button_inm_doc_ubicacion_escritura_elimina_bd, header: $header, ws: $ws);
                }

                $this->button_inm_doc_ubicacion_escritura_elimina_bd = $button_inm_doc_ubicacion_escritura_elimina_bd;
            }
        }

        $filtro_inm_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_inm_doc['doc_tipo_documento.id'] = 38;
        $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->filtro_and(filtro: $filtro_inm_doc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar doc',data:  $r_inm_doc_comprador,
                header: $header,ws:  $ws);
        }

        if($r_inm_doc_comprador->n_registros > 0) {
            $this->descripcion_doc_comprador = "Avaluo";

            $button_inm_doc_comprador_descarga = $this->html->button_href(accion: 'descarga', etiqueta: 'Descarga',
                registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga = $button_inm_doc_comprador_descarga;

            $button_inm_doc_comprador_vista_previa = $this->html->button_href(accion: 'vista_previa',
                etiqueta: 'Vista Previa', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_vista_previa, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_vista_previa = $button_inm_doc_comprador_vista_previa;

            $button_inm_doc_comprador_descarga_zip = $this->html->button_href(accion: 'descarga_zip',
                etiqueta: 'Descarga ZIP', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'success');
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button',
                    data: $button_inm_doc_comprador_descarga_zip, header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_descarga_zip = $button_inm_doc_comprador_descarga_zip;

            $params = array('accion_retorno'=>'proceso_cliente','seccion_retorno'=>'inm_comprador',
                'id_retorno'=>$this->registro_id, 'pestana_general_actual' => 'pestanageneral2',
                'pestana_actual' => 'pestana4');
            $button_inm_doc_comprador_elimina_bd = $this->html->button_href(accion: 'elimina_bd',
                etiqueta: 'Elimina', registro_id: $r_inm_doc_comprador->registros[0]['inm_doc_comprador_id'],
                seccion: 'inm_doc_comprador', style: 'danger',params: $params);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar button', data: $button_inm_doc_comprador_elimina_bd,
                    header: $header, ws: $ws);
            }

            $this->button_inm_doc_comprador_elimina_bd = $button_inm_doc_comprador_elimina_bd;
        }

        return $base;
    }

    public function asigna_ingresado(bool $header, bool $ws = false): array|stdClass
    {

        $filtro['inm_comprador.id']= $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'mts_terrenos',
            keys_selects:$keys_selects, place_holder: 'Metros de Terreno');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'mts_construidos',
            keys_selects:$keys_selects, place_holder: 'Metros de Construidos');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'valor_avaluo',
            keys_selects:$keys_selects, place_holder: 'Valor Avaluo');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $documento = $this->html->input_file(cols: 12,name: 'avaluo',row_upd:  new stdClass(),value_vacio:  false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento, header: $header,ws:  $ws);
        }

        $this->inputs->documento = $documento;

        $columns_ds = array('com_cliente_rfc','com_cliente_razon_social');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'com_cliente_id',
            keys_selects:$keys_selects, id_selected: $registro->registros[0]['com_cliente_id'], label: 'Cliente',
            columns_ds : $columns_ds,disabled: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }

        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_ingresado_bd = $this->obj_link->link_con_id(accion:'ingresado_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_comprador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_ingresado_bd,
                header: $header,ws:  $ws);
        }

        $this->link_ingresado_bd = $link_ingresado_bd;

        $button_solicitud_infonavit = $this->html->button_href(accion: 'solicitud_infonavit',
            etiqueta: 'Solicitud Infonavit', registro_id: $this->registro_id, seccion: 'inm_comprador',
            style: 'success');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar button',
                data: $button_solicitud_infonavit, header: $header, ws: $ws);
        }

        $this->button_solicitud_infonavit = $button_solicitud_infonavit;

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    final public function asigna_avaluo_bd(bool $header, bool $ws = false): array|stdClass{

        $this->link->beginTransaction();

        $retorno = (new \gamboamartin\inmuebles\controllers\_base())->init_retorno();
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de retorno', data: $retorno,
                header: true, ws: false);
        }

        $inm_comprador_id = $this->registro_id;
        $inm_co_acreditado_ins['nss'] = $_POST['nss'];
        $inm_co_acreditado_ins['curp'] = $_POST['curp'];
        $inm_co_acreditado_ins['rfc'] = $_POST['rfc'];
        $inm_co_acreditado_ins['apellido_paterno'] = $_POST['apellido_paterno'];
        $inm_co_acreditado_ins['apellido_materno'] = $_POST['apellido_materno'];
        $inm_co_acreditado_ins['nombre'] = $_POST['nombre'];
        $inm_co_acreditado_ins['lada'] = $_POST['lada'];
        $inm_co_acreditado_ins['numero'] = $_POST['numero'];
        $inm_co_acreditado_ins['celular'] = $_POST['celular'];
        $inm_co_acreditado_ins['genero'] = $_POST['genero'];
        $inm_co_acreditado_ins['correo'] = $_POST['correo'];
        $inm_co_acreditado_ins['nombre_empresa_patron'] = $_POST['nombre_empresa_patron'];
        $inm_co_acreditado_ins['nrp'] = $_POST['nrp'];
        $inm_co_acreditado_ins['lada_nep'] = $_POST['lada_nep'];
        $inm_co_acreditado_ins['numero_nep'] = $_POST['numero_nep'];
        $inm_co_acreditado_ins['extension_nep'] = $_POST['extension_nep'];

        $result = (new inm_comprador(link: $this->link))->asigna_nuevo_co_acreditado_bd(
            inm_comprador_id: $inm_comprador_id, inm_co_acreditado: $inm_co_acreditado_ins);

        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos',data:  $result,header:  $header,ws:  $ws);
        }
        $this->link->commit();


        $out = (new \gamboamartin\inmuebles\controllers\_base())->out(controlador: $this,header:  $header,
            result:  $result,retorno:  $retorno, ws: $ws);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar salida', data: $out,
                header: true, ws: false);
        }

        $result->siguiente_view = $retorno->siguiente_view;


        return $result;
    }

    public function asigna_en_avaluo(bool $header, bool $ws = false): array|stdClass
    {

        $filtro_rel['inm_comprador.id'] = $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente($this->link))->filtro_and(filtro: $filtro_rel);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $registro_valuador = (new inm_rel_cliente_valuador($this->link))->filtro_and(filtro: $filtro_rel);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $inm_valuador_id = -1;
        if($registro_valuador->n_registros > 0){
            $inm_valuador_id = $registro_valuador->registros[0]['inm_valuador_id'];
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_valuador_descripcion');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_valuador_id',
            keys_selects: $keys_selects, id_selected: $inm_valuador_id, label: 'Valuador', columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $columns_ds = array('com_cliente_rfc','com_cliente_razon_social');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'com_cliente_id',
            keys_selects:$keys_selects, id_selected: $registro->registros[0]['com_cliente_id'], label: 'Cliente',
            columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] == 'proceso_cliente') {
            $params = array('pestana_general_actual' => 'pestanageneral2', 'pestana_actual' => 'pestana3');
        }
        $link_inm_rel_cliente_valuador_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,
            seccion: 'inm_rel_cliente_valuador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_inm_rel_cliente_valuador_alta_bd,
                header: $header,ws:  $ws);
        }
        $this->link_inm_rel_cliente_valuador_alta_bd = $link_inm_rel_cliente_valuador_alta_bd;

        /*$this->link_inm_rel_cliente_valuador_alta_bd = $link_inm_rel_cliente_valuador_alta_bd;
        $filtro['com_cliente.id'] = $registro->registros[0]['com_cliente_id'];
        $inm_clientes_valuadores = (new inm_rel_cliente_valuador($this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener compradores',data:  $inm_clientes_valuadores,
                header: $header,ws:  $ws);
        }
        $this->inm_clientes_valuadores = (array)$inm_clientes_valuadores->registros;*/

        $button_solicitud_avaluo = $this->html->button_href(accion: 'solicitud_avaluo',
            etiqueta: 'Solicitud Avaluo', registro_id: $this->registro_id, seccion: 'inm_comprador',
            style: 'success');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar button',
                data: $button_solicitud_avaluo, header: $header, ws: $ws);
        }

        $this->button_solicitud_avaluo = $button_solicitud_avaluo;


        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $base;
    }

    public function autorizado_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->existe_status_comprador(
            inm_comprador_id: $this->registro_id, values: array('11'));
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener bitacora status comp', data: $inm_bit_comp,
                header: $header, ws: $ws);
        }

        if ($inm_bit_comp->n_registros > 0) {
            return $this->retorno_error(mensaje: 'Error el cliente ya esta cancelado', data: $inm_bit_comp,
                header: $header, ws: $ws);
        }

        $filtro_exi['inm_comprador.id'] = $this->registro_id;
        $filtro_exi['inm_status_comprador.id'] = 6;
        $existe = (new inm_bitacora_status_comprador(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if (!$existe) {
            $registro = array();
            $registro['inm_comprador_id'] = $this->registro_id;
            $registro['inm_status_comprador_id'] = 6;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_comprador = (new inm_bitacora_status_comprador(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_comprador,
                    header: $header, ws: $ws);
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 39;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if (!$existe) {
            if (trim($_FILES['sic']['name']) !== '') {
                $_FILES['documento'] = $_FILES['sic'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro_id;
                $registro['doc_tipo_documento_id'] = 39;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador,
                        header: $header, ws: $ws);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 40;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if (!$existe) {
            if (trim($_FILES['constancia_credito']['name']) !== '') {
                $_FILES['documento'] = $_FILES['constancia_credito'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro_id;
                $registro['doc_tipo_documento_id'] = 40;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador,
                        header: $header, ws: $ws);
                }
            }
        }

        $registro_mod = array();
        $registro_mod['inm_notaria_id'] = $_POST['inm_notaria_id'];
        $r_mod_comprador = (new inm_comprador(link: $this->link))->modifica_bd(
            registro: $registro_mod,id: $this->registro_id);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_mod_comprador,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'proceso_cliente', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }


    /**
     * Integra los campos para las vistas de frontend base
     * @return array
     * @version 1.106.1
     */
    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion', 'descuento_pension_alimenticia_dh',
            'descuento_pension_alimenticia_fc','monto_credito_solicitado_dh','monto_ahorro_voluntario','nss','curp',
            'rfc','apellido_paterno','apellido_materno','nombre','calle','numero_exterior','numero_interior','telefono',
            'nombre_empresa_patron','nrp_nep','lada_nep','numero_nep','extension_nep','lada_com','numero_com',
            'cel_com','correo_com','sub_cuenta','monto_final','descuento','puntos', 'telefono_casa',
            'correo_empresa','mts_construidos','mts_terrenos','metros_construidos','metros_terreno', 'valor_avaluo',
            'numero_escritura','isr','nombre_beneficiario','monto_transferencia','efectivo','monto','numero_cheque',
            'transferencia','serie','folio','exportacion','observaciones_factura','descripcion_factura','unidad',
            'cuenta_predial','cantidad','valor_unitario','subtotal','descuento_factura','total',
            'observaciones_nota_credito', 'valor_unitario_nota_credito','descripcion_nota_credito',
            'observaciones_complemento_pago', 'valor_unitario_complemento_pago','descripcion_complemento_pago',
            'numero_credito', 'pago_precio_compra_venta','pago_parcial_precio_compra_venta','pago_propio_peculio',
            'pago_cuv','uuid', 'etapa','area_empresa');
        $keys->selects = array();
        $keys->fechas = array('fecha_factura');
        $keys->textareas = array('direccion_empresa');

        $init_data = array();
        $init_data['inm_producto_infonavit'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_credito'] = "gamboamartin\\inmuebles";
        $init_data['inm_attr_tipo_credito'] = "gamboamartin\\inmuebles";
        $init_data['inm_destino_credito'] = "gamboamartin\\inmuebles";
        $init_data['inm_plazo_credito_sc'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_discapacidad'] = "gamboamartin\\inmuebles";
        $init_data['inm_persona_discapacidad'] = "gamboamartin\\inmuebles";
        $init_data['inm_estado_civil'] = "gamboamartin\\inmuebles";
        $init_data['inm_institucion_hipotecaria'] = "gamboamartin\\inmuebles";
        $init_data['inm_sindicato'] = "gamboamartin\\inmuebles";
        $init_data['inm_nacionalidad'] = "gamboamartin\\inmuebles";
        $init_data['inm_ocupacion'] = "gamboamartin\\inmuebles";
        $init_data['inm_valuador'] = "gamboamartin\\inmuebles";
        $init_data['com_cliente'] = "gamboamartin\\comercial";
        $init_data['adm_estado_civil'] = "gamboamartin\\administrador";
        $init_data['inm_tipo_cheque'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_gasto'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_exento'] = "gamboamartin\\inmuebles";
        $init_data['inm_notaria'] = "gamboamartin\\inmuebles";
        $init_data['inm_conf_cuenta_notaria'] = "gamboamartin\\inmuebles";

        $init_data['bn_cuenta'] = "gamboamartin\\banco";

        $init_data['com_sucursal'] = "gamboamartin\\comercial";
        $init_data['com_agente'] = "gamboamartin\\comercial";
        $init_data['fc_csd'] = "gamboamartin\\facturacion";
        $init_data['cat_sat_tipo_de_comprobante'] = "gamboamartin\\cat_sat";
        $init_data['com_tipo_cambio'] = "gamboamartin\\comercial";
        $init_data['com_producto'] = "gamboamartin\\comercial";
        $init_data['cat_sat_obj_imp'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_conf_imps'] = "gamboamartin\\cat_sat";
        $init_data['fc_factura'] = "gamboamartin\\facturacion";
        $init_data['org_sucursal'] = "gamboamartin\\organigrama";

        $init_data = (new _base_paquete())->init_data_domicilio(init_data: $init_data);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $init_data);
        }

        $init_data['cat_sat_regimen_fiscal'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_moneda'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_forma_pago'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_metodo_pago'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_uso_cfdi'] = "gamboamartin\\cat_sat";
        $init_data['com_tipo_cliente'] = "gamboamartin\\comercial";
        $init_data['cat_sat_tipo_persona'] = "gamboamartin\\cat_sat";
        $init_data['bn_cuenta'] = "gamboamartin\\banco";
        $init_data['adm_estado_civil'] = "gamboamartin\\administrador";
        $init_data['inm_tipo_credito'] = "gamboamartin\\inmuebles";

        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    public function cancelado(bool $header, bool $ws = false): array|stdClass
    {
        $template = parent::modifica(header: false); // TODO: Change the autogenerated stub
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar template', data: $template, header: $header, ws: $ws);
        }

        $columns_ds = array('inm_comprador_nss', 'inm_comprador_nombre', 'inm_comprador_apellido_paterno',
            'inm_comprador_apellido_materno');

        foreach ($columns_ds as $index => $key){
            if(trim($this->registro[$key]) === ''){
                unset($columns_ds[$index]);
            }
        }

        $filtro['inm_comprador.id'] = $this->registro_id;
        $inm_prospecto_id = (new inm_comprador_html(html: $this->html_base))->select_inm_comprador_id(
            cols: 12, con_registros: true, id_selected: $this->registro_id, link: $this->link, columns_ds: $columns_ds,
            filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_prospecto_id, header: $header, ws: $ws);
        }
        $this->inputs->inm_comprador_seleccionado_id  = $inm_prospecto_id;

        $filtro_status['inm_status_comprador.es_cancelado'] = 'activo';
        $r_inm_status_comprador = (new inm_status_comprador(link: $this->link))->filtro_and(filtro: $filtro_status);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $r_inm_status_comprador,
                header: $header, ws: $ws);
        }

        $id_selected = -1;
        if ($r_inm_status_comprador->n_registros > 0) {
            $id_selected = $r_inm_status_comprador->registros[0]['inm_status_comprador_id'];
        }
        $columns_ds = array();
        $columns_ds[] = 'inm_status_comprador_descripcion';

        $inm_status_comprador_id = (new inm_status_comprador_html(html: $this->html_base))->select_inm_status_comprador_id(
            cols: 6, con_registros: true, id_selected: $id_selected, link: $this->link, columns_ds: $columns_ds,
            filtro: $filtro_status, label: 'Status comprador');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $inm_status_comprador_id,
                header: $header, ws: $ws);
        }
        $this->inputs->inm_status_comprador_id = $inm_status_comprador_id;

        $filtro_status['inm_comprador.id'] = $this->registro_id;
        $r_inm_bitacora = (new inm_bitacora_status_comprador(link: $this->link))->filtro_and(filtro: $filtro_status);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $r_inm_bitacora,
                header: $header, ws: $ws);
        }

        $hoy = date('Y-m-d\TH:i:s');
        $observaciones = "";
        if($r_inm_bitacora->n_registros){
            $hoy = $r_inm_bitacora->registros[0]['inm_bitacora_status_comprador_fecha_status'];
            $observaciones = $r_inm_bitacora->registros[0]['inm_bitacora_status_comprador_observaciones'];
        }
        $fecha = $this->html->input_fecha(cols: 6, row_upd: new stdClass(), value_vacio: false, disabled: true,
            name: 'fecha_status', value: $hoy, value_hora: true);
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

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $inm_comprador_id,  header: $header, ws: $ws);
        }

        $this->inputs->inm_comprador_id = $inm_comprador_id;
        
        $params = array();
        $link_cancelado_bd = $this->obj_link->link_con_id(accion:'cancelado_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_comprador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_cancelado_bd,
                header: $header,ws:  $ws);
        }

        $this->link_cancelado_bd = $link_cancelado_bd;

        return $template;
    }

    public function cancelado_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $in_comp = array();
        $in_comp['llave'] = 'inm_status_comprador.id';
        $in_comp['values'] = array('9','10');

        $filtro_bit['inm_comprador.id'] = $_POST['inm_comprador_id'];
        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->filtro_and(filtro: $filtro_bit,
            in: $in_comp);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener etapas no validas', data: $inm_bit_comp,
                header: $header, ws: $ws);
        }

        if ($inm_bit_comp->n_registros > 0) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error esta en una etapa no habilitada para cancelar',
                data: $inm_bit_comp, header: $header, ws: $ws);
        }

        $imp_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->existe_imp_rel_ubi_comp(
            inm_comprador_id: $_POST['inm_comprador_id']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener imp_rel_ubi_comp', data: $imp_rel_ubi_comp,
                header: $header,ws: $ws);
        }

        if($imp_rel_ubi_comp['existe']){
            $registro_rel['status'] = 'inactivo';

            $imp_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->modifica_bd(
                registro: $registro_rel, id: $imp_rel_ubi_comp['registro']['inm_rel_ubi_comp_id']);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al obtener imp_rel_ubi_comp', data: $imp_rel_ubi_comp,
                    header: $header,ws: $ws);
            }
        }

        $registro = array();
        $registro['inm_comprador_id'] = $_POST['inm_comprador_id'];
        $registro['inm_status_comprador_id'] = $_POST['inm_status_comprador_id'];
        $registro['fecha_status'] = date('Y-m-d\TH:i:s');
        $registro['observaciones'] = $_POST['observaciones'];
        $r_inm_bitacora_status_comprador = (new inm_bitacora_status_comprador(link: $this->link))->alta_registro(
            registro: $registro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_comprador,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        $params = array();
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'lista', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }


    public function co_acreditados(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = (new _keys_selects())->base_co_acreditado(controler: $this,function: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }


        return $r_modifica;
    }

    public function cotejado_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->existe_status_comprador(
            inm_comprador_id: $this->registro_id, values: array('11'));
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener bitacora status comp',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        if ($inm_bit_comp->n_registros > 0) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error el cliente ya esta cancelado',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        $filtro_exi['inm_comprador.id'] = $this->registro_id;
        $filtro_exi['inm_status_comprador.id'] = 9;
        $existe = (new inm_bitacora_status_comprador(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $registro = array();
            $registro['inm_comprador_id'] = $this->registro_id;
            $registro['inm_status_comprador_id'] = 9;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_comprador = (new inm_bitacora_status_comprador(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_comprador,
                    header: $header, ws: $ws);
            }
        }

        $this->link->commit();

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'proceso_cliente', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }
    public function cobrado_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->existe_status_comprador(
            inm_comprador_id: $this->registro_id, values: array('11'));
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener bitacora status comp',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        if ($inm_bit_comp->n_registros > 0) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error el cliente ya esta cancelado',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        if(isset($_POST['inm_tipo_gasto_id']) && trim($_POST['inm_tipo_gasto_id']) === '1') {
            $registro = array();
            $registro['inm_comprador_id'] = $this->registro_id;
            $registro['monto'] = $_POST['monto'];
            $registro['nombre_beneficiario'] = $_POST['nombre_beneficiario'];
            $registro['inm_tipo_cheque_id'] = $_POST['inm_tipo_cheque_id'];
            $registro['bn_cuenta_id'] = $_POST['bn_cuenta_sl_id'];
            $registro['numero_cheque'] = '';

            if(isset($_POST['numero_cheque'])){
                $registro['numero_cheque'] = $_POST['numero_cheque'];
            }

            $r_inm_cheque = (new inm_rel_cheque_comprador(link: $this->link))->alta_registro(registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_cheque,
                    header: $header, ws: $ws);
            }
        }

        if(isset($_POST['inm_tipo_gasto_id']) && trim($_POST['inm_tipo_gasto_id']) === '2') {
            $registro = array();
            $registro['inm_comprador_id'] = $this->registro_id;
            $registro['monto'] = $_POST['monto_transferencia'];
            $registro['nombre_beneficiario'] = $_POST['nombre_beneficiario'];
            $registro['bn_cuenta_id'] = $_POST['bn_cuenta_sl_trs_id'];

            $registro['transferencia'] = '';

            if(isset($_POST['transferencia'])){
                $registro['transferencia'] = $_POST['transferencia'];
            }
            $r_inm_transferencia = (new inm_rel_transferencia_comprador(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_transferencia,
                    header: $header, ws: $ws);
            }
        }

        if(isset($_POST['inm_tipo_gasto_id']) && trim($_POST['inm_tipo_gasto_id']) === '3') {
            $registro = array();
            $registro['inm_comprador_id'] = $this->registro_id;
            $registro['monto'] = $_POST['efectivo'];
            $registro['nombre_beneficiario'] = $_POST['nombre_beneficiario'];
            $r_inm_efectivo = (new inm_rel_efectivo_comprador(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_efectivo,
                    header: $header, ws: $ws);
            }
        }

        if(isset($_POST['alta_documento']) && trim($_POST['alta_documento']) !== '') {
            $inm_doc_comprador =  new inm_doc_comprador(link: $this->link);

            $tipos_documento_cheque = array();
            if(isset($_FILES['documentos_cheques'])) {
                foreach ($_FILES['documentos_cheques']['name'] as $costo => $tipo_documento) {
                    foreach ($tipo_documento as $key => $value) {
                        foreach ($value as $nombre_documento) {
                            $tipos_documento_cheque[$costo][$key]['name'] = $nombre_documento;
                        }
                    }
                }

                foreach ($_FILES['documentos_cheques']['tmp_name'] as $costo => $tipo_documento) {
                    foreach ($tipo_documento as $key => $value) {
                        foreach ($value as $nombre_documento) {
                            $tipos_documento_cheque[$costo][$key]['tmp_name'] = $nombre_documento;
                        }
                    }
                }
            }

            $tipos_documento_transferencia = array();
            if(isset($_FILES['documentos_transferencias'])) {
                foreach ($_FILES['documentos_transferencias']['name'] as $costo => $tipo_documento) {
                    foreach ($tipo_documento as $key => $value) {
                        foreach ($value as $nombre_documento) {
                            $tipos_documento_transferencia[$costo][$key]['name'] = $nombre_documento;
                        }
                    }
                }

                foreach ($_FILES['documentos_transferencias']['tmp_name'] as $costo => $tipo_documento) {
                    foreach ($tipo_documento as $key => $value) {
                        foreach ($value as $nombre_documento) {
                            $tipos_documento_transferencia[$costo][$key]['tmp_name'] = $nombre_documento;
                        }
                    }
                }
            }

            $r_rel_doc_ubi_che = array();
            foreach ($tipos_documento_cheque AS $cheque_id => $doc) {
                $valor_documento = array();
                foreach ($doc AS $tipo_documento_id => $value) {
                    $valor_documento['name'] = $value['name'];
                    $valor_documento['tmp_name'] = $value['tmp_name'];

                    if ($valor_documento['name'] !== '' && $valor_documento['tmp_name'] !== '') {
                        $registro = array();
                        $registro['doc_tipo_documento_id'] = $tipo_documento_id;
                        $registro['inm_comprador_id'] = $this->registro_id;

                        $_FILES = array();
                        $_FILES['documento'] = $valor_documento;
                        $result = $inm_doc_comprador->alta_registro(registro: $registro);
                        if (errores::$error) {
                            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $result, header: $header,
                                ws: $ws);
                        }

                        $registro_rel = array();
                        $registro_rel['inm_cheque_id'] = $cheque_id;
                        $registro_rel['inm_doc_comprador_id'] = $result->registro_id;
                        $r_rel_doc_ubi_che = (new inm_rel_doc_cheque(link: $this->link))->alta_registro(registro: $registro_rel);
                        if (errores::$error) {
                            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_rel_doc_ubi_che,
                                header: $header, ws: $ws);
                        }
                    }
                }
            }

            $r_rel_doc_ubi_trs = array();
            foreach ($tipos_documento_transferencia AS $transferencia_id => $doc) {
                $valor_documento = array();
                foreach ($doc AS $tipo_documento_id => $value) {
                    $valor_documento['name'] = $value['name'];
                    $valor_documento['tmp_name'] = $value['tmp_name'];

                    if ($valor_documento['name'] !== '' && $valor_documento['tmp_name'] !== '') {
                        $registro = array();
                        $registro['doc_tipo_documento_id'] = $tipo_documento_id;
                        $registro['inm_comprador_id'] = $this->registro_id;

                        $_FILES = array();
                        $_FILES['documento'] = $valor_documento;
                        $result = $inm_doc_comprador->alta_registro(registro: $registro);
                        if (errores::$error) {
                            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $result, header: $header,
                                ws: $ws);
                        }

                        $registro_rel = array();
                        $registro_rel['inm_transferencia_id'] = $transferencia_id;
                        $registro_rel['inm_doc_comprador_id'] = $result->registro_id;
                        $r_rel_doc_ubi_trs = (new inm_rel_doc_transferencia(link: $this->link))->alta_registro(registro: $registro_rel);
                        if (errores::$error) {
                            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_rel_doc_ubi_trs,
                                header: $header, ws: $ws);
                        }
                    }
                }
            }
        }

        if(isset($_POST['avanza_etapa']) && trim($_POST['avanza_etapa']) !== '') {
            $filtro_exi['inm_comprador.id'] = $this->registro_id;
            $filtro_exi['inm_status_comprador.id'] = 10;
            $existe = (new inm_bitacora_status_comprador(link: $this->link))->existe(filtro: $filtro_exi);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                    header: $header, ws: $ws);
            }

            if (!$existe) {
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro_id;
                $registro['inm_status_comprador_id'] = 10;
                $registro['fecha_status'] = date('Y-m-d\TH:i:s');
                $r_inm_bitacora_status_comprador = (new inm_bitacora_status_comprador(link: $this->link))->alta_registro(
                    registro: $registro);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_comprador,
                        header: $header, ws: $ws);
                }
            }
        }

        $this->link->commit();

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'proceso_cliente', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }

    final public function documentos(bool $header, bool $ws = false): array
    {

        if(isset($_GET['accion']) && $_GET['accion'] == 'documentos') {
            $template = $this->modifica(header: false);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
            }
        }

        $inm_conf_docs_comprador = (new _inm_comprador())->integra_inm_documentos(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar buttons',data:  $inm_conf_docs_comprador, header: $header,ws:  $ws);
        }

        $temp = array();
        foreach ($inm_conf_docs_comprador as $docs){
            $res = "<tr>
            <td>$docs[doc_tipo_documento_descripcion]</td>
            <td>$docs[descarga]</td>
            <td>$docs[vista_previa]</td>
            <td>$docs[descarga_zip]</td>
            <td>$docs[elimina_bd]</td>
            </tr>";
            if(isset($docs['subir_documento'])){
                $res = "<tr>
                <td>$docs[doc_tipo_documento_descripcion]</td>
                <td colspan='4'>$docs[subir_documento]</td>
                </tr>";
            }
            $temp[] = $res;
        }

        $this->inm_conf_docs_comprador = $temp;

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] === 'proceso_cliente') {
            $params = array('pestana_general_actual' => 'pestanageneral1',
                'pestana_actual' => 'pestanacliente2');
        }
        $link_documento_bd = $this->obj_link->link_con_id(
            accion: 'documentos_bd', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_documento_bd, header: $header, ws: $ws);
        }

        $this->link_documento_bd = $link_documento_bd;

        return $inm_conf_docs_comprador;

    }

    public function documentos_bd(bool $header, bool $ws = false): array|stdClass{

        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->existe_status_comprador(
            inm_comprador_id: $this->registro_id, values: array('11'));
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener bitacora status comp',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        if ($inm_bit_comp->n_registros > 0) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error el cliente ya esta cancelado',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        $inm_doc_comprador =  new inm_doc_comprador(link: $this->link);

        $names = array();
        foreach ($_FILES['documentos']['name'] as $key => $foto){
            $names[$key]['name'] = $foto;
        }

        foreach ($_FILES['documentos']['tmp_name'] as $key => $foto){
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
                    $registro['inm_comprador_id'] = $this->registro_id;
                    $_FILES['documento'] = $valor;
                    $result = $inm_doc_comprador->alta_registro(registro: $registro);
                    if (errores::$error) {
                        return $this->retorno_error(mensaje: 'Error al insertar datos', data: $result, header: $header, ws: $ws);
                    }
                }
            }
        }

        $accion = 'documentos';
        if(isset($_POST['btn_action_next'])){
            $accion = $_POST['btn_action_next'];
        }

        $params = array();
        if (isset($_GET['pestana_general_actual'])) {
            $params = array('pestana_general_actual' => 'pestanageneral1', 'pestana_actual' => $_GET['pestana_actual']);
        }        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: $accion, link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }
        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $result;
    }

    public function etapa(bool $header, bool $ws = false): array|stdClass
    {
        if(isset($_GET['accion']) && $_GET['accion'] == 'etapa') {
            $template = parent::modifica(header: false); // TODO: Change the autogenerated stub
            if (errores::$error) {
                $this->retorno_error(mensaje: 'Error al generar template', data: $template, header: $header, ws: $ws);
            }
        }

        $columns_ds[] = 'inm_status_comprador_descripcion';

        $inm_status_comprador_id = (new inm_status_comprador_html(html: $this->html_base))->select_inm_status_comprador_id(
            cols: 6, con_registros: true, id_selected: -1, link: $this->link, columns_ds: $columns_ds,
            filtro: array('inm_status_comprador.es_cancelado'=>'inactivo'), label: 'Status comprador');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $inm_status_comprador_id, header: $header, ws: $ws);
        }
        $this->inputs->inm_status_comprador_id = $inm_status_comprador_id;

        $hoy = date('Y-m-d\TH:i:s');
        $fecha = $this->html->input_fecha(cols: 6, row_upd: new stdClass(), value_vacio: false, name: 'fecha_status',
            value: $hoy, value_hora: true);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar input fecha', data: $fecha, header: $header, ws: $ws);
        }

        $this->inputs->fecha_etapa = $fecha;

        $observaciones = $this->html->input_text(cols: 12, disabled: false, name: 'observaciones', place_holder: 'Observaciones',
            row_upd: new stdClass(), value_vacio: false, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $observaciones,  header: $header, ws: $ws);
        }

        $this->inputs->observaciones = $observaciones;

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $inm_comprador_id,  header: $header, ws: $ws);
        }

        $this->inputs->inm_comprador_id = $inm_comprador_id;

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] == 'proceso_cliente') {
            $params = array('pestana_general_actual' => 'pestanageneral1', 'pestana_actual' => 'pestanacliente3');
        }
        $link_alta_bitacora= $this->obj_link->link_alta_bd(link: $this->link, seccion: 'inm_bitacora_status_comprador',
            params: $params);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar link', data: $link_alta_bitacora, header: $header,
                ws: $ws);
        }

        $this->link_alta_bitacora = $link_alta_bitacora;

        $etapas = (new inm_comprador(link: $this->link))->status_comprador(inm_comprador_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener etapas', data: $etapas, header: $header, ws: $ws);
        }

        $this->etapas = $etapas;

        $retorno = 'etapa';
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

    public function exportar_xls(bool $header, bool $ws = false)
    {
        $nombre_hojas = array('Clientes');
        $keys_hojas = array();

        $registros = $this->result_inm_prosp();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_prospecto_ubicacion', data: $registros, header: $header,
                ws: $ws);
        }

        $ths[] = array('etiqueta'=>'ID', 'campo'=>'inm_comprador_id');
        $ths[] = array('etiqueta'=>'Cliente', 'campo'=>'inm_comprador_razon_social');
        $ths[] = array('etiqueta'=>'Ubicacion', 'campo'=>'inm_ubicacion_completa');
        $ths[] = array('etiqueta'=>'Agente', 'campo'=>'com_agente_descripcion');
        $ths[] = array('etiqueta'=>'NSS', 'campo'=>'inm_comprador_nss');
        $ths[] = array('etiqueta'=>'Mi Cuenta Infonavit', 'campo'=>'inm_comprador_password_mi_cuenta_infonavit');
        $ths[] = array('etiqueta'=>'Numero Credito', 'campo'=>'inm_comprador_numero_credito');
        $ths[] = array('etiqueta'=>'Status Cliente', 'campo'=>'inm_status_comprador_descripcion');

        /*$keys = array();
        foreach ($ths as $data_th) {
            $keys[] = $data_th['campo'];
        }*/

        $keys_hojas['Clientes'] = new stdClass();
        $keys_hojas['Clientes']->keys = $ths;
        $keys_hojas['Clientes']->registros = $registros->registros;

        $moneda = array();
        $totales_hoja = new stdClass();
        //$totales_hoja->prospecto_ubicacions = (array)$registros->totales;
        $xls = (new exportador())->genera_xls(header: $header, name: 'Clientes', nombre_hojas: $nombre_hojas,
            keys_hojas: $keys_hojas, path_base: $this->path_base, moneda: $moneda/*, totales_hoja: $totales_hoja*/);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener xls', data: $xls, header: $header, ws: $ws);
        }

    }

    public function formato_excel(bool $header, bool $ws = false): array|stdClass
    {
        $template = parent::modifica(header: false); // TODO: Change the autogenerated stub
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar template', data: $template, header: $header, ws: $ws);
        }

        return $this->inputs;
    }

    public function get_etapa_actual(bool $header, bool $ws = false){
        $in_comp = array();
        $in_comp['llave'] = 'inm_status_comprador.id';
        $in_comp['values'] = array('1','2','3','4','5','6','7','8','9','10');

        $filtro_bit['inm_comprador.id'] = $_POST['id'];
        $order = array('inm_bitacora_status_comprador.fecha_status'=>'DESC');
        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->filtro_and(filtro: $filtro_bit,
            in: $in_comp, order: $order);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener etapas no validas', data: $inm_bit_comp,
                header: $header, ws: $ws);
        }

        $ultimo_etapa = 'DETENIDO';
        if ($inm_bit_comp->n_registros > 0) {
            $ultimo_etapa = $inm_bit_comp->registros[0]['inm_status_comprador_descripcion'];
        }

        $pestanas = array("DETENIDO" => "pestana1", "ASIGNADO" => "pestana2", "EN AVALUO" => "pestana3",
            "POR INGRESAR" => "pestana4", "INGRESADO" => "pestana5", "AUTORIZADO" => "pestana6",
            "POR FIRMAR" => "pestana7", "ESCRITURADO" => "pestana8", "COTEJADO" => "pestana9",
            "COBRADO" => "pestana10", "CANCELADO"=> "sin_pestana");

        $pestana_anterior = $pestanas[$ultimo_etapa] ?? 'pestana1';

        $r_comprador = (new inm_comprador(link: $this->link))->registro(registro_id: $_POST['id']);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al obtener registro de comprador', data: $r_comprador,
                header: $header, ws: $ws);
        }

        $pestana_actual = '';
        foreach ($pestanas as $key => $value) {
            if($key === $r_comprador['inm_status_comprador_descripcion']){
                $pestana_actual = $value;

                if($key === 'CANCELADO'){
                    $pestana_actual = $pestana_anterior;
                }
            }
        }

        return $pestana_actual;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     * @version 1.40.0
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_comprador_id"]["titulo"] = "Id";
        $columns["inm_comprador_razon_social"]["titulo"] = "Nombre";
        $columns["inm_ubicacion_completa"]["titulo"] = "Ubicacion";
        $columns["com_agente_descripcion"]["titulo"] = "Agente";
        $columns["inm_comprador_comentarios"]["titulo"] = "Comentarios";
        $columns["inm_comprador_nss"]["titulo"] = "NSS";
        $columns["inm_comprador_monto_credito_solicitado_dh"]["titulo"] = "Precalificacion";
        $columns["inm_fecha_status"]["titulo"] = "Fecha Etapa";
        $columns["inm_status_comprador_descripcion"]["titulo"] = "Etapa Actual";

        $filtro = array("inm_comprador.id", 'inm_comprador_razon_social', 'inm_ubicacion_completa',
            'com_agente.descripcion', 'inm_comprador.nss', 'inm_status_comprador.descripcion', 'inm_fecha_status');

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;
        $datatables->menu_active = true;
        $datatables->order_sec = array('inm_status_comprador.id' => 'ASC','inm_comprador.id' => 'DESC');

        return $datatables;
    }

    public function ingresado_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->existe_status_comprador(
            inm_comprador_id: $this->registro_id, values: array('11'));
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener bitacora status comp',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        if ($inm_bit_comp->n_registros > 0) {
            return $this->retorno_error(mensaje: 'Error el cliente ya esta cancelado',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        $filtro_exi['inm_comprador.id'] = $this->registro_id;
        $filtro_exi['inm_status_comprador.id'] = 5;
        $existe = (new inm_bitacora_status_comprador(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $registro = array();
            $registro['inm_comprador_id'] = $this->registro_id;
            $registro['inm_status_comprador_id'] = 5;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_comprador = (new inm_bitacora_status_comprador(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_comprador,
                    header: $header, ws: $ws);
            }
        }

        $this->link->commit();

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'proceso_cliente', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }

    public function inputs_conyuge(bool $header, controlador_inm_comprador $controler, bool $ws = false){

        $conyuge = new stdClass();

        $existe_conyuge = false;
        if($controler->registro_id > 0) {
            $existe_conyuge = $controler->modelo->existe_conyuge(
                inm_comprador_id: $controler->registro_id);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al validar si existe conyuge', data: $existe_conyuge,
                    header: $header, ws:$ws);
            }
        }

        $row_upd = new stdClass();
        $row_upd->nombre = '';
        $row_upd->apellido_paterno = '';
        $row_upd->apellido_materno = '';
        $row_upd->fecha_nacimiento = '';
        $row_upd->curp = '';
        $row_upd->rfc = '';
        $row_upd->numero_credito = '';
        $row_upd->adeudo_hipoteca = '';
        $row_upd->telefono_casa = '';
        $row_upd->telefono_celular = '';
        $row_upd->dp_estado_id = -1;
        $row_upd->dp_municipio_id = -1;
        $row_upd->inm_nacionalidad_id = -1;
        $row_upd->inm_ocupacion_id = -1;
        if($existe_conyuge){
            $row_upd = $controler->modelo->inm_conyuge(columnas_en_bruto: true,
                inm_comprador_id: $controler->registro_id, link: $controler->link, retorno_obj: true);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al obtener datos de conyuge',data:  $row_upd,
                    header: $header, ws:$ws);
            }

            $dp_municipio_data = (new dp_municipio(link: $controler->link))->registro(
                registro_id: $row_upd->dp_municipio_id, columnas_en_bruto: true, retorno_obj: true);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al obtener datos  dp_municipio_data',
                    data: $dp_municipio_data, header: $header, ws:$ws);
            }
            $row_upd->dp_estado_id = $dp_municipio_data->dp_estado_id;

        }

        $nombre = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[nombre]',
            place_holder: 'Nombre', row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_nombre'),
            required: false, value: $row_upd->nombre);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $nombre,header: $header, ws:$ws);
        }

        $conyuge->nombre = $nombre;

        $apellido_paterno = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[apellido_paterno]',
            place_holder: 'Apellido Pat', row_upd: $row_upd, value_vacio: false,
            class_css: array('conyuge_apellido_paterno'), required: false, value: $row_upd->apellido_paterno);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $apellido_paterno,header: $header,
                ws:$ws);
        }

        $conyuge->apellido_paterno = $apellido_paterno;

        $apellido_materno = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[apellido_materno]',
            place_holder: 'Apellido Mat', row_upd: $row_upd, value_vacio: false,
            class_css: array('conyuge_apellido_materno'), required: false, value: $row_upd->apellido_materno);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $apellido_materno,header: $header,
                ws:$ws);
        }

        $conyuge->apellido_materno = $apellido_materno;

        $curp = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[curp]', place_holder: 'CURP',
            row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_curp'), required: false,
            value: $row_upd->curp);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $curp,header: $header, ws:$ws);
        }

        $conyuge->curp = $curp;

        $rfc = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[rfc]', place_holder: 'RFC',
            row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_rfc'), required: false,
            value: $row_upd->rfc);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $rfc,header: $header, ws:$ws);
        }

        $conyuge->rfc = $rfc;

        $numero_credito = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[numero_credito]',
            place_holder: 'Numero Credito', row_upd: $row_upd, value_vacio: false,
            class_css: array('conyuge_numero_credito'), required: false, value: $row_upd->numero_credito);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $numero_credito,header: $header, ws:$ws);
        }

        $conyuge->numero_credito = $numero_credito;

        $adeudo_hipoteca = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[adeudo_hipoteca]',
            place_holder: 'Adeudo Hipoteca', row_upd: $row_upd, value_vacio: false,
            class_css: array('conyuge_adeudo_hipoteca'), required: false, value: $row_upd->adeudo_hipoteca);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $adeudo_hipoteca,header: $header, ws:$ws);
        }

        $conyuge->adeudo_hipoteca = $adeudo_hipoteca;

        $telefono_casa = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[telefono_casa]',
            place_holder: 'Tel Casa', row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_telefono_casa'),
            required: false, value: $row_upd->telefono_casa);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $telefono_casa,header: $header,
                ws:$ws);
        }

        $conyuge->telefono_casa = $telefono_casa;

        $telefono_celular = $controler->html->input_text(cols: 2, disabled: false, name: 'conyuge[telefono_celular]',
            place_holder: 'Cel', row_upd: $row_upd, value_vacio: false, class_css: array('conyuge_telefono_celular'),
            required: false, value: $row_upd->telefono_celular);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $telefono_celular,header: $header,
                ws:$ws);
        }

        $conyuge->telefono_celular = $telefono_celular;

        $modelo = new dp_estado(link: $controler->link);
        $dp_estado_id = $controler->html->select_catalogo(cols: 2, con_registros: true,
            id_selected: $row_upd->dp_estado_id, modelo: $modelo, id_css: 'conyuge_dp_estado_id',
            label: 'Estado Nac', name: 'conyuge[dp_estado_id]');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $dp_estado_id,header: $header, ws:$ws);
        }

        $conyuge->dp_estado_id = $dp_estado_id;

        //print_r($dp_estado_id);exit;
        $modelo = new dp_municipio(link: $controler->link);
        $dp_municipio_id = $controler->html->select_catalogo(cols: 2, con_registros: true,
            id_selected: $row_upd->dp_municipio_id, modelo: $modelo,
            filtro: array('dp_estado.id'=>$row_upd->dp_estado_id), id_css: 'conyuge_dp_municipio_id',
            label: 'Municipio Nac', name: 'conyuge[dp_municipio_id]');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $dp_municipio_id,header: $header,
                ws:$ws);
        }

        $conyuge->dp_municipio_id = $dp_municipio_id;

        $modelo = new inm_nacionalidad(link: $controler->link);
        $inm_nacionalidad_id = $controler->html->select_catalogo(cols: 2, con_registros: true,
            id_selected: $row_upd->inm_nacionalidad_id, modelo: $modelo, label: 'Nacionalidad',
            name: 'conyuge[inm_nacionalidad_id]');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $inm_nacionalidad_id,header: $header,
                ws:$ws);
        }

        $conyuge->inm_nacionalidad_id = $inm_nacionalidad_id;

        $modelo = new inm_ocupacion(link: $controler->link);
        $inm_ocupacion_id = $controler->html->select_catalogo(cols: 2, con_registros: true,
            id_selected: $row_upd->inm_ocupacion_id, modelo: $modelo, label: 'Ocupacion',
            name: 'conyuge[inm_ocupacion_id]');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $inm_ocupacion_id,header: $header,
                ws:$ws);
        }

        $conyuge->inm_ocupacion_id = $inm_ocupacion_id;

        $fecha_nacimiento = $controler->html->input_fecha(cols: 2, row_upd: $row_upd,
            value_vacio: false, name: 'conyuge[fecha_nacimiento]', place_holder: 'Fecha Nac', required: false,
            value: $row_upd->fecha_nacimiento);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener fecha_nacimiento',data:  $fecha_nacimiento,
                header: $header, ws:$ws);
        }

        $conyuge->fecha_nacimiento = $fecha_nacimiento;

        return $conyuge;
    }


    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'descuento_pension_alimenticia_dh',
            keys_selects:$keys_selects, place_holder: 'Desc. Pension Alimenticia Derechohabiente');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'descuento_pension_alimenticia_fc',
            keys_selects:$keys_selects, place_holder: 'Desc. Pension Alimenticia Familiar/Corresidente');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'monto_credito_solicitado_dh',
            keys_selects:$keys_selects, place_holder: 'Monto Credito Solicitado Derechohabiente');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'monto_ahorro_voluntario',
            keys_selects:$keys_selects, place_holder: 'Monto Ahorro Voluntario');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new _keys_selects())->keys_base_cliente(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'calle',
            keys_selects:$keys_selects, place_holder: 'Calle');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'numero_exterior',
            keys_selects:$keys_selects, place_holder: 'Exterior');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'numero_interior',
            keys_selects:$keys_selects, place_holder: 'Interior', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'telefono',
            keys_selects:$keys_selects, place_holder: 'Telefono');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'lada_com', keys_selects:$keys_selects,
            place_holder: 'Lada',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects['lada_com']->regex = $this->validacion->patterns['lada_html'];

        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'numero_com',
            keys_selects:$keys_selects, place_holder: 'Numero',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects['numero_com']->regex = $this->validacion->patterns['tel_sin_lada_html'];

        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'cel_com',
            keys_selects:$keys_selects, place_holder: 'Celular',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects['cel_com']->regex = $this->validacion->patterns['telefono_mx_html'];

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'genero',
            keys_selects:$keys_selects, place_holder: 'Genero');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'correo_com',
            keys_selects:$keys_selects, place_holder: 'Correo',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects['correo_com']->regex = $this->validacion->patterns['correo_html_base'];

        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'sub_cuenta',
            keys_selects:$keys_selects, place_holder: 'Sub Cuenta');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'monto_final',
            keys_selects:$keys_selects, place_holder: 'Monto Final');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'descuento',
            keys_selects:$keys_selects, place_holder: 'Descuento');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3,key: 'puntos',
            keys_selects:$keys_selects, place_holder: 'Puntos');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'serie',
            keys_selects:$keys_selects, place_holder: 'Serie', required: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'folio',
            keys_selects:$keys_selects, place_holder: 'Folio',required: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'exportacion',
            keys_selects:$keys_selects, place_holder: 'Exportacion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'fecha_factura',
            keys_selects:$keys_selects, place_holder: 'Fecha Factura',disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'observaciones_factura',
            keys_selects:$keys_selects, place_holder: 'Observaciones Factura',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'observaciones_nota_credito',
            keys_selects:$keys_selects, place_holder: 'Observaciones',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'observaciones_complemento_pago',
            keys_selects:$keys_selects, place_holder: 'Observaciones',required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'unidad',
            keys_selects: $keys_selects, place_holder: 'Unidad', required: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'cantidad',
            keys_selects:$keys_selects, place_holder: 'Cantidad');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'valor_unitario',
            keys_selects:$keys_selects, place_holder: 'Valor Factura');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'subtotal',
            keys_selects:$keys_selects, place_holder: 'Subtotal', disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'descuento_factura',
            keys_selects:$keys_selects, place_holder: 'Descuento');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'total',
            keys_selects:$keys_selects, place_holder: 'Total', disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'valor_unitario_nota_credito',
            keys_selects:$keys_selects, place_holder: 'Valor Nota Credito');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'descripcion_nota_credito',
            keys_selects:$keys_selects, place_holder: 'Partida Nota Credito');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'valor_unitario_complemento_pago',
            keys_selects:$keys_selects, place_holder: 'Pago');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'descripcion_complemento_pago',
            keys_selects:$keys_selects, place_holder: 'Partida Pago');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

    public function lista(bool $header, bool $ws = false): array
    {
        $r_lista = parent::lista($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $r_lista, header: $header,ws:$ws);
        }

        $status_comprador = (new inm_status_comprador(link:$this->link))->registros();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener regitros de status', data:  $status_comprador,
                header: $header,ws:$ws);
        }

        $this->status_comprador = $status_comprador;

        return $r_lista;
    }

    /**
     * Genera la vista de modifica
     * @param bool $header Si header retorna resultado en web
     * @param bool $ws Si ws muestra resultado en json
     * @return array|stdClass
     */
    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $data_row = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $data_row,header: $header,ws: $ws);
        }

        $filtro['inm_comprador.id']= $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente(link: $this->link))->filtro_and(filtro:$filtro);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        if(!isset($this->row_upd->inm_tipo_credito_id)){
            $this->row_upd->inm_tipo_credito_id = $data_row->inm_tipo_credito_id;
        }

        if(!isset($this->row_upd->adm_estado_civil_id)){
            $this->row_upd->adm_estado_civil_id = $data_row->adm_estado_civil_id;
        }

        $keys = array('dp_colonia_postal_id','dp_cp_id','dp_municipio_id','dp_estado_id','dp_pais_id',
            'cat_sat_regimen_fiscal_id','cat_sat_moneda_id','cat_sat_forma_pago_id','cat_sat_metodo_pago_id',
            'cat_sat_uso_cfdi_id','cat_sat_tipo_persona_id');
        foreach ($keys AS $key){
            $this->row_upd->$key = $registro->registros[0][$key];
        }

        $keys = array('com_cliente_calle','com_cliente_numero_exterior','com_cliente_numero_interior');
        foreach ($keys AS $key){
            $rename = str_replace('com_cliente_','',$key);
            $this->row_upd->$rename = $registro->registros[0][$key];
        }

        $keys_selects = (new _keys_selects())->key_selects_base(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $radios = (new _inm_comprador())->radios_chk(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar radios',data:  $radios, header: $header,ws:  $ws);
        }

        $sl_dp_estado_nacimiento_id = (new dp_estado_html(html: $this->html_base))->select_dp_estado_id(
            cols: 3,con_registros:  true,id_selected:  $this->registro['dp_estado_nacimiento_id'],
            link:  $this->link, label: 'Estado Nac', name: 'dp_estado_nacimiento_id');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar sl_dp_estado_nacimiento_id',
                data:  $sl_dp_estado_nacimiento_id,header: $header,ws: $ws);
        }

        $filtro = array('dp_estado.id'=>$this->registro['dp_estado_nacimiento_id']);
        $this->inputs->dp_estado_nacimiento_id = $sl_dp_estado_nacimiento_id;

        $sl_dp_municipio_nacimiento_id = (new dp_municipio_html(html: $this->html_base))->select_dp_municipio_id(
            cols: 3, con_registros: true, id_selected: $this->registro['dp_municipio_nacimiento_id'],
            link: $this->link, filtro: $filtro, label: 'Municipio Nac', name: 'dp_municipio_nacimiento_id');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar sl_dp_municipio_nacimiento_id',
                data:  $sl_dp_municipio_nacimiento_id, header: $header,ws: $ws);
        }

        $this->inputs->dp_municipio_nacimiento_id = $sl_dp_municipio_nacimiento_id;

        $fecha_nacimiento = $this->html->input_fecha(cols: 3, row_upd: $this->row_upd, value_vacio: false,
            place_holder: 'Fecha de Nacimiento', value: $this->row_upd->fecha_nacimiento);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar fecha_nacimiento',
                data:  $fecha_nacimiento, header: $header,ws: $ws);
        }

        $this->inputs->fecha_nacimiento = $fecha_nacimiento;

        $btn_collapse_all = $this->html->button_para_java(id_css: 'collapse_all',style:  'primary',
            tag:  'Ver/Ocultar Todo');
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al btn_collapse_all',data:  $btn_collapse_all, header: $header,ws:  $ws);
        }

        $this->buttons['btn_collapse_all'] = $btn_collapse_all;

        $co_acreditados = (new inm_comprador(link: $this->link))->get_co_acreditados(inm_comprador_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener co_acreditados',data:  $co_acreditados, header: $header,ws:  $ws);
        }

        $inm_co_acreditado = new stdClass();
        $inm_co_acreditado->genero_co_acreditado = 'M';
        if(count($co_acreditados) === 1){
            foreach ($co_acreditados[0] AS $co_acred => $value){
                $key_co_acred = "co_acreditado[$co_acred]";
                $inm_co_acreditado->$key_co_acred = $value;

                if($co_acred === 'genero'){
                    $inm_co_acreditado->genero_co_acreditado = $value;
                }
            }
        }

        $headers = (new \gamboamartin\inmuebles\controllers\_inm_comprador())->frontend_co_acreditado(controler: $this,
            row_upd: $inm_co_acreditado);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al integrar headers',data:  $headers, header: $header,ws:  $ws);
        }

        $button_upd = $this->html->boton_submit(class_button: 'modifica', class_control: 'btn-modifica',
            style: 'success', tag: 'Modifica', id_button: 'btn_modifica');
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar button_upd',data:  $button_upd, header: $header,ws:  $ws);
        }

        $this->btn = $button_upd;


        $boton_edit_1 = $this->html->button_para_java(id_css: 'edit_ref_1',style: 'success',tag: 'Edita');
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar boton_edit_1',data:  $boton_edit_1, header: $header,ws:  $ws);
        }

        $this->buttons['edita_ref_1'] = $boton_edit_1;

        $boton_edit_2 = $this->html->button_para_java(id_css: 'edit_ref_2',style: 'success',tag: 'Edita');
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar boton_edit_2',data:  $boton_edit_2, header: $header,ws:  $ws);
        }

        $this->buttons['edita_ref_2'] = $boton_edit_2;

        $inm_prospecto_id = -1;
        $tiene_prospecto = (new inm_comprador(link: $this->link))->tiene_prospecto(inm_comprador_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al validar tiene prospecto',data:  $tiene_prospecto, header: $header,ws:  $ws);
        }

        if($tiene_prospecto){
            $inm_prospecto = (new inm_comprador(link: $this->link))->inm_prospecto(inm_comprador_id: $this->registro_id);
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener prospecto',data:  $inm_prospecto, header: $header,ws:  $ws);
            }
            $inm_prospecto_id = $inm_prospecto->inm_prospecto_id;
        }


        $controlador_inm_prospecto = (new controlador_inm_prospecto(link: $this->link));
        $controlador_inm_prospecto->registro_id = $inm_prospecto_id;

        $conyuge = $this->inputs_conyuge(header: $header, controler: $this, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener conyuge',data:  $conyuge,
                header: $header,ws:  $ws);
        }

        $this->inputs->conyuge = $conyuge;

        $beneficiario = (new _beneficiario())->inputs_beneficiario(controler: $controlador_inm_prospecto);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener beneficiario',data:  $beneficiario,
                header: $header,ws:  $ws);
        }

        $this->inputs->beneficiario = $beneficiario;

        $filtro_ben['inm_comprador.id'] = $this->registro_id;
        $r_inm_beneficiario = (new inm_rel_beneficiario_comprador(link: $this->link))->filtro_and(filtro: $filtro_ben);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener beneficiarios',data:  $r_inm_beneficiario,
                header: $header,ws:  $ws);
        }

        $accion_retorno = __FUNCTION__;
        if(isset($_GET['accion']) && $_GET['accion'] == 'proceso_cliente') {
            $accion_retorno = 'proceso_cliente';
        }

        $params = (new \gamboamartin\inmuebles\controllers\_inm_comprador())->params_btn(accion_retorno: $accion_retorno,
            registro_id:  $this->registro_id,seccion_retorno:  $this->tabla);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener params',data:  $params,
                header: $header,ws:  $ws);
        }

        $beneficiarios = $r_inm_beneficiario->registros;

        $beneficiarios = (new \gamboamartin\inmuebles\controllers\_inm_comprador())->rows(controlador: $controlador_inm_prospecto,
            datas: $beneficiarios,params:  $params, seccion_exe: 'inm_rel_beneficiario_comprador');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener beneficiarios link del',data:  $beneficiarios,
                header: $header,ws:  $ws);
        }

        $this->beneficiarios = $beneficiarios;

        $referencia = (new _referencia())->inputs_referencia(controler: $controlador_inm_prospecto);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener referencias',data:  $referencia,
                header: $header,ws:  $ws);
        }
        $this->inputs->referencia = $referencia;

        $r_inm_referencia_prospecto = (new inm_rel_referencia_comprador(link: $this->link))->filtro_and(filtro: $filtro_ben);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener referencia_prospectos',data:  $r_inm_referencia_prospecto,
                header: $header,ws:  $ws);
        }

        $params = (new \gamboamartin\inmuebles\controllers\_inm_comprador())->params_btn(accion_retorno: $accion_retorno,
            registro_id:  $this->registro_id,seccion_retorno:  $this->tabla);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener params',data:  $params,
                header: $header,ws:  $ws);
        }

        $referencia_prospectos = $r_inm_referencia_prospecto->registros;

        $referencia_prospectos = (new \gamboamartin\inmuebles\controllers\_inm_comprador())->rows(controlador: $controlador_inm_prospecto,
            datas: $referencia_prospectos,params:  $params, seccion_exe: 'inm_rel_referencia_comprador');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener beneficiarios link del',data:  $referencia_prospectos,
                header: $header,ws:  $ws);
        }

        $this->referencias = $referencia_prospectos;

        return $r_modifica;
    }

    public function modifica_bd(bool $header, bool $ws): array|stdClass
    {
        $this->link->beginTransaction();

        $inm_bit_comp = (new inm_bitacora_status_comprador(link: $this->link))->existe_status_comprador(
            inm_comprador_id: $this->registro_id, values: array('11'));
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener bitacora status comp',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        if ($inm_bit_comp->n_registros > 0) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error el cliente ya esta cancelado',data:  $inm_bit_comp,
                header: $header, ws: $ws);
        }

        $tiene_prospecto = (new inm_comprador(link: $this->link))->tiene_prospecto(inm_comprador_id: $this->registro_id);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al validar inm_prospecto',data:  $tiene_prospecto, header: $header,ws:  $ws);
        }

        if($tiene_prospecto) {
            $inm_prospecto = (new inm_comprador(link: $this->link))->inm_prospecto(inm_comprador_id: $this->registro_id);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al obtener inm_prospecto', data: $inm_prospecto, header: $header, ws: $ws);
            }

            $result_co_acreditado = $this->modelo->transacciona_co_acreditado(inm_comprador_id: $this->registro_id,
                link: $this->link);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al modificar inm_prospecto',data:  $result_co_acreditado,
                    header: $header,ws:  $ws);
            }

            $result_conyuge =  $this->modelo->transacciona_conyuge(inm_comprador_id: $this->registro_id,link: $this->link);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar conyuge', data: $result_conyuge,
                    header: $header, ws: $ws);
            }

            $result_beneficiario = $this->modelo->transacciona_beneficiario(inm_comprador_id: $this->registro_id,link: $this->link);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al insertar beneficiario', data: $result_beneficiario,
                    header: $header, ws: $ws);
            }

            $result_referencia = $this->modelo->transacciona_referencia(inm_comprador_id: $this->registro_id,link: $this->link);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al insertar referencia', data: $result_referencia,
                    header: $header, ws: $ws);
            }
        }
        if(isset($_POST['conyuge'])){
            unset($_POST['conyuge']);
        }
        if(isset($_POST['beneficiario'])){
            unset($_POST['beneficiario']);
        }
        if(isset($_POST['referencia'])){
            unset($_POST['referencia']);
        }
        if(isset($_POST['co_acreditado'])){
            unset($_POST['co_acreditado']);
        }

        $r_modifica = parent::modifica_bd(header: false,ws:  $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al modificar inm_prospecto',data:  $r_modifica,
                header: $header,ws:  $ws);
        }
        $this->link->commit();

        $_SESSION[$r_modifica->salida][]['mensaje'] = $r_modifica->mensaje.' del id '.$this->registro_id;
        $this->header_out(result: $r_modifica, header: $header,ws:  $ws);

        return $r_modifica;


    }

    public function por_firmar_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $filtro_exi['inm_comprador.id'] = $this->registro_id;
        $filtro_exi['inm_status_comprador.id'] = 7;
        $existe = (new inm_bitacora_status_comprador(link: $this->link))->existe(filtro: $filtro_exi);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            $registro = array();
            $registro['inm_comprador_id'] = $this->registro_id;
            $registro['inm_status_comprador_id'] = 7;
            $registro['fecha_status'] = date('Y-m-d\TH:i:s');
            $r_inm_bitacora_status_comprador = (new inm_bitacora_status_comprador(link: $this->link))->alta_registro(
                registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_bitacora_status_comprador,
                    header: $header, ws: $ws);
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 41;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            if(trim($_FILES['anexos']['name']) !== '') {
                $_FILES['documento'] = $_FILES['anexos'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro_id;
                $registro['doc_tipo_documento_id'] = 41;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador,
                        header: $header, ws: $ws);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 42;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            if(trim($_FILES['instruccion_credito']['name']) !== '') {
                $_FILES['documento'] = $_FILES['instruccion_credito'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro_id;
                $registro['doc_tipo_documento_id'] = 42;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador,
                        header: $header, ws: $ws);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 43;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            if(trim($_FILES['notificacion_descuento']['name']) !== '') {
                $_FILES['documento'] = $_FILES['notificacion_descuento'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro_id;
                $registro['doc_tipo_documento_id'] = 43;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador,
                        header: $header, ws: $ws);
                }
            }
        }

        $filtro_doc['inm_comprador.id'] = $this->registro_id;
        $filtro_doc['doc_tipo_documento.id'] = 44;
        $existe = (new inm_doc_comprador(link: $this->link))->existe(filtro: $filtro_doc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de bitacora', data: $existe,
                header: $header, ws: $ws);
        }

        if(!$existe) {
            if(trim($_FILES['isr_notaria']['name']) !== '') {
                $_FILES['documento'] = $_FILES['isr_notaria'];
                $registro = array();
                $registro['inm_comprador_id'] = $this->registro_id;
                $registro['doc_tipo_documento_id'] = 44;
                $r_inm_doc_comprador = (new inm_doc_comprador(link: $this->link))->alta_registro(registro: $registro);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_inm_doc_comprador,
                        header: $header, ws: $ws);
                }
            }
        }

        $this->link->commit();

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'proceso_cliente', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }

    public function tipos_documentos(bool $header, bool $ws = false): array
    {
        $inm_conf_docs_prospecto = (new _inm_comprador())->integra_inm_documentos_comprador(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar buttons', data: $inm_conf_docs_prospecto, header: $header, ws: $ws);
        }

        $salida['draw'] = count($inm_conf_docs_prospecto);
        $salida['recordsTotal'] = count($inm_conf_docs_prospecto);
        $salida['recordsFiltered'] = count($inm_conf_docs_prospecto);
        $salida['data'] = $inm_conf_docs_prospecto;

        header('Content-Type: application/json');
        echo json_encode($salida);
        exit;
    }

    private function result_inm_prosp(): array|stdClass
    {
        $result = new stdClass();
        $result->registros = array();
        $result->totales = array();

        $table = 'inm_comprador';

        $filtro_rango = array();
        if(!empty($_POST['fecha_inicial'])){
            $filtro_rango[$table.'.fecha_alta']['valor1'] = $_POST['fecha_inicial'];
        }
        if(!empty($_POST['fecha_final'])) {
            $filtro_rango[$table . '.fecha_alta']['valor2'] = $_POST['fecha_final'];
        }

        $filtro_especial = array();

        if(!empty($_POST['nombre_comprador'])){
            $filtro_especial[0][$table.'.inm_comprador_razon_social']['operador'] = 'LIKE';
            $filtro_especial[0][$table.'.inm_comprador_razon_social']['valor'] = '%'.$_POST['nombre_comprador'].'%';
            $filtro_especial[0][$table.'.inm_comprador_razon_social']['comparacion'] = 'AND';

            //$filtro_text[$table.'.razon_social'] = $_POST['nombre_prospecto_ubicacion'];
        }

        if(!empty($_POST['nss'])){
            $filtro_especial[1][$table.'.nss']['operador'] = 'LIKE';
            $filtro_especial[1][$table.'.nss']['valor'] = '%'.$_POST['nss'].'%';
            $filtro_especial[1][$table.'.nss']['comparacion'] = 'AND';

            //$filtro_text[$table.'.cuenta_predial'] = $_POST['cuenta_predial'];
        }

        if(!empty($_POST['numero_credito'])){
            $filtro_especial[1][$table.'.numero_credito']['operador'] = 'LIKE';
            $filtro_especial[1][$table.'.numero_credito']['valor'] = '%'.$_POST['numero_credito'].'%';
            $filtro_especial[1][$table.'.numero_credito']['comparacion'] = 'AND';

            //$filtro_text[$table.'.cuenta_predial'] = $_POST['cuenta_predial'];
        }

        if(!empty($_POST['ubicacion'])){
            $filtro_especial[2]['inm_ubicacion_completa']['operador'] = 'LIKE';
            $filtro_especial[2]['inm_ubicacion_completa']['valor'] = '%'.$_POST['ubicacion'].'%';
            $filtro_especial[2]['inm_ubicacion_completa']['comparacion'] = 'AND';

            //$filtro_text['com_agente.descripcion'] = $_POST['agente'];
        }

        if(!empty($_POST['agente'])){
            $filtro_especial[2]['com_agente.descripcion']['operador'] = 'LIKE';
            $filtro_especial[2]['com_agente.descripcion']['valor'] = '%'.$_POST['agente'].'%';
            $filtro_especial[2]['com_agente.descripcion']['comparacion'] = 'AND';

            //$filtro_text['com_agente.descripcion'] = $_POST['agente'];
        }

        $in = array();
        if(!empty($_POST['inm_status_comprador'])){
            $array = explode(",", $_POST['inm_status_comprador']);
            $in['llave'] = 'inm_status_comprador.descripcion';
            $in['values'] = $array;
        }

        /*$columnas_totales[] = 'inm_prospecto_ubicacion_sub_total_base';
        $columnas_totales[] = 'inm_prospecto_ubicacion_total_descuento';
        $columnas_totales[] = 'inm_prospecto_ubicacion_total_traslados';
        $columnas_totales[] = 'inm_prospecto_ubicacion_total_retenciones';
        $columnas_totales[] = 'inm_prospecto_ubicacion_total';*/

        $result = (new inm_comprador(link: $this->link))->filtro_and(filtro_especial: $filtro_especial,
            filtro_rango: $filtro_rango, in: $in);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener prospecto_ubicacions', data: $result);
        }

        return $result;
    }

    public function solicitud_avaluo(bool $header, bool $ws = false)
    {
        $imp_rel_comprador_com_cliente = (new inm_rel_comprador_com_cliente(link: $this->link))
            ->imp_rel_comprador_com_cliente(inm_comprador_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener imp_rel_comprador_com_cliente',
                data: $imp_rel_comprador_com_cliente,header: $header,ws: $ws);
        }

        $imp_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->imp_rel_ubi_comp(
            inm_comprador_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener imp_rel_ubi_comp', data: $imp_rel_ubi_comp,
                header: $header,ws: $ws);
        }

        $inm_rel_cliente_valuador = (new inm_rel_cliente_valuador(link: $this->link))
            ->inm_rel_cliente_valuador(inm_comprador_id:  $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_rel_cliente_valuador',
                data: $inm_rel_cliente_valuador, header: $header, ws: $ws);
        }

        if($inm_rel_cliente_valuador['inm_valuador_alias'] === 'fajardo'){
            $keys_cliente = array('inm_comprador_nss','com_cliente_razon_social','inm_comprador_curp',
                'com_cliente_rfc','dp_colonia_postal_id','com_cliente_calle');
            $valida = $this->validacion->valida_existencia_keys(keys: $keys_cliente,
                registro: $imp_rel_comprador_com_cliente);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error el registro de cliente con id '.
                    $imp_rel_comprador_com_cliente['inm_comprador_id'] .' y nombre '.
                    $imp_rel_comprador_com_cliente['com_cliente_razon_social'], data: $valida,
                    header: $header, ws: $ws);
            }

            $keys = array('dp_colonia_postal_domicilio_id','inm_ubicacion_calle_domicilio',
                'inm_ubicacion_numero_exterior_domicilio','inm_tipo_vivienda_id','inm_ubicacion_numero_notaria',
                'inm_ubicacion_nombre_notario', 'inm_ubicacion_plaza_notaria','inm_ubicacion_numero_escritura',
                'inm_ubicacion_volumen','inm_ubicacion_entre_calle_1', 'inm_ubicacion_entre_calle_2');
            $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $imp_rel_ubi_comp);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error el registro de ubicacion con id '.
                    $imp_rel_ubi_comp['inm_ubicacion_id'] .' y domicilio '.$imp_rel_ubi_comp['inm_ubicacion_ubicacion'],
                    data: $valida, header: $header, ws: $ws);
            }
        }

        $pdf = new Fpdi();
        $_pdf = new _pdf(pdf: $pdf);

        $pdf_exe = $_pdf->solicitud_avaluo(inm_comprador_id: $this->registro_id,path_base:  $this->path_base,
            modelo:  $this->modelo);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al escribir en pdf', data: $pdf_exe, header: $header, ws: $ws);
        }

        exit;
    }


    public function solicitud_infonavit(bool $header, bool $ws = false)
    {

        $pdf = new Fpdi();
        $_pdf = new _pdf(pdf: $pdf);

        $pdf_exe = $_pdf->solicitud_infonavit(inm_comprador_id: $this->registro_id,path_base:  $this->path_base,
            modelo:  $this->modelo);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al escribir en pdf', data: $pdf_exe, header: $header, ws: $ws);
        }

        exit;
    }

    final public function subir_documento(bool $header, bool $ws = false){

        $inm_comprador = (new inm_comprador(link: $this->link))->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_comprador', data: $inm_comprador,
                header: $header, ws: $ws);
        }

        $inm_conf_docs_comprador = (new inm_conf_docs_comprador(link: $this->link))->filtro_and(
            columnas: ['doc_tipo_documento_id'],
            filtro: array('inm_attr_tipo_credito_id' => $inm_comprador['inm_attr_tipo_credito_id']));
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_conf_docs_comprador', data: $inm_conf_docs_comprador,
                header: $header, ws: $ws);
        }

        $this->inputs = new stdClass();

        $filtro['inm_comprador.id'] = $this->registro_id;
        $inm_comprador_id = (new inm_comprador_html(html: $this->html_base))->select_inm_comprador_id(
            cols: 12,con_registros:  true,id_selected:  $this->registro_id,link:  $this->link,filtro: $filtro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_comprador_id, header: $header, ws: $ws);
        }
        $this->inputs->inm_comprador_id = $inm_comprador_id;

        $doc_ids = array_map(function ($registro) {
            return $registro['doc_tipo_documento_id'];
        }, $inm_conf_docs_comprador->registros);

        $doc_tipos_documentos = array();

        if (count($doc_ids) > 0) {
            $doc_tipos_documentos = (new _doctos())->documentos_de_comprador(inm_comprador_id: $this->registro_id,
                link: $this->link, todos: false, tipos_documentos: $doc_ids);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al obtener tipos de documento', data: $doc_tipos_documentos,
                    header: $header, ws: $ws);
            }
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
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_comprador_id, header: $header, ws: $ws);
        }
        $this->inputs->doc_tipo_documento_id = $doc_tipo_documento_id;

        $documento = $this->html->input_file(cols: 12,name:  'documento',row_upd:  new stdClass(),value_vacio:  false);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $documento, header: $header,ws:  $ws);
        }

        $this->inputs->documento = $documento;

        $params = array();
        if(isset($_GET['pestana_general_actual'])) {
            $params = array('pestana_general_actual' => 'pestanageneral1',
                'pestana_actual' => $_GET['pestana_actual']);
        }
        $link_alta_doc = $this->obj_link->link_alta_bd(link:  $this->link,seccion:  'inm_doc_comprador',
            params: $params);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar link',data:  $link_alta_doc, header: $header,ws:  $ws);
        }

        $this->link_inm_doc_comprador_alta_bd = $link_alta_doc;

        $retorno = 'documentos';
        if(isset($_GET['pestana_general_actual'])){
            $retorno = 'proceso_cliente';
        }

        $btn_action_next = $this->html->hidden('btn_action_next',value: $retorno);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next',data:  $btn_action_next, header: $header,ws:  $ws);
        }

        $id_retorno = $this->html->hidden('id_retorno',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next',data:  $btn_action_next, header: $header,ws:  $ws);
        }

        $seccion_retorno = $this->html->hidden('seccion_retorno',value: $this->seccion);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar btn_action_next',data:  $btn_action_next, header: $header,ws:  $ws);
        }

        $this->inputs->btn_action_next = $btn_action_next;
        $this->inputs->id_retorno = $id_retorno;
        $this->inputs->seccion_retorno = $seccion_retorno;
    }


    public function asigna_comision(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $registro = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $keys_selects = (new _keys_selects())->key_selects_asigna_ubicacion(controler: $this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $inm_comprador_id = $this->html->hidden(name:'inm_comprador_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al in_registro_id',data:  $inm_comprador_id,
                header: $header,ws:  $ws);
        }


        $hiddens = (new _keys_selects())->hiddens(controler: $this,funcion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs',data:  $hiddens,
                header: $header,ws:  $ws);
        }

        $inputs = (new _keys_selects())->inputs_form_base(btn_action_next: $hiddens->btn_action_next,
            controler: $this, id_retorno: $hiddens->id_retorno, in_registro_id: $hiddens->in_registro_id,
            inm_comprador_id: $inm_comprador_id, inm_ubicacion_id: '', precio_operacion: $hiddens->precio_operacion,
            seccion_retorno: $hiddens->seccion_retorno);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener inputs_hidden',data:  $inputs, header: $header,ws:  $ws);
        }



        $extra_params_keys[] = 'inm_ubicacion_precio';
        $ubicaciones_con_precio = (new inm_ubicacion(link: $this->link))->ubicaciones_con_precio(etapa: 'ALTA',
            inm_comprador_id:  $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener ubicaciones con precio',data:  $ubicaciones_con_precio,
                header: $header,ws:  $ws);
        }

        $inm_ubicacion_id = (new _inm_comprador())->inm_ubicacion_id_input(controler: $this,
            extra_params_keys: $extra_params_keys, registros: $ubicaciones_con_precio);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al inm_ubicacion_id',data:  $inm_ubicacion_id,
                header: $header,ws:  $ws);
        }

        $this->inputs->inm_ubicacion_id = $inm_ubicacion_id;

        $link_rel_ubi_comp_alta_bd = $this->obj_link->link_alta_bd(link: $this->link,seccion: 'inm_rel_ubi_comp');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_rel_ubi_comp_alta_bd,
                header: $header,ws:  $ws);
        }

        $this->link_rel_ubi_comp_alta_bd = $link_rel_ubi_comp_alta_bd;

        $inm_ubicaciones = (new _inm_comprador())->inm_ubicaciones(inm_comprador_id: $this->registro_id,
            link:  $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener compradores',data:  $inm_ubicaciones,
                header: $header,ws:  $ws);
        }

        $this->inm_ubicaciones = $inm_ubicaciones;

        return $r_modifica;
    }


    public function genera_factura(bool $header, bool $ws = false): array|stdClass
    {
        $filtro_comp['inm_comprador.id'] = $this->registro_id;

        $in_comp = array();
        $in_comp['llave'] = 'inm_status_comprador.id';
        $in_comp['values'] = array('9','10');
        $r_comprador_etapa = (new inm_comprador(link: $this->link))->filtro_and(filtro: $filtro_comp, in: $in_comp);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener compradores firmadas',data:  $r_comprador_etapa,
                header: $header,ws:  $ws);
        }

        if($r_comprador_etapa->n_registros <= 0){
            return $this->retorno_error(mensaje: 'Error el cliente no esta COTEJADO', data: $r_comprador_etapa,
                header: $header, ws: $ws);
        }

        if(isset($_GET['accion']) && $_GET['accion'] == 'genera_factura') {
            $template = $this->modifica(header: false);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
            }
        }

        $filtro_rel['inm_comprador.id'] = $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente($this->link))->filtro_and(filtro: $filtro_rel);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $filtro_sucursal['com_cliente.id'] = $registro->registros[0]['com_cliente_id'];
        $r_com_sucursal = (new com_sucursal(link: $this->link))->filtro_and(
            filtro: $filtro_sucursal);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_com_sucursal,header: $header,ws: $ws);
        }

        $filtro_fac['com_cliente.id'] =  $registro->registros[0]['com_cliente_id'];
        $r_fc_factura = (new fc_factura(link: $this->link))->filtro_and(
            filtro: $filtro_fac);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_fc_factura,header: $header,ws: $ws);
        }

        $this->row_upd->exportacion = '01';
        $this->row_upd->fecha_factura = date('Y-m-d');
        $this->row_upd->cantidad = '1';
        $this->row_upd->valor_unitario = $this->row_upd->pago_precio_compra_venta;
        $this->row_upd->subtotal = 0;
        $this->row_upd->descuento_factura = 0;
        $this->row_upd->total = 0;

        $fc_partida = new stdClass();
        $fc_partida->n_registros = 0;
        $fc_partida->registros = array();

        $fc_factura_id = -1;

        if($r_fc_factura->n_registros > 0){
            $this->row_upd->serie = $r_fc_factura->registros[0]['fc_factura_serie'];
            $this->row_upd->folio = $r_fc_factura->registros[0]['fc_factura_folio'];
            $this->row_upd->exportacion = $r_fc_factura->registros[0]['fc_factura_exportacion'];
            $this->row_upd->fecha_factura = $r_fc_factura->registros[0]['fc_factura_fecha'];
            $this->row_upd->observaciones_factura = $r_fc_factura->registros[0]['fc_factura_observaciones'];

            $filtro_par['fc_factura.id'] =  $r_fc_factura->registros[0]['fc_factura_id'];
            $fc_partida = (new fc_partida(link: $this->link))->filtro_and(
                filtro: $filtro_par);
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $fc_partida,header: $header,ws: $ws);
            }
            $this->row_upd->descripcion_factura = $fc_partida->registros[0]['fc_partida_descripcion'];
            $this->row_upd->cantidad = $fc_partida->registros[0]['fc_partida_cantidad'];
            $this->row_upd->valor_unitario = $fc_partida->registros[0]['fc_partida_valor_unitario'];
            $this->row_upd->subtotal = $fc_partida->registros[0]['fc_partida_sub_total'];
            $this->row_upd->descuento_factura = $fc_partida->registros[0]['fc_partida_descuento'];
            $this->row_upd->total = $fc_partida->registros[0]['fc_partida_total'];

            $fc_factura_id = $r_fc_factura->registros[0]['fc_factura_id'];
        }

        $keys_selects = array();
        $columns_ds = array('com_cliente_rfc','com_cliente_razon_social');
        $filtro['com_sucursal.id'] = $r_com_sucursal->registros[0]['com_sucursal_id'];
        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: $filtro, key: 'com_sucursal_id',
            keys_selects: $keys_selects, id_selected: $r_com_sucursal->registros[0]['com_sucursal_id'],
            label: 'Cliente', columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $fc_csds = (new fc_csd(link: $this->link))->registros_activos();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener fc_csds',data:  $fc_csds);
            print_r($error);
            die('Error');
        }

        $id_selected = -1;
        if(count($fc_csds) === 1){
            $id_selected = $fc_csds[0]['fc_csd_id'];
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: array(), key: 'fc_csd_id',
            keys_selects: $keys_selects, id_selected: $id_selected, label: 'Empresa');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $id_selected = -1;
        if(isset($registro->registros[0]['com_cliente_cat_sat_tipo_de_comprobante_id']) &&
            trim($registro->registros[0]['com_cliente_cat_sat_tipo_de_comprobante_id']) !== ''){
            $id_selected = $registro->registros[0]['com_cliente_cat_sat_tipo_de_comprobante_id'];
        }
        if($r_fc_factura->n_registros > 0) {
            $id_selected = $r_fc_factura->registros[0]['cat_sat_tipo_de_comprobante_id'];
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: array(),
            key: 'cat_sat_tipo_de_comprobante_id', keys_selects: $keys_selects, id_selected: $id_selected,
            label: 'Tipo de Comprobante');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $id_selected = -1;
        if(isset($registro->registros[0]['com_cliente_cat_sat_metodo_pago_id']) &&
            trim($registro->registros[0]['com_cliente_cat_sat_metodo_pago_id']) !== ''){
            $id_selected = $registro->registros[0]['com_cliente_cat_sat_metodo_pago_id'];
        }
        if($r_fc_factura->n_registros > 0) {
            $id_selected = $r_fc_factura->registros[0]['cat_sat_metodo_pago_id'];
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: array(),
            key: 'cat_sat_metodo_pago_id', keys_selects: $keys_selects, id_selected: $id_selected,
            label: 'Metodo de Pago');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $id_selected = -1;
        if(isset($registro->registros[0]['com_cliente_cat_sat_forma_pago_id']) &&
            trim($registro->registros[0]['com_cliente_cat_sat_forma_pago_id']) !== ''){
            $id_selected = $registro->registros[0]['com_cliente_cat_sat_forma_pago_id'];
        }
        if($r_fc_factura->n_registros > 0) {
            $id_selected = $r_fc_factura->registros[0]['cat_sat_forma_pago_id'];
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: array(),
            key: 'cat_sat_forma_pago_id', keys_selects: $keys_selects, id_selected: $id_selected,
            label: 'Forma de Pago');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $id_selected = -1;
        if(isset($registro->registros[0]['com_cliente_cat_sat_moneda_id']) &&
            trim($registro->registros[0]['com_cliente_cat_sat_moneda_id']) !== ''){
            $id_selected = $registro->registros[0]['com_cliente_cat_sat_moneda_id'];
        }
        if($r_fc_factura->n_registros > 0) {
            $id_selected = $r_fc_factura->registros[0]['cat_sat_moneda_id'];
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: array(),
            key: 'cat_sat_moneda_id', keys_selects: $keys_selects, id_selected: $id_selected,
            label: 'Moneda');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $filtro_cambio['cat_sat_moneda.id'] =  $registro->registros[0]['com_cliente_cat_sat_moneda_id'];
        $com_tipo_cambio = (new com_tipo_cambio(link: $this->link))->filtro_and(filtro: $filtro_cambio);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener fc_csds',data:  $com_tipo_cambio);
            print_r($error);
            die('Error');
        }

        $id_selected = -1;
        if($com_tipo_cambio->n_registros > 0){
            $id_selected = $com_tipo_cambio->registros[0]['com_tipo_cambio_id'];
        }
        if($r_fc_factura->n_registros > 0) {
            $id_selected = $r_fc_factura->registros[0]['com_tipo_cambio_id'];
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: array(),
            key: 'com_tipo_cambio_id', keys_selects: $keys_selects, id_selected: $id_selected,
            label: 'Tipo de Cambio');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $id_selected = -1;
        if(isset($registro->registros[0]['com_cliente_cat_sat_uso_cfdi_id']) &&
            trim($registro->registros[0]['com_cliente_cat_sat_uso_cfdi_id']) !== ''){
            $id_selected = $registro->registros[0]['com_cliente_cat_sat_uso_cfdi_id'];
        }
        if($r_fc_factura->n_registros > 0) {
            $id_selected = $r_fc_factura->registros[0]['cat_sat_uso_cfdi_id'];
        }
        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: array(),
            key: 'cat_sat_uso_cfdi_id', keys_selects: $keys_selects, id_selected: $id_selected,
            label: 'Uso CFDI');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $id_selected = -1;
        if($fc_partida->n_registros > 0){
            $id_selected = $fc_partida->registros[0]['com_producto_id'];
        }

        $filtro_prod['com_producto.codigo_sat'] = '95122101';
        $r_com_producto = (new com_producto(link: $this->link))->filtro_and(
            filtro: $filtro_prod);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_com_producto,header: $header,ws: $ws);
        }

        if($r_com_producto->n_registros > 0){
            $id_selected = $r_com_producto->registros[0]['com_producto_id'];
        }

        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: $filtro_prod,
            key: 'com_producto_id', keys_selects: $keys_selects, id_selected: $id_selected,
            label: 'Producto', extra_params_keys:  array("com_producto_aplica_predial","com_producto_descripcion"));
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $id_selected = -1;
        if($fc_partida->n_registros > 0){
            $id_selected = $fc_partida->registros[0]['cat_sat_obj_imp_id'];
        }
        $keys_selects = $this->key_select(cols: 12, con_registros: true,filtro: array(),
            key: 'cat_sat_obj_imp_id', keys_selects: $keys_selects, id_selected: $id_selected,
            label: 'Objeto de Impuesto');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols: 12, con_registros: true,filtro: array(),
            key: 'cat_sat_conf_imps_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Configuracion de Impuestos');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $filtro = array();
        $filtro['inm_comprador.id'] = $this->registro_id;
        $order = array('inm_rel_ubi_comp.fecha_alta'=>'DESC');
        $r_inm_rel_ubi_comp = (new inm_rel_ubi_comp(link: $this->link))->filtro_and(filtro: $filtro, order: $order);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener compradores',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $registro = (new inm_comprador(link: $this->link))->registro(registro_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'descripcion_factura',
            keys_selects:$keys_selects, place_holder: 'Descripcion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $numero_notaria = trim($r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_numero_escritura']);

        $enLetras = '';
        if (is_numeric($numero_notaria)) {
            $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
            $enLetras = $formatter->format($numero_notaria);
        }

        $enMayusculas = mb_strtoupper($enLetras, 'UTF-8');

        $this->row_upd->descripcion_factura = $registro['inm_ubicacion_completa'].' NUMERO ESCRITURA '.
            $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_numero_escritura'].' '.
            $enMayusculas. '  NOTARIA PÚBLICA NÚMERO '.
            $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_numero_notaria'].' DE '.
            $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_plaza_notaria'].' LIC. '.
            $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_nombre_notario'].' CUENTA PREDIAL '.
            $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_cuenta_predial'];

        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'cuenta_predial',
            keys_selects:$keys_selects, place_holder: 'Cuenta Predial', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $this->row_upd->cuenta_predial = $r_inm_rel_ubi_comp->registros[0]['inm_ubicacion_cuenta_predial'];

        $this->row_upd->uuid = '';
        if(isset($r_fc_factura->registros[0]['fc_factura_folio_fiscal']) &&
            $r_fc_factura->registros[0]['fc_factura_folio_fiscal'] !== ''){
            $this->row_upd->uuid = $r_fc_factura->registros[0]['fc_factura_folio_fiscal'];
        }
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'uuid', keys_selects:$keys_selects,
            place_holder: 'UUID', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        
        $this->row_upd->etapa = '';
        if(isset($r_fc_factura->registros[0]['fc_factura_etapa']) &&
            $r_fc_factura->registros[0]['fc_factura_etapa'] !== ''){
            $this->row_upd->etapa = $r_fc_factura->registros[0]['fc_factura_etapa'];
        }
        $keys_selects = (new init())->key_select_txt(cols: 6,key: 'etapa', keys_selects:$keys_selects,
            place_holder: 'Etapa', required: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $buttons = $this->buttons_base(fc_factura_id: $fc_factura_id);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener buttons', data: $buttons);
        }

        $this->buttons_base = $buttons;

        $params = array('pestana_general_actual' => 'pestanageneral2');
        $link_genera_factura_bd = $this->obj_link->link_con_id(accion:'genera_factura_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_comprador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_genera_factura_bd,
                header: $header,ws:  $ws);
        }

        $this->link_genera_factura_bd = $link_genera_factura_bd;

        return $base;
    }

    public function genera_factura_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $filtro_fac['com_sucursal.id'] = $_POST['com_sucursal_id'];
        $r_factura = (new fc_factura(link: $this->link))->filtro_and(filtro: $filtro_fac);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_factura,
                header: $header, ws: $ws);
        }

        $r_com_sucursal = (new com_sucursal(link: $this->link))->registro(registro_id: $_POST['com_sucursal_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_com_sucursal,
                header: $header, ws: $ws);
        }

        $filtro_tipo['cat_sat_moneda.id'] = $r_com_sucursal['cat_sat_moneda_id'];
        $r_moneda = (new com_tipo_cambio(link: $this->link))->filtro_and(filtro: $filtro_tipo);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_moneda,
                header: $header, ws: $ws);
        }

        $r_producto = (new com_producto(link: $this->link))->registro(registro_id: $_POST['com_producto_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_producto,
                header: $header, ws: $ws);
        }

        $filtro_comp['cat_sat_tipo_de_comprobante.descripcion'] = 'Ingreso';
        $r_comprobante = (new cat_sat_tipo_de_comprobante(link: $this->link))->filtro_and(filtro: $filtro_comp);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_comprobante,
                header: $header, ws: $ws);
        }

        if($r_factura->n_registros > 0) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error ya existe una factura para este cliente', data: $r_factura,
                header: $header, ws: $ws);
        }

        if($r_factura->n_registros <= 0) {
            $registro['fc_csd_id'] = $_POST['fc_csd_id'];
            $registro['com_sucursal_id'] = $_POST['com_sucursal_id'];
            $registro['exportacion'] = '01';
            $registro['cat_sat_tipo_de_comprobante_id'] = $r_comprobante->registros[0]['cat_sat_tipo_de_comprobante_id'];
            $registro['cat_sat_metodo_pago_id'] = $r_com_sucursal['cat_sat_metodo_pago_id'];
            $registro['cat_sat_forma_pago_id'] = $r_com_sucursal['cat_sat_forma_pago_id'];
            $registro['cat_sat_moneda_id'] = $r_com_sucursal['cat_sat_moneda_id'];
            $registro['com_tipo_cambio_id'] = $r_moneda->registros[0]['com_tipo_cambio_id'];
            $registro['cat_sat_uso_cfdi_id'] = $r_com_sucursal['cat_sat_uso_cfdi_id'];
            $registro['observaciones'] = $_POST['observaciones_factura'];
            $r_fc_factura = (new fc_factura(link: $this->link))->alta_registro(registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_factura,
                    header: $header, ws: $ws);
            }

            $cantidad = 1;
            if(isset($_POST['cantidad'])){
                $cantidad = $_POST['cantidad'];
            }

            $descuento = 0;
            if(isset($_POST['descuento'])){
                $descuento = $_POST['descuento'];
            }

            $registro_partida['fc_factura_id'] = $r_fc_factura->registro_id;
            $registro_partida['com_producto_id'] = $_POST['com_producto_id'];
            $registro_partida['cuenta_predial'] = $_POST['cuenta_predial'];
            $registro_partida['cat_sat_obj_imp_id'] = $r_producto['cat_sat_obj_imp_id'];
            $registro_partida['descripcion'] = $_POST['descripcion_factura'];
            $registro_partida['cantidad'] = $cantidad;
            $registro_partida['valor_unitario'] = $_POST['valor_unitario'];
            $registro_partida['descuento'] = $descuento;
            $registro_partida['cat_sat_conf_imps_id'] = $r_producto['cat_sat_conf_imps_id'];
            $r_fc_partida = (new fc_partida(link: $this->link))->alta_registro(registro: $registro_partida);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_partida,
                    header: $header, ws: $ws);
            }
        }
        $this->link->commit();

        //$params = array('pestana_general_actual' => 'pestanageneral2');
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'genera_factura', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: array());
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }

    public function buttons_base(int $fc_factura_id = -1): array|string
    {
        $button_fc_factura_modifica =  $this->html->button_href(accion: 'modifica', etiqueta: 'Detalle Factura',
            registro_id: $fc_factura_id, seccion: 'fc_factura', style: 'success', cols: 4, params: array());
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button_fc_factura_modifica);
        }

        $button_fc_factura_timbra =  $this->html->button_href(accion: 'timbra_xml', etiqueta: 'Timbrar',
            registro_id: $fc_factura_id, seccion: 'fc_factura', style: 'danger', cols: 6, params: array());
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button_fc_factura_timbra);
        }

        $button_fc_factura_nota_credito =  $this->html->button_href(accion: 'genera_nota_credito',
            etiqueta: 'Nota de Credito', registro_id: $this->registro_id, seccion: $this->seccion, style: 'warning',
            cols: 4, params: array());
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button_fc_factura_nota_credito);
        }

        $button_fc_factura_complemento_pago =  $this->html->button_href(accion: 'genera_complemento_pago',
            etiqueta: 'Complemento Pago', registro_id: $this->registro_id, seccion: $this->seccion, style: 'warning',
            cols: 4, params: array());
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button_fc_factura_complemento_pago);
        }

        $button_fc_factura_exportar_documentos =  $this->html->button_href(accion: 'exportar_documentos',
            etiqueta: 'Descargar Factura', registro_id: $fc_factura_id, seccion: 'fc_factura', style: 'success',
            cols: 6, params: array());
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button_fc_factura_exportar_documentos);
        }

        $buttons = $button_fc_factura_modifica.$button_fc_factura_nota_credito.
            $button_fc_factura_complemento_pago;

        $return =  "";
        if($fc_factura_id > 0) {
            $return = "<div class='col-md-12 buttons-form'>$button_fc_factura_timbra $button_fc_factura_exportar_documentos</div>
                <div class='col-md-12 buttons-form'>$buttons</div> ";
        }

        return $return;
    }

    public function genera_nota_credito(bool $header, bool $ws = false): array|stdClass
    {
        if(isset($_GET['accion']) && $_GET['accion'] == 'genera_nota_credito') {
            $template = $this->modifica(header: false);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
            }
        }

        $filtro_rel['inm_comprador.id'] = $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente($this->link))->filtro_and(filtro: $filtro_rel);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $filtro_fac['com_cliente.id'] =  $registro->registros[0]['com_cliente_id'];
        $r_fc_factura = (new fc_factura(link: $this->link))->filtro_and(
            filtro: $filtro_fac);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_fc_factura,header: $header,ws: $ws);
        }

        if($r_fc_factura->n_registros <= 0){
            return $this->retorno_error(
                mensaje: 'Error no existe factura del cliente ID: ' .
                $this->registro_id ,data:  $r_fc_factura,header: $header,ws: $ws);
        }

        $this->row_upd->valor_unitario_nota_credito = 0;

        $filtro_sucursal['com_cliente.id'] = $registro->registros[0]['com_cliente_id'];
        $r_com_sucursal = (new com_sucursal(link: $this->link))->filtro_and(
            filtro: $filtro_sucursal);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_com_sucursal,header: $header,ws: $ws);
        }

        $keys_selects = array();
        $columns_ds = array('com_cliente_rfc','com_cliente_razon_social');
        $filtro_sucu['com_sucursal.id'] = $r_com_sucursal->registros[0]['com_sucursal_id'];
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro: $filtro_sucu, key: 'com_sucursal_id',
            keys_selects: $keys_selects, id_selected: $r_com_sucursal->registros[0]['com_sucursal_id'],
            label: 'Cliente', columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: $filtro_fac,
            key: 'fc_factura_id', keys_selects: $keys_selects,
            id_selected: $r_fc_factura->registros[0]['fc_factura_id'], label: 'Factura');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols: 4, con_registros: true,filtro: array(),
            key: 'com_producto_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Producto', extra_params_keys: array('com_producto_descripcion'));
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols: 4, con_registros: true,filtro: array(),
            key: 'cat_sat_metodo_pago_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Metodo de Pago');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols: 4, con_registros: true,filtro: array(),
            key: 'cat_sat_forma_pago_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Forma de Pago');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_comprador_nss', 'inm_comprador_nombre', 'inm_comprador_apellido_paterno',
            'inm_comprador_apellido_materno');
        $filtro['inm_comprador.id'] = $this->registro_id;
        $inm_prospecto_id = (new inm_comprador_html(html: $this->html_base))->select_inm_comprador_id(
            cols: 12, con_registros: true, id_selected: $this->registro_id, link: $this->link, columns_ds: $columns_ds,
            filtro: $filtro,label: 'Cliente');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_prospecto_id, header: $header, ws: $ws);
        }

        $this->inputs->inm_comprador_id = $inm_prospecto_id;

        $buttons = $this->buttons_nota_credito();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener buttons', data: $buttons);
        }

        $this->buttons_base = $buttons;

        $params = array();
        $link_nota_credito_bd = $this->obj_link->link_con_id(accion:'genera_nota_credito_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_comprador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_nota_credito_bd,
                header: $header,ws:  $ws);
        }

        $this->link_nota_credito_bd = $link_nota_credito_bd;

        $filtro['inm_comprador.id'] = $this->registro_id;
        $order = array('inm_cheque.fecha_alta'=>'DESC');
        $r_inm_cheque = (new inm_rel_cheque_comprador(link: $this->link))->filtro_and(filtro: $filtro,order: $order);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener etapas', data: $r_inm_cheque,header: $header,
                ws:  $ws);
        }

        $r_nc = array();
        $cont = 1;
        foreach ($r_inm_cheque->registros as $cheque){
            $temp['monto'] = $cheque['inm_cheque_monto'];
            $temp['descripcion_select'] = $cheque['inm_tipo_cheque_descripcion'].' - '. $cheque['inm_cheque_monto'];

            $r_nc[$cont] = $temp;
            $cont++;
        }

        if($this->row_upd->pago_propio_peculio > 0){
            $cont++;

            $temp['monto'] = $this->row_upd->pago_propio_peculio;
            $temp['descripcion_select'] = 'PAGO PROPIO PECULIO - ' . $this->row_upd->pago_propio_peculio;
            $r_nc[$cont] = $temp;
        }

        if($this->row_upd->pago_cuv > 0){
            $cont++;

            $temp['monto'] = $this->row_upd->pago_cuv;
            $temp['descripcion_select'] = 'PAGO CUV - ' . $this->row_upd->pago_cuv;
            $r_nc[$cont] = $temp;
        }

        $filtro_nc['com_cliente.id'] =  $registro->registros[0]['com_cliente_id'];
        $r_fc_nota_credito = (new fc_nota_credito(link: $this->link))->filtro_and(
            filtro: $filtro_nc);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_fc_nota_credito,header: $header,ws: $ws);
        }

        $notas_credito = array();

        foreach ($r_fc_nota_credito->registros as $nota_credito){
            foreach ($r_nc as $key => $nc){
                if($nc['monto'] === $nota_credito['fc_nota_credito_total']){
                    unset($r_nc[$key]);
                }
            }

            $timbra_xml = $this->html->button_href(accion: 'modifica', etiqueta: 'Detalles',
                registro_id: $nota_credito['fc_nota_credito_id'], seccion: 'fc_nota_credito', style: 'warning');
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $timbra_xml,header: $header,ws: $ws);
            }
            $nota_credito['modifica'] = $timbra_xml;

            $timbra_xml = $this->html->button_href(accion: 'timbra_xml', etiqueta: 'Timbra XML',
                registro_id: $nota_credito['fc_nota_credito_id'], seccion: 'fc_nota_credito', style: 'danger');
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $timbra_xml,header: $header,ws: $ws);
            }
            $nota_credito['timbra_xml'] = $timbra_xml;

            $exportar_documentos = $this->html->button_href(accion: 'exportar_documentos', etiqueta: 'Descargar',
                registro_id: $nota_credito['fc_nota_credito_id'], seccion: 'fc_nota_credito', style: 'success');
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $exportar_documentos,header: $header,ws: $ws);
            }
            $nota_credito['exportar_documentos'] = $exportar_documentos;

            $elimina_documentos = $this->html->button_href(accion: 'elimina_bd', etiqueta: 'Elimina',
                registro_id: $nota_credito['fc_nota_credito_id'], seccion: 'fc_nota_credito', style: 'danger');
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $elimina_documentos,header: $header,ws: $ws);
            }
            $nota_credito['elimina_bd'] = $elimina_documentos;

            $notas_credito[] = $nota_credito;
        }

        $this->notas_credito = $notas_credito;

        $select = $this->html_base->select(cols: 6, id_selected: -1, label: 'Montos Nota Credito',
            name: 'monto_nota_credito', values: $r_nc, extra_params_key: array("monto"));
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar select', data: $select,header: $header,
                ws:  $ws);
        }

        $this->inputs->monto_nota_credito = $select;

        return $base;
    }

    public function buttons_nota_credito(): array|string
    {
        $button_fc_factura_nota_credito =  $this->html->button_href(accion: 'genera_factura',
            etiqueta: 'Regresa a Factura', registro_id: $this->registro_id, seccion: $this->seccion, style: 'warning',
            cols: 12, params: array('inm_comprador_id' => $this->registro_id));
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button_fc_factura_nota_credito);
        }

        $buttons = $button_fc_factura_nota_credito;

        return "<div class='col-md-12 buttons-form'>$buttons</div>";
    }

    public function genera_nota_credito_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $r_fc_factura = (new fc_factura(link: $this->link))->registro(registro_id: $_POST['fc_factura_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de nota_credito', data: $r_fc_factura,
                header: $header, ws: $ws);
        }

        $r_com_sucursal = (new com_sucursal(link: $this->link))->registro(registro_id: $_POST['com_sucursal_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_com_sucursal,
                header: $header, ws: $ws);
        }

        $filtro_tipo['cat_sat_moneda.id'] = $r_com_sucursal['cat_sat_moneda_id'];
        $r_moneda = (new com_tipo_cambio(link: $this->link))->filtro_and(filtro: $filtro_tipo);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_moneda,
                header: $header, ws: $ws);
        }

        $r_producto = (new com_producto(link: $this->link))->registro(registro_id: $_POST['com_producto_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_producto,
                header: $header, ws: $ws);
        }

        $filtro_comp['cat_sat_tipo_de_comprobante.descripcion'] = 'Egreso';
        $r_comprobante = (new cat_sat_tipo_de_comprobante(link: $this->link))->filtro_and(filtro: $filtro_comp);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_comprobante,
                header: $header, ws: $ws);
        }

        $filtro_uso['cat_sat_uso_cfdi.codigo'] = 'S01';
        $r_uso_cfdi = (new cat_sat_uso_cfdi(link: $this->link))->filtro_and(filtro: $filtro_uso);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_uso_cfdi,
                header: $header, ws: $ws);
        }

        $registro['fc_csd_id'] = $r_fc_factura['fc_csd_id'];
        $registro['com_sucursal_id'] = $_POST['com_sucursal_id'];
        $registro['exportacion'] = '01';
        $registro['cat_sat_tipo_de_comprobante_id'] = $r_comprobante->registros[0]['cat_sat_tipo_de_comprobante_id'];
        $registro['cat_sat_metodo_pago_id'] = $_POST['cat_sat_metodo_pago_id'];
        $registro['cat_sat_forma_pago_id'] = $_POST['cat_sat_forma_pago_id'];
        $registro['cat_sat_moneda_id'] = $r_com_sucursal['cat_sat_moneda_id'];
        $registro['com_tipo_cambio_id'] = $r_moneda->registros[0]['com_tipo_cambio_id'];
        $registro['cat_sat_uso_cfdi_id'] = $r_uso_cfdi->registros[0]['cat_sat_uso_cfdi_id'];
        $registro['observaciones'] = $_POST['observaciones_nota_credito'];
        $r_fc_nota_credito = (new fc_nota_credito(link: $this->link))->alta_registro(registro: $registro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_nota_credito,
                header: $header, ws: $ws);
        }

        $cantidad = 1;
        if(isset($_POST['cantidad'])){
            $cantidad = $_POST['cantidad'];
        }

        $descuento = 0;
        if(isset($_POST['descuento'])){
            $descuento = $_POST['descuento'];
        }

        $registro_partida['fc_nota_credito_id'] = $r_fc_nota_credito->registro_id;
        $registro_partida['com_producto_id'] = $_POST['com_producto_id'];
        $registro_partida['cat_sat_obj_imp_id'] = $r_producto['cat_sat_obj_imp_id'];
        $registro_partida['descripcion'] = $_POST['descripcion_nota_credito'];
        $registro_partida['cantidad'] = $cantidad;
        $registro_partida['descuento'] = $descuento;
        $registro_partida['valor_unitario'] = $_POST['valor_unitario_nota_credito'];
        $registro_partida['cat_sat_conf_imps_id'] = $r_producto['cat_sat_conf_imps_id'];
        $r_fc_partida = (new fc_partida_nc(link: $this->link))->alta_registro(registro: $registro_partida);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_partida,
                header: $header, ws: $ws);
        }

        $filtro_relacion['cat_sat_tipo_relacion.codigo'] = '01';
        $r_tipo_relacion = (new cat_sat_tipo_relacion(link: $this->link))->filtro_and(filtro: $filtro_relacion);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_tipo_relacion,
                header: $header, ws: $ws);
        }

        $registro_relacion_nc['cat_sat_tipo_relacion_id'] = $r_tipo_relacion->registros[0]['cat_sat_tipo_relacion_id'];
        $registro_relacion_nc['fc_nota_credito_id'] = $r_fc_nota_credito->registro_id;
        $r_fc_relacion_nc = (new fc_relacion_nc(link: $this->link))->alta_registro(registro: $registro_relacion_nc);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_relacion_nc,
                header: $header, ws: $ws);
        }
        
        $registro_fc_nc_rel['fc_factura_id'] = $_POST['fc_factura_id'];
        $registro_fc_nc_rel['fc_relacion_nc_id'] = $r_fc_relacion_nc->registro_id;
        $registro_fc_nc_rel['monto_aplicado_factura'] = $cantidad;
        $r_fc_nc_rel = (new fc_nc_rel(link: $this->link))->alta_registro(registro: $registro_fc_nc_rel);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_nc_rel,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        //$params = array('pestana_general_actual' => 'pestanageneral2');
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'genera_nota_credito', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: array());
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }


    public function genera_complemento_pago(bool $header, bool $ws = false): array|stdClass
    {
        if(isset($_GET['accion']) && $_GET['accion'] == 'genera_complemento_pago') {
            $template = $this->modifica(header: false);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
            }
        }

        $filtro_rel['inm_comprador.id'] = $this->registro_id;
        $registro = (new inm_rel_comprador_com_cliente($this->link))->filtro_and(filtro: $filtro_rel);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $registro,header: $header,ws: $ws);
        }

        $filtro_fac['com_cliente.id'] =  $registro->registros[0]['com_cliente_id'];
        $r_fc_factura = (new fc_factura(link: $this->link))->filtro_and(
            filtro: $filtro_fac);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_fc_factura,header: $header,ws: $ws);
        }

        if($r_fc_factura->n_registros <= 0){
            return $this->retorno_error(
                mensaje: 'Error no existe factura del cliente ID: ' .
                $this->registro_id ,data:  $r_fc_factura,header: $header,ws: $ws);
        }

        $this->row_upd->valor_unitario_complemento_pago = 0;

        $filtro_sucursal['com_cliente.id'] = $registro->registros[0]['com_cliente_id'];
        $r_com_sucursal = (new com_sucursal(link: $this->link))->filtro_and(
            filtro: $filtro_sucursal);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_com_sucursal,header: $header,ws: $ws);
        }

        $keys_selects = array();
        $columns_ds = array('com_cliente_rfc','com_cliente_razon_social');
        $filtro_sucu['com_sucursal.id'] = $r_com_sucursal->registros[0]['com_sucursal_id'];
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro: $filtro_sucu, key: 'com_sucursal_id',
            keys_selects: $keys_selects, id_selected: $r_com_sucursal->registros[0]['com_sucursal_id'],
            label: 'Cliente', columns_ds : $columns_ds);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: $filtro_fac,
            key: 'fc_factura_id', keys_selects: $keys_selects,
            id_selected: $r_fc_factura->registros[0]['fc_factura_id'], label: 'Factura');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $filtro_prod['com_producto.descripcion'] = 'Pago';
        $r_com_producto = (new com_producto(link: $this->link))->filtro_and(filtro: $filtro_prod);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $r_com_producto,
                header: $header,ws:  $ws);
        }

        $id_selected = -1;
        if($r_com_producto->n_registros > 0){
            $id_selected = $r_com_producto->registros[0]['com_producto_id'];
        }

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: $filtro_prod, key: 'com_producto_id',
            keys_selects: $keys_selects, id_selected: $id_selected, label: 'Producto',
            extra_params_keys: array('com_producto_descripcion'));
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols: 6, con_registros: true,filtro: array(),
            key: 'cat_sat_forma_pago_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Forma de Pago');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $columns_ds = array('inm_comprador_nss', 'inm_comprador_nombre', 'inm_comprador_apellido_paterno',
            'inm_comprador_apellido_materno');
        $filtro['inm_comprador.id'] = $this->registro_id;
        $inm_prospecto_id = (new inm_comprador_html(html: $this->html_base))->select_inm_comprador_id(
            cols: 12, con_registros: true, id_selected: $this->registro_id, link: $this->link, columns_ds: $columns_ds,
            filtro: $filtro,label: 'Cliente');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_prospecto_id, header: $header, ws: $ws);
        }

        $this->inputs->inm_comprador_id = $inm_prospecto_id;

        $fecha_pago = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd,
            value_vacio: false, name: 'fecha_pago', place_holder: 'Fecha Pago', required: false,
            value: date("Y-m-d H:i:s"),value_hora: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener fecha_nacimiento',data:  $fecha_pago,
                header: $header, ws:$ws);
        }

        $this->inputs->fecha_pago = $fecha_pago;

        $buttons = $this->buttons_complemento_pago();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener buttons', data: $buttons);
        }

        $this->buttons_base = $buttons;

        $params = array();
        $link_complemento_pago_bd = $this->obj_link->link_con_id(accion:'genera_complemento_pago_bd',
            link: $this->link,registro_id: $this->registro_id,seccion: 'inm_comprador',params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar link',data:  $link_complemento_pago_bd,
                header: $header,ws:  $ws);
        }

        $this->link_complemento_pago_bd = $link_complemento_pago_bd;

        $r_cp = array();
        if($this->row_upd->pago_parcial_precio_compra_venta > 0){
            $temp['monto'] = $this->row_upd->pago_parcial_precio_compra_venta;
            $temp['descripcion_select'] = 'PAGO PARCIAL COMPRA-VENTA - ' . $this->row_upd->pago_parcial_precio_compra_venta;
            $r_cp[1] = $temp;
        }

        $filtro_nc['com_cliente.id'] =  $registro->registros[0]['com_cliente_id'];
        $r_fc_complemento_pago = (new fc_complemento_pago(link: $this->link))->filtro_and(
            filtro: $filtro_nc);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener registro',data:  $r_fc_complemento_pago,header: $header,ws: $ws);
        }

        $complementos_pago = array();

        foreach ($r_fc_complemento_pago->registros as $complemento_pago){
            $timbra_xml = $this->html->button_href(accion: 'modifica', etiqueta: 'Detalles',
                registro_id: $complemento_pago['fc_complemento_pago_id'], seccion: 'fc_complemento_pago', style: 'warning');
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $timbra_xml,header: $header,ws: $ws);
            }
            $complemento_pago['modifica'] = $timbra_xml;

            $timbra_xml = $this->html->button_href(accion: 'timbra_xml', etiqueta: 'Timbra XML',
                registro_id: $complemento_pago['fc_complemento_pago_id'], seccion: 'fc_complemento_pago', style: 'danger');
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $timbra_xml,header: $header,ws: $ws);
            }
            $complemento_pago['timbra_xml'] = $timbra_xml;

            $exporta_documentos = $this->html->button_href(accion: 'exporta_documentos', etiqueta: 'Descargar',
                registro_id: $complemento_pago['fc_complemento_pago_id'], seccion: 'fc_complemento_pago', style: 'success');
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $exporta_documentos,header: $header,ws: $ws);
            }
            $complemento_pago['exporta_documentos'] = $exporta_documentos;

            $elimina_bd = $this->html->button_href(accion: 'elimina_bd', etiqueta: 'Elimina',
                registro_id: $complemento_pago['fc_complemento_pago_id'], seccion: 'fc_complemento_pago',
                style: 'danger');
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener registro',data:  $elimina_bd,header: $header,ws: $ws);
            }
            $complemento_pago['elimina_bd'] = $elimina_bd;

            $complementos_pago[] = $complemento_pago;
        }

        $this->complementos_pago = $complementos_pago;

        $select = $this->html_base->select(cols: 6, id_selected: -1, label: 'Montos Pagos',
            name: 'monto_pago', values: $r_cp, extra_params_key: array("monto"));
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar select', data: $select,header: $header,
                ws:  $ws);
        }

        $this->inputs->monto_pago = $select;

        return $base;
    }

    public function buttons_complemento_pago(): array|string
    {
        $button_fc_factura_complemento_pago =  $this->html->button_href(accion: 'genera_factura',
            etiqueta: 'Regresa a Factura', registro_id: $this->registro_id, seccion: $this->seccion, style: 'warning',
            cols: 12, params: array('inm_comprador_id' => $this->registro_id));
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button_fc_factura_complemento_pago);
        }

        $buttons = $button_fc_factura_complemento_pago;

        return "<div class='col-md-12 buttons-form'>$buttons</div>";
    }

    public function genera_complemento_pago_bd(bool $header, bool $ws = false)
    {
        $this->link->beginTransaction();

        $r_fc_factura = (new fc_factura(link: $this->link))->registro(registro_id: $_POST['fc_factura_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de complemento_pago', data: $r_fc_factura,
                header: $header, ws: $ws);
        }

        $r_com_sucursal = (new com_sucursal(link: $this->link))->registro(registro_id: $_POST['com_sucursal_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_com_sucursal,
                header: $header, ws: $ws);
        }

        $filtro_tipo['cat_sat_moneda.id'] = $r_com_sucursal['cat_sat_moneda_id'];
        $r_moneda = (new com_tipo_cambio(link: $this->link))->filtro_and(filtro: $filtro_tipo);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_moneda,
                header: $header, ws: $ws);
        }

        $r_producto = (new com_producto(link: $this->link))->registro(registro_id: $_POST['com_producto_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_producto,
                header: $header, ws: $ws);
        }

        $filtro_comp['cat_sat_tipo_de_comprobante.descripcion'] = 'Pago';
        $r_comprobante = (new cat_sat_tipo_de_comprobante(link: $this->link))->filtro_and(filtro: $filtro_comp);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_comprobante,
                header: $header, ws: $ws);
        }

        $filtro_uso['cat_sat_uso_cfdi.codigo'] = 'CP01';
        $r_uso_cfdi = (new cat_sat_uso_cfdi(link: $this->link))->filtro_and(filtro: $filtro_uso);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_uso_cfdi,
                header: $header, ws: $ws);
        }

        $filtro_moneda['cat_sat_moneda.codigo'] = 'XXX';
        $r_moneda_pago = (new cat_sat_moneda(link: $this->link))->filtro_and(filtro: $filtro_moneda);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de factura', data: $r_moneda_pago,
                header: $header, ws: $ws);
        }

        $registro['fc_csd_id'] = $r_fc_factura['fc_csd_id'];
        $registro['com_sucursal_id'] = $_POST['com_sucursal_id'];
        $registro['exportacion'] = '01';
        $registro['cat_sat_tipo_de_comprobante_id'] = $r_comprobante->registros[0]['cat_sat_tipo_de_comprobante_id'];
        $registro['cat_sat_metodo_pago_id'] = $r_com_sucursal['cat_sat_metodo_pago_id'];
        $registro['cat_sat_forma_pago_id'] = $r_com_sucursal['cat_sat_forma_pago_id'];
        $registro['cat_sat_moneda_id'] = $r_moneda_pago->registros[0]['cat_sat_moneda_id'];
        $registro['com_tipo_cambio_id'] = $r_moneda->registros[0]['com_tipo_cambio_id'];
        $registro['cat_sat_uso_cfdi_id'] = $r_uso_cfdi->registros[0]['cat_sat_uso_cfdi_id'];
        $registro['observaciones'] = $_POST['observaciones_complemento_pago'];
        $r_fc_complemento_pago = (new fc_complemento_pago(link: $this->link))->alta_registro(registro: $registro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_complemento_pago,
                header: $header, ws: $ws);
        }

        $filtro_fc_pago['fc_complemento_pago.id'] = $r_fc_complemento_pago->registro_id;
        $fc_pago = (new fc_pago(link:$this->link))->filtro_and(filtro: $filtro_fc_pago);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $fc_pago,
                header: $header, ws: $ws);
        }

        /*$cantidad = 1;
        if(isset($_POST['cantidad'])){
            $cantidad = $_POST['cantidad'];
        }

        $descuento = 0;
        if(isset($_POST['descuento'])){
            $descuento = $_POST['descuento'];
        }

        $registro_partida['fc_complemento_pago_id'] = $r_fc_complemento_pago->registro_id;
        $registro_partida['com_producto_id'] = $_POST['com_producto_id'];
        $registro_partida['cat_sat_obj_imp_id'] = $r_producto['cat_sat_obj_imp_id'];
        //$registro_partida['descripcion'] = $_POST['descripcion_complemento_pago'];
        $registro_partida['cantidad'] = $cantidad;
        $registro_partida['descuento'] = $descuento;
        $registro_partida['valor_unitario'] = $_POST['valor_unitario_complemento_pago'];
        $registro_partida['cat_sat_conf_imps_id'] = $r_producto['cat_sat_conf_imps_id'];
        $r_fc_partida = (new fc_partida_cp(link: $this->link))->alta_registro(registro: $registro_partida);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_partida,
                header: $header, ws: $ws);
        }*/

        $registro_pago = array();
        $registro_pago['fecha_pago'] = $_POST['fecha_pago'];
        $registro_pago['cat_sat_forma_pago_id'] = $_POST['cat_sat_forma_pago_id'];
        $registro_pago['fc_pago_id'] = $fc_pago->registros[0]['fc_pago_id'];
        $registro_pago['com_tipo_cambio_id'] = $r_moneda->registros[0]['com_tipo_cambio_id'];
        $registro_pago['monto'] = $_POST['valor_unitario_complemento_pago'];
        $r_fc_pago_pago = (new fc_pago_pago(link: $this->link))->alta_registro(registro: $registro_pago);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_pago_pago,
                header: $header, ws: $ws);
        }

        $registro_relacionado = array();
        $registro_relacionado['fc_factura_id'] = $_POST['fc_factura_id'];
        $registro_relacionado['fc_pago_pago_id'] = $r_fc_pago_pago->registro_id;
        $registro_relacionado['imp_pagado'] = $_POST['valor_unitario_complemento_pago'];
        $r_fc_docto_relacionado = (new fc_docto_relacionado(link: $this->link))->alta_registro(registro: $registro_relacionado);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_fc_docto_relacionado,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        //$params = array('pestana_general_actual' => 'pestanageneral2');
        $link_proceso_comprador = $this->obj_link->link_con_id(
            accion: 'genera_complemento_pago', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_comprador',
            params: array());
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_comprador, header: $header, ws: $ws);
        }

        if($header) {
            header('Location:' . $link_proceso_comprador);
            exit;
        }

        return $this->registro_id;
    }
}
