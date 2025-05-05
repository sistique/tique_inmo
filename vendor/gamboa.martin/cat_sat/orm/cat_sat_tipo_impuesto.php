<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_defaults;
use base\orm\_modelo_parent_sin_codigo;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;

class cat_sat_tipo_impuesto  extends _modelo_parent_sin_codigo {
    public function __construct(PDO $link){
        $tabla = 'cat_sat_tipo_impuesto';
        $columnas = array($tabla=>false);
        $campos_obligatorios[] = 'descripcion';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->etiqueta = 'Tipo Impuesto';
        $this->NAMESPACE = __NAMESPACE__;

        /*

        if(!isset($_SESSION['init'][$tabla])) {


            $catalogo = array();
            $catalogo[] = array('id'=>1,'codigo' => '001', 'descripcion' => 'ISR');
            $catalogo[] = array('id'=>2,'codigo' => '002', 'descripcion' => 'IVA');
            $catalogo[] = array('id'=>3,'codigo' => '003', 'descripcion' => 'IEPS');


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