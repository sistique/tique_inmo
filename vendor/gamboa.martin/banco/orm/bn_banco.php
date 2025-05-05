<?php
namespace gamboamartin\banco\models;
use base\orm\_modelo_parent;
use PDO;

class bn_banco extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'bn_banco';
        $columnas = array($tabla=>false,'bn_tipo_banco'=>$tabla);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigos'] = 'cod_1_letras_mayusc';

        $columnas_extra['bn_banco_n_sucursales'] = /** @lang sql */
            "(SELECT COUNT(*) FROM bn_sucursal WHERE bn_sucursal.bn_banco_id = bn_banco.id)";

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;
    }


}