<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;
$_SESSION['grupo_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_periodo_asistencia;
use gamboamartin\inmuebles\controllers\controlador_inm_periodo_asistencia;

$con = new conexion();
$link = conexion::$link;

$modelo_inm_periodo_asistencia = new inm_periodo_asistencia(link: $link);

$filtro['inm_periodo_asistencia.fecha_envio'] = date('Y-m-d');
$filtro['inm_periodo_asistencia.enviado'] = 'inactivo';
$r_periodos = $modelo_inm_periodo_asistencia->filtro_and(filtro: $filtro);
if (errores::$error) {
    return $modelo_inm_periodo_asistencia->error->error('Error al guardar archivo', $r_periodos);
}

$return = array();
foreach ($r_periodos->registros AS $registro){
    $return = $modelo_inm_periodo_asistencia->envia_reporte(registro_id: $registro['inm_periodo_asistencia']);
    if (errores::$error) {
        return $modelo_inm_periodo_asistencia->error->error('Error al guardar archivo', $return);
    }
}

print_r($return);
exit;