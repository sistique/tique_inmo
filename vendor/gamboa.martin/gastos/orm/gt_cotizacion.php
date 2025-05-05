<?php

namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent_sin_codigo;
use base\orm\modelo;
use Exception;
use gamboamartin\errores\errores;
use gamboamartin\gastos\controllers\constantes;
use gamboamartin\system\_ctl_parent_sin_codigo;
use PDO;
use stdClass;

class gt_cotizacion extends _modelo_parent_sin_codigo
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_cotizacion';
        $columnas = array($tabla => false, 'gt_tipo_cotizacion' => $tabla, 'gt_centro_costo' => $tabla,
            'gt_tipo_centro_costo' => 'gt_centro_costo', 'gt_proveedor' => $tabla);
        $campos_obligatorios = array();

        $no_duplicados = array();


        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $gt_requisicion_id = $this->registro['gt_requisicion_id'];

        $estado = (new gt_requisicion($this->link))->registro(registro_id: $gt_requisicion_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener requisicion', data: $estado);
        }

        if ($estado['gt_requisicion_etapa'] != constantes::PR_ETAPA_AUTORIZADO->value) {
            return $this->error->error(mensaje: "Error la requisicion no se encuentra AUTORIZADA, etapa actual: {$estado['gt_requisicion_etapa']}", data: $estado);
        }

        $acciones_cotizador = $this->acciones_cotizador();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar acciones para el cotizador', data: $acciones_cotizador);
        }

        $acciones_requisicion = $this->acciones_requisicion();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar acciones para requisicion', data: $acciones_requisicion);
        }

        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar cotizacion', data: $r_alta_bd);
        }

        $relacion_requisitores = $this->acciones_cotizadores(gt_cotizacion_id: $r_alta_bd->registro_id,
            gt_cotizador_id: $acciones_cotizador->registros[0]['gt_cotizador_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar relacion entre el requisicion y la requisitor',
                data: $relacion_requisitores);
        }

        $relacion = $this->alta_relacion_requisicion_cotizacion(gt_requisicion_id: $gt_requisicion_id,
            gt_cotizacion_id: $r_alta_bd->registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar relacion entre solicitud y requisicion', data: $relacion);
        }

        return $r_alta_bd;
    }

    /**
     * Da de alta una relación entre una cotización y un cotizador.
     *
     * @param int $gt_cotizacion_id El ID de la cotización.
     * @param int $gt_cotizador_id El ID del cotizador.
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado del alta de la relación.
     * @throws Exception Devuelve una excepción si ocurre un error al dar de alta la relación.
     */
    public function alta_cotizadores(int $gt_cotizacion_id, int $gt_cotizador_id): array|stdClass
    {
        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }
        $registros['descripcion'] = "Cotizacion - Cotizador";
        $registros['gt_cotizacion_id'] = $gt_cotizacion_id;
        $registros['gt_cotizador_id'] = $gt_cotizador_id;

        $alta = (new gt_cotizadores($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al dar de alta relacion entre cotizacion y la cotizador',
                data: $alta);
        }

        return $alta;
    }

    /**
     * Da de alta una relación entre una requisición y una cotización.
     *
     * @param int $gt_requisicion_id El ID de la requisición.
     * @param int $gt_cotizacion_id El ID de la cotización.
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado del alta de la relación.
     * @throws Exception Devuelve una excepción si ocurre un error al dar de alta la relación.
     */
    public function alta_relacion_requisicion_cotizacion(int $gt_requisicion_id, int $gt_cotizacion_id): array|stdClass
    {
        $registros = array();
        $registros['gt_cotizacion_id'] = $gt_cotizacion_id;
        $registros['gt_requisicion_id'] = $gt_requisicion_id;
        $alta = (new gt_cotizacion_requisicion($this->link))->alta_registro(registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ejecutar insercion de datos para solicitud y requisicion', data: $alta);
        }

        return $alta;
    }

    /**
     * Realiza acciones relacionadas con una requisición.
     *
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado de las acciones realizadas.
     * @throws Exception Devuelve una excepción si ocurre un error al realizar las acciones.
     */
    public function acciones_requisicion(): array|stdClass
    {
        $resultado = $this->verificar_estado_requisicion(registros: $this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al verificar etapa de la requisicion', data: $resultado);
        }

        $this->registro = $this->limpia_campos_extras(registro: $this->registro, campos_limpiar: array("gt_requisicion_id"));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $this->registro);
        }

        return $this->registro;
    }

    /**
     * Realiza acciones relacionadas con un cotizador.
     *
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado de las acciones realizadas.
     * @throws Exception Devuelve una excepción si ocurre un error al realizar las acciones.
     */
    public function acciones_cotizador() : array | stdClass
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
     * Realiza acciones relacionadas con la relación entre una cotización y un cotizador.
     *
     * @param int $gt_cotizacion_id El ID de la cotización.
     * @param int $gt_cotizador_id El ID del cotizador.
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado de las acciones realizadas.
     * @throws Exception Devuelve una excepción si ocurre un error al realizar las acciones.
     */
    public function acciones_cotizadores(int $gt_cotizacion_id, int $gt_cotizador_id) : array | stdClass
    {
        $alta_cotizadores = $this->alta_cotizadores(gt_cotizacion_id: $gt_cotizacion_id, gt_cotizador_id: $gt_cotizador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error insertar relacion entre cotizacion y la cotizador',
                data: $alta_cotizadores);
        }

        return $alta_cotizadores;
    }

    /**
     * Valida si el usuario actual está autorizado para hacer cotizaciones.
     *
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado de la validación.
     * @throws Exception Devuelve una excepción si ocurre un error al validar si el usuario está autorizado para hacer cotizaciones.
     */
    public function validar_permiso_usuario() : array | stdClass
    {
        $existe = Transaccion::of(new gt_empleado_usuario($this->link))
            ->existe(filtro: ['gt_empleado_usuario.adm_usuario_id' => $_SESSION['usuario_id']]);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al comprobar si el usuario esta autorizado para hacer cotizaciones',
                data: $existe);
        }

        if ($existe->n_registros <= 0) {
            return $this->error->error(mensaje: 'Error el usuario no se encuentra autorizado para hacer cotizaciones',
                data: $existe);
        }

        return $existe;
    }

    /**
     * Valida si un empleado específico está autorizado para hacer cotizaciones.
     *
     * @param int $em_empleado_id El ID del empleado a verificar.
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado de la validación.
     * @throws Exception Devuelve una excepción si ocurre un error al validar si el empleado está autorizado para hacer cotizaciones.
     */
    public function validar_permiso_empleado(int $em_empleado_id) : array | stdClass
    {
        $existe = Transaccion::of(new gt_cotizador($this->link))
            ->existe(filtro: ['gt_cotizador.em_empleado_id' => $em_empleado_id]);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al comprobar si el empleado esta autorizado para hacer cotizaciones',
                data: $existe);
        }

        if ($existe->n_registros <= 0) {
            return $this->error->error(mensaje: 'Error el empleado no es un cotizador autorizado',
                data: $existe);
        }

        return $existe;
    }

    /**
     * Verifica si una requisición específica se encuentra en la etapa de "AUTORIZADO".
     *
     * @param array $registros Un array que contiene información sobre la requisición.
     * @return array|stdClass Devuelve un array o un objeto stdClass que representa el resultado de la verificación.
     * @throws Exception Devuelve una excepción si ocurre un error al verificar si la requisición se encuentra en la etapa de "AUTORIZADO".
     */
    public function verificar_estado_requisicion(array $registros): array|stdClass
    {
        $filtro['gt_requisicion_etapa.gt_requisicion_id'] = $registros['gt_requisicion_id'];
        $filtro['gt_requisicion.etapa'] = "AUTORIZADO";
        $etapa = (new gt_requisicion_etapa($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al filtrar etapa de la requisicion', data: $etapa);
        }

        if ($etapa->n_registros <= 0) {
            return $this->error->error(mensaje: 'Error la requisicion no se encuentra AUTORIZADA', data: $etapa);
        }

        return $etapa;
    }

}