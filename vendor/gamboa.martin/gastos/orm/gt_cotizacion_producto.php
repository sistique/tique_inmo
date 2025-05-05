<?php

namespace gamboamartin\gastos\models;

use gamboamartin\errores\errores;
use PDO;

class gt_cotizacion_producto extends _base_transacciones
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_cotizacion_producto';
        $columnas = array($tabla => false, 'gt_cotizacion' => $tabla, 'com_producto' => $tabla, 'cat_sat_unidad' => $tabla,
            'gt_tipo_cotizacion' => 'gt_cotizacion', 'gt_proveedor' => 'gt_cotizacion');
        $campos_obligatorios = array();

        $no_duplicados = array();

        $columnas_extra = array();
        $columnas_extra['gt_cotizacion_producto_total'] =
            "IFNULL ( IFNULL(gt_cotizacion_producto.cantidad, 0) * IFNULL(gt_cotizacion_producto.precio, 1),0)";

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    protected function inicializa_campos(array $registros): array
    {
        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }
        $registros['descripcion'] = $registros['codigo'];

        return $registros;
    }
}