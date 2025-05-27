<?php
namespace gamboamartin\administrador\instalacion;

use config\generales;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\administrador\models\adm_accion;
use gamboamartin\administrador\models\adm_accion_basica;
use gamboamartin\administrador\models\adm_grupo;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\administrador\models\adm_tipo_dato;
use gamboamartin\administrador\models\adm_usuario;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{

    private function _add_adm_campo(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_campo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['adm_tipo_dato_id'] = new stdClass();
        $foraneas['adm_seccion_id'] = new stdClass();

        $result = (new _instalacion(link: $link))->foraneas(foraneas: $foraneas,table:  'adm_campo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }

        $campos = new stdClass();
        $campos->codigo = new stdClass();
        $campos->sub_consulta = new stdClass();
        $campos->sub_consulta->tipo_dato = 'TEXT';

        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $campos->es_foranea = new stdClass();
        $campos->es_foranea->default = 'inactivo';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_campo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_menu(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_menu');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;


        $campos = new stdClass();
        $campos->codigo = new stdClass();


        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $campos->etiqueta_label = new stdClass();
        $campos->etiqueta_label->default = 'SE';

        $campos->icono = new stdClass();
        $campos->icono->default = 'SI';

        $campos->titulo = new stdClass();
        $campos->titulo->default = 'ST';



        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_menu');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_atributo(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_atributo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['adm_tipo_dato_id'] = new stdClass();
        $foraneas['adm_seccion_id'] = new stdClass();

        $result = (new _instalacion(link: $link))->foraneas(foraneas: $foraneas,table:  'adm_atributo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }




        return $out;

    }

    private function _add_adm_namespace(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_namespace');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;


        $campos = new stdClass();
        $campos->codigo = new stdClass();


        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';


        $campos->name = new stdClass();
        $campos->name->default = 'SN';


        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_namespace');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_grupo(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_grupo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;


        $campos = new stdClass();
        $campos->codigo = new stdClass();


        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $campos->root = new stdClass();
        $campos->root->default = 'inactivo';

        $campos->solo_mi_info = new stdClass();
        $campos->solo_mi_info->default = 'inactivo';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_grupo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_sistema(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_sistema');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;


        $campos = new stdClass();
        $campos->codigo = new stdClass();


        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_sistema');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_usuario(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_usuario');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;


        $campos = new stdClass();
        $campos->codigo = new stdClass();


        $campos->descripcion = new stdClass();
        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $campos->user = new stdClass();
        $campos->password = new stdClass();
        $campos->email = new stdClass();
        $campos->status = new stdClass();
        $campos->status->default = 'activo';
        $campos->session = new stdClass();
        $campos->telefono = new stdClass();
        $campos->nombre = new stdClass();
        $campos->ap = new stdClass();
        $campos->am = new stdClass();


        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_usuario');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }

        $foraneas = array();
        $foraneas['adm_grupo_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_usuario');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    private function _add_adm_bitacora(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_bitacora');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;


        $foraneas = array();
        $foraneas['adm_seccion_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_bitacora');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        $campos = new stdClass();
        $campos->codigo = new stdClass();
        $campos->descripcion = new stdClass();


        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';


        $campos->registro = new stdClass();
        $campos->registro->tipo_dato = 'LONGBLOB';
        //$campos->registro->default = 'SN';

        $campos->sql_data = new stdClass();
        $campos->sql_data->tipo_dato = 'LONGBLOB';
        //$campos->sql_data->default = 'SN';

        $campos->valor_id = new stdClass();
        $campos->valor_id->tipo_dato = 'BIGINT';
        $campos->valor_id->default = '-1';

        $campos->adm_usuario_id = new stdClass();
        $campos->adm_usuario_id->tipo_dato = 'BIGINT';
        $campos->adm_usuario_id->default = '-1';

        $campos->transaccion = new stdClass();
        $campos->transaccion->tipo_dato = 'VARCHAR';



        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_bitacora');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_reporte(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_reporte');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $campos = new stdClass();
        $campos->codigo = new stdClass();
        $campos->descripcion = new stdClass();


        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $campos->alias = new stdClass();
        $campos->alias->default = 'SIN AL';


        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_reporte');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_categoria_usuario(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_categoria_usuario');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['adm_categoria_sistema_id'] = new stdClass();
        $foraneas['adm_usuario_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_categoria_usuario');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        return $out;

    }

    private function _add_adm_categoria_sistema(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_categoria_sistema');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['adm_sistema_id'] = new stdClass();
        $foraneas['adm_categoria_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_categoria_sistema');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        return $out;

    }

    private function _add_adm_categoria(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_categoria');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;

    }

    private function _add_adm_categoria_secciones(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_categoria_secciones');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['adm_categoria_usuario_id'] = new stdClass();
        $foraneas['adm_seccion_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_categoria_secciones');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        return $out;

    }

    private function _add_adm_seccion(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_seccion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['adm_menu_id'] = new stdClass();
        $foraneas['adm_namespace_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_seccion');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        $campos = new stdClass();
        $campos->etiqueta_label = new stdClass();
        $campos->etiqueta_label->default = 'SIN ETIQUETA';

        $campos->icono = new stdClass();
        $campos->icono->default = 'SIN ICONO';

        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_seccion');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;


        return $out;

    }

    private function _add_adm_accion(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_accion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['adm_seccion_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_accion');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        $campos = new stdClass();
        $campos->etiqueta_label = new stdClass();
        $campos->etiqueta_label->default = 'SIN ETIQUETA';

        $campos->icono = new stdClass();
        $campos->icono->default = 'SIN ICONO';

        $campos->visible = new stdClass();
        $campos->visible->default = 'activo';

        $campos->inicio = new stdClass();
        $campos->inicio->default = 'activo';

        $campos->lista = new stdClass();
        $campos->lista->default = 'activo';

        $campos->seguridad = new stdClass();
        $campos->seguridad->default = 'activo';

        $campos->es_modal = new stdClass();
        $campos->es_modal->default = 'inactivo';

        $campos->es_view = new stdClass();
        $campos->es_view->default = 'activo';

        $campos->titulo = new stdClass();
        $campos->titulo->default = 'SIN TITULO';

        $campos->css = new stdClass();
        $campos->css->default = 'info';

        $campos->es_status = new stdClass();
        $campos->es_status->default = 'inactivo';

        $campos->es_lista = new stdClass();
        $campos->es_lista->default = 'activo';

        $campos->muestra_icono_btn = new stdClass();
        $campos->muestra_icono_btn->default = 'activo';

        $campos->muestra_titulo_btn = new stdClass();
        $campos->muestra_titulo_btn->default = 'activo';

        $campos->id_css = new stdClass();
        $campos->id_css->default = '';



        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_accion');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;


        return $out;

    }

    private function _add_adm_seccion_pertenece(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_seccion_pertenece');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['adm_seccion_id'] = new stdClass();
        $foraneas['adm_sistema_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_seccion_pertenece');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        $campos = new stdClass();


        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'activo';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_seccion_pertenece');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_accion_grupo(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_accion_grupo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;


        $campos = new stdClass();
        $campos->codigo = new stdClass();
        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';
        $campos->descripcion = new stdClass();
        $campos->descripcion->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_accion_grupo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        $foraneas = array();
        $foraneas['adm_accion_id'] = new stdClass();
        $foraneas['adm_grupo_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_accion_grupo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Esta función agrega la entidad tipo de dato en el administrador.
     *
     * @param PDO $link Conexión a la base de datos.
     *
     * @return stdClass|array Devuelve un objeto con los resultados de la operación.
     * Si hay un error, devuelve un array con los detalles del error.
     *
     * @version 17.39.0
     */
    private function _add_adm_tipo_dato(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_tipo_dato');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $campos = new stdClass();
        $campos->codigo = new stdClass();
        $campos->descripcion_select = new stdClass();
        $campos->descripcion_select->default = 'SIN DS';

        $campos->codigo_bis = new stdClass();
        $campos->predeterminado = new stdClass();
        $campos->predeterminado->default = 'inactivo';

        $campos->sub_consulta = new stdClass();


        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_tipo_dato');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_adm_accion_basica(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_accion_basica');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;


        $campos = new stdClass();
        $campos->etiqueta_label = new stdClass();
        $campos->etiqueta_label->default = 'SIN ETIQUETA';

        $campos->icono = new stdClass();
        $campos->icono->default = 'SIN ICONO';

        $campos->visible = new stdClass();
        $campos->visible->default = 'activo';

        $campos->inicio = new stdClass();
        $campos->inicio->default = 'activo';

        $campos->lista = new stdClass();
        $campos->lista->default = 'activo';

        $campos->seguridad = new stdClass();
        $campos->seguridad->default = 'activo';

        $campos->es_modal = new stdClass();
        $campos->es_modal->default = 'inactivo';

        $campos->es_view = new stdClass();
        $campos->es_view->default = 'activo';

        $campos->titulo = new stdClass();
        $campos->titulo->default = 'SIN TITULO';

        $campos->css = new stdClass();
        $campos->css->default = 'info';

        $campos->es_status = new stdClass();
        $campos->es_status->default = 'inactivo';

        $campos->es_lista = new stdClass();
        $campos->es_lista->default = 'activo';

        $campos->muestra_icono_btn = new stdClass();
        $campos->muestra_icono_btn->default = 'activo';

        $campos->muestra_titulo_btn = new stdClass();
        $campos->muestra_titulo_btn->default = 'activo';

        $campos->id_css = new stdClass();
        $campos->id_css->default = '';


        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_accion_basica');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;


        return $out;

    }

    private function accion_basica_importa(string $accion_basica_descripcion, PDO $link)
    {
        $accion_basica_importa = (new adm_accion_basica(link: $link))->accion_basica(descripcion:$accion_basica_descripcion);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener accion_basica_importa',
                data:  $accion_basica_importa);
        }
        unset($accion_basica_importa['id']);
        unset($accion_basica_importa['usuario_alta_id']);
        unset($accion_basica_importa['usuario_update_id']);

        return $accion_basica_importa;

    }

    private function adm_accion(PDO $link): array|stdClass
    {

        $adm_acciones = (new adm_accion(link: $link))->registros(columnas_en_bruto: true);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones', data:  $adm_acciones);
        }
        $upds = array();
        foreach ($adm_acciones as $adm_accion){
            $upd = array();
            if($adm_accion['es_view'] === 'false'){
                $upd['es_view'] = 'inactivo';
            }
            if($adm_accion['descripcion'] === 'lista'){
                if($adm_accion['visible'] === 'inactivo') {
                    $upd['visible'] = 'activo';
                }
            }
            if($adm_accion['descripcion'] === 'descarga_excel'){
                if($adm_accion['visible'] === 'inactivo') {
                    $upd['visible'] = 'activo';
                }
            }
            if($adm_accion['descripcion'] === 'alta'){
                if($adm_accion['visible'] === 'inactivo') {
                    $upd['visible'] = 'activo';
                }
            }
            if($adm_accion['descripcion'] === 'importa'){
                if($adm_accion['visible'] === 'inactivo') {
                    $upd['visible'] = 'activo';
                }
            }
            if($adm_accion['descripcion'] === 'modifica'){
                if($adm_accion['visible'] === 'activo') {
                    $upd['visible'] = 'inactivo';
                }
            }
            if($adm_accion['descripcion'] === 'elimina_bd'){
                if($adm_accion['id_css'] === '') {
                    $upd['id_css'] = 'elimina_bd';
                }
            }

            if(count($upd) >0) {
                $r_upd = (new adm_accion(link: $link))->modifica_bd_base(registro: $upd, id: $adm_accion['id']);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al actualizar accion', data: $r_upd);
                }
                $upds[] = $r_upd;
            }
        }

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Acciones';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'get_adm_accion',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'inactivo',titulo:  'Get Adm Accion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'asigna_permiso',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'activo', icono: 'bi bi-database-lock',
            link:  $link, lista:  'activo',titulo:  'Asigna Permiso');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        return $upds;

    }
    private function adm_accion_basica(PDO $link): array|stdClass
    {

        $create = $this->_add_adm_accion_basica(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $out = new stdClass();

        $adm_acciones_basicas = array();
        $adm_acciones_basicas[0]['descripcion'] = 'get_data_descripcion';
        $adm_acciones_basicas[0]['visible'] = 'inactivo';
        $adm_acciones_basicas[0]['seguridad'] = 'activo';
        $adm_acciones_basicas[0]['inicio'] = 'inactivo';
        $adm_acciones_basicas[0]['lista'] = 'inactivo';
        $adm_acciones_basicas[0]['status'] = 'activo';
        $adm_acciones_basicas[0]['es_view'] = 'inactivo';
        $adm_acciones_basicas[0]['codigo'] = 'get_data_descripcion';
        $adm_acciones_basicas[0]['codigo_bis'] = 'get_data_descripcion';
        $adm_acciones_basicas[0]['descripcion_select'] = 'get_data_descripcion';
        $adm_acciones_basicas[0]['etiqueta_label'] = 'get_data_descripcion';
        $adm_acciones_basicas[0]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[0]['titulo'] = 'get_data_descripcion';
        $adm_acciones_basicas[0]['css'] = 'info';
        $adm_acciones_basicas[0]['es_status'] = 'inactivo';
        $adm_acciones_basicas[0]['alias'] = 'get_data_descripcion';
        $adm_acciones_basicas[0]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[0]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[0]['muestra_titulo_btn'] = 'inactivo';

        $adm_acciones_basicas[1]['descripcion'] = 'importa';
        $adm_acciones_basicas[1]['visible'] = 'activo';
        $adm_acciones_basicas[1]['seguridad'] = 'activo';
        $adm_acciones_basicas[1]['inicio'] = 'inactivo';
        $adm_acciones_basicas[1]['lista'] = 'inactivo';
        $adm_acciones_basicas[1]['status'] = 'activo';
        $adm_acciones_basicas[1]['es_view'] = 'activo';
        $adm_acciones_basicas[1]['codigo'] = 'importa';
        $adm_acciones_basicas[1]['codigo_bis'] = 'importa';
        $adm_acciones_basicas[1]['descripcion_select'] = 'importa';
        $adm_acciones_basicas[1]['etiqueta_label'] = 'Importa';
        $adm_acciones_basicas[1]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[1]['titulo'] = 'Importa';
        $adm_acciones_basicas[1]['css'] = 'info';
        $adm_acciones_basicas[1]['es_status'] = 'inactivo';
        $adm_acciones_basicas[1]['alias'] = 'importa';
        $adm_acciones_basicas[1]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[1]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[1]['muestra_titulo_btn'] = 'inactivo';

        $adm_acciones_basicas[2]['descripcion'] = 'importa_previo';
        $adm_acciones_basicas[2]['visible'] = 'inactivo';
        $adm_acciones_basicas[2]['seguridad'] = 'activo';
        $adm_acciones_basicas[2]['inicio'] = 'inactivo';
        $adm_acciones_basicas[2]['lista'] = 'inactivo';
        $adm_acciones_basicas[2]['status'] = 'activo';
        $adm_acciones_basicas[2]['es_view'] = 'activo';
        $adm_acciones_basicas[2]['codigo'] = 'importa_previo';
        $adm_acciones_basicas[2]['codigo_bis'] = 'importa_previo';
        $adm_acciones_basicas[2]['descripcion_select'] = 'importa_previo';
        $adm_acciones_basicas[2]['etiqueta_label'] = 'importa_previo';
        $adm_acciones_basicas[2]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[2]['titulo'] = 'importa_previo';
        $adm_acciones_basicas[2]['css'] = 'info';
        $adm_acciones_basicas[2]['es_status'] = 'inactivo';
        $adm_acciones_basicas[2]['alias'] = 'importa_previo';
        $adm_acciones_basicas[2]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[2]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[2]['muestra_titulo_btn'] = 'inactivo';


        $adm_acciones_basicas[3]['descripcion'] = 'importa_previo_muestra';
        $adm_acciones_basicas[3]['visible'] = 'inactivo';
        $adm_acciones_basicas[3]['seguridad'] = 'activo';
        $adm_acciones_basicas[3]['inicio'] = 'inactivo';
        $adm_acciones_basicas[3]['lista'] = 'inactivo';
        $adm_acciones_basicas[3]['status'] = 'activo';
        $adm_acciones_basicas[3]['es_view'] = 'activo';
        $adm_acciones_basicas[3]['codigo'] = 'importa_previo_muestra';
        $adm_acciones_basicas[3]['codigo_bis'] = 'importa_previo_muestra';
        $adm_acciones_basicas[3]['descripcion_select'] = 'importa_previo_muestra';
        $adm_acciones_basicas[3]['etiqueta_label'] = 'importa_previo_muestra';
        $adm_acciones_basicas[3]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[3]['titulo'] = 'importa_previo_muestra';
        $adm_acciones_basicas[3]['css'] = 'info';
        $adm_acciones_basicas[3]['es_status'] = 'inactivo';
        $adm_acciones_basicas[3]['alias'] = 'importa_previo_muestra';
        $adm_acciones_basicas[3]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[3]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[3]['muestra_titulo_btn'] = 'inactivo';


        $adm_acciones_basicas[4]['descripcion'] = 'importa_previo_muestra_bd';
        $adm_acciones_basicas[4]['visible'] = 'inactivo';
        $adm_acciones_basicas[4]['seguridad'] = 'activo';
        $adm_acciones_basicas[4]['inicio'] = 'inactivo';
        $adm_acciones_basicas[4]['lista'] = 'inactivo';
        $adm_acciones_basicas[4]['status'] = 'activo';
        $adm_acciones_basicas[4]['es_view'] = 'inactivo';
        $adm_acciones_basicas[4]['codigo'] = 'importa_previo_muestra_bd';
        $adm_acciones_basicas[4]['codigo_bis'] = 'importa_previo_muestra_bd';
        $adm_acciones_basicas[4]['descripcion_select'] = 'importa_previo_muestra_bd';
        $adm_acciones_basicas[4]['etiqueta_label'] = 'importa_previo_muestra_bd';
        $adm_acciones_basicas[4]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[4]['titulo'] = 'importa_previo_muestra_bd';
        $adm_acciones_basicas[4]['css'] = 'info';
        $adm_acciones_basicas[4]['es_status'] = 'inactivo';
        $adm_acciones_basicas[4]['alias'] = 'importa_previo_muestra_bd';
        $adm_acciones_basicas[4]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[4]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[4]['muestra_titulo_btn'] = 'inactivo';


        $adm_acciones_basicas[5]['descripcion'] = 'importa_result';
        $adm_acciones_basicas[5]['visible'] = 'inactivo';
        $adm_acciones_basicas[5]['seguridad'] = 'activo';
        $adm_acciones_basicas[5]['inicio'] = 'inactivo';
        $adm_acciones_basicas[5]['lista'] = 'inactivo';
        $adm_acciones_basicas[5]['status'] = 'activo';
        $adm_acciones_basicas[5]['es_view'] = 'activo';
        $adm_acciones_basicas[5]['codigo'] = 'importa_result';
        $adm_acciones_basicas[5]['codigo_bis'] = 'importa_result';
        $adm_acciones_basicas[5]['descripcion_select'] = 'importa_result';
        $adm_acciones_basicas[5]['etiqueta_label'] = 'importa_result';
        $adm_acciones_basicas[5]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[5]['titulo'] = 'importa_result';
        $adm_acciones_basicas[5]['css'] = 'info';
        $adm_acciones_basicas[5]['es_status'] = 'inactivo';
        $adm_acciones_basicas[5]['alias'] = 'importa_result';
        $adm_acciones_basicas[5]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[5]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[5]['muestra_titulo_btn'] = 'inactivo';


        $adm_acciones_basicas[6]['descripcion'] = 'descarga_layout';
        $adm_acciones_basicas[6]['visible'] = 'activo';
        $adm_acciones_basicas[6]['seguridad'] = 'activo';
        $adm_acciones_basicas[6]['inicio'] = 'inactivo';
        $adm_acciones_basicas[6]['lista'] = 'inactivo';
        $adm_acciones_basicas[6]['status'] = 'activo';
        $adm_acciones_basicas[6]['es_view'] = 'inactivo';
        $adm_acciones_basicas[6]['codigo'] = 'descarga_layout';
        $adm_acciones_basicas[6]['codigo_bis'] = 'descarga_layout';
        $adm_acciones_basicas[6]['descripcion_select'] = 'descarga_layout';
        $adm_acciones_basicas[6]['etiqueta_label'] = 'descarga_layout';
        $adm_acciones_basicas[6]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[6]['titulo'] = 'descarga_layout';
        $adm_acciones_basicas[6]['css'] = 'info';
        $adm_acciones_basicas[6]['es_status'] = 'inactivo';
        $adm_acciones_basicas[6]['alias'] = 'descarga_layout';
        $adm_acciones_basicas[6]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[6]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[6]['muestra_titulo_btn'] = 'inactivo';


        $adm_acciones_basicas[7]['descripcion'] = 'lista';
        $adm_acciones_basicas[7]['visible'] = 'activo';
        $adm_acciones_basicas[7]['seguridad'] = 'activo';
        $adm_acciones_basicas[7]['inicio'] = 'inactivo';
        $adm_acciones_basicas[7]['lista'] = 'inactivo';
        $adm_acciones_basicas[7]['status'] = 'activo';
        $adm_acciones_basicas[7]['es_view'] = 'activo';
        $adm_acciones_basicas[7]['codigo'] = 'lista';
        $adm_acciones_basicas[7]['codigo_bis'] = 'lista';
        $adm_acciones_basicas[7]['descripcion_select'] = 'lista';
        $adm_acciones_basicas[7]['etiqueta_label'] = 'lista';
        $adm_acciones_basicas[7]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[7]['titulo'] = 'lista';
        $adm_acciones_basicas[7]['css'] = 'info';
        $adm_acciones_basicas[7]['es_status'] = 'inactivo';
        $adm_acciones_basicas[7]['alias'] = 'lista';
        $adm_acciones_basicas[7]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[7]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[7]['muestra_titulo_btn'] = 'inactivo';

        $adm_acciones_basicas[8]['descripcion'] = 'modifica';
        $adm_acciones_basicas[8]['visible'] = 'inactivo';
        $adm_acciones_basicas[8]['seguridad'] = 'activo';
        $adm_acciones_basicas[8]['inicio'] = 'inactivo';
        $adm_acciones_basicas[8]['lista'] = 'activo';
        $adm_acciones_basicas[8]['status'] = 'activo';
        $adm_acciones_basicas[8]['es_view'] = 'activo';
        $adm_acciones_basicas[8]['codigo'] = 'modifica';
        $adm_acciones_basicas[8]['codigo_bis'] = 'modifica';
        $adm_acciones_basicas[8]['descripcion_select'] = 'modifica';
        $adm_acciones_basicas[8]['etiqueta_label'] = 'Modifica';
        $adm_acciones_basicas[8]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[8]['titulo'] = 'Modifica';
        $adm_acciones_basicas[8]['css'] = 'warning';
        $adm_acciones_basicas[8]['es_status'] = 'inactivo';
        $adm_acciones_basicas[8]['alias'] = 'modifica';
        $adm_acciones_basicas[8]['es_lista'] = 'activo';
        $adm_acciones_basicas[8]['muestra_icono_btn'] = 'activo';
        $adm_acciones_basicas[8]['muestra_titulo_btn'] = 'inactivo';
        $adm_acciones_basicas[8]['icono'] = 'bi bi-pencil';

        $adm_acciones_basicas[9]['descripcion'] = 'modifica_bd';
        $adm_acciones_basicas[9]['visible'] = 'inactivo';
        $adm_acciones_basicas[9]['seguridad'] = 'activo';
        $adm_acciones_basicas[9]['inicio'] = 'inactivo';
        $adm_acciones_basicas[9]['lista'] = 'inactivo';
        $adm_acciones_basicas[9]['status'] = 'activo';
        $adm_acciones_basicas[9]['es_view'] = 'inactivo';
        $adm_acciones_basicas[9]['codigo'] = 'modifica_bd';
        $adm_acciones_basicas[9]['codigo_bis'] = 'modifica_bd';
        $adm_acciones_basicas[9]['descripcion_select'] = 'modifica_bd';
        $adm_acciones_basicas[9]['etiqueta_label'] = 'Modifica';
        $adm_acciones_basicas[9]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[9]['titulo'] = 'Modifica';
        $adm_acciones_basicas[9]['css'] = 'warning';
        $adm_acciones_basicas[9]['es_status'] = 'inactivo';
        $adm_acciones_basicas[9]['alias'] = 'modifica';
        $adm_acciones_basicas[9]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[9]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[9]['muestra_titulo_btn'] = 'inactivo';
        $adm_acciones_basicas[9]['icono'] = 'bi bi-pencil';

        $adm_acciones_basicas[10]['descripcion'] = 'elimina_bd';
        $adm_acciones_basicas[10]['visible'] = 'inactivo';
        $adm_acciones_basicas[10]['seguridad'] = 'activo';
        $adm_acciones_basicas[10]['inicio'] = 'inactivo';
        $adm_acciones_basicas[10]['lista'] = 'activo';
        $adm_acciones_basicas[10]['status'] = 'activo';
        $adm_acciones_basicas[10]['es_view'] = 'inactivo';
        $adm_acciones_basicas[10]['codigo'] = 'elimina_bd';
        $adm_acciones_basicas[10]['codigo_bis'] = 'elimina_bd';
        $adm_acciones_basicas[10]['descripcion_select'] = 'elimina_bd';
        $adm_acciones_basicas[10]['etiqueta_label'] = 'Elimina';
        $adm_acciones_basicas[10]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[10]['titulo'] = 'Elimina';
        $adm_acciones_basicas[10]['css'] = 'danger';
        $adm_acciones_basicas[10]['es_status'] = 'inactivo';
        $adm_acciones_basicas[10]['alias'] = 'elimina';
        $adm_acciones_basicas[10]['es_lista'] = 'activo';
        $adm_acciones_basicas[10]['muestra_icono_btn'] = 'activo';
        $adm_acciones_basicas[10]['muestra_titulo_btn'] = 'inactivo';
        $adm_acciones_basicas[10]['icono'] = 'bi bi-trash';
        $adm_acciones_basicas[10]['id_css'] = 'elimina_bd';

        $adm_acciones_basicas[11]['descripcion'] = 'status';
        $adm_acciones_basicas[11]['visible'] = 'inactivo';
        $adm_acciones_basicas[11]['seguridad'] = 'activo';
        $adm_acciones_basicas[11]['inicio'] = 'inactivo';
        $adm_acciones_basicas[11]['lista'] = 'activo';
        $adm_acciones_basicas[11]['status'] = 'activo';
        $adm_acciones_basicas[11]['es_view'] = 'inactivo';
        $adm_acciones_basicas[11]['codigo'] = 'status';
        $adm_acciones_basicas[11]['codigo_bis'] = 'status';
        $adm_acciones_basicas[11]['descripcion_select'] = 'status';
        $adm_acciones_basicas[11]['etiqueta_label'] = 'Status';
        $adm_acciones_basicas[11]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[11]['titulo'] = 'Cambia Status';
        $adm_acciones_basicas[11]['css'] = 'danger';
        $adm_acciones_basicas[11]['es_status'] = 'activo';
        $adm_acciones_basicas[11]['alias'] = 'status';
        $adm_acciones_basicas[11]['es_lista'] = 'activo';
        $adm_acciones_basicas[11]['muestra_icono_btn'] = 'activo';
        $adm_acciones_basicas[11]['muestra_titulo_btn'] = 'inactivo';
        $adm_acciones_basicas[11]['icono'] = 'bi bi-plus-slash-minus';

        $adm_acciones_basicas[12]['descripcion'] = 'alta';
        $adm_acciones_basicas[12]['visible'] = 'activo';
        $adm_acciones_basicas[12]['seguridad'] = 'activo';
        $adm_acciones_basicas[12]['inicio'] = 'inactivo';
        $adm_acciones_basicas[12]['lista'] = 'inactivo';
        $adm_acciones_basicas[12]['status'] = 'activo';
        $adm_acciones_basicas[12]['es_view'] = 'activo';
        $adm_acciones_basicas[12]['codigo'] = 'alta';
        $adm_acciones_basicas[12]['codigo_bis'] = 'alta';
        $adm_acciones_basicas[12]['descripcion_select'] = 'alta';
        $adm_acciones_basicas[12]['etiqueta_label'] = 'Alta';
        $adm_acciones_basicas[12]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[12]['titulo'] = 'Alta';
        $adm_acciones_basicas[12]['css'] = 'warning';
        $adm_acciones_basicas[12]['es_status'] = 'inactivo';
        $adm_acciones_basicas[12]['alias'] = 'alta';
        $adm_acciones_basicas[12]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[12]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[12]['muestra_titulo_btn'] = 'inactivo';
        $adm_acciones_basicas[12]['icono'] = 'bi bi-pencil';

        $adm_acciones_basicas[13]['descripcion'] = 'alta_bd';
        $adm_acciones_basicas[13]['visible'] = 'inactivo';
        $adm_acciones_basicas[13]['seguridad'] = 'activo';
        $adm_acciones_basicas[13]['inicio'] = 'inactivo';
        $adm_acciones_basicas[13]['lista'] = 'inactivo';
        $adm_acciones_basicas[13]['status'] = 'activo';
        $adm_acciones_basicas[13]['es_view'] = 'inactivo';
        $adm_acciones_basicas[13]['codigo'] = 'alta_bd';
        $adm_acciones_basicas[13]['codigo_bis'] = 'alta_bd';
        $adm_acciones_basicas[13]['descripcion_select'] = 'alta_bd';
        $adm_acciones_basicas[13]['etiqueta_label'] = 'Alta';
        $adm_acciones_basicas[13]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[13]['titulo'] = 'Alta';
        $adm_acciones_basicas[13]['css'] = 'warning';
        $adm_acciones_basicas[13]['es_status'] = 'inactivo';
        $adm_acciones_basicas[13]['alias'] = 'modifica';
        $adm_acciones_basicas[13]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[13]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[13]['muestra_titulo_btn'] = 'inactivo';
        $adm_acciones_basicas[13]['icono'] = 'bi bi-pencil';

        $adm_acciones_basicas[14]['descripcion'] = 'get_data';
        $adm_acciones_basicas[14]['visible'] = 'inactivo';
        $adm_acciones_basicas[14]['seguridad'] = 'activo';
        $adm_acciones_basicas[14]['inicio'] = 'inactivo';
        $adm_acciones_basicas[14]['lista'] = 'inactivo';
        $adm_acciones_basicas[14]['status'] = 'activo';
        $adm_acciones_basicas[14]['es_view'] = 'inactivo';
        $adm_acciones_basicas[14]['codigo'] = 'get_data';
        $adm_acciones_basicas[14]['codigo_bis'] = 'get_data';
        $adm_acciones_basicas[14]['descripcion_select'] = 'get_data';
        $adm_acciones_basicas[14]['etiqueta_label'] = 'get_data';
        $adm_acciones_basicas[14]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[14]['titulo'] = 'Get Data';
        $adm_acciones_basicas[14]['css'] = 'warning';
        $adm_acciones_basicas[14]['es_status'] = 'inactivo';
        $adm_acciones_basicas[14]['alias'] = 'get_data';
        $adm_acciones_basicas[14]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[14]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[14]['muestra_titulo_btn'] = 'inactivo';
        $adm_acciones_basicas[14]['icono'] = 'bi bi-pencil';


        $adm_acciones_basicas[15]['descripcion'] = 'descarga_excel';
        $adm_acciones_basicas[15]['visible'] = 'activo';
        $adm_acciones_basicas[15]['seguridad'] = 'activo';
        $adm_acciones_basicas[15]['inicio'] = 'inactivo';
        $adm_acciones_basicas[15]['lista'] = 'inactivo';
        $adm_acciones_basicas[15]['status'] = 'activo';
        $adm_acciones_basicas[15]['es_view'] = 'inactivo';
        $adm_acciones_basicas[15]['codigo'] = 'descarga_excel';
        $adm_acciones_basicas[15]['codigo_bis'] = 'descarga_excel';
        $adm_acciones_basicas[15]['descripcion_select'] = 'descarga_excel';
        $adm_acciones_basicas[15]['etiqueta_label'] = 'descarga_excel';
        $adm_acciones_basicas[15]['es_modal'] = 'inactivo';
        $adm_acciones_basicas[15]['titulo'] = 'Descarga XLS';
        $adm_acciones_basicas[15]['css'] = 'success';
        $adm_acciones_basicas[15]['es_status'] = 'inactivo';
        $adm_acciones_basicas[15]['alias'] = 'descarga_excel';
        $adm_acciones_basicas[15]['es_lista'] = 'inactivo';
        $adm_acciones_basicas[15]['muestra_icono_btn'] = 'inactivo';
        $adm_acciones_basicas[15]['muestra_titulo_btn'] = 'inactivo';
        $adm_acciones_basicas[15]['icono'] = 'bi bi-pencil';

        $altas = array();
        foreach ($adm_acciones_basicas as $adm_accion_basica){
            $con_descripcion['adm_accion_basica.descripcion'] = $adm_accion_basica['descripcion'];
            $alta = (new adm_accion_basica(link: $link))->inserta_registro_si_no_existe(registro: $adm_accion_basica,
                con_descripcion: $con_descripcion);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar accion_basica',data:  $alta);
            }
            $altas[] = $alta;
        }

        $out->altas = $altas;

        return $out;



    }

    private function adm_atributo(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_atributo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $adm_menu_descripcion = 'Control';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Atributos';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;

    }
    private function adm_campo(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_campo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        return $create;

    }

    private function adm_menu(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_menu(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $create_seccion = $this->_add_adm_seccion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create_seccion', data:  $create_seccion);
        }


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Menus';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $create;

    }

    private function adm_namespace(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_namespace(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $create_menu = $this->_add_adm_menu(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create_menu', data:  $create_menu);
        }
        $create_sistema = $this->_add_adm_sistema(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create_sistema', data:  $create_sistema);
        }
        $create_grupo = $this->_add_adm_grupo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create_grupo', data:  $create_grupo);
        }

        $create_seccion = $this->_add_adm_seccion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create_seccion', data:  $create_seccion);
        }

        $create_accion = $this->_add_adm_accion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create_accion', data:  $create_accion);
        }

        $create_seccion_pertenece = $this->_add_adm_seccion_pertenece(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create_seccion_pertenece', data:  $create_seccion_pertenece);
        }

        $create = $this->_add_adm_accion_grupo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Namespaces';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $create;

    }

    private function adm_grupo(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_grupo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Grupos de Usuario';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        $adm_grupo_ins = array();
        $adm_grupo_ins['id'] = 2;
        $adm_grupo_ins['descripcion'] = 'Administrador Sistema';
        $adm_grupo_ins['status'] = 'activo';
        $adm_grupo_ins['root'] = 'activo';

        $r_adm_grupo = (new adm_grupo(link: $link))->inserta_registro_si_no_existe(registro: $adm_grupo_ins);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar grupo', data:  $r_adm_grupo);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'asigna_permiso',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'activo', icono: 'bi bi-database-lock',
            link:  $link, lista:  'activo',titulo:  'Asigna Permiso');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'asigna_permiso_seccion',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'activo', icono: 'bi bi-file-earmark-lock2',
            link:  $link, lista:  'activo',titulo:  'Asigna Permiso Por Seccion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'usuarios',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'activo', icono: 'bi bi-file-earmark-person-fill',
            link:  $link, lista:  'activo',titulo:  'Usuarios');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'asigna_permiso_seccion_bd',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-file-earmark-lock2',
            link:  $link, lista:  'inactivo',titulo:  'Asigna Permiso Por Seccion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'root',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-database-fill-up',
            link:  $link, lista:  'activo',titulo:  'Root',es_status: 'activo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'solo_mi_info',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-database-lock',
            link:  $link, lista:  'activo',titulo:  'Ver Solo mi Info',es_status: 'activo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }


        return $create;

    }

    private function adm_sistema(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_sistema(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Sistemas';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }



        return $create;

    }

    private function adm_usuario(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Usuarios';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        if(!isset((new generales())->adm_usuario_user_init)){
            return (new errores())->error(mensaje: 'Error integre el atributo adm_usuario_user_init en generales',
                data:  '', es_final: true);
        }
        if(!isset((new generales())->adm_usuario_password_init)){
            return (new errores())->error(mensaje: 'Error integre el atributo adm_usuario_password_init en generales',
                data:  '', es_final: true);
        }

        $adm_usuario_user_init = trim((new generales())->adm_usuario_user_init);
        $adm_usuario_password_init = trim((new generales())->adm_usuario_password_init);

        if($adm_usuario_user_init === ''){
            return (new errores())->error(
                mensaje: 'Error esta vacio el atributo adm_usuario_password_init en generales', data:  '',
                es_final: true);
        }
        if($adm_usuario_password_init === ''){
            return (new errores())->error(
                mensaje: 'Error esta vacio el atributo adm_usuario_password_init en generales', data:  '',
                es_final: true);
        }

        $adm_usuario_ins = array();
        $adm_usuario_ins['id'] = 2;
        $adm_usuario_ins['user'] = (new generales())->adm_usuario_user_init;
        $adm_usuario_ins['password'] = (new generales())->adm_usuario_password_init;
        $adm_usuario_ins['email'] = 'sinmail@mail.com';
        $adm_usuario_ins['adm_grupo_id'] = 2;
        $adm_usuario_ins['status'] = 'activo';
        $adm_usuario_ins['telefono'] = '3333333333';
        $adm_usuario_ins['nombre'] = 'admin';
        $adm_usuario_ins['ap'] = 'admin';
        $adm_usuario_ins['am'] = 'admin';
        $adm_usuario_ins['codigo'] = '2';

        $r_adm_usuario = (new adm_usuario(link: $link))->inserta_registro_si_no_existe(registro: $adm_usuario_ins);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar r_adm_usuario', data:  $r_adm_usuario);
        }


        return $create;

    }

    private function adm_bitacora(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_bitacora(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $adm_menu_descripcion = 'Logs';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Bitacora';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $create;

    }

    private function adm_reporte(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_reporte(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function adm_categoria_secciones(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_categoria(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $create = $this->_add_adm_categoria_sistema(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $create = $this->_add_adm_categoria_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $create = $this->_add_adm_categoria_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $create = $this->_add_adm_categoria_secciones(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }
    private function adm_categoria_usuario(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_categoria_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function adm_categoria_sistema(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_categoria_sistema(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function adm_categoria(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_categoria(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }


    private function _add_adm_session(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'adm_session');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $campos = new stdClass();
        $campos->name = new stdClass();
        $campos->numero_empresa = new stdClass();
        $campos->fecha = new stdClass();
        $campos->fecha->tipo_dato = 'DATE';

        $campos->fecha_ultima_ejecucion = new stdClass();
        $campos->fecha->fecha_ultima_ejecucion = 'TIMESTAMP';
        $campos->ip_publica = new stdClass();
        $campos->permanente = new stdClass();
        $campos->permanente->default = 'inactivo';



        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'adm_session');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }

        $foraneas = array();
        $foraneas['adm_usuario_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'adm_session');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foraneas_r', data:  $foraneas_r);
        }

        return $out;

    }
    private function adm_session(PDO $link): array|stdClass
    {

        $create = $this->_add_adm_session(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Sessiones';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'login',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'activo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'inactivo',titulo:  'login', es_status: 'inactivo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'loguea',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'inactivo',titulo:  'loguea', es_status: 'inactivo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'denegado',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'activo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'inactivo',titulo:  'denegado', es_status: 'inactivo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'inicio',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'activo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'inactivo',titulo:  'inicio', es_status: 'inactivo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'logout',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'inactivo',titulo:  'logout', es_status: 'inactivo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        return $acl;

    }

    private function adm_seccion(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_seccion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $create_accion_grupo = $this->_add_adm_accion_grupo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create_accion_grupo', data:  $create_accion_grupo);
        }


        $adm_secciones = (new adm_seccion(link: $link))->registros();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener adm_secciones', data:  $adm_secciones);
        }


        $r_acciones = $this->integra_accion_basica(accion_basica_descripcion: 'importa', adm_secciones: $adm_secciones,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_acciones);
        }
        $r_acciones = $this->integra_accion_basica(accion_basica_descripcion: 'importa_previo', adm_secciones: $adm_secciones,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_acciones);
        }
        $r_acciones = $this->integra_accion_basica(accion_basica_descripcion: 'importa_previo_muestra', adm_secciones: $adm_secciones,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_acciones);
        }
        $r_acciones = $this->integra_accion_basica(accion_basica_descripcion: 'importa_previo_muestra_bd', adm_secciones: $adm_secciones,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_acciones);
        }
        $r_acciones = $this->integra_accion_basica(accion_basica_descripcion: 'importa_result', adm_secciones: $adm_secciones,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_acciones);
        }

        $r_acciones = $this->integra_accion_basica(accion_basica_descripcion: 'descarga_layout', adm_secciones: $adm_secciones,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_acciones);
        }




        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Secciones';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        $inserta_campos = (new _instalacion(link: $link))->inserta_adm_campos(
            modelo_integracion: (new adm_seccion(link: $link)));
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar adm campos', data:  $inserta_campos);
        }


        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'get_adm_seccion',
            adm_seccion_descripcion:  __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-arrow-up-short',
            link:  $link, lista:  'inactivo',titulo:  'Get Adm Seccion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }



        return $create;


    }

    private function adm_seccion_pertenece(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_seccion_pertenece(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Secciones Sistema';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $create;

    }

    private function adm_accion_grupo(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_accion_grupo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'administrador';
        $etiqueta_label = 'Permisos';
        $adm_seccion_pertenece_descripcion = 'administrador';
        $adm_namespace_descripcion = 'gamboa.martin/administrador';
        $adm_namespace_name = 'gamboamartin/administrador';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $create;

    }

    private function adm_tipo_dato(PDO $link): array|stdClass
    {
        $create = $this->_add_adm_tipo_dato(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_tipo_dato_modelo = new adm_tipo_dato(link: $link);

        $adm_tipos_datos = array();

        $adm_tipo_dato['id'] = 1;
        $adm_tipo_dato['descripcion'] = 'INT';
        $adm_tipo_dato['codigo'] = 'INT';

        $adm_tipos_datos[] = $adm_tipo_dato;

        $adm_tipo_dato['id'] = 2;
        $adm_tipo_dato['descripcion'] = 'BIGINT';
        $adm_tipo_dato['codigo'] = 'BIGINT';

        $adm_tipos_datos[] = $adm_tipo_dato;

        $adm_tipo_dato['id'] = 3;
        $adm_tipo_dato['descripcion'] = 'VARCHAR';
        $adm_tipo_dato['codigo'] = 'VARCHAR';

        $adm_tipos_datos[] = $adm_tipo_dato;

        $adm_tipo_dato['id'] = 4;
        $adm_tipo_dato['descripcion'] = 'TEXT';
        $adm_tipo_dato['codigo'] = 'TEXT';

        $adm_tipos_datos[] = $adm_tipo_dato;

        $adm_tipo_dato['id'] = 5;
        $adm_tipo_dato['descripcion'] = 'TIMESTAMP';
        $adm_tipo_dato['codigo'] = 'TIMESTAMP';


        $adm_tipos_datos[] = $adm_tipo_dato;

        $adm_tipo_dato['id'] = 6;
        $adm_tipo_dato['descripcion'] = 'DOUBLE';
        $adm_tipo_dato['codigo'] = 'DOUBLE';


        $adm_tipos_datos[] = $adm_tipo_dato;

        $adm_tipo_dato['id'] = 7;
        $adm_tipo_dato['descripcion'] = 'FLOAT';
        $adm_tipo_dato['codigo'] = 'FLOAT';


        $adm_tipos_datos[] = $adm_tipo_dato;

        $adm_tipo_dato['id'] = 8;
        $adm_tipo_dato['descripcion'] = 'DATE';
        $adm_tipo_dato['codigo'] = 'DATE';


        $adm_tipos_datos[] = $adm_tipo_dato;

        $adm_tipo_dato['id'] = 9;
        $adm_tipo_dato['descripcion'] = 'DATETIME';
        $adm_tipo_dato['codigo'] = 'DATETIME';


        $adm_tipos_datos[] = $adm_tipo_dato;


        foreach ($adm_tipos_datos as $adm_tipo_dato){

            $existe = $adm_tipo_dato_modelo->existe_by_id(registro_id: $adm_tipo_dato['id']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar adm_tipo_dato', data:  $existe);
            }

            if($existe){
                $upd = $adm_tipo_dato_modelo->modifica_bd(registro: $adm_tipo_dato,id:  $adm_tipo_dato['id']);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al actualizar adm_tipo_dato', data:  $upd);
                }

            }

        }


        foreach ($adm_tipos_datos as $adm_tipo_dato){
            $inserta = $adm_tipo_dato_modelo->inserta_registro_si_no_existe(registro: $adm_tipo_dato);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar adm_tipo_dato', data:  $inserta);
            }
        }

        foreach ($adm_tipos_datos as $adm_tipo_dato){
            $existe = $adm_tipo_dato_modelo->existe_by_id(registro_id: $adm_tipo_dato['id']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al verificar si existe', data:  $existe);
            }
            if($existe){
                $filtro = array();
                $filtro['adm_tipo_dato.id'] = $adm_tipo_dato['id'];
                $filtro['adm_tipo_dato.descripcion'] = $adm_tipo_dato['descripcion'];
                $filtro['adm_tipo_dato.codigo'] = $adm_tipo_dato['codigo'];

                $existe_fil = $adm_tipo_dato_modelo->existe(filtro: $filtro);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al verificar si existe', data:  $existe_fil);
                }

                if(!$existe_fil){
                    $upd = $adm_tipo_dato_modelo->modifica_bd_base(registro: $adm_tipo_dato, id: $adm_tipo_dato['id']);
                    if(errores::$error){
                        return (new errores())->error(mensaje: 'Error al actualizar', data:  $upd);
                    }
                }

            }
        }

        return $create;

    }

    private function existe_accion(string $accion, array $adm_seccion, PDO $link)
    {
        $seccion = $adm_seccion['adm_seccion_descripcion'];
        $existe_accion = (new adm_accion(link: $link))->existe_accion(adm_accion: $accion,adm_seccion:  $seccion);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar accion', data:  $existe_accion);
        }
        return $existe_accion;

    }

    private function inserta_accion(array $accion_basica_importa, array $adm_seccion, PDO $link): array|stdClass
    {
        $accion_ins = $accion_basica_importa;
        $accion_ins['adm_seccion_id'] = $adm_seccion['adm_seccion_id'];
        $r_accion = (new adm_accion(link: $link))->alta_registro(registro: $accion_ins);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_accion);
        }
        return $r_accion;

    }

    private function inserta_accion_base(string $accion, array $accion_basica_importa, array $adm_seccion, PDO $link): array|stdClass
    {
        $r_accion = new stdClass();
        $existe_accion = $this->existe_accion(accion: $accion,adm_seccion:  $adm_seccion, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar accion', data:  $existe_accion);
        }
        if(!$existe_accion){
            $r_accion = $this->inserta_accion(accion_basica_importa: $accion_basica_importa,adm_seccion:  $adm_seccion,link:  $link);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_accion);
            }
        }
        return $r_accion;

    }
    final public function instala(PDO $link): array|stdClass
    {

        $out = new stdClass();

        $adm_namespace = $this->adm_namespace(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_namespace', data: $adm_namespace);
        }
        $out->adm_namespace = $adm_namespace;

        $adm_grupo = $this->adm_grupo(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_grupo', data: $adm_grupo);
        }
        $out->adm_grupo = $adm_grupo;

        $adm_sistema = $this->adm_sistema(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_sistema', data: $adm_sistema);
        }
        $out->adm_sistema = $adm_sistema;


        $adm_usuario = $this->adm_usuario(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_usuario', data: $adm_usuario);
        }
        $out->adm_grupo = $adm_grupo;


        $adm_menu = $this->adm_menu(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_menu', data: $adm_menu);
        }
        $out->adm_menu = $adm_menu;

        $adm_tipo_dato = $this->adm_tipo_dato(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_tipo_dato', data: $adm_tipo_dato);
        }
        $out->adm_tipo_dato = $adm_tipo_dato;

        $adm_campo = $this->adm_campo(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_campo', data: $adm_campo);
        }
        $out->adm_campo = $adm_campo;


        $adm_accion_basica = $this->adm_accion_basica(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_accion_basica', data: $adm_accion_basica);
        }
        $out->adm_accion_basica = $adm_accion_basica;

        $adm_seccion = $this->adm_seccion(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_seccion', data: $adm_seccion);
        }
        $out->adm_seccion = $adm_seccion;


        $adm_seccion_pertenece = $this->adm_seccion_pertenece(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_seccion_pertenece', data: $adm_seccion_pertenece);
        }
        $out->adm_seccion_pertenece = $adm_seccion_pertenece;

        $adm_atributo = $this->adm_atributo(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_atributo', data: $adm_atributo);
        }
        $out->adm_atributo = $adm_atributo;


        $adm_bitacora = $this->adm_bitacora(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_bitacora', data: $adm_bitacora);
        }
        $out->adm_bitacora = $adm_bitacora;


        $adm_accion = $this->adm_accion(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_accion', data: $adm_accion);
        }
        $out->adm_accion = $adm_accion;

        $adm_accion_grupo = $this->adm_accion_grupo(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_accion_grupo', data: $adm_accion_grupo);
        }
        $out->adm_accion = $adm_accion;

        $adm_reporte = $this->adm_reporte(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_reporte', data: $adm_reporte);
        }
        $out->adm_reporte = $adm_reporte;

        $adm_session = $this->adm_session(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_session', data: $adm_session);
        }
        $out->adm_session = $adm_session;

        $adm_categoria_secciones = $this->adm_categoria_secciones(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_categoria_secciones', data: $adm_categoria_secciones);
        }
        $out->adm_session = $adm_session;

        $adm_categoria_usuario = $this->adm_categoria_usuario(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_categoria_usuario', data: $adm_categoria_usuario);
        }
        $out->adm_categoria_usuario = $adm_categoria_usuario;

        $adm_categoria_sistema = $this->adm_categoria_sistema(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_categoria_sistema', data: $adm_categoria_sistema);
        }
        $out->adm_categoria_sistema = $adm_categoria_sistema;

        $adm_categoria = $this->adm_categoria(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al init adm_categoria', data: $adm_categoria);
        }
        $out->adm_categoria = $adm_categoria;


        return $out;

    }

    private function integra_accion_basica(string $accion_basica_descripcion, array $adm_secciones, PDO $link): array
    {

        $acciones = array();
        $accion_basica_importa = $this->accion_basica_importa(accion_basica_descripcion: $accion_basica_descripcion,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener accion_basica_importa',
                data:  $accion_basica_importa);
        }

        foreach ($adm_secciones as $adm_seccion){

            $r_accion = $this->inserta_accion_base(accion: $accion_basica_descripcion,
                accion_basica_importa:  $accion_basica_importa,adm_seccion:  $adm_seccion,link:  $link);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar accion', data:  $r_accion);
            }
            $acciones[] = $r_accion;

        }

        return $acciones;

    }

}
