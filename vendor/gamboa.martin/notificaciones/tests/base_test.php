<?php
namespace gamboamartin\notificaciones\tests;
use base\orm\modelo_base;

use gamboamartin\cat_sat\models\cat_sat_factor;
use gamboamartin\cat_sat\models\cat_sat_forma_pago;
use gamboamartin\cat_sat\models\cat_sat_metodo_pago;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\models\cat_sat_tipo_factor;
use gamboamartin\cat_sat\models\cat_sat_tipo_relacion;
use gamboamartin\comercial\models\com_producto;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\comercial\models\com_tipo_cambio;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_conf_retenido;
use gamboamartin\facturacion\models\fc_conf_traslado;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\facturacion\models\fc_factura;
use gamboamartin\facturacion\models\fc_factura_relacionada;
use gamboamartin\facturacion\models\fc_partida;


use gamboamartin\facturacion\models\fc_relacion;
use gamboamartin\organigrama\models\org_sucursal;
use PDO;


class base_test{

    public function alta_fc_factura_relacionada(PDO $link, int $fc_factura_id = 1,int $fc_relacion_id = 1, int $id = 1): array|\stdClass
    {

        $existe = (new fc_relacion($link))->existe_by_id(registro_id: $fc_relacion_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al verificar si existe ', data: $existe);
        }
        if(!$existe) {
            $alta = $this->alta_fc_relacion(link: $link, id: $fc_relacion_id);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }

        $existe = (new fc_factura($link))->existe_by_id(registro_id: $fc_factura_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al verificar si existe ', data: $existe);
        }
        if(!$existe) {
            $alta = $this->alta_fc_factura(link: $link, id: $fc_factura_id);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['fc_relacion_id'] = $fc_relacion_id;
        $registro['fc_factura_id'] = $fc_factura_id;

        $alta = (new fc_factura_relacionada($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);

        }
        return $alta;
    }


    


    public function del(PDO $link, string $name_model): array
    {

        $model = (new modelo_base($link))->genera_modelo(modelo: $name_model);
        $del = $model->elimina_todo();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar '.$name_model, data: $del);
        }
        return $del;
    }



    public function del_not_mensaje_etapa(PDO $link): array
    {

        $del = $this->del_not_rel_mensaje_etapa($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);

        }

        $del = $this->del($link, 'gamboamartin\\notificaciones\\models\\not_mensaje_etapa');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_not_rel_mensaje_etapa(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\notificaciones\\models\\not_rel_mensaje_etapa');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }




}
