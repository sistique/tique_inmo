<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;
use PDO;

class org_tipo_puesto extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_tipo_puesto';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        $columnas_extra = array();
        $columnas_extra['org_tipo_puesto_n_puestos'] = /** @lang sql */
            "(SELECT COUNT(*) FROM org_puesto WHERE org_puesto.org_tipo_puesto_id = org_tipo_puesto.id)";

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra);
        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Puesto';
    }
}