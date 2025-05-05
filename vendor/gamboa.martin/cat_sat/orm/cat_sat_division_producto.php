<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_defaults;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class cat_sat_division_producto extends _modelo_parent{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_division_producto';
        $columnas = array($tabla=>false,"cat_sat_tipo_producto" => $tabla);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'cat_sat_tipo_producto_id';

        $columnas_extra['cat_sat_division_producto_n_grupos'] = "(SELECT COUNT(*) FROM cat_sat_grupo_producto 
        WHERE cat_sat_grupo_producto.cat_sat_division_producto_id = cat_sat_division_producto.id)";

        $tipo_campos['codigo'] = 'cod_int_0_2_numbers';

        $parents_data['cat_sat_tipo_producto'] = array();
        $parents_data['cat_sat_tipo_producto']['namespace'] = 'gamboamartin\\cat_sat\\models';
        $parents_data['cat_sat_tipo_producto']['registro_id'] = -1;
        $parents_data['cat_sat_tipo_producto']['keys_parents'] = array('cat_sat_tipo_producto_descripcion');
        $parents_data['cat_sat_tipo_producto']['key_id'] = 'cat_sat_tipo_producto_id';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, tipo_campos: $tipo_campos,
            parents_data: $parents_data);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Division Producto';


        $this->id_code = true;


    }

    public function get_division(int $cat_sat_division_producto_id): array|stdClass
    {
        $registro = $this->registro(registro_id: $cat_sat_division_producto_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener division producto',data:  $registro);
        }

        return $registro;
    }
}