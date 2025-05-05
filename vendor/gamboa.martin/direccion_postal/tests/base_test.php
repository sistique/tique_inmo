<?php
namespace gamboamartin\direccion_postal\tests;
use base\orm\modelo_base;
use gamboamartin\direccion_postal\models\dp_calle;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\direccion_postal\models\dp_colonia;
use gamboamartin\direccion_postal\models\dp_colonia_postal;
use gamboamartin\direccion_postal\models\dp_cp;
use gamboamartin\direccion_postal\models\dp_estado;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\direccion_postal\models\dp_pais;
use gamboamartin\errores\errores;

use gamboamartin\test\test;
use PDO;
use stdClass;


class base_test{

    public function alta_adm_accion(PDO $link, string $adm_seccion_descripcion = 'adm_seccion',
                                    int $adm_seccion_id = 1, string $descripcion = 'alta',
                                    int $id = 1): array|\stdClass
    {


        $alta = (new \gamboamartin\administrador\tests\base_test())->alta_adm_accion(link: $link,
            adm_seccion_descripcion: $adm_seccion_descripcion, adm_seccion_id: $adm_seccion_id,
            descripcion: $descripcion, id: $id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_calle(PDO $link, string $codigo = '1', string $descripcion = '1', int $id = 1,
                                  bool $predeterminado = false): array|\stdClass
    {


        $registro = (new test(''))->registro(
            codigo: $codigo,descripcion:  $descripcion,id:  $id,predeterminado:  $predeterminado);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al generar registro', data: $registro);
        }

        $alta = (new dp_calle($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_calle_pertenece(PDO $link, string $codigo = '1',string $descripcion = '1',
                                            int $dp_calle_id = 1, int $dp_colonia_postal_id = 1, int $id = 1,
                                            string $predeterminado = 'inactivo'): array|\stdClass
    {

        $existe = (new dp_calle($link))->existe_by_id(registro_id: $dp_calle_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe', data: $existe);
        }

        if(!$existe){
            $alta = $this->alta_dp_calle(link: $link,id: $dp_calle_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            }
        }

        $existe = (new dp_colonia_postal($link))->existe_by_id(registro_id: $dp_colonia_postal_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe', data: $existe);
        }

        if(!$existe){
            $alta = $this->alta_dp_colonia_postal(link: $link,id: $dp_colonia_postal_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            }
        }

        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['dp_calle_id'] = $dp_calle_id;
        $registro['dp_colonia_postal_id'] = $dp_colonia_postal_id;
        $registro['predeterminado'] = $predeterminado;


        $alta = (new dp_calle_pertenece($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_colonia(PDO $link, string $codigo = '0009999', string $descripcion = '0009999',
                                    int $id = 1, bool $predeterminado = false): array|\stdClass
    {

        $registro = (new test(''))->registro(
            codigo: $codigo,descripcion:  $descripcion,id:  $id,predeterminado:  $predeterminado);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al generar registro', data: $registro);
        }



        $alta = (new dp_colonia($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_colonia_postal(PDO $link, string $codigo = '1', string $descripcion = '1',
                                           int $dp_colonia_id = 1, int $dp_cp_id = 1, int $id = 1,
                                           string $predeterminado = 'inactivo'): array|\stdClass
    {


        $registro['dp_colonia_id'] = $dp_colonia_id;

        $existe = (new dp_colonia($link))->existe_by_id(registro_id: $dp_colonia_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);
        }

        if(!$existe) {
            $alta = (new \gamboamartin\direccion_postal\tests\base_test())->alta_dp_colonia(link: $link, id: $dp_colonia_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }


        $existe = (new dp_cp($link))->existe_by_id(registro_id: $dp_cp_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);
        }

        if(!$existe) {
            $alta = (new \gamboamartin\direccion_postal\tests\base_test())->alta_dp_cp(link: $link, id: $dp_cp_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }


        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['dp_cp_id'] = $dp_cp_id;

        $alta = (new dp_colonia_postal($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_cp(PDO $link, string $codigo = '00099',string $descripcion = '00099' , int $dp_municipio_id = 1,
                               int $id = 1, string $predeterminado = 'inactivo'): array|\stdClass
    {

        $existe = (new dp_municipio($link))->existe_by_id(registro_id: $dp_municipio_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);
        }

        if(!$existe) {
            $alta = $this->alta_dp_municipio(link: $link, id: $dp_municipio_id);
            if (errores::$error) {
                return (new errores())->error('Error al dar de alta', $alta);
            }
        }


        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['dp_municipio_id'] = $dp_municipio_id;
        $registro['predeterminado'] = $predeterminado;


        $alta = (new dp_cp($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_estado(PDO $link, string $codigo = 'JAL', string $descripcion = '1', int $dp_pais_id = 151,
                                   int $id = 14, bool $predeterminado = false): array|\stdClass
    {


        $registro = (new test())->registro(
            codigo: $codigo,descripcion:  $descripcion,id:  $id,predeterminado:  $predeterminado);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al generar registro', data: $registro);
        }

        $registro['dp_pais_id'] = $dp_pais_id;


        $alta = (new dp_estado($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_municipio(PDO $link, string $codigo = '1', string $descripcion = '1',
                                      int $dp_estado_id = 14, int $id = 1,
                                      string $predeterminado = 'inactivo'): array|stdClass{


        $existe = (new dp_estado($link))->existe_by_id(registro_id: $dp_estado_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe', data: $existe);
        }

        if(!$existe){
            $alta = $this->alta_dp_estado(link: $link,id: $dp_estado_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
            }
        }

        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;
        $registro['dp_estado_id'] = $dp_estado_id;
        $registro['predeterminado'] = $predeterminado;


        $alta = (new dp_municipio($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_pais(PDO $link, string $codigo = '1', string $descripcion = '1', int $id = 1): array|\stdClass
    {

        $registro = (new test())->registro(
            codigo: $codigo,descripcion:  $descripcion,id:  $id,predeterminado:  false);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al generar registro', data: $registro);
        }

        $alta = (new dp_pais($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function del(PDO $link, string $name_model): array
    {

        $model = (new modelo_base($link))->genera_modelo(modelo: $name_model);
        $del = $model->elimina_todo();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar '.$name_model, data: $del);
        }
        return $del;
    }

    public function del_adm_seccion(PDO $link): array
    {

        $del = (new \gamboamartin\administrador\tests\base_test())->del_adm_seccion(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        return $del;
    }

    public function del_dp_calle(PDO $link): array
    {

        $del = $this->del_dp_calle_pertenece($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\direccion_postal\\models\\dp_calle');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_dp_calle_pertenece(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\direccion_postal\\models\\dp_calle_pertenece');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_dp_colonia(PDO $link): array
    {
        $del = $this->del_dp_colonia_postal($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\direccion_postal\\models\\dp_colonia');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_dp_colonia_postal(PDO $link): array
    {
        $del = $this->del_dp_calle_pertenece($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\direccion_postal\\models\\dp_colonia_postal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_dp_cp(PDO $link): array
    {

        $del = $this->del_dp_colonia_postal($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\direccion_postal\\models\\dp_cp');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_dp_estado(PDO $link): array
    {

        $del = $this->del_dp_municipio($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\direccion_postal\\models\\dp_estado');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_dp_municipio(PDO $link): array
    {

        $del = $this->del_dp_cp($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\direccion_postal\\models\\dp_municipio');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_dp_pais(PDO $link): array
    {

        $del = $this->del_dp_estado($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\direccion_postal\\models\\dp_pais');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }




}
