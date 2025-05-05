<?php
namespace gamboamartin\notificaciones\mail;
use gamboamartin\errores\errores;
use PHPMailer\PHPMailer\PHPMailer;
use stdClass;
use Throwable;

class _mail{
    /**
     * Envia un correo con adjuntos
     * @param stdClass $mensaje
     * @param array $adjuntos
     * @param array $cc
     * @param array $cco
     * @return array|PHPMailer
     */
    final public function envia(stdClass $mensaje, array $adjuntos = array(), array $cc = array(), array $cco = array()): array|PHPMailer
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
            foreach ($adjuntos as $adjunto ){
                $path =  $adjunto['doc_documento_ruta_absoluta'];
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
