<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_defaults;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;

class cat_sat_regimen_fiscal extends _modelo_parent{
    public function __construct(PDO $link, bool $aplica_transacciones_base = false){
        $tabla = 'cat_sat_regimen_fiscal';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigo'] = 'cod_int_0_3_numbers';
        $this->aplica_transacciones_base = $aplica_transacciones_base;


        parent::__construct(link: $link, tabla: $tabla, aplica_transacciones_base: $aplica_transacciones_base,
            campos_obligatorios: $campos_obligatorios, columnas: $columnas, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Regimen Fiscal';
        
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|\stdClass
    {
        $keys = array('codigo','descripcion');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al validar registro',data:  $valida);

        }

        $this->registro['id'] = $this->registro['codigo'];

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al insertar regimen fiscal',data:  $r_alta_bd);

        }
        return $r_alta_bd;

    }
}