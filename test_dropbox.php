<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\_dropbox;

$con = new conexion();
$link = conexion::$link;
/*
$nombre_doc = 'inm_ubicacion/hola.pdf';
$name = 'archivos/doc_documento/9.858556784382.pdf';

$guarda = (new _dropbox(link: $link))->upload(archivo_drop: $nombre_doc, archivo_local: $name);
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}

$nombre_local = 'descarga.pdf';
$guarda = (new _dropbox(link: $link))->download(dropbox_id: 'id:zQQGljOYR2oAAAAAAAAHdw',archivo_local: $nombre_local);
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}

$guarda = (new _dropbox(link: $link))->preview(dropbox_id: 'id:zQQGljOYR2oAAAAAAAAHdw');
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}

$guarda = (new _dropbox(link: $link))->delete(dropbox_id: 'id:zQQGljOYR2oAAAAAAAAHdw');
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}

$nombre_local = 'archivos/doc_documento/21.718030211876.pdf';
$guarda = (new _dropbox(link: $link))->overwrite(dropbox_id: 'id:zQQGljOYR2oAAAAAAAAHdw',archivo_local: $nombre_local);
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}*/
exit;