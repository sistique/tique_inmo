<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\_dropbox;

$con = new conexion();
$link = conexion::$link;

$guarda = (new _dropbox(link: $link))->refresh();
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}
exit;