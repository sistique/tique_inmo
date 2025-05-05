<?php
namespace gamboamartin\direccion_postal\models;
use base\orm\_modelo_parent;
use PDO;

class dp_pais extends _modelo_parent {
    public function __construct(PDO $link, bool $aplica_transacciones_base = false){
        $tabla = 'dp_pais';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['descripcion'] = array('type' => 'inputs');

        parent::__construct(link: $link, tabla: $tabla, aplica_transacciones_base: $aplica_transacciones_base,
            campos_obligatorios: $campos_obligatorios, columnas: $columnas, campos_view: $campos_view);
        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Pais';


    }

}