<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class inm_pago_costo extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_pago_costo';
        $columnas = array($tabla=>false,'inm_costo'=>$tabla,'inm_concepto'=>'inm_costo','inm_tipo_concepto'=>'inm_concepto',
            'inm_ubicacion'=>'inm_costo','cat_sat_forma_pago'=>$tabla, 'gt_proveedor'=>'inm_costo');

        $campos_obligatorios = array('inm_costo_id','cat_sat_forma_pago_id','monto','fecha_pago','referencia');

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array('inm_costo_id','cat_sat_forma_pago_id','monto','fecha_pago','referencia');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Pago Costo';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        if (!isset($this->registro['descripcion'])) {
            $descripcion = 'Costo ' . $this->registro['inm_costo_id'];
            $descripcion .= ' ' . $this->registro['fecha_pago'];
            $descripcion .= ' ' . $this->registro['referencia'];
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
        $r_pago_costo = (new inm_pago_costo(link: $this->link))->registro(registro_id: $id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener pago de costo', data: $r_pago_costo);
        }

        $inm_costo_id = $r_pago_costo['inm_costo_id'];

        $r_elimina_bd = parent::elimina_bd(id: $id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al eliminar pago de costo', data: $r_elimina_bd);
        }

        $filtro_pagos['inm_pago_costo.inm_costo_id'] = $inm_costo_id;
        $r_pagos_restantes = (new inm_pago_costo(link: $this->link))->filtro_and(filtro: $filtro_pagos);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener pagos restantes', data: $r_pagos_restantes);
        }

        if ((int)$r_pagos_restantes->n_registros === 0) {
            $filtro_costo['inm_costo.id'] = $inm_costo_id;
            $del = (new inm_costo(link: $this->link))->elimina_con_filtro_and(filtro: $filtro_costo);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al eliminar costo', data: $del);
            }
        }

        return $r_elimina_bd;
    }
}

