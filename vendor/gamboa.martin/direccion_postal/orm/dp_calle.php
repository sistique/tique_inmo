<?php
namespace gamboamartin\direccion_postal\models;
use base\orm\_defaults;
use base\orm\_modelo_parent;


use gamboamartin\errores\errores;
use PDO;
use stdClass;

class dp_calle extends _modelo_parent {
    public function __construct(PDO $link){
        $tabla = 'dp_calle';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['descripcion'] = array('type' => 'inputs');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Calle';




    }



    public function get_calle(int $dp_calle_id): array|stdClass
    {
        $registro = $this->registro(registro_id: $dp_calle_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener calle',data:  $registro);
        }

        return $registro;
    }


}