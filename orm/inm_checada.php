<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use DateTime;
use gamboamartin\errores\errores;
use gamboamartin\plugins\exportador;
use IntlDateFormatter;
use PDO;
use stdClass;


class inm_checada extends _modelo_parent{
    public string $hora_limite = '09:15:00';
    public function __construct(PDO $link)
    {
        $tabla = 'inm_checada';
        $columnas = array($tabla=>false,'inm_empleado'=>$tabla,'inm_status_asistencia'=>$tabla,
            'inm_periodo_asistencia'=>$tabla,'inm_tipo_checada'=>$tabla, 'inm_horario' => 'inm_empleado');

        $columnas_extra= array();
        $sql = "(UPPER(DAYNAME(inm_checada.fecha)))";

        $columnas_extra['inm_checada_dia'] = $sql;

        $sql = "(SELECT inm_horario_diario.hora_entrada 
                 FROM inm_horario_diario 
                 LEFT JOIN inm_dia_semana 
                   ON inm_dia_semana.id = inm_horario_diario.inm_dia_semana_id 
                 WHERE inm_dia_semana.descripcion = UPPER(DAYNAME(inm_checada.fecha)) AND inm_horario_diario.inm_horario_id = inm_horario.id
                )";

        $columnas_extra['inm_checada_hora_esperada'] = $sql;
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Checada';
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
            $descripcion .= ' ' . $this->registro['fecha'];
            $descripcion .= ' ' . $this->registro['hora'] . rand();
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
            $registro_empleado = (new inm_empleado(link: $this->link))->registro(
                registro_id: $this->registro['inm_empleado_id']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar checada', data: $registro_empleado);
            }

            $filtro_rango_peri[$this->registro['fecha']]['valor1'] = 'inm_periodo_asistencia.fecha_inicio';
            $filtro_rango_peri[$this->registro['fecha']]['valor2'] = 'inm_periodo_asistencia.fecha_fin';
            $filtro_rango_peri[$this->registro['fecha']]['valor_campo'] = true;

            $order = array('inm_periodo_asistencia.fecha_inicio'=>'DESC');
            $filtro_peri['inm_horario.id'] = $registro_empleado['inm_horario_id'];
            $r_periodo_asistencia = (new inm_periodo_asistencia(link:$this->link))->filtro_and(filtro: $filtro_peri,
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

    /**
     * @throws \DateMalformedStringException
     */
    public function inserta_auto(): array|stdClass{
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
            if($registro_empleado['inm_empleado_calcula'] === 'activo'){
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

                    $filtro_rango_peri[$fecha]['valor1'] = 'inm_periodo_asistencia.fecha_inicio';
                    $filtro_rango_peri[$fecha]['valor2'] = 'inm_periodo_asistencia.fecha_fin';
                    $filtro_rango_peri[$fecha]['valor_campo'] = true;

                    $order = array('inm_periodo_asistencia.fecha_inicio'=>'DESC');
                    $filtro_peri['inm_horario.id'] = $registro_empleado['inm_horario_id'];
                    $r_periodo_asistencia = (new inm_periodo_asistencia(link:$this->link))->filtro_and(filtro: $filtro_peri,
                        filtro_rango: $filtro_rango_peri, limit: 1, order: $order);
                    if (errores::$error) {
                        return $this->error->error(mensaje: 'Error al obtener periodos', data: $r_periodo_asistencia);
                    }

                    if($r_periodo_asistencia->n_registros <= 0){
                        return $this->error->error(mensaje: 'Error no existe periodo de asistencia',
                            data: $r_periodo_asistencia);
                    }

                    $fecha_fin = (new DateTime($r_periodo_asistencia->registros[0]['inm_periodo_asistencia_fecha_inicio']))
                        ->modify("+30 days")->format('Y-m-d');

                    $registro = $this->generar_asistencias(
                        inm_empleado_id: $registro_empleado['inm_empleado_id'], fecha: $fecha,
                        fecha_inicio: $r_periodo_asistencia->registros[0]['inm_periodo_asistencia_fecha_inicio'],
                        fecha_fin: $fecha_fin,
                        hora_entrada: $r_horarios->registros[0]['inm_horario_diario_hora_entrada']);
                    if (errores::$error) {
                        return $this->error->error(mensaje: 'Error al obtener tipo checada', data: $registro);
                    }
                    
                    $r_alta_bd = $this->alta_registro(registro: $registro);
                    if (errores::$error) {
                        return $this->error->error(mensaje: 'Error al insertar checada', data: $r_alta_bd);
                    }

                    $r_altas[] = $r_alta_bd;
                }
            }
        }

