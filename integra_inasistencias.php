<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_checada;

$con = new conexion();
$link = conexion::$link;

$guarda = (new inm_checada(link: $link))->marca_inasistencia();
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}
exit;