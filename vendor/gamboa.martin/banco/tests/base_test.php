<?php
namespace gamboamartin\banco\tests;
use base\orm\modelo_base;
use gamboamartin\banco\models\bn_banco;
use gamboamartin\banco\models\bn_cuenta;
use gamboamartin\banco\models\bn_empleado;
use gamboamartin\banco\models\bn_sucursal;
use gamboamartin\banco\models\bn_tipo_banco;
use gamboamartin\banco\models\bn_tipo_cuenta;
use gamboamartin\banco\models\bn_tipo_sucursal;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_puesto;
use gamboamartin\organigrama\models\org_sucursal;
use PDO;


class base_test{

    public function alta_bn_cuenta(
        PDO $link, int $bn_empleado_id = 1, string $bn_sucursal_codigo = '001', int $bn_sucursal_id = 1,
        int $bn_tipo_cuenta_id = 1, int $cat_sat_regimen_fiscal_id = 601, int $cat_sat_tipo_persona_id = 4,
        string $descripcion = 'CUENTA 1', int $id = 1, int $org_sucursal_id = 1): array|\stdClass
    {
        $existe = (new bn_tipo_cuenta(link: $link))->existe_by_id(registro_id: $bn_tipo_cuenta_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe bn_tipo_cuenta', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_bn_tipo_cuenta(link: $link, id: $bn_tipo_cuenta_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar bn_tipo_cuenta', data: $alta);
            }
        }

        $existe = (new bn_sucursal(link: $link))->existe_by_id(registro_id: $bn_sucursal_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe bn_sucursal', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_bn_sucursal(link: $link, codigo: $bn_sucursal_codigo, id: $bn_sucursal_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar bn_sucursal', data: $alta);
            }
        }

        $existe = (new bn_empleado(link: $link))->existe_by_id(registro_id: $bn_empleado_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe bn_empleado', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_bn_empleado(link: $link, id: $bn_empleado_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar bn_empleado', data: $alta);
            }
        }

        $existe = (new org_sucursal(link: $link))->existe_by_id(registro_id: $org_sucursal_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe org_sucursal', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_org_sucursal(link: $link, cat_sat_regimen_fiscal_id: $cat_sat_regimen_fiscal_id,
                cat_sat_tipo_persona_id: $cat_sat_tipo_persona_id, id: $bn_empleado_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar org_sucursal', data: $alta);
            }
        }

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['bn_tipo_cuenta_id'] = $bn_tipo_cuenta_id;
        $registro['bn_sucursal_id'] = $bn_sucursal_id;
        $registro['org_sucursal_id'] = $org_sucursal_id;
        $registro['bn_empleado_id'] = $bn_empleado_id;

        $alta = (new bn_cuenta($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_bn_empleado(PDO $link, string $am = 'AM 1', string $ap = 'AP 1', int $id = 1,
                                     string $nombre = 'NOMBRE 1', int $org_puesto_id = 1): array|\stdClass
    {

        $existe = (new org_puesto(link: $link))->existe_by_id(registro_id: $org_puesto_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe org_puesto', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_org_puesto(link: $link, id: $org_puesto_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar org_puesto', data: $alta);
            }
        }

        $registro['id'] = $id;
        $registro['nombre'] = $nombre;
        $registro['ap'] = $ap;
        $registro['am'] = $am;
        $registro['org_puesto_id'] = $org_puesto_id;


        $alta = (new bn_empleado($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_bn_banco(PDO $link, int $bn_tipo_banco_id = 1,string $descripcion = 'banco 1', int $id = 1): array|\stdClass
    {

        $existe = (new bn_tipo_banco(link: $link))->existe_by_id(registro_id: $bn_tipo_banco_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe bn_tipo_bancoid', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_bn_tipo_banco(link: $link, id: $bn_tipo_banco_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar bn_tipo_bancoid', data: $alta);
            }
        }
        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['bn_tipo_banco_id'] = $bn_tipo_banco_id;

        $alta = (new bn_banco($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_bn_sucursal(PDO $link, int $bn_banco_id = 1, int $bn_tipo_sucursal_id = 1,
                                     string $codigo = '001', string $descripcion = 'SUCURSAL 1',
                                     int $id = 1): array|\stdClass
    {
        $existe = (new bn_banco(link: $link))->existe_by_id(registro_id: $bn_banco_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe bn_banco', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_bn_banco(link: $link, id: $bn_banco_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar bn_banco', data: $alta);
            }
        }

        $existe = (new bn_tipo_sucursal(link: $link))->existe_by_id(registro_id: $bn_tipo_sucursal_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe bn_tipo_sucursal', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_bn_tipo_sucursal(link: $link, id: $bn_tipo_sucursal_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar bn_tipo_sucursal', data: $alta);
            }
        }

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;
        $registro['bn_banco_id'] = $bn_banco_id;
        $registro['bn_tipo_sucursal_id'] = $bn_tipo_sucursal_id;
        $registro['codigo'] = $codigo;

        $alta = (new bn_sucursal($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_bn_tipo_banco(PDO $link, string $descripcion = 'TIPO BANCO 1', int $id = 1): array|\stdClass
    {

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;

        $alta = (new bn_tipo_banco($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_bn_tipo_cuenta(PDO $link, string $descripcion = 'TIPO CUENTA 1', int $id = 1): array|\stdClass
    {

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;

        $alta = (new bn_tipo_cuenta($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_bn_tipo_sucursal(PDO $link, string $descripcion = 'TIPO SUCURSAL 1', int $id = 1): array|\stdClass
    {

        $registro['id'] = $id;
        $registro['descripcion'] = $descripcion;

        $alta = (new bn_tipo_sucursal($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_puesto(PDO $link, int $id = 1): array|\stdClass
    {

        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_org_puesto(link: $link,id: $id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_sucursal(PDO $link, int $cat_sat_regimen_fiscal_id = 601, int $cat_sat_tipo_persona_id = 4,
                                      int $id = 1): array|\stdClass
    {

        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_org_sucursal(link: $link,
            cat_sat_regimen_fiscal_id: $cat_sat_regimen_fiscal_id, cat_sat_tipo_persona_id: $cat_sat_tipo_persona_id,
            id: $id);
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

    public function del_bn_cuenta(PDO $link): array
    {



        $del = $this->del($link, 'gamboamartin\\banco\\models\\bn_cuenta');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_bn_empleado(PDO $link): array
    {
        $del = $this->del_bn_cuenta(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\banco\\models\\bn_empleado');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }


    public function del_bn_sucursal(PDO $link): array
    {
        $del = $this->del_bn_cuenta($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\banco\\models\\bn_sucursal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_bn_tipo_cuenta(PDO $link): array
    {

        $del = $this->del_bn_cuenta($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\banco\\models\\bn_tipo_cuenta');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_bn_banco(PDO $link): array
    {
        $del = $this->del_bn_sucursal($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\banco\\models\\bn_banco');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_bn_tipo_banco(PDO $link): array
    {
        $del = $this->del_bn_banco($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\banco\\models\\bn_tipo_banco');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_bn_tipo_sucursal(PDO $link): array
    {


        $del = $this->del_bn_sucursal($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = $this->del($link, 'gamboamartin\\banco\\models\\bn_tipo_sucursal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_puesto(PDO $link): array
    {

        $del = $this->del_bn_empleado($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = (new \gamboamartin\organigrama\tests\base_test())->del_org_puesto(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }


}
