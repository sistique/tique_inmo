<?php
namespace gamboamartin\documento\models;
use base\orm\_defaults;
use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;


class doc_acl_tipo_documento extends _modelo_parent{ //FINALIZADAS
    /**
     * DEBUG INI
     * accion constructor.
     * @param PDO $link
     */
    public function __construct(PDO $link){
        $tabla = 'doc_acl_tipo_documento';
        $columnas = array($tabla=>false, 'doc_tipo_documento'=>$tabla, 'adm_grupo'=>$tabla);
        $campos_obligatorios = array('doc_tipo_documento_id', 'adm_grupo_id');
        parent::__construct(link: $link,tabla:  $tabla,campos_obligatorios: $campos_obligatorios, columnas:  $columnas);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'ACL Por Doc';

    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $codigo = $this->registro['doc_tipo_documento_id'].'.'.$this->registro['adm_grupo_id'];
        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $codigo;
        }

        $descripcion = $this->registro['doc_tipo_documento_id'].'.'.$this->registro['adm_grupo_id'];
        if(!isset($this->registro['descripcion'])){
            $this->registro['descripcion'] = $descripcion;
        }

        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar extension permitida',data:  $r_alta_bd);
        }
        return $r_alta_bd;
    }

    /**
     * REG
     * Verifica si un grupo de usuarios tiene permisos sobre un tipo de documento.
     *
     * Esta función consulta la base de datos para determinar si existe un registro en la tabla
     * `doc_acl_tipo_documento` que asocie un grupo de usuarios (`adm_grupo.id`) con un tipo
     * de documento (`doc_tipo_documento.id`). La función solo considera registros con estado
     * "activo".
     *
     * @param int $grupo_id Identificador del grupo de usuarios.
     *                      - Debe ser un número entero positivo mayor a 0.
     * @param int $tipo_documento_id Identificador del tipo de documento.
     *                                - Debe ser un número entero positivo mayor a 0.
     *
     * @return bool|array Devuelve `true` si el permiso existe, `false` si no existe.
     *                    En caso de error, devuelve un array con detalles del error.
     *
     * @example Uso exitoso:
     * ```php
     * $acl = new doc_acl_tipo_documento($pdo);
     * $permiso = $acl->tipo_documento_permiso(grupo_id: 2, tipo_documento_id: 5);
     * var_dump($permiso);
     * ```
     * **Salida esperada (si el permiso existe)**:
     * ```php
     * bool(true)
     * ```
     *
     * **Salida esperada (si el permiso NO existe)**:
     * ```php
     * bool(false)
     * ```
     *
     * @example Caso con error:
     * ```php
     * $permiso = $acl->tipo_documento_permiso(grupo_id: 0, tipo_documento_id: 5);
     * ```
     * **Salida esperada (error por `grupo_id` inválido)**:
     * ```php
     * array(
     *   'error' => true,
     *   'mensaje' => 'Error grupo id no puede ser menor a 1',
     *   'data' => 0
     * )
     * ```
     *
     * @throws errores En caso de fallo en la consulta o si los parámetros no son válidos.
     * @version 0.9.1
     */
    final public function tipo_documento_permiso(int $grupo_id, int $tipo_documento_id): bool|array
    {
        if ($grupo_id <= 0) {
            return $this->error->error(mensaje: 'Error grupo id no puede ser menor a 1', data: $grupo_id,
                es_final: true);
        }
        if ($tipo_documento_id <= 0) {
            return $this->error->error(mensaje: 'Error tipo documento id no puede ser menor a 1',
                data: $tipo_documento_id, es_final: true);
        }

        $filtro['doc_tipo_documento.id'] = $tipo_documento_id;
        $filtro['adm_grupo.id'] = $grupo_id;
        $filtro['doc_acl_tipo_documento.status'] = 'activo';

        $existe = $this->existe(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener acl', data: $existe);
        }

        return $existe;
    }

}