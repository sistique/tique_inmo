<?php

namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent;
use Exception;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class gt_autorizante_ejecutores_compra extends _base_auto_soli
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_autorizante_ejecutores_compra';
        $columnas = array($tabla => false, 'gt_autorizante' => $tabla, 'gt_ejecutor_compra' => $tabla);
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

    /**
     * Obtiene relaciones entre un autorizante y un ejecutor de compra.
     *
     * @param int $gt_autorizante_id El ID del autorizante.
     * @param int $gt_ejecutor_compra_id El ID del ejecutor de compra.
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa las relaciones encontradas.
     * @throws Exception Si ocurre un error al obtener las relaciones.
     */
    public function get_relaciones(int $gt_autorizante_id, int $gt_ejecutor_compra_id): array|stdClass
    {
        $filtro['gt_autorizante_ejecutores_compra.gt_autorizante_id'] = $gt_autorizante_id;
        $filtro['gt_autorizante_ejecutores_compra.gt_ejecutor_compra_id'] = $gt_ejecutor_compra_id;
        $resultado = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error filtrar permisos', data: $resultado);
        }

        return $resultado;
    }

    /**
     * Valida los permisos de un autorizante para aprobar una orden de compra de un ejecutor.
     *
     * @param int $gt_autorizante_id El ID del autorizante.
     * @param int $gt_ejecutor_compra_id El ID del ejecutor de compra.
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado de la validación de permisos.
     * @throws Exception Si ocurre un error al validar los permisos.
     */
    public function valida_permisos(int $gt_autorizante_id, int $gt_ejecutor_compra_id): array|stdClass
    {
        $registro = $this->get_relaciones(gt_autorizante_id: $gt_autorizante_id, gt_ejecutor_compra_id: $gt_ejecutor_compra_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener permisos', data: $registro);
        }

        if ($registro->n_registros <= 0) {
            return $this->error->error(mensaje: 'El autorizante no tiene permisos para aprobar la orden de compra de este ejecutor',
                data: $registro);
        }

        return $registro;
    }

}