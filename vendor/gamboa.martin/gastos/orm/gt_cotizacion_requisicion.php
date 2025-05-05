<?php

namespace gamboamartin\gastos\models;

use gamboamartin\errores\errores;
use PDO;

class gt_cotizacion_requisicion extends _base_transacciones
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_cotizacion_requisicion';
        $columnas = array($tabla => false, "gt_cotizacion" => $tabla, "gt_requisicion" => $tabla,
            "gt_tipo_cotizacion" => "gt_cotizacion", "gt_proveedor" => "gt_cotizacion");
        $campos_obligatorios = array();

        $no_duplicados = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, no_duplicados: $no_duplicados);

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