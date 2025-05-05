<?php
namespace gamboamartin\acl\java;

use gamboamartin\errores\errores;
use gamboamartin\js_base\base;

$js = new base();
$sl_exe_change_ajax = $js->sl_exe_change_ajax(event: 'get_adm_seccion', key_parent_id: 'adm_menu_id', debug_console: true);
if(errores::$error){
    $error = (new errores())->error(mensaje: 'Error al integrar selector',data:  $sl_exe_change_ajax);
    print_r($error);
    die('Error');
}

echo $sl_exe_change_ajax; ?>

