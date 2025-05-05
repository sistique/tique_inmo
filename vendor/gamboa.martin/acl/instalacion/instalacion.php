<?php
namespace gamboamartin\acl\instalacion;


use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{

    private function adm_accion(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'acl';
        $etiqueta_label = 'Acciones';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/acl';
        $adm_namespace_descripcion = 'gamboa.martin/acl';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }

    private function adm_grupo(PDO $link): array|stdClass
    {


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'acl';
        $etiqueta_label = 'Grupos';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/acl';
        $adm_namespace_descripcion = 'gamboa.martin/acl';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }

    private function adm_seccion(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'acl';
        $etiqueta_label = 'Secciones';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/acl';
        $adm_namespace_descripcion = 'gamboa.martin/acl';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;



    }

    private function adm_usuario(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'acl';
        $etiqueta_label = 'Usuarios';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/acl';
        $adm_namespace_descripcion = 'gamboa.martin/acl';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }



    final public function instala(PDO $link): array|stdClass
    {

        $result = new stdClass();


        $adm_seccion = $this->adm_seccion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_seccion', data:  $adm_seccion);
        }
        $result->adm_seccion = $adm_seccion;

        $adm_grupo = $this->adm_grupo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_grupo', data:  $adm_grupo);
        }
        $result->adm_grupo = $adm_grupo;


        $adm_accion = $this->adm_accion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_accion', data:  $adm_accion);
        }
        $result->adm_grupo = $adm_grupo;

        $adm_usuario = $this->adm_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_usuario', data:  $adm_usuario);
        }
        $result->adm_usuario = $adm_usuario;


        return $result;

    }


}
