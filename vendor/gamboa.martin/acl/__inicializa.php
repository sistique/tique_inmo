<?php

use base\conexion;
use gamboamartin\administrador\instalacion\instalacion;
use gamboamartin\errores\errores;

$_SESSION['usuario_id'] = 2;

require "init.php";
require 'vendor/autoload.php';

$con = new conexion();
$link = conexion::$link;

$link->beginTransaction();

$administrador = new instalacion();

$instala = $administrador->instala(link: $link);

if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar administrador', data: $instala);
    print_r($error);
    exit;
}

$acl = new gamboamartin\acl\instalacion\instalacion();
$instala = $acl->instala(link: $link);

if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar acl', data: $instala);
    print_r($error);
    exit;
}

if($link->inTransaction()) {
    $link->commit();
}


