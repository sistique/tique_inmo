<?php
namespace gamboamartin\cat_sat\tests;
use base\orm\modelo_base;
use gamboamartin\cat_sat\models\cat_sat_clase_producto;
use gamboamartin\cat_sat\models\cat_sat_conf_imps;
use gamboamartin\cat_sat\models\cat_sat_conf_imps_tipo_pers;
use gamboamartin\cat_sat\models\cat_sat_conf_reg_tp;
use gamboamartin\cat_sat\models\cat_sat_division_producto;
use gamboamartin\cat_sat\models\cat_sat_factor;
use gamboamartin\cat_sat\models\cat_sat_forma_pago;
use gamboamartin\cat_sat\models\cat_sat_grupo_producto;
use gamboamartin\cat_sat\models\cat_sat_isn;
use gamboamartin\cat_sat\models\cat_sat_isr;
use gamboamartin\cat_sat\models\cat_sat_metodo_pago;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\models\cat_sat_obj_imp;
use gamboamartin\cat_sat\models\cat_sat_periodicidad_pago_nom;
use gamboamartin\cat_sat\models\cat_sat_producto;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use gamboamartin\cat_sat\models\cat_sat_subsidio;
use gamboamartin\cat_sat\models\cat_sat_tipo_de_comprobante;
use gamboamartin\cat_sat\models\cat_sat_tipo_factor;
use gamboamartin\cat_sat\models\cat_sat_tipo_nomina;
use gamboamartin\cat_sat\models\cat_sat_tipo_persona;
use gamboamartin\cat_sat\models\cat_sat_tipo_producto;
use gamboamartin\cat_sat\models\cat_sat_tipo_relacion;
use gamboamartin\cat_sat\models\cat_sat_unidad;
use gamboamartin\cat_sat\models\cat_sat_uso_cfdi;
use gamboamartin\direccion_postal\models\dp_estado;
use gamboamartin\direccion_postal\models\dp_pais;
use gamboamartin\errores\errores;

use gamboamartin\test\test;
use PDO;
use stdClass;


class base_test{

