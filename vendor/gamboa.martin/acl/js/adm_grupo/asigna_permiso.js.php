<?php
namespace gamboamartin\acl\java;

use gamboamartin\errores\errores;
use gamboamartin\js_base\base;

$js = new base();

$get_adm_seccion = $js->sl_exe_change_ajax(event: 'get_adm_seccion', key_parent_id: 'adm_menu_id');
if(errores::$error){
    $error = (new errores())->error(mensaje: 'Error al integrar selector',data:  $get_adm_seccion);
    print_r($error);
    die('Error');
}

$get_adm_accion = $js->sl_exe_change_ajax(event: 'get_adm_accion', key_parent_id: 'adm_seccion_id');
if(errores::$error){
    $error = (new errores())->error(mensaje: 'Error al integrar selector',data:  $get_adm_accion);
    print_r($error);
    die('Error');
}

echo $get_adm_seccion;
echo $get_adm_accion;

?>











