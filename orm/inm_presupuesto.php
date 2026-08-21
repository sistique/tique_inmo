<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class inm_presupuesto extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_presupuesto';
        $columnas = array($tabla=>false,'inm_categoria_financiera'=>$tabla);

        $campos_obligatorios = array('inm_categoria_financiera_id','anio','mes','monto_proyectado','es_ingreso');

        $columnas_extra= array();

        $columnas_extra['inm_presupuesto_monto_real'] = "(SELECT IFNULL(SUM(inm_mov_real.monto),0) FROM inm_mov_real ".
            "WHERE inm_mov_real.inm_categoria_financiera_id = inm_presupuesto.inm_categoria_financiera_id ".
            "AND inm_mov_real.anio = inm_presupuesto.anio ".
            "AND inm_mov_real.mes = inm_presupuesto.mes ".
            "AND inm_mov_real.es_ingreso = inm_presupuesto.es_ingreso)";

        $columnas_extra['inm_presupuesto_diferencia'] = "(inm_presupuesto.monto_proyectado - ".
            "(SELECT IFNULL(SUM(inm_mov_real.monto),0) FROM inm_mov_real ".
            "WHERE inm_mov_real.inm_categoria_financiera_id = inm_presupuesto.inm_categoria_financiera_id ".
            "AND inm_mov_real.anio = inm_presupuesto.anio ".
            "AND inm_mov_real.mes = inm_presupuesto.mes ".
            "AND inm_mov_real.es_ingreso = inm_presupuesto.es_ingreso))";

        $columnas_extra['inm_presupuesto_pct_cumplimiento'] = "(CASE WHEN inm_presupuesto.monto_proyectado > 0 THEN ".
            "ROUND(((SELECT IFNULL(SUM(inm_mov_real.monto),0) FROM inm_mov_real ".
            "WHERE inm_mov_real.inm_categoria_financiera_id = inm_presupuesto.inm_categoria_financiera_id ".
            "AND inm_mov_real.anio = inm_presupuesto.anio ".
            "AND inm_mov_real.mes = inm_presupuesto.mes ".
            "AND inm_mov_real.es_ingreso = inm_presupuesto.es_ingreso) / inm_presupuesto.monto_proyectado) * 100, 2) ".
            "ELSE 0 END)";

        $renombres= array();

        $atributos_criticos = array('inm_categoria_financiera_id','anio','mes','monto_proyectado','es_ingreso');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Presupuestos';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $keys = array('inm_categoria_financiera_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $keys = array('monto_proyectado');
        $valida = $this->validacion->valida_double_mayores_igual_0(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        if (!isset($this->registro['descripcion'])) {
            $descripcion = $this->registro['es_ingreso'] === 'activo' ? 'INGRESO' : 'EGRESO';
            $descripcion .= ' ' . $this->registro['anio'] . '-' . str_pad($this->registro['mes'], 2, '0', STR_PAD_LEFT);
            $descripcion .= ' CAT:' . $this->registro['inm_categoria_financiera_id'];
            $this->registro['descripcion'] = $descripcion;
        }

        if (!isset($this->registro['codigo'])) {
            $this->registro['codigo'] = $this->registro['es_ingreso'] . '_' . $this->registro['anio'] . '_' .
                $this->registro['mes'] . '_' . $this->registro['inm_categoria_financiera_id'] . '_' . time();
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar presupuesto', data: $r_alta_bd);
        }

        return $r_alta_bd;
    }

    /**
     * Obtiene el reporte comparativo mensual: presupuesto vs real
     * @param int $anio Año a consultar
     * @param int $mes Mes a consultar (0 = todos)
     * @return array
     */
    public function reporte_comparativo(int $anio, int $mes = 0): array
    {
        $filtro = array();
        $filtro['inm_presupuesto.anio'] = $anio;
        if($mes > 0){
            $filtro['inm_presupuesto.mes'] = $mes;
        }

        $r_presupuestos = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener presupuestos', data: $r_presupuestos);
        }

        return $r_presupuestos;
    }

    /**
     * Calcula el flujo de efectivo mensual proyectado
     * @param int $anio Año
     * @param int $mes Mes
     * @return array|stdClass
     */
    public function flujo_proyectado(int $anio, int $mes): array|stdClass
    {
        $filtro_ingreso = array('inm_presupuesto.anio' => $anio, 'inm_presupuesto.mes' => $mes,
            'inm_presupuesto.es_ingreso' => 'activo');
        $ingresos = $this->filtro_and(filtro: $filtro_ingreso);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener ingresos proyectados', data: $ingresos);
        }

        $filtro_egreso = array('inm_presupuesto.anio' => $anio, 'inm_presupuesto.mes' => $mes,
            'inm_presupuesto.es_ingreso' => 'inactivo');
        $egresos = $this->filtro_and(filtro: $filtro_egreso);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener egresos proyectados', data: $egresos);
        }

        $total_ingresos = 0.0;
        foreach ($ingresos as $ingreso){
            $total_ingresos += (float)$ingreso['inm_presupuesto_monto_proyectado'];
        }

        $total_egresos = 0.0;
        foreach ($egresos as $egreso){
            $total_egresos += (float)$egreso['inm_presupuesto_monto_proyectado'];
        }

        $out = new stdClass();
        $out->anio = $anio;
        $out->mes = $mes;
        $out->total_ingresos_proyectados = $total_ingresos;
        $out->total_egresos_proyectados = $total_egresos;
        $out->flujo_neto_proyectado = $total_ingresos - $total_egresos;
        $out->ingresos = $ingresos;
        $out->egresos = $egresos;

        return $out;
    }
}

