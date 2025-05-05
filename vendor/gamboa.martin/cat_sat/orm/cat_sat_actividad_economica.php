<?php
namespace gamboamartin\cat_sat\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class cat_sat_actividad_economica extends modelo{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_actividad_economica';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->etiqueta = 'Actividad Economica';
        $this->NAMESPACE = __NAMESPACE__;
    }

    final public function alta_bd(): array|stdClass
    {
        if(!isset($this->registro['codigo_bis'])){
            $this->registro['codigo_bis'] = $this->registro['codigo'];
        }
        if(!isset($this->registro['descripcion_select'])){
            $this->registro['descripcion_select'] = $this->registro['descripcion'];
        }
        if(!isset($this->registro['alias'])){
            $this->registro['alias'] = $this->registro['descripcion'];
        }
        $r_alta = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar actividad economica',data:  $r_alta);
        }
        return $r_alta;

    }
}