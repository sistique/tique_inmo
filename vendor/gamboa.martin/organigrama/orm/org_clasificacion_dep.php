<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;
use PDO;


class org_clasificacion_dep extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_clasificacion_dep';
        $columnas = array($tabla=>false);

        $campos_obligatorios = array();
        $no_duplicados = array();
        $tipo_campos = array();

        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['codigo_bis'] = array('type' => 'inputs');

        $childrens['org_departamento'] ="gamboamartin\organigrama\models";

        $columnas_extra['org_clasificacion_dep_n_departamentos'] = /** @lang sql */
            "(SELECT COUNT(*) FROM org_departamento WHERE org_departamento.org_clasificacion_dep_id = org_clasificacion_dep.id)";

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            campos_view: $campos_view, columnas_extra: $columnas_extra, no_duplicados: $no_duplicados,
            tipo_campos: $tipo_campos, childrens: $childrens);
        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Clasificacion de departamento';
    }

}