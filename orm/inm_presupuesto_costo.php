<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class inm_presupuesto_costo extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_presupuesto_costo';
        $columnas = array($tabla=>false,'inm_concepto'=>$tabla,'inm_tipo_concepto'=>'inm_concepto',
            'gt_proveedor'=>$tabla,'inm_ubicacion'=>$tabla);

        $campos_obligatorios = array('inm_ubicacion_id','inm_concepto_id','gt_proveedor_id','monto_presupuesto');

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array('inm_ubicacion_id','inm_concepto_id','gt_proveedor_id','monto_presupuesto');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Presupuesto Costo';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        if (!isset($this->registro['descripcion'])) {
            $descripcion = 'Ubicacion ' . $this->registro['inm_ubicacion_id'];
            $descripcion .= ' Concepto ' . $this->registro['inm_concepto_id'];
            $descripcion .= ' Prov ' . $this->registro['gt_proveedor_id'];
            $this->registro['descripcion'] = $descripcion;
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar opcion', data: $r_alta_bd);
        }

        return $r_alta_bd;
    }

    public function elimina_bd(int $id): array|stdClass
    {
        $r_presupuesto = (new inm_presupuesto_costo(link: $this->link))->registro(registro_id: $id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener presupuesto', data: $r_presupuesto);
        }

        $filtro_costo = [
            'inm_ubicacion.id' => $r_presupuesto['inm_ubicacion_id'],
            'inm_concepto.id' => $r_presupuesto['inm_concepto_id']
        ];
        $r_costos = (new inm_costo(link: $this->link))->filtro_and(filtro: $filtro_costo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener costos', data: $r_costos);
        }

        foreach ($r_costos->registros as $costo) {
            $inm_costo_id = $costo['inm_costo_id'];

            $filtro_pago = ['inm_costo.id' => $inm_costo_id];
            $del_pagos = (new inm_pago_costo(link: $this->link))->elimina_con_filtro_and(filtro: $filtro_pago);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al eliminar pagos del costo', data: $del_pagos);
            }

            $filtro_del_costo = ['inm_costo.id' => $inm_costo_id];
            $del_costo = (new inm_costo(link: $this->link))->elimina_con_filtro_and(filtro: $filtro_del_costo);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al eliminar costo', data: $del_costo);
            }
        }

        $r_elimina_bd = parent::elimina_bd(id: $id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al eliminar presupuesto', data: $r_elimina_bd);
        }

        return $r_elimina_bd;
    }
}

