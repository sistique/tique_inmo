<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use DateTime;
use gamboamartin\errores\errores;
use IntlDateFormatter;
use PDO;
use stdClass;


class inm_checada extends _modelo_parent{
    public string $hora_limite = '09:15:00';
    public function __construct(PDO $link)
    {
        $tabla = 'inm_checada';
        $columnas = array($tabla=>false,'inm_empleado'=>$tabla,'inm_status_asistencia'=>$tabla,
            'inm_periodo_asistencia'=>$tabla,'inm_tipo_checada'=>$tabla);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Status Comprador';
    }


    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {

        if (!isset($this->registro['inm_tipo_checada_id'])) {
            $this->registro['inm_tipo_checada_id'] = 1;
        }
        
        $filtro_validacion['inm_checada.fecha'] = $this->registro['fecha'];
        $filtro_validacion['inm_empleado.id'] = $this->registro['inm_empleado_id'];
        $filtro_validacion['inm_tipo_checada.id'] = $this->registro['inm_tipo_checada_id'];
        $r_checada = $this->filtro_and(filtro: $filtro_validacion);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar checada', data: $r_checada);
        }

        if ($r_checada->n_registros > 0) {
            return array('mensaje'=>'Checada ya registrada');
        }

        if (!isset($this->registro['descripcion'])) {
            $descripcion = $this->registro['inm_empleado_id'];
            $descripcion .= ' ' . $this->registro['fecha'];
            $descripcion .= ' ' . $this->registro['hora'];
            $this->registro['descripcion'] = $descripcion;
        }

        if (!isset($this->registro['codigo'])) {
            $descripcion = $this->registro['inm_empleado_id'];
            $descripcion .= ' ' . $this->registro['fecha'] . rand();
            $this->registro['codigo'] = $descripcion;
        }


        if (!isset($this->registro['minutos_retraso'])) {
            $this->registro['minutos_retraso'] = 0;

            $registro_empleado = (new inm_empleado(link: $this->link))->registro(
                registro_id: $this->registro['inm_empleado_id']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar checada', data: $registro_empleado);
            }

            $fmt = new IntlDateFormatter(
                'es_MX',
                IntlDateFormatter::FULL,
                IntlDateFormatter::NONE,
                null,
                null,
                'EEEE'
            );

            $dia_semana = mb_strtoupper($fmt->format(new DateTime($this->registro['fecha'])), 'UTF-8');

            $filtro_horarios['inm_horario.id'] = $registro_empleado['inm_horario_id'];
            $filtro_horarios['inm_dia_semana.descripcion'] = $dia_semana;
            $filtro_horarios['inm_horario_diario.status'] = 'activo';
            $r_horarios = (new inm_horario_diario(link:$this->link))->filtro_and(filtro: $filtro_horarios);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar checada', data: $r_horarios);
            }

