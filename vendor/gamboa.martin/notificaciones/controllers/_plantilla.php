<?php
namespace gamboamartin\notificaciones\controllers;

use config\generales;
use gamboamartin\administrador\models\adm_usuario;
use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_emisor;
use gamboamartin\notificaciones\models\not_mensaje;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\notificaciones\models\not_rel_mensaje;
use PDO;
use stdClass;

class _plantilla{
    private function accesos(string $dom_comercial, string $link_acceso, string $link_web_oficial,
                                  string $nombre_comercial, string $nombre_completo, string $password, string $usuario): array|string
    {


        $base = $this->base_correo_acceso($dom_comercial, $link_acceso, $link_web_oficial, $nombre_comercial, $password, $usuario);
        if(errores::$error){
            return (new errores())->error('Error al generar base',data: $base);
        }

        return "Buen día <b>$nombre_completo</b>: Estos son tus accesos: <br>$base->accesos<br> $base->pie<br> $base->estilo";
        
    }

    private function accesos_html(string $link_acceso, string $password, string $usuario): string|array
    {
        $link_acceso = trim($link_acceso);
        if($link_acceso === ''){
            return (new errores())->error('Error al generar link_acceso',data: $link_acceso);
        }
        $usuario = trim($usuario);
        if($usuario === ''){
            return (new errores())->error('Error al generar usuario',data: $usuario);
        }
        $password = trim($password);
        if($password === ''){
            return (new errores())->error('Error al generar password',data: $password);
        }

        $el_a = "<br><b>$link_acceso</b><br>";
        $el_user = "<br><b>Usuario:</b> $usuario<br>";
        $el_pass = "<br><b>Password:</b> $password<br>";
        return $el_a.$el_user.$el_pass;
    }

    private function base_correo_acceso(string $dom_comercial, string $link_acceso, string $link_web_oficial,
                                        string $nombre_comercial, string $password, string $usuario): array|stdClass
    {
        $link_acceso = trim($link_acceso);
        if($link_acceso === ''){
            return (new errores())->error('Error al generar link_acceso',data: $link_acceso);
        }
        $usuario = trim($usuario);
        if($usuario === ''){
            return (new errores())->error('Error al generar usuario',data: $usuario);
        }
        $password = trim($password);
        if($password === ''){
            return (new errores())->error('Error al generar password',data: $password);
        }

        $pie = $this->pie(dom_comercial: $dom_comercial,link_web_oficial:  $link_web_oficial,nombre_comercial: $nombre_comercial);
        if(errores::$error){
            return (new errores())->error('Error al generar pie',data: $pie);
        }

        $estilo = $this->estilo_correo();
        if(errores::$error){
            return (new errores())->error('Error al generar estilo',data: $estilo);
        }
        $accesos = $this->accesos_html($link_acceso, $password, $usuario);
        if(errores::$error){
            return (new errores())->error('Error al generar estilo',data: $accesos);
        }

        $html = new stdClass();
        $html->pie = $pie;
        $html->estilo = $estilo;
        $html->accesos = $accesos;

        return $html;


    }

