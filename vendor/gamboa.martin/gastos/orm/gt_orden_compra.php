<?php
namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent_sin_codigo;
use Exception;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class gt_orden_compra extends _modelo_parent_sin_codigo {
    public function __construct(PDO $link){
        $tabla = 'gt_orden_compra';
        $columnas = array($tabla=>false, "gt_tipo_orden_compra" => $tabla);
        $campos_obligatorios = array();

        $no_duplicados = array();


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $gt_cotizacion_id = $this->registro['gt_cotizacion_id'];

        $estado = (new gt_cotizacion($this->link))->registro(registro_id: $gt_cotizacion_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener cotización', data: $estado);
        }

        if ($estado['gt_cotizacion_etapa'] != constantes::PR_ETAPA_AUTORIZADO->value) {
            return $this->error->error(mensaje: "Error la cotización no se encuentra AUTORIZADA, etapa actual: 
            {$estado['gt_cotizacion_etapa']}", data: $estado);
        }

        $acciones_ejecutor = $this->acciones_ejecutor();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar acciones para el ejecutor de la compra',
                data: $acciones_ejecutor);
        }

        $acciones = $this->acciones_cotizacion();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar acciones para cotizacion', data: $acciones);
        }

        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar orden compra', data: $r_alta_bd);
        }

        $relacion_ejecutores = $this->acciones_ejecutores(gt_orden_compra_id: $r_alta_bd->registro_id,
            gt_ejecutor_compra_id: $acciones_ejecutor->registros[0]['gt_ejecutor_compra_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar relacion entre el ejecutor y la orden de compra',
                data: $relacion_ejecutores);
        }

        $relacion = $this->alta_relacion_cotizacion_orden_compra(gt_cotizacion_id: $gt_cotizacion_id,
            gt_orden_compra_id: $r_alta_bd->registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar relacion entre cotizacion y orden de compra', data: $relacion);
        }

        return $r_alta_bd;
    }

    /**
     * Registra un nuevo ejecutor de compra asociado a una orden de compra en la base de datos.
     *
     * @param int $gt_orden_compra_id El ID de la orden de compra a la que se asociará el ejecutor de compra.
     * @param int $gt_ejecutor_compra_id El ID del ejecutor de compra que se asociará a la orden de compra.
     *
     * @return array|stdClass Devuelve el resultado de la operación de registro.
     * @throws Exception Si ocurre un error al registrar la relación entre el ejecutor de compra y la orden de compra.
     */
    public function alta_ejecutores(int $gt_orden_compra_id, int $gt_ejecutor_compra_id) : array|stdClass
    {
        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }
        $registros['descripcion'] = "Cotizacion - Cotizador";
        $registros['gt_orden_compra_id'] = $gt_orden_compra_id;
        $registros['gt_ejecutor_compra_id'] = $gt_ejecutor_compra_id;

        $alta = (new gt_ejecutores_compra($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al dar de alta relacion entre el ejecutor y la orden de compra',
                data: $alta);
        }

        return $alta;
    }

    /**
     * Realiza acciones relacionadas con la cotización, como verificar el estado y limpiar campos adicionales.
     *
     * @return array Devuelve el registro de la cotización con las acciones realizadas, o un objeto de error en caso de fallo.
     * @throws Exception Si ocurre un error al verificar el estado de la cotización o al limpiar campos adicionales.
     */
    public function acciones_cotizacion(): array
    {
        $resultado = $this->verificar_estado_cotizacion(registros: $this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al verificar etapa de la cotizacion', data: $resultado);
        }

        $this->registro = $this->limpia_campos_extras(registro: $this->registro, campos_limpiar: array("gt_cotizacion_id"));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $this->registro);
        }

        return $this->registro;
    }

    /**
     * Realiza acciones relacionadas con el ejecutor, como validar permisos de usuario y empleado.
     *
     * @return array|stdClass Devuelve el resultado de las acciones realizadas o un objeto de error en caso de fallo.
     * @throws Exception Si ocurre un error al validar permisos de usuario o empleado.
     */
    public function acciones_ejecutor() : array | stdClass
    {
        $existe_usuario = $this->validar_permiso_usuario();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al comprobar permisos del usuario', data: $existe_usuario);
        }

        $existe_solicitante = $this->validar_permiso_empleado($existe_usuario->registros[0]['em_empleado_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al comprobar permisos del empleado',
                data: $existe_solicitante);
        }

        return $existe_solicitante;
    }

    /**
     * Realiza acciones relacionadas con los ejecutores de compra,
     * como registrar una nueva relación entre un ejecutor y una orden de compra.
     *
     * @param int $gt_orden_compra_id El ID de la orden de compra a la que se asociará el ejecutor de compra.
     * @param int $gt_ejecutor_compra_id El ID del ejecutor de compra que se asociará a la orden de compra.
     *
     * @return array|stdClass Devuelve el resultado de la operación de registro de la relación entre el ejecutor y la orden de compra.
     * @throws Exception Si ocurre un error al insertar la relación entre el ejecutor y la orden de compra.
     */
    public function acciones_ejecutores(int $gt_orden_compra_id, int $gt_ejecutor_compra_id) : array | stdClass
    {
        $alta_ejecutores = $this->alta_ejecutores(gt_orden_compra_id: $gt_orden_compra_id, gt_ejecutor_compra_id: $gt_ejecutor_compra_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error insertar relacion entre ejecutor y la orden de compra',
                data: $alta_ejecutores);
        }

        return $alta_ejecutores;
    }

    /**
     * Valida si el usuario actual tiene permisos para realizar órdenes de compra.
     *
     * @return array|stdClass Devuelve un array o un objeto que indica si el usuario tiene permisos.
     * @throws Exception Si ocurre un error al comprobar los permisos del usuario para realizar órdenes de compra.
     */
    public function validar_permiso_usuario() : array|stdClass
    {
        $existe = Transaccion::of(new gt_empleado_usuario($this->link))
            ->existe(filtro: ['gt_empleado_usuario.adm_usuario_id' => $_SESSION['usuario_id']]);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al comprobar si el usuario esta autorizado para hacer ordenes de compra',
                data: $existe);
        }

        if ($existe->n_registros <= 0) {
            return $this->error->error(mensaje: 'Error el usuario no se encuentra autorizado para hacer ordenes de compra',
                data: $existe);
        }

        return $existe;
    }

    /**
     * Valida si un empleado específico está autorizado para realizar órdenes de compra.
     *
     * @param int $em_empleado_id El ID del empleado que se va a validar.
     *
     * @return array|stdClass Devuelve un array o un objeto que indica si el empleado está autorizado.
     * @throws Exception Si ocurre un error al comprobar los permisos del empleado para realizar órdenes de compra.
     */
    public function validar_permiso_empleado(int $em_empleado_id) : array|stdClass
    {
        $existe = Transaccion::of(new gt_ejecutor_compra($this->link))
            ->existe(filtro: ['gt_ejecutor_compra.em_empleado_id' => $em_empleado_id]);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al comprobar si el empleado esta autorizado para hacer ordenes de compra',
                data: $existe);
        }

        if ($existe->n_registros <= 0) {
            return $this->error->error(mensaje: 'Error el empleado no es un ejecutor autorizado',
                data: $existe);
        }

        return $existe;
    }

    /**
     * Verifica el estado de una cotización en función de su etapa.
     *
     * @param array $registros Un array que contiene información relevante sobre la cotización.
     *
     * @return array|stdClass Devuelve un array o un objeto que indica el estado de la cotización.
     * @throws Exception Si ocurre un error al verificar el estado de la cotización.
     */
    public function verificar_estado_cotizacion(array $registros): array|stdClass
    {
        $filtro['gt_cotizacion_etapa.gt_cotizacion_id'] = $registros['gt_cotizacion_id'];
        $filtro['gt_cotizacion.etapa'] = "AUTORIZADO";
        $etapa = (new gt_cotizacion_etapa($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al filtrar etapa de la cotizacion', data: $etapa);
        }

        if ($etapa->n_registros <= 0) {
            return $this->error->error(mensaje: 'Error la cotizacion no se encuentra AUTORIZADA', data: $etapa);
        }

        return $etapa;
    }





    public function alta_relacion_cotizacion_orden_compra(int $gt_cotizacion_id, int $gt_orden_compra_id): array|stdClass
    {
        $registros = array();
        $registros['gt_cotizacion_id'] = $gt_cotizacion_id;
        $registros['gt_orden_compra_id'] = $gt_orden_compra_id;
        $alta = (new gt_orden_compra_cotizacion($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar insercion de datos para cotizacion y orden de compra',
                data: $alta);
        }

        return $alta;
    }





    public function genera_orden_compra(int $gt_cotizacion_id) : array|stdClass
    {
        $productos = $this->cotizacion_productos(gt_cotizacion_id: $gt_cotizacion_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener productos', data: $productos);
        }

        $alta_productos = $this->alta_orden_productos(datos: $productos, gt_cotizacion_id: $gt_cotizacion_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener productos', data: $productos);
        }

        return $alta_productos;
    }

    public function cotizacion_productos(int $gt_cotizacion_id)
    {
        $filtro['gt_cotizacion_producto.gt_cotizacion_id'] = $gt_cotizacion_id;

        $datos = (new gt_cotizacion_producto($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error obtener productos de la cotizacion', data: $datos);
        }

        return $datos;
    }

    public function alta_orden_productos(stdClass $datos, int $gt_cotizacion_id) : array
    {
        $descripcion =  $this->get_codigo_aleatorio();

        $alta_orden = $this->alta_orden_compra(descripcion: $descripcion);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error insertar orden compra', data: $alta_orden);
        }

        $alta_relacion = $this->alta_relacion_orden_compra_cotizacion(gt_cotizacion_id: $gt_cotizacion_id,
            gt_orden_compra_id: $alta_orden->registro_id, descripcion: $descripcion);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error insertar relacion orden compra cotizacion', data: $alta_relacion);
        }

        $registros = $datos->registros;

        foreach ($registros as $registro){
            $alta['gt_orden_compra_id'] = $alta_orden->registro_id;
            $alta['com_producto_id'] = $registro['com_producto_id'];
            $alta['cat_sat_unidad_id'] = $registro['cat_sat_unidad_id'];
            $alta['cantidad'] = $registro['gt_solicitud_producto_cantidad'];
            $alta['precio'] = $registro['com_producto_precio'];

            $alta = (new gt_orden_compra_producto($this->link))->alta_registro(registro: $alta);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al dar de alta orden compra producto', data: $alta);
            }
        }

        return $registros;
    }

    private function alta_orden_compra(string $descripcion)
    {
        $registros['descripcion'] = $descripcion;

        $alta = (new gt_orden_compra($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al dar de alta orden compra', data: $alta);
        }

        return $alta;
    }

    private function alta_relacion_orden_compra_cotizacion(int $gt_cotizacion_id, int $gt_orden_compra_id, string $descripcion)
    {
        $registros['gt_cotizacion_id'] = $gt_cotizacion_id;
        $registros['gt_orden_compra_id'] = $gt_orden_compra_id;
        $registros['descripcion'] = $descripcion;

        $alta = (new gt_orden_compra_cotizacion($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al dar de alta relacion orden compra cotizacion', data: $alta);
        }

        return $alta;
    }
}