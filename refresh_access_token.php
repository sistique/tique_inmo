<?php

require "init.php";
require 'vendor/autoload.php';

use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\_dropbox;

$guarda = (new _dropbox())->refresh();
if (errores::$error) {
    return $this->error->error('Error al guardar archivo', $guarda);
}
exit;