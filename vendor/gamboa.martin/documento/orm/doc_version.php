<?php
namespace gamboamartin\documento\models;
use base\orm\modelo;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\plugins\files;
use PDO;
use stdClass;


class doc_version extends modelo
{ //FINALIZADAS
    /**
     * DEBUG INI
     * accion constructor.
     * @param PDO $link
     */
    public function __construct(PDO $link)
    {
        $tabla = 'doc_version';
        $columnas = array($tabla => false, 'doc_documento' => $tabla, 'doc_extension' => $tabla,'doc_tipo_documento'=>'doc_documento');
        $campos_obligatorios = array('doc_documento_id', 'doc_extension_id');
        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas);
        $this->NAMESPACE = __NAMESPACE__;
    }

    /**
     * PRUEBA P ORDER P INT
     * Funcion sobrescrita la cual solo devuelve error
     * @param bool $reactiva
     * @param int $registro_id
     * @return array
     */
    public function activa_bd(bool $reactiva = false, int $registro_id = -1): array
    {
        return $this->error->error(mensaje: 'Error la funcion de activa_bd no esta permitada para este modelo', data: $reactiva);
    }

    /**
     * PRUEBA P ORDER P INT
     * Inserta registro de version eliminando el documento fisico del documento actual
     * @return array|stdClass
     */
    public function alta_bd(): array|stdClass
    {
        $keys = array('doc_documento_id');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar $this->registro', data: $valida);
        }

        $doc = (new doc_documento($this->link))->registro(registro_id: $this->registro['doc_documento_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener documento', data: $doc);
        }

        $grupo_id = -1;
        if(isset($_SESSION['grupo_id']) && $_SESSION['grupo_id']!==''){
            $grupo_id = $_SESSION['grupo_id'];
        }

        $tiene_permiso = (new doc_acl_tipo_documento($this->link))->tipo_documento_permiso(
            grupo_id: $grupo_id, tipo_documento_id: $doc['doc_tipo_documento_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar permiso',
                data: $tiene_permiso);
        }
        if (!$tiene_permiso) {
            return $this->error->error(mensaje: 'Error no tiene permiso de alta', data: $tiene_permiso);
        }

        $nombre_doc = (new files())->nombre_doc(tipo_documento_id: $doc['doc_tipo_documento_id'],
            extension: $doc['doc_extension_descripcion']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error obtener nombre documento', data: $nombre_doc);
        }

        $ruta_archivos = (new generales())->path_base.'archivos/';
        $ruta_relativa = 'archivos/'.$this->tabla.'/';

        if(!is_dir($ruta_archivos) && !mkdir($ruta_archivos) && !is_dir($ruta_archivos)) {
            return $this->error->error(mensaje: 'Error crear directorio', data: $ruta_archivos);
        }

        $ruta_absoluta_directorio = (new generales())->path_base.$ruta_relativa;
        if(!is_dir($ruta_absoluta_directorio) && !mkdir($ruta_absoluta_directorio) &&
            !is_dir($ruta_absoluta_directorio)) {
            return $this->error->error(mensaje: 'Error crear directorio', data: $ruta_absoluta_directorio);
        }

        if(!file_exists($doc['doc_documento_ruta_absoluta'])){
            return $this->error->error(mensaje: 'Error no existe el doc original',
                data: $doc['doc_documento_ruta_absoluta']);
        }

        $this->registro['status'] = 'activo';
        $this->registro['nombre'] = $nombre_doc;
        $this->registro['ruta_relativa'] = $ruta_relativa.$nombre_doc;
        $this->registro['ruta_absoluta'] = $ruta_absoluta_directorio.$nombre_doc;
        $this->registro['doc_documento_id'] = $doc['doc_documento_id'];
        $this->registro['doc_extension_id'] = $doc['doc_extension_id'];

        $guarda = (new files())->guarda_archivo_fisico(contenido_file:  file_get_contents($doc['doc_documento_ruta_absoluta']),
            ruta_file: $this->registro['ruta_absoluta']);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al guardar archivo', data: $guarda);
        }

        if(file_exists($doc['doc_documento_ruta_absoluta'])){
            unlink($doc['doc_documento_ruta_absoluta']);
        }

        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
            $this->registro['codigo'] .= mt_rand(10,99);
        }

        if(!isset($this->registro['descripcion'])){
            $this->registro['descripcion'] = mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
            $this->registro['descripcion'] .= mt_rand(10,99);
        }

        $r_alta_doc = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje:'Error al guardar registro', data: $r_alta_doc);
        }

        return $r_alta_doc; // TODO: Change the autogenerated stub
    }

    /**
     * PRUEBA P ORDER P INT
     * Funcion sobrescrita la cual solo devuelve error
     * @return array
     */
    public function desactiva_bd(): array
    {
        return $this->error->error(mensaje: 'Error la funcion de desactiva_bd no esta permitada para este modelo', data: $this);
    }

    /**
     * P ORDER P INT
     * Funcion sobrescrita elimina archivo fisico y registro de entidad version
     * @param int $id Identificador de un registro version
     * @return array
     */
    public function elimina_bd(int $id): array|stdClass
    {
        $version = $this->registro(registro_id: $id);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al obtener registro version', data: $version);
        }

        $documento = (new doc_documento($this->link))->registro(registro_id: $version['doc_version_doc_documento_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener documento', data: $documento);
        }

        $grupo_id = -1;
        if(isset($_SESSION['grupo_id']) && $_SESSION['grupo_id']!==''){
            $grupo_id = $_SESSION['grupo_id'];
        }

        $tiene_permiso = (new doc_acl_tipo_documento($this->link))->tipo_documento_permiso(
            grupo_id: $grupo_id, tipo_documento_id: $documento['doc_tipo_documento_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar permiso',
                data: $tiene_permiso);
        }
        if (!$tiene_permiso) {
            return $this->error->error(mensaje: 'Error no tiene permiso de alta', data: $tiene_permiso);
        }

        if(file_exists($version['doc_version_ruta_absoluta'])){
            unlink($version['doc_version_ruta_absoluta']);
        }

        $r_elimina_ver = parent::elimina_bd(id: $id);
        if(errores::$error){
            return $this->error->error(mensaje:'Error al eliminar version', data: $r_elimina_ver);
        }

        return $r_elimina_ver;
    }


    /**
     * P ORDER P INT
     * Funcion sobrescrita la cual solo devuelve error
     * @param array $registro
     * @param int $id
     * @param bool $reactiva
     * @return array|stdClass
     */
    public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {
        return $this->error->error(mensaje: 'Error la funcion de modifica_bd no esta permitada para este modelo', data: $this);
    }


}