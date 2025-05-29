<?php
namespace gamboamartin\inmuebles\controllers;

use gamboamartin\documento\models\doc_tipo_documento;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_co_acreditado_html;
use gamboamartin\inmuebles\html\inm_comprador_html;
use gamboamartin\inmuebles\models\inm_conf_docs_comprador;
use gamboamartin\inmuebles\models\inm_doc_comprador;
use gamboamartin\inmuebles\models\inm_comprador;
use stdClass;

class _inm_comprador{

    private errores $error;

    public function __construct(){
        $this->error = new errores();
    }

   private function aplica_seccion_co_acreditado(array $inm_comprador): bool
    {
        $aplica_seccion_co_acreditado = true;

        if((int)$inm_comprador['inm_attr_tipo_credito_id']=== 6 || (int)$inm_comprador['inm_attr_tipo_credito_id']=== 8) {
            $aplica_seccion_co_acreditado = false;
        }
        return $aplica_seccion_co_acreditado;
    }

    /**
     * @param controlador_inm_comprador $controler
     * @param int $n_apartado
     * @param string $tag_header
     * @param stdClass $row_upd
     * @return array|stdClass
     */
    private function data_co_acreditado(controlador_inm_comprador $controler, int $n_apartado, string $tag_header,
                                        stdClass $row_upd = new stdClass()): array|stdClass
    {

        $header_apartado = $this->header_apartado(html_entidad: $controler->html_entidad,n_apartado:  $n_apartado,
            tag_header: $tag_header);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar header',data:  $header_apartado);
        }


