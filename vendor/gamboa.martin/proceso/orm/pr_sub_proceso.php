<?php
namespace gamboamartin\proceso\models;
use base\orm\_modelo_parent;

use PDO;


class pr_sub_proceso extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'pr_sub_proceso';
        $columnas = array($tabla=>false,'pr_proceso'=>$tabla,'adm_seccion'=>$tabla,'pr_tipo_proceso'=>'pr_proceso');
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos = array();


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;
    }



}