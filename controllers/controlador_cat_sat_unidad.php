<?php

namespace gamboamartin\inmuebles\controllers;
use gamboamartin\errores\errores;
use stdClass;
use Throwable;

class controlador_cat_sat_unidad extends \gamboamartin\cat_sat\controllers\controlador_cat_sat_unidad {

    public function get_unidad(bool $header, bool $ws = true): array|stdClass
    {
        $filtro['cat_sat_unidad.codigo'] = $_GET['cat_sat_unidad_codigo'];
        $r_modelo = $this->modelo->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener datos',data:  $r_modelo,header: $header,ws: $ws);
        }

        if($r_modelo->n_registros <= 0){
            return $this->retorno_error(mensaje: 'Error no hay tipo persona',data:  $r_modelo,
                header:  $header,ws:  $ws);
        }

        if($header){
            $retorno = $_SERVER['HTTP_REFERER'];
            header('Location:'.$retorno);
            exit;
        }
        if($ws){
            header('Content-Type: application/json');
            try {
                echo json_encode($r_modelo->registros[0], JSON_THROW_ON_ERROR);
            }
            catch (Throwable $e){
                return $this->errores->error(mensaje: 'Error al maquetar estados',data:  $e);
            }
            exit;
        }

        return $r_modelo;
    }
}
