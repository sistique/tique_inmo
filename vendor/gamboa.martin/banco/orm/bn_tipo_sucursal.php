<?php
namespace gamboamartin\banco\models;
use base\orm\_modelo_parent;
use PDO;

class bn_tipo_sucursal extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'bn_tipo_sucursal';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigos'] = 'cod_1_letras_mayusc';

        $columnas_extra['bn_tipo_sucursal_n_sucursales'] = /** @lang sql */
            "(SELECT COUNT(*) FROM bn_sucursal WHERE bn_sucursal.bn_tipo_sucursal_id = bn_tipo_sucursal.id)";

        $no_duplicados = array('codigo','descripcion','codigo_bis','alias');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            columnas_extra: $columnas_extra, no_duplicados: $no_duplicados, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;
    }


}