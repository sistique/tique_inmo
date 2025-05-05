<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent;
use PDO;

class cat_sat_subsidio extends _modelo_parent{

    public function __construct(PDO $link)
    {
        $tabla = 'cat_sat_subsidio';
        $columnas = array($tabla => false, "cat_sat_periodicidad_pago_nom" => $tabla);
        $campos_obligatorios[] = 'codigo';
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'limite_inferior';
        $campos_obligatorios[] = 'limite_superior';
        $campos_obligatorios[] = 'cuota_fija';
        $campos_obligatorios[] = 'porcentaje_excedente';
        $campos_obligatorios[] = 'cat_sat_periodicidad_pago_nom_id';

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, temp: true);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Subsidio';
    }
}