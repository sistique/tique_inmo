<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_defaults;
use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class cat_sat_clase_producto extends _modelo_parent{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_clase_producto';
        $columnas = array($tabla=>false,"cat_sat_grupo_producto" => $tabla,
            "cat_sat_division_producto" => "cat_sat_grupo_producto","cat_sat_tipo_producto" => "cat_sat_division_producto");
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'cat_sat_grupo_producto_id';

        $columnas_extra['cat_sat_clase_producto_n_productos'] = "(SELECT COUNT(*) FROM cat_sat_producto 
        WHERE cat_sat_producto.cat_sat_clase_producto_id = cat_sat_clase_producto.id)";

        $tipo_campos['codigo'] = 'cod_int_0_6_numbers';

        $parents_data['cat_sat_grupo_producto'] = array();
        $parents_data['cat_sat_grupo_producto']['namespace'] = 'gamboamartin\\cat_sat\\models';
        $parents_data['cat_sat_grupo_producto']['registro_id'] = -1;
        $parents_data['cat_sat_grupo_producto']['keys_parents'] = array('cat_sat_grupo_producto_descripcion');
        $parents_data['cat_sat_grupo_producto']['key_id'] = 'cat_sat_grupo_producto_id';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            columnas_extra: $columnas_extra, tipo_campos: $tipo_campos, parents_data: $parents_data);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Clase Producto';
        $this->id_code = true;






    }

    public function alta_bd( array $keys_integra_ds = array()): array|stdClass
    {
        $this->registro = $this->campos_base(data:$this->registro,modelo: $this);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $this->registro);
        }

        $campos_limpiar[] = 'cat_sat_tipo_producto_id';
        $campos_limpiar[] = 'cat_sat_division_producto_id';
        $this->registro = $this->limpia_campos_extras(registro: $this->registro, campos_limpiar: $campos_limpiar);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $this->registro);
        }

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar clase producto',data:  $r_alta_bd);
        }
        return $r_alta_bd;
    }

    public function get_clase(int $cat_sat_clase_producto_id): array|stdClass
    {
        $registro = $this->registro(registro_id: $cat_sat_clase_producto_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener clase producto',data:  $registro);
        }

        return $registro;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo','descripcion')): array|stdClass
    {
        $registro = $this->campos_base(data: $registro, modelo: $this, id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $registro);
        }

        $campos_limpiar[] = 'cat_sat_tipo_producto_id';
        $campos_limpiar[] = 'cat_sat_division_producto_id';
        $registro = $this->limpia_campos_extras(registro: $registro, campos_limpiar: $campos_limpiar);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $registro);
        }

        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar clase producto',data:  $r_modifica_bd);
        }
        return $r_modifica_bd;
    }
}