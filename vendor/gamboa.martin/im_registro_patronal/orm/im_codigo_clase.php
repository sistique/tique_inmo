<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;

use gamboamartin\errores\errores;
use PDO;

class im_codigo_clase extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_codigo_clase";
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(): array|\stdClass
    {
        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $this->registro['descripcion'];
        }

        if(!isset($this->registro['codigo_bis'])) {
            $this->registro['codigo_bis'] = $this->registro['codigo'];
        }

        if(!isset($this->registro['alias'])) {
            $this->registro['alias'] = $this->registro['codigo_bis'];
        }

        if(!isset($this->registro['descripcion_select'])) {
            $this->registro['descripcion_select'] = $this->registro['descripcion'];
        }

        $alta_bd = parent::alta_bd();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar movimiento', data: $alta_bd);
        }

        return $alta_bd;
    }



}