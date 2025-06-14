<?php

namespace gamboamartin\inmuebles\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class inm_bitacora_status_comprador extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'inm_bitacora_status_comprador';
        $columnas = array($tabla=>false,'inm_status_comprador'=>$tabla,'inm_comprador'=>$tabla);

        $columnas_extra= array();
        $renombres= array();


        parent::__construct(link: $link, tabla: $tabla, columnas: $columnas, columnas_extra: $columnas_extra,
            renombres: $renombres);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Bitacora Status comprador';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        if(!isset($this->registro['descripcion'])){
            $descripcion = $this->registro['inm_comprador_id'];
            $descripcion .= ' '.$this->registro['inm_status_comprador_id'];
            $descripcion .= ' '.$this->registro['fecha_status'];
            $this->registro['descripcion'] = $descripcion;
        }

        if(!isset($this->registro['codigo'])){
            $descripcion = $this->registro['inm_comprador_id'];
            $descripcion .= ' '.$this->registro['inm_status_comprador_id'];
            $descripcion .= ' '.$this->registro['fecha_status'] . rand();
            $this->registro['codigo'] = $descripcion;
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar comprador',data:  $r_alta_bd);
        }
        
        $filtro_status['inm_status_comprador.id'] = $this->registro['inm_status_comprador_id'];
        $r_inm_status_comprador = (new inm_status_comprador(link: $this->link))->filtro_and(filtro: $filtro_status);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al ajustar status de prospecto',data:  $r_inm_status_comprador);
        }

        $regitros_mod['status'] = 'activo';
        if($r_inm_status_comprador->n_registros > 0){
            if($r_inm_status_comprador->registros[0]['inm_status_comprador_es_cancelado'] === 'activo') {
                $regitros_mod['status'] = 'inactivo';
            }
        }

        $regitros_mod['inm_status_comprador_id'] = $this->registro['inm_status_comprador_id'];
        $r_modifica_bd = (new inm_comprador(link: $this->link))->modifica_bd(registro: $regitros_mod,
            id: $this->registro['inm_comprador_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al ajustar status de comprador',data:  $r_modifica_bd);
        }

        return $r_alta_bd;
    }

}