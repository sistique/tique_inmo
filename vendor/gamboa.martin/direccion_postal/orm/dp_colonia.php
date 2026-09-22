<?php
namespace gamboamartin\direccion_postal\models;
use base\orm\_defaults;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class dp_colonia extends _modelo_parent {
    public function __construct(PDO $link){
        $tabla = 'dp_colonia';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        $campos_view['descripcion'] = array('type' => 'inputs');


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Colonia';




    }


    /**
     * Obtiene una colonia en base a su id
     * @param int $dp_colonia_id Identificador de colonia
     * @return array|stdClass
     * @version 1.9.6
     */
    public function get_colonia(int $dp_colonia_id): array|stdClass
    {
        if($dp_colonia_id <=0 ){
            return $this->error->error(mensaje: 'Error dp_colonia_id debe ser mayor a 0',data:  $dp_colonia_id);
        }
        $registro = $this->registro(registro_id: $dp_colonia_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener colonia',data:  $registro);
        }

        return $registro;
    }

    public function get_colonia_id(string $nombre_colonia): array|stdClass|null|int
    {
        if ($nombre_colonia === '') {
            return null;
        }

        $filtro['dp_colonia.descripcion'] = $nombre_colonia;
        $r_colonia = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener colonia',data:  $r_colonia);
        }

        if(count($r_colonia->registros) === 0){
            return null;
        }

        return $r_colonia->registros[0]['dp_colonia_id'];
    }

}