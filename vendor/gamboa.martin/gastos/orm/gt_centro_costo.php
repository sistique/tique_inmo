<?php

namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class gt_centro_costo extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_centro_costo';
        $columnas = array($tabla => false, 'gt_tipo_centro_costo' => $tabla);
        $campos_obligatorios = array();

        $no_duplicados = array();


        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, no_duplicados: $no_duplicados);


        $this->NAMESPACE = __NAMESPACE__;

    }

    /**
     * Calcula el total de saldos de cotización para una etapa específica.
     *
     * @param array $registros Un array de registros de cotización.
     * @param string $etapa La etapa específica para la cual se calcula el total de saldos.
     * @return float El total de saldos de cotización para la etapa especificada.
     */
    private function calculo_total_saldos_cotizacion(array $registros, string $etapa): float
    {
        return Stream::of($registros)
            ->filter(fn($registro) => $registro['gt_cotizacion_etapa'] === $etapa)
            ->map(fn($registro) => $registro['gt_cotizacion_id'])
            ->flatMap(fn($id) => $this->get_productos(tabla: new gt_cotizacion_producto($this->link),
                campo: 'gt_cotizacion_id',
                id: $id,
                campo_Total: 'gt_cotizacion_producto_total'))
            ->reduce(fn($acumulador, $valor) => $acumulador + $valor, 0.0);
    }

    /**
     * Calcula el total de saldos de orden de compra para una etapa específica.
     *
     * @param array $registros Un array de registros de cotización.
     * @param string $etapa La etapa específica para la cual se calcula el total de saldos.
     * @return float El total de saldos de orden de compra para la etapa especificada.
     */
    private function calculo_total_saldos_orden_compra(array $registros, string $etapa): float
    {
        return Stream::of($registros)
            ->filter(fn($registro) => $registro['gt_cotizacion_etapa'] === $etapa)
            ->map(fn($registro) => $registro['gt_cotizacion_id'])
            ->flatMap(fn($cotizacion_id) => $this->get_orden_compra_cotizacion($cotizacion_id))
            ->filter(fn($orden_compra_id) => $orden_compra_id > -1)
            ->flatMap(fn($id) => $this->get_productos(tabla: new gt_orden_compra_producto($this->link),
                campo: 'gt_orden_compra_id',
                id: $id,
                campo_Total: 'gt_orden_compra_producto_total'))
            ->reduce(fn($acumulador, $valor) => $acumulador + $valor, 0.0);
    }

    /**
     * Calcula el total de saldos de requisición para una etapa específica.
     *
     * @param array $registros Un array de registros de cotización.
     * @param string $etapa La etapa específica para la cual se calcula el total de saldos.
     * @return float El total de saldos de requisición para la etapa especificada.
     */
    private function calculo_total_saldos_requisicion(array $registros, string $etapa): float
    {
        return Stream::of($registros)
            ->filter(fn($registro) => $registro['gt_requisicion_etapa'] === $etapa)
            ->map(fn($registro) => $registro['gt_requisicion_id'])
            ->flatMap(fn($id) => $this->get_productos(tabla: new gt_requisicion_producto($this->link),
                campo: 'gt_requisicion_id',
                id: $id,
                campo_Total: 'gt_requisicion_producto_total'))
            ->reduce(fn($acumulador, $valor) => $acumulador + $valor, 0.0);
    }

    /**
     * Calcula el total de saldos de solicitud para una etapa específica.
     *
     * @param array $registros Un array de registros de cotización.
     * @param string $etapa La etapa específica para la cual se calcula el total de saldos.
     * @return float El total de saldos de solicitud para la etapa especificada.
     */
    private function calculo_total_saldos_solicitud(array $registros, string $etapa): float
    {
        return Stream::of($registros)
            ->filter(fn($registro) => $registro['gt_solicitud_etapa'] === $etapa)
            ->map(fn($registro) => $registro['gt_solicitud_id'])
            ->flatMap(fn($id) => $this->get_productos(tabla: new gt_solicitud_producto($this->link),
                campo: 'gt_solicitud_id',
                id: $id,
                campo_Total: 'gt_solicitud_producto_total'))
            ->reduce(fn($acumulador, $valor) => $acumulador + $valor, 0.0);
    }

    /**
     * Obtiene el id de la orden de compra asociada a una cotización.
     *
     * @param int $gt_cotizacion_id El id de la cotización.
     * @return int El id de la orden de compra asociada a la cotización.
     * Si no hay orden de compra asociada, retorna -1.
     * Si hay un error, retorna un objeto stdClass con el error.
     */
    public function get_orden_compra_cotizacion(int $gt_cotizacion_id): int
    {
        $filtro = ['gt_orden_compra_cotizacion.gt_cotizacion_id' => $gt_cotizacion_id];
        $orden = (new gt_orden_compra_cotizacion($this->link))->filtro_and(
            columnas: ['gt_orden_compra_id'],
            filtro: $filtro
        );
        if (errores::$error) {
            return $this->error->error('Error filtrar orden compra cotizacion', $orden);
        }

        return Stream::of($orden->registros)
            ->map(fn($registro) => $registro['gt_orden_compra_id'])
            ->findFirst() ?? -1;
    }

    /**
     * Obtiene los productos asociados a un registro.
     *
     * @param modelo $tabla La tabla de la cual se obtienen los productos.
     * @param string $campo El campo por el cual se filtra la tabla.
     * @param int $id El id por el cual se filtra la tabla.
     * @param string $campo_Total El campo que se obtiene de la tabla.
     * @return array|stdClass Un array con los valores del campo especificado.
     * Si hay un error, retorna un objeto stdClass con el error.
     */
    public function get_productos(modelo $tabla, string $campo, int $id, string $campo_Total): array|stdClass
    {
        $filtro = [$campo => $id];
        $datos = $tabla->filtro_and(
            columnas: [$campo_Total],
            filtro: $filtro
        );
        if (errores::$error) {
            return $this->error->error('Error al obtener los datos', $datos);
        }

        return Stream::of($datos->registros)
            ->map(fn($registro) => $registro[$campo_Total])
            ->toArray();
    }

    /**
     * Obtiene el total de saldos de cotización para un centro de costo.
     *
     * @param int $gt_centro_costo_id El id del centro de costo.
     * @return array|stdClass Un array con el total de saldos de cotización.
     * Si hay un error, retorna un objeto stdClass con el error.
     */
    public function total_saldos_cotizacion(int $gt_centro_costo_id): array|stdClass
    {
        $cotizaciones = Transaccion::getInstance(new gt_cotizacion($this->link), $this->error)
            ->get_registros('gt_centro_costo_id', $gt_centro_costo_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener cotizaciones', data: $cotizaciones);
        }

        $total_alta = $this->calculo_total_saldos_cotizacion(registros: $cotizaciones->registros, etapa: 'ALTA');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de cotizacion en alta',
                data: $total_alta);
        }

        $total_autorizado = $this->calculo_total_saldos_cotizacion(registros: $cotizaciones->registros,
            etapa: 'AUTORIZADO');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de cotizacion autorizados',
                data: $total_autorizado);
        }

        return [
            "total_alta" => $total_alta,
            "total_autorizado" => $total_autorizado,
            "total" => $total_alta + $total_autorizado,
        ];
    }

    /**
     * Obtiene el total de saldos de orden de compra para un centro de costo.
     *
     * @param int $gt_centro_costo_id El id del centro de costo.
     * @return array|stdClass Un array con el total de saldos de orden de compra.
     * Si hay un error, retorna un objeto stdClass con el error.
     */
    public function total_saldos_orden_compra(int $gt_centro_costo_id): array|stdClass
    {
        $cotizaciones = Transaccion::getInstance(new gt_cotizacion($this->link), $this->error)
            ->get_registros('gt_centro_costo_id', $gt_centro_costo_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener cotizaciones', data: $cotizaciones);
        }

        $total_alta = $this->calculo_total_saldos_orden_compra(registros: $cotizaciones->registros, etapa: 'ALTA');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de orden de compra en alta',
                data: $total_alta);
        }

        $total_autorizado = $this->calculo_total_saldos_orden_compra(registros: $cotizaciones->registros, etapa: 'AUTORIZADO');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de orden de compra autorizados',
                data: $total_autorizado);
        }

        return [
            "total_alta" => $total_alta,
            "total_autorizado" => $total_autorizado,
            "total" => $total_alta + $total_autorizado,
        ];
    }

    /**
     * Obtiene el total de saldos de requisición para un centro de costo.
     *
     * @param int $gt_centro_costo_id El id del centro de costo.
     * @return array|stdClass Un array con el total de saldos de requisición.
     * Si hay un error, retorna un objeto stdClass con el error.
     */
    public function total_saldos_requisicion(int $gt_centro_costo_id): array|stdClass
    {
        $requisiciones = Transaccion::getInstance(new gt_requisicion($this->link), $this->error)
            ->get_registros('gt_centro_costo_id', $gt_centro_costo_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener requisiciones', data: $requisiciones);
        }

        $total_alta = $this->calculo_total_saldos_requisicion(registros: $requisiciones->registros, etapa: 'ALTA');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de requisicion en alta',
                data: $total_alta);
        }

        $total_autorizado = $this->calculo_total_saldos_requisicion(registros: $requisiciones->registros,
            etapa: 'AUTORIZADO');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de requisicion autorizados',
                data: $total_autorizado);
        }

        return [
            "total_alta" => $total_alta,
            "total_autorizado" => $total_autorizado,
            "total" => $total_alta + $total_autorizado,
        ];
    }

    /**
     * Obtiene el total de saldos de solicitud para un centro de costo.
     *
     * @param int $gt_centro_costo_id El id del centro de costo.
     * @return array|stdClass Un array con el total de saldos de solicitud.
     * Si hay un error, retorna un objeto stdClass con el error.
     */
    public function total_saldos_solicitud(int $gt_centro_costo_id): array|stdClass
    {
        $solicitudes = Transaccion::getInstance(new gt_solicitud($this->link), $this->error)
            ->get_registros('gt_centro_costo_id', $gt_centro_costo_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener solicitudes', data: $solicitudes);
        }

        $total_alta = $this->calculo_total_saldos_solicitud(registros: $solicitudes->registros, etapa: 'ALTA');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de solicitud en alta',
                data: $total_alta);
        }

        $total_autorizado = $this->calculo_total_saldos_solicitud(registros: $solicitudes->registros,
            etapa: 'AUTORIZADO');
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular total de saldos de solicitud autorizados',
                data: $total_autorizado);
        }

        return [
            "total_alta" => $total_alta,
            "total_autorizado" => $total_autorizado,
            "total" => $total_alta + $total_autorizado,
        ];
    }
}