    final public function bienvenida(string $dom_comercial, string $link_acceso, string $link_web_oficial, string $nombre_comercial, string $password, string $usuario): array|string
    {
        $link_acceso = trim($link_acceso);
        if($link_acceso === ''){
            return (new errores())->error('Error al generar link_acceso',data: $link_acceso);
        }
        $usuario = trim($usuario);
        if($usuario === ''){
            return (new errores())->error('Error al generar usuario',data: $usuario);
        }
        $password = trim($password);
        if($password === ''){
            return (new errores())->error('Error al generar password',data: $password);
        }
        $base = $this->base_correo_acceso($dom_comercial, $link_acceso, $link_web_oficial, $nombre_comercial, $password, $usuario);
        if(errores::$error){
            return (new errores())->error('Error al generar base',data: $base);
        }

        $html = "Estimado cliente: <br><br>";
        $html.= "Reciba un cordial saludo, el presente documento es para poder dar inicio al proceso de implementación.<br><br>";
        $html.= "Por lo anterior, requerimos la siguiente documentación por parte de la empresa.<br><br>";
        $html.= "<b>ACTA CONSITUTIVA DE LA EMPRESA </b><br><br>";
        $html.= "<b>PODER DEL REPRESENTANTE LEGAL (En caso de existir un representante legal distinto al definido en el acta constitutiva, anexar el poder legal). </b><br><br>";
        $html.= "<b>IDENTIFICACIÓN OFICIAL DEL REPRESENTANTE LEGAL</b><br><br>";
        $html.= "<b>CONSTANCIA DE SITUACIÓN FISCAL  </b><br><br>";
        $html.= "<b>COMPROBANTE DE DOMICILIO </b><br><br>";
        $html.= "<b>ACUSE DE AFILIACIÓN AL IMSS, DE LOS COLABORADORES QUE SERAN INSCRITOS AL FONDO DE PENSIÓN</b><br><br>";
        $html.= "Dicha documentación es de suma importancia, pues es necesaria para la elaboración del contrato de prestación de servicio. Anexamos un ejemplo de contrato para la revisión de este.<br><br>";
        $html.= "Tambien te dejamos tus datos de accesos para subir dicha informacion: <br><br>";
        $html.= "<br><br>$base->accesos <br><br> $base->pie <br> $base->estilo";

        return $html;
        
    }

    private function dom_comercial()
    {
        $dom_comercial = '';
        if(isset($generales->dom_comercial)){
            $dom_comercial = $generales->dom_comercial;
        }
        return $dom_comercial;

    }

    final public function envia_mensaje_accesos(int $adm_usuario_id, PDO $link): object|array
    {
        $not_mensaje = $this->init_mensaje_accesos(adm_usuario_id: $adm_usuario_id,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al maquetar mensaje',data:  $not_mensaje);
        }


        $envia_mensaje = (new not_mensaje(link: $link))->envia_mensaje(not_mensaje_id: $not_mensaje->registro_id);
        if(errores::$error){

            return (new errores())->error(mensaje: 'Error al enviar mensaje',data:  $envia_mensaje);
        }
        $envia_mensaje = (object)$envia_mensaje;
        $envia_mensaje->id_retorno = -1;

        return $envia_mensaje;

    }

    private function estilo_correo(): string
    {
        $font = "{font-family: Arial, Helvetica, sans-serif;font-size: 12px; }";
        return "<style> html $font li {} .pie {color: #0979AE;} </style>";

    }


    private function genera_mensaje_accesos(stdClass $adm_usuario): array|string
    {
        $params_msj = $this->params_msj_accesos(adm_usuario: $adm_usuario);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener params_msj',data:  $params_msj);
        }

