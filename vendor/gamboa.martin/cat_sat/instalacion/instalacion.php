<?php
namespace gamboamartin\cat_sat\instalacion;

use base\orm\modelo;
use config\generales;
use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\cat_sat\models\cat_sat_clase_producto;
use gamboamartin\cat_sat\models\cat_sat_conf_imps;
use gamboamartin\cat_sat\models\cat_sat_conf_imps_tipo_pers;
use gamboamartin\cat_sat\models\cat_sat_conf_reg_tp;
use gamboamartin\cat_sat\models\cat_sat_cve_prod;
use gamboamartin\cat_sat\models\cat_sat_division_producto;
use gamboamartin\cat_sat\models\cat_sat_forma_pago;
use gamboamartin\cat_sat\models\cat_sat_grupo_producto;
use gamboamartin\cat_sat\models\cat_sat_metodo_pago;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\models\cat_sat_motivo_cancelacion;
use gamboamartin\cat_sat\models\cat_sat_obj_imp;
use gamboamartin\cat_sat\models\cat_sat_periodicidad;
use gamboamartin\cat_sat\models\cat_sat_producto;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use gamboamartin\cat_sat\models\cat_sat_tipo_de_comprobante;
use gamboamartin\cat_sat\models\cat_sat_tipo_impuesto;
use gamboamartin\cat_sat\models\cat_sat_tipo_persona;
use gamboamartin\cat_sat\models\cat_sat_tipo_producto;
use gamboamartin\cat_sat\models\cat_sat_tipo_relacion;
use gamboamartin\cat_sat\models\cat_sat_unidad;
use gamboamartin\cat_sat\models\cat_sat_uso_cfdi;
use gamboamartin\errores\errores;
use gamboamartin\js_base\valida;
use gamboamartin\plugins\Importador;
use PDO;
use stdClass;

class instalacion
{
    private stdClass $data;

    public function __construct(PDO $link)
    {
        $data = $this->data(link: $link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al incializar',data:  $data);
            print_r($error);
            exit;
        }

    }