        return $r_altas;
    }

    /**
     * @throws \DateMalformedStringException
     */
    function generar_asistencias($inm_empleado_id, $fecha, $fecha_inicio, $fecha_fin, $hora_entrada = "09:00:00",
                                 $porc_inasistencias = 3, $porc_retardos = 10)
    {
        $inicio = new DateTime($fecha_inicio);
        $fin = new DateTime($fecha_fin);
        $hoy = new DateTime($fecha);

        $diasTotal = $inicio->diff($fin)->days + 1;
        $diasTranscurridos = max(0, $inicio->diff($hoy)->days + 1);

        $progreso = min(1, $diasTranscurridos / $diasTotal);

        $porc_inasistencias_real = min(100, $porc_inasistencias + ($progreso * (100 - $porc_inasistencias)));
        $porc_retardos_real = min(100, $porc_retardos + ($progreso * (100 - $porc_retardos)));

        $filtro_especial[0][$fecha_inicio]['operador'] = '<=';
        $filtro_especial[0][$fecha_inicio]['valor'] = 'inm_checada.fecha';
        $filtro_especial[0][$fecha_inicio]['comparacion'] = 'AND';
        $filtro_especial[0][$fecha_inicio]['valor_es_campo'] = true;

        $filtro_especial[1][$fecha_fin]['operador'] = '>=';
        $filtro_especial[1][$fecha_fin]['valor'] = 'inm_checada.fecha';
        $filtro_especial[1][$fecha_fin]['comparacion'] = 'AND';
        $filtro_especial[1][$fecha_fin]['valor_es_campo'] = true;

        $order = array('inm_checada.fecha'=>'DESC');

        $filtro_che['inm_empleado.id'] = $inm_empleado_id;
        $filtro_che['inm_tipo_checada.id'] = 1;
        $filtro_che['inm_status_asistencia.id'] = 2;
        $r_checada = (new inm_checada(link:$this->link))->filtro_and(filtro: $filtro_che,
            filtro_especial: $filtro_especial, order: $order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener periodos', data: $r_checada);
        }

        $retardosExistentes = 0;
        if($r_checada->n_registros > 0){
            $retardosExistentes = $r_checada->n_registros;
        }

        $filtro_che['inm_status_asistencia.id'] = 3;
        $r_checada = (new inm_checada(link:$this->link))->filtro_and(filtro: $filtro_che,
            filtro_especial: $filtro_especial, order: $order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener periodos', data: $r_checada);
        }

        $faltasExistentes = 0;
        if($r_checada->n_registros > 0){
            $faltasExistentes = $r_checada->n_registros;
        }

        $maxFaltas = max(1, ceil($diasTotal * $porc_inasistencias / 100));
        $maxRetardos = max(1, ceil($diasTotal * $porc_retardos / 100));

        if ($faltasExistentes < $maxFaltas && mt_rand(1, 100) <= $porc_inasistencias_real) {
            $hora = date('H:i:s');
        } else if ($retardosExistentes < $maxRetardos && mt_rand(1, 100) <= $porc_retardos_real) {
            $min = mt_rand(15, 60);
            $seg = mt_rand(1, 59);
            $hora = (new DateTime($hora_entrada))->modify("+{$min} minutes")
                ->modify("+{$seg} seconds")->format('H:i:s');
        } else {
            $min = mt_rand(0, 15);
            $seg = mt_rand(0, 59);
            $hora = (new DateTime($hora_entrada))->modify("+{$min} minutes")
                ->modify("+{$seg} seconds")->format('H:i:s');
        }

        return [
            'inm_empleado_id' => $inm_empleado_id,
            'fecha'           => $fecha,
            'hora'            => $hora,
        ];
    }

    public function genera_reporte(string $path_base, int $registro_id){
        $nombre_hojas = array('Checadas');

        $r_periodo_asistencia = (new inm_periodo_asistencia(link: $this->link))->registro(registro_id: $registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener prospecto_ubicacions', data: $r_periodo_asistencia);
        }

        $nombre = $r_periodo_asistencia['inm_periodo_asistencia_descripcion'];

        $filtro['inm_periodo_asistencia.id'] = $registro_id;
        $result = (new inm_checada(link: $this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener prospecto_ubicacions', data: $result);
        }

        $ths[] = array('etiqueta'=>'Inicio Semana', 'campo'=>'inm_periodo_asistencia_fecha_inicio');
        $ths[] = array('etiqueta'=>'Fin Semana', 'campo'=>'inm_periodo_asistencia_fecha_fin');
        $ths[] = array('etiqueta'=>'Empleado', 'campo'=>'inm_empleado_razon_social');
        $ths[] = array('etiqueta'=>'Fecha', 'campo'=>'inm_checada_fecha');
        $ths[] = array('etiqueta'=>'Dia', 'campo'=>'inm_checada_dia');
        $ths[] = array('etiqueta'=>'Hora Esperada', 'campo'=>'inm_checada_hora_esperada');
        $ths[] = array('etiqueta'=>'Hora de Entrada', 'campo'=>'inm_checada_hora');
        $ths[] = array('etiqueta'=>'Minutos de Retraso', 'campo'=>'inm_checada_minutos_retraso');
        $ths[] = array('etiqueta'=>'Estatus', 'campo'=>'inm_status_asistencia_descripcion');

        $keys_hojas['Checadas'] = new stdClass();
        $keys_hojas['Checadas']->keys = $ths;
        $keys_hojas['Checadas']->registros = $result->registros;

        $xls = (new exportador())->genera_xls(header: false, name: $nombre, nombre_hojas: $nombre_hojas,
            keys_hojas: $keys_hojas, path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener xls', data: $xls);
        }

        return $xls;
    }
}