<?php
namespace gamboamartin\banco\models;
use base\orm\_modelo_parent;
use PDO;

class bn_sucursal extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'bn_sucursal';
        $columnas = array($tabla=>false,'bn_banco'=>$tabla,'bn_tipo_sucursal'=>$tabla,'bn_tipo_banco'=>'bn_banco');
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigos'] = 'cod_1_letras_mayusc';

        $columnas_extra['bn_cuenta_n_cuentas'] = /** @lang sql */
            "(SELECT COUNT(*) FROM bn_cuenta WHERE bn_cuenta.bn_sucursal_id = bn_sucursal.id)";


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;
    }


}