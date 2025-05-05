<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent;
use PDO;

class org_asociado extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'org_asociado';
        $columnas = array($tabla=>false,'dp_calle_pertenece'=>$tabla);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigos'] = 'cod_1_letras_mayusc';


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;
    }


}