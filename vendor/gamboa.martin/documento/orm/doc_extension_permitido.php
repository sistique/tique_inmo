<?php
namespace gamboamartin\documento\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;



class doc_extension_permitido extends modelo{
    /**
     * DEBUG INI
     * accion constructor.
     * @param PDO $link
     */
    public function __construct(PDO $link){
        $tabla = 'doc_extension_permitido';
        $columnas = array($tabla=>false, 'doc_tipo_documento'=>$tabla, 'doc_extension'=>$tabla);
        $campos_obligatorios = array('doc_tipo_documento_id', 'doc_extension_id');
        parent::__construct(link: $link,tabla:  $tabla,campos_obligatorios: $campos_obligatorios, columnas:  $columnas);
        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Extension Permitida';

    }

    public function alta_bd(): array|stdClass
    {
        $codigo = $this->registro['doc_tipo_documento_id'].'.'.$this->registro['doc_extension_id'];
        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $codigo;
        }

        $descripcion = $this->registro['doc_tipo_documento_id'].'.'.$this->registro['doc_extension_id'];
        if(!isset($this->registro['descripcion'])){
            $this->registro['descripcion'] = $descripcion;
        }

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar extension permitida',data:  $r_alta_bd);
        }
        return $r_alta_bd;

    }
}