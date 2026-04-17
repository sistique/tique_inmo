<?php
namespace gamboamartin\nomina\instalacion;

use base\orm\modelo;
use base\orm\modelo_base;
use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{

    private function _add_fc_cancelacion(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'fc_cancelacion');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['cat_sat_motivo_cancelacion_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'fc_cancelacion');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;


        return $out;

    }

    private function fc_cancelacion(PDO $link): array|stdClass
    {
        $create = $this->_add_fc_cancelacion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }

    final public function instala(PDO $link): array|stdClass
    {

        $result = new stdClass();

        $fc_cancelacion = $this->fc_cancelacion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar fc_cancelacion', data:  $fc_cancelacion);
        }
        $result->fc_cancelacion = $fc_cancelacion;

        return $result;

    }

    final public function limpia(PDO $link): array|stdClass
    {

        $out = new stdClass();

        $modelos = array();
        $modelos[] = 'fc_cer_pem';

        foreach ($modelos as $modelo){

            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\facturacion\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }

            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }

            $out->$modelo = $del;

        }

        $modelos = array();
        $modelos[] = 'org_sucursal';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\organigrama\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }
            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }
            $out->$modelo = $del;

        }

        $modelos = array();
        $modelos[] = 'com_email_cte';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\comercial\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }
            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }
            $out->$modelo = $del;

        }

        $modelos = array();
        $modelos[] = 'not_rel_mensaje_etapa';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\notificaciones\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }
            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }
            $out->$modelo = $del;

        }

        $modelos = array();
        $modelos[] = 'doc_version';

        foreach ($modelos as $modelo){
            $modelo_new = modelo_base::modelo_new(link: $link,modelo:  $modelo,
                namespace_model: 'gamboamartin\\documento\\models');
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al generar modelo', data:  $modelo);
            }
            $del = $modelo_new->elimina_todo();
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al eliminar datos del modelo '.$modelo, data:  $del);
            }
            $out->$modelo = $del;

        }

        return $out;


    }
}
