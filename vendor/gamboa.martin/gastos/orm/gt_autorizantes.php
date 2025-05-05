<?php

namespace gamboamartin\gastos\models;

use gamboamartin\errores\errores;
use PDO;

class gt_autorizantes extends _base_auto_soli
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_autorizantes';
        $columnas = array($tabla => false, 'gt_solicitud' => $tabla, 'gt_autorizante' => $tabla,
            "em_empleado" => 'gt_autorizante', "gt_centro_costo" => 'gt_solicitud', 'gt_tipo_solicitud' => 'gt_solicitud');
        $campos_obligatorios = array();

        $no_duplicados = array();

        $columnas_extra['em_empleado_nombre_completo'] = 'CONCAT (IFNULL(em_empleado.nombre,"")," ",IFNULL(em_empleado.ap, "")," ",IFNULL(em_empleado.am,""))';

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    protected function inicializa_campos(array $registros): array
    {
        $keys = array('gt_autorizante_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error validar campos', data: $valida);
        }

        $init = parent::inicializa_campos($registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error validar campos', data: $valida);
        }

        return $init;
    }
}