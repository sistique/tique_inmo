<?php
namespace gamboamartin\banco\models;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;

class bn_cuenta extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'bn_cuenta';
        $columnas = array($tabla=>false,'bn_empleado'=>$tabla,'org_sucursal'=>$tabla,'bn_sucursal'=>$tabla,
            'bn_tipo_cuenta'=>$tabla,'bn_tipo_sucursal'=>'bn_sucursal','bn_banco'=>'bn_sucursal',
            'bn_tipo_banco'=>'bn_banco','org_empresa'=>'org_sucursal');
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';
        $campos_obligatorios[] = 'bn_tipo_cuenta_id';


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }

    final public function bn_cuenta_id_default(){
        $bn_cuenta_id = -1;
        $bn_cuentas = $this->registros_activos(columnas: array('bn_cuenta_id'));
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener cuentas',data:  $bn_cuentas);
        }
        if(count($bn_cuentas) === 1){
            $bn_cuenta_id = $bn_cuentas[0]['bn_cuenta_id'];
        }
        return $bn_cuenta_id;

    }


}