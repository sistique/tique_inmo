<?php
namespace gamboamartin\cat_sat\models;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class cat_sat_conf_reg_tp extends _modelo_parent{
    public function __construct(PDO $link, bool $aplica_transacciones_base = false){
        $tabla = 'cat_sat_conf_reg_tp';

        $columnas = array($tabla=>false,"cat_sat_regimen_fiscal" => $tabla,
            "cat_sat_tipo_persona" => $tabla);


        $campos_obligatorios[] = 'cat_sat_regimen_fiscal_id';
        $campos_obligatorios[] = 'cat_sat_tipo_persona_id';

        $parents_data['cat_sat_regimen_fiscal'] = array();
        $parents_data['cat_sat_regimen_fiscal']['namespace'] = 'gamboamartin\\cat_sat\\models';
        $parents_data['cat_sat_regimen_fiscal']['registro_id'] = -1;
        $parents_data['cat_sat_regimen_fiscal']['keys_parents'] = array('cat_sat_regimen_fiscal_descripcion');
        $parents_data['cat_sat_regimen_fiscal']['key_id'] = 'cat_sat_regimen_fiscal_id';

        $parents_data['cat_sat_tipo_persona'] = array();
        $parents_data['cat_sat_tipo_persona']['namespace'] = 'gamboamartin\\cat_sat\\models';
        $parents_data['cat_sat_tipo_persona']['registro_id'] = -1;
        $parents_data['cat_sat_tipo_persona']['keys_parents'] = array('cat_sat_tipo_persona_descripcion');
        $parents_data['cat_sat_tipo_persona']['key_id'] = 'cat_sat_tipo_persona_id';


        parent::__construct(link: $link, tabla: $tabla, aplica_transacciones_base: $aplica_transacciones_base,
            campos_obligatorios: $campos_obligatorios, columnas: $columnas, columnas_extra: array(),
            tipo_campos: array(), parents_data: $parents_data);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Configuracion de regimenes fiscales';


    }

    public function alta_bd( array $keys_integra_ds = array()): array|stdClass
    {

        $data = $this->datos_base_alta(registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener datos',data: $data);
        }
        $registro = $this->descripcion(data: $data,registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro',data: $registro);
        }

        $this->registro = $this->campos_base(data:$registro,modelo: $this);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $this->registro);
        }

        $filtro['cat_sat_regimen_fiscal_id'] = $data->cat_sat_regimen_fiscal['cat_sat_regimen_fiscal_id'];
        $filtro['cat_sat_tipo_persona_id'] = $data->cat_sat_tipo_persona['cat_sat_tipo_persona_id'];

        $existe = $this->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al verificar si existe',data: $existe);
        }
        if($existe){
            $r_alta_bd = $this->alta_existente(filtro: $filtro);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al maquetar salida',data: $r_alta_bd);
            }
        }
        else {
            $r_alta_bd = parent::alta_bd();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al insertar clase producto', data: $r_alta_bd);
            }
        }
        return $r_alta_bd;
    }



    /**
     * Obtiene los elementos base parents del row
     * @param array $registro Registro en proceso
     * @return array|stdClass
     * @version 8.48.0
     */
    private function datos_base_alta(array $registro): array|stdClass
    {
        $keys = array('cat_sat_regimen_fiscal_id','cat_sat_tipo_persona_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data: $valida);
        }

        $cat_sat_regimen_fiscal = (new cat_sat_regimen_fiscal(link: $this->link))->registro(
            registro_id: $registro['cat_sat_regimen_fiscal_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener cat_sat_regimen_fiscal',data: $cat_sat_regimen_fiscal);
        }

        $cat_sat_tipo_persona = (new cat_sat_tipo_persona(link: $this->link))->registro(
            registro_id: $registro['cat_sat_tipo_persona_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener cat_sat_tipo_persona',data: $cat_sat_tipo_persona);
        }
        $data = new stdClass();
        $data->cat_sat_regimen_fiscal = $cat_sat_regimen_fiscal;
        $data->cat_sat_tipo_persona = $cat_sat_tipo_persona;
        return $data;
    }

    private function descripcion(stdClass $data, array $registro): array
    {
        if(!isset($registro['descripcion'])){
            $registro['descripcion'] = $data->cat_sat_regimen_fiscal['cat_sat_regimen_fiscal_descripcion'];
            $registro['descripcion'] .= ' '.$data->cat_sat_tipo_persona['cat_sat_tipo_persona_descripcion'];
        }
        return $registro;
    }



    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo','descripcion')): array|stdClass
    {
        $registro = $this->campos_base(data: $registro, modelo: $this, id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $registro);
        }


        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar clase producto',data:  $r_modifica_bd);
        }
        return $r_modifica_bd;
    }
}