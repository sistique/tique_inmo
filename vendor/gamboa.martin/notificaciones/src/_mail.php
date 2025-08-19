<?php
namespace gamboamartin\notificaciones\mail;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\_dropbox;
use gamboamartin\inmuebles\models\inm_dropbox_ruta;
use PHPMailer\PHPMailer\PHPMailer;
use stdClass;
use Throwable;
use PDO;

class _mail{
    /**
     * Envia un correo con adjuntos
     * @param stdClass $mensaje
     * @param array $adjuntos
     * @param array $cc
     * @param array $cco
     * @return array|PHPMailer
     */
    final public function envia(stdClass $mensaje, PDO $link, array $adjuntos = array(), array $cc = array(),
                                array $cco = array()): array|PHPMailer
    {

        try {

            $mail = new PHPMailer (true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = $mensaje->not_emisor_host;
            $mail->Port = $mensaje->not_emisor_port;
            $mail->Username = $mensaje->not_emisor_user_name;
            $mail->Password = $mensaje->not_emisor_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->setFrom($mensaje->not_emisor_email, $mensaje->not_emisor_email);
            $mail->addAddress($mensaje->not_receptor_email, $mensaje->not_receptor_alias);
            $mail->isHTML(true);
            $mail->Subject = $mensaje->not_mensaje_asunto;
            $mail->Body = $mensaje->not_mensaje_mensaje;
            $mail->AltBody = $mensaje->not_mensaje_mensaje;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            foreach ($adjuntos as $adjunto){
                $path =  $adjunto['doc_documento_ruta_absoluta'];

                if((new generales())->guarda_archivo_dropbox) {
                    $filtro_drop['doc_documento.id'] = $adjunto['doc_documento_id'];
                    $r_inm_dropbox_ruta = (new inm_dropbox_ruta($link))->filtro_and(filtro: $filtro_drop);
                    if(errores::$error){
                        return (new errores())->error(mensaje: 'Error al obtener adjuntos', data: $r_inm_dropbox_ruta);
                    }

                    if($r_inm_dropbox_ruta->n_registros > 0) {
                        $reg = $r_inm_dropbox_ruta->registros[0];
                        $guarda = (new _dropbox(link: $link))->preview(
                            dropbox_id: $reg['inm_dropbox_ruta_id_dropbox'], extencion: $reg['doc_extension_descripcion']);
                        if (errores::$error) {
                            return (new errores())->error(mensaje: 'Error al obtener adjuntos', data: $guarda);
                        }

                        $path = (new generales())->path_base . $guarda->ruta_archivo;
                    }
                }

                $name =  $adjunto['not_adjunto_name_out'];
                $mail->AddAttachment($path, $name);
            }

            if (count($cc) > 0) {
                foreach ($cc as $c) {
                    $mail->addCC($c);
                }
            }

            if (count($cco) > 0) {
                foreach ($cco as $c) {
                    $mail->addBCC($c);
                }
            }

            $mail->send();


        } catch (Throwable $e) {
            return (new errores())->error(mensaje: 'Error al enviar mensaje',data: $e,es_final: true);
        }
        return $mail;
    }
}
