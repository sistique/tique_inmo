<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent;
use PDO;

class cat_sat_metodo_pago extends _modelo_parent {

    public function __construct(PDO $link, bool $aplica_transacciones_base = false){
        $tabla = 'cat_sat_metodo_pago';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        $tipo_campos['codigos'] = 'cod_1_letras_mayusc';

        parent::__construct(link: $link, tabla: $tabla, aplica_transacciones_base: $aplica_transacciones_base,
            campos_obligatorios: $campos_obligatorios, columnas: $columnas, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Metodo de Pago';


    }
}