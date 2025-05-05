<?php
namespace gamboamartin\organigrama\models;
use base\orm\_defaults;
use base\orm\_modelo_parent_sin_codigo;

use gamboamartin\errores\errores;
use PDO;


class org_tipo_empresa extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_tipo_empresa';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['descripcion'] = array('type' => 'inputs');

        $columnas_extra = array();
        $columnas_extra['org_tipo_empresa_n_empresas'] = /** @lang sql */
            "(SELECT COUNT(*) FROM org_empresa WHERE org_empresa.org_tipo_empresa_id = org_tipo_empresa.id)";

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view, columnas_extra: $columnas_extra);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Empresa';


        if(!isset($_SESSION['init'][$tabla])) {
            $catalogo = array();
            $catalogo[] = array('id'=>1,'codigo' => 'OPS', 'descripcion' => 'OPERACIONES');


            $r_alta_bd = (new _defaults())->alta_defaults(catalogo: $catalogo, entidad: $this);
            if (errores::$error) {
                $error = $this->error->error(mensaje: 'Error al insertar', data: $r_alta_bd);
                print_r($error);
                exit;
            }
            $_SESSION['init'][$tabla] = true;
        }


    }




}