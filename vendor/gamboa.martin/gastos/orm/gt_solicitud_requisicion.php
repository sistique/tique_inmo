<?php

namespace gamboamartin\gastos\models;

use gamboamartin\errores\errores;
use PDO;

class gt_solicitud_requisicion extends _base_auto_soli
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_solicitud_requisicion';
        $columnas = array($tabla => false, 'gt_requisicion' => $tabla, 'gt_solicitud' => $tabla,
            "gt_centro_costo" => 'gt_requisicion');
        $campos_obligatorios = array();

        $no_duplicados = array();


        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    protected function inicializa_campos(array $registros): array
    {
        $keys = array('gt_requisicion_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error validar campos', data: $valida);
        }

        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }

        $registros['codigo'] .= $registros['gt_solicitud_id'];
        $registros['descripcion'] = $registros['codigo'];

        return $registros;
    }
}