        $param = $this->param_header(n_apartado: $n_apartado);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros',data:  $param);
        }

        $key_header = $param->key_header;
        $controler->header_frontend->$key_header = $header_apartado;

        $inm_co_acreditado_inputs = (new inm_co_acreditado_html(html: $controler->html_base))->inputs(
            entidad: 'inm_co_acreditado', integra_prefijo: true, row_upd: $row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inm_co_acreditado_inputs);
        }

        $controler->inputs->inm_co_acreditado = $inm_co_acreditado_inputs;
        return $controler->inputs;
    }

    final public function frontend_co_acreditado(controlador_inm_comprador $controler, stdClass $row_upd = new stdClass()){
        $aplica_seccion_co_acreditado = $this->aplica_seccion_co_acreditado(inm_comprador: $controler->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar co_acreditado',data:  $aplica_seccion_co_acreditado);
        }
        $controler->aplica_seccion_co_acreditado = $aplica_seccion_co_acreditado;


        $headers = $this->get_headers(inm_comprador: $controler->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar headers',data:  $headers);
        }

        foreach ($headers as $n_apartado=>$tag_header){

            $inputs = $this->data_co_acreditado(controler: $controler,n_apartado:  $n_apartado,tag_header:  $tag_header,
                row_upd: $row_upd);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar inputs co acreditado',data:  $inputs);
            }
        }
        return $headers;
    }

    private function get_headers(array $inm_comprador){
        $headers = $this->headers_base();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar headers',data:  $headers);
        }


        $aplica_seccion_co_acreditado = $this->aplica_seccion_co_acreditado(inm_comprador: $inm_comprador);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar co_acreditado',data:  $headers);
        }

        if($aplica_seccion_co_acreditado) {
            $headers = $this->header_co_acreditado(headers: $headers);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar header',data:  $headers);
            }
        }
        return $headers;
    }

    private function header_apartado(inm_comprador_html $html_entidad, int $n_apartado, string $tag_header){
        $param = $this->param_header(n_apartado: $n_apartado);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros',data:  $param);
        }

        $header_apartado = $html_entidad->header_collapsible(id_css_button: $param->id_css_button,
            style_button: 'primary', tag_button: 'Ver/Ocultar',tag_header:  $tag_header);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar header',data:  $header_apartado);
        }
        return $header_apartado;
    }
    private function header_co_acreditado(array $headers): array
    {
        $headers['6'] = '6. DATOS DE IDENTIFICACIÓN QUE SERÁN VALIDADOS (OBLIGATORIOS EN CRÉDITO CONYUGAL, FAMILIAR O CORRESIDENCIAL)';
        $headers['7'] = '7. DATOS DE LA EMPRESA O PATRÓN CO ACREDITADO';
        return $headers;
    }

    private function headers_base(): array
    {
        $headers['1'] = '1. CRÉDITO SOLICITADO';
        $headers['2'] = '2. DATOS PARA DETERMINAR EL MONTO DE CRÉDITO';
        $headers['3'] = '3. DATOS DE LA VIVIENDA/TERRENO DESTINO DEL CRÉDITO';
        $headers['4'] = '4. DATOS DE LA EMPRESA O PATRÓN';
        $headers['5'] = '5. DATOS DE IDENTIFICACIÓN DEL (DE LA) DERECHOHABIENTE / DATOS QUE SERÁN VALIDADOS';
        $headers['8'] = '7. REFERENCIAS FAMILIARES DEL (DE LA) DERECHOHABIENTE / DATOS QUE SERÁN VALIDADOS';
        $headers['13'] = '13. DATOS FISCALES PARA FACTURACION';
        $headers['14'] = '14. CONTROL INTERNO';
        $headers['15'] = '15. DATOS CONYUGE';
        $headers['16'] = '16. BENEFICIARIOS';
        $headers['17'] = '17. REFERENCIAS';

        return $headers;
    }

    private function param_header(int $n_apartado): stdClass
    {
        $id_css_button = "collapse_a$n_apartado";
        $key_header = "apartado_$n_apartado";

        $data = new stdClass();

        $data->id_css_button = $id_css_button;
        $data->key_header = $key_header;

        return $data;
    }

    private function doc_existente(controlador_inm_comprador $controler, array $doc_tipo_documento, int $indice,
                                   array $inm_conf_docs_comprador, array $inm_doc_comprador){

        $existe = false;
        if($doc_tipo_documento['doc_tipo_documento_id'] === $inm_doc_comprador['doc_tipo_documento_id']){

            $existe = true;

            $inm_conf_docs_comprador = $this->buttons_base(
                controler: $controler,indice:  $indice,inm_comprador_id:  $controler->registro_id,
                inm_conf_docs_comprador:  $inm_conf_docs_comprador,inm_doc_comprador:  $inm_doc_comprador);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_comprador);
            }
        }

        $data = new stdClass();
        $data->existe = $existe;
        $data->inm_conf_docs_comprador = $inm_conf_docs_comprador;
        return $data;
    }
    
    private function inm_docs_comprador(controlador_inm_comprador $controler, array $doc_tipo_documento,
                                        int $indice, array $inm_conf_docs_comprador,array $inm_docs_comprador){
        $existe = false;
        foreach ($inm_docs_comprador as $inm_doc_comprador){

            $existe_doc_comprador = $this->doc_existente(controler: $controler,
                doc_tipo_documento:  $doc_tipo_documento,indice:  $indice,
                inm_conf_docs_comprador:  $inm_conf_docs_comprador,inm_doc_comprador:  $inm_doc_comprador);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar datos',data:  $existe_doc_comprador);
            }

            $inm_conf_docs_comprador = $existe_doc_comprador->inm_conf_docs_comprador;
            $existe = $existe_doc_comprador->existe;
            if($existe){
                break;
            }

        }
        if(!$existe){
            $inm_conf_docs_comprador = $this->integra_data(controler: $controler,
                doc_tipo_documento:  $doc_tipo_documento,indice:  $indice,
                inm_conf_docs_comprador:  $inm_conf_docs_comprador);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_comprador);
            }
        }
        return $inm_conf_docs_comprador;
    }

    private function integra_data(controlador_inm_comprador $controler, array $doc_tipo_documento,
                                  int $indice, array $inm_conf_docs_comprador){
        $params = array('doc_tipo_documento_id'=>$doc_tipo_documento['doc_tipo_documento_id']);

        $button = $controler->html->button_href(accion: 'subir_documento',etiqueta:
            'Subir Documento',registro_id:  $controler->registro_id,
            seccion:  'inm_comprador',style:  'warning', params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $button);
        }

        $inm_conf_docs_comprador = $this->integra_button_default(button: $button,
            indice:  $indice,inm_conf_docs_comprador:  $inm_conf_docs_comprador);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_comprador);
        }
        return $inm_conf_docs_comprador;
    }

    private function integra_button_default(string $button, int $indice, array $inm_conf_docs_comprador): array
    {
        $inm_conf_docs_comprador[$indice]['descarga'] = $button;
        $inm_conf_docs_comprador[$indice]['vista_previa'] = $button;
        $inm_conf_docs_comprador[$indice]['descarga_zip'] = $button;
        $inm_conf_docs_comprador[$indice]['elimina_bd'] = $button;
        return $inm_conf_docs_comprador;
    }

    private function inm_conf_docs_comprador(controlador_inm_comprador $controler, array $inm_docs_comprador, array $tipos_documentos){
        $in = array();
        if (count($tipos_documentos) > 0) {
            $in['llave'] = 'doc_tipo_documento.id';
            $in['values'] = $tipos_documentos;
        }

        $r_doc_tipo_documento = (new doc_tipo_documento(link: $controler->link))->filtro_and(in: $in);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al Obtener tipos de documento',data:  $r_doc_tipo_documento);
        }

        $inm_conf_docs_comprador = $r_doc_tipo_documento->registros;

        foreach ($inm_conf_docs_comprador as $indice=>$doc_tipo_documento){
            $inm_conf_docs_comprador = $this->inm_docs_comprador(controler: $controler,
                doc_tipo_documento:  $doc_tipo_documento,indice:  $indice,
                inm_conf_docs_comprador:  $inm_conf_docs_comprador,inm_docs_comprador:  $inm_docs_comprador);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar buttons',data:  $inm_conf_docs_comprador);
            }
        }
        return $inm_conf_docs_comprador;
    }

    final public function integra_inm_documentos_comprador(controlador_inm_comprador $controler){
        $inm_prospecto = (new inm_comprador(link: $controler->link))->registro(registro_id: $controler->registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_comprador',data:  $inm_prospecto);
        }

        $filtro['inm_conf_docs_comprador.es_foto'] = 'inactivo';
        $inm_conf_docs_prospecto = (new inm_conf_docs_comprador(link: $controler->link))->filtro_and(
            columnas: ['doc_tipo_documento_id'],filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_conf_docs_prospecto',data:  $inm_conf_docs_prospecto);
        }

        $doc_ids = array_map(function($registro) {
            return $registro['doc_tipo_documento_id'];
        }, $inm_conf_docs_prospecto->registros);

        if (count($doc_ids) <= 0) {
            return array();
        }

        $inm_docs_prospecto = (new inm_doc_comprador(link: $controler->link))->inm_docs_comprador(
            inm_comprador_id: $controler->registro_id, tipos_documentos: $doc_ids);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_docs_prospecto',data:  $inm_docs_prospecto);
        }

        $inm_docs_prospecto = $this->inm_conf_docs_comprador(controler: $controler,
            inm_docs_comprador:  $inm_docs_prospecto,tipos_documentos: $doc_ids);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar buttons',data:  $inm_docs_prospecto);
        }

        return $inm_docs_prospecto;
    }
}