        $mensaje = $this->mensaje_accesos(dom_comercial: $params_msj->dom_comercial,link_sistema:  $params_msj->link_sistema,
            link_web_oficial:  $params_msj->link_web_oficial,nombre_comercial:  $params_msj->nombre_comercial,
            nombre_completo: $params_msj->nombre_completo,password:  $adm_usuario->password,usuario:  $adm_usuario->user);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al maquetar mensaje',data:  $mensaje);
        }

        return $mensaje;

    }

    private function init_mensaje_accesos(int $adm_usuario_id, PDO $link): array|stdClass
    {
        $adm_usuario = (new adm_usuario(link: $link))->registro(registro_id: $adm_usuario_id, columnas_en_bruto: true,
            retorno_obj: true);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener usuario',data:  $adm_usuario);
        }

        $not_mensaje = $this->inserta_mensaje_accesos(adm_usuario: $adm_usuario,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al maquetar mensaje',data:  $not_mensaje);
        }


        $r_not_rel_mensaje = $this->inserta_not_rel_mensaje(email_receptor: $adm_usuario->email,
            link:  $link,not_mensaje_id:  $not_mensaje->not_mensaje_id);
        if(errores::$error){
            return(new errores())->error(mensaje: 'Error al insertar r_not_rel_mensaje',data:  $r_not_rel_mensaje);
        }

        return $not_mensaje;

    }

    private function inserta_mensaje_accesos(stdClass $adm_usuario, PDO $link): array|stdClass
    {
        $not_mensaje_ins = $this->not_mensaje_ins_accesos(adm_usuario: $adm_usuario,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al maquetar mensaje',data:  $not_mensaje_ins);
        }


        $not_mensaje = (new not_mensaje(link: $link))->alta_registro(registro: $not_mensaje_ins);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar mensaje',data:  $not_mensaje);
        }
        return $not_mensaje;

    }

    private function inserta_not_rel_mensaje(string $email_receptor, PDO $link, int $not_mensaje_id): array|stdClass
    {
        $not_receptor_id = (new not_receptor(link: $link))->not_receptor_id(email: $email_receptor);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error obtener not_receptor_id',data:  $not_receptor_id);
        }

        $not_rel_mensaje_ins = array();
        $not_rel_mensaje_ins['not_mensaje_id'] = $not_mensaje_id;
        $not_rel_mensaje_ins['not_receptor_id'] = $not_receptor_id;

        $r_not_rel_mensaje = (new not_rel_mensaje(link: $link))->alta_registro(registro: $not_rel_mensaje_ins);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar r_not_rel_mensaje',data:  $r_not_rel_mensaje);
        }
        return $r_not_rel_mensaje;

    }


    private function link_sistema(): string
    {
        $generales = new generales();
        $liga = $generales->url_base;
        return "<b>$liga</b>";

    }

    private function link_web_oficial()
    {

        $link_web_oficial = '';
        if(isset($generales->link_web_oficial)){
            $link_web_oficial = $generales->link_web_oficial;
        }
        return $link_web_oficial;

    }

    private function mensaje_accesos(string $dom_comercial, string $link_sistema, string $link_web_oficial, $nombre_comercial,
                                     string $nombre_completo, string $password, string $usuario): array|string
    {

        $mensaje_html = $this->accesos($dom_comercial, $link_sistema, $link_web_oficial, $nombre_comercial,
            $nombre_completo, $password, $usuario);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al maquetar mensaje',data:  $mensaje_html);
        }

        return $mensaje_html;

    }

    private function nombre_comercial()
    {

        $nombre_comercial = '';
        if(isset($generales->nombre_comercial)){
            $nombre_comercial = $generales->nombre_comercial;
        }
        return $nombre_comercial;

    }

    private function nombre_completo_user(stdClass $adm_usuario): string
    {
        return trim($adm_usuario->nombre.' '.$adm_usuario->ap.' '.$adm_usuario->am);

    }

    private function not_mensaje_ins_accesos(stdClass $adm_usuario, PDO $link): array
    {
        $mensaje = $this->genera_mensaje_accesos(adm_usuario: $adm_usuario);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al maquetar mensaje',data:  $mensaje);
        }


        $not_emisor = (new not_emisor(link: $link))->not_emisor_selected();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener emisores',data:  $not_emisor);
        }

        $not_mensaje_ins['mensaje'] = $mensaje;
        $not_mensaje_ins['not_emisor_id'] = $not_emisor->not_emisor_id;
        $not_mensaje_ins['asunto'] = 'Recuperacion de contraseña (No Reply)';

        return $not_mensaje_ins;

    }


    private function params_msj_accesos(stdClass $adm_usuario): array|stdClass
    {
        $link_sistema = $this->link_sistema();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener link_sistema',data:  $link_sistema);
        }
        $dom_comercial = $this->dom_comercial();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener dom_comercial',data:  $dom_comercial);
        }
        $link_web_oficial = $this->link_web_oficial();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener link_web_oficial',data:  $link_web_oficial);
        }
        $nombre_comercial = $this->nombre_comercial();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener nombre_comercial',data:  $nombre_comercial);
        }
        $nombre_completo = $this->nombre_completo_user(adm_usuario: $adm_usuario);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener nombre_completo',data:  $nombre_completo);
        }

        $params = new stdClass();
        $params->link_sistema = $link_sistema;
        $params->dom_comercial = $dom_comercial;
        $params->link_web_oficial = $link_web_oficial;
        $params->nombre_comercial = $nombre_comercial;
        $params->nombre_completo = $nombre_completo;

        return $params;

    }


    private function pie(string $dom_comercial, string $link_web_oficial, string $nombre_comercial): string
    {
        return "Quedamos a su disposicion para cualquier duda o aclaracion. <br> $dom_comercial <br> $link_web_oficial <br>$nombre_comercial";

    }


}
