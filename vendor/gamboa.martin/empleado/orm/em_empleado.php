<?php
namespace gamboamartin\empleado\models;

use base\orm\_modelo_parent;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use gamboamartin\cat_sat\models\cat_sat_tipo_jornada_nom;
use gamboamartin\cat_sat\models\cat_sat_tipo_regimen_nom;
use gamboamartin\comercial\models\com_cliente;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\documento\models\doc_conf_tipo_documento_seccion;
use gamboamartin\documento\models\doc_documento;
use gamboamartin\documento\models\doc_tipo_documento;
use gamboamartin\empleado\controllers\controlador_em_empleado;
use gamboamartin\errores\errores;

use gamboamartin\organigrama\models\org_puesto;
use gamboamartin\plugins\imagen;
use gamboamartin\plugins\pdf;
use gamboamartin\plugins\web;
use PDO;
use stdClass;

class em_empleado extends _modelo_parent{
    public errores $error;
    public function __construct(PDO $link){
        $this->error = new errores();
        $tabla = 'em_empleado';

        $columnas = array($tabla=>false, 'em_registro_patronal'=>$tabla, 'cat_sat_regimen_fiscal'=>$tabla,
            'dp_calle_pertenece'=>$tabla,'cat_sat_tipo_regimen_nom'=>$tabla,'org_puesto'=>$tabla,
            'org_departamento'=>'org_puesto','cat_sat_tipo_jornada_nom'=>$tabla, 'em_centro_costo' =>$tabla,
            'fc_csd' => 'em_registro_patronal');

        $campos_obligatorios = array('nombre','ap','descripcion','codigo','curp','rfc');

        $tipo_campos = array();
        $tipo_campos['rfc'] = 'rfc';

        $columnas_extra['em_empleado_nombre_completo'] = 'CONCAT (IFNULL(em_empleado.nombre,"")," ",IFNULL(em_empleado.ap, "")," ",IFNULL(em_empleado.am,""))';
        $columnas_extra['em_empleado_nombre_completo_inv'] = 'CONCAT (IFNULL(em_empleado.ap,"")," ",IFNULL(em_empleado.am, "")," ",IFNULL(em_empleado.nombre,""))';
        $columnas_extra['em_empleado_n_cuentas_bancarias'] = "(SELECT COUNT(*) FROM em_cuenta_bancaria 
        WHERE em_cuenta_bancaria.em_empleado_id = em_empleado.id)";

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, tipo_campos: $tipo_campos);


        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass 
    {
        if(!isset($this->registro['codigo'])){ 

            $this->registro['codigo'] =  $this->get_codigo_aleatorio();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar codigo aleatorio',data:  $this->registro);
            }

            if (isset($this->registro['rfc'])){
                $this->registro['codigo'] = $this->registro['rfc'];
            }
        }

        if(!isset($this->registro['descripcion'])){
            $this->registro['descripcion'] = $this->registro['nombre']. ' ';
            $this->registro['descripcion'] .= $this->registro['ap'];
        }

        if(!isset($this->registro['dp_municipio_id'])){
            return $this->error->error(mensaje: 'Error dp_municipio_id no existe',data:  $this->registro);
        }

        $dp_municipio_modelo = new dp_municipio(link: $this->link);
        $dp_municipio = $dp_municipio_modelo->registro(registro_id: $this->registro['dp_municipio_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener dp_municipio', data: $dp_municipio);
        }

        $this->registro['pais'] = $dp_municipio['dp_pais_descripcion'];
        $this->registro['estado'] = $dp_municipio['dp_estado_descripcion'];
        $this->registro['municipio'] = $dp_municipio['dp_municipio_descripcion'];

        $this->registro = $this->fecha_inicio_rel_laboral_default($this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar fecha rel laboral',data: $this->registro);
        }

        $this->registro = $this->dp_calle_pertenece_id($this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar direcciones',data: $this->registro);
        }

        $this->registro = $this->org_puesto_id($this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar puesto',data: $this->registro);
        }

        $this->registro = $this->cat_sat_tipo_jornada_nom_id($this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar tipo jornada nomina',data: $this->registro);
        }

        $this->registro = $this->cat_sat_regimen_fiscal_id($this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar tipo jornada nomina',data: $this->registro);
        }

        $this->registro = $this->cat_sat_tipo_regimen_nom_id($this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar cat_sat_tipo_regimen_nom_id',data: $this->registro);
        }

        $this->registro = $this->em_centro_costo_id($this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar em_centro_costo_id',data: $this->registro);
        }

        $this->registro = $this->campos_base(data:$this->registro,modelo: $this);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $this->registro);
        }

        $this->registro['descripcion_select'] = isset($this->registro['nss']) ? $this->registro['nss']." - " : "SIN NSS - ";
        $this->registro['descripcion_select'] .= $this->registro['nombre']. ' ';
        $this->registro['descripcion_select'] .= $this->registro['ap']. ' ';
        $this->registro['descripcion_select'] .= isset($this->registro['am']) ? $this->registro['am']: "";
        $this->registro['descripcion_select'] = strtoupper($this->registro['descripcion_select']);

        $this->registro = $this->limpia_campos_extras(registro: $this->registro, campos_limpiar: array("dp_pais_id",
            "dp_estado_id", "dp_cp_id","dp_colonia_postal_id"));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $this->registro);
        }

        if(!isset($this->registro['rfc'])){
            $this->registro['rfc'] = 'AAA010101AAA';
        }



        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta empleado',data:  $r_alta_bd);
        }


        /*$respuesta = $this->transacciona_em_rel_empleado_sucursal(data: $this->registro,
            em_empleado_id: $r_alta_bd->registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al transaccionar relacion empleado sucursal',data:  $respuesta);
        }*/

        $inserta_documento = $this->registra_documento_empleado(em_empleado: $r_alta_bd->registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar documento para empleado', data: $inserta_documento);
        }

        return $r_alta_bd;
    }

    public function registra_documento_empleado(int $em_empleado) : array|stdClass {
        $tipo_documento = (new doc_documento($this->link))->validar_permisos_documento(modelo: $this->tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar permisos para el documento', data: $tipo_documento);
        }

        $_POST = array();
        $em_empleado_documento = new em_empleado_documento($this->link);
        $em_empleado_documento->registro['em_empleado_id'] = $em_empleado;
        $em_empleado_documento->registro['doc_tipo_documento_id'] = $tipo_documento['doc_tipo_documento_id'];
        $_POST['doc_tipo_documento_id'] = $tipo_documento['doc_tipo_documento_id'];

        $alta_documento = $em_empleado_documento->alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar documento', data: $alta_documento );
        }

        return $alta_documento;
    }

    final public function integra_documentos(controlador_em_empleado $controler)
    {
        $empleado = $this->registro(registro_id: $controler->registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener empleado', data: $empleado);
        }

        $conf_tipos_docs = (new doc_conf_tipo_documento_seccion(link: $controler->link))->filtro_and(
            columnas: ['doc_tipo_documento_id'],filtro: array('adm_seccion.descripcion' => $this->tabla));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener conf. de tipos de documentos', data: $conf_tipos_docs);
        }

        $doc_ids = array_map(function ($registro) {
            return $registro['doc_tipo_documento_id'];
        }, $conf_tipos_docs->registros);

        if (count($doc_ids) <= 0) {
            return array();
        }

        $empleados_documentos = (new em_empleado_documento(link: $controler->link))->documentos(
            em_empleado: $controler->registro_id, tipos_documentos: $doc_ids);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener documentos', data: $empleados_documentos);
        }

        $buttons_documentos = $this->buttons_documentos(controler: $controler, empleados_documentos: $empleados_documentos,
            tipos_documentos: $doc_ids);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar buttons', data: $buttons_documentos);
        }

        return $buttons_documentos;
    }

    public function buttons_documentos(controlador_em_empleado $controler, array $empleados_documentos, array $tipos_documentos)
    {
        $conf_docs = $this->documentos_de_empleado(em_empleado_id: $controler->registro_id,
            link: $controler->link, todos: true, tipos_documentos: $tipos_documentos);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener configuraciones de documentos',
                data: $conf_docs);
        }

        foreach ($conf_docs as $indice => $doc_tipo_documento) {
            $conf_docs = $this->docs_empleado(controler: $controler,
                doc_tipo_documento: $doc_tipo_documento, indice: $indice,
                em_conf_tipo_doc_empleado: $conf_docs, empleados_documentos: $empleados_documentos);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar buttons', data: $conf_docs);
            }
        }

        return $conf_docs;
    }

    final public function documentos_de_empleado(int $em_empleado_id, PDO $link, bool $todos, array $tipos_documentos)
    {
        $in = array();

        if (count($tipos_documentos) > 0) {
            $in['llave'] = 'doc_tipo_documento.id';
            $in['values'] = $tipos_documentos;
        }

        $r_doc_tipo_documento = (new doc_tipo_documento(link: $link))->filtro_and(in: $in);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener tipos de documento', data: $r_doc_tipo_documento);
        }

        return $r_doc_tipo_documento->registros;
    }

    private function docs_empleado(controlador_em_empleado $controler, array $doc_tipo_documento, int $indice,
                                   array $em_conf_tipo_doc_empleado, array $empleados_documentos)
    {
        $existe = false;
        foreach ($empleados_documentos as $empleado_documento) {
            $existe_doc = $this->doc_existente(controler: $controler,
                doc_tipo_documento: $doc_tipo_documento, indice: $indice,
                em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado, empleado_documento: $empleado_documento);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar datos', data: $existe_doc);
            }

            $em_conf_tipo_doc_empleado = $existe_doc->em_conf_tipo_doc_empleado;
            $existe = $existe_doc->existe;
            if ($existe) {
                break;
            }
        }

        if (!$existe) {
            $em_conf_tipo_doc_empleado = $this->integra_data(controler: $controler,
                doc_tipo_documento: $doc_tipo_documento, indice: $indice,
                em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar button', data: $em_conf_tipo_doc_empleado);
            }
        }

        return $em_conf_tipo_doc_empleado;
    }

    private function integra_data(controlador_em_empleado $controler, array $doc_tipo_documento,
                                  int $indice, array $em_conf_tipo_doc_empleado){
        $params = array('doc_tipo_documento_id'=>$doc_tipo_documento['doc_tipo_documento_id']);

        $button = $controler->html->button_href(accion: 'subir_documento',etiqueta:
            'Subir Documento',registro_id:  $controler->registro_id,
            seccion:  'em_empleado',style:  'warning', params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $button);
        }

        $em_conf_tipo_doc_empleado = $this->integra_button_default(button: $button,
            indice:  $indice, em_conf_tipo_doc_empleado:  $em_conf_tipo_doc_empleado);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $em_conf_tipo_doc_empleado);
        }

        return $em_conf_tipo_doc_empleado;
    }

    private function integra_button_default(string $button, int $indice, array $em_conf_tipo_doc_empleado): array
    {
        $em_conf_tipo_doc_empleado[$indice]['descarga'] = $button;
        $em_conf_tipo_doc_empleado[$indice]['vista_previa'] = $button;
        $em_conf_tipo_doc_empleado[$indice]['descarga_zip'] = $button;
        $em_conf_tipo_doc_empleado[$indice]['elimina_bd'] = $button;
        return $em_conf_tipo_doc_empleado;
    }

    private function doc_existente(controlador_em_empleado $controler, array $doc_tipo_documento, int $indice,
                                   array                   $em_conf_tipo_doc_empleado, array $empleado_documento)
    {

        $existe = false;
        if ($doc_tipo_documento['doc_tipo_documento_id'] === $empleado_documento['doc_tipo_documento_id']) {

            $existe = true;

            $em_conf_tipo_doc_empleado = $this->buttons_base(controler: $controler, indice: $indice,
                em_empleado_documento_id: $controler->registro_id, em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado,
                empleado_documento: $empleado_documento);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar button', data: $em_conf_tipo_doc_empleado);
            }
        }

        $data = new stdClass();
        $data->existe = $existe;
        $data->em_conf_tipo_doc_empleado = $em_conf_tipo_doc_empleado;
        return $data;
    }

    private function buttons_base(controlador_em_empleado $controler, int $indice, int $em_empleado_documento_id,
                                  array $em_conf_tipo_doc_empleado, array $empleado_documento): array
    {
        $em_conf_tipo_doc_empleado = $this->buttons(controler: $controler, indice: $indice,
            em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado, empleado_documento: $empleado_documento);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar button', data: $em_conf_tipo_doc_empleado);
        }

        $em_conf_tipo_doc_empleado = $this->button_del(controler: $controler, indice: $indice,
            em_empleado_documento_id: $em_empleado_documento_id, em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado,
            empleado_documento: $empleado_documento);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar button', data: $em_conf_tipo_doc_empleado);
        }

        return $em_conf_tipo_doc_empleado;
    }

    private function buttons(controlador_em_empleado $controler, int $indice, array $em_conf_tipo_doc_empleado,
                             array $empleado_documento)
    {

        $em_conf_tipo_doc_empleado = $this->button(accion: 'descarga', controler: $controler,
            etiqueta: 'Descarga', indice: $indice, em_empleado_documento_id: $empleado_documento['em_empleado_documento_id'],
            em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar button', data: $em_conf_tipo_doc_empleado);
        }

        $em_conf_tipo_doc_empleado = $this->button(accion: 'vista_previa', controler: $controler,
            etiqueta: 'Vista Previa', indice: $indice, em_empleado_documento_id: $empleado_documento['em_empleado_documento_id'],
            em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado, target: '_blank');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar button', data: $em_conf_tipo_doc_empleado);
        }

        $em_conf_tipo_doc_empleado = $this->button(accion: 'descarga_zip', controler: $controler,
            etiqueta: 'ZIP', indice: $indice, em_empleado_documento_id: $empleado_documento['em_empleado_documento_id'],
            em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado, target: '_blank');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar button', data: $em_conf_tipo_doc_empleado);
        }

        return $em_conf_tipo_doc_empleado;
    }

    private function button(string $accion, controlador_em_empleado $controler, string $etiqueta, int $indice,
                            int    $em_empleado_documento_id, array $em_conf_tipo_doc_empleado, array $params = array(),
                            string $style = 'success', string $target = ''): array
    {
        $button = $controler->html->button_href(accion: $accion, etiqueta: $etiqueta,
            registro_id: $em_empleado_documento_id, seccion: 'em_empleado_documento', style: $style, params: $params,
            target: $target);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar button', data: $button);
        }
        $em_conf_tipo_doc_empleado[$indice][$accion] = $button;

        return $em_conf_tipo_doc_empleado;
    }

    final public function button_del(controlador_em_empleado $controler, int $indice, int $em_empleado_documento_id,
                                     array $em_conf_tipo_doc_empleado, array $empleado_documento){
        $params = array('accion_retorno'=>'documentos','seccion_retorno'=>$controler->seccion,
            'id_retorno'=>$em_empleado_documento_id);

        $em_conf_tipo_doc_empleado = $this->button(accion: 'elimina_bd', controler: $controler,
            etiqueta: 'Elimina', indice: $indice, em_empleado_documento_id: $empleado_documento['em_empleado_documento_id'],
            em_conf_tipo_doc_empleado: $em_conf_tipo_doc_empleado, params: $params, style: 'danger');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar button', data: $em_conf_tipo_doc_empleado);
        }

        return $em_conf_tipo_doc_empleado;
    }


    /**
     * Obtiene el tipo de jornada si no existe
     * @param array $registro Registro en proceso
     * @return array
     */
    private function cat_sat_regimen_fiscal_id(array $registro): array
    {
        $existe = (new cat_sat_regimen_fiscal(link: $this->link))->existe_by_id(registro_id: 616);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cat_sat_regimen_fiscal',
                data: $existe);
        }

        if(!$existe){
            $cat_sat_rf_ins['id'] = 616;
            $cat_sat_rf_ins['descripcion'] = 'Sin obligaciones fiscales';
            $cat_sat_rf_ins['descripcion_select'] = '616 Sin obligaciones fiscales';
            $cat_sat_rf_ins['codigo'] = '616';
            $cat_sat_rf_ins['alias'] = 'Sin obligaciones fiscales';
            $cat_sat_rf_ins['codigo_bis'] = '616';
            $cat_sat_rf_ins['predeterminado'] = 'activo';
            $inserta_tjn = (new cat_sat_regimen_fiscal(link: $this->link))->alta_registro(
                registro:$cat_sat_rf_ins);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar cat_sat_rf_ins', data: $cat_sat_rf_ins);
            }
        }


        if (!isset($registro['cat_sat_regimen_fiscal_id'])) {
            $cat_sat_regimen_fiscal_id =  (new cat_sat_regimen_fiscal($this->link))->id_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener cat_sat_regimen_fiscal_id',
                    data: $cat_sat_regimen_fiscal_id);
            }
            $registro['cat_sat_regimen_fiscal_id'] = $cat_sat_regimen_fiscal_id;
        }
        return $registro;
    }

    private function cat_sat_tipo_regimen_nom_id(array $registro): array
    {
        $existe = (new cat_sat_tipo_regimen_nom(link: $this->link))->existe_by_id(registro_id: 99);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cat_sat_regimen_fiscal',
                data: $existe);
        }

        if(!$existe){
            $cat_sat_trn_ins['id'] = 99;
            $cat_sat_trn_ins['descripcion'] = 'Otro régimen';
            $cat_sat_trn_ins['descripcion_select'] = '99 Otro régimen';
            $cat_sat_trn_ins['codigo'] = '99';
            $cat_sat_trn_ins['alias'] = 'Otro régimen';
            $cat_sat_trn_ins['codigo_bis'] = '99';
            $cat_sat_trn_ins['predeterminado'] = 'activo';
            $inserta_cstrn = (new cat_sat_tipo_regimen_nom(link: $this->link))->alta_registro(
                registro:$cat_sat_trn_ins);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar inserta_cstrn', data: $inserta_cstrn);
            }
        }


        if (!isset($registro['cat_sat_tipo_regimen_nom_id'])) {
            $cat_sat_tipo_regimen_nom_id =  (new cat_sat_tipo_regimen_nom($this->link))->id_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener cat_sat_tipo_regimen_nom_id',
                    data: $cat_sat_tipo_regimen_nom_id);
            }
            $registro['cat_sat_tipo_regimen_nom_id'] = $cat_sat_tipo_regimen_nom_id;
        }
        return $registro;
    }

    private function em_centro_costo_id(array $registro): array
    {
        $existe = (new em_centro_costo(link: $this->link))->existe_by_id(registro_id: 99);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cat_sat_regimen_fiscal',
                data: $existe);
        }

        if(!$existe){
            $em_centro_costo_ins['id'] = 99;
            $em_centro_costo_ins['descripcion'] = 'CC DEFAULT';
            $em_centro_costo_ins['descripcion_select'] = 'CC DEFAULT';
            $em_centro_costo_ins['codigo'] = '99';
            $em_centro_costo_ins['alias'] = 'CC DEFAULT';
            $em_centro_costo_ins['codigo_bis'] = '99';
            $em_centro_costo_ins['predeterminado'] = 'activo';
            $inst_em_centro_costo = (new em_centro_costo(link: $this->link))->alta_registro(
                registro:$em_centro_costo_ins);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar inst_em_centro_costo', data: $inst_em_centro_costo);
            }
        }


        if (!isset($registro['em_centro_costo_id'])) {
            $em_centro_costo_id =  (new em_centro_costo($this->link))->id_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener em_centro_costo_id',
                    data: $em_centro_costo_id);
            }
            $registro['em_centro_costo_id'] = $em_centro_costo_id;
        }
        return $registro;
    }

    private function cat_sat_tipo_jornada_nom_id(array $registro): array
    {
        $existe = (new cat_sat_tipo_jornada_nom(link: $this->link))->existe_by_id(registro_id: 99);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cat_sat_tipo_jornada_nom',
                data: $existe);
        }

        if(!$existe){
            $tipo_jornada_nom_ins['id'] = 99;
            $tipo_jornada_nom_ins['descripcion'] = 'Otra Jornada';
            $tipo_jornada_nom_ins['descripcion_select'] = '99 Otra Jornada';
            $tipo_jornada_nom_ins['codigo'] = '99';
            $tipo_jornada_nom_ins['alias'] = 'Otra Jornada';
            $tipo_jornada_nom_ins['codigo_bis'] = '99';
            $tipo_jornada_nom_ins['predeterminado'] = 'activo';
            $inserta_tjn = (new cat_sat_tipo_jornada_nom(link: $this->link))->alta_registro(
                registro:$tipo_jornada_nom_ins);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar inserta_tjn', data: $inserta_tjn);
            }
        }


        if (!isset($registro['cat_sat_tipo_jornada_nom_id'])) {
            $cat_tipo_jornada_nom_id =  (new cat_sat_tipo_jornada_nom($this->link))->id_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener cat_tipo_jornada_nom_id',data: $cat_tipo_jornada_nom_id);
            }
            $registro['cat_sat_tipo_jornada_nom_id'] = $cat_tipo_jornada_nom_id;
        }
        return $registro;
    }

    public function leer_codigo_qr(): array|stdClass
    {
        if (!array_key_exists('documento', $_FILES)) {
            return $this->error->error(mensaje: 'Error no existe documento', data: $_FILES);
        }

        $directorio_destino = 'archivos/temporales/pdf/empleado_'. $_GET['registro_id'].'/';

        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }

        $nombre_archivo = basename($_FILES['documento']['name']);
        $ruta_destino = $directorio_destino . $nombre_archivo;

        if (!move_uploaded_file($_FILES['documento']['tmp_name'], $ruta_destino)) {
            return $this->error->error(mensaje: 'Error al mover archivo', data: $_FILES);
        }

        $nombre_directorio_imagen = 'archivos/temporales/imagenes/empleado_'.$_GET['registro_id'].'/';

        $contenido = (new pdf())->leer_pdf(directorio: $nombre_directorio_imagen, prefijo_imagen: "imagen",
            ruta_pdf: $ruta_destino);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al leer pdf', data: $contenido);
        }

        $ruta_qr = (new imagen())->obtener_qr($contenido['imagenes']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener qr', data: $ruta_qr);
        }

        $url = (new imagen())->leer_codigo_qr(ruta_qr: $ruta_qr);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al leer código QR', data: $url);
        }

        $directorio_borrado = (new doc_documento($this->link))->borrar_directorio($directorio_destino);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al borrar directorio', data: $directorio_borrado);
        }

        $directorio_borrado = (new doc_documento($this->link))->borrar_directorio($nombre_directorio_imagen);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al borrar directorio', data: $directorio_borrado);
        }

        $contenido = (new web())->leer_contenido(url: $url);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al leer contenido', data: $contenido);
        }

        $contenido_formateado = (new web())->contenido_web_formateado(html: $contenido);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al formatear contenido', data: $contenido_formateado);
        }

        return get_object_vars($contenido_formateado);
    }



    private function dp_calle_pertenece_id(array $registro): array
    {
        if (!isset($registro['dp_calle_pertenece_id'])) {
            $registro['dp_calle_pertenece_id'] =  (new dp_calle_pertenece($this->link))->get_calle_pertenece_default_id();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener calle_pertenece_default',data: $registro['dp_calle_pertenece_id']);
            }
        }
        return $registro;
    }

    private function fecha_inicio_rel_laboral_default(array $registro): array
    {
        if (!isset($registro['fecha_inicio_rel_laboral'])) {
            $registro['fecha_inicio_rel_laboral'] = '1900-01-01';
        }
        return $registro;
    }

    public function get_direccion(int $dp_calle_pertenece_id): array|stdClass
    {
        if($dp_calle_pertenece_id <= 0){
            return $this->error->error(mensaje: 'Error $dp_calle_pertenece_id debe ser mayor a 0', data: $dp_calle_pertenece_id);
        }

        $filtro['dp_calle_pertenece.id'] = $dp_calle_pertenece_id;
        $dp_calle_pertenece = (new dp_calle_pertenece($this->link))->registro($dp_calle_pertenece_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener direccion', data: $dp_calle_pertenece);
        }

        return $dp_calle_pertenece;
    }

    /**
     * Obtiene empresa a partir de empleado
     * @param int $em_empleado_id Identificador del empleado a revisar su empresa
     * @return array|stdClass
     * @version
     */
    public function get_empresa(int $em_empleado_id): array|stdClass
    {
        $r_empleado = $this->registro(registro_id: $em_empleado_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener empleado',data: $r_empleado);
        }

        $r_registro_patronal =  (new em_registro_patronal($this->link))->registro(registro_id:
            $r_empleado['em_registro_patronal_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro patronal',data: $r_registro_patronal);
        }

        return $r_registro_patronal;
    }

    public function inserta_com_cliente(array $data): array|stdClass
    {

        $data = $this->maqueta_com_cliente(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al maquetar datos para cliente', data: $data);
        }

        foreach ($data as $campo=>$value){
            if(is_iterable($value)){
                return $this->error->error(mensaje: 'Error value es iterable '.$campo, data: $value);
            }
        }

        $dp_calle_pertenece = (new dp_calle_pertenece(link: $this->link))->registro(
            registro_id: $data['dp_calle_pertenece_id'],retorno_obj: true);

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos de direccion', data: $dp_calle_pertenece);
        }

        $data['dp_municipio_id'] = $dp_calle_pertenece->dp_municipio_id;

        $respuesta = (new com_cliente($this->link))->alta_registro(registro: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ingresar cliente', data: $respuesta);
        }

        return $respuesta;
    }

    public function transacciona_em_rel_empleado_sucursal(array $data, int $em_empleado_id): array|stdClass
    {
        $alta_com_cliente = $this->inserta_com_cliente(data: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta com_cliente',data:  $alta_com_cliente);
        }

        $filtro['com_cliente_id'] = $alta_com_cliente->registro_id;
        $com_sucursal = (new com_sucursal($this->link))->filtro_and(filtro: $filtro, limit: 1);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al datos del cliente', data: $data);
        }

        $data = $com_sucursal->registros[0];
        $data['em_empleado_id'] = $em_empleado_id;

        $respuesta = (new em_rel_empleado_sucursal($this->link))->inserta_em_rel_empleado_sucursal(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ingresar cliente', data: $respuesta);
        }

        return $respuesta;
    }


    public function maqueta_com_cliente(array $data): array
    {
        $salida = array();

        if (isset($data['codigo'])) {
            $salida['codigo'] = $data['codigo'];
        }

        if (isset($data['descripcion'])) {
            $salida['descripcion'] = $data['descripcion'];
        }

        $r_rfc = $this->rfc(registro: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar rfc',data: $r_rfc);
        }

        $rfc = $r_rfc['rfc'];
        if (isset($data['rfc'])) {
            $rfc = $data['rfc'];
        }

        $razon_social = $data['descripcion'];
        if (isset($data['razon_social'])) {
            $razon_social = $data['razon_social'];
        }

        $telefono = "9999999999";

        if (isset($data['telefono'])) {
            $telefono = $data['telefono'];
        }

        $numero_exterior = "xxx";

        if (isset($data['numero_exterior'])) {
            $numero_exterior = $data['numero_exterior'];
        }

        $numero_interior = "xxx";

        if (isset($data['numero_interior'])) {
            $numero_interior = $data['numero_interior'];
        }

        $dp_calle_pertenece_id = $this->dp_calle_pertenece_id(registro: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar direcciones',data: $dp_calle_pertenece_id);
        }

        if (isset($data['dp_calle_pertenece_id'])) {
            $dp_calle_pertenece_id = $data['dp_calle_pertenece_id'];
        }


        if(isset($data['cat_sat_regimen_fiscal_id'])){
            $salida['cat_sat_regimen_fiscal_id'] = $data['cat_sat_regimen_fiscal_id'];
        }
        if(isset($data['cat_sat_moneda_id'])){
            $salida['cat_sat_moneda_id'] = $data['cat_sat_moneda_id'];
        }
        if(isset($data['cat_sat_forma_pago_id'])){
            $salida['cat_sat_forma_pago_id'] = $data['cat_sat_forma_pago_id'];
        }
        if(isset($data['cat_sat_metodo_pago_id'])){
            $salida['cat_sat_metodo_pago_id'] = $data['cat_sat_metodo_pago_id'];
        }
        if(isset($data['cat_sat_uso_cfdi_id'])){
            $salida['cat_sat_uso_cfdi_id'] = $data['cat_sat_uso_cfdi_id'];
        }
        if(isset($data['cat_sat_tipo_de_comprobante_id'])){
            $salida['cat_sat_tipo_de_comprobante_id'] = $data['cat_sat_tipo_de_comprobante_id'];
        }
        if(isset($data['com_tipo_cliente_id'])){
            $salida['com_tipo_cliente_id'] = $data['com_tipo_cliente_id'];
        }

        $salida['razon_social'] = $razon_social;
        $salida['rfc'] = $rfc;
        $salida['telefono'] = $telefono;
        $salida['numero_exterior'] = $numero_exterior;
        $salida['numero_interior'] = $numero_interior;
        $salida['dp_calle_pertenece_id'] = $dp_calle_pertenece_id;
        $salida['es_empleado'] = true;
        $salida['cat_sat_tipo_persona_id'] = 5;

        return $salida;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $em_empleado_previo = $this->registro(registro_id: $id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener em_empleado_previo',data: $em_empleado_previo);
        }

        $registro = $this->init_values(registro: $registro, em_empleado_previo: $em_empleado_previo, id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar valores',data: $registro);
        }


        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva, $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar empleado',data: $r_modifica_bd);
        }

        return $r_modifica_bd;
    }

    public function init_values(array $registro, stdClass $em_empleado_previo, int $id) : array
    {
        if (isset($registro['status'])){
            return $registro;
        }

        if(!isset($registro['codigo'])){
            if (isset($registro['rfc'])){
                $registro['codigo'] = $registro['rfc'];
            }
        }

        if(!isset($registro['descripcion'])){

            if(!isset($registro['nombre'])){
                $registro['nombre'] = $em_empleado_previo->nombre;
            }
            if(!isset($registro['ap'])){
                $registro['ap'] = $em_empleado_previo->ap;
            }

            $registro['descripcion'] = $registro['nombre']. ' ';
            $registro['descripcion'] .= $registro['ap'];
        }

        $registro = $this->campos_base(data:$registro,modelo: $this,id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $this->registro);
        }

        if ($registro['nss'] != ""){
            $registro['descripcion_select'] = $registro['nss']." - ";
        } else {
            $registro['descripcion_select'] = "SIN NSS - ";
        }

        //$registro['descripcion_select'] = is_null($registro['nss']) ? $registro['nss']." - " : "SIN NSS - ";
        $registro['descripcion_select'] .= $registro['nombre']. ' ';
        $registro['descripcion_select'] .= $registro['ap']. ' ';
        $registro['descripcion_select'] .= isset($registro['am']) ? $registro['am']: "";
        $registro['descripcion_select'] = strtoupper($registro['descripcion_select']);

        $registro = $this->limpia_campos_extras(registro: $registro, campos_limpiar: array("dp_pais_id",
            "dp_estado_id", "dp_cp_id","dp_colonia_postal_id"));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $registro);
        }

        return $registro;
    }


    private function org_puesto_id(array $registro): array
    {
        if (!isset($registro['org_puesto_id'])) {
            $registro['org_puesto_id'] =  (new org_puesto($this->link))->get_puesto_default_id();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener get_puesto_default_id',data: $registro['org_puesto_id']);
            }
        }
        return $registro;
    }


    /**
     * Genera un rfc default
     * @param array $registro Registro en proceso
     * @return array
     */
    private function rfc(array $registro): array
    {
        if (!isset($registro['rfc'])) {
            $registro['rfc'] = 'AAA010101AAA';
        }
        return $registro;
    }
}