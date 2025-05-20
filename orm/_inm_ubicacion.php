<?php
namespace gamboamartin\inmuebles\models;

use gamboamartin\errores\errores;
use gamboamartin\inmuebles\controllers\_doctos;
use gamboamartin\inmuebles\controllers\controlador_inm_comprador;
use gamboamartin\inmuebles\controllers\controlador_inm_ubicacion;
use gamboamartin\inmuebles\controllers\controlador_inm_ubicacion;
use gamboamartin\inmuebles\controllers\controlador_inm_ubicacion;
use gamboamartin\validacion\validacion;
use stdClass;

class _inm_ubicacion{

    private errores $error;

    public function __construct(){
        $this->error = new errores();
    }


    private function button(string $accion, controlador_inm_ubicacion $controler, string $etiqueta, int $indice,
                                 int $inm_doc_ubicacion_id, array $inm_conf_docs_ubicacion, array $params = array(),
                                 string $style = 'success', string $target = ''): array
    {
        $button = $controler->html->button_href(accion: $accion, etiqueta: $etiqueta,
            registro_id: $inm_doc_ubicacion_id, seccion: 'inm_doc_ubicacion', style: $style, params: $params,
            target: $target);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $button);
        }
        $inm_conf_docs_ubicacion[$indice][$accion] = $button;
        return $inm_conf_docs_ubicacion;
    }

    final public function button_del(controlador_inm_ubicacion $controler, int $indice, int $inm_ubicacion_id,
                                array $inm_conf_docs_ubicacion, array $inm_doc_ubicacion){

        $params = array('accion_retorno'=>'documentos','seccion_retorno'=>$controler->seccion,
            'id_retorno'=>$inm_ubicacion_id);

        $inm_conf_docs_comprador = (new _inm_ubicacion())->button(accion: 'elimina_bd', controler: $controler,
            etiqueta: 'Elimina', indice: $indice, inm_doc_ubicacion_id: $inm_doc_ubicacion['inm_doc_ubicacion_id'],
            inm_conf_docs_ubicacion: $inm_conf_docs_ubicacion, params: $params, style: 'danger');

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_comprador);
        }
        return $inm_conf_docs_comprador;
    }

    private function buttons(controlador_inm_ubicacion $controler, int $indice, array $inm_conf_docs_ubicacion,
                                  array $inm_doc_ubicacion){

        $inm_conf_docs_ubicacion = $this->button(accion: 'descarga', controler: $controler,
            etiqueta: 'Descarga', indice: $indice, inm_doc_ubicacion_id: $inm_doc_ubicacion['inm_doc_ubicacion_id'],
            inm_conf_docs_ubicacion: $inm_conf_docs_ubicacion);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_ubicacion);
        }

        $inm_conf_docs_ubicacion = $this->button(accion: 'vista_previa', controler: $controler,
            etiqueta: 'Vista Previa', indice: $indice, inm_doc_ubicacion_id: $inm_doc_ubicacion['inm_doc_ubicacion_id'],
            inm_conf_docs_ubicacion: $inm_conf_docs_ubicacion, target: '_blank');

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_ubicacion);
        }

        $inm_conf_docs_ubicacion = $this->button(accion: 'descarga_zip', controler: $controler,
            etiqueta: 'ZIP', indice: $indice, inm_doc_ubicacion_id: $inm_doc_ubicacion['inm_doc_ubicacion_id'],
            inm_conf_docs_ubicacion: $inm_conf_docs_ubicacion);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_ubicacion);
        }
        return $inm_conf_docs_ubicacion;
    }

    private function buttons_base(controlador_inm_ubicacion $controler, int $indice, int $inm_ubicacion_id,
                                  array $inm_conf_docs_ubicacion, array $inm_doc_ubicacion): array
    {
        $inm_conf_docs_ubicacion = $this->buttons(controler: $controler,indice:  $indice,
            inm_conf_docs_ubicacion:  $inm_conf_docs_ubicacion,inm_doc_ubicacion:  $inm_doc_ubicacion);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_ubicacion);
        }

        $inm_conf_docs_ubicacion = $this->button_del(controler: $controler,indice:  $indice,
            inm_ubicacion_id:  $inm_ubicacion_id,inm_conf_docs_ubicacion:  $inm_conf_docs_ubicacion,
            inm_doc_ubicacion:  $inm_doc_ubicacion);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_ubicacion);
        }

        return $inm_conf_docs_ubicacion;
    }

    /**
     * Integra los checkeds default para upd
     * @param controlador_inm_comprador|controlador_inm_ubicacion $controler Controlador en ejecucion
     * @return stdClass|array
     */
    private function checkeds_default(controlador_inm_comprador|controlador_inm_ubicacion $controler): stdClass|array
    {
        $keys = array('es_segundo_credito','con_discapacidad');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $controler->row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar row upd',data:  $valida);
        }
        $checked_default_esc = 1;
        if($controler->row_upd->es_segundo_credito === 'NO'){
            $checked_default_esc = 2;
        }

        $checked_default_cd = 2;
        if($controler->row_upd->con_discapacidad === 'NO'){
            $checked_default_cd = 1;
        }

        $data = new stdClass();
        $data->checked_default_esc = $checked_default_esc;
        $data->checked_default_cd = $checked_default_cd;

        return $data;

    }

    private function doc_existente(controlador_inm_ubicacion $controler, array $doc_tipo_documento, int $indice,
                                        array $inm_conf_docs_ubicacion, array $inm_doc_ubicacion){

        $existe = false;
        if($doc_tipo_documento['doc_tipo_documento_id'] === $inm_doc_ubicacion['doc_tipo_documento_id']){

            $existe = true;

            $inm_conf_docs_ubicacion = $this->buttons_base(
                controler: $controler,indice:  $indice,inm_ubicacion_id:  $controler->registro_id,
                inm_conf_docs_ubicacion:  $inm_conf_docs_ubicacion,inm_doc_ubicacion:  $inm_doc_ubicacion);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_ubicacion);
            }
        }

        $data = new stdClass();
        $data->existe = $existe;
        $data->inm_conf_docs_ubicacion = $inm_conf_docs_ubicacion;
        return $data;
    }



    private function inm_conf_docs_ubicacion(controlador_inm_ubicacion $controler, array $inm_docs_ubicacion, array $tipos_documentos){
        $inm_conf_docs_ubicacion = (new _doctos())->documentos_de_ubicacion(inm_ubicacion_id: $controler->registro_id,
            link:  $controler->link, todos: true, tipos_documentos: $tipos_documentos);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener configuraciones de documentos',
                data:  $inm_conf_docs_ubicacion);
        }

        foreach ($inm_conf_docs_ubicacion as $indice=>$doc_tipo_documento){
            $inm_conf_docs_ubicacion = $this->inm_docs_ubicacion(controler: $controler,
                doc_tipo_documento:  $doc_tipo_documento,indice:  $indice,
                inm_conf_docs_ubicacion:  $inm_conf_docs_ubicacion,inm_docs_ubicacion:  $inm_docs_ubicacion);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar buttons',data:  $inm_conf_docs_ubicacion);
            }
        }
        return $inm_conf_docs_ubicacion;
    }

    private function inm_docs_ubicacion(controlador_inm_ubicacion $controler, array $doc_tipo_documento,
                                             int $indice, array $inm_conf_docs_ubicacion,array $inm_docs_ubicacion){
        $existe = false;
        foreach ($inm_docs_ubicacion as $inm_doc_ubicacion){

            $existe_doc_ubicacion = $this->doc_existente(controler: $controler,
                doc_tipo_documento:  $doc_tipo_documento,indice:  $indice,
                inm_conf_docs_ubicacion:  $inm_conf_docs_ubicacion,inm_doc_ubicacion:  $inm_doc_ubicacion);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar datos',data:  $existe_doc_ubicacion);
            }

            $inm_conf_docs_ubicacion = $existe_doc_ubicacion->inm_conf_docs_ubicacion;
            $existe = $existe_doc_ubicacion->existe;
            if($existe){
                break;
            }

        }
        if(!$existe){
            $inm_conf_docs_ubicacion = $this->integra_data(controler: $controler,
                doc_tipo_documento:  $doc_tipo_documento,indice:  $indice,
                inm_conf_docs_ubicacion:  $inm_conf_docs_ubicacion);

            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_ubicacion);
            }
        }
        return $inm_conf_docs_ubicacion;
    }


    /**
     * Genera un registro para la insersion de una relacion entre conyuge y ubicacion
     * @param int $inm_conyuge_id Id de conyuge
     * @param int $inm_ubicacion_id Id de ubicacion
     * @return array
     * @version 2.266.2
     */
    final public function inm_rel_conyuge_ubicacion_ins(int $inm_conyuge_id, int $inm_ubicacion_id): array
    {
        if($inm_conyuge_id <= 0){
            return $this->error->error(mensaje: 'Error inm_conyuge_id debe ser mayor a 0',data:  $inm_conyuge_id);
        }
        if($inm_ubicacion_id <= 0){
            return $this->error->error(mensaje: 'Error inm_ubicacion_id debe ser mayor a 0',data:  $inm_ubicacion_id);
        }
        $inm_rel_conyuge_ubicacion_ins['inm_ubicacion_id'] = $inm_ubicacion_id;
        $inm_rel_conyuge_ubicacion_ins['inm_conyuge_id'] = $inm_conyuge_id;

        return $inm_rel_conyuge_ubicacion_ins;
    }



    private function integra_button_default(string $button, int $indice, array $inm_conf_docs_ubicacion): array
    {
        $inm_conf_docs_ubicacion[$indice]['descarga'] = $button;
        $inm_conf_docs_ubicacion[$indice]['vista_previa'] = $button;
        $inm_conf_docs_ubicacion[$indice]['descarga_zip'] = $button;
        $inm_conf_docs_ubicacion[$indice]['elimina_bd'] = $button;
        return $inm_conf_docs_ubicacion;
    }

    private function integra_data(controlador_inm_ubicacion $controler, array $doc_tipo_documento,
                                       int $indice, array $inm_conf_docs_ubicacion){
        $params = array('doc_tipo_documento_id'=>$doc_tipo_documento['doc_tipo_documento_id']);

        $button = $controler->html->button_href(accion: 'subir_documento',etiqueta:
            'Subir Documento',registro_id:  $controler->registro_id,
            seccion:  'inm_ubicacion',style:  'warning', params: $params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $button);
        }

        $inm_conf_docs_ubicacion = $this->integra_button_default(button: $button,
            indice:  $indice,inm_conf_docs_ubicacion:  $inm_conf_docs_ubicacion);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar button',data:  $inm_conf_docs_ubicacion);
        }
        return $inm_conf_docs_ubicacion;
    }

    final public function integra_inm_documentos(controlador_inm_ubicacion $controler){
        $inm_ubicacion = (new inm_ubicacion(link: $controler->link))->registro(registro_id: $controler->registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_ubicacion',data:  $inm_ubicacion);
        }

        $filtro['inm_conf_docs_ubicacion.es_foto'] = 'inactivo';
        $inm_conf_docs_ubicacion = (new inm_conf_docs_ubicacion(link: $controler->link))->filtro_and(
            columnas: ['doc_tipo_documento_id'],
            filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_conf_docs_ubicacion',data:  $inm_conf_docs_ubicacion);
        }

        $doc_ids = array_map(function($registro) {
            return $registro['doc_tipo_documento_id'];
        }, $inm_conf_docs_ubicacion->registros);

        if (count($doc_ids) <= 0) {
            return array();
        }

        $inm_docs_ubicacion = (new inm_doc_ubicacion(link: $controler->link))->inm_docs_ubicacion(
            inm_ubicacion: $controler->registro_id, tipos_documentos: $doc_ids);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_docs_ubicacion',data:  $inm_docs_ubicacion);
        }

        $inm_docs_ubicacion = $this->inm_conf_docs_ubicacion(controler: $controler,inm_docs_ubicacion:  $inm_docs_ubicacion,
            tipos_documentos: $doc_ids);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar buttons',data:  $inm_docs_ubicacion);
        }

        return $inm_docs_ubicacion;
    }



}