    private function _add_cat_sat_tipo_persona(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'cat_sat_tipo_persona');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }


        return $out;

    }

    private function _add_cat_sat_metodo_pago(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'cat_sat_metodo_pago');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }


        return $out;

    }

    private function _add_cat_sat_unidad(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'cat_sat_unidad');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }


        return $out;

    }


    private function _add_cat_sat_conf_imps(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_conf_imps');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_cve_prod', data: $create);
        }

        $out->create = $create;

        return $out;
    }
    private function _add_cat_sat_conf_imps_tipo_pers(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'cat_sat_conf_imps_tipo_pers');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['cat_sat_conf_reg_tp_id'] = new stdClass();
        $foraneas['cat_sat_conf_imps_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_conf_imps_tipo_pers');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    /**
     * POR DOCUMENTAR WN WIKI
     * Esta función es responsable de agregar el catálogo SAT de formas de pago en la base de datos.
     *
     * @param PDO $link es la conexión PDO a la base de datos.
     * @return array|stdClass Este es el resultado del método. Devuelve un objeto si el método tiene éxito,
     *                        o un array con los detalles del error si ocurre algún problema.
     *                        Dentro de los resultados exitosos, devuelve un objeto que contiene:
     *                        - create: este miembro almacena el estado de la creación de la tabla.
     *
     *  La metodología específica de la función es la siguiente:
     *  - Crea una nueva tabla en la base de datos llamada 'cat_sat_forma_pago'. Si hay algún error al
     *    crear la tabla, devuelve un objeto estándar que contiene detalles sobre los errores encontrados.
     *  - Al final, la función devuelve un objeto con la respuesta de la creación de la tabla.
     *
     * @throws errores en caso de que no se pueda crear la tabla correctamente.
     * @version 18.29.0
     */
    private function _add_cat_sat_forma_pago(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_forma_pago');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_forma_pago', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_moneda(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_moneda');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_moneda', data: $create);
        }

        $out->create = $create;

        $foraneas = array();
        $foraneas['dp_pais_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_moneda');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        return $out;
    }
    private function _add_cat_sat_motivo_cancelacion(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_motivo_cancelacion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_motivo_cancelacion', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_periodo(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_periodo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_moneda', data: $create);
        }

        $out->create = $create;

        $foraneas = array();
        $foraneas['cat_sat_periodicidad_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_periodo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        $campos = new stdClass();
        $campos->fecha_inicio = new stdClass();
        $campos->fecha_inicio->tipo_dato = 'DATE';

        $campos->fecha_fin = new stdClass();
        $campos->fecha_fin->tipo_dato = 'DATE';


        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'cat_sat_periodo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;

        return $out;
    }

    private function _add_cat_sat_isn(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_isn');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_isn', data: $create);
        }

        $out->create = $create;

        $foraneas = array();
        $foraneas['dp_estado_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_isn');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        $campos = new stdClass();
        $campos->porcentaje_isn = new stdClass();
        $campos->porcentaje_isn->tipo_dato = 'DOUBLE';

        $campos->factor_isn_adicional = new stdClass();
        $campos->factor_isn_adicional->tipo_dato = 'DOUBLE';



        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'cat_sat_periodo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;

        return $out;
    }

    private function _add_cat_sat_isr(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_isr');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_isr', data: $create);
        }

        $out->create = $create;

        $foraneas = array();
        $foraneas['cat_sat_periodicidad_pago_nom_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_isr');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        $campos = new stdClass();
        $campos->limite_inferior = new stdClass();
        $campos->limite_inferior->tipo_dato = 'DOUBLE';

        $campos->limite_superior = new stdClass();
        $campos->limite_superior->tipo_dato = 'DOUBLE';

        $campos->cuota_fija = new stdClass();
        $campos->cuota_fija->tipo_dato = 'DOUBLE';

        $campos->porcentaje_excedente = new stdClass();
        $campos->porcentaje_excedente->tipo_dato = 'DOUBLE';

        $campos->fecha_inicio = new stdClass();
        $campos->fecha_inicio->tipo_dato = 'DATE';

        $campos->fecha_fin = new stdClass();
        $campos->fecha_fin->tipo_dato = 'DATE';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'cat_sat_isr');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;

        return $out;
    }
    private function _add_cat_sat_retencion_conf(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_retencion_conf');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_retencion_conf', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_tipo_contrato_nom(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_tipo_contrato_nom');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_retencion_conf', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_tipo_nomina(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_tipo_nomina');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_tipo_nomina', data: $create);
        }

        $out->create = $create;

        return $out;
    }
    private function _add_cat_sat_tipo_impuesto(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_tipo_impuesto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_tipo_impuesto', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_traslado_conf(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_traslado_conf');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_retencion_conf', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_tipo_relacion(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_tipo_relacion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_motivo_cancelacion', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_actividad_economica(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_actividad_economica');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_motivo_cancelacion', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function actualiza_datos_predeterminadas(int $id_compare, modelo $modelo, bool $valida_alfa_code): array
    {
        $registros_actuales = $modelo->registros();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener monedas', data: $registros_actuales);
        }

        $upds = $this->upd_registros(id_compare: $id_compare, registros_actuales: $registros_actuales, modelo: $modelo, valida_alfa_code: $valida_alfa_code);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al actualizar registros', data: $upds);
        }
        return $upds;
    }

    private function _add_cat_sat_clase_producto(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_clase_producto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_cve_prod', data: $create);
        }

        $out->create = $create;

        $foraneas = array();
        $foraneas['cat_sat_grupo_producto_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_clase_producto');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }

        return $out;
    }

    private function cat_sat_clase_producto(PDO $link): array|stdClass
    {

        $create = $this->_add_cat_sat_clase_producto(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al actualizar cat_sat_clase_producto', data: $create);
        }

        $out = new stdClass();
        $cat_sat_clase_producto_modelo = new cat_sat_clase_producto(link: $link);

        $cat_sat_clase_productos = array();
        $cat_sat_clase_productos[0]['id'] = 841115;
        $cat_sat_clase_productos[0]['descripcion'] = 'Servicios contables (Honorarios contables)';
        $cat_sat_clase_productos[0]['codigo'] = '841115';
        $cat_sat_clase_productos[0]['descripcion_select'] = '841115 Servicios Contables (Honorarios Contables)';
        $cat_sat_clase_productos[0]['cat_sat_grupo_producto_id'] = '8411';


        $out->cat_sat_clase_productos = $cat_sat_clase_productos;

        foreach ($cat_sat_clase_productos as $cat_sat_clase_producto){
            $existe = $cat_sat_clase_producto_modelo->existe_by_id(registro_id: $cat_sat_clase_producto['id']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe clase de producto', data: $existe);
            }
            $out->existe = $existe;
            if(!$existe){
                $alta = $cat_sat_clase_producto_modelo->alta_registro(registro: $cat_sat_clase_producto);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al insertar clase de producto', data: $alta);
                }
                $out->altas[] = $alta;
            }
        }

        return $out;

    }
    private function cat_sat_conf_imps(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_conf_imps(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_cve_prod', data: $create);
        }

        $out->create = $create;

        $cat_sat_conf_imps = array();

        $cat_sat_conf_imp['id'] = 1;
        $cat_sat_conf_imp['descripcion'] = 'PESONA MORAL IVA 16';
        $cat_sat_conf_imp['codigo'] = '001';
        $cat_sat_conf_imp['descripcion_select'] = '001 PESONA MORAL IVA 16';

        $cat_sat_conf_imps[0] = $cat_sat_conf_imp;

        $cat_sat_conf_imp['id'] = 2;
        $cat_sat_conf_imp['descripcion'] = 'RESICO PERSONA FISICA IVA 16';
        $cat_sat_conf_imp['codigo'] = '002';
        $cat_sat_conf_imp['descripcion_select'] = '002 RESICO PERSONA FISICA IVA 16';

        $cat_sat_conf_imps[1] = $cat_sat_conf_imp;

        $cat_sat_conf_imp['id'] = 3;
        $cat_sat_conf_imp['descripcion'] = 'INMOBILIARIA EXENTO';
        $cat_sat_conf_imp['codigo'] = '003';
        $cat_sat_conf_imp['descripcion_select'] = '003 INMOBILIARIA EXENTO';

        $cat_sat_conf_imps[2] = $cat_sat_conf_imp;

        $cat_sat_conf_imp['id'] = 4;
        $cat_sat_conf_imp['descripcion'] = 'RESICO PERSONAL MORAL CON RETENCION MAS IVA';
        $cat_sat_conf_imp['codigo'] = '004';
        $cat_sat_conf_imp['descripcion_select'] = '004 RESICO PERSONAL MORAL CON RETENCION MAS IVA';

        $cat_sat_conf_imps[3] = $cat_sat_conf_imp;

        $cat_sat_conf_imp['id'] = 5;
        $cat_sat_conf_imp['descripcion'] = 'CONF ARRENADAMIENTO PERSONA FISICA A PERSONA MORAL';
        $cat_sat_conf_imp['codigo'] = '005';
        $cat_sat_conf_imp['descripcion_select'] = '005 CONF ARRENADAMIENTO PERSONA FISICA A PERSONA MORAL';

        $cat_sat_conf_imps[4] = $cat_sat_conf_imp;

        $cat_sat_conf_imp['id'] = 998;
        $cat_sat_conf_imp['descripcion'] = 'RET PROFESIONALES RESICO';
        $cat_sat_conf_imp['codigo'] = '998';
        $cat_sat_conf_imp['descripcion_select'] = '998 RET PROFESIONALES RESICO';

        $cat_sat_conf_imps[5] = $cat_sat_conf_imp;

        $cat_sat_conf_imp['id'] = 999;
        $cat_sat_conf_imp['descripcion'] = 'SIN CONFIGURACIONES';
        $cat_sat_conf_imp['codigo'] = '999';
        $cat_sat_conf_imp['descripcion_select'] = '999 SIN CONFIGURACIONES';

        $cat_sat_conf_imps[6] = $cat_sat_conf_imp;


        $cat_sat_conf_imps_modelo = new cat_sat_conf_imps(link: $link);


        $altas = array();

        foreach ($cat_sat_conf_imps as $cat_sat_conf_imp) {


            $alta = $cat_sat_conf_imps_modelo->inserta_registro_si_no_existe(registro: $cat_sat_conf_imp);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar cat_sat_conf_imp', data: $alta);
            }
            $altas[] = $alta;
        }
        $out->altas = $altas;

        $adm_menu_descripcion = 'Configuraciones';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Configuraciones';
        $adm_seccion_pertenece_descripcion = 'cat_sat';
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $out;

    }

    private function _add_cat_sat_conf_reg_tp(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_conf_reg_tp');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_conf_reg_tp', data: $create);
        }

        $out->create = $create;

        $foraneas = array();
        $foraneas['cat_sat_tipo_persona_id'] = new stdClass();
        $foraneas['cat_sat_regimen_fiscal_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_conf_reg_tp');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }

        return $out;
    }
    private function cat_sat_conf_reg_tp(PDO $link): array|stdClass
    {

        $create = $this->_add_cat_sat_conf_reg_tp(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_conf_reg_tp', data: $create);
        }

        $out = new stdClass();
        $cat_sat_conf_reg_tp_modelo = new cat_sat_conf_reg_tp(link: $link, aplica_transacciones_base: true);

        $cat_sat_conf_reg_tps = array();
        $cat_sat_conf_reg_tps[0]['id'] = 1;
        $cat_sat_conf_reg_tps[0]['cat_sat_tipo_persona_id'] = '4';
        $cat_sat_conf_reg_tps[0]['cat_sat_regimen_fiscal_id'] = '601';

        $cat_sat_conf_reg_tps[1]['id'] = 2;
        $cat_sat_conf_reg_tps[1]['cat_sat_tipo_persona_id'] = '4';
        $cat_sat_conf_reg_tps[1]['cat_sat_regimen_fiscal_id'] = '603';

        $cat_sat_conf_reg_tps[2]['id'] = 3;
        $cat_sat_conf_reg_tps[2]['cat_sat_tipo_persona_id'] = '5';
        $cat_sat_conf_reg_tps[2]['cat_sat_regimen_fiscal_id'] = '605';

        $cat_sat_conf_reg_tps[3]['id'] = 4;
        $cat_sat_conf_reg_tps[3]['cat_sat_tipo_persona_id'] = '5';
        $cat_sat_conf_reg_tps[3]['cat_sat_regimen_fiscal_id'] = '612';

        $cat_sat_conf_reg_tps[4]['id'] = 5;
        $cat_sat_conf_reg_tps[4]['cat_sat_tipo_persona_id'] = '4';
        $cat_sat_conf_reg_tps[4]['cat_sat_regimen_fiscal_id'] = '626';

        $cat_sat_conf_reg_tps[5]['id'] = 6;
        $cat_sat_conf_reg_tps[5]['cat_sat_tipo_persona_id'] = '5';
        $cat_sat_conf_reg_tps[5]['cat_sat_regimen_fiscal_id'] = '626';

        $cat_sat_conf_reg_tps[6]['id'] = 7;
        $cat_sat_conf_reg_tps[6]['cat_sat_tipo_persona_id'] = '4';
        $cat_sat_conf_reg_tps[6]['cat_sat_regimen_fiscal_id'] = '622';

        $cat_sat_conf_reg_tps[7]['id'] = 8;
        $cat_sat_conf_reg_tps[7]['cat_sat_tipo_persona_id'] = '4';
        $cat_sat_conf_reg_tps[7]['cat_sat_regimen_fiscal_id'] = '623';

        $cat_sat_conf_reg_tps[8]['id'] = 9;
        $cat_sat_conf_reg_tps[8]['cat_sat_tipo_persona_id'] = '5';
        $cat_sat_conf_reg_tps[8]['cat_sat_regimen_fiscal_id'] = '616';

        $cat_sat_conf_reg_tps[9]['id'] = 10;
        $cat_sat_conf_reg_tps[9]['cat_sat_tipo_persona_id'] = '5';
        $cat_sat_conf_reg_tps[9]['cat_sat_regimen_fiscal_id'] = '621';


        $out->cat_sat_conf_reg_tps = $cat_sat_conf_reg_tps;

        foreach ($cat_sat_conf_reg_tps as $cat_sat_conf_reg_tp){
            $existe = $cat_sat_conf_reg_tp_modelo->existe_by_id(registro_id: $cat_sat_conf_reg_tp['id']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe cat_sat_conf_reg_tp', data: $existe);
            }
            $out->existe = $existe;
            if(!$existe){
                $alta = $cat_sat_conf_reg_tp_modelo->alta_registro(registro: $cat_sat_conf_reg_tp);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_conf_reg_tp', data: $alta);
                }
                $out->altas[] = $alta;
            }
        }

        return $out;

    }
    private function cat_sat_cve_prod(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:__FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_cve_prod', data: $create);
        }

        $out->create = $create;

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'c_ClaveProdServ';
        $columnas[] = 'Descripción';
        $columnas[] = 'Incluir IVA trasladado';
        $columnas[] = 'Incluir IEPS trasladado';
        $columnas[] = 'Complemento que debe incluir';
        $columnas[] = 'FechaInicioVigencia';
        $columnas[] = 'FechaFinVigencia';
        $columnas[] = 'Estímulo Franja Fronteriza';
        $columnas[] = 'Palabras similares';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'cat_sat'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/cat_sat/instalacion/cat_sat_cve_prod.ods";
        }


        $cat_sat_cve_prod_modelo = new cat_sat_cve_prod(link: $link);

        $n_prods = $cat_sat_cve_prod_modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar cat_sat_cve_prod', data: $n_prods);
        }
        $altas = array();
        if($n_prods !== 52512) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                $row = (array)$row;
                $cat_sat_cve_prod_ins['id'] = trim($row['c_ClaveProdServ']);
                $cat_sat_cve_prod_ins['codigo'] = trim($row['c_ClaveProdServ']);
                $cat_sat_cve_prod_ins['descripcion'] = trim($row['Descripción']);
                $cat_sat_cve_prod_ins['descripcion_select'] = trim($row['c_ClaveProdServ']) . ' ' . trim($row['Descripción']);
                $cat_sat_cve_prod_ins['predeterminado'] = 'inactivo';
                $alta = $cat_sat_cve_prod_modelo->inserta_registro_si_no_existe(registro: $cat_sat_cve_prod_ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $alta);
                }
                $altas[] = $alta->registro_id;
            }
        }
        $out->altas = $altas;


        $adm_menu_descripcion = 'Productos';
        $adm_sistema_descripcion = 'comercial';
        $etiqueta_label = 'Productos';
        $adm_seccion_pertenece_descripcion = 'comercial';
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $out;



    }
    private function cat_sat_division_producto(PDO $link): array|stdClass
    {
        $out = new stdClass();

        $create = (NEW _instalacion($link))->create_table_new(table:__FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_division_producto', data: $create);
        }

        $out->create = $create;


        $foraneas = array();
        $foraneas['cat_sat_tipo_producto_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link: $link))->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_grupo_producto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_grupo_producto', data: $create);
        }

        $out->create = $create;


        $foraneas = array();
        $foraneas['cat_sat_division_producto_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link: $link))->foraneas(foraneas: $foraneas,table:  'cat_sat_grupo_producto');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $out->foraneas_r = $foraneas_r;

        $cat_sat_division_producto_modelo = new cat_sat_division_producto(link: $link);

        $cat_sat_division_productos = array();
        $cat_sat_division_productos[0]['id'] = 84;
        $cat_sat_division_productos[0]['descripcion'] = 'Servicios Financieros y de Seguros';
        $cat_sat_division_productos[0]['codigo'] = '84';
        $cat_sat_division_productos[0]['descripcion_select'] = '84 Servicios Financieros y de Seguros';
        $cat_sat_division_productos[0]['cat_sat_tipo_producto_id'] = '2';


        foreach ($cat_sat_division_productos as $cat_sat_division_producto){
            $existe = $cat_sat_division_producto_modelo->existe_by_id(registro_id: $cat_sat_division_producto['id']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe cat_sat_division_producto', data: $existe);
            }
            $out->existe = $existe;
            if(!$existe){
                $alta = $cat_sat_division_producto_modelo->alta_registro(registro: $cat_sat_division_producto);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_division_producto', data: $alta);
                }
                $out->altas[] = $alta;
            }
        }

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Division de Producto';
        $adm_seccion_pertenece_descripcion = 'cat_sat';
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $out;

    }
    private function cat_sat_forma_pago(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_forma_pago(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';
        $columnas[] = 'descripcion_select';
        $columnas[] = 'predeterminado';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'cat_sat'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/cat_sat/instalacion/".__FUNCTION__.".ods";
        }


        $modelo = new cat_sat_forma_pago(link: $link,aplica_transacciones_base: true);

        $n_rows = $modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_rows', data: $n_rows);
        }
        $altas = array();
        if($n_rows !== 4) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                $row = (array)$row;
                $ins['id'] = trim($row['id']);
                $ins['codigo'] = trim($row['codigo']);
                $ins['descripcion'] = trim($row['descripcion']);
                $ins['descripcion_select'] = trim($row['descripcion_select']);
                $ins['predeterminado'] = $row['predeterminado'];

                $existe_code = $modelo->existe_by_codigo(codigo: trim($row['codigo']) );
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar get_row', data: $existe_code);
                }

                $alta = array();
                if($existe_code ){
                    $get = $modelo->get_data_by_code(codigo: trim($row['codigo']));
                    if(errores::$error){
                        return (new errores())->error(mensaje: 'Error al obtener registro', data: $get);
                    }
                    if((int)$get->cat_sat_forma_pago_id !== (int)$get->cat_sat_forma_pago_codigo){
                        $ins = array();
                        $ins['codigo'] = trim($row['codigo']);
                        $ins['descripcion'] = trim($row['descripcion']);
                        $ins['descripcion_select'] = trim($row['descripcion_select']);
                        $ins['predeterminado'] = $row['predeterminado'];
                        $alta = $modelo->modifica_bd(registro: $ins, id: $get->cat_sat_forma_pago_id);
                        if (errores::$error) {
                            return (new errores())->error(mensaje: 'Error al modificar row', data: $alta);
                        }
                    }

                }
                else {
                    $alta = $modelo->inserta_registro_si_no_existe(registro: $ins);
                    if (errores::$error) {
                        return (new errores())->error(mensaje: 'Error al insertar row', data: $alta);
                    }
                }
                $altas[] = $alta;
            }
        }

        $upds = $this->integra_datos_predeterminadas(id_compare: 99, modelo: $modelo, valida_alfa_code: false);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al actualizar registros', data: $upds);
        }


        $out->altas = $altas;

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Forma Pago';
        $adm_seccion_pertenece_descripcion = 'cat_sat';
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $out;

    }
    private function cat_sat_grupo_producto(PDO $link): array|stdClass
    {
        $out = new stdClass();

        $create = (NEW _instalacion($link))->create_table_new(table:__FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_grupo_producto', data: $create);
        }

        $out->create = $create;


        $foraneas = array();
        $foraneas['cat_sat_division_producto_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link: $link))->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $cat_sat_grupo_producto_modelo = new cat_sat_grupo_producto(link: $link);

        $cat_sat_grupo_productos = array();
        $cat_sat_grupo_productos[0]['id'] = 8411;
        $cat_sat_grupo_productos[0]['descripcion'] = 'Servicios de contabilidad y auditorias';
        $cat_sat_grupo_productos[0]['codigo'] = '8411';
        $cat_sat_grupo_productos[0]['descripcion_select'] = '8411 Servicios De Contabilidad Y Auditorias';
        $cat_sat_grupo_productos[0]['cat_sat_division_producto_id'] = '84';


        foreach ($cat_sat_grupo_productos as $cat_sat_grupo_producto){
            $existe = $cat_sat_grupo_producto_modelo->existe_by_id(registro_id: $cat_sat_grupo_producto['id']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe cat_sat_grupo_producto', data: $existe);
            }
            $out->existe = $existe;
            if(!$existe){
                $alta = $cat_sat_grupo_producto_modelo->alta_registro(registro: $cat_sat_grupo_producto);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_grupo_producto', data: $alta);
                }
                $out->altas[] = $alta;
            }
        }

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Grupos de Producto';
        $adm_seccion_pertenece_descripcion = 'cat_sat';
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        return $out;

    }
    private function cat_sat_metodo_pago(PDO $link): array|stdClass
    {
        $create = $this->_add_cat_sat_metodo_pago(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $out = new stdClass();
        $cat_sat_metodo_modelo = new cat_sat_metodo_pago(link: $link,aplica_transacciones_base: true);

        $cat_sat_metodos_pago = array();
        $cat_sat_metodos_pago[0]['id'] = 1;
        $cat_sat_metodos_pago[0]['descripcion'] = 'Pago en una sola exhibición';
        $cat_sat_metodos_pago[0]['codigo'] = 'PUE';
        $cat_sat_metodos_pago[0]['descripcion_select'] = 'PUE Pago en una sola exhibición';
        $cat_sat_metodos_pago[0]['predeterminado'] = 'activo';


        $cat_sat_metodos_pago[1]['id'] = 2;
        $cat_sat_metodos_pago[1]['descripcion'] = 'Pago en parcialidades o diferido';
        $cat_sat_metodos_pago[1]['codigo'] = 'PPD';
        $cat_sat_metodos_pago[1]['descripcion_select'] = 'PPD Pago en parcialidades o diferido';
        $out->cat_sat_metodos_pago = $cat_sat_metodos_pago;

        foreach ($cat_sat_metodos_pago as $cat_sat_metodo_pago){

            $alta = $cat_sat_metodo_modelo->inserta_registro_si_no_existe(registro: $cat_sat_metodo_pago);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar cat_sat_metodo_pago', data: $alta);
            }
            $out->alta[] = $alta;

        }

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Metodo de Pago';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $out;

    }

    private function cat_sat_moneda(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_moneda(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $out->create = $create;

        $cat_sat_monedas = array();

        $cat_sat_monedas[0]['id'] = 161;
        $cat_sat_monedas[0]['descripcion'] = 'PESO MEXICANO';
        $cat_sat_monedas[0]['codigo'] = 'MXN';
        $cat_sat_monedas[0]['descripcion_select'] = 'MXN PESO MEXICANO';
        $cat_sat_monedas[0]['dp_pais_id'] = 151;


        $cat_sat_monedas[1]['id'] = 163;
        $cat_sat_monedas[1]['descripcion'] = 'Los códigos asignados para las transacciones en que intervenga ninguna moneda';
        $cat_sat_monedas[1]['codigo'] = 'XXX';
        $cat_sat_monedas[1]['descripcion_select'] = 'LOS CóDIGOS ASIGNADOS PARA LAS TRANSACCIONES EN QUE INTERVENGA NINGUNA MONEDA SIN PAIS';
        $cat_sat_monedas[1]['predeterminado'] = 'activo';
        $cat_sat_monedas[1]['dp_pais_id'] = 253;

        $cat_sat_monedas[2]['id'] = 164;
        $cat_sat_monedas[2]['descripcion'] = 'Dolar americano';
        $cat_sat_monedas[2]['codigo'] = 'USD';
        $cat_sat_monedas[2]['descripcion_select'] = 'Dolar americano';
        $cat_sat_monedas[2]['dp_pais_id'] = 66;

        $modelo = new cat_sat_moneda(link: $link,aplica_transacciones_base: true);


        foreach ($cat_sat_monedas as $cat_sat_moneda){
            $existe = $modelo->existe_by_codigo(codigo: $cat_sat_moneda['codigo']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe codigo', data: $existe);
            }
            if($existe){
                $cat_sat_moneda_id_existente = $modelo->get_id_by_codigo(codigo: $cat_sat_moneda['codigo']);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al obtener registro', data: $cat_sat_moneda_id_existente);
                }
                if((int)$cat_sat_moneda_id_existente !== (int)$cat_sat_moneda['id']){
                    $code = $modelo->letras[mt_rand(0,24)];
                    $code .= $modelo->letras[mt_rand(0,24)];
                    $code .= $modelo->letras[mt_rand(0,24)];

                    $upd_moneda['codigo'] = $code;
                    $upd_moneda['predeterminado'] = 'inactivo';
                    $upd = $modelo->modifica_bd(registro: $upd_moneda,id:  $cat_sat_moneda_id_existente);
                    if(errores::$error){
                        return (new errores())->error(mensaje: 'Error al actualizar registro', data: $upd);
                    }
                }
            }
        }

        foreach ($cat_sat_monedas as $cat_sat_moneda){

            $alta = $modelo->inserta_registro_si_no_existe(registro: $cat_sat_moneda);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar cat_sat_unidad', data: $alta);
            }
            $out->alta[] = $alta;

        }

        $upds = $this->integra_datos_predeterminadas(id_compare: 163, modelo: $modelo, valida_alfa_code: true);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al actualizar registros', data: $upds);
        }

        $out->pred = $upds;


        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Monedas';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        return $out;

    }
    private function cat_sat_motivo_cancelacion(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_motivo_cancelacion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';
        $columnas[] = 'status';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'cat_sat'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/cat_sat/instalacion/".__FUNCTION__.".ods";
        }


        $cat_sat_motivo_cancelacion_modelo = new cat_sat_motivo_cancelacion(link: $link);

        $n_motivos = $cat_sat_motivo_cancelacion_modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_motivos', data: $n_motivos);
        }
        $altas = array();
        if($n_motivos !== 4) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                $row = (array)$row;
                $cat_sat_motivo_cancelacion_ins['id'] = trim($row['id']);
                $cat_sat_motivo_cancelacion_ins['codigo'] = trim($row['codigo']);
                $cat_sat_motivo_cancelacion_ins['descripcion'] = trim($row['descripcion']);
                $cat_sat_motivo_cancelacion_ins['descripcion_select'] = trim($row['codigo']) . ' ' . trim($row['descripcion']);
                $cat_sat_motivo_cancelacion_ins['predeterminado'] = 'inactivo';
                $alta = $cat_sat_motivo_cancelacion_modelo->inserta_registro_si_no_existe(registro: $cat_sat_motivo_cancelacion_ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $alta);
                }
                $altas[] = $alta;
            }
        }
        $out->altas = $altas;


        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Motivo Cancelacion';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $out;

    }

    private function cat_sat_periodo(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_periodo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $out->create = $create;


        $adm_menu_descripcion = 'Periodos';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Periodos';
        $adm_seccion_pertenece_descripcion = 'cat_sat';
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }


        return $out;

    }

    private function cat_sat_isn(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_isn(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $out->create = $create;

        return $out;

    }

    private function cat_sat_isr(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_isr(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $out->create = $create;

        return $out;

    }
    private function cat_sat_producto(PDO $link): array|stdClass
    {
        $out = new stdClass();

        $create = (NEW _instalacion($link))->create_table_new(table: 'cat_sat_producto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_division_producto', data: $create);
        }

        $out->create = $create;

        $foraneas = array();
        $foraneas['cat_sat_clase_producto_id'] = new stdClass();
        $foraneas_r = (new _instalacion(link: $link))->foraneas(foraneas: $foraneas,table:  'cat_sat_producto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }

        $cat_sat_producto_modelo = new cat_sat_producto(link: $link);

        $cat_sat_productos = array();
        $cat_sat_productos[0]['id'] = 84111506;
        $cat_sat_productos[0]['descripcion'] = 'Servicios de facturación';
        $cat_sat_productos[0]['codigo'] = '84111506';
        $cat_sat_productos[0]['descripcion_select'] = '84111506 Servicios De Facturación';
        $cat_sat_productos[0]['cat_sat_clase_producto_id'] = '841115';


        $out->cat_sat_productos = $cat_sat_productos;

        foreach ($cat_sat_productos as $cat_sat_producto){
            $existe = $cat_sat_producto_modelo->existe_by_id(registro_id: $cat_sat_producto['id']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe producto', data: $existe);
            }
            $out->existe = $existe;
            if(!$existe){
                $alta = $cat_sat_producto_modelo->alta_registro(registro: $cat_sat_producto);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al insertar producto', data: $alta);
                }
                $out->altas[] = $alta;
            }
        }

        return $out;

    }
    private function cat_sat_tipo_producto(PDO $link): array|stdClass
    {
        $out = new stdClass();

        $create = (new _instalacion($link))->create_table_new(table: 'cat_sat_tipo_producto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_tipo_producto', data: $create);
        }

        $out->create = $create;


        $create = (new _instalacion($link))->create_table_new(table: 'cat_sat_division_producto');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_division_producto', data: $create);
        }

        $out->create = $create;


        $foraneas = array();
        $foraneas['cat_sat_tipo_producto_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link: $link))->foraneas(foraneas: $foraneas,table:  'cat_sat_division_producto');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $cat_sat_tipo_producto_modelo = new cat_sat_tipo_producto(link: $link);

        $cat_sat_tipo_productos = array();
        $cat_sat_tipo_productos[0]['id'] = 1;
        $cat_sat_tipo_productos[0]['descripcion'] = 'Productos';
        $cat_sat_tipo_productos[0]['codigo'] = 'Productos';
        $cat_sat_tipo_productos[0]['descripcion_select'] = 'Productos';


        $cat_sat_tipo_productos[1]['id'] = 2;
        $cat_sat_tipo_productos[1]['descripcion'] = 'Servicios';
        $cat_sat_tipo_productos[1]['codigo'] = 'Servicios';
        $cat_sat_tipo_productos[1]['descripcion_select'] = 'Servicios';
        $out->cat_sat_tipo_productos = $cat_sat_tipo_productos;

        foreach ($cat_sat_tipo_productos as $cat_sat_tipo_producto){
            $existe = $cat_sat_tipo_producto_modelo->existe_by_id(registro_id: $cat_sat_tipo_producto['id']);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al validar si existe tipo de producto', data: $existe);
            }
            $out->existe = $existe;
            if(!$existe){
                $alta = $cat_sat_tipo_producto_modelo->alta_registro(registro: $cat_sat_tipo_producto);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error al insertar tipo de producto', data: $alta);
                }
                $out->altas[] = $alta;
            }
        }

        return $out;

    }

    private function _add_cat_sat_regimen_fiscal(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'cat_sat_regimen_fiscal');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }


        return $out;

    }
    private function cat_sat_regimen_fiscal(PDO $link): array
    {
        $create = $this->_add_cat_sat_regimen_fiscal(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $cat_sat_regimen_fiscal = array();
        $cat_sat_regimen_fiscal[] = array('id'=>"601",'descripcion'=>"General de Ley Personas Morales",
            'codigo'=>"601", 'status'=>"activo",'descripcion_select'=>"601 General de Ley Personas Morales",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"603",'descripcion'=>"Personas Morales con Fines no Lucrativos",
            'codigo'=>"603", 'status'=>"activo",'descripcion_select'=>"603 Personas Morales con Fines no Lucrativos",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"605",
            'descripcion'=>"Sueldos y Salarios e Ingresos Asimilados a Salarios", 'codigo'=>"605", 'status'=>"activo",
            'descripcion_select'=>"605 Sueldos y Salarios e Ingresos Asimilados a Salarios",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"606",'descripcion'=>"Arrendamiento", 'codigo'=>"606",
            'status'=>"activo",'descripcion_select'=>"606 Arrendamiento", 'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"607",'descripcion'=>"Régimen de Enajenación o Adquisición de Bienes",
            'codigo'=>"607", 'status'=>	"activo",
            'descripcion_select'=>"607 Régimen de Enajenación o Adquisición de Bienes", 'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"608",'descripcion'=>"Demás ingresos", 'codigo'=>"608",
            'status'=>	"activo",'descripcion_select'=>"608 Demás ingresos", 'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"610",
            'descripcion'=>"Residentes en el Extranjero sin Establecimiento Permanente en México", 'codigo'=>"610",
            'status'=>"activo",
            'descripcion_select'=>"610 Residentes en el Extranjero sin Establecimiento Permanente en México",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"611",'descripcion'=>"Ingresos por Dividendos (socios y accionistas)",
            'codigo'=>"611", 'status'=>	"activo",
            'descripcion_select'=>"611 Ingresos por Dividendos (socios y accionistas)", 'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"612",
            'descripcion'=>"Personas Físicas con Actividades Empresariales y Profesionales", 'codigo'=>"612",
            'status'=>	"activo",
            'descripcion_select'=>"612 Personas Físicas con Actividades Empresariales y Profesionales",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"614",'descripcion'=>"Ingresos por intereses",
            'codigo'=>"614", 'status'=>	"activo",'descripcion_select'=>"614 Ingresos por intereses",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"615",'descripcion'=>"Régimen de los ingresos por obtención de premios",
            'codigo'=>"615", 'status'=>	"activo",
            'descripcion_select'=>"615 Régimen de los ingresos por obtención de premios", 'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"616",'descripcion'=>"Sin obligaciones fiscales",
            'codigo'=>"616"	, 'status'=>"activo",'descripcion_select'=>"616 Sin obligaciones fiscales",
            'predeterminado'=>"activo");
        $cat_sat_regimen_fiscal[] = array('id'=>"620",
            'descripcion'=>"Sociedades Cooperativas de Producción que optan por diferir sus ingresos",
            'codigo'=>"620", 'status'=>	"activo",
            'descripcion_select'=>"620 Sociedades Cooperativas de Producción que optan por diferir sus ingresos",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"621",'descripcion'=>"Incorporación Fiscal",
            'codigo'=>"621", 'status'=>	"activo",'descripcion_select'=>"621 Incorporación Fiscal",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"622",
            'descripcion'=>"Actividades Agrícolas	 Ganaderas	 Silvícolas y Pesqueras",
            'codigo'=>"622", 'status'=>	"activo",
            'descripcion_select'=>"622 Actividades Agrícolas	 Ganaderas	 Silvícolas y Pesqueras",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"623",'descripcion'=>"Opcional para Grupos de Sociedades",
            'codigo'=>"623", 'status'=>	"activo",'descripcion_select'=>"623 Opcional para Grupos de Sociedades",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"624",'descripcion'=>"Coordinados",
            'codigo'=>"624", 'status'=>	"activo",'descripcion_select'=>"624 Coordinados",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"625",
            'descripcion'=>"Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas",
            'codigo'=>"625", 'status'=>	"activo",
            'descripcion_select'=>
                "625 Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"626",'descripcion'=>"Régimen Simplificado de Confianza",
            'codigo'=>"626", 'status'=>	"activo",'descripcion_select'=>"626 Régimen Simplificado de Confianza",
            'predeterminado'=>"inactivo");
        $cat_sat_regimen_fiscal[] = array('id'=>"999",'descripcion'=>"POR DEFINIR", 'codigo'=>"999",
            'status'=>	"activo",'descripcion_select'=>"999 POR DEFINIR", 'predeterminado'=>"inactivo");

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Regimen Fiscal';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $cat_sat_regimen_fiscal;

    }
    private function cat_sat_tipo_persona(PDO $link): array
    {
        $create = $this->_add_cat_sat_tipo_persona(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $cat_sat_tipo_persona = array();
        $cat_sat_tipo_persona[0]['id'] = 4;
        $cat_sat_tipo_persona[0]['descripcion'] = 'PERSONA MORAL';
        $cat_sat_tipo_persona[0]['codigo'] = 'PM';
        $cat_sat_tipo_persona[0]['predeterminado'] = 'inactivo';


        $cat_sat_tipo_persona[1]['id'] = 5;
        $cat_sat_tipo_persona[1]['descripcion'] = 'PERSONA FISICA';
        $cat_sat_tipo_persona[1]['codigo'] = 'PF';
        $cat_sat_tipo_persona[1]['predeterminado'] = 'inactivo';


        $cat_sat_tipo_persona[2]['id'] = 6;
        $cat_sat_tipo_persona[2]['descripcion'] = 'POR DEFINIR';
        $cat_sat_tipo_persona[2]['codigo'] = 'PD';
        $cat_sat_tipo_persona[2]['predeterminado'] = 'activo';

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Tipo Persona';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $cat_sat_tipo_persona;

    }

    private function cat_sat_tipo_relacion(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_tipo_relacion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'cat_sat'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/cat_sat/instalacion/".__FUNCTION__.".ods";
        }


        $cat_sat_tipo_relacion_modelo = new cat_sat_tipo_relacion(link: $link);

        $n_motivos = $cat_sat_tipo_relacion_modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_motivos', data: $n_motivos);
        }
        $altas = array();
        if($n_motivos !== 9) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                $row = (array)$row;
                $cat_sat_tipo_relacion_ins['id'] = trim($row['id']);
                $cat_sat_tipo_relacion_ins['codigo'] = trim($row['codigo']);
                $cat_sat_tipo_relacion_ins['descripcion'] = trim($row['descripcion']);
                $cat_sat_tipo_relacion_ins['descripcion_select'] = trim($row['codigo']) . ' ' . trim($row['descripcion']);
                $cat_sat_tipo_relacion_ins['predeterminado'] = 'inactivo';
                $alta = $cat_sat_tipo_relacion_modelo->inserta_registro_si_no_existe(registro: $cat_sat_tipo_relacion_ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $alta);
                }
                $altas[] = $alta;
            }
        }
        $out->altas = $altas;


        return $out;

    }

    private function cat_sat_actividad_economica(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_actividad_economica(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;

        $adm_menu_descripcion = 'Generales';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Actividades Economicas';
        $adm_seccion_pertenece_descripcion = 'cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';
        $adm_namespace_name = 'gamboamartin/cat_sat';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__, adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion, etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }




        return $out;

    }

    private function cat_sat_tipo_nomina(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_tipo_nomina(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;


        return $out;

    }

    private function cat_sat_tipo_contrato_nom(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_tipo_contrato_nom(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;


        return $out;

    }

    private function cat_sat_tipo_impuesto(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_tipo_impuesto(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;


        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'cat_sat'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/cat_sat/instalacion/".__FUNCTION__.".ods";
        }


        $modelo = new cat_sat_tipo_impuesto(link: $link);

        $n_rows = $modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_rows', data: $n_rows);
        }
        $altas = array();
        if($n_rows !== 8) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                $row = (array)$row;
                $ins['id'] = trim($row['id']);
                $ins['codigo'] = trim($row['codigo']);
                $ins['descripcion'] = trim($row['descripcion']);
                $ins['descripcion_select'] = trim($row['codigo']) . ' ' . trim($row['descripcion']);
                $ins['predeterminado'] = 'inactivo';
                $alta = $modelo->inserta_registro_si_no_existe(registro: $ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $alta);
                }
                $altas[] = $alta;
            }
        }
        $create->altas = $altas;




        return $out;

    }
    private function cat_sat_retencion_conf(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_retencion_conf(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['cat_sat_factor_id'] = new stdClass();
        $foraneas['cat_sat_tipo_factor_id'] = new stdClass();
        $foraneas['cat_sat_tipo_impuesto_id'] = new stdClass();
        $foraneas['cat_sat_conf_imps_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_retencion_conf');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;




        return $out;

    }

    private function cat_sat_traslado_conf(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_traslado_conf(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;

        $foraneas = array();
        $foraneas['cat_sat_factor_id'] = new stdClass();
        $foraneas['cat_sat_tipo_factor_id'] = new stdClass();
        $foraneas['cat_sat_tipo_impuesto_id'] = new stdClass();
        $foraneas['cat_sat_conf_imps_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'cat_sat_traslado_conf');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;




        return $out;

    }
    private function cat_sat_unidad(PDO $link): array|stdClass
    {
        $create = $this->_add_cat_sat_unidad(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $out = new stdClass();
        $cat_sat_unidad_modelo = new cat_sat_unidad(link: $link);

        $cat_sat_unidades = array();
        $cat_sat_unidades[0]['id'] = 241;
        $cat_sat_unidades[0]['descripcion'] = 'Actividad';
        $cat_sat_unidades[0]['codigo'] = 'ACT';
        $cat_sat_unidades[0]['descripcion_select'] = 'ACT Actividad';


        foreach ($cat_sat_unidades as $cat_sat_unidad){

            $alta = $cat_sat_unidad_modelo->inserta_registro_si_no_existe(registro: $cat_sat_unidad);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar cat_sat_unidad', data: $alta);
            }
            $out->alta[] = $alta;

        }

        return $out;

    }
    private function data(PDO $link): stdClass|array
    {
        $this->data = new stdClass();

        $cat_sat_tipo_persona = $this->cat_sat_tipo_persona(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al obtener cat_sat_tipo_persona', data: $cat_sat_tipo_persona);
        }

        $this->data->cat_sat_tipo_persona = $cat_sat_tipo_persona;

        $cat_sat_regimen_fiscal = $this->cat_sat_regimen_fiscal(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al obtener cat_sat_regimen_fiscal', data: $cat_sat_regimen_fiscal);
        }

        $this->data->cat_sat_regimen_fiscal = $cat_sat_regimen_fiscal;


        return $this->data;

    }

    private function cat_sat_conf_imps_tipo_pers(PDO $link): array|stdClass
    {
        $create = $this->_add_cat_sat_conf_imps_tipo_pers(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';
        $columnas[] = 'cat_sat_conf_reg_tp_id';
        $columnas[] = 'cat_sat_conf_imps_id';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'cat_sat'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/cat_sat/instalacion/".__FUNCTION__.".ods";
        }


        $modelo = new cat_sat_conf_imps_tipo_pers(link: $link,aplica_transacciones_base: true);

        $n_rows = $modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_rows', data: $n_rows);
        }
        $altas = array();
        if($n_rows !== 8) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                $row = (array)$row;
                $ins['id'] = trim($row['id']);
                $ins['codigo'] = trim($row['codigo']);
                $ins['descripcion'] = trim($row['descripcion']);
                $ins['descripcion_select'] = trim($row['codigo']) . ' ' . trim($row['descripcion']);
                $ins['predeterminado'] = 'inactivo';
                $ins['cat_sat_conf_reg_tp_id'] = $row['cat_sat_conf_reg_tp_id'];
                $ins['cat_sat_conf_imps_id'] = $row['cat_sat_conf_imps_id'];
                $alta = $modelo->inserta_registro_si_no_existe(registro: $ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $alta);
                }
                $altas[] = $alta;
            }
        }
        $create->altas = $altas;



        return $create;

    }

    private function code_3_letras(modelo $modelo): string
    {
        $code = $modelo->letras[mt_rand(0,24)];
        $code .= $modelo->letras[mt_rand(0,24)];
        $code .= $modelo->letras[mt_rand(0,24)];
        return $code;

    }

    private function _add_cat_sat_factor(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_factor');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_factor', data: $create);
        }

        $campos = new stdClass();
        $campos->factor = new stdClass();
        $campos->factor->tipo_dato = 'DOUBLE';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'cat_sat_factor');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }

        return $out;
    }

    private function cat_sat_factor(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_factor(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_factor', data: $create);
        }

        $out->create = $create;
        return $out;

    }

    private function _add_cat_sat_tipo_factor(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_tipo_factor');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_tipo_factor', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function cat_sat_tipo_factor(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_tipo_factor(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;


        return $out;

    }

    private function _add_cat_sat_periodicidad(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_periodicidad');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_motivo_cancelacion', data: $create);
        }

        $out->create = $create;

        $columnas = new stdClass();

        $columnas->predeterminado = new stdClass();
        $columnas->predeterminado->default =  'inactivo';

        $add_colums = (new _instalacion(link: $link))->add_columns(campos: $columnas,table:  'cat_sat_periodicidad');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al agregar columnas', data:  $add_colums);
        }
        $out->add_colums_base = $add_colums;



        return $out;
    }

    private function _add_cat_sat_periodicidad_pago_nom(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_periodicidad_pago_nom');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_periodicidad_pago_nom', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function cat_sat_periodicidad(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_periodicidad(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $out->create = $create;

        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'codigo';
        $columnas[] = 'descripcion';
        $columnas[] = 'status';
        $columnas[] = 'descripcion_select';
        $columnas[] = 'alias';
        $columnas[] = 'codigo_bis';
        $columnas[] = 'predeterminado';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'cat_sat'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/cat_sat/instalacion/".__FUNCTION__.".ods";
        }


        $modelo = new cat_sat_periodicidad(link: $link);

        $n_rows = $modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_rows', data: $n_rows);
        }
        $altas = array();
        if($n_rows !== 11) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                $row = (array)$row;
                $ins['id'] = trim($row['id']);
                $ins['codigo'] = trim($row['codigo']);
                $ins['descripcion'] = trim($row['descripcion']);
                $ins['status'] = 'activo';
                $ins['descripcion_select'] = trim($row['descripcion_select']) ;
                $ins['alias'] = trim($row['alias']) ;
                $ins['predeterminado'] = 'inactivo';

                $ins['codigo_bis'] = trim($row['codigo_bis']) ;
                $alta = $modelo->inserta_registro_si_no_existe(registro: $ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $alta);
                }
            }
        }

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Periodicidad';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $out;

    }

    private function cat_sat_periodicidad_pago_nom(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_periodicidad_pago_nom(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }
        $out->create = $create;


        return $out;

    }

    private function _add_cat_sat_obj_imp(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_obj_imp');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_factor', data: $create);
        }

        $campos = new stdClass();
        $campos->exento = new stdClass();
        $campos->exento->default = 'inactivo';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'cat_sat_obj_imp');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }

        return $out;
    }

    private function cat_sat_obj_imp(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_obj_imp(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_obj_imp', data: $create);
        }

        $out->create = $create;


        $importador = new Importador();
        $columnas = array();
        $columnas[] = 'id';
        $columnas[] = 'descripcion';
        $columnas[] = 'codigo';

        $ruta = (new generales())->path_base."instalacion/".__FUNCTION__.'.ods';

        if((new generales())->sistema !== 'cat_sat'){
            $ruta = (new generales())->path_base;
            $ruta .= "vendor/gamboa.martin/cat_sat/instalacion/".__FUNCTION__.".ods";
        }


        $modelo = new cat_sat_obj_imp(link: $link);

        $n_rows = $modelo->cuenta();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar n_rows', data: $n_rows);
        }
        $altas = array();
        if($n_rows !== 3) {

            $data = $importador->leer_registros(ruta_absoluta: $ruta, columnas: $columnas);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al leer cat_sat_cve_prod', data: $data);
            }

            foreach ($data as $row) {
                $row = (array)$row;
                $ins['id'] = trim($row['id']);
                $ins['codigo'] = trim($row['codigo']);
                $ins['descripcion'] = trim($row['descripcion']);
                $ins['descripcion_select'] = trim($row['codigo']) . ' ' . trim($row['descripcion']);
                $ins['predeterminado'] = 'inactivo';
                $alta = $modelo->inserta_registro_si_no_existe(registro: $ins);
                if (errores::$error) {
                    return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $alta);
                }
            }
        }

        return $out;

    }

    private function _add_cat_sat_uso_cfdi(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_uso_cfdi');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_moneda', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function cat_sat_uso_cfdi(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_uso_cfdi(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;


        $cat_sat_uso_cfdi = array();
        $cat_sat_uso_cfdi['id'] = '22';
        $cat_sat_uso_cfdi['codigo'] = 'S01';
        $cat_sat_uso_cfdi['descripcion_select'] = 'S01 Sin efectos fiscales.';
        $cat_sat_uso_cfdi['descripcion'] = 'Sin efectos fiscales.  ';
        $cat_sat_uso_cfdi['predeterminado'] = 'activo';

        $cat_sat_usos_cfdi[0] = $cat_sat_uso_cfdi;


        foreach ($cat_sat_usos_cfdi as $cat_sat_uso_cfdi){
            $alta = (new cat_sat_uso_cfdi(link: $link))->inserta_registro_si_no_existe(registro: $cat_sat_uso_cfdi);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            }
            $out->altas[] = $alta;

        }

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Uso de CFDI';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        return $out;

    }

    private function cat_sat_tipo_regimen_nom(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_tipo_regimen_nom(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;



        return $out;

    }

    private function cat_sat_tipo_jornada_nom(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_tipo_jornada_nom(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;



        return $out;

    }

    private function cat_sat_tipo_de_comprobante(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = $this->_add_cat_sat_tipo_de_comprobante(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar create', data: $create);
        }
        $out->create = $create;

        $cat_sat_tipo_de_comprobante = array();
        $cat_sat_tipo_de_comprobante['id'] = '1';
        $cat_sat_tipo_de_comprobante['codigo'] = 'I';
        $cat_sat_tipo_de_comprobante['descripcion_select'] = 'I Ingreso';
        $cat_sat_tipo_de_comprobante['descripcion'] = 'Ingreso';
        $cat_sat_tipo_de_comprobante['predeterminado'] = 'activo';

        $cat_sat_tipos_de_comprobante[0] = $cat_sat_tipo_de_comprobante;


        foreach ($cat_sat_tipos_de_comprobante as $cat_sat_tipo_de_comprobante){
            $alta = (new cat_sat_tipo_de_comprobante(link: $link))->inserta_registro_si_no_existe(
                registro: $cat_sat_tipo_de_comprobante);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            }
            $out->altas[] = $alta;

        }

        $adm_menu_descripcion = 'SAT';
        $adm_sistema_descripcion = 'cat_sat';
        $etiqueta_label = 'Tipo de Comprobante';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/cat_sat';
        $adm_namespace_descripcion = 'gamboa.martin/cat_sat';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        return $out;

    }

    private function _add_cat_sat_tipo_de_comprobante(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_tipo_de_comprobante');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_tipo_de_comprobante', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_tipo_regimen_nom(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_tipo_regimen_nom');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_tipo_de_comprobante', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    private function _add_cat_sat_tipo_jornada_nom(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (NEW _instalacion($link))->create_table_new(table:'cat_sat_tipo_jornada_nom');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al crear cat_sat_tipo_jornada_nom', data: $create);
        }

        $out->create = $create;

        return $out;
    }

    final public function instala(PDO $link): array|stdClass
    {

        $out = new stdClass();

        $cat_sat_tipo_regimen_nom = $this->cat_sat_tipo_regimen_nom(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_regimen_nom', data: $cat_sat_tipo_regimen_nom);
        }
        $out->cat_sat_tipo_regimen_nom = $cat_sat_tipo_regimen_nom;

        $cat_sat_tipo_jornada_nom = $this->cat_sat_tipo_jornada_nom(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_jornada_nom', data: $cat_sat_tipo_jornada_nom);
        }
        $out->cat_sat_tipo_jornada_nom = $cat_sat_tipo_jornada_nom;

        $cat_sat_uso_cfdi = $this->cat_sat_uso_cfdi(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_uso_cfdi', data: $cat_sat_uso_cfdi);
        }
        $out->cat_sat_uso_cfdi = $cat_sat_uso_cfdi;

        $cat_sat_actividad_economica = $this->cat_sat_actividad_economica(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_actividad_economica',
                data: $cat_sat_actividad_economica);
        }
        $out->cat_sat_actividad_economica = $cat_sat_actividad_economica;

        $cat_sat_tipo_nomina = $this->cat_sat_tipo_nomina(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_nomina',
                data: $cat_sat_tipo_nomina);
        }
        $out->cat_sat_tipo_nomina = $cat_sat_tipo_nomina;

        $cat_sat_tipo_de_comprobante = $this->cat_sat_tipo_de_comprobante(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_de_comprobante', data: $cat_sat_tipo_de_comprobante);
        }
        $out->cat_sat_tipo_de_comprobante = $cat_sat_tipo_de_comprobante;

        $cat_sat_motivo_cancelacion = $this->cat_sat_motivo_cancelacion(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_motivo_cancelacion',
                data: $cat_sat_motivo_cancelacion);
        }
        $out->cat_sat_motivo_cancelacion = $cat_sat_motivo_cancelacion;

        $cat_sat_tipo_factor = $this->cat_sat_tipo_factor(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_factor',
                data: $cat_sat_motivo_cancelacion);
        }
        $out->cat_sat_tipo_factor = $cat_sat_tipo_factor;


        $cat_sat_periodicidad = $this->cat_sat_periodicidad(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_periodicidad', data: $cat_sat_periodicidad);
        }
        $out->cat_sat_periodicidad = $cat_sat_periodicidad;

        $cat_sat_periodicidad_pago_nom = $this->cat_sat_periodicidad_pago_nom(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar $cat_sat_periodicidad_pago_nom', data: $cat_sat_periodicidad_pago_nom);
        }
        $out->cat_sat_periodicidad_pago_nom = $cat_sat_periodicidad_pago_nom;



        $cat_sat_forma_pago = $this->cat_sat_forma_pago(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_forma_pago',
                data: $cat_sat_forma_pago);
        }
        $out->cat_sat_motivo_cancelacion = $cat_sat_motivo_cancelacion;

        $cat_sat_tipo_contrato_nom = $this->cat_sat_tipo_contrato_nom(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_contrato_nom',
                data: $cat_sat_tipo_contrato_nom);
        }
        $out->cat_sat_motivo_cancelacion = $cat_sat_motivo_cancelacion;

        $cat_sat_tipo_impuesto = $this->cat_sat_tipo_impuesto(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_impuesto',
                data: $cat_sat_tipo_impuesto);
        }
        $out->cat_sat_tipo_impuesto = $cat_sat_tipo_impuesto;

        $cat_sat_tipo_relacion = $this->cat_sat_tipo_relacion(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_relacion',
                data: $cat_sat_tipo_relacion);
        }
        $out->cat_sat_tipo_relacion = $cat_sat_tipo_relacion;

        $cat_sat_unidad = $this->cat_sat_unidad(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_unidad', data: $cat_sat_unidad);
        }
        $out->cat_sat_unidad = $cat_sat_unidad;

        $cat_sat_cve_prod = $this->cat_sat_conf_imps(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $cat_sat_cve_prod);
        }
        $out->cat_sat_cve_prod = $cat_sat_cve_prod;

        $cat_sat_cve_prod = $this->cat_sat_cve_prod(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_cve_prod', data: $cat_sat_cve_prod);
        }
        $out->cat_sat_cve_prod = $cat_sat_cve_prod;


        $cat_sat_factor = $this->cat_sat_factor(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_factor',
                data: $cat_sat_factor);
        }


        $cat_sat_obj_imp = $this->cat_sat_obj_imp(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_obj_imp',
                data: $cat_sat_obj_imp);
        }
        $out->cat_sat_obj_imp = $cat_sat_obj_imp;


        $cat_sat_metodo_pago = $this->cat_sat_metodo_pago(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al integrar cat_sat_metodo_pago', data: $cat_sat_metodo_pago);
        }
        $out->cat_sat_metodo_pago = $cat_sat_metodo_pago;

        $cat_sat_tipo_persona_modelo = new cat_sat_tipo_persona(link: $link);

        $out->cat_sat_tipo_persona = new stdClass();
        $cat_sat_tipo_persona_alta = $cat_sat_tipo_persona_modelo->inserta_registros_no_existentes_id(
            registros: $this->data->cat_sat_tipo_persona);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_persona', data: $cat_sat_tipo_persona_alta);
        }
        $out->cat_sat_tipo_persona->alta = $cat_sat_tipo_persona_alta;

        $cat_sat_regimen_fiscal_modelo = new cat_sat_regimen_fiscal(link: $link,aplica_transacciones_base: true);
        $out->cat_sat_regimen_fiscal = new stdClass();
        $cat_sat_regimen_fiscal = $cat_sat_regimen_fiscal_modelo->inserta_registros_no_existentes_id(
            registros: $this->data->cat_sat_regimen_fiscal);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_regimen_fiscal', data: $cat_sat_regimen_fiscal);
        }
        $out->cat_sat_regimen_fiscal->alta = $cat_sat_regimen_fiscal;

        $cat_sat_tipo_producto = $this->cat_sat_tipo_producto(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_tipo_producto', data: $cat_sat_tipo_producto);
        }
        $out->cat_sat_tipo_producto = $cat_sat_tipo_producto;

        $cat_sat_division_producto = $this->cat_sat_division_producto(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_division_producto', data: $cat_sat_division_producto);
        }
        $out->cat_sat_division_producto = $cat_sat_division_producto;

        $cat_sat_grupo_producto = $this->cat_sat_grupo_producto(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_grupo_producto', data: $cat_sat_grupo_producto);
        }
        $out->cat_sat_grupo_producto = $cat_sat_grupo_producto;

        $cat_sat_clase_producto = $this->cat_sat_clase_producto(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_clase_producto', data: $cat_sat_clase_producto);
        }
        $out->cat_sat_clase_producto = $cat_sat_clase_producto;

        $cat_sat_producto = $this->cat_sat_producto(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_producto', data: $cat_sat_producto);
        }
        $out->cat_sat_producto = $cat_sat_producto;

        $cat_sat_conf_reg_tp = $this->cat_sat_conf_reg_tp(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_conf_reg_tp', data: $cat_sat_conf_reg_tp);
        }
        $out->cat_sat_conf_reg_tp = $cat_sat_conf_reg_tp;


        $cat_sat_conf_imps_tipo_pers = $this->cat_sat_conf_imps_tipo_pers(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_conf_imps_tipo_pers', data: $cat_sat_conf_imps_tipo_pers);
        }
        $out->cat_sat_conf_imps_tipo_pers = $cat_sat_conf_imps_tipo_pers;


        $cat_sat_retencion_conf = $this->cat_sat_retencion_conf(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_retencion_conf', data: $cat_sat_retencion_conf);
        }
        $out->cat_sat_retencion_conf = $cat_sat_retencion_conf;

        $cat_sat_traslado_conf = $this->cat_sat_traslado_conf(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_traslado_conf', data: $cat_sat_traslado_conf);
        }
        $out->cat_sat_traslado_conf = $cat_sat_traslado_conf;

        $cat_sat_moneda = $this->cat_sat_moneda(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_moneda', data: $cat_sat_moneda);
        }
        $out->cat_sat_moneda = $cat_sat_moneda;

        $cat_sat_periodo = $this->cat_sat_periodo(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_periodo', data: $cat_sat_periodo);
        }
        $out->cat_sat_periodo = $cat_sat_periodo;

        $cat_sat_isn = $this->cat_sat_isn(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_isn', data: $cat_sat_isn);
        }
        $out->cat_sat_isn = $cat_sat_isn;

        $cat_sat_isr = $this->cat_sat_isr(link: $link);
        if (errores::$error) {
            return (new errores())->error(mensaje: 'Error al insertar cat_sat_isr', data: $cat_sat_isr);
        }
        $out->cat_sat_isr = $cat_sat_isr;

        return $out;

    }

    private function integra_datos_predeterminadas(int $id_compare, modelo $modelo, bool $valida_alfa_code): array
    {
        $upds = array();
        $filtro = array();
        $filtro[$modelo->tabla.'.predeterminado'] = 'activo';
        $cuenta = $modelo->cuenta(filtro: $filtro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al contar predeterminados', data: $cuenta);
        }

        if($cuenta > 1){
            $upds = $this->actualiza_datos_predeterminadas(id_compare: $id_compare, modelo: $modelo, valida_alfa_code: $valida_alfa_code);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al actualizar registros', data: $upds);
            }
        }
        if($cuenta === 0){

            $upd_row['predeterminado'] = 'activo';
            $upd = $modelo->modifica_bd(registro: $upd_row,id:  $id_compare);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al actualizar registros', data: $upds);
            }
            $upds[] = $upd;

        }
        return $upds;

    }

    private function upd_row(array $registro_actual, modelo $modelo, bool $valida_alfa_code): array|stdClass
    {
        $upd_row = $this->upd_row_code(registro_actual: $registro_actual,modelo:  $modelo, valida_alfa_code: $valida_alfa_code);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error maquetar upd_row code', data: $upd_row);
        }

        $upd_row['predeterminado'] = 'inactivo';
        $upd = $modelo->modifica_bd(registro: $upd_row,id:  $registro_actual[$modelo->key_id]);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al actualizar registro', data: $upd);
        }
        return $upd;

    }

    private function upd_row_code(array $registro_actual, modelo $modelo, bool $valida_alfa_code): array
    {
        $upd_row = array();
        if(is_numeric($registro_actual[$modelo->tabla.'_codigo']) && $valida_alfa_code){
            $code = $this->code_3_letras(modelo: $modelo);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar code', data: $code);
            }
            $upd_row['codigo'] = $code;
        }
        return $upd_row;

    }

    private function upd_registro_predeterminado(int $id_compare, array $registro_actual, modelo $modelo, bool $valida_alfa_code): array|stdClass
    {
        $upd = new stdClass();
        if((int)$registro_actual[$modelo->key_id] !== $id_compare){

            $upd = $this->upd_row(registro_actual: $registro_actual,modelo:  $modelo, valida_alfa_code: $valida_alfa_code);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al actualizar registro', data: $upd);
            }
        }
        return $upd;

    }

    private function upd_registros(int $id_compare, array $registros_actuales, modelo $modelo, bool $valida_alfa_code): array
    {
        $upds = array();
        foreach ($registros_actuales as $registro_actual){
            $upd = $this->upd_registro_predeterminado(id_compare: $id_compare, registro_actual: $registro_actual,
                modelo: $modelo, valida_alfa_code: $valida_alfa_code);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al actualizar registro', data: $upd);
            }
            $upds[] = $upd;
        }
        return $upds;

    }

}
