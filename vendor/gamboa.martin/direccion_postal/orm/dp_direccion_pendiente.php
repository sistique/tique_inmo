<?php
namespace gamboamartin\direccion_postal\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class dp_direccion_pendiente extends modelo{
    public function __construct(PDO $link){
        $tabla = 'dp_direccion_pendiente';
        $columnas = array($tabla=>false);

        $campos_view['descripcion_pais'] = array('type' => 'inputs');
        $campos_view['descripcion_estado'] = array('type' => 'inputs');
        $campos_view['descripcion_municipio'] = array('type' => 'inputs');
        $campos_view['descripcion_cp'] = array('type' => 'inputs');
        $campos_view['descripcion_colonia'] = array('type' => 'inputs');
        $campos_view['descripcion_calle_pertenece'] = array('type' => 'inputs');

        parent::__construct(link: $link,tabla:  $tabla, columnas: $columnas, campos_view: $campos_view);
        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Direccion Pendiente';
    }

    public function alta_bd(): array|stdClass
    {
        $this->registro = $this->campos_base();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $this->registro);
        }

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta direccion pendiente',data: $r_alta_bd);
        }

        return $r_alta_bd;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {
        $this->registro = $this->campos_base();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $this->registro);
        }

        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar direccion pendiente',data: $r_modifica_bd);
        }

        return $r_modifica_bd;
    }

    private function campos_base(): array
    {
        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $this->registro['descripcion_pais'];
            $this->registro['codigo'] .= $this->registro['descripcion_estado'];
            $this->registro['codigo'] .= $this->registro['descripcion_municipio'];
            $this->registro['codigo'] .= $this->registro['descripcion_cp'];
            $this->registro['codigo'] .= $this->registro['descripcion_colonia'];
            $this->registro['codigo'] .= $this->registro['descripcion_calle_pertenece'];
        }

        if(!isset($this->registro['descripcion'])){
            $this->registro['descripcion'] =  $this->registro['codigo'];
        }

        if(!isset($this->registro['codigo_bis'])){
            $this->registro['codigo_bis'] =  $this->registro['codigo'];
        }

        if(!isset($this->registro['descripcion_select'])){
            $this->registro['descripcion_select'] =  $this->registro['codigo'];
        }

        if(!isset($this->registro['alias'])){
            $this->registro['alias'] =  $this->registro['codigo'];
        }

        return  $this->registro;
    }
}