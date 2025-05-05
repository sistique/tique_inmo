<?php

use base\conexion;
use gamboamartin\errores\errores;

$_SESSION['usuario_id'] = 2;

require "init.php";
require 'vendor/autoload.php';

$con = new conexion();
$link = conexion::$link;

$link->beginTransaction();
$administrador = new gamboamartin\administrador\instalacion\instalacion();

$instala = $administrador->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar administrador', data: $administrador);
    print_r($error);
    exit;
}


$proceso = new gamboamartin\proceso\instalacion\instalacion();
$instala = $proceso->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar proceso', data: $instala);
    print_r($error);
    exit;
}


$direccion_postal = new gamboamartin\direccion_postal\instalacion\instalacion();

$instala = $direccion_postal->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar direccion_postal', data: $instala);
    print_r($error);
    exit;
}



$cat_sat = new gamboamartin\cat_sat\instalacion\instalacion();

$instala = $cat_sat->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar cat_sat', data: $instala);
    print_r($error);
    exit;
}


$organigrama = new gamboamartin\organigrama\instalacion\instalacion();

$instala = $organigrama->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar organigrama', data: $instala);
    print_r($error);
    exit;
}

$documento = new gamboamartin\documento\instalacion\instalacion();

$instala = $documento->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar documento', data: $instala);
    print_r($error);
    exit;
}

$facturacion = new gamboamartin\banco\instalacion\instalacion();

$instala = $facturacion->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar facturacion', data: $instala);
    print_r($error);
    exit;
}


if($link->inTransaction()) {
    $link->commit();
}


