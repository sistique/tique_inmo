<?php
namespace gamboamartin\direccion_postal\models;
use base\orm\_defaults;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class dp_estado extends modelo {
    public function __construct(PDO $link){
        $tabla = 'dp_estado';
        $columnas = array($tabla=>false,'dp_pais'=>$tabla);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';
        $campos_obligatorios[] = 'dp_pais_id';

        $campos_view['dp_pais_id'] = array('type' => 'selects', 'model' => new dp_pais($link));
        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['descripcion'] = array('type' => 'inputs');

        $parents_data['dp_pais'] = array();
        $parents_data['dp_pais']['namespace'] = 'gamboamartin\\direccion_postal\\models';
        $parents_data['dp_pais']['registro_id'] = -1;
        $parents_data['dp_pais']['keys_parents'] = array('dp_pais_descripcion');
        $parents_data['dp_pais']['key_id'] = 'dp_pais_id';

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view: $campos_view, parents_data: $parents_data);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Estado';



    }

    public function alta_bd(): array|stdClass
    {
        $this->registro = $this->campos_base(data:$this->registro, modelo: $this);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $this->registro);
        }

        if(!isset($this->registro['dp_pais_id'])){

            $r_pred = (new dp_pais(link: $this->link))->inserta_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar prederminado',data:  $r_pred);
            }

            $dp_pais_id = (new dp_pais($this->link))->id_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener pais predeterminado',data:  $dp_pais_id);
            }
            $this->registro['dp_pais_id'] = $dp_pais_id;
        }

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar estado',data:  $r_alta_bd);
        }
        return $r_alta_bd;
    }


    public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {
        $registro = $this->campos_base(data: $registro, modelo: $this, id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar campo base',data: $registro);
        }

        $r_modifica_bd = parent::modifica_bd(registro: $registro,id: $id,reactiva:  $reactiva);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar estado',data:  $r_modifica_bd);
        }
        return $r_modifica_bd;
    }

    public function get_estado(int $dp_estado_id): array|stdClass|int
    {
        $registro = $this->registro(registro_id: $dp_estado_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener estado',data:  $registro);
        }

        return $registro;
    }

    public function get_estado_default(): array|stdClass|int
    {
        $filtro["dp_estado.predeterminado"] = 'activo';
        $registro = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el estado predeterminado',data:  $registro);
        }

        return $registro;
    }

    public function get_estado_default_id(): array|stdClass|int
    {
        $id_predeterminado = $this->id_predeterminado();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el estado predeterminado',data:  $id_predeterminado);
        }

        return (int)$id_predeterminado;
    }

}