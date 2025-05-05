<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class org_puesto extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_puesto';
        $columnas = array($tabla=>false,'org_tipo_puesto'=>$tabla,'org_departamento'=>$tabla,
            'org_empresa'=>'org_departamento');
        $campos_obligatorios = array('org_tipo_puesto_id','org_departamento_id');
        $campos_view = array(
            'org_tipo_puesto_id' => array('type' => 'selects', 'model' => new org_tipo_puesto($link)),
            'org_departamento_id' => array('type' => 'selects', 'model' => new org_departamento($link)),
            'descripcion' => array('type' => 'inputs','cols' => 6));

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Puesto';


    }



    /**
     * Obtiene el puesto default
     * @return array|stdClass|int
     * @version 0.300.39
     */
    public function get_puesto_default_id(): array|stdClass|int
    {
        $id_predeterminado = $this->id_predeterminado();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el puesto predeterminado',data:  $id_predeterminado);
        }

        return (int)$id_predeterminado;
    }




}