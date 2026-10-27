<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class inm_conf_presupuesto_costo extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_conf_presupuesto_costo';
        $columnas = array($tabla=>false,'inm_concepto'=>$tabla,'inm_tipo_concepto'=>'inm_concepto','gt_proveedor'=>$tabla);

        $campos_obligatorios = array('inm_concepto_id','gt_proveedor_id','monto_presupuesto');

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array('inm_concepto_id','gt_proveedor_id','monto_presupuesto');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Conf Presupuesto Costo';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        if (!isset($this->registro['descripcion'])) {
            $descripcion = 'Concepto ' . $this->registro['inm_concepto_id'];
            $descripcion .= ' Prov ' . $this->registro['gt_proveedor_id'];
            $this->registro['descripcion'] = $descripcion;
        }

        if (!isset($this->registro['aplica_iva'])) {
            $this->registro['aplica_iva'] = 'inactivo';
        }
        if (!isset($this->registro['aplica_co_acreditado'])) {
            $this->registro['aplica_co_acreditado'] = 'inactivo';
        }
        if (!isset($this->registro['aplica_conyuge'])) {
            $this->registro['aplica_conyuge'] = 'inactivo';
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar opcion', data: $r_alta_bd);
        }

        return $r_alta_bd;
    }
}

