<?php
namespace gamboamartin\documento\models;
use base\orm\_modelo_parent_sin_codigo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class doc_conf_tipo_documento_seccion extends _modelo_parent_sin_codigo {
    public function __construct(PDO $link, array $childrens = array()){
        $tabla = 'doc_conf_tipo_documento_seccion';
        $columnas = array($tabla=>false, 'doc_tipo_documento'=>$tabla, 'adm_seccion'=>$tabla);
        $campos_obligatorios = array('doc_tipo_documento_id','adm_seccion_id');

        $columnas_extra = array();

        $atributos_criticos =  array('doc_tipo_documento_id','adm_seccion_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, childrens: $childrens,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Configuracion de tipo de documento por seccion';


    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $this->registro = $this->inicializa_campos($this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar campo base', data: $this->registro);
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar configuracion de tipo de documento por seccion',
                data: $r_alta_bd);
        }

        return $r_alta_bd;
    }

    /**
     * TOTAL
     * Inicializa y valida ciertos campos en un registro.
     *
     * Esta función realiza las siguientes operaciones:
     * 1. Valida la existencia y validez de los campos clave `doc_tipo_documento_id` y `adm_seccion_id`.
     * 2. Genera un código aleatorio y lo asigna al campo `codigo` en el registro.
     * 3. Si el campo `descripcion` no está presente, lo inicializa concatenando los valores de
     * `doc_tipo_documento_id` y `adm_seccion_id`, separados por un guion.
     *
     * @param array $registros Arreglo asociativo que representa un registro con posibles campos a inicializar.
     *
     * @return array Arreglo actualizado con los campos inicializados o un mensaje de error en caso de falla.
     * - En caso de éxito, retorna el arreglo con los campos `codigo` y `descripcion` inicializados.
     * - En caso de error, retorna un arreglo con un mensaje de error.
     *
     * @url https://github.com/gamboamartin/documento/wiki/orm.doc_conf_tipo_documento_seccion.inicializa_campos
     */
    final protected function inicializa_campos(array $registros): array
    {
        $keys = array('doc_tipo_documento_id','adm_seccion_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error validar registros', data: $valida);
        }

        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }

        if(!isset($registros['descripcion'])){
            $descripcion = trim($registros['doc_tipo_documento_id']);
            $descripcion .= '-'.trim($registros['adm_seccion_id']);
            $registros['descripcion'] = $descripcion;
        }

        return $registros;
    }

}