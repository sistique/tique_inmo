<?php
namespace gamboamartin\banco\models;
use base\orm\_modelo_parent;
use PDO;

class bn_tipo_cuenta extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'bn_tipo_cuenta';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigos'] = 'cod_1_letras_mayusc';

        $columnas_extra['bn_tipo_cuenta_n_cuentas'] = /** @lang sql */
            "(SELECT COUNT(*) FROM bn_cuenta WHERE bn_cuenta.bn_tipo_cuenta_id = bn_tipo_cuenta.id)";

        $no_duplicados = array('codigo','descripcion','codigo_bis','alias');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;
    }


}