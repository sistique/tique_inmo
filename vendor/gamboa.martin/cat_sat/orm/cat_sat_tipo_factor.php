<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_defaults;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;

class cat_sat_tipo_factor extends _modelo_parent{
    public function __construct(PDO $link){
        $tabla = 'cat_sat_tipo_factor';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Factor';

        /*
        if(!isset($_SESSION['init'][$tabla])) {
            $catalogo = array();
            $catalogo[] = array('id'=>1,'codigo' => 'Tasa', 'descripcion' => 'Tasa');
            $catalogo[] = array('id'=>2,'codigo' => 'Cuota', 'descripcion' => 'Cuota');
            $catalogo[] = array('id'=>3,'codigo' => 'Exento', 'descripcion' => 'Exento');



            $r_alta_bd = (new _defaults())->alta_defaults(catalogo: $catalogo, entidad: $this);
            if (errores::$error) {
                $error = $this->error->error(mensaje: 'Error al insertar', data: $r_alta_bd);
                print_r($error);
                exit;
            }
            $_SESSION['init'][$tabla] = true;
        }
        */

    }
}