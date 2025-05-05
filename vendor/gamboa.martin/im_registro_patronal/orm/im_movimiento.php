<?php

namespace gamboamartin\im_registro_patronal\models;

use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\empleado\models\em_empleado;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use gamboamartin\xml_cfdi_4\validacion;
use PDO;
use stdClass;

class im_movimiento extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = "im_movimiento";
        $columnas = array($tabla => false, 'em_empleado' => $tabla, 'em_registro_patronal' => $tabla,
            'fc_csd' => 'em_registro_patronal', 'org_sucursal' => 'fc_csd',
            'org_empresa' => 'org_sucursal', 'im_tipo_movimiento' => $tabla,);
        $campos_obligatorios = array('em_registro_patronal_id', 'im_tipo_movimiento_id', 'em_empleado_id', 'fecha');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        if (!isset($this->registro['codigo'])) {
            $codigo = $this->get_codigo_aleatorio();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al generar codigo', data: $codigo);
            }
            $this->registro['codigo'] = $codigo;
        }

        if (!isset($this->registro['descripcion'])) {
            $this->registro['descripcion'] = $this->registro['em_empleado_id'];
            $this->registro['descripcion'] .= $this->registro['em_registro_patronal_id'];
            $this->registro['descripcion'] .= $this->registro['im_tipo_movimiento_id'];
        }

        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar movimiento', data: $r_alta_bd);
        }

        $modifica = $this->modifica_empleado(registro_emp: $this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al modificar empleado', data: $modifica);
        }

        return $r_alta_bd;
    }

    private function data_filtro_movto(int $em_empleado_id, string $fecha): array|stdClass
    {
        $filtro['em_empleado.id'] = $em_empleado_id;
        $order['im_movimiento.fecha'] = 'DESC';

        $filtro_extra = $this->filtro_extra_fecha(fecha: $fecha);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener filtro', data: $filtro_extra);
        }

        $data = new stdClass();
        $data->filtro = $filtro;
        $data->order = $order;
        $data->filtro_extra = $filtro_extra;
        return $data;
    }

    /**
     * Genera un filtro fecha para movimiento
     * @param string $fecha Fecha de movimiento
     * @return array
     * @version 0.28.4
     */
    private function filtro_extra_fecha(string $fecha): array
    {
        $valida = $this->validacion->valida_fecha(fecha: $fecha);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar fecha', data: $valida);
        }
        $filtro_extra[0]['im_movimiento.fecha']['valor'] = $fecha;
        $filtro_extra[0]['im_movimiento.fecha']['operador'] = '>=';
        $filtro_extra[0]['im_movimiento.fecha']['comparacion'] = 'AND';

        return $filtro_extra;
    }

    public function filtro_movimiento_fecha(int $em_empleado_id, string $fecha): stdClass|array
    {
        if ($em_empleado_id <= -1) {
            return $this->error->error(mensaje: 'Error id del empleado no puede ser menor a uno', data: $em_empleado_id);
        }

        $valida = (new validacion())->valida_fecha(fecha: $fecha);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error: ingrese una fecha valida', data: $valida);
        }


        $data = $this->data_filtro_movto(em_empleado_id: $em_empleado_id, fecha: $fecha);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos para filtro', data: $data);
        }

        $im_movimiento = $this->obten_datos_ultimo_registro(filtro: $data->filtro, filtro_extra: $data->filtro_extra,
            order: $data->order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener el movimiento del empleado', data: $im_movimiento);
        }

        if (count($im_movimiento) === 0) {
            return $this->error->error(mensaje: 'Error no hay registros para el empleado', data: $em_empleado_id);
        }

        return $im_movimiento;
    }

    public function get_ultimo_movimiento_empleado(int $em_empleado_id): stdClass|array
    {
        if ($em_empleado_id <= -1) {
            return $this->error->error(mensaje: 'Error id del empleado no puede ser menor a uno', data: $em_empleado_id);
        }

        $filtro['em_empleado.id'] = $em_empleado_id;
        $order['im_movimiento.fecha'] = 'DESC';
        $im_movimiento = $this->obten_datos_ultimo_registro(filtro: $filtro, order: $order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener el movimiento del empleado', data: $im_movimiento);
        }

        if (count($im_movimiento) === 0) {
            return $this->error->error(mensaje: 'Error no hay registros para el empleado', data: $im_movimiento);
        }

        return $im_movimiento;
    }

    public function calcula_riesgo_de_trabajo(float $em_clase_riesgo_factor, float $n_dias_trabajados,
                                              float $salario_base_cotizacion): float|array
    {
        if ($em_clase_riesgo_factor <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $em_clase_riesgo_factor);
        }
        if ($salario_base_cotizacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_cotizacion);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $salario_base_cotizacion * $n_dias_trabajados;
        $res = $cuota_diaria * $em_clase_riesgo_factor;
        $total_cuota = $res / 100;

        return round($total_cuota, 2);
    }

    public function calcula_enf_mat_cuota_fija(float $factor_cuota_fija, float $n_dias_trabajados,
                                               float $uma): float|array
    {
        if ($factor_cuota_fija <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_cuota_fija);
        }
        if ($uma <= 0.0) {
            return $this->error->error("Error uma debe ser menor a 0",
                $uma);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $factor_cuota_fija * $uma;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;
        $total_cuota = $total_cuota / 100;

        return round($total_cuota, 2);
    }

    public function calcula_enf_mat_cuota_adicional(float $factor_cuota_adicional, float $n_dias_trabajados,
                                                    float $salario_base_cotizacion, float $uma): float|array
    {

        if ($salario_base_cotizacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_cotizacion);
        }
        if ($uma <= 0.0) {
            return $this->error->error("Error uma debe ser menor a 0", $uma);
        }
        if ($factor_cuota_adicional <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_cuota_adicional);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0", $n_dias_trabajados);
        }

        $excedente = 0;
        $tres_umas = $uma * 3;
        if ($salario_base_cotizacion > $tres_umas) {
            $excedente = $salario_base_cotizacion - $tres_umas;
        }

        $cuota_diaria = $factor_cuota_adicional * $excedente;
        $cuota_diaria = $cuota_diaria / 100;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;

        return round($total_cuota, 2);
    }

    public function calcula_enf_mat_gastos_medicos(float $factor_gastos_medicos, float $n_dias_trabajados,
                                                   float $salario_base_cotizacion): float|array
    {
        if ($factor_gastos_medicos <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_gastos_medicos);
        }
        if ($salario_base_cotizacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_cotizacion);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $factor_gastos_medicos * $salario_base_cotizacion;
        $cuota_diaria = $cuota_diaria / 100;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;

        return round($total_cuota, 2);
    }

    public function calcula_enf_mat_pres_dinero(float $factor_pres_dineros, float $n_dias_trabajados,
                                                float $salario_base_cotizacion): float|array
    {
        if ($factor_pres_dineros <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_pres_dineros);
        }
        if ($salario_base_cotizacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_cotizacion);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $factor_pres_dineros * $salario_base_cotizacion;
        $cuota_diaria = $cuota_diaria / 100;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;

        return round($total_cuota, 2);
    }

    public function calcula_invalidez_vida(float $factor_invalidez_vida, float $n_dias_trabajados,
                                           float $salario_base_cotizacion): float|array
    {
        if ($factor_invalidez_vida <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_invalidez_vida);
        }
        if ($salario_base_cotizacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_cotizacion);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $factor_invalidez_vida * $salario_base_cotizacion;
        $cuota_diaria = $cuota_diaria / 100;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;

        return round($total_cuota, 2);
    }

    public function calcula_guarderia_prestaciones_sociales(float $factor_pres_sociales, float $n_dias_trabajados,
                                                            float $salario_base_cotizacion): float|array
    {
        if ($factor_pres_sociales <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_pres_sociales);
        }
        if ($salario_base_cotizacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_cotizacion);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $factor_pres_sociales * $salario_base_cotizacion;
        $cuota_diaria = $cuota_diaria / 100;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;

        return round($total_cuota, 2);
    }

    public function calcula_retiro(float $factor_retiro, float $n_dias_trabajados,
                                   float $salario_base_cotizacion): float|array
    {
        if ($factor_retiro <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_retiro);
        }
        if ($salario_base_cotizacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_cotizacion);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $factor_retiro * $salario_base_cotizacion;
        $cuota_diaria = $cuota_diaria / 100;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;

        return round($total_cuota, 2);
    }

    public function calcula_ceav(float $factor_ceav, float $n_dias_trabajados,
                                 float $salario_base_cotizacion): float|array
    {
        if ($factor_ceav <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_ceav);
        }
        if ($salario_base_cotizacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_cotizacion);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $factor_ceav * $salario_base_cotizacion;
        $cuota_diaria = $cuota_diaria / 100;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;

        return round($total_cuota, 2);
    }

    public function calcula_credito_vivienda(float $factor_credito_vivienda, float $n_dias_trabajados,
                                             float $salario_base_aportacion): float|array
    {
        if ($factor_credito_vivienda <= 0.0) {
            return $this->error->error("Error el factor debe ser menor a 0", $factor_credito_vivienda);
        }
        if ($salario_base_aportacion <= 0.0) {
            return $this->error->error("Error salario base de cotizacion debe ser menor a 0",
                $salario_base_aportacion);
        }
        if ($n_dias_trabajados <= 0.0) {
            return $this->error->error("Error los dias trabajados no debe ser menor a 0",
                $n_dias_trabajados);
        }

        $cuota_diaria = $factor_credito_vivienda * $salario_base_aportacion;
        $cuota_diaria = $cuota_diaria / 100;
        $total_cuota = $cuota_diaria * $n_dias_trabajados;

        return round($total_cuota, 2);
    }

    /**
     * Maqueta un registro de tip empleado dependiendo el movimiento
     * @param array $registro_emp Registro de empleaod a modificar
     * @return array
     */
    private function maqueta_row_upd_empleado(array $registro_emp): array
    {
        $registro = array();
        $keys = array('salario_diario_integrado', 'salario_diario');
        foreach ($keys as $key) {
            if (isset($registro_emp[$key])) {
                $registro[$key] = $registro_emp[$key];
            }
        }
        return $registro;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        if (!isset($registro['codigo'])) {
            $movimiento = $this->registro(registro_id: $id,columnas: array("im_movimiento_codigo"));
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener movimiento', data: $movimiento);
            }
            $registro['codigo'] = $movimiento['im_movimiento_codigo'];
        }

        if (!isset($registro['descripcion'])) {
            $registro['descripcion'] = $registro['em_empleado_id'];
            $registro['descripcion'] .= $registro['em_registro_patronal_id'];
            $registro['descripcion'] .= $registro['im_tipo_movimiento_id'];
        }

        $em_empleado = $this->registro_por_id(entidad: new em_empleado($this->link),
            id: $registro['em_empleado_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar registros de empleado ', data: $em_empleado);
        }

        if ($em_empleado->em_empleado_salario_diario !== $registro['salario_diario'] &&
            $em_empleado->em_empleado_salario_diario_integrado !== $registro['salario_diario_integrado']) {

            $modifica = $this->modifica_empleado(registro_emp: $registro);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al modificar empleado', data: $modifica);
            }
        }

        $modifica_bd = parent::modifica_bd($registro, $id, $reactiva, $keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al modificar movimiento', data: $modifica_bd);
        }

        return $modifica_bd;
    }

    private function modifica_empleado(array $registro_emp): array|stdClass
    {

        $tipo = (new im_tipo_movimiento($this->link))->registro(registro_id: $registro_emp['im_tipo_movimiento_id'],
            columnas: array('im_tipo_movimiento_descripcion'));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener tipo de movto', data: $tipo);
        }

        if ($tipo['im_tipo_movimiento_descripcion'] === "BAJA"){
            unset($registro_emp["salario_diario"]);
            unset($registro_emp["salario_diario_integrado"]);
        }

        $keys = array('im_tipo_movimiento_id', 'em_empleado_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $registro_emp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar registro_emp', data: $valida);
        }

        $registro = $this->row_upd_empleado(registro_emp: $registro_emp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar registro', data: $registro);
        }

        $modifica = $this->upd_empleado(registro: $registro, registro_emp: $registro_emp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al modificar empleado', data: $modifica);
        }

        return $modifica;
    }

    private function row_fecha_ini_rel(bool $es_alta, array $registro, array $registro_emp): array
    {
        if ($es_alta) {
            $keys = array('fecha');
            $valida = $this->validacion->fechas_in_array(data: $registro_emp, keys: $keys);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar registro_emp', data: $valida);
            }
            $registro['fecha_inicio_rel_laboral'] = $registro_emp['fecha'];
        }
        return $registro;
    }

    private function row_upd_empleado(array $registro_emp): array
    {
        $registro = $this->maqueta_row_upd_empleado(registro_emp: $registro_emp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al maquetar registro', data: $registro);
        }

        $es_alta = (new im_tipo_movimiento($this->link))->es_alta(
            im_tipo_movimiento_id: $registro_emp['im_tipo_movimiento_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener tipo de movto', data: $es_alta);
        }

        $registro = $this->row_fecha_ini_rel(es_alta: $es_alta, registro: $registro, registro_emp: $registro_emp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar fecha', data: $registro);
        }

        return $registro;
    }

    private function upd_empleado(array $registro, array $registro_emp): array|stdClass
    {
        $modifica = new stdClass();

        if (count($registro) > 0) {

            $modifica = (new em_empleado($this->link))->modifica_bd(
                registro: $registro, id: $registro_emp['em_empleado_id']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al modificar empleado', data: $modifica);
            }
        }
        return $modifica;
    }
}