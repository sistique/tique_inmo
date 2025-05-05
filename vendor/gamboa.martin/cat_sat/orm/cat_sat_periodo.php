<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class cat_sat_periodo extends _modelo_parent{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_periodo';
        $columnas = array($tabla=>false,'cat_sat_periodicidad_pago_nom' => $tabla);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Periodo';
        
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|\stdClass
    {
        $r_periodicidad = (new cat_sat_periodicidad_pago_nom(link: $this->link))->registro(
            registro_id: $this->registro['cat_sat_periodicidad_pago_nom_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar com tipo periodicidad', data: $r_periodicidad);
        }

        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $this->registro['fecha_inicio'].$this->registro['fecha_fin'].rand();
        }

        if(!isset($this->registro['descripcion'])){
            $this->registro['descripcion'] = $r_periodicidad['cat_sat_periodicidad_pago_nom_descripcion']." - ".
                $this->registro['fecha_inicio']." al ".$this->registro['fecha_fin'];
        }

        if(!isset($this->registro['descripcion_select'])){
            $this->registro['descripcion_select'] = $this->registro['descripcion'];
        }

        $_POST['fecha_inicial'] = $this->registro['fecha_inicio'];
        $_POST['fecha_final'] = $this->registro['fecha_fin'];

        $fechas = $this->fechas_in();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar fechas', data: $fechas);
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al insertar regimen fiscal',data:  $r_alta_bd);

        }
        return $r_alta_bd;

    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $r_periodicidad = (new cat_sat_periodicidad_pago_nom(link: $this->link))->registro(
            registro_id: $registro['cat_sat_periodicidad_pago_nom_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar com tipo periodicidad', data: $r_periodicidad);
        }

        if(!isset($registro['codigo'])){
            $registro['codigo'] = $registro['fecha_inicio'].$registro['fecha_fin'].rand();
        }

        if(!isset($registro['descripcion'])){
            $registro['descripcion'] = $r_periodicidad['cat_sat_periodicidad_pago_nom_descripcion']." - ".
                $registro['fecha_inicio']." al ".$registro['fecha_fin'];
        }

        if(!isset($registro['descripcion_select'])){
            $registro['descripcion_select'] = $registro['descripcion'];
        }

        $_POST['fecha_inicial'] = $registro['fecha_inicio'];
        $_POST['fecha_final'] = $registro['fecha_fin'];

        $fechas = $this->fechas_in();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar fechas', data: $fechas);
        }

        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar clase producto',data:  $r_modifica_bd);
        }
        return $r_modifica_bd;
    }
}