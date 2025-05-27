<?php
namespace gamboamartin\direccion_postal\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class _base extends modelo{
    public function __construct(PDO $link, string $tabla, bool $aplica_bitacora = false, bool $aplica_seguridad = false,
                                bool $aplica_transaccion_inactivo = true, array $campos_encriptados = array(),
                                array $campos_obligatorios = array(), array $columnas = array(),
                                array $campos_view = array(), array $columnas_extra = array(),
                                array $extension_estructura = array(), array $no_duplicados = array(),
                                array $renombres = array(), array $sub_querys = array(), array $tipo_campos = array(),
                                bool $validation = false, array $campos_no_upd = array(), array $parents = array(),
                                bool $temp = false, array $childrens = array(), array $defaults = array(),
                                array $parents_data = array())
    {
        $campos_view['dp_pais_id'] = array('type' => 'selects', 'model' => new dp_pais($link));
        $campos_view['dp_estado_id'] = array('type' => 'selects', 'model' => new dp_estado($link));
        $campos_view['dp_municipio_id'] = array('type' => 'selects', 'model' => new dp_municipio($link));
        $campos_view['dp_cp_id'] = array('type' => 'selects', 'model' => new dp_cp($link));

        parent::__construct(link: $link,tabla:  $tabla,aplica_bitacora:  $aplica_bitacora,
            aplica_seguridad:  $aplica_seguridad,aplica_transaccion_inactivo:  $aplica_transaccion_inactivo,
            campos_encriptados:  $campos_encriptados, campos_obligatorios: $campos_obligatorios,columnas:  $columnas,
            campos_view: $campos_view,columnas_extra:  $columnas_extra,extension_estructura:  $extension_estructura,
            no_duplicados: $no_duplicados, renombres: $renombres,sub_querys:  $sub_querys,tipo_campos:  $tipo_campos,
            validation: $validation, campos_no_upd: $campos_no_upd,parents:  $parents, temp: $temp,
            childrens: $childrens,defaults:  $defaults, parents_data: $parents_data);
    }



}