            if($r_horarios->n_registros > 0) {
                $hora_entrada = new DateTime($r_horarios->registros[0]['inm_horario_diario_hora_entrada']);
                $hora_actual = new DateTime($this->registro['hora']);

                if($hora_actual > $hora_entrada) {
                    $diferencia = $hora_entrada->diff($hora_actual);
                    $minutos_retraso = ($diferencia->h * 60) + $diferencia->i;
                    $this->registro['minutos_retraso'] = $minutos_retraso;
                }
            }
        }

        if (!isset($this->registro['inm_status_asistencia_id'])) {
            if($this->registro['inm_tipo_checada_id'] === 1){
                $filtro_rango_poli[$this->registro['hora']]['valor1'] = 'inm_politica_asistencia.hora_inicio';
                $filtro_rango_poli[$this->registro['hora']]['valor2'] = 'inm_politica_asistencia.hora_fin';
                $filtro_rango_poli[$this->registro['hora']]['valor_campo'] = true;

                $order = array('inm_politica_asistencia.hora_inicio'=>'DESC');

                $r_politica_asistencia = (new inm_politica_asistencia(link:$this->link))->filtro_and(
                    filtro_rango: $filtro_rango_poli, limit: 1, order: $order);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al obtener politicas', data: $r_politica_asistencia);
                }

                if($r_politica_asistencia->n_registros <= 0){
                    return $this->error->error(mensaje: 'Error no existe politica de asistencia',
                        data: $r_politica_asistencia);
                }

                $this->registro['inm_status_asistencia_id'] =
                    $r_politica_asistencia->registros[0]['inm_status_asistencia_id'];
            }else{
                $this->registro['inm_status_asistencia_id'] = 1;
            }
        }

        if (!isset($this->registro['inm_periodo_asistencia_id'])) {
            $filtro_rango_peri[$this->registro['fecha']]['valor1'] = 'inm_periodo_asistencia.fecha_inicio';
            $filtro_rango_peri[$this->registro['fecha']]['valor2'] = 'inm_periodo_asistencia.fecha_fin';
            $filtro_rango_peri[$this->registro['fecha']]['valor_campo'] = true;

            $order = array('inm_periodo_asistencia.fecha_inicio'=>'DESC');

            $r_periodo_asistencia = (new inm_periodo_asistencia(link:$this->link))->filtro_and(
                filtro_rango: $filtro_rango_peri, limit: 1, order: $order);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener periodos', data: $r_periodo_asistencia);
            }

            if($r_periodo_asistencia->n_registros <= 0){
                return $this->error->error(mensaje: 'Error no existe periodo de asistencia',
                    data: $r_periodo_asistencia);
            }

            $this->registro['inm_periodo_asistencia_id'] =
                $r_periodo_asistencia->registros[0]['inm_periodo_asistencia_id'];
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar checada', data: $r_alta_bd);
        }

        return $r_alta_bd;
    }

    public function marca_inasistencia(): array|stdClass
    {
        $filtro_politica['inm_status_asistencia.descripcion'] = 'INASISTENCIA';

        $filtro_rango[date('H:i:s')]['valor1'] = 'inm_politica_asistencia.hora_inicio';
        $filtro_rango[date('H:i:s')]['valor2'] = 'inm_politica_asistencia.hora_fin';
        $filtro_rango[date('H:i:s')]['valor_campo'] = true;

        $r_politica_asistencia = (new inm_politica_asistencia(link:$this->link))->filtro_and(filtro: $filtro_politica,
            filtro_rango: $filtro_rango, limit: 1);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener politicas', data: $r_politica_asistencia);
        }

        if($r_politica_asistencia->n_registros <= 0){
            return array('Mensaje'=>'No existe politica de asistencia');
        }

        $registros_empleados = (new inm_empleado(link:$this->link))->registros_activos();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar checada', data: $registros_empleados);
        }

        $fecha = date('Y-m-d');

        $fmt = new IntlDateFormatter(
            'es_MX',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            null,
            null,
            'EEEE'
        );

        $dia_semana = mb_strtoupper($fmt->format(new DateTime($fecha)), 'UTF-8');

        $r_altas = array();
        foreach ($registros_empleados as $registro_empleado) {
            $filtro_horarios['inm_horario.id'] = $registro_empleado['inm_horario_id'];
            $filtro_horarios['inm_dia_semana.descripcion'] = $dia_semana;
            $filtro_horarios['inm_horario_diario.status'] = 'activo';
            $r_horarios = (new inm_horario_diario(link:$this->link))->filtro_and(filtro: $filtro_horarios);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar checada', data: $r_horarios);
            }

            if($r_horarios->n_registros > 0){
                $filtro['inm_checada.fecha'] = $fecha;
                $filtro['inm_empleado.id'] = $registro_empleado['inm_empleado_id'];
                $r_checada = $this->filtro_and(filtro: $filtro);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar checada', data: $r_checada);
                }

                if ($r_checada->n_registros > 0) {
                    continue;
                }

                $filtro_excepcion = [
                    'inm_excepcion_asistencia.inm_empleado_id' => $registro_empleado['inm_empleado_id'],
                    'inm_excepcion_asistencia.status' => 'activo'
                ];

                $filtro_rango_excep[$fecha]['valor1'] = 'inm_excepcion_asistencia.fecha_inicio';
                $filtro_rango_excep[$fecha]['valor2'] = 'inm_excepcion_asistencia.fecha_fin';
                $filtro_rango_excep[$fecha]['valor_campo'] = true;

                $r_excepcion = (new inm_excepcion_asistencia(link:$this->link))->filtro_and(filtro: $filtro_excepcion,
                    filtro_rango: $filtro_rango_excep);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al verificar excepciones', data: $r_excepcion);
                }

                $tipo = (new inm_tipo_checada(link:$this->link))->filtro_and(filtro: ['descripcion' => 'ENTRADA']);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al obtener tipo checada', data: $tipo);
                }

                if ($r_excepcion->n_registros > 0) {
                    $status = (new inm_status_asistencia(link:$this->link))->filtro_and(filtro: ['descripcion' => 'EN TIEMPO']);
                    if (errores::$error) {
                        return $this->error->error(mensaje: 'Error al obtener status asistencia EN TIEMPO', data: $status);
                    }

                    $registro = [
                        'inm_empleado_id' => $registro_empleado['inm_empleado_id'],
                        'fecha' => $fecha,
                        'hora' => '09:00:00',
                        'inm_status_asistencia_id' => $status->registros[0]['inm_status_asistencia_id'],
                        'inm_tipo_checada_id' => $tipo->registros[0]['inm_tipo_checada_id'],
                        'observaciones' => 'Checada generada por excepción de asistencia'
                    ];
                } else {
                    $status = (new inm_status_asistencia(link:$this->link))->filtro_and(filtro: ['descripcion' => 'INASISTENCIA']);
                    if (errores::$error) {
                        return $this->error->error(mensaje: 'Error al obtener status asistencia INASISTENCIA', data: $status);
                    }

                    $registro = [
                        'inm_empleado_id' => $registro_empleado['inm_empleado_id'],
                        'fecha' => $fecha,
                        'hora' => date('H:i:s'),
                        'inm_status_asistencia_id' => $status->registros[0]['inm_status_asistencia_id'],
                        'inm_tipo_checada_id' => $tipo->registros[0]['inm_tipo_checada_id'],
                        'observaciones' => 'Checada generada automáticamente por inasistencia'
                    ];
                }

                $r_alta_bd = $this->alta_registro(registro: $registro);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al insertar checada', data: $r_alta_bd);
                }

                $r_altas[] = $r_alta_bd;
            }
        }

        return $r_altas;
    }
}