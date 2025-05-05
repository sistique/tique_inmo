<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;

use gamboamartin\errores\errores;
use PDO;
use stdClass;

class org_departamento extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_departamento';
        $columnas = array($tabla=>false, 'org_empresa'=>$tabla,'org_clasificacion_dep'=>$tabla,'org_tipo_empresa'=>'org_empresa');

        $campos_obligatorios = array('org_clasificacion_dep_id');
        $no_duplicados = array();
        $tipo_campos = array();

        $campos_view['org_clasificacion_dep_id'] = array('type' => 'selects', 'model' => new org_clasificacion_dep($link));
        $campos_view['org_empresa_id'] = array('type' => 'selects', 'model' => new org_empresa($link));
        $campos_view['codigo'] = array('type' => 'inputs');
        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            campos_view: $campos_view, no_duplicados: $no_duplicados, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Departamento';
    }



    public function departamentos(int $org_empresa_id): array|stdClass
    {
        if($org_empresa_id <=0){
            return $this->error->error(mensaje: 'Error $org_empresa_id debe ser mayor a 0', data: $org_empresa_id);
        }
        $filtro['org_empresa.id'] = $org_empresa_id;
        $r_org_departamento = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener departamentos', data: $r_org_departamento);
        }
        return $r_org_departamento;
    }

    public function departamentos_por_cls(int $org_clasificacion_dep_id): array|stdClass
    {
        if($org_clasificacion_dep_id <=0){
            return $this->error->error(mensaje: 'Error $org_clasificacion_dep_id debe ser mayor a 0', data: $org_clasificacion_dep_id);
        }
        $filtro['org_clasificacion_dep.id'] = $org_clasificacion_dep_id;
        $r_org_departamento = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener departamentos', data: $r_org_departamento);
        }
        return $r_org_departamento;
    }
}