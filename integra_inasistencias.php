<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_checada;

$con = new conexion();
$link = conexion::$link;

$modelo_inm_checada = new inm_checada(link: $link);
try {
    $guarda = $modelo_inm_checada->inserta_auto();
    if (errores::$error) {
        return $modelo_inm_checada->error->error('Error al guardar archivo', $guarda);
    }
} catch (DateMalformedStringException $e) {
    return $modelo_inm_checada->error->error('Error al guardar archivo', $e);
}

$guarda = $modelo_inm_checada->marca_inasistencia();
if (errores::$error) {
    return $modelo_inm_checada->error->error('Error al guardar archivo', $guarda);
}

print_r($guarda);
exit;