<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_mov_real extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_mov_real';
        $columnas = array($tabla=>false,'inm_categoria_financiera'=>$tabla,
            'inm_tipo_movimiento'=>$tabla);

        $campos_obligatorios = array('inm_categoria_financiera_id','inm_tipo_movimiento_id',
            'monto','fecha','es_ingreso','anio','mes');

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array('inm_categoria_financiera_id','inm_tipo_movimiento_id',
            'monto','fecha','es_ingreso','referencia');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Movimientos Reales';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $keys = array('inm_categoria_financiera_id','inm_tipo_movimiento_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $keys = array('monto');
        $valida = $this->validacion->valida_double_mayores_igual_0(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $keys = array('fecha');
        $valida = $this->validacion->fechas_in_array(data: $this->registro,keys:  $keys);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        // Auto-derivar anio y mes de la fecha si no vienen
        if(!isset($this->registro['anio']) || trim($this->registro['anio']) === ''){
            $this->registro['anio'] = (int)date('Y', strtotime($this->registro['fecha']));
        }
        if(!isset($this->registro['mes']) || trim($this->registro['mes']) === ''){
            $this->registro['mes'] = (int)date('n', strtotime($this->registro['fecha']));
        }

        if (!isset($this->registro['descripcion'])) {
            $tipo = $this->registro['es_ingreso'] === 'activo' ? 'INGRESO' : 'EGRESO';
            $descripcion = $tipo . ' ' . $this->registro['fecha'];
            $descripcion .= ' $' . number_format((float)$this->registro['monto'], 2);
            $this->registro['descripcion'] = $descripcion;
        }

        if (!isset($this->registro['codigo'])) {
            $this->registro['codigo'] = $this->registro['fecha'] . '_' .
                $this->registro['inm_categoria_financiera_id'] . '_' .
                $this->registro['inm_tipo_movimiento_id'] . '_' . time();
        }

        if(!isset($this->registro['referencia'])){
            $this->registro['referencia'] = 'SIN REF';
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar movimiento real', data: $r_alta_bd);
        }

        return $r_alta_bd;
    }

    /**
     * Obtiene los totales reales por mes
     * @param int $anio Año
     * @param int $mes Mes
     * @return array|stdClass
     */
    public function totales_mes(int $anio, int $mes): array|stdClass
    {
        $filtro_ingreso = array('inm_mov_real.anio' => $anio, 'inm_mov_real.mes' => $mes,
            'inm_mov_real.es_ingreso' => 'activo');
        $ingresos = $this->filtro_and(filtro: $filtro_ingreso);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener ingresos reales', data: $ingresos);
        }

        $filtro_egreso = array('inm_mov_real.anio' => $anio, 'inm_mov_real.mes' => $mes,
            'inm_mov_real.es_ingreso' => 'inactivo');
        $egresos = $this->filtro_and(filtro: $filtro_egreso);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener egresos reales', data: $egresos);
        }

        $total_ingresos = 0.0;
        foreach ($ingresos as $ingreso){
            $total_ingresos += (float)$ingreso['inm_mov_real_monto'];
        }

        $total_egresos = 0.0;
        foreach ($egresos as $egreso){
            $total_egresos += (float)$egreso['inm_mov_real_monto'];
        }

        $out = new stdClass();
        $out->anio = $anio;
        $out->mes = $mes;
        $out->total_ingresos = $total_ingresos;
        $out->total_egresos = $total_egresos;
        $out->flujo_neto = $total_ingresos - $total_egresos;
        $out->ingresos = $ingresos;
        $out->egresos = $egresos;

        return $out;
    }

    /**
     * Calcula el saldo acumulado hasta cierto mes
     * @param int $anio Año
     * @param int $mes_hasta Mes hasta donde acumular
     * @return array|stdClass
     */
    public function saldo_acumulado(int $anio, int $mes_hasta): array|stdClass
    {
        $saldo = 0.0;
        $detalle = array();

        for($m = 1; $m <= $mes_hasta; $m++){
            $totales = $this->totales_mes(anio: $anio, mes: $m);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener totales mes ' . $m, data: $totales);
            }
            $saldo += $totales->flujo_neto;

            $detalle[] = array(
                'mes' => $m,
                'ingresos' => $totales->total_ingresos,
                'egresos' => $totales->total_egresos,
                'flujo_neto' => $totales->flujo_neto,
                'saldo_acumulado' => $saldo
            );
        }

        $out = new stdClass();
        $out->anio = $anio;
        $out->mes_hasta = $mes_hasta;
        $out->saldo_acumulado = $saldo;
        $out->detalle_mensual = $detalle;

        return $out;
    }

    /**
     * Obtiene movimientos por ubicación (propiedad)
     * @param int $inm_ubicacion_id ID de la ubicación
     * @return array
     */
    public function movimientos_por_ubicacion(int $inm_ubicacion_id): array
    {
        $filtro = array('inm_mov_real.inm_ubicacion_id' => $inm_ubicacion_id);
        $movimientos = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener movimientos por ubicacion', data: $movimientos);
        }
        return $movimientos;
    }
}

