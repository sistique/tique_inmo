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
use gamboamartin\administrador\models\adm_campo;
use gamboamartin\calculo\calculo;
use gamboamartin\comercial\models\com_agente;
use gamboamartin\comercial\models\com_direccion;
use gamboamartin\comercial\models\com_direccion_prospecto;
use gamboamartin\comercial\models\com_prospecto;
use gamboamartin\comercial\models\com_prospecto_etapa;
use gamboamartin\comercial\models\com_rel_agente;
use gamboamartin\comercial\models\com_tipo_prospecto;
use gamboamartin\controllers\_controlador_adm_reporte\_fechas;
use gamboamartin\controllers\_controlador_adm_reporte\_filtros;
use gamboamartin\controllers\_controlador_adm_reporte\_table;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_prospecto_html;
use gamboamartin\inmuebles\html\inm_status_prospecto_html;
use gamboamartin\inmuebles\models\_base_paquete;
use gamboamartin\inmuebles\models\_dropbox;
use gamboamartin\inmuebles\models\_email;
use gamboamartin\inmuebles\models\_inm_prospecto;
use gamboamartin\inmuebles\models\_upd_prospecto;
use gamboamartin\inmuebles\models\inm_beneficiario;
use gamboamartin\inmuebles\models\inm_comprador;
use gamboamartin\inmuebles\models\inm_conf_docs_prospecto;
use gamboamartin\inmuebles\models\inm_conf_institucion_campo;
use gamboamartin\inmuebles\models\inm_doc_comprador;
use gamboamartin\inmuebles\models\inm_doc_prospecto;
use gamboamartin\inmuebles\models\inm_prospecto;
use gamboamartin\inmuebles\models\inm_rel_beneficiario_prospecto;
use gamboamartin\inmuebles\models\inm_rel_referencia_prospecto;
use gamboamartin\inmuebles\models\inm_status_prospecto;
use gamboamartin\inmuebles\models\inm_tipo_beneficiario;
use gamboamartin\plugins\exportador;
use gamboamartin\proceso\html\pr_etapa_proceso_html;
use gamboamartin\system\actions;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\doc_tipo_documento_html;
use PDO;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader\PdfReader;
use setasign\Fpdi\PdfReader\PdfReaderException;
use stdClass;
use Throwable;
use Tomsgu\PdfMerger\Exception\FileNotFoundException;
use Tomsgu\PdfMerger\Exception\InvalidArgumentException;
use Tomsgu\PdfMerger\PdfCollection;
use Tomsgu\PdfMerger\PdfFile;
use Tomsgu\PdfMerger\PdfMerger;

class controlador_inm_prospecto extends _ctl_formato
{

    public stdClass $header_frontend;
    public inm_prospecto_html $html_entidad;

    public string $link_alta_bitacora = '';
    public array $etapas = array();
    public array $relaciones = array();
    public string $link_documento_bd = '';
    public string $link_alta_integra_relacion_bd = '';
    public string $link_inm_doc_prospecto_alta_bd = '';
    public string $link_modifica_direccion = '';
    public string $link_agrupa_documentos = '';
    public string $link_verifica_documentos = '';
    public string $link_envia_documentos = '';
    public string $link_exportar_xls ='';


    public array $inm_conf_docs_prospecto = array();

    public array $direcciones = array();
    public array $beneficiarios = array();
    public array $referencias = array();
    public array $acciones_headers = array();
    public array $status_prospecto = array();
    public bool $es_agente = false;


    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_prospecto(link: $link);
        $html_ = new inm_prospecto_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable(link: $link);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->html_entidad = $html_;

        $this->header_frontend = new stdClass();
        $this->lista_get_data = true;

        $this->acciones_headers = array();

