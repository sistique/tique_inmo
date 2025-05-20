<?php

require "init.php";
require 'vendor/autoload.php';

$_SESSION['usuario_id'] = 2;

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\_dropbox;

$con = new conexion();
$link = conexion::$link;

$nombre_doc = 'hola.pdf';
$name = 'archivos/doc_documento/9.713642539828.pdf';

$guarda = (new _dropbox(link: $link))->upload(archivo_drop: $nombre_doc, archivo_local: $name);
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}
exit;