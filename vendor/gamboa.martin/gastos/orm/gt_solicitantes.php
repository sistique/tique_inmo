<?php

namespace gamboamartin\gastos\models;

use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class gt_solicitantes extends _base_auto_soli
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_solicitantes';
        $columnas = array($tabla => false, 'gt_solicitud' => $tabla, 'gt_solicitante' => $tabla,
            "em_empleado" => 'gt_solicitante', "gt_centro_costo" => 'gt_solicitud');
        $campos_obligatorios = array();

        $no_duplicados = array();

        $columnas_extra['em_empleado_nombre_completo'] = 'CONCAT (IFNULL(em_empleado.nombre,"")," ",IFNULL(em_empleado.ap, "")," ",IFNULL(em_empleado.am,""))';

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    protected function inicializa_campos(array $registros): array
    {
        $keys = array('gt_solicitante_id');
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