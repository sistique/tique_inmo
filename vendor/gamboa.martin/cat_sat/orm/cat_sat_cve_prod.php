<?php

namespace gamboamartin\cat_sat\models;

use base\orm\_modelo_parent;
use PDO;

class cat_sat_cve_prod extends _modelo_parent
{
    public function __construct(PDO $link)
    {
        $tabla = 'cat_sat_cve_prod';
        $columnas = array($tabla => false);
        $campos_obligatorios[] = 'codigo';
        $campos_obligatorios[] = 'descripcion';

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, temp: true);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Productos y Servicios';
    }




}