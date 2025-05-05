<?php

namespace gamboamartin\cat_sat\models;

use base\orm\_defaults;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;

class cat_sat_tipo_producto extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'cat_sat_tipo_producto';

        $columnas = array($tabla => false);

        $campos_obligatorios[] = 'descripcion';

        $columnas_extra['cat_sat_tipo_producto_n_divisiones'] = "(SELECT COUNT(*) FROM cat_sat_division_producto 
        WHERE cat_sat_division_producto.cat_sat_tipo_producto_id = cat_sat_tipo_producto.id)";

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            columnas_extra: $columnas_extra);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Producto';

        $this->id_code = true;


    }
}