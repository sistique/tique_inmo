<?php
namespace gamboamartin\notificaciones\instalacion;

use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\administrador\models\adm_accion;
use gamboamartin\administrador\models\adm_accion_basica;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\administrador\models\adm_seccion_pertenece;
use gamboamartin\administrador\models\adm_sistema;
use gamboamartin\errores\errores;
use gamboamartin\proceso\models\pr_etapa;
use gamboamartin\proceso\models\pr_etapa_proceso;
use gamboamartin\proceso\models\pr_proceso;
use gamboamartin\proceso\models\pr_tipo_proceso;
use PDO;
use stdClass;

class instalacion
{

    /**
     * @param PDO $link
     * @return array|stdClass
     */
    private function _add_not_tipo_medio(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: 'not_tipo_medio');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al agregar tabla', data:  $create);
        }
        $out->create = $create;


        return $out;
    }

    private function adm_usuario(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'notificaciones';
        $etiqueta_label = 'Usuarios';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/notificaciones';
        $adm_namespace_descripcion = 'gamboa.martin/notificaciones';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'recupera_contrasena',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'activo',titulo:  'Recupera Contraseña');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        return $adm_acciones_basicas;

    }


    private function not_adjunto(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_mensaje_id'] = new stdClass();
        $foraneas['doc_documento_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $campos = new stdClass();

        $campos->name_out = new stdClass();
        $campos->name_out->default = 'SN';


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }

        $result = new stdClass();

        $result->campos_r = $campos_r;

        $adm_menu_descripcion = 'Documentos';
        $adm_sistema_descripcion = 'notificaciones';
        $etiqueta_label = 'Adjuntos';
        $adm_seccion_pertenece_descripcion = 'not_adjunto';
        $adm_namespace_name = 'gamboamartin/notificaciones';
        $adm_namespace_descripcion = 'gamboa.martin/notificaciones';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $result;

    }

    private function not_emisor(PDO $link): array|stdClass
    {
        $result = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }
        $result->create = $create;

        $campos = new stdClass();

        $campos->email = new stdClass();
        $campos->user_name = new stdClass();
        $campos->password =new stdClass();
        $campos->port =new stdClass();
        $campos->host =new stdClass();
        $campos->smtp_secure =new stdClass();


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }



        $result->campos_r = $campos_r;


        $adm_menu_descripcion = 'Notificaciones';
        $adm_sistema_descripcion = 'notificaciones';
        $etiqueta_label = 'Emisores de Correo';
        $adm_seccion_pertenece_descripcion = 'not_emisor';
        $adm_namespace_descripcion = 'gamboa.martin/notificaciones';
        $adm_namespace_name = 'gamboamartin/notificaciones';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $result;

    }
    private function not_mensaje(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_emisor_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $campos = new stdClass();

        $campos->asunto = new stdClass();
        $campos->mensaje = new stdClass();
        $campos->mensaje->default = 'SIN MENSAJE';


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }

        $result = new stdClass();
        $result->foranenas = $foraneas_r;
        $result->campos = $campos_r;


        $adm_menu_descripcion = 'Notificaciones';
        $adm_sistema_descripcion = 'notificaciones';
        $etiqueta_label = 'Mensajes';
        $adm_seccion_pertenece_descripcion = 'not_mensaje';
        $adm_namespace_descripcion = 'gamboa.martin/notificaciones';
        $adm_namespace_name = 'gamboamartin/notificaciones';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'envia_mensaje',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'activo',titulo:  'Recupera Contraseña');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $modelo_pr_etapa_proceso = new pr_etapa_proceso(link: $link);
        $modelo_pr_etapa = new pr_etapa(link: $link);
        $modelo_pr_proceso = new pr_proceso(link: $link);
        $modelo_pr_tipo_proceso = new pr_tipo_proceso(link: $link);

        $inserta = (new _adm())->genera_pr_etapa_proceso(adm_accion_descripcion: 'alta_bd',
            adm_seccion_descripcion: __FUNCTION__, modelo_pr_etapa: $modelo_pr_etapa,
            modelo_pr_etapa_proceso: $modelo_pr_etapa_proceso, modelo_pr_proceso: $modelo_pr_proceso,
            modelo_pr_tipo_proceso: $modelo_pr_tipo_proceso, pr_etapa_codigo: 'ALTA', pr_proceso_codigo: 'NOTIFICACION',
            pr_tipo_proceso_codigo: 'Control');
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar rows', data: $inserta);
        }

        $modelo_pr_etapa_proceso = new pr_etapa_proceso(link: $link);
        $modelo_pr_etapa = new pr_etapa(link: $link);
        $modelo_pr_proceso = new pr_proceso(link: $link);
        $modelo_pr_tipo_proceso = new pr_tipo_proceso(link: $link);

        $inserta = (new _adm())->genera_pr_etapa_proceso(adm_accion_descripcion: 'envia_mensaje',
            adm_seccion_descripcion: __FUNCTION__, modelo_pr_etapa: $modelo_pr_etapa,
            modelo_pr_etapa_proceso: $modelo_pr_etapa_proceso, modelo_pr_proceso: $modelo_pr_proceso,
            modelo_pr_tipo_proceso: $modelo_pr_tipo_proceso, pr_etapa_codigo: 'ENVIADO', pr_proceso_codigo: 'NOTIFICACION',
            pr_tipo_proceso_codigo: 'Control');
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar rows', data: $inserta);
        }

        return $result;

    }

    private function not_mensaje_etapa(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_mensaje_id'] = new stdClass();
        $foraneas['pr_etapa_proceso_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $campos = new stdClass();

        $campos->fecha = new stdClass();
        $campos->fecha->tipo_dato = 'DATE';
        $campos->fecha->default = '1900-01-01';


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }

        $result = new stdClass();
        $result->foranenas = $foraneas_r;
        $result->campos = $campos_r;




        return $result;

    }

    private function not_receptor(PDO $link): array|stdClass
    {
        $result = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }
        $result->create = $create;

        $campos = new stdClass();

        $campos->email = new stdClass();


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }



        $result->campos_r = $campos_r;


        return $result;

    }
    private function not_rel_mensaje(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_mensaje_id'] = new stdClass();
        $foraneas['not_receptor_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }

        $result = new stdClass();
        $result->foranenas = $foraneas_r;

        $adm_menu_descripcion = 'Notificaciones';
        $adm_sistema_descripcion = 'notificaciones';
        $etiqueta_label = 'Mensajes';
        $adm_seccion_pertenece_descripcion = 'not_rel_mensaje';
        $adm_namespace_descripcion = 'gamboa.martin/notificaciones';
        $adm_namespace_name = 'gamboamartin/notificaciones';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'envia_mensaje',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'activo',titulo:  'Recupera Contraseña');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }


        $modelo_pr_etapa_proceso = new pr_etapa_proceso(link: $link);
        $modelo_pr_etapa = new pr_etapa(link: $link);
        $modelo_pr_proceso = new pr_proceso(link: $link);
        $modelo_pr_tipo_proceso = new pr_tipo_proceso(link: $link);

        $inserta = (new _adm())->genera_pr_etapa_proceso(adm_accion_descripcion: 'alta_bd',
            adm_seccion_descripcion: __FUNCTION__, modelo_pr_etapa: $modelo_pr_etapa,
            modelo_pr_etapa_proceso: $modelo_pr_etapa_proceso, modelo_pr_proceso: $modelo_pr_proceso,
            modelo_pr_tipo_proceso: $modelo_pr_tipo_proceso, pr_etapa_codigo: 'ALTA', pr_proceso_codigo: 'NOTIFICACION',
            pr_tipo_proceso_codigo: 'Control');
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar rows', data: $inserta);
        }


        $modelo_pr_etapa_proceso = new pr_etapa_proceso(link: $link);
        $modelo_pr_etapa = new pr_etapa(link: $link);
        $modelo_pr_proceso = new pr_proceso(link: $link);
        $modelo_pr_tipo_proceso = new pr_tipo_proceso(link: $link);

        $inserta = (new _adm())->genera_pr_etapa_proceso(adm_accion_descripcion: 'envia_mensaje',
            adm_seccion_descripcion: __FUNCTION__, modelo_pr_etapa: $modelo_pr_etapa,
            modelo_pr_etapa_proceso: $modelo_pr_etapa_proceso, modelo_pr_proceso: $modelo_pr_proceso,
            modelo_pr_tipo_proceso: $modelo_pr_tipo_proceso, pr_etapa_codigo: 'ENVIADO', pr_proceso_codigo: 'NOTIFICACION',
            pr_tipo_proceso_codigo: 'Control');
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar rows', data: $inserta);
        }


        return $result;

    }
    private function not_rel_mensaje_etapa(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_rel_mensaje_id'] = new stdClass();
        $foraneas['pr_etapa_proceso_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $campos = new stdClass();

        $campos->fecha = new stdClass();
        $campos->fecha->tipo_dato = 'DATE';
        $campos->fecha->default = '1900-01-01';

        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }

        $result = new stdClass();
        $result->foranenas = $foraneas_r;
        $result->campos = $campos_r;

        return $result;

    }

    private function not_tipo_medio(PDO $link): array|stdClass
    {
        $result = new stdClass();

        $create = $this->_add_not_tipo_medio(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $adm_menu_descripcion = 'Notificaciones';
        $adm_sistema_descripcion = 'notificaciones';
        $etiqueta_label = 'Tipos de Medios';
        $adm_seccion_pertenece_descripcion = 'not_tipo_medio';
        $adm_namespace_descripcion = 'gamboa.martin/notificaciones';
        $adm_namespace_name = 'gamboamartin/notificaciones';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        $acciones_basicas = (new adm_accion_basica(link: $link))->registros();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $acciones_basicas);
        }

        $adm_seccion_id = (new adm_seccion(link: $link))->adm_seccion_id(descripcion: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener seccion', data:  $adm_seccion_id);
        }

        foreach ($acciones_basicas as $accion_basica) {
            $filtro = array();
            $filtro['adm_seccion.id'] = $adm_seccion_id;
            $filtro['adm_accion.descripcion'] = $accion_basica['adm_accion_basica_descripcion'];
            $existe = (new adm_accion(link: $link))->existe(filtro: $filtro);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe accion', data:  $existe);
            }
            if(!$existe){
                $adm_accion_ins['descripcion'] = $accion_basica['adm_accion_basica_descripcion'];
                $adm_accion_ins['etiqueta_label'] = $accion_basica['adm_accion_basica_etiqueta_label'];
                $adm_accion_ins['adm_seccion_id'] = $adm_seccion_id;
                $adm_accion_ins['status'] = 'activo';
                $adm_accion_ins['icono'] = $accion_basica['adm_accion_basica_icono'];
                $adm_accion_ins['visible'] = $accion_basica['adm_accion_basica_visible'];
                $adm_accion_ins['inicio'] = $accion_basica['adm_accion_basica_inicio'];
                $adm_accion_ins['lista'] = $accion_basica['adm_accion_basica_lista'];
                $adm_accion_ins['seguridad'] = $accion_basica['adm_accion_basica_seguridad'];
                $adm_accion_ins['es_modal'] = $accion_basica['adm_accion_basica_es_modal'];
                $adm_accion_ins['es_view'] = $accion_basica['adm_accion_basica_es_view'];
                $adm_accion_ins['titulo'] = $accion_basica['adm_accion_basica_titulo'];
                $adm_accion_ins['css'] = $accion_basica['adm_accion_basica_css'];
                $adm_accion_ins['es_status'] = $accion_basica['adm_accion_basica_es_status'];
                $adm_accion_ins['es_lista'] = $accion_basica['adm_accion_basica_es_lista'];
                $adm_accion_ins['muestra_icono_btn'] = $accion_basica['adm_accion_basica_muestra_icono_btn'];
                $adm_accion_ins['muestra_titulo_btn'] = $accion_basica['adm_accion_basica_muestra_titulo_btn'];

                $alta = (new adm_accion(link: $link))->alta_registro(registro: $adm_accion_ins);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al insertar accion', data:  $alta);
                }
            }

        }



        $result->create = $create;


        return $result;

    }

    final public function instala(PDO $link): array|stdClass
    {

        $result = new stdClass();

        $not_tipo_medio = $this->not_tipo_medio(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_tipo_medio', data:  $not_tipo_medio);
        }

        $not_emisor = $this->not_emisor(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_emisor', data:  $not_emisor);
        }

        $result->not_emisor = $not_emisor;

        $not_receptor = $this->not_receptor(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_receptor', data:  $not_receptor);
        }

        $result->not_emisor = $not_emisor;

        $not_mensaje = $this->not_mensaje(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_mensaje', data:  $not_mensaje);
        }

        $result->not_mensaje = $not_mensaje;


        $not_adjunto = $this->not_adjunto(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_adjunto', data:  $not_adjunto);
        }

        $result->not_adjunto = $not_adjunto;


        $not_mensaje_etapa = $this->not_mensaje_etapa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_mensaje_etapa', data:  $not_mensaje_etapa);
        }

        $result->not_mensaje = $not_mensaje;

        $not_mensaje = $this->not_rel_mensaje(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_mensaje', data:  $not_mensaje);
        }

        $result->not_mensaje = $not_mensaje;


        $not_rel_mensaje_etapa = $this->not_rel_mensaje_etapa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar fc_factura', data:  $not_rel_mensaje_etapa);
        }

        $result->not_rel_mensaje_etapa = $not_rel_mensaje_etapa;

        $secciones_acl[] = 'adm_seccion';
        $secciones_acl[] = 'adm_accion';
        $secciones_acl[] = 'adm_accion_grupo';
        $secciones_acl[] = 'adm_grupo';
        $secciones_acl[] = 'adm_menu';
        $secciones_acl[] = 'adm_usuario';
        $secciones_acl[] = 'adm_sistema';
        $secciones_acl[] = 'adm_seccion_pertenece';

        $r_adm_sistema = (new adm_sistema(link: $link))->get_data_descripcion(dato: 'notificaciones');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener sistema', data:  $r_adm_sistema);
        }

        foreach ($secciones_acl as $adm_seccion_descripcion){
            $filtro = array();
            $filtro['adm_sistema.descripcion'] = 'notificaciones';
            $filtro['adm_seccion.descripcion'] = $adm_seccion_descripcion;
            $existe = (new adm_seccion_pertenece(link: $link))->existe(filtro: $filtro);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe seccion', data:  $existe);
            }

            if(!$existe){

                $adm_seccion_id = (new adm_seccion(link: $link))->adm_seccion_id(descripcion: $adm_seccion_descripcion);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al obtener adm_seccion_id', data:  $adm_seccion_id);
                }

                $adm_seccion_pertenece_ins['adm_sistema_id'] = $r_adm_sistema->registros[0]['adm_sistema_id'];
                $adm_seccion_pertenece_ins['adm_seccion_id'] = $adm_seccion_id;

                $alta_seccion_pertenece = (new adm_seccion_pertenece(link: $link))->alta_registro(registro: $adm_seccion_pertenece_ins);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al insertar alta_seccion_pertenece', data:  $alta_seccion_pertenece);
                }

            }
        }

        $adm_usuario = $this->adm_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_usuario', data:  $adm_usuario);
        }




        return $result;

    }

}
