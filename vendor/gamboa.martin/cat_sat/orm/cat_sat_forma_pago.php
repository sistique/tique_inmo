<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent;
use PDO;

class cat_sat_forma_pago extends _modelo_parent{

    public function __construct(PDO $link, bool $aplica_transacciones_base = false){

        $tabla = 'cat_sat_forma_pago';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        parent::__construct(link: $link, tabla: $tabla, aplica_transacciones_base: $aplica_transacciones_base,
            campos_obligatorios: $campos_obligatorios, columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Forma Pago';

    }
}