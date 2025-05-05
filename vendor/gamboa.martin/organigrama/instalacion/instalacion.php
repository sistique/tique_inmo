<?php
namespace gamboamartin\organigrama\instalacion;

use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion


{

    private function _add_org_clasificacion_dep(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_clasificacion_dep');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;

    }

    private function _add_org_departamento(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_departamento');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['org_clasificacion_dep_id'] = new stdClass();
        $foraneas['org_empresa_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'org_departamento');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        return $out;

    }
    private function _add_org_empresa(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_empresa');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $campos = new stdClass();

        $campos->logo = new stdClass();
        $campos->nombre_comercial = new stdClass();
        $campos->fecha_inicio_operaciones = new stdClass();
        $campos->fecha_inicio_operaciones->tipo_dato = 'DATE';

        $campos->fecha_ultimo_cambio_sat = new stdClass();
        $campos->fecha_ultimo_cambio_sat->tipo_dato = 'DATE';

        $campos->exterior = new stdClass();
        $campos->interior = new stdClass();
        $campos->email_sat = new stdClass();
        $campos->telefono_1 = new stdClass();
        $campos->telefono_2 = new stdClass();
        $campos->telefono_3 = new stdClass();
        $campos->rfc = new stdClass();
        $campos->razon_social = new stdClass();
        $campos->pagina_web = new stdClass();


        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'org_empresa');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        $foraneas = array();
        $foraneas['cat_sat_regimen_fiscal_id'] = new stdClass();
        $foraneas['dp_calle_pertenece_id'] = new stdClass();
        $foraneas['org_tipo_empresa_id'] = new stdClass();
        $foraneas['cat_sat_tipo_persona_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'org_empresa');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    private function _add_org_actividad(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_actividad');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $campos = new stdClass();

        $campos->tiempo = new stdClass();
        $campos->tiempo->tipo_dato = 'BIGINT';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'org_actividad');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        $foraneas = array();
        $foraneas['org_tipo_actividad_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'org_actividad');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    private function _add_org_logo(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_logo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['org_empresa_id'] = new stdClass();
        $foraneas['doc_documento_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'org_logo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    private function _add_org_puesto(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_puesto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['org_departamento_id'] = new stdClass();
        $foraneas['org_tipo_puesto_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'org_puesto');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    private function _add_org_ejecuta(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_ejecuta');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['org_puesto_id'] = new stdClass();
        $foraneas['org_actividad_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'org_ejecuta');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    private function _add_org_sucursal(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_sucursal');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['dp_calle_pertenece_id'] = new stdClass();
        $foraneas['org_empresa_id'] = new stdClass();
        $foraneas['org_tipo_sucursal_id'] = new stdClass();


        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'org_sucursal');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        $campos = new stdClass();

        $campos->fecha_inicio_operaciones = new stdClass();
        $campos->fecha_inicio_operaciones->tipo_dato = 'DATE';
        $campos->exterior = new stdClass();
        $campos->interior = new stdClass();
        $campos->telefono_1 = new stdClass();
        $campos->telefono_2 = new stdClass();
        $campos->telefono_3 = new stdClass();
        $campos->serie = new stdClass();


        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'org_sucursal');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }


        return $out;

    }

    private function _add_org_tipo_empresa(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_tipo_empresa');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;

    }

    private function _add_org_tipo_actividad(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_tipo_actividad');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;

    }

    private function _add_org_tipo_puesto(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_tipo_puesto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;

    }

    private function _add_org_tipo_sucursal(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_tipo_sucursal');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;

    }

    private function org_clasificacion_dep(PDO $link): array|stdClass
    {
        $create = $this->_add_org_clasificacion_dep(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function org_departamento(PDO $link): array|stdClass
    {
        $create = $this->_add_org_departamento(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function org_empresa(PDO $link): array|stdClass
    {
        $create = $this->_add_org_empresa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function org_actividad(PDO $link): array|stdClass
    {
        $create = $this->_add_org_actividad(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function org_logo(PDO $link): array|stdClass
    {
        $create = $this->_add_org_logo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function org_puesto(PDO $link): array|stdClass
    {
        $create = $this->_add_org_puesto(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }
    private function org_ejecuta(PDO $link): array|stdClass
    {
        $create = $this->_add_org_ejecuta(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }


    private function org_sucursal(PDO $link): array|stdClass
    {
        $create = $this->_add_org_sucursal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function org_tipo_empresa(PDO $link): array|stdClass
    {
        $create = $this->_add_org_tipo_empresa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }
    private function org_tipo_actividad(PDO $link): array|stdClass
    {
        $create = $this->_add_org_tipo_actividad(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }


    private function org_tipo_sucursal(PDO $link): array|stdClass
    {
        $create = $this->_add_org_tipo_sucursal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    private function org_tipo_puesto(PDO $link): array|stdClass
    {
        $create = $this->_add_org_tipo_puesto(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }


    final public function instala(PDO $link)
    {
        $result = new stdClass();
        $org_tipo_empresa = $this->org_tipo_empresa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_tipo_empresa', data:  $org_tipo_empresa);
        }
        $org_tipo_actividad = $this->org_tipo_actividad(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_tipo_actividad', data:  $org_tipo_actividad);
        }


        $org_tipo_puesto = $this->org_tipo_puesto(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_tipo_puesto', data:  $org_tipo_puesto);
        }

        $org_tipo_sucursal = $this->org_tipo_sucursal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_tipo_sucursal', data:  $org_tipo_sucursal);
        }

        $org_clasificacion_dep = $this->org_clasificacion_dep(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_clasificacion_dep', data:  $org_clasificacion_dep);
        }

        $org_empresa = $this->org_empresa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_empresa', data:  $org_empresa);
        }

        $org_sucursal = $this->org_sucursal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_sucursal', data:  $org_sucursal);
        }

        $org_departamento = $this->org_departamento(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_departamento', data:  $org_departamento);
        }

        $org_puesto = $this->org_puesto(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_puesto', data:  $org_puesto);
        }
        
        $org_logo = $this->org_logo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_logo', data:  $org_logo);
        }

        $org_actividad = $this->org_actividad(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_actividad', data:  $org_actividad);
        }

        $org_ejecuta = $this->org_ejecuta(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_ejecuta', data:  $org_ejecuta);
        }


        return $result;

    }

}