<?php

namespace gamboamartin\inmuebles\models;

use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\notificaciones\mail\_mail;
use gamboamartin\notificaciones\models\not_adjunto;
use gamboamartin\notificaciones\models\not_emisor;
use gamboamartin\notificaciones\models\not_mensaje;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\notificaciones\models\not_rel_mensaje;
use gamboamartin\validacion\validacion;
use PDO;
use stdClass;

class _email
{
    public const ERROR_CORREO_NO_VALIDO = "El correo '%s' no es válido.";
    public const ERROR_CORREO_NO_EXISTE = "El correo '%s' no es existe.";
    public const ERROR_FILTRO = "Error al filtrar al '%s'";
    public const ERROR_VALIDACION = "Error al validar el correo '%s'";
    public const ERROR_CORREO_NO_ENCONTRADO = "No se encontró el emisor con el correo '%s'";
    public const ERROR_AL_INSERTAR = "Error al insertar al '%s'";
    private PDO $link;

    public function __construct(PDO $link)
    {
        $this->link = $link;
    }

    private function asunto_reporte_asistencia(stdClass $row_entidad): string|array
    {
        $keys = array('org_empresa_razon_social','org_empresa_rfc');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $row_entidad);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar row_entidad',data:  $valida);
        }

        return " Reporte Asistencia - $row_entidad->inm_periodo_asistencia_descripcion ";
    }

    public function correo_validacion(string $correo, modelo $modelo, string $campo): array|stdClass
    {
        $validacion = $this->validar_correo($correo);
        if (!$validacion) {
            $mensaje_error = sprintf(self::ERROR_CORREO_NO_VALIDO, $correo);
            return (new errores())->error(mensaje: $mensaje_error, data: $correo);
        }

        $filtro = array();
        $filtro['email'] = $correo;
        $datos = $modelo->filtro_and(filtro: $filtro);
        if (errores::$error) {
            $mensaje_error = sprintf(self::ERROR_FILTRO, $campo);
            return (new errores())->error(mensaje: $mensaje_error, data: $datos);
        }

        return $datos;
    }

    private function data_email_reporte_asistencia(stdClass $row_entidad): array|stdClass
    {
        $asunto = $this->asunto_reporte_asistencia(row_entidad: $row_entidad);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al generar asunto', data: $asunto);

        }

        $mensaje = $this->mensaje_reporte_asistencia(asunto: $asunto);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al generar asunto', data: $asunto);
        }

        $data = new stdClass();
        $data->asunto = $asunto;
        $data->mensaje = $mensaje;

        return $data;
    }

    public function emisor(string $correo): array
    {
        $datos = $this->correo_validacion(correo: $correo, modelo: (new not_emisor(link: $this->link)), campo: 'emisor');
        if (errores::$error) {
            $mensaje_error = sprintf(self::ERROR_VALIDACION, $correo);
            return (new errores())->error(mensaje: $mensaje_error, data: $datos);
        }

        if ($datos->n_registros == 0) {
            $mensaje_error = sprintf(self::ERROR_CORREO_NO_EXISTE, $correo);
            return (new errores())->error(mensaje: $mensaje_error, data: $datos);
        }

        return $datos->registros[0];
    }

    private function genera_not_mensaje_reporte_asistencia_ins( PDO $link, stdClass $row_entidad): array
    {
        $data_mensaje = $this->data_email_reporte_asistencia(row_entidad: $row_entidad);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al generar asunto', data: $data_mensaje);
        }

        $not_emisor = $this->not_emisor(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al obtener emisor', data: $not_emisor);
        }

        $not_mensaje_ins = $this->not_mensaje_ins(data_mensaje: $data_mensaje,not_emisor:  $not_emisor);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al obtener emisor', data: $not_emisor);
        }
        return $not_mensaje_ins;
    }

    public function inserta_adjunto(array $doc, int $not_mensaje_id, PDO $link){

        $not_adjunto_ins['not_mensaje_id'] = $not_mensaje_id;
        $not_adjunto_ins['doc_documento_id'] = $doc['doc_documento_id'];
        $not_adjunto_ins['descripcion'] = '.'.date('YmdHis').mt_rand(10000,99999).
            '.'.$doc['doc_extension_descripcion'];

        $not_adjunto_ins['name_out'] =  $doc['doc_documento_name_out'];
        if($doc['doc_tipo_documento_descripcion'] !=='ADJUNTO') {
            $not_adjunto_ins['name_out'] = '.' . $doc['doc_extension_descripcion'];
        }

        $r_not_adjunto = (new not_adjunto(link: $link))->alta_registro(registro: $not_adjunto_ins);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar adjunto', data: $r_not_adjunto);
        }

        return $r_not_adjunto;
    }

    public function inserta_mensaje(PDO $link, stdClass $row_entidad){
        $not_mensaje_ins = $this->genera_not_mensaje_reporte_asistencia_ins(link: $link, row_entidad: $row_entidad);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al obtener emisor', data: $not_mensaje_ins);
        }

        $r_not_mensaje = (new not_mensaje(link: $link))->alta_registro(registro: $not_mensaje_ins);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar mensaje', data: $r_not_mensaje);
        }

        $key_entidad_id = 'inm_periodo_asistencia_id';

        $fc_notificacion_ins[$key_entidad_id] = $row_entidad->$key_entidad_id;
        $fc_notificacion_ins['not_mensaje_id'] = $r_not_mensaje->registro_id;

        $r_fc_notificacion = (new inm_notificacion_periodo(link: $link))->alta_registro(registro: $fc_notificacion_ins);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar fc_notificacion_ins', data: $r_fc_notificacion);
        }

        return $r_not_mensaje->registro_id;
    }



    public function notifica(int $not_mensaje_id, PDO $link, array $cc = array(), array $cco = array()){
        $not_mensaje = (new not_mensaje(link: $link))->registro(registro_id: $not_mensaje_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener mensaje',data:  $not_mensaje);
        }

        $filtro['not_mensaje.id'] = $not_mensaje_id;
        $r_not_adjunto = (new not_adjunto(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener adjuntos', data: $r_not_adjunto);
        }

        $adjuntos = $r_not_adjunto->registros;

        $mail = (new _mail())->envia(mensaje: $not_mensaje, adjuntos: $adjuntos,cc: $cc, cco: $cco);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al enviar mensaje',data:  $mail);
        }

        return $mail;
    }

    private function not_emisor(PDO $link){
        $not_emisores = (new not_emisor(link: $link))->registros_activos();
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al obtener emisor', data: $not_emisores);
        }
        $n_emisores = count($not_emisores);
        $indice = mt_rand(0,$n_emisores-1);

        return $not_emisores[$indice];
    }

    private function not_mensaje_ins(stdClass $data_mensaje, array $not_emisor): array
    {
        $not_mensaje_ins['asunto'] =  $data_mensaje->asunto;
        $not_mensaje_ins['mensaje'] =  $data_mensaje->mensaje;
        $not_mensaje_ins['not_emisor_id'] =  $not_emisor['not_emisor_id'];

        return $not_mensaje_ins;
    }

    public function receptor(string $correo): array
    {
        $datos = $this->correo_validacion(correo: $correo, modelo: (new not_receptor(link: $this->link)), campo: 'receptor');
        if (errores::$error) {
            $mensaje_error = sprintf(self::ERROR_VALIDACION, $correo);
            return (new errores())->error(mensaje: $mensaje_error, data: $datos);
        }

        if ($datos->n_registros == 0) {
            $alta_not_receptor = (new not_receptor(link: $this->link))->alta_registro(
                array(
                    'email' => $correo,
                    'descripcion' => $correo,
                    'codigo' => $correo,
                ));
            if (errores::$error) {
                $mensaje_error = sprintf(self::ERROR_AL_INSERTAR, 'receptor');
                return (new errores())->error(mensaje: $mensaje_error, data: $alta_not_receptor);
            }

            return (new not_receptor(link: $this->link))->registro(registro_id: $alta_not_receptor->registro_id);
        }

        return $datos->registros[0];
    }

    public function mensaje(string $asunto, string $mensaje, int $emisor): array
    {
        $UUID = (new not_mensaje(link: $this->link))->get_codigo_aleatorio(10);
        $alta_not_mensaje = (new not_mensaje(link: $this->link))->alta_registro(
            array(
                'asunto' => $asunto,
                'mensaje' => $mensaje,
                'not_emisor_id' => $emisor,
                'descripcion' => $asunto . $UUID,
                'codigo' => $UUID,
            ));
        if (errores::$error) {
            $mensaje_error = sprintf(self::ERROR_AL_INSERTAR, 'mensaje');
            return (new errores())->error(mensaje: $mensaje_error, data: $alta_not_mensaje);
        }

        return (new not_mensaje(link: $this->link))->registro(registro_id: $alta_not_mensaje->registro_id);
    }

    private function mensaje_reporte_asistencia(string $asunto): string
    {
        return "Buen día se envia $asunto";
    }

    public function mensaje_receptor(int $mensaje, int $receptor): array
    {
        $alta_not_mensaje_receptor = (new not_rel_mensaje(link: $this->link))->alta_registro(
            array(
                'not_mensaje_id' => $mensaje,
                'not_receptor_id' => $receptor,
            ));
        if (errores::$error) {
            $mensaje_error = sprintf(self::ERROR_AL_INSERTAR, 'relación mensaje receptor');
            return (new errores())->error(mensaje: $mensaje_error, data: $alta_not_mensaje_receptor);
        }

        return (new not_rel_mensaje(link: $this->link))->registro(registro_id: $alta_not_mensaje_receptor->registro_id);
    }

    public function adjunto(int $mensaje, int $documento): array
    {
        $alta_not_adjunto = (new not_adjunto(link: $this->link))->alta_registro(
            array(
                'not_mensaje_id' => $mensaje,
                'doc_documento_id' => $documento,
            ));
        if (errores::$error) {
            $mensaje_error = sprintf(self::ERROR_AL_INSERTAR, 'adjunto');
            return (new errores())->error(mensaje: $mensaje_error, data: $alta_not_adjunto);
        }

        return (new not_rel_mensaje(link: $this->link))->registro(registro_id: $alta_not_adjunto->registro_id);
    }

    public function adjuntos(int $mensaje, array $documentos): array
    {
        $resultado = array();
        foreach ($documentos as $documento) {
            $resultado[] = $this->adjunto(mensaje: $mensaje, documento: $documento);
        }

        return $resultado;
    }




    public function validar_correo($correo): mixed
    {
        return filter_var($correo, FILTER_VALIDATE_EMAIL);
    }

}