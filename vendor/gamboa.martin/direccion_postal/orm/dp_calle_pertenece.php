<?php
namespace gamboamartin\direccion_postal\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class dp_calle_pertenece extends _base {
    public function __construct(PDO $link){
        $tabla = 'dp_calle_pertenece';
        $columnas = array($tabla=>false,'dp_colonia_postal'=>$tabla,'dp_calle'=>$tabla,'dp_cp'=>'dp_colonia_postal',
            'dp_colonia'=>'dp_colonia_postal','dp_municipio'=>'dp_cp','dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado');
        $campos_obligatorios[] = 'descripcion';


        $campos_view['dp_colonia_postal_id'] = array('type' => 'selects', 'model' => new dp_colonia_postal($link));
        $campos_view['dp_calle_id'] = array('type' => 'selects', 'model' => new dp_calle($link));
        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['georeferencia'] = array('type' => 'inputs');

        $parents_data['dp_calle'] = array();
        $parents_data['dp_calle']['namespace'] = 'gamboamartin\\direccion_postal\\models';
        $parents_data['dp_calle']['registro_id'] = -1;
        $parents_data['dp_calle']['keys_parents'] = array('dp_calle_descripcion');
        $parents_data['dp_calle']['key_id'] = 'dp_calle_id';

        $parents_data['dp_colonia_postal'] = array();
        $parents_data['dp_colonia_postal']['namespace'] = 'gamboamartin\\direccion_postal\\models';
        $parents_data['dp_colonia_postal']['registro_id'] = -1;
        $parents_data['dp_colonia_postal']['keys_parents'] = array('dp_colonia_postal_descripcion');
        $parents_data['dp_colonia_postal']['key_id'] = 'dp_colonia_postal_id';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view: $campos_view, parents_data: $parents_data);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Calle Pertenece';
    }

    public function alta_bd(): array|stdClass
    {


        $registro = $this->init_alta_bd(registro: $this->registro);
        if(errores::$error) {
            return $this->error->error(
                mensaje: 'Error al integrar predeterminados', data: $registro);
        }
        $this->registro = $registro;


        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje:  'Error al dar de alta registro', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }

    protected function campos_base_temp(array $data, modelo $modelo, int $id = -1, array $keys_integra_ds = array()): array
    {

        $keys = array('dp_calle_id','dp_colonia_postal_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar data',data:  $valida);
        }


        $colonia_postal = (new dp_colonia_postal($this->link))->get_colonia_postal($data['dp_colonia_postal_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener colonia postal',data:  $colonia_postal);
        }

        $calle = (new dp_calle($this->link))->get_calle($data['dp_calle_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener calle',data:  $calle);
        }
        if(!isset($data['codigo'])){
            $data['codigo'] =  $colonia_postal['dp_colonia_postal_descripcion'].' '.$calle['dp_calle_descripcion'];
        }

        if(!isset($data['codigo_bis'])){
            $data['codigo_bis'] =  $data['codigo'];
        }

        if(!isset($data['descripcion'])){
            $data['descripcion'] =  "{$calle['dp_calle_descripcion']} - {$colonia_postal['dp_colonia_postal_descripcion']}";
        }

        $data = $this->data_base(data: $data);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar data base', data: $data);
        }

        return $data;
    }

    private function dp_calle_id_predeterminado(array $registro): array
    {

        if(!isset($registro['dp_calle_id']) || (int)$registro['dp_calle_id'] === -1){
            $registro = $this->integra_dp_calle_id_predeterminado(registro: $registro);
            if(errores::$error){
                return $this->error->error(
                    mensaje: 'Error al integrar dp_calle_id predeterminado',data:  $registro);
            }
        }
        return $registro;
    }

    private function dp_colonia_postal_id_predeterminado(array $registro): array
    {
        if(!isset($registro['dp_colonia_postal_id']) || (int)$registro['dp_colonia_postal_id'] === -1){
            $registro = $this->integra_dp_colonia_postal_id_predeterminado(registro: $registro);
            if(errores::$error){
                return $this->error->error(
                    mensaje: 'Error al integrar dp_colonia_postal_id predeterminado',data:  $registro);
            }
        }
        return $registro;
    }

    /**
     * Obtiene una calle basada en el id
     * @param int $dp_calle_pertenece_id
     * @return array|stdClass
     */
    public function get_calle_pertenece(int $dp_calle_pertenece_id): array|stdClass
    {
        $registro = $this->registro(registro_id: $dp_calle_pertenece_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener calle pertenece',data:  $registro);
        }

        return $registro;
    }

    public function get_calle_pertenece_default_id(): array|stdClass|int
    {

        $id_predeterminado = $this->id_predeterminado();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el puesto predeterminado',data:  $id_predeterminado);
        }

        return (int)$id_predeterminado;
    }

    private function init_alta_bd(array $registro): array
    {
        $registro = $this->predeterminados(registro: $registro);
        if(errores::$error) {
            return $this->error->error(
                mensaje: 'Error al integrar predeterminados', data: $registro);
        }

        $keys = array('dp_calle_id','dp_colonia_postal_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar modelo->registro',data:  $valida);
        }


        $registro = $this->campos_base_temp(data:$registro, modelo: $this);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $registro);
        }

        $registro = $this->limpia_campos(registro: $registro, campos_limpiar: array('dp_pais_id',
            'dp_estado_id', 'dp_municipio_id','dp_cp_id'));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $registro);
        }
        return $registro;
    }

    private function integra_dp_calle_id_predeterminado(array $registro): array
    {

        $r_pred = (new dp_calle(link: $this->link))->inserta_predeterminado();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar prederminado',data:  $r_pred);
        }


        $dp_calle_id = (new dp_calle($this->link))->id_predeterminado();
        if(errores::$error){
            return $this->error->error(
                mensaje: 'Error al obtener dp_calle_id predeterminado',data:  $dp_calle_id);
        }
        $registro['dp_calle_id'] = $dp_calle_id;
        return $registro;
    }

    private function integra_dp_colonia_postal_id_predeterminado(array $registro): array
    {
        $r_pred = (new dp_colonia_postal(link: $this->link))->inserta_predeterminado();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar prederminado',data:  $r_pred);
        }

        $dp_colonia_postal_id = (new dp_colonia_postal($this->link))->id_predeterminado();
        if(errores::$error){
            return $this->error->error(
                mensaje: 'Error al obtener dp_colonia_postal_id predeterminado',data:  $dp_colonia_postal_id);
        }
        $registro['dp_colonia_postal_id'] = $dp_colonia_postal_id;
        return $registro;
    }

    private function limpia_campos(array $registro, array $campos_limpiar): array
    {
        foreach ($campos_limpiar as $valor) {
            if (isset($registro[$valor])) {
                unset($registro[$valor]);
            }
        }
        return $registro;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {

        $registro_previo = $this->registro(registro_id: $id, retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro_previo',data:  $registro_previo);
        }

        if(!isset($registro['dp_calle_id'])){
            $registro['dp_calle_id'] = $registro_previo->dp_calle_id;
        }
        if(!isset($registro['dp_colonia_postal_id'])){
            $registro['dp_colonia_postal_id'] = $registro_previo->dp_colonia_postal_id;
        }
        if(!isset($registro['codigo'])){
            $registro['codigo'] = $registro_previo->dp_calle_pertenece_codigo;
        }

        $keys = array('dp_calle_id','dp_colonia_postal_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $registro = $this->campos_base_temp(data:$registro, modelo: $this,id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $registro);
        }

        $registro = $this->limpia_campos(registro: $registro, campos_limpiar: array('dp_pais_id','dp_estado_id',
            'dp_municipio_id','dp_cp_id'));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos', data: $registro);
        }

        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar calle pertenece',data:  $r_modifica_bd);
        }

        return $r_modifica_bd;
    }


    /**
     * Genera un objeto con todos los elementos de una calle como elemento atomico de domicilios a nivel datos
     * @param int $dp_calle_pertenece_id Identificador de calle_pertenece
     * @return stdClass|array $data->pais, $data->estado, $data->municipio, $data->cp, $data->colonia, $data->colonia_postal
     * $data->calle, $data->calle_pertenece
     * @version 0.115.8
     */
    public function objs_direcciones(int $dp_calle_pertenece_id): stdClass|array
    {
        if($dp_calle_pertenece_id <=0){
            return $this->error->error(mensaje: 'Error $dp_calle_pertenece_id debe ser mayor a 0',
                data:  $dp_calle_pertenece_id);
        }
        $dp_calle_pertenece = $this->registro(
            registro_id: $dp_calle_pertenece_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener calle pertenece',data:  $dp_calle_pertenece);
        }

        $dp_calle = (new dp_calle($this->link))->registro(
            registro_id: $dp_calle_pertenece->dp_calle_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener $dp_calle',data:  $dp_calle);
        }

        $dp_colonia_postal = (new dp_colonia_postal($this->link))->registro(
            registro_id: $dp_calle_pertenece->dp_colonia_postal_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener $dp_colonia_postal',data:  $dp_colonia_postal);
        }

        $dp_colonia = (new dp_colonia($this->link))->registro(
            registro_id: $dp_colonia_postal->dp_colonia_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener $dp_colonia',data:  $dp_colonia);
        }

        $dp_cp = (new dp_cp($this->link))->registro(
            registro_id: $dp_colonia_postal->dp_cp_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener $dp_cp',data:  $dp_cp);
        }

        $dp_municipio = (new dp_municipio($this->link))->registro(
            registro_id: $dp_cp->dp_municipio_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener $dp_municipio',data:  $dp_municipio);
        }

        $dp_estado = (new dp_estado($this->link))->registro(registro_id: $dp_municipio->dp_estado_id,
            columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener estado',data:  $dp_estado);
        }
        $dp_pais = (new dp_pais($this->link))->registro(registro_id: $dp_estado->dp_pais_id,
            columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener pais',data:  $dp_pais);
        }

        $data = new stdClass();

        $data->pais = $dp_pais;
        $data->estado = $dp_estado;
        $data->municipio = $dp_municipio;
        $data->cp = $dp_cp;
        $data->colonia = $dp_colonia;
        $data->colonia_postal = $dp_colonia_postal;
        $data->calle = $dp_calle;
        $data->calle_pertenece = $dp_calle_pertenece;

        return $data;

    }

    private function predeterminados(array $registro): array
    {
        $registro = $this->dp_calle_id_predeterminado(registro: $registro);
        if(errores::$error) {
            return $this->error->error(
                mensaje: 'Error al integrar dp_calle_id predeterminado', data: $registro);
        }

        $registro = $this->dp_colonia_postal_id_predeterminado(registro: $registro);
        if(errores::$error) {
            return $this->error->error(
                mensaje: 'Error al integrar dp_colonia_postal_id predeterminado', data: $registro);
        }
        return $registro;
    }


}