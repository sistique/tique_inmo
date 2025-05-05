<?php

namespace gamboamartin\gastos\models;

use Exception;
use gamboamartin\errores\errores;
use gamboamartin\proceso\models\pr_etapa_proceso;
use PDO;
use stdClass;

class gt_solicitud_etapa extends _base_transacciones
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_solicitud_etapa';
        $columnas = array($tabla => false, 'gt_solicitud' => $tabla, 'pr_etapa_proceso' => $tabla,
            'pr_proceso' => 'pr_etapa_proceso', 'pr_etapa' => 'pr_etapa_proceso');
        $campos_obligatorios = array();

        $no_duplicados = array();


        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar solicitud etapa', data: $r_alta_bd);
        }

        $acciones = $this->acciones_solicitud(registros: $this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar acciones de solicitud', data: $acciones);
        }

        return $r_alta_bd;
    }

    /**
     * Realiza acciones en una solicitud específica.
     *
     * @param array $registros Los registros de la solicitud a modificar.
     * @return array|stdClass Devuelve el resultado de la modificación si es exitosa.
     * @throws Exception Si ocurre un error durante la ejecución.
     */
    public function acciones_solicitud(array $registros): array|stdClass
    {
        $etapa_proceso = (new pr_etapa_proceso($this->link))->registro(registro_id: $registros['pr_etapa_proceso_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar filtro en etapa proceso', data: $etapa_proceso);
        }

        $registro['etapa'] = $etapa_proceso['pr_etapa_descripcion'];

        $update = (new gt_solicitud($this->link))->modifica_bd(registro: $registro,id: $registros['gt_solicitud_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al modificar etapa de solicitud', data: $update);
        }

        return $update;
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