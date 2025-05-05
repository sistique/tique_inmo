<?php

namespace gamboamartin\gastos\models;

use Exception;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class gt_solicitud_producto extends _base_transacciones
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_solicitud_producto';
        $columnas = array($tabla => false, 'gt_solicitud' => $tabla, 'com_producto' => $tabla, 'cat_sat_unidad' => $tabla);
        $campos_obligatorios = array();

        $no_duplicados = array();


        $columnas_extra = array();
        $columnas_extra['gt_solicitud_producto_total'] =
            "IFNULL ( IFNULL(gt_solicitud_producto.cantidad, 0) * IFNULL(gt_solicitud_producto.precio, 1),0)";

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

    /**
     * Obtiene el precio promedio de un producto específico.
     *
     * @param int $com_producto_id Identificador del producto a calcular su precio promedio.
     * @return float|array Devuelve el precio promedio si es exitoso, o un error si ocurre un problema.
     * @throws Exception Si ocurre un error durante la obtención de los productos o el cálculo del promedio.
     */
    public function get_precio_promedio(int $com_producto_id) : float|array
    {
        $productos = $this->get_productos(com_producto_id: $com_producto_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener productos', data: $productos);
        }

        $promedio = $this->promedio_precios_productos(productos: $productos);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al calcular el promedio de precios de los productos', data: $promedio);
        }

        return $promedio;
    }

    /**
     * Obtiene los productos basados en un identificador de producto específico.
     *
     * @param int $com_producto_id Identificador del producto a obtener.
     * @return array Devuelve los productos si la operación es exitosa, o un error si ocurre un problema.
     * @throws Exception Si ocurre un error durante la obtención de los productos.
     */
    public function get_productos(int $com_producto_id)
    {
        $filtro['gt_solicitud_producto.com_producto_id'] = $com_producto_id;

        $datos = (new gt_solicitud_producto($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al aplicar filtro para productos', data: $datos);
        }

        return $datos;
    }

    /**
     * Calcula el precio promedio de un conjunto de productos.
     *
     * @param stdClass $productos Los productos para los cuales calcular el precio promedio.
     * @return float Devuelve el precio promedio si la operación es exitosa, o 0.0 si no hay productos.
     */
    public function promedio_precios_productos(stdClass $productos) : float
    {
        if ($productos->n_registros <= 0) {
            return 0.0;
        }

        $promedio = 0.0;
        $registros = $productos->registros;

        foreach ($registros as $registro){
            $promedio += $registro['gt_solicitud_producto_precio'];
        }

        return round($promedio / $productos->n_registros, 2);
    }
}