    public function alta_adm_seccion(PDO $link, string $descripcion = 'adm_seccion', int $id = 1): array|stdClass
    {

        $alta = (new \gamboamartin\administrador\tests\base_test())->alta_adm_seccion(
            link: $link, descripcion: $descripcion, id: $id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_adm_usuario(PDO $link, int $id = 1): array|stdClass
    {

        $alta = (new \gamboamartin\administrador\tests\base_test())->alta_adm_usuario(link: $link,id: $id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_clase_producto(PDO $link, int $cat_sat_grupo_producto_id = 1,
                                                string $codigo = '010101', $descripcion = '010101',
                                                int $id = 1, string $predeterminado = 'inactivo'): array|stdClass
    {

        $existe = (new cat_sat_grupo_producto($link))->existe_by_id(registro_id: $cat_sat_grupo_producto_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_grupo_producto(link: $link, id: $cat_sat_grupo_producto_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['predeterminado'] = $predeterminado;
        $registro['cat_sat_grupo_producto_id'] = $cat_sat_grupo_producto_id;
        $registro['codigo'] = $codigo;

        $alta = (new cat_sat_clase_producto($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_conf_imps(PDO $link, int $id = 1): array|stdClass
    {

        $registro['id'] = $id;

        $alta = (new cat_sat_conf_imps($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }



    public function alta_cat_sat_division_producto(PDO $link, int $cat_sat_tipo_producto_id = 1, string $codigo = '01',
                                                   $descripcion = '01', int $id = 1,
                                                   string $predeterminado = 'inactivo'): array|stdClass
    {

        $existe = (new cat_sat_tipo_producto($link))->existe_by_id(registro_id: $cat_sat_tipo_producto_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_tipo_producto(link: $link, id: $cat_sat_tipo_producto_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['predeterminado'] = $predeterminado;
        $registro['codigo'] = $codigo;
        $registro['cat_sat_tipo_producto_id'] = $cat_sat_tipo_producto_id;

        $alta = (new cat_sat_division_producto($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_factor(PDO $link, string $codigo = '16', float $factor = .16, int $id = 1): array|stdClass
    {
        $registro['codigo'] = $codigo;
        $registro['id'] = $id;
        $registro['factor'] = $factor;
        $alta = (new cat_sat_factor($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_forma_pago(PDO $link, string $codigo = '99', $descripcion = '99', int $id = 1,
                                             bool $predeterminado = false): array|stdClass
    {
        $registro = (new test())->registro(
            codigo:$codigo,descripcion: $descripcion,id: $id, predeterminado: $predeterminado);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }

        $alta = (new cat_sat_forma_pago($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_grupo_producto(PDO $link, int $cat_sat_division_producto_id = 1,
                                                string $codigo = '0101', $descripcion = '0101', int $id = 1,
                                                string $predeterminado = 'inactivo'): array|stdClass
    {

        $existe = (new cat_sat_division_producto($link))->existe_by_id(registro_id: $cat_sat_division_producto_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_division_producto(link: $link, id: $cat_sat_division_producto_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['predeterminado'] = $predeterminado;
        $registro['codigo'] = $codigo;
        $registro['cat_sat_division_producto_id'] = $cat_sat_division_producto_id;

        $alta = (new cat_sat_grupo_producto($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_isn(PDO $link, string $codigo = '1', int $dp_estado_id = 1, $descripcion = '1', int $id = 1): array|stdClass
    {

        $existe = (new dp_estado($link))->existe_by_id(registro_id: $dp_estado_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_dp_estado(link: $link, id: $dp_estado_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }

        $registro = (new test(''))->registro(
            codigo:$codigo,descripcion: $descripcion,id: $id, predeterminado: false);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }

        $registro['dp_estado_id'] = $dp_estado_id;

        $alta = (new cat_sat_isn($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_isr(PDO $link, int $cat_sat_periodicidad_pago_nom_id = 1, float $cuota_fija = 0,
                                     string $fecha_fin = '2020-12-31', string $fecha_inicio = '2020-01-01',
                                     int $id = 1, float $limite_inferior = 0.01,
                                     float $limite_superior = 99999, float $porcentaje_excedente = 1.92): array|\stdClass
    {

        $existe = (new cat_sat_periodicidad_pago_nom($link))->existe_by_id(registro_id: $cat_sat_periodicidad_pago_nom_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_periodicidad_pago_nom(link: $link, id: $cat_sat_periodicidad_pago_nom_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;
        $registro['descripcion_select'] = 1;
        $registro['codigo_bis'] = 1;
        $registro['alias'] = 1;
        $registro['limite_inferior'] =$limite_inferior;
        $registro['limite_superior'] = $limite_superior;
        $registro['cuota_fija'] = $cuota_fija;
        $registro['porcentaje_excedente'] = $porcentaje_excedente;
        $registro['cat_sat_periodicidad_pago_nom_id'] = $cat_sat_periodicidad_pago_nom_id;
        $registro['fecha_inicio'] = $fecha_inicio;
        $registro['fecha_fin'] = $fecha_fin;

        $alta = (new cat_sat_isr($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_metodo_pago(PDO $link, string $codigo = '1', $descripcion = '1', int $id = 1,
                                             string $predeterminado = 'inactivo'): array|stdClass
    {
        $registro = (new test())->registro(
            codigo:$codigo,descripcion: $descripcion,id: $id, predeterminado: $predeterminado);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }

        $registro['predeterminado'] = $predeterminado;

        $alta = (new cat_sat_metodo_pago($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }



    public function alta_cat_sat_moneda(PDO $link, string $codigo = 'XSM', string $descripcion = '1', int $id = 1,
                                        int $dp_pais_id = 1, string $predeterminado = 'inactivo'): array|stdClass
    {

        $registro = (new test())->registro(codigo:$codigo,descripcion:  $descripcion, id: $id,
            predeterminado:  $predeterminado);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }



        $existe = (new dp_pais($link))->existe_by_id(registro_id: $dp_pais_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_dp_pais(link: $link);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }

        }

        $registro['dp_pais_id'] = $dp_pais_id;
        $registro['predeterminado'] = $predeterminado;

        $alta = (new cat_sat_moneda($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_obj_imp(PDO $link, string $codigo = '1', $descripcion = '1', int $id = 1,
                                        bool $predeterminado = false): array|stdClass
    {
        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['predeterminado'] = $predeterminado;
        $alta = (new cat_sat_obj_imp($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_periodicidad_pago_nom(PDO $link, string $codigo = '01', $id = 1): array|\stdClass
    {
        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = 1;
        $registro['descripcion_select'] = 1;
        $registro['codigo_bis'] = 1;
        $registro['alias'] = 1;
        $registro['n_dias'] = 1;

        $alta = (new cat_sat_periodicidad_pago_nom($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_producto(PDO $link, int $cat_sat_clase_producto_id = 1, string $codigo = '01010101',
                                          $descripcion = '01010101', int $id = 1,
                                          string $predeterminado = 'inactivo'): array|stdClass
    {

        $existe = (new cat_sat_clase_producto($link))->existe_by_id(registro_id: $cat_sat_clase_producto_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_clase_producto(link: $link);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }

        }

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['predeterminado'] = $predeterminado;
        $registro['cat_sat_clase_producto_id'] = $cat_sat_clase_producto_id;
        $registro['codigo'] = $codigo;

        $alta = (new cat_sat_producto($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_regimen_fiscal(PDO $link, string $codigo = '001', string $descripcion = '1',
                                                int $id = 1, bool $predeterminado = false): array|stdClass
    {
        $registro = (new test())->registro(
            codigo:$codigo,descripcion: $descripcion,id: $id, predeterminado: $predeterminado);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }


        $alta = (new cat_sat_regimen_fiscal($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_subsidio(PDO $link, string $alias = '1', int $cat_sat_periodicidad_pago_nom_id = 1,
                                          string $codigo = '1', string $codigo_bis = '1', float $cuota_fija = 0,
                                          string $descripcion = '1', string $descripcion_select = '1',
                                          string $fecha_fin = '2020-12-31', string $fecha_inicio = '2020-01-01',
                                          int $id = 1, float $limite_inferior = 0.01, float $limite_superior = 99999,
                                          float $porcentaje_excedente = 1.92): array|\stdClass
    {

        $existe = (new cat_sat_periodicidad_pago_nom($link))->existe_by_id(registro_id: $cat_sat_periodicidad_pago_nom_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_periodicidad_pago_nom(link: $link, id: $cat_sat_periodicidad_pago_nom_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['descripcion_select'] = $descripcion_select;
        $registro['codigo_bis'] = $codigo_bis;
        $registro['alias'] = $alias;
        $registro['limite_inferior'] = $limite_inferior;
        $registro['limite_superior'] = $limite_superior;
        $registro['cuota_fija'] = $cuota_fija;
        $registro['porcentaje_excedente'] = $porcentaje_excedente;
        $registro['cat_sat_periodicidad_pago_nom_id'] = $cat_sat_periodicidad_pago_nom_id;
        $registro['fecha_inicio'] = $fecha_inicio;
        $registro['fecha_fin'] = $fecha_fin;

        $alta = (new cat_sat_subsidio($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_tipo_de_comprobante(PDO $link, string $codigo = '1', $descripcion = '1', int $id = 1,
                                             bool $predeterminado = false): array|stdClass
    {
        $registro = (new test())->registro(
            codigo:$codigo,descripcion: $descripcion,id: $id, predeterminado: $predeterminado);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }

        $alta = (new cat_sat_tipo_de_comprobante($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_tipo_factor(PDO $link, string $descripcion = 'Tasa', int $id = 1): array|stdClass
    {

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;

        $alta = (new cat_sat_tipo_factor($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_tipo_nomina(PDO $link, string $codigo = 'A', $descripcion = '1', int $id = 1): array|stdClass
    {
        $registro = (new test())->registro(
            codigo:$codigo,descripcion: $descripcion,id: $id, predeterminado: false);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }

        $alta = (new cat_sat_tipo_nomina($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_tipo_persona(PDO $link, string $codigo = '99', $descripcion = '99', int $id = 1,
                                            bool $predeterminado = false): array|stdClass
    {
        $registro = (new test())->registro(
            codigo:$codigo,descripcion: $descripcion,id: $id, predeterminado: $predeterminado);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }

        $alta = (new cat_sat_tipo_persona($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_tipo_producto(PDO $link, string $codigo = '010101', $descripcion = '010101',
                                                int $id = 1, string $predeterminado = 'inactivo'): array|stdClass
    {


        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['predeterminado'] = $predeterminado;
        $registro['codigo'] = $codigo;

        $alta = (new cat_sat_tipo_producto($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_tipo_relacion(PDO $link, int $id = 1): array|stdClass
    {

        $registro['id'] = $id;

        $alta = (new cat_sat_tipo_relacion($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }


    public function alta_cat_sat_uso_cfdi(PDO $link, string $codigo = '1', $descripcion = '1', int $id = 1,
                                             bool $predeterminado = false): array|stdClass
    {
        $registro = (new test())->registro(
            codigo:$codigo,descripcion: $descripcion,id: $id, predeterminado: $predeterminado);
        if (errores::$error) {
            return (new errores())->error('Error al integrar predeterminado si existe', $registro);

        }

        $alta = (new cat_sat_uso_cfdi($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_cat_sat_unidad(PDO $link, string $codigo = '1', $descripcion = '1', int $id = 1,
                                          bool $predeterminado = false): array|stdClass
    {
        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['predeterminado'] = $predeterminado;
        $alta = (new cat_sat_unidad($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_dp_estado(PDO $link, string $codigo = '1', $descripcion = '1', int $id = 1): array|stdClass
    {

        $alta = (new \gamboamartin\direccion_postal\tests\base_test())->alta_dp_estado(link: $link, codigo: $codigo,
            descripcion: $descripcion, id: $id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }

    public function alta_dp_pais(PDO $link, string $codigo = '1', $descripcion = '1', int $id = 1): array|stdClass
    {

        $alta = (new \gamboamartin\direccion_postal\tests\base_test())->alta_dp_pais(link: $link, codigo: $codigo,
            descripcion: $descripcion, id: $id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }





    public function del(PDO $link, string $name_model): array
    {

        $model = (new modelo_base($link))->genera_modelo(modelo: $name_model);
        $del = $model->elimina_todo();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar '.$name_model, data: $del);
        }
        return $del;
    }

    public function del_adm_seccion(PDO $link): array
    {


        $del = (new \gamboamartin\administrador\tests\base_test())->del_adm_seccion(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_adm_usuario(PDO $link): array
    {


        $del = (new \gamboamartin\administrador\tests\base_test())->del_adm_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_clase_producto(PDO $link): array
    {
        $del = $this->del_cat_sat_producto($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_clase_producto');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_division_producto(PDO $link): array
    {

        $del = $this->del_cat_sat_grupo_producto($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_division_producto');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_factor(PDO $link): array
    {

        $del = $this->del_cat_sat_traslado_conf($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }
        $del = $this->del_cat_sat_retencion_conf($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_factor');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_forma_pago(PDO $link): array
    {

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_forma_pago');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_grupo_producto(PDO $link): array
    {

        $del = $this->del_cat_sat_clase_producto($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_grupo_producto');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_isn(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_isn');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_metodo_pago(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_metodo_pago');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_moneda(PDO $link): array
    {

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_moneda');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_obj_imp(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_obj_imp');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_producto(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_producto');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_regimen_fiscal(PDO $link): array
    {

        $del = $this->del_cat_sat_conf_reg_tp($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_regimen_fiscal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_retencion_conf(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_retencion_conf');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_tipo_de_comprobante(PDO $link): array
    {
        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_tipo_de_comprobante');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_tipo_factor(PDO $link): array
    {

        $del = $this->del_cat_sat_retencion_conf($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }
        $del = $this->del_cat_sat_traslado_conf($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }


        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_tipo_factor');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function alta_cat_sat_conf_reg_tp(PDO $link, string $cat_sat_regimen_fiscal_codigo = '001',
                                             int $cat_sat_regimen_fiscal_id = 1,
                                             string $cat_sat_tipo_persona_codigo = '99',
                                             int $cat_sat_tipo_persona_id = 1, int $id = 1): array|stdClass
    {

        $existe = (new cat_sat_regimen_fiscal($link))->existe_by_id(registro_id: $cat_sat_regimen_fiscal_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar si existe', $existe);

        }
        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_regimen_fiscal(link: $link, codigo: $cat_sat_regimen_fiscal_codigo,
                id: $cat_sat_regimen_fiscal_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }

        $existe = (new cat_sat_tipo_persona($link))->existe_by_id(registro_id: $cat_sat_tipo_persona_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar si existe', $existe);

        }
        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_tipo_persona(link: $link, codigo: $cat_sat_tipo_persona_codigo,
                id: $cat_sat_tipo_persona_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }

        $registro['id'] = $id;
        $registro['cat_sat_regimen_fiscal_id'] = $cat_sat_regimen_fiscal_id;
        $registro['cat_sat_tipo_persona_id'] = $cat_sat_tipo_persona_id;

        $alta = (new cat_sat_conf_reg_tp($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }


    public function del_cat_sat_tipo_nomina(PDO $link): array
    {

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_tipo_nomina');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_conf_reg_tp(PDO $link): array
    {
        $del = $this->del_cat_sat_conf_imps_tipo_pers($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_conf_reg_tp');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_conf_imps_tipo_pers(PDO $link): array
    {

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_conf_imps_tipo_pers');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_tipo_persona(PDO $link): array
    {

        $del = $this->del_cat_sat_conf_reg_tp($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_tipo_persona');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_tipo_producto(PDO $link): array
    {

        $del = $this->del_cat_sat_division_producto($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_tipo_producto');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_traslado_conf(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_traslado_conf');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }


    public function del_cat_sat_unidad(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_unidad');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_dp_colonia(PDO $link): array
    {

        $del = (new \gamboamartin\direccion_postal\tests\base_test())->del_dp_colonia(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }
        return $del;
    }

    public function del_dp_pais(PDO $link): array
    {


        $del = $this->del_cat_sat_isn($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = (new \gamboamartin\direccion_postal\tests\base_test())->del_dp_pais(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }
        return $del;
    }

    public function del_cat_sat_isr(PDO $link): array
    {
        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_isr');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_subsidio(PDO $link): array
    {
        $del = $this->del($link, 'gamboamartin\\cat_sat\\models\\cat_sat_subsidio');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }










}
