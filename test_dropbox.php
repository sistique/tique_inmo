<?php

require "init.php";
require 'vendor/autoload.php';

use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\_dropbox;

$nombre_doc = 'hola.pdf';
$name = 'archivos/doc_documento/9.713642539828.pdf';

$guarda = (new _dropbox())->refresh();
//$guarda = (new _dropbox())->upload(archivo_drop: $nombre_doc, archivo_local: $name);
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}
exit;