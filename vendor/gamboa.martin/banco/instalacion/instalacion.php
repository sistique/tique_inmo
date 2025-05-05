<?php
namespace gamboamartin\banco\instalacion;

use base\orm\modelo;
use base\orm\modelo_base;
use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{

    private function _add_bn_banco(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_banco');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['bn_tipo_banco_id'] = new stdClass();

        $result = $init->foraneas(foraneas: $foraneas,table:  'bn_banco');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }

        $out->foraneas = $result;
        
        return $out;
    }

    private function _add_bn_cuenta(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_cuenta');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['bn_tipo_cuenta_id'] = new stdClass();
        $foraneas['bn_sucursal_id'] = new stdClass();
        $foraneas['org_sucursal_id'] = new stdClass();

        $result = $init->foraneas(foraneas: $foraneas,table:  'bn_cuenta');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }

        $out->foraneas = $result;

        return $out;
    }

    private function _add_bn_detalle_layout(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_detalle_layout');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['bn_layout_id'] = new stdClass();

        $result = $init->foraneas(foraneas: $foraneas,table:  'bn_detalle_layout');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }

        $out->foraneas = $result;

        return $out;
    }


    private function _add_bn_layout(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_layout');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['bn_sucursal_id'] = new stdClass();

        $result = $init->foraneas(foraneas: $foraneas,table:  'bn_layout');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }

        $out->foraneas = $result;

        $campos = new stdClass();
        $campos->fecha_pago = new stdClass();
        $campos->fecha_pago->tipo_dato = 'DATETIME';
        $campos->fecha_pago->default = '1900-01-01';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'bn_layout');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;

        return $out;
    }

    private function _add_bn_empleado(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_empleado');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;
    }

    private function _add_bn_tipo_banco(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_tipo_banco');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;
    }

    private function _add_bn_tipo_cuenta(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_tipo_cuenta');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;
    }

    private function _add_bn_tipo_sucursal(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_tipo_sucursal');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;
    }

    private function _add_bn_sucursal(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = (new _instalacion(link: $link))->create_table_new(table: 'bn_sucursal');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['bn_banco_id'] = new stdClass();
        $foraneas['bn_tipo_sucursal_id'] = new stdClass();

        $result = $init->foraneas(foraneas: $foraneas,table:  'bn_sucursal');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }

        $out->foraneas = $result;

        return $out;
    }

    private function bn_banco(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_banco(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Banco';
        $adm_seccion_pertenece_descripcion = 'banco';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    private function bn_cuenta(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_cuenta(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Cuenta';
        $adm_seccion_pertenece_descripcion = 'cuenta';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    private function bn_detalle_layout(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_detalle_layout(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Detalle Layout';
        $adm_seccion_pertenece_descripcion = 'detalle_layout';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    private function bn_layout(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_layout(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Layout';
        $adm_seccion_pertenece_descripcion = 'layout';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    private function bn_empleado(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_empleado(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Tipo Banco';
        $adm_seccion_pertenece_descripcion = 'empleado';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    private function bn_tipo_banco(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_tipo_banco(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Tipo Banco';
        $adm_seccion_pertenece_descripcion = 'tipo_banco';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    private function bn_tipo_cuenta(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_tipo_cuenta(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Tipo Cuenta';
        $adm_seccion_pertenece_descripcion = 'tipo_cuenta';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    private function bn_tipo_sucursal(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_tipo_sucursal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Tipo Sucursal';
        $adm_seccion_pertenece_descripcion = 'tipo_sucursal';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    private function bn_sucursal(PDO $link): array|stdClass
    {
        $create = $this->_add_bn_sucursal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $adm_menu_descripcion = 'Banco';
        $adm_sistema_descripcion = 'banco';
        $etiqueta_label = 'Sucursal';
        $adm_seccion_pertenece_descripcion = 'sucursal';
        $adm_namespace_name = 'gamboamartin/banco';
        $adm_namespace_descripcion = 'gamboa.martin/banco';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $create;
    }

    final public function instala(PDO $link): array|stdClass
    {
        $result = new stdClass();

        $bn_empleado = $this->bn_empleado(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_empleado', data:  $bn_empleado);
        }
        $result->bn_empleado = $bn_empleado;

        $bn_tipo_banco = $this->bn_tipo_banco(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_tipo_banco', data:  $bn_tipo_banco);
        }
        $result->bn_tipo_banco = $bn_tipo_banco;

        $bn_banco = $this->bn_banco(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_banco', data:  $bn_banco);
        }
        $result->bn_banco = $bn_banco;

        $bn_tipo_sucursal = $this->bn_tipo_sucursal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_tipo_sucursal', data:  $bn_tipo_sucursal);
        }
        $result->bn_tipo_sucursal = $bn_tipo_sucursal;

        $bn_sucursal = $this->bn_sucursal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_sucursal', data:  $bn_sucursal);
        }
        $result->bn_sucursal = $bn_sucursal;

        $bn_tipo_cuenta = $this->bn_tipo_cuenta(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_tipo_cuenta', data:  $bn_tipo_cuenta);
        }
        $result->bn_tipo_cuenta = $bn_tipo_cuenta;

        $bn_cuenta = $this->bn_cuenta(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_cuenta', data:  $bn_cuenta);
        }
        $result->bn_cuenta = $bn_cuenta;

        $bn_layout = $this->bn_layout(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_layout', data:  $bn_layout);
        }
        $result->bn_layout = $bn_layout;

        $bn_detalle_layout = $this->bn_detalle_layout(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar bn_detalle_layout', data:  $bn_detalle_layout);
        }
        $result->bn_detalle_layout = $bn_detalle_layout;

        return $result;

    }

    final public function limpia(PDO $link): array|stdClass
    {

        $out = new stdClass();

        $modelos = array();
        $modelos[] = 'bn_banco';
        $modelos[] = 'bn_cuenta';
        $modelos[] = 'bn_detalle_layout';
        $modelos[] = 'bn_layout';
        $modelos[] = 'bn_tipo_banco';
        $modelos[] = 'bn_tipo_cuenta';
        $modelos[] = 'bn_tipo_sucursal';
        $modelos[] = 'bn_sucursal';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\banco\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }

            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }

            $out->$modelo = $del;

        }

        $modelos = array();
        $modelos[] = 'org_sucursal';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\organigrama\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }
            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }
            $out->$modelo = $del;

        }

        $modelos = array();
        $modelos[] = 'com_email_cte';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\comercial\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }
            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }
            $out->$modelo = $del;

        }

        $modelos = array();
        $modelos[] = 'not_rel_mensaje_etapa';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\notificaciones\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }
            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }
            $out->$modelo = $del;

        }

        $modelos = array();
        $modelos[] = 'doc_version';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\documento\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }
            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }
            $out->$modelo = $del;

        }

        return $out;


    }
}
