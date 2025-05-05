<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_defaults;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class cat_sat_conf_imps extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'cat_sat_conf_imps';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        $tipo_campos['codigo'] = 'cod_int_0_3_numbers';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Configuraciones de Impuestos';
        $this->id_code = true;

    }

    final public function get_impuestos(int $cat_sat_conf_imps_id){

        $retencion = $this->get_retenciones(cat_sat_conf_imps_id: $cat_sat_conf_imps_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener configuracion',data:  $retencion);
        }
        $traslado = $this->get_traslados(cat_sat_conf_imps_id: $cat_sat_conf_imps_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener configuracion',data:  $traslado);
        }

        $data = new stdClass();
        $data->retenciones = $retencion;
        $data->traslados = $traslado;

        return $data;


    }

    /**
     * Ontiene las configuraciones de retencion
     * @param int $cat_sat_conf_imps_id
     * @return array
     */
    final public function get_retenciones(int $cat_sat_conf_imps_id): array
    {
        $filtro['cat_sat_conf_imps.id'] = $cat_sat_conf_imps_id;
        $r_retencion_conf = (new cat_sat_retencion_conf(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener configuracion',data:  $r_retencion_conf);
        }
        return $r_retencion_conf->registros;
    }
    final public function get_traslados(int $cat_sat_conf_imps_id){
        $filtro['cat_sat_conf_imps.id'] = $cat_sat_conf_imps_id;
        $r_traslado_conf = (new cat_sat_traslado_conf(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener configuracion',data:  $r_traslado_conf);
        }
        return $r_traslado_conf->registros; 

    }

}