        $init_links = $this->init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
            print_r($error);
            die('Error');
        }

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
     * Genera un formulario de alta
     * @param bool $header Muestra resultado en web
     * @param bool $ws Muestra resultado a nivel ws
     * @return array|string
     */
    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        // Pre-pobla $keys_selects con id_selected desde sesión para que key_select()
        // respete los valores que el usuario tenía al enviar el formulario.
        $keys_selects = $this->init_keys_selects_desde_proceso();
        $keys_selects = (new _keys_selects())->keys_selects_prospecto(controler: $this, keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects', data: $keys_selects,
                header: $header, ws: $ws);
        }

        $filtro_agente['adm_usuario.id'] = $_SESSION['usuario_id'];
        $r_agente = (new com_agente(link: $this->link))->filtro_and(filtro: $filtro_agente);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al insertar prospecto',data:  $r_agente,
                header: $header, ws: $ws);
        }

        $filtro_agente_sel = array();
        $com_agente_id = -1;
        $con_registros = false;

        if($r_agente->n_registros > 0){
            $filtro_agente_sel['com_agente.id'] = $r_agente->registros[0]['com_agente_id'];
            $com_agente_id = $r_agente->registros[0]['com_agente_id'];

            $con_registros = true;
        }

        $keys_selects = $this->key_select(cols:4, con_registros: $con_registros,filtro: $filtro_agente_sel, key: 'com_agente_id',
            keys_selects:$keys_selects, id_selected: $com_agente_id, label: 'Agente');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects', data: $keys_selects,
                header: $header, ws: $ws);
        }

        $filtro_sucursal_sel = array();
        $org_sucursal_id = -1;
        if($r_agente->n_registros > 0){
            $filtro_sucursal_sel['org_sucursal.id'] = $r_agente->registros[0]['org_sucursal_id'];
            $org_sucursal_id = $r_agente->registros[0]['org_sucursal_id'];
        }

        $keys_selects = $this->key_select(cols:4, con_registros: true,filtro: $filtro_sucursal_sel, key: 'org_sucursal_id',
            keys_selects:$keys_selects, id_selected: $org_sucursal_id, label: 'Empresa');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects', data: $keys_selects,
                header: $header, ws: $ws);
        }


        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'liga_red_social',
            keys_selects: $keys_selects, place_holder: 'Liga Red Social', required: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects', data: $keys_selects,
                header: $header, ws: $ws);
        }
        $keys_selects['liga_red_social']->disabled = true;

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: array(),
            key: 'com_medio_prospeccion_id', keys_selects: $keys_selects, id_selected: -1,
            label: 'Medio de Prospeccion');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects', data: $keys_selects,
                header: $header, ws: $ws);
        }
        $extra_params_keys[] = 'com_medio_prospeccion_id';
        $extra_params_keys[] = 'com_medio_prospeccion_es_red_social';
        $keys_selects['com_medio_prospeccion_id']->extra_params_keys = $extra_params_keys;

        $filtro_tipo['com_tipo_prospecto.descripcion'] = 'VENTA DE VIVIENDA';
        $com_tipo_prospecto_reg = (new com_tipo_prospecto(link: $this->link))->filtro_and(filtro: $filtro_tipo);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects', data: $keys_selects,
                header: $header, ws: $ws);
        }

        $com_tipo_prospecto_id = -1;
        if($com_tipo_prospecto_reg->n_registros > 0){
            $com_tipo_prospecto_id = $com_tipo_prospecto_reg->registros[0]['com_tipo_prospecto_id'];
        }

        $keys_selects = $this->key_select(cols:4, con_registros: true,filtro: $filtro_tipo,
            key: 'com_tipo_prospecto_id', keys_selects:$keys_selects, id_selected: $com_tipo_prospecto_id,
            label: 'Tipo de prospecto');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects', data: $keys_selects,
                header: $header, ws: $ws);
        }

        $keys_selects = $this->key_select(cols: 3, con_registros: true, filtro: array(), key: 'inm_tipo_venta_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Tipo de Venta');
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects', data: $keys_selects,
                header: $header, ws: $ws);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        $nss = $this->html->input_text(cols: 3, disabled: false, name: 'nss', place_holder: 'Nss',
            row_upd: new stdClass(), value_vacio: false, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $nss,  header: $header, ws: $ws);
        }

        $this->inputs->nss = $nss;
        
        $nombre = $this->html->input_text(cols: 3, disabled: false, name: 'nombre', place_holder: 'Nombre',
            row_upd: new stdClass(), value_vacio: false, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $nombre,  header: $header, ws: $ws);
        }

        $this->inputs->nombre = $nombre;

        $apellido_paterno = $this->html->input_text(cols: 3, disabled: false, name: 'apellido_paterno',
            place_holder: 'Apellido Paterno', row_upd: new stdClass(), value_vacio: false, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $apellido_paterno,  header: $header, ws: $ws);
        }

        $this->inputs->apellido_paterno = $apellido_paterno;

        $apellido_materno = $this->html->input_text(cols: 3, disabled: false, name: 'apellido_materno',
            place_holder: 'Apellido Materno', row_upd: new stdClass(), value_vacio: false, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $apellido_materno,  header: $header, ws: $ws);
        }

        $this->inputs->apellido_materno = $apellido_materno;
        
        $devolucion = $this->html->input_text(cols: 3, disabled: false, name: 'devolucion',
            place_holder: 'Devolucion', row_upd: new stdClass(), value_vacio: false, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $devolucion,  header: $header, ws: $ws);
        }

        $this->inputs->devolucion = $devolucion;    
        
        $numero_com = $this->html->input_text(cols: 2, disabled: false, name: 'numero_com',
            place_holder: 'Telefono', row_upd: new stdClass(), value_vacio: false,
            regex: $this->validacion->patterns['telefono_mx_html'], required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $numero_com,  header: $header, ws: $ws);
        }

        $this->inputs->numero_com = $numero_com;    
        
        $cel_com = $this->html->input_text(cols: 2, disabled: false, name: 'cel_com',
            place_holder: 'Celular', row_upd: new stdClass(), value_vacio: false,
            regex: $this->validacion->patterns['telefono_mx_html'], required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $cel_com,  header: $header, ws: $ws);
        }

        $this->inputs->cel_com = $cel_com;
        
        $correo_com = $this->html->input_text(cols: 2, disabled: false, name: 'correo_com',
            place_holder: 'Correo', row_upd: new stdClass(), value_vacio: false,
            regex: $this->validacion->patterns['correo_html5'], required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $correo_com,  header: $header, ws: $ws);
        }

        $this->inputs->correo_com = $correo_com;

        $documento = $this->html->input_file(cols: 12, name: 'precalificacion', row_upd: new stdClass(),
            value_vacio: false, place_holder: 'Precalificacion', required: false);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $documento, header: $header, ws: $ws);
        }

        $this->inputs->documento = $documento;

        return $r_alta;
    }

    /**
     * @throws FileNotFoundException
     * @throws PdfReaderException
     * @throws InvalidArgumentException
     */
    final public function agrupa_documentos(bool $header, bool $ws = false): array|string
    {
        $documentos = explode(',', $_POST['documentos']);
        $pdfCollection = new PdfCollection();

        foreach ($documentos as $documento) {
            $registro = (new inm_doc_prospecto($this->link))->registro(registro_id: $documento, retorno_obj: true);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al obtener documento', data: $registro, header: $header, ws: $ws);
            }
            $ruta_doc = $this->path_base . "$registro->doc_documento_ruta_relativa";
            $pdfCollection->addPdf($ruta_doc);
        }

        $fpdi = new Fpdi();
        $pdfMerger = new PdfMerger($fpdi);

        $outputPath = 'documentos.pdf';
        $pdfMerger->merge($pdfCollection, $outputPath, PdfMerger::MODE_DOWNLOAD);

        return $documentos;
    }

    /**
     * Inicializa los campos view para frontend
     * @return array
     * @version 2.250.2
     */
    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->textareas = array('direccion_empresa');
        $keys->dates = array('fecha_nacimiento');
        $keys->inputs = array('nombre', 'apellido_paterno', 'apellido_materno', 'telefono', 'correo_com', 'razon_social',
            'lada_com', 'numero_com', 'cel_com', 'descuento_pension_alimenticia_dh', 'descuento_pension_alimenticia_fc',
            'monto_credito_solicitado_dh', 'monto_ahorro_voluntario', 'nombre_empresa_patron', 'nrp_nep', 'lada_nep',
            'numero_nep', 'extension_nep', 'nss', 'curp', 'rfc', 'numero_exterior', 'numero_interior', 'observaciones',
            'sub_cuenta', 'monto_final', 'descuento', 'puntos', 'telefono_casa', 'correo_empresa',
            'correo_mi_cuenta_infonavit', 'password_mi_cuenta_infonavit', 'nss_extra', 'liga_red_social', 'area_empresa',
            'texto_exterior', 'texto_interior', 'documentos', 'receptor', 'asunto', 'mensaje','calle','devolucion');

        $keys->selects = array();

        $init_data = array();
        $init_data['com_agente'] = "gamboamartin\\comercial";
        $init_data['com_tipo_prospecto'] = "gamboamartin\\comercial";
        $init_data['com_medio_prospeccion'] = "gamboamartin\\comercial";
        $init_data['com_prospecto'] = "gamboamartin\\comercial";

        $init_data['inm_institucion_hipotecaria'] = "gamboamartin\\inmuebles";
        $init_data['inm_producto_infonavit'] = "gamboamartin\\inmuebles";
        $init_data['adm_estado_civil'] = "gamboamartin\\administrador";
        $init_data['inm_estado_civil'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_credito'] = "gamboamartin\\inmuebles";
        $init_data['inm_attr_tipo_credito'] = "gamboamartin\\inmuebles";
        $init_data['inm_destino_credito'] = "gamboamartin\\inmuebles";
        $init_data['inm_plazo_credito_sc'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_discapacidad'] = "gamboamartin\\inmuebles";
        $init_data['inm_persona_discapacidad'] = "gamboamartin\\inmuebles";
        $init_data['inm_sindicato'] = "gamboamartin\\inmuebles";
        $init_data['inm_ocupacion'] = "gamboamartin\\inmuebles";
        $init_data['com_tipo_direccion'] = "gamboamartin\\comercial";
        $init_data['org_sucursal'] = "gamboamartin\\organigrama";
        $init_data['inm_tipo_venta'] = "gamboamartin\\inmuebles";

        $init_data = (new _base_paquete())->init_data_domicilio(init_data: $init_data);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $init_data);
        }

        $init_data['inm_nacionalidad'] = "gamboamartin\\inmuebles";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);

        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }


        return $campos_view;
    }

    /**
     * Convierte un prospecto en cliente
     * @param bool $header Muestra resultado en web
     * @param bool $ws Muestra resultado a nivel ws
     * @return array|string
     */
    public function convierte_cliente(bool $header, bool $ws = false): array|string
    {
        $this->link->beginTransaction();

        if ($this->registro_id <= 0) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error registro_id debe ser mayor a 0', data: $this->registro_id,
                header: true, ws: false);
        }

        $retorno = (new _base())->init_retorno();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener datos de retorno', data: $retorno,
                header: true, ws: false, class: __CLASS__, file: __FILE__, function: __FILE__, line: __LINE__);
        }

        $conversion = (new inm_prospecto(link: $this->link))->convierte_cliente(inm_prospecto_id: $this->registro_id);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al convertir en cliente', data: $conversion,
                header: true, ws: false, class: __CLASS__, file: __FILE__, function: __FILE__, line: __LINE__);
        }

        $filtro['inm_prospecto.id'] = $this->registro_id;
        $r_doc_prospecto = (new inm_doc_prospecto(link:$this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al convertir en cliente', data: $r_doc_prospecto,
                header: true, ws: false, class: __CLASS__, file: __FILE__, function: __FILE__, line: __LINE__);
        }

        if($r_doc_prospecto->n_registros > 0){
            foreach ($r_doc_prospecto->registros as $registro) {
                $ruta = $registro['doc_documento_ruta_absoluta'];
                if((new generales())->guarda_archivo_dropbox && trim((string)$registro['inm_dropbox_ruta_id_dropbox']) !== '') {
                    $guarda = (new _dropbox(link: $this->link))->preview(
                        dropbox_id: $registro['inm_dropbox_ruta_id_dropbox'],
                        extencion: $registro['doc_extension_descripcion']);
                    if (errores::$error) {
                        return $this->retorno_error('Error al guardar archivo', $guarda, header: $header,
                            ws: $ws);
                    }

                    $ruta = $this->path_base.$guarda->ruta_archivo;
                }

                $_FILES['documento'] = [
                    'name' => basename($ruta),
                    'type' => mime_content_type($ruta),
                    'tmp_name' => $ruta,
                    'error' => 0,
                    'size' => filesize($ruta)
                ];

                $registro_doc['inm_comprador_id'] = $conversion->r_alta_comprador->registro_id;
                $registro_doc['doc_tipo_documento_id'] = $registro['doc_tipo_documento_id'];

                $r_inm_doc_comprador = (new inm_doc_comprador(link:$this->link))->alta_registro(registro: $registro_doc);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al convertir en cliente', data: $r_inm_doc_comprador,
                        header: true, ws: false, class: __CLASS__, file: __FILE__, function: __FILE__, line: __LINE__);
                }
            }
        }

        $this->link->commit();

        $controlador_inm_comprador = new controlador_inm_comprador(link:$this->link);
        $retorno->id_retorno = $conversion->r_alta_comprador->registro_id;
        $retorno->siguiente_view = 'modifica';

        if($header){
            $controlador_inm_comprador->retorno_base(registro_id: $retorno->id_retorno, result: $conversion,
                siguiente_view: $retorno->siguiente_view, ws:  $ws, seccion_retorno: 'inm_comprador',
                valida_permiso: true);
        }

        return $conversion->r_alta_rel;


    }

    final public function documentos(bool $header, bool $ws = false): array|stdClass
    {
        if(isset($_GET['accion']) && $_GET['accion'] == 'documentos') {
            $template = $this->modifica(header: false);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
            }
        }

        $inm_conf_docs_prospecto = (new _inm_prospecto())->integra_inm_documentos(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar buttons', data: $inm_conf_docs_prospecto, header: $header, ws: $ws);
        }

        $temp = array();
        foreach ($inm_conf_docs_prospecto as $docs){
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

        $this->inm_conf_docs_prospecto = $temp;

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] === 'proceso_prospecto') {
            $params = array('pestana_general_actual' => 'pestanageneral1',
                'pestana_actual' => 'pestanaprospecto2');
        }
        $link_documento_bd = $this->obj_link->link_con_id(
            accion: 'documentos_bd', link: $this->link, registro_id: $this->registro_id, seccion: 'inm_prospecto',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_documento_bd, header: $header, ws: $ws);
        }

        $this->link_documento_bd = $link_documento_bd;

        /*$keys_selects = $this->init_selects_inputs();
        if (errores::$error) {return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
        }

        $keys_selects['com_tipo_prospecto_id']->id_selected = $this->registro['com_tipo_prospecto_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $this->row_upd->asunto = "TU MENSAJE";
        $this->row_upd->mensaje = "TU MENSAJE";
        $this->inm_conf_docs_prospecto = $inm_conf_docs_prospecto;
*/
        //print_r($this->row_upd);


        return $this->inputs;
    }

    public function documentos_bd(bool $header, bool $ws = false): array|stdClass{
        $inm_doc_prospecto =  new inm_doc_prospecto(link: $this->link);

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
                    $registro['inm_prospecto_id'] = $this->registro_id;
                    $_FILES['documento'] = $valor;
                    $result = $inm_doc_prospecto->alta_registro(registro: $registro);
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
        }        $link_proceso_prospecto = $this->obj_link->link_con_id(
            accion: $accion, link: $this->link, registro_id: $this->registro_id, seccion: 'inm_prospecto',params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_proceso_prospecto, header: $header, ws: $ws);
        }
        if($header) {
            header('Location:' . $link_proceso_prospecto);
            exit;
        }

        return $result;
    }

    final public function envia_documentos(bool $header, bool $ws = false): array|string
    {
        $campos_necesarios = $this->valida_campos($_POST);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al validar campos', data: $campos_necesarios,
                header: $header, ws: $ws);
        }

        $validacion = (new _email($this->link))->validar_correo(correo: $campos_necesarios['receptor']);
        if (!$validacion) {
            $mensaje_error = sprintf(_email::ERROR_CORREO_NO_VALIDO, $campos_necesarios['receptor']);
            return $this->retorno_error(mensaje: $mensaje_error, data: $campos_necesarios,
                header: $header, ws: $ws);
        }

        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        $receptor = (new _email($this->link))->receptor(correo: $campos_necesarios['receptor']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener receptor', data: $receptor,
                header: $header, ws: $ws);
        }

        $emisor = (new _email($this->link))->emisor(correo: 'test@ivitec.mx');
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener emisor', data: $emisor,
                header: $header, ws: $ws);
        }

        $mensaje = (new _email($this->link))->mensaje(asunto: $campos_necesarios['asunto'],
            mensaje: $campos_necesarios['mensaje'], emisor: $emisor['not_emisor_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener mensaje', data: $mensaje,
                header: $header, ws: $ws);
        }

        $mensaje_receptor = (new _email($this->link))->mensaje_receptor(mensaje: $mensaje['not_mensaje_id'],
            receptor: $receptor['not_receptor_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener mensaje receptor', data: $mensaje_receptor,
                header: $header, ws: $ws);
        }

        $documentos = explode(',', $campos_necesarios);
        $r_alta_doc_etapa = new stdClass();

        $mensaje_adjuntos = (new _email($this->link))->adjuntos(mensaje: $mensaje['not_mensaje_id'],
            documentos: $documentos);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener adjuntos', data: $mensaje_adjuntos,
                header: $header, ws: $ws);
        }

        print_r($mensaje_adjuntos);
        exit();

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id: $this->registro_id, result: $r_alta_doc_etapa,
                siguiente_view: "documentos", ws: $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_alta_doc_etapa, JSON_THROW_ON_ERROR);
            exit;
        }
        $r_alta_doc_etapa->siguiente_view = "documentos";

        return $r_alta_doc_etapa;
    }

    public function etapa(bool $header, bool $ws = false): array|stdClass
    {
        if(isset($_GET['accion']) && $_GET['accion'] == 'etapa') {
            $template = $this->modifica(header: false);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
            }
        }
        $columns_ds[] = 'inm_status_prospecto_descripcion';

        $inm_status_prospecto_id = (new inm_status_prospecto_html(html: $this->html_base))->select_inm_status_prospecto_id(
            cols: 6, con_registros: true, id_selected: -1, link: $this->link, columns_ds: $columns_ds,
            label: 'Status Prospecto');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener selector de etapa', data: $inm_status_prospecto_id, header: $header, ws: $ws);
        }

        $com_agentes = (new com_agente(link: $this->link))->com_agentes_session();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener agentes',data:  $com_agentes, header: $header, ws: $ws);
        }

        $disabled = false;
        if(count($com_agentes) > 0){
            $disabled = true;
        }

        $this->inputs->inm_status_prospecto_id = $inm_status_prospecto_id;

        $hoy = date('Y-m-d\TH:i:s');
        $fecha = $this->html->input_fecha(cols: 6, row_upd: new stdClass(), value_vacio: false, disabled: $disabled,
            name: 'fecha_status', value: $hoy, value_hora: true);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input fecha', data: $fecha, header: $header, ws: $ws);
        }

        $this->inputs->fecha = $fecha;

        $observaciones = $this->html->input_text(cols: 12, disabled: false, name: 'observaciones', place_holder: 'Observaciones',
            row_upd: new stdClass(), value_vacio: false, required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $observaciones,  header: $header, ws: $ws);
        }

        $this->inputs->observaciones = $observaciones;

        $inm_prospecto_id = $this->html->hidden(name:'inm_prospecto_id',value: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener input',data:  $inm_prospecto_id,  header: $header, ws: $ws);
        }

        $this->inputs->inm_prospecto_id = $inm_prospecto_id;

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] === 'proceso_prospecto') {
            $params = array('pestana_general_actual' => 'pestanageneral1',
                'pestana_actual' => 'pestanaprospecto4');
        }
        $link_alta_bitacora= $this->obj_link->link_alta_bd(link: $this->link, seccion: 'inm_bitacora_status_prospecto',
            params: $params);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar link', data: $link_alta_bitacora, header: $header,
                ws: $ws);
        }

        $this->link_alta_bitacora = $link_alta_bitacora;

        $etapas = (new inm_prospecto(link: $this->link))->status_prospecto(inm_prospecto_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener etapas', data: $etapas, header: $header, ws: $ws);
        }

        $this->etapas = $etapas;

        return $this->inputs;
    }

    public function exportar_xls(bool $header, bool $ws = false)
    {
        $nombre_hojas = array('Prospectos');
        $keys_hojas = array();

        $registros = $this->result_inm_prosp();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_prospecto', data: $registros, header: $header,
                ws: $ws);
        }

        $ths[] = array('etiqueta'=>'ID', 'campo'=>'inm_prospecto_id');
        $ths[] = array('etiqueta'=>'Nombre Prospecto', 'campo'=>'inm_prospecto_razon_social');
        $ths[] = array('etiqueta'=>'NSS', 'campo'=>'inm_prospecto_nss');
        $ths[] = array('etiqueta'=>'Precalificacion', 'campo'=>'inm_prospecto_monto_credito_solicitado_dh');
        $ths[] = array('etiqueta'=>'Fecha Alta', 'campo'=>'inm_prospecto_fecha_alta');
        $ths[] = array('etiqueta'=>'Agente', 'campo'=>'com_agente_descripcion');
        $ths[] = array('etiqueta'=>'Status Prospecto', 'campo'=>'inm_status_prospecto_descripcion');

        $keys = array();
        foreach ($ths as $data_th) {
            $keys[] = $data_th['campo'];
        }

        $keys_hojas['Prospectos'] = new stdClass();
        $keys_hojas['Prospectos']->keys = $keys;
        $keys_hojas['Prospectos']->registros = $registros->registros;


        $moneda = array();
        $totales_hoja = new stdClass();
        //$totales_hoja->Prospectos = (array)$registros->totales;
        $xls = (new exportador())->genera_xls(header: $header, name: 'Prospectos', nombre_hojas: $nombre_hojas,
            keys_hojas: $keys_hojas, path_base: $this->path_base, moneda: $moneda/*, totales_hoja: $totales_hoja*/);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener xls', data: $xls, header: $header, ws: $ws);
        }

    }

    private function result_inm_prosp(): array|stdClass
    {
        $result = new stdClass();
        $result->registros = array();
        $result->totales = array();

        $table = 'inm_prospecto';

        $filtro_rango = array();
        if(!empty($_POST['fecha_inicial'])){
            $filtro_rango[$table.'.fecha_alta']['valor1'] = $_POST['fecha_inicial'];
        }
        if(!empty($_POST['fecha_final'])) {
            $filtro_rango[$table . '.fecha_alta']['valor2'] = $_POST['fecha_final'];
        }

        $filtro_especial = array();

        if(!empty($_POST['nombre_prospecto'])){
            $filtro_especial[0][$table.'.razon_social']['operador'] = 'LIKE';
            $filtro_especial[0][$table.'.razon_social']['valor'] = '%'.$_POST['nombre_prospecto'].'%';
            $filtro_especial[0][$table.'.razon_social']['comparacion'] = 'AND';

            //$filtro_text[$table.'.razon_social'] = $_POST['nombre_prospecto'];
        }

        if(!empty($_POST['nss'])){
            $filtro_especial[1][$table.'.nss']['operador'] = 'LIKE';
            $filtro_especial[1][$table.'.nss']['valor'] = '%'.$_POST['nss'].'%';
            $filtro_especial[1][$table.'.nss']['comparacion'] = 'AND';

            $filtro_text[$table.'.nss'] = $_POST['nss'];
        }

        if(!empty($_POST['agente'])){
            $filtro_especial[2]['com_agente.descripcion']['operador'] = 'LIKE';
            $filtro_especial[2]['com_agente.descripcion']['valor'] = '%'.$_POST['agente'].'%';
            $filtro_especial[2]['com_agente.descripcion']['comparacion'] = 'AND';

            $filtro_text['com_agente.descripcion'] = $_POST['agente'];
        }

        $in = array();
        if(!empty($_POST['inm_status_prospecto'])){
            $array = explode(",", $_POST['inm_status_prospecto']);
            $in['llave'] = 'inm_status_prospecto.descripcion';
            $in['values'] = $array;
        }

        /*$columnas_totales[] = 'inm_prospecto_sub_total_base';
        $columnas_totales[] = 'inm_prospecto_total_descuento';
        $columnas_totales[] = 'inm_prospecto_total_traslados';
        $columnas_totales[] = 'inm_prospecto_total_retenciones';
        $columnas_totales[] = 'inm_prospecto_total';*/

        $result = (new inm_prospecto(link: $this->link))->filtro_and(filtro_especial: $filtro_especial,
            filtro_rango: $filtro_rango, in: $in);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener prospectos', data: $result);
        }

        return $result;
    }

    private function init_selects(array $keys_selects, string $key, string $label, int|null $id_selected = -1, int $cols = 6,
                                  bool  $con_registros = true, array $filtro = array()): array
    {
        $keys_selects = $this->key_select(cols: $cols, con_registros: $con_registros, filtro: $filtro, key: $key,
            keys_selects: $keys_selects, id_selected: $id_selected, label: $label);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }
    /**
     * POR DOCUMENTAR EN WIKI
     * Inicializa el objeto de datos 'datatables' con cinco columnas - Id, Nombre, NSS, RFC y CURP.
     * También establece el filtro en estas columnas para buscar y filtrar en la tabla de datos.
     *
     * @return stdClass Un objeto con dos propiedades 'columns' y 'filtro', que definen las columnas que se mostrarán
     *  en la tabla de datos y los campos a filtrar respectivamente.
     * @version 2.347.3
     *
     */
    private function init_datatable(PDO $link): stdClass|array
    {

        $filtro_agente['adm_usuario.id'] = $_SESSION['usuario_id'];
        $existe = (new com_agente(link: $link))->existe(filtro: $filtro_agente);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al insertar prospecto',data:  $existe);
        }

        $columns["inm_prospecto_id"]["titulo"] = "Id";
        $columns["inm_prospecto_nss"]["titulo"] = "NSS";
        $columns["inm_prospecto_razon_social"]["titulo"] = "Nombre";
        $columns["inm_prospecto_fecha_alta"]["titulo"] = "Fecha Alta";
        if(!$existe){
            $columns["inm_prospecto_monto_credito_solicitado_dh"]["titulo"] = "Precalificacion";
            $columns["com_agente_descripcion"]["titulo"] = "Agente";
        }
        $columns["inm_status_prospecto_descripcion"]["titulo"] = "Status Prospecto";


        $filtro = array("inm_prospecto.id", "inm_prospecto.razon_social", 'inm_prospecto.nss',
            'inm_prospecto.monto_credito_solicitado_dh','inm_prospecto.fecha_alta', 'com_agente.descripcion',
            'inm_status_prospecto.descripcion');

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;
        $datatables->menu_active = true;
        $datatables->order_sec = array('inm_status_prospecto.id' => 'ASC','inm_prospecto.id' => 'DESC');

        $datatables->dom = "<'row'<'col-md-12'tr>><'row mt-2'<'col-md-4'l><'col-md-4'i><'col-md-4'p>>";

        return $datatables;
    }

    protected function init_links(): array|string
    {
        $links = $this->obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "inm_prospecto", accion: "modifica_direccion");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link modifica_direccion', data: $link);
            print_r($error);
            exit;
        }
        $this->link_modifica_direccion = $link;

        $link = $this->obj_link->get_link(seccion: "inm_prospecto", accion: "agrupa_documentos");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link agrupa_documentos', data: $link);
            print_r($error);
            exit;
        }
        $this->link_agrupa_documentos = $link;

        $link = $this->obj_link->get_link(seccion: "inm_prospecto", accion: "verifica_documentos");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link verifica_documentos', data: $link);
            print_r($error);
            exit;
        }
        $this->link_verifica_documentos = $link;

        $link = $this->obj_link->get_link(seccion: "inm_prospecto", accion: "envia_documentos");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link envia_documentos', data: $link);
            print_r($error);
            exit;
        }
        $this->link_envia_documentos = $link;

        return $link;
    }

    public function inserta_beneficiario(bool $header, bool $ws): array|stdClass
    {
        $r_inm_beneficiario_bd = (new _upd_prospecto())->inserta_beneficiario(beneficiario: $_POST,
            inm_prospecto_id: $_GET['registro_id'], link: $this->link);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al insertar r_inm_beneficiario_bd', data: $r_inm_beneficiario_bd,
                header: $header, ws: $ws);
        }

        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_inm_beneficiario_bd, JSON_THROW_ON_ERROR);
            exit;
        }

        return $r_inm_beneficiario_bd;
    }

    public function inserta_domicilio(bool $header, bool $ws): array|stdClass
    {
        $domicilio = (new _upd_prospecto())->inserta_domicilio(domicilio: $_POST,
            inm_prospecto_id: $this->registro_id, link: $this->link);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al insertar domicilio', data: $domicilio,
                header: $header, ws: $ws);
        }

        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($domicilio, JSON_THROW_ON_ERROR);
            exit;
        }

        return $domicilio;
    }

    public function inserta_referencia(bool $header, bool $ws): array|stdClass
    {
        $r_inm_referencia_bd = (new _upd_prospecto())->inserta_referencia(referencia: $_POST,
            inm_prospecto_id: $_GET['registro_id'], link: $this->link);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al insertar r_inm_referencia_bd', data: $r_inm_referencia_bd,
                header: $header, ws: $ws);
        }

        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_inm_referencia_bd, JSON_THROW_ON_ERROR);
            exit;
        }

        return $r_inm_referencia_bd;
    }

    public function integra_relacion(bool $header, bool $ws = false): array|stdClass
    {
        if(isset($_GET['accion']) && $_GET['accion'] == 'integra_relacion') {
            $template = $this->modifica(header: false);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al integrar base', data: $template, header: $header, ws: $ws);
            }
        }

        $keys_selects = array();
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "com_agente_id", label: "Agente",cols: 12);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar selector',data:  $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razon Social',disabled: true);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $inm_prospecto = (new inm_prospecto($this->link))->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $inm_prospecto, header: $header, ws: $ws);
        }

        $filtro['com_prospecto.id'] = $inm_prospecto['com_prospecto_id'];
        $order = array('com_rel_agente.id'=>'DESC');
        $relaciones = (new com_rel_agente(link: $this->link))->filtro_and(filtro: $filtro,order: $order);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al obtener etapas', data: $relaciones, header: $header, ws: $ws);
        }

        $this->relaciones = $relaciones->registros;

        $params = array();
        if(isset($_GET['accion']) && $_GET['accion'] === 'proceso_prospecto') {
            $params = array('pestana_general_actual' => 'pestanageneral1',
                'pestana_actual' => 'pestanaprospecto3');
        }
        $link_integra_relacion_bd = $this->obj_link->link_con_id(
            accion: 'integra_relacion_bd', link: $this->link, registro_id: $this->registro_id,
            seccion: 'inm_prospecto',params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_integra_relacion_bd, header: $header, ws: $ws);
        }

        $this->link_alta_integra_relacion_bd = $link_integra_relacion_bd;


        return $this->inputs;
    }

    public function integra_relacion_bd(bool $header, bool $ws = false): array|stdClass
    {

        $this->link->beginTransaction();

        $modelo_inm_prospecto = new inm_prospecto($this->link);
        $inm_prospecto = $modelo_inm_prospecto->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $inm_prospecto, header: $header, ws: $ws);
        }

        $con_rel_agente = new com_rel_agente($this->link);

        $registro['com_agente_id'] = $_POST['com_agente_id'];
        $registro['com_prospecto_id'] = $inm_prospecto['com_prospecto_id'];
        $result = $con_rel_agente->alta_registro(registro: $registro);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $result, header: $header, ws: $ws);
        }

        $registros_prosp['com_agente_id'] = $_POST['com_agente_id'];
        $r_modifica = (new com_prospecto(link: $this->link))->modifica_bd(registro: $registros_prosp,
            id: $inm_prospecto['com_prospecto_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_modifica, header: $header, ws: $ws);
        }

        $registros_prosp['com_agente_id'] = $_POST['com_agente_id'];
        $r_modifica = (new inm_prospecto(link: $this->link))->modifica_bd(registro: $registros_prosp,
            id: $this->registro_id);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al insertar datos', data: $r_modifica, header: $header, ws: $ws);
        }

        $this->link->commit();

        $accion = 'integra_relacion';
        if(isset($_POST['btn_action_next'])){
            $accion = $_POST['btn_action_next'];
        }

        $params = array();
        if (isset($_GET['pestana_general_actual'])) {
            $params = array('pestana_general_actual' => 'pestanageneral1', 'pestana_actual' => $_GET['pestana_actual']);
        }
        $link_integra_relacion_bd = $this->obj_link->link_con_id(
            accion: $accion, link: $this->link, registro_id: $this->registro_id, seccion: 'inm_prospecto',
            params: $params);
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_integra_relacion_bd, header: $header,
                ws: $ws);
        }

        if($header) {
            header('Location:' . $link_integra_relacion_bd);
            exit;
        }

        return $result;
    }

    public function generales(bool $header, bool $ws = false): array|stdClass
    {
        $inm_prospecto = $this->init_datos_prospecto(header: $header, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar datos del prospecto',
                data: $inm_prospecto, header: $header, ws: $ws);
        }

        $inm_conyuge = $this->init_conyuge(datos_prospecto: $inm_prospecto, header: $header, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar cónyuge',
                data: $inm_conyuge, header: $header, ws: $ws);
        }

        $inm_tipo_beneficiarios = $this->init_beneficiarios_agrupados(header: $header, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar beneficiarios agrupados',
                data: $inm_tipo_beneficiarios, header: $header, ws: $ws);
        }

        $inm_referencias = $this->init_referencias(header: $header, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar referencias',
                data: $inm_referencias, header: $header, ws: $ws);
        }

        $this->registro->inm_conyuge            = $inm_conyuge;
        $this->registro->inm_tipo_beneficiarios  = $inm_tipo_beneficiarios;
        $this->registro->inm_referencias         = $inm_referencias;

        return new stdClass();
    }

    /**
     * Obtiene y almacena en $this->registro los datos base del prospecto y sus co-acreditados.
     * @return array|stdClass  Datos del prospecto o error
     */
    private function init_datos_prospecto(bool $header, bool $ws): array|stdClass
    {
        $inm_prospecto = (new _generales())->inm_prospecto(
            inm_prospecto_id: $this->registro_id, link: $this->link
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al ajustar prospecto',
                data: $inm_prospecto, header: $header, ws: $ws);
        }

        $this->registro                    = new stdClass();
        $this->registro->inm_prospecto     = $inm_prospecto;

        $inm_co_acreditados = (new inm_prospecto(link: $this->link))->inm_co_acreditados(
            inm_prospecto_id: $this->registro_id
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener co-acreditados',
                data: $inm_co_acreditados, header: $header, ws: $ws);
        }

        $this->registro->inm_beneficiarios = $inm_co_acreditados;

        return $inm_prospecto;
    }

    /**
     * Inicializa y enriquece los datos del cónyuge del prospecto.
     * Si no existe cónyuge devuelve un stdClass vacío inicializado.
     * @param stdClass $datos_prospecto  Fila del prospecto (para tomar estado civil)
     * @return array|stdClass            Datos del cónyuge enriquecidos o error
     */
    private function init_conyuge(stdClass $datos_prospecto, bool $header, bool $ws): array|stdClass
    {
        $inm_conyuge = (new _generales())->inm_conyuge_init();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar inm_conyuge',
                data: $inm_conyuge, header: $header, ws: $ws);
        }

        $existe_conyuge = (new inm_prospecto(link: $this->link))->existe_conyuge(
            inm_prospecto_id: $this->registro_id
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al validar existencia de cónyuge',
                data: $existe_conyuge, header: $header, ws: $ws);
        }

        if ($existe_conyuge) {
            $inm_conyuge = $this->enriquece_conyuge(
                datos_prospecto: $datos_prospecto, header: $header, ws: $ws
            );
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al enriquecer datos del cónyuge',
                    data: $inm_conyuge, header: $header, ws: $ws);
            }
        }

        $nombre_completo = (new _generales())->nombre_completo(
            name_entidad: 'inm_conyuge', row: $inm_conyuge
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener nombre completo del cónyuge',
                data: $nombre_completo, header: $header, ws: $ws);
        }
        $inm_conyuge->inm_conyuge_nombre_completo = $nombre_completo;

        $lugar_fecha_nac = (new _generales())->data_nacimiento(
            entidad_edo: 'dp_estado', entidad_mun: 'dp_municipio',
            entidad_name: 'inm_conyuge', row: $inm_conyuge
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener lugar y fecha de nacimiento del cónyuge',
                data: $lugar_fecha_nac, header: $header, ws: $ws);
        }
        $inm_conyuge->inm_conyuge_lugar_fecha_nac = $lugar_fecha_nac;

        return $inm_conyuge;
    }

    /**
     * Obtiene los datos del cónyuge desde BD y agrega edad y estado civil.
     * Solo se llama cuando ya se verificó que el cónyuge existe.
     * @param stdClass $datos_prospecto  Fila del prospecto (para estado civil)
     * @return array|stdClass            Datos del cónyuge o error
     */
    private function enriquece_conyuge(stdClass $datos_prospecto, bool $header, bool $ws): array|stdClass
    {
        $inm_conyuge = (new inm_prospecto(link: $this->link))->inm_conyuge(
            inm_prospecto_id: $this->registro_id
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_conyuge',
                data: $inm_conyuge, header: $header, ws: $ws);
        }

        $edad = (new calculo())->edad_hoy(fecha_nacimiento: $inm_conyuge->inm_conyuge_fecha_nacimiento);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al calcular edad del cónyuge',
                data: $edad, header: $header, ws: $ws);
        }

        $inm_conyuge->inm_conyuge_edad        = $edad . ' AÑOS';
        $inm_conyuge->inm_conyuge_estado_civil = $datos_prospecto->inm_estado_civil_descripcion;

        return $inm_conyuge;
    }

    /**
     * Obtiene los tipos de beneficiario y agrupa dentro de cada tipo
     * los beneficiarios del prospecto con su nombre completo calculado.
     * @return array|stdClass  Array de tipos de beneficiario con sus beneficiarios o error
     */
    private function init_beneficiarios_agrupados(bool $header, bool $ws): array|stdClass
    {
        $inm_tipo_beneficiarios = (new inm_tipo_beneficiario(link: $this->link))->registros_activos(
            retorno_obj: true
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener tipos de beneficiario',
                data: $inm_tipo_beneficiarios, header: $header, ws: $ws);
        }

        $inm_beneficiarios = (new inm_prospecto(link: $this->link))->inm_beneficiarios(
            inm_prospecto_id: $this->registro_id
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener beneficiarios del prospecto',
                data: $inm_beneficiarios, header: $header, ws: $ws);
        }

        foreach ($inm_tipo_beneficiarios as $indice => $inm_tipo_beneficiario) {
            $inm_tipo_beneficiario->inm_beneficiarios ??= array();

            foreach ($inm_beneficiarios as $inm_beneficiario) {
                $nombre_completo = (new _generales())->nombre_completo(
                    name_entidad: 'inm_beneficiario', row: $inm_beneficiario
                );
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al obtener nombre del beneficiario',
                        data: $nombre_completo, header: $header, ws: $ws);
                }
                $inm_beneficiario->inm_beneficiario_nombre_completo = $nombre_completo;

                if ((int)$inm_beneficiario->inm_tipo_beneficiario_id
                    === (int)$inm_tipo_beneficiario->inm_tipo_beneficiario_id
                ) {
                    $inm_tipo_beneficiario->inm_beneficiarios[] = $inm_beneficiario;
                }
            }

            $inm_tipo_beneficiarios[$indice] = $inm_tipo_beneficiario;
        }

        return $inm_tipo_beneficiarios;
    }

    /**
     * Obtiene las referencias del prospecto y enriquece cada una con
     * nombre completo, teléfono y celular normalizados.
     * @return array|stdClass  Array de referencias enriquecidas o error
     */
    private function init_referencias(bool $header, bool $ws): array|stdClass
    {
        $inm_referencias = (new inm_prospecto(link: $this->link))->inm_referencias(
            inm_prospecto_id: $this->registro_id
        );
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener referencias del prospecto',
                data: $inm_referencias, header: $header, ws: $ws);
        }

        foreach ($inm_referencias as $indice => $inm_referencia) {
            $nombre_completo = (new _generales())->nombre_completo(
                name_entidad: 'inm_referencia', row: $inm_referencia
            );
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al obtener nombre de la referencia',
                    data: $nombre_completo, header: $header, ws: $ws);
            }

            $inm_referencia->inm_referencia_prospecto_nombre_completo = $nombre_completo;
            $inm_referencia->inm_referencia_prospecto_telefono         = $inm_referencia->inm_referencia_numero;
            $inm_referencia->inm_referencia_prospecto_celular          = $inm_referencia->inm_referencia_celular;

            $inm_referencias[$indice] = $inm_referencia;
        }

        return $inm_referencias;
    }

    protected function key_selects_txt(array $keys_selects, int $cols_descripcion = 12): array
    {

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'apellido_paterno',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'apellido_materno',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'lada_com',
            keys_selects: $keys_selects, place_holder: 'Lada', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'documentos',
            keys_selects: $keys_selects, place_holder: 'Documentos');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'receptor',
            keys_selects: $keys_selects, place_holder: 'Receptor');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'asunto',
            keys_selects: $keys_selects, place_holder: 'Asunto');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'mensaje',
            keys_selects: $keys_selects, place_holder: 'Mensaje');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['lada_com']->regex = $this->validacion->patterns['lada_html'];

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'numero_com',
            keys_selects: $keys_selects, place_holder: 'Numero', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['numero_com']->regex = $this->validacion->patterns['telefono_mx_html'];

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'cel_com',
            keys_selects: $keys_selects, place_holder: 'Cel', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['cel_com']->regex = $this->validacion->patterns['telefono_mx_html'];

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'correo_com',
            keys_selects: $keys_selects, place_holder: 'Correo', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['correo_com']->regex = $this->validacion->patterns['correo_html5'];

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razon Social');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'nombre_empresa_patron',
            keys_selects: $keys_selects, place_holder: 'Nombre Empresa', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'nrp_nep',
            keys_selects: $keys_selects, place_holder: 'Registro Patronal', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'lada_nep',
            keys_selects: $keys_selects, place_holder: 'Lada Tel Empresa', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'numero_nep',
            keys_selects: $keys_selects, place_holder: 'Tel. Empresa', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'extension_nep',
            keys_selects: $keys_selects, place_holder: 'Extension Empresa', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'rfc',
            keys_selects: $keys_selects, place_holder: 'RFC', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'numero_exterior',
            keys_selects: $keys_selects, place_holder: 'Ext Dom.', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'numero_interior',
            keys_selects: $keys_selects, place_holder: 'Int Dom.', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'texto_exterior',
            keys_selects: $keys_selects, place_holder: 'Numero Ext', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'texto_interior',
            keys_selects: $keys_selects, place_holder: 'Numero Int', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'nss',
            keys_selects: $keys_selects, place_holder: 'NSS', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'curp',
            keys_selects: $keys_selects, place_holder: 'CURP', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'descuento_pension_alimenticia_dh',
            keys_selects: $keys_selects, place_holder: 'Desc Pension Alimen. Der.', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'descuento_pension_alimenticia_fc',
            keys_selects: $keys_selects, place_holder: 'Desc Pension Alimen. Fam/Cor', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'monto_credito_solicitado_dh',
            keys_selects: $keys_selects, place_holder: 'Monto Credito Solicitado Der.', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'monto_ahorro_voluntario',
            keys_selects: $keys_selects, place_holder: 'Monto Ahorro Voluntario ', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'observaciones',
            keys_selects: $keys_selects, place_holder: 'Observaciones', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'sub_cuenta',
            keys_selects: $keys_selects, place_holder: 'Sub Cuenta', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'monto_final',
            keys_selects: $keys_selects, place_holder: 'Monto Final', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'descuento',
            keys_selects: $keys_selects, place_holder: 'Descuento', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 3, key: 'puntos',
            keys_selects: $keys_selects, place_holder: 'Puntos', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'telefono_casa',
            keys_selects: $keys_selects, place_holder: 'Telefono de Casa', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['telefono_casa']->regex = $this->validacion->patterns['telefono_mx_html'];

        $keys_selects = (new init())->key_select_txt(cols: 2, key: 'correo_empresa',
            keys_selects: $keys_selects, place_holder: 'Correo Empresa', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['correo_empresa']->regex = $this->validacion->patterns['correo_html5'];

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'calle',
            keys_selects: $keys_selects, place_holder: 'Calle Dom.', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'devolucion',
            keys_selects: $keys_selects, place_holder: 'Devolucion', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function lista(bool $header, bool $ws = false): array
    {
        $filtro_agente['adm_usuario.id'] = $_SESSION['usuario_id'];
        $existe = (new com_agente(link: $this->link))->existe(filtro: $filtro_agente);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al insertar prospecto',data:  $existe, header: $header,
                ws:$ws);
        }

        if($existe){
            $this->es_agente = true;
        }

        $r_lista = parent::lista($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $r_lista, header: $header,ws:$ws);
        }

        $status_prospecto = (new inm_status_prospecto(link:$this->link))->registros();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener regitros de status', data:  $status_prospecto,
                header: $header,ws:$ws);
        }

        $this->status_prospecto = $status_prospecto;

        return $r_lista;
    }


    public function load_html(bool $header, bool $ws = false): array
    {
        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $data = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->inputs_base(controlador: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar datos para front', data: $data,
                header: $header, ws: $ws);
        }

        $base = $this->base_upd(keys_selects: $data->keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        echo $this->inputs->inm_producto_infonavit_id;
        echo $this->inputs->inm_attr_tipo_credito_id;
        echo $this->inputs->inm_destino_credito_id;
        echo $this->inputs->es_segundo_credito;
        echo $this->inputs->inm_plazo_credito_sc_id;

        exit;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $registro_prospecto = (new inm_prospecto(link: $this->link))->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener registro prospecto', data: $registro_prospecto,
                header: $header, ws: $ws);
        }

        if(!isset($this->row_upd->inm_tipo_credito_id)){
            $this->row_upd->inm_tipo_credito_id = $registro_prospecto['inm_tipo_credito_id'];
        }

        if(!isset($this->row_upd->adm_estado_civil_id)){
            $this->row_upd->adm_estado_civil_id = $registro_prospecto['adm_estado_civil_id'];
        }

        $data = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->inputs_base(controlador: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar datos para front', data: $data,
                header: $header, ws: $ws);
        }

        $keys_selects = $this->key_selects_txt(keys_selects: $data->keys_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $inputs = $this->genera_inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener inputs',data:  $inputs);
        }

        $btn_collapse_all = $this->html->button_para_java(id_css: 'collapse_all',style:  'primary',
            tag:  'Ver/Ocultar Todo');
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al btn_collapse_all',data:  $btn_collapse_all, header: $header,ws:  $ws);
        }

        $this->buttons['btn_collapse_all'] = $btn_collapse_all;

        if(isset($registro_prospecto['inm_institucion_hipotecaria_id'])){
            $filtro_campo['adm_seccion.descripcion'] = $this->seccion;
            $campos_totales = (new adm_campo(link: $this->link))->filtro_and(filtro: $filtro_campo);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al obtener campos totales', data: $campos_totales,
                    header: $header, ws: $ws);
            }

            $filtro_conf['inm_institucion_hipotecaria.id'] = $registro_prospecto['inm_institucion_hipotecaria_id'];
            if($campos_totales->n_registros > 0) {
                $keys_ajustado = array();
                foreach ($campos_totales->registros as $campo) {
                    $filtro_conf['adm_campo.id'] = $campo['adm_campo_id'];
                    $r_conf_institucion = (new inm_conf_institucion_campo(link: $this->link))->filtro_and(filtro: $filtro_conf);
                    if (errores::$error) {
                        return $this->retorno_error(mensaje: 'Error al obtener registros campos', data: $r_conf_institucion,
                            header: $header, ws: $ws);
                    }

                    if($r_conf_institucion->n_registros <= 0){
                        $val = $campo['adm_campo_descripcion'];
                        $this->inputs->$val = '';
                    }
                }
            }
        }

        $headers = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->headers_front(controlador: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar headers', data: $headers, header: $header, ws: $ws);
        }

        $inputs = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->inputs_nacimiento(controlador: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar inputs', data: $inputs, header: $header, ws: $ws);
        }

        $co_acreditados = (new inm_prospecto(link: $this->link))->inm_co_acreditados(
            inm_prospecto_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener co_acreditados',data:  $co_acreditados,
                header: $header,ws:  $ws);
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

        $headers = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->frontend_co_acreditado(
            controler: $this, row_upd: $inm_co_acreditado);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al integrar headers',data:  $headers, header: $header,ws:  $ws);
        }

        $class_upd = '_upd_prospecto';
        $conyuge = (new _conyuge())->inputs_conyuge(controler: $this,class_upd: $class_upd);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener conyuge', data: $conyuge,
                header: $header, ws: $ws);
        }

        $this->inputs->conyuge = $conyuge;

        $beneficiario = (new _beneficiario())->inputs_beneficiario(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener beneficiario', data: $beneficiario,
                header: $header, ws: $ws);
        }

        $this->inputs->beneficiario = $beneficiario;

        $direccion = (new _direccion())->inputs_direccion(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener direccion', data: $direccion,
                header: $header, ws: $ws);
        }

        $this->inputs->direccion = $direccion;

        $filtro['inm_prospecto.id'] = $this->registro_id;
        $r_inm_beneficiario = (new inm_rel_beneficiario_prospecto(link: $this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener beneficiarios', data: $r_inm_beneficiario,
                header: $header, ws: $ws);
        }

        $params = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->params_btn(accion_retorno: __FUNCTION__,
            registro_id: $this->registro_id, seccion_retorno: $this->tabla);

        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener params', data: $params,
                header: $header, ws: $ws);
        }

        $beneficiarios = $r_inm_beneficiario->registros;

        $beneficiarios = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->rows(controlador: $this,
            datas: $beneficiarios, params: $params, seccion_exe: 'inm_rel_beneficiario_prospecto');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener beneficiarios link del', data: $beneficiarios,
                header: $header, ws: $ws);
        }

        $this->beneficiarios = $beneficiarios;

        $direcciones = (new com_direccion_prospecto(link: $this->link))->filtro_and(filtro: array('com_prospecto_id' =>
            $this->registro['com_prospecto_id']));
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener direcciones', data: $direcciones,
                header: $header, ws: $ws);
        }


        $direcciones_reg = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->rows_direccion(controlador: $this,
            datas: $direcciones->registros, params: $params, seccion_exe: 'com_direccion', seccion_sec: 'com_direccion_prospecto');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener beneficiarios link del', data: $direcciones,
                header: $header, ws: $ws);
        }

        $this->direcciones = $direcciones_reg;

        $referencia = (new _referencia())->inputs_referencia(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener referencias', data: $referencia,
                header: $header, ws: $ws);
        }
        $this->inputs->referencia = $referencia;

        $r_inm_referencia_prospecto = (new inm_rel_referencia_prospecto(link: $this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener referencia_prospectos', data: $r_inm_referencia_prospecto,
                header: $header, ws: $ws);
        }

        $params = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->params_btn(accion_retorno: __FUNCTION__,
            registro_id: $this->registro_id, seccion_retorno: $this->tabla);

        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener params', data: $params,
                header: $header, ws: $ws);
        }

        $referencia_prospectos = $r_inm_referencia_prospecto->registros;

        $referencia_prospectos = (new \gamboamartin\inmuebles\controllers\_inm_prospecto())->rows(controlador: $this,
            datas: $referencia_prospectos, params: $params, seccion_exe: 'inm_rel_referencia_prospecto');
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener beneficiarios link del', data: $referencia_prospectos,
                header: $header, ws: $ws);
        }

        $this->referencias = $referencia_prospectos;

        $this->keys_selects = array_merge($keys_selects, $this->keys_selects);

        return $r_modifica;
    }

    public function modifica_bd(bool $header, bool $ws): array|stdClass
    {
        //print_r($_POST);exit;
        $this->link->beginTransaction();

        $result_transacciones = (new inm_prospecto(link: $this->link))->transacciones_upd(inm_prospecto_id: $this->registro_id);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al ejecutar result_transacciones', data: $result_transacciones,
                header: $header, ws: $ws);
        }

        $r_modifica = parent::modifica_bd(header: false, ws: $ws); // TODO: Change the autogenerated stub
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al modificar inm_prospecto', data: $r_modifica,
                header: $header, ws: $ws);
        }
        $this->link->commit();

        $_SESSION[$r_modifica->salida][]['mensaje'] = $r_modifica->mensaje . ' del id ' . $this->registro_id;
        $this->header_out(result: $r_modifica, header: $header, ws: $ws);

        return $r_modifica;


    }

    public function modifica_direccion(bool $header, bool $ws = false): array|stdClass
    {
        if (!isset($_POST['dp_calle_pertenece_id'])) {
            return $this->retorno_error(mensaje: 'Error no existe dp_calle_pertenece_id', data: $_POST, header: $header,
                ws: $ws);
        }

        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }

        $modifica = array();
        $modifica['dp_calle_pertenece_id'] = $_POST['dp_calle_pertenece_id'];
        $modifica['texto_exterior'] = $_POST['texto_exterior'];
        $modifica['texto_interior'] = $_POST['texto_interior'];
        $modifica = (new com_direccion(link: $this->link))->modifica_bd(registro: $modifica, id: $_POST['com_direccion_id']);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al modificar com_direccion', data: $modifica,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id: $this->registro_id, result: $modifica,
                siguiente_view: "modifica", ws: $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode(array(), JSON_THROW_ON_ERROR);
            exit;
        }

        return $modifica;
    }

    public function proceso_prospecto(bool $header, bool $ws = false): array|stdClass
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

        $integra_relacion = $this->integra_relacion($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $integra_relacion,header: $header,ws: $ws);
        }

        $etapa = $this->etapa($header);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $etapa,header: $header,ws: $ws);
        }

        $base = $this->base_upd(keys_selects: $this->keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $btn_action_next = $this->html->hidden('btn_action_next', value: 'proceso_prospecto');
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

    public function regenera_curp(bool $header, bool $ws = false): array|string
    {
        $columnas[] = 'inm_prospecto_id';
        $columnas[] = 'inm_prospecto_curp';
        $registros = (new inm_prospecto(link: $this->link))->registros(columnas: $columnas);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener prospectos', data: $registros,
                header: $header, ws: $ws);
        }

        foreach ($registros as $inm_prospecto) {
            $this->link->beginTransaction();
            $nss = trim($inm_prospecto['inm_prospecto_curp']);
            if ($nss === '') {
                $inm_prospecto_upd['curp'] = 'XEXX010101HNEXXXA4';
                $upd = (new inm_prospecto(link: $this->link))->modifica_bd(registro: $inm_prospecto_upd,
                    id: $inm_prospecto['inm_prospecto_id']);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al upd prospecto', data: $upd,
                        header: $header, ws: $ws);
                }
                print_r($upd);

            }
            $this->link->commit();
        }
        exit;
    }

    public function regenera_nombre_completo_valida(bool $header, bool $ws = false): array|string
    {

        $this->link->beginTransaction();
        $regenera = (new inm_prospecto(link: $this->link))->regenera_nombre_completo_valida();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al regenerar', data: $regenera,
                header: $header, ws: $ws);
        }
        $this->link->commit();
        print_r($regenera);

        exit;
    }

    public function regenera_nss(bool $header, bool $ws = false): array|string
    {
        $columnas[] = 'inm_prospecto_id';
        $columnas[] = 'inm_prospecto_nss';
        $registros = (new inm_prospecto(link: $this->link))->registros(columnas: $columnas);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener prospectos', data: $registros,
                header: $header, ws: $ws);
        }

        foreach ($registros as $inm_prospecto) {
            $this->link->beginTransaction();
            $nss = trim($inm_prospecto['inm_prospecto_nss']);
            if ($nss === '') {
                $inm_prospecto_upd['nss'] = '99999999999';
                $upd = (new inm_prospecto(link: $this->link))->modifica_bd(registro: $inm_prospecto_upd,
                    id: $inm_prospecto['inm_prospecto_id']);
                if (errores::$error) {
                    $this->link->rollBack();
                    return $this->retorno_error(mensaje: 'Error al upd prospecto', data: $upd,
                        header: $header, ws: $ws);
                }
                print_r($upd);

            }
            $this->link->commit();
        }
        exit;
    }

    public function regenera_rel_com_agente(bool $header, bool $ws = false): array|string
    {

        $this->link->beginTransaction();
        $regenera = (new inm_prospecto(link: $this->link))->regenera_agentes_iniciales();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al regenerar', data: $regenera,
                header: $header, ws: $ws);
        }
        $this->link->commit();
        print_r($regenera);

        exit;
    }

    final public function subir_documento(bool $header, bool $ws = false)
    {
        $inm_prospecto = (new inm_prospecto(link: $this->link))->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_prospecto', data: $inm_prospecto,
                header: $header, ws: $ws);
        }

        $inm_conf_docs_prospecto = (new inm_conf_docs_prospecto(link: $this->link))->filtro_and(
            columnas: ['doc_tipo_documento_id'],
            filtro: array('inm_attr_tipo_credito_id' => $inm_prospecto['inm_attr_tipo_credito_id']));
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_conf_docs_prospecto', data: $inm_conf_docs_prospecto,
                header: $header, ws: $ws);
        }

        $this->inputs = new stdClass();

        $filtro['inm_prospecto.id'] = $this->registro_id;
        $inm_prospecto_id = (new inm_prospecto_html(html: $this->html_base))->select_inm_prospecto_id(
            cols: 12, con_registros: true, id_selected: $this->registro_id, link: $this->link, filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_prospecto_id, header: $header, ws: $ws);
        }
        $this->inputs->inm_prospecto_id = $inm_prospecto_id;

        $doc_ids = array_map(function ($registro) {
            return $registro['doc_tipo_documento_id'];
        }, $inm_conf_docs_prospecto->registros);

        $doc_tipos_documentos = array();

        if (count($doc_ids) > 0) {
            $doc_tipos_documentos = (new _doctos())->documentos_de_prospecto(inm_prospecto_id: $this->registro_id,
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
            return $this->retorno_error(mensaje: 'Error al generar input', data: $inm_prospecto_id, header: $header, ws: $ws);
        }
        $this->inputs->doc_tipo_documento_id = $doc_tipo_documento_id;

        $documento = $this->html->input_file(cols: 12, name: 'documento', row_upd: new stdClass(), value_vacio: false);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $documento, header: $header, ws: $ws);
        }

        $this->inputs->documento = $documento;

        $params = array();
        if(isset($_GET['pestana_general_actual'])) {
            $params = array('pestana_general_actual' => 'pestanageneral1',
                'pestana_actual' => $_GET['pestana_actual']);
        }
        $link_alta_doc = $this->obj_link->link_alta_bd(link: $this->link, seccion: 'inm_doc_prospecto',params: $params);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar link', data: $link_alta_doc, header: $header, ws: $ws);
        }

        $this->link_inm_doc_prospecto_alta_bd = $link_alta_doc;

        $retorno = 'documentos';
        if(isset($_GET['pestana_general_actual'])){
            $retorno = 'proceso_prospecto';
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
        $inm_conf_docs_prospecto = (new _inm_prospecto())->integra_inm_documentos(controler: $this);
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

    public function valida_campos(array $campos): array
    {
        $campos_validos = array('documentos', 'receptor', 'asunto', 'mensaje');
        $campos_faltantes = array_diff($campos_validos, array_keys($campos));
        if (!empty($campos_faltantes)) {
            $mensaje_error = 'Faltan los siguientes campos: ' . implode(', ', $campos_faltantes);
            return $this->errores->error(mensaje: $mensaje_error, data: $campos_faltantes);
        }

        return $campos;
    }

    public function valida_prioridad(bool $header, bool $ws = false)
    {
        $resultado = (new inm_prospecto(link: $this->link))->valida_prioridad_campo($_GET);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $resultado, header: $header, ws: $ws);
        }

        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($resultado, JSON_THROW_ON_ERROR);
            exit;
        }

        return $resultado;
    }

    final public function verifica_documentos(bool $header, bool $ws = false): array|string
    {
        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        $documentos = explode(',', $_POST['documentos']);
        $r_alta_doc_etapa = new stdClass();

        foreach ($documentos as $documento) {
            $prospecto = (new inm_prospecto($this->link))->registro(registro_id: $this->registro_id);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al obtener prospecto', data: $prospecto, header: $header, ws: $ws);
            }

            if ($prospecto['inm_prospecto_etapa'] != "VERIFICADO") {
                $registro = (new inm_doc_prospecto($this->link))->registro(registro_id: $documento, retorno_obj: true);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al obtener documento', data: $registro, header: $header, ws: $ws);
                }

                $r_alta_doc_etapa = (new inm_doc_prospecto($this->link))->
                genera_documento_etapa(doc_documento_id: $registro->doc_documento_id, etapa: "VERIFICADO");
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al generar documento etapa',
                        data: $r_alta_doc_etapa, header: $header, ws: $ws);
                }

                $accion = (new inm_prospecto($this->link))->actualiza_etapa(com_prospecto_id: $this->registro_id,
                    etapa: "VERIFICADO");
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'Error al actualizar etapa', data: $accion, header: $header, ws: $ws);
                }
            }

        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id: $this->registro_id, result: $r_alta_doc_etapa,
                siguiente_view: "documentos", ws: $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_alta_doc_etapa, JSON_THROW_ON_ERROR);
            exit;
        }
        $r_alta_doc_etapa->siguiente_view = "documentos";

        return $r_alta_doc_etapa;
    }
}
