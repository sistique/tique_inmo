<?php
namespace gamboamartin\direccion_postal\instalacion;

use config\generales;
use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\direccion_postal\models\dp_calle;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\direccion_postal\models\dp_colonia;
use gamboamartin\direccion_postal\models\dp_colonia_postal;
use gamboamartin\direccion_postal\models\dp_cp;
use gamboamartin\direccion_postal\models\dp_estado;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\direccion_postal\models\dp_pais;
use gamboamartin\errores\errores;
use gamboamartin\plugins\Importador;
use PDO;
use stdClass;

class instalacion
{

    private function _add_dp_calle(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'dp_calle');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;




        return $out;

    }
    private function _add_dp_calle_pertenece(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'dp_calle_pertenece');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $campos = new stdClass();
        $campos->georeferencia = new stdClass();

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'dp_calle_pertenece');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;

        $foraneas = array();
        $foraneas['dp_calle_id'] = new stdClass();
        $foraneas['dp_colonia_postal_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'dp_calle_pertenece');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;



        return $out;

    }


    private function _add_dp_colonia(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'dp_colonia');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $campos = new stdClass();
        $campos->georeferencia = new stdClass();

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'dp_colonia');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;


        return $out;

    }

    private function _add_dp_colonia_postal(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'dp_colonia_postal');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['dp_cp_id'] = new stdClass();
        $foraneas['dp_colonia_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'dp_colonia_postal');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;



        return $out;

    }

    private function _add_dp_cp(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'dp_cp');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['dp_municipio_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'dp_cp');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        $campos = new stdClass();
        $campos->georeferencia = new stdClass();

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'dp_cp');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;



        return $out;

    }

    private function _add_dp_estado(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'dp_estado');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['dp_pais_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'dp_estado');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;




        return $out;

    }

    private function _add_dp_municipio(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'dp_municipio');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['dp_estado_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'dp_municipio');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;



        return $out;

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Este método maneja la creación de la tabla 'dp_pais' en el proceso de instalación.
     *
     * @param PDO $link Conexión de base de datos mediante el objeto PDO.
     *
     * @return array|stdClass
     * Retorna un objeto con el resultado de la creación de la tabla. En caso de error, Retorna un objeto de error.
     *
     * @throws errores En el caso de que ocurran errores en el proceso de creación de la tabla, se lanza una excepción.
     * @version 20.3.0
     */

    private function _add_dp_pais(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'dp_pais');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;

        return $out;

    }

    private function dp_calle(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_dp_calle(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $dp_calle_ins['id'] = '100';
        $dp_calle_ins['codigo'] = 'PREDETERMINADO';
        $dp_calle_ins['descripcion_select'] = 'PREDETERMINADO';
        $dp_calle_ins['descripcion'] = 'PREDETERMINADO';
        $dp_calle_ins['predeterminado'] = 'activo';

        $dp_calles_ins[1] = $dp_calle_ins;

        foreach ($dp_calles_ins as $dp_calle_ins){
            $alta = (new dp_calle(link: $link))->inserta_registro_si_no_existe(registro: $dp_calle_ins);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            }
            $out->altas[] = $alta;

        }


        return $out;

    }

    private function dp_calle_pertenece(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_dp_calle_pertenece(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }


        $dp_calle_ins['id'] = '100';
        $dp_calle_ins['codigo'] = 'PREDETERMINADO';
        $dp_calle_ins['descripcion_select'] = 'PREDETERMINADO';
        $dp_calle_ins['descripcion'] = 'PREDETERMINADO';
        $dp_calle_ins['predeterminado'] = 'activo';
        $dp_calle_ins['dp_calle_id'] = '100';
        $dp_calle_ins['georeferencia'] = '100';
        $dp_calle_ins['dp_colonia_postal_id'] = '105';

        $dp_calles_ins[1] = $dp_calle_ins;

        foreach ($dp_calles_ins as $dp_calle_ins){
            $alta = (new dp_calle_pertenece(link: $link))->inserta_registro_si_no_existe(registro: $dp_calle_ins);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            }
            $out->altas[] = $alta;

        }


        return $out;

    }



    private function dp_colonia(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_dp_colonia(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $dp_colonias_ins = array();
        $dp_colonia_ins = array();
        $dp_colonia_ins['id'] = '49728';
        $dp_colonia_ins['codigo'] = '49728';
        $dp_colonia_ins['descripcion_select'] = 'Residencial Revolución';
        $dp_colonia_ins['descripcion'] = 'Residencial Revolución';
        $dp_colonia_ins['georeferencia'] = 'SG';

        $dp_colonias_ins[0] = $dp_colonia_ins;

        $dp_colonia_ins = array();
        $dp_colonia_ins['id'] = '110707';
        $dp_colonia_ins['codigo'] = '110707';
        $dp_colonia_ins['descripcion_select'] = 'PREDETERMINADO';
        $dp_colonia_ins['descripcion'] = 'PREDETERMINADO';
        $dp_colonia_ins['georeferencia'] = 'SG';
        $dp_colonia_ins['predeterminado'] = 'activo';

        $dp_colonias_ins[1] = $dp_colonia_ins;


        foreach ($dp_colonias_ins as $dp_colonia_ins){
            $alta = (new dp_colonia(link: $link))->inserta_registro_si_no_existe(registro: $dp_colonia_ins);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            }
            $out->altas[] = $alta;

        }

        return $out;

    }

    private function dp_colonia_postal(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_dp_colonia_postal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $dp_colonias_postales_ins = array();
        $dp_colonia_postal_ins['id'] = '23';
        $dp_colonia_postal_ins['codigo'] = '45580 Residencial Revolución';
        $dp_colonia_postal_ins['descripcion_select'] = '45580 Residencial Revolución Residencial Revolución - 45580';
        $dp_colonia_postal_ins['descripcion'] = 'Residencial Revolución - 45580';
        $dp_colonia_postal_ins['dp_cp_id'] = '2';
        $dp_colonia_postal_ins['dp_colonia_id'] = '49728';

        $dp_colonias_postales_ins[0] = $dp_colonia_postal_ins;


        $dp_colonia_postal_ins['id'] = '105';
        $dp_colonia_postal_ins['codigo'] = 'PREDETERMINADO';
        $dp_colonia_postal_ins['descripcion_select'] = 'PREDETERMINADO';
        $dp_colonia_postal_ins['descripcion'] = 'PREDETERMINADO';
        $dp_colonia_postal_ins['dp_cp_id'] = '11';
        $dp_colonia_postal_ins['dp_colonia_id'] = '110707';
        $dp_colonia_postal_ins['predeterminado'] = 'activo';

        $dp_colonias_postales_ins[1] = $dp_colonia_postal_ins;

        foreach ($dp_colonias_postales_ins as $dp_colonia_postal_ins){
            $alta = (new dp_colonia_postal(link: $link))->inserta_registro_si_no_existe(registro: $dp_colonia_postal_ins);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            }
            $out->altas[] = $alta;

        }

        return $out;

    }

    private function dp_cp(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_dp_cp(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $dp_cps_ins = array();
        $dp_cp_ins = array();
        $dp_cp_ins['id'] = '1';
        $dp_cp_ins['codigo'] = '45010';
        $dp_cp_ins['descripcion_select'] = '45010 45010';
        $dp_cp_ins['descripcion'] = '45010';
        $dp_cp_ins['dp_municipio_id'] = '1805';
        $dp_cp_ins['georeferencia'] = 'SG';
        $dp_cps_ins[0] = $dp_cp_ins;

        $dp_cp_ins = array();
        $dp_cp_ins['id'] = '2';
        $dp_cp_ins['codigo'] = '45580';
        $dp_cp_ins['descripcion_select'] = '45580 45580';
        $dp_cp_ins['descripcion'] = '45580';
        $dp_cp_ins['dp_municipio_id'] = '1649';
        $dp_cp_ins['georeferencia'] = 'SG';

        $dp_cps_ins[1] = $dp_cp_ins;

        $dp_cp_ins = array();
        $dp_cp_ins['id'] = '11';
        $dp_cp_ins['codigo'] = 'PRED';
        $dp_cp_ins['descripcion_select'] = 'PRED';
        $dp_cp_ins['descripcion'] = 'PRED';
        $dp_cp_ins['dp_municipio_id'] = '2467';
        $dp_cp_ins['georeferencia'] = 'SG';
        $dp_cp_ins['predeterminado'] = 'activo';

        $dp_cps_ins[2] = $dp_cp_ins;

        foreach ($dp_cps_ins as $dp_cp_ins){
            $alta = (new dp_cp(link: $link))->inserta_registro_si_no_existe(registro: $dp_cp_ins);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            }
            $out->altas[] = $alta;

        }

        $adm_menu_descripcion = 'Direcciones';
        $adm_sistema_descripcion = 'direccion_postal';
        $etiqueta_label = 'CPS';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/direccion_postal';
        $adm_namespace_descripcion = 'gamboa.martin/direccion_postal';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'get_cp',
            adm_seccion_descripcion: __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-file-earmark-plus-fill',
            link: $link, lista: 'inactivo', titulo: 'Get CP');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        return $out;

    }

    private function dp_estado(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_dp_estado(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';
        $columnas[] = 'status';
        $columnas[] = 'descripcion_select';
        $columnas[] = 'alias';
        $columnas[] = 'codigo_bis';
        $columnas[] = 'dp_pais_id';
        $columnas[] = 'predeterminado';


        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'direccion_postal'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/direccion_postal/instalacion/".__FUNCTION__.".ods";
        }


        $modelo = new dp_estado(link: $link);

        $n_rows = $modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_rows', data: $n_rows);
        }
        $altas = array();
        if($n_rows !== 97) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                if(is_object($row)) {
                    $row = (array)$row;
                }
                $ins = (new _instalacion(link: $link))->row_ins_base(row: $row);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al maquetar row base', data: $ins);
                }
                $ins['dp_pais_id'] = trim($row['dp_pais_id']);
                $alta = $modelo->inserta_registro_si_no_existe(registro: $ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $alta);
                }
                $altas[] = $alta;
            }
        }


        $adm_menu_descripcion = 'Direcciones';
        $adm_sistema_descripcion = 'direccion_postal';
        $etiqueta_label = 'Estados';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/direccion_postal';
        $adm_namespace_descripcion = 'gamboa.martin/direccion_postal';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'get_estado',
            adm_seccion_descripcion: __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-file-earmark-plus-fill',
            link: $link, lista: 'inactivo', titulo: 'Get Estado');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }


        return $out;

    }

    private function dp_municipio(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_dp_municipio(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';
        $columnas[] = 'status';
        $columnas[] = 'descripcion_select';
        $columnas[] = 'alias';
        $columnas[] = 'codigo_bis';
        $columnas[] = 'dp_estado_id';
        $columnas[] = 'predeterminado';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'direccion_postal'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/direccion_postal/instalacion/".__FUNCTION__.".ods";
        }


        $modelo = new dp_municipio(link: $link);

        $n_rows = $modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_rows', data: $n_rows);
        }
        $altas = array();
        if($n_rows !== 2466) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer municipio', data: $data);
            }

            foreach ($data as $row) {
                if(is_object($row)) {
                    $row = (array)$row;
                }
                $ins = (new _instalacion(link: $link))->row_ins_base(row: $row);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al maquetar row base', data: $ins);
                }

                $ins['dp_estado_id'] = trim($row['dp_estado_id']);
                $alta = $modelo->inserta_registro_si_no_existe(registro: $ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar municipio', data: $alta);
                }
                $altas[] = $alta;
            }
        }

        $adm_menu_descripcion = 'Direcciones';
        $adm_sistema_descripcion = 'direccion_postal';
        $etiqueta_label = 'Municipios';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/direccion_postal';
        $adm_namespace_descripcion = 'gamboa.martin/direccion_postal';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'get_municipio',
            adm_seccion_descripcion: __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-file-earmark-plus-fill',
            link: $link, lista: 'inactivo', titulo: 'Get Municipio');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }


        return $out;

    }

    private function dp_pais(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_dp_pais(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';
        $columnas[] = 'status';
        $columnas[] = 'descripcion_select';
        $columnas[] = 'alias';
        $columnas[] = 'codigo_bis';
        $columnas[] = 'predeterminado';


        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'direccion_postal'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/direccion_postal/instalacion/".__FUNCTION__.".ods";
        }


        $modelo = new dp_pais(link: $link,aplica_transacciones_base: true);

        $n_rows = $modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_rows', data: $n_rows);
        }
        $altas = array();
        if($n_rows !== 251) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer dp_pais', data: $data);
            }

            foreach ($data as $row) {
                if(is_object($row)) {
                    $row = (array)$row;
                }
                $ins = (new _instalacion(link: $link))->row_ins_base(row: $row);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al maquetar row base', data: $ins);
                }
                $alta = $modelo->inserta_registro_si_no_existe(registro: $ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar dp_pais', data: $alta);
                }
                $altas[] = $alta;
            }
        }

        $adm_menu_descripcion = 'Direcciones';
        $adm_sistema_descripcion = 'direccion_postal';
        $etiqueta_label = 'Paises';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/direccion_postal';
        $adm_namespace_descripcion = 'gamboa.martin/direccion_postal';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        $alta_accion = (new _adm())->inserta_accion_base(adm_accion_descripcion: 'get_pais',
            adm_seccion_descripcion: __FUNCTION__, es_view: 'inactivo', icono: 'bi bi-file-earmark-plus-fill',
            link: $link, lista: 'inactivo', titulo: 'Get Pais');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar accion',data:  $alta_accion);
        }

        return $out;

    }

    final public function instala(PDO $link): array|stdClass
    {

        $result = new stdClass();

        $dp_pais = $this->dp_pais(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar dp_pais', data:  $dp_pais);
        }
        $result->dp_pais = $dp_pais;

        $dp_colonia = $this->dp_colonia(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar dp_colonia', data:  $dp_colonia);
        }
        $result->dp_colonia = $dp_colonia;

        $dp_estado = $this->dp_estado(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar dp_estado', data:  $dp_estado);
        }
        $result->dp_estado = $dp_estado;

        $dp_municipio = $this->dp_municipio(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar dp_municipio', data:  $dp_municipio);
        }
        $result->dp_municipio = $dp_municipio;

        $dp_cp = $this->dp_cp(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar dp_cp', data:  $dp_cp);
        }
        $result->dp_cp = $dp_cp;

        $dp_colonia_postal = $this->dp_colonia_postal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar dp_colonia_postal', data:  $dp_colonia_postal);
        }
        $result->dp_colonia_postal = $dp_colonia_postal;

        $dp_calle = $this->dp_calle(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar dp_calle', data:  $dp_calle);
        }
        $result->dp_calle = $dp_calle;

        $dp_calle_pertenece = $this->dp_calle_pertenece(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar dp_calle_pertenece', data:  $dp_calle_pertenece);
        }
        $result->dp_calle_pertenece = $dp_calle_pertenece;


        return $result;

    }

}
