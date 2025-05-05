<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;
use gamboamartin\direccion_postal\models\dp_cp;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class im_salario_minimo extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_salario_minimo";
        $columnas = array($tabla=>false,'im_tipo_salario_minimo'=>$tabla);
        $campos_obligatorios = array("im_tipo_salario_minimo_id","dp_cp_id","fecha_inicio","fecha_fin","monto");
        $campos_view = array("im_tipo_salario_minimo_id" => array("type" => "selects", "model" => new im_tipo_salario_minimo(link: $link)),
            "dp_cp_id" => array("type" => "selects", "model" => new dp_cp(link: $link)));

        $campos_view = array();
        $campos_view['dp_cp_id']['type'] = 'selects';
        $campos_view['dp_cp_id']['model'] = (new dp_cp($link));

        $campos_view['im_tipo_salario_minimo_id']['type'] = 'selects';
        $campos_view['im_tipo_salario_minimo_id']['model'] = (new im_tipo_salario_minimo($link));

        $campos_view['fecha_inicio']['type'] = 'dates';
        $campos_view['fecha_fin']['type'] = 'dates';
        $campos_view['monto']['type'] = 'inputs';


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(): array|stdClass
    {
        if(!isset($this->registro['codigo_bis'])){
            $this->registro['codigo_bis'] = strtoupper($this->registro['codigo']);
        }

        if(!isset($this->registro['descripcion_select'])){
            $this->registro['descripcion_select'] = $this->registro['descripcion'];
            $this->registro['descripcion_select'] .= $this->registro['codigo'];
        }
        if(!isset($this->registro['alias'])){
            $this->registro['alias'] = strtoupper($this->registro['descripcion_select']);
        }

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta tipo salario minimo',data: $r_alta_bd);
        }
        return $r_alta_bd;
    }
}