<?php
namespace gamboamartin\im_registro_patronal\test;
use base\orm\modelo_base;
use gamboamartin\cat_sat\models\cat_sat_isn;
use gamboamartin\empleado\models\em_clase_riesgo;
use gamboamartin\empleado\models\em_empleado;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\im_registro_patronal\models\im_clase_riesgo;
use gamboamartin\im_registro_patronal\models\im_movimiento;
use gamboamartin\im_registro_patronal\models\im_rcv;
use gamboamartin\im_registro_patronal\models\im_registro_patronal;
use gamboamartin\im_registro_patronal\models\im_tipo_movimiento;
use gamboamartin\im_registro_patronal\models\im_uma;
use PDO;

class base_test{

    public function alta_cat_sat_isn(PDO $link, int $id = 1): array|\stdClass
    {

        $alta = (new \gamboamartin\cat_sat\tests\base_test())->alta_cat_sat_isn(link: $link, id: $id);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_em_clase_riesgo(PDO $link, int $id = 1): array|\stdClass
    {

        $alta = ((new \gamboamartin\empleado\test\base_test()))->alta_em_clase_riesgo(link: $link, id: $id);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_em_empleado(PDO $link, int $id = 1): array|\stdClass
    {

        $alta = (new \gamboamartin\empleado\test\base_test())->alta_em_empleado(link: $link, id: $id);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_em_registro_patronal(PDO $link, int $id = 1): array|\stdClass
    {

        $alta = ((new \gamboamartin\empleado\test\base_test()))->alta_em_registro_patronal(link: $link, id: $id);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_fc_csd(PDO $link, int $id = 1): array|\stdClass
    {

        $alta = (new \gamboamartin\facturacion\tests\base_test())->alta_fc_csd(link: $link, id: $id);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }


    public function alta_im_clase_riesgo(PDO $link, string $codigo = '1', string $codigo_bis = '1',
                                            string $descripcion = '1', float $factor = .01, int $id = 1): array|\stdClass
    {
        $im_clase_riesgo = array();
        $im_clase_riesgo['id'] = $id;
        $im_clase_riesgo['codigo'] = $codigo;
        $im_clase_riesgo['codigo_bis'] = $codigo_bis;
        $im_clase_riesgo['descripcion'] = $descripcion;
        $im_clase_riesgo['factor'] = $factor;



        $alta = (new im_clase_riesgo($link))->alta_registro($im_clase_riesgo);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_im_movimiento(PDO $link, int $em_empleado_id = 1, int $em_registro_patronal_id = 1,
                                       int $im_tipo_movimiento_id = 1): array|\stdClass
    {

        $existe = (new em_registro_patronal($link))->existe_by_id(registro_id: $em_registro_patronal_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar modelo', $existe);
        }
        if(!$existe){
            $alta = (new base_test())->alta_em_registro_patronal(link: $link, id: $em_registro_patronal_id);
            if(errores::$error){
                return (new errores())->error('Error al dar de alta ', $alta);

            }
        }

        $existe = (new im_tipo_movimiento($link))->existe_by_id(registro_id: $im_tipo_movimiento_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar modelo', $existe);
        }
        if(!$existe){
            $alta = (new base_test())->alta_im_tipo_movimiento(link: $link, id: $im_tipo_movimiento_id);
            if(errores::$error){
                return (new errores())->error('Error al dar de alta ', $alta);

            }
        }

        $existe = (new em_empleado($link))->existe_by_id(registro_id: $em_empleado_id);
        if(errores::$error){
            return (new errores())->error('Error al verificar modelo', $existe);
        }
        if(!$existe){
            $alta = (new base_test())->alta_em_empleado(link: $link, id: $em_empleado_id);
            if(errores::$error){
                return (new errores())->error('Error al dar de alta ', $alta);

            }
        }


        $org_puesto = array();
        $org_puesto['id'] = 1;
        $org_puesto['codigo'] = 1;
        $org_puesto['descripcion'] = 1;
        $org_puesto['em_registro_patronal_id'] = $em_registro_patronal_id;
        $org_puesto['im_tipo_movimiento_id'] = $im_tipo_movimiento_id;
        $org_puesto['em_empleado_id'] = $em_empleado_id;
        $org_puesto['fecha'] = '2022-09-13';


        $alta = (new im_movimiento($link))->alta_registro($org_puesto);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_im_registro_patronal(PDO $link, int $cat_sat_isn_id = 1, int $em_clase_riesgo_id = 1,
                                              int $id = 1, int $fc_csd_id = 1): array|\stdClass
    {

        $existe = (new fc_csd($link))->existe_by_id(registro_id: $fc_csd_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_fc_csd(link: $link, id: $fc_csd_id);
            if(errores::$error){
                return (new errores())->error('Error al dar de alta ', $alta);

            }

        }

        $existe = (new cat_sat_isn($link))->existe_by_id(registro_id: $cat_sat_isn_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_cat_sat_isn(link: $link, id: $cat_sat_isn_id);
            if(errores::$error){
                return (new errores())->error('Error al dar de alta ', $alta);

            }

        }

        $existe = (new em_clase_riesgo($link))->existe_by_id(registro_id: $em_clase_riesgo_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_em_clase_riesgo(link: $link, id: $em_clase_riesgo_id);
            if(errores::$error){
                return (new errores())->error('Error al dar de alta ', $alta);

            }

        }

        $existe = (new em_clase_riesgo($link))->existe_by_id(registro_id: $em_clase_riesgo_id);
        if (errores::$error) {
            return (new errores())->error('Error al validar si existe', $existe);

        }

        if(!$existe) {
            $alta = (new base_test())->alta_em_clase_riesgo(link: $link, id: $em_clase_riesgo_id);
            if(errores::$error){
                return (new errores())->error('Error al dar de alta ', $alta);

            }

        }



        $org_puesto = array();
        $org_puesto['id'] = $id;
        $org_puesto['codigo'] = 1;
        $org_puesto['descripcion'] = 1;
        $org_puesto['em_clase_riesgo_id'] = $em_clase_riesgo_id;
        $org_puesto['cat_sat_isn_id'] = $cat_sat_isn_id;
        $org_puesto['fc_csd_id'] = $fc_csd_id;
        $org_puesto['descripcion_select'] = 1;


        $alta = (new im_registro_patronal($link))->alta_registro($org_puesto);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_im_tipo_movimiento(PDO $link, string $codigo = '1', string $codigo_bis = '1',
                                            string $descripcion = '1', string $es_alta = 'inactivo', int $id = 1): array|\stdClass
    {
        $org_puesto = array();
        $org_puesto['id'] = $id;
        $org_puesto['codigo'] = $codigo;
        $org_puesto['codigo_bis'] = $codigo_bis;
        $org_puesto['descripcion'] = $descripcion;
        $org_puesto['es_alta'] = $es_alta;



        $alta = (new im_tipo_movimiento($link))->alta_registro($org_puesto);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_im_uma(PDO $link, string $codigo = '1', string $codigo_bis = '1', string $descripcion = '1',
                                string $fecha_fin = '2020-12-31', string $fecha_inicio ='2020-01-01',
                                int $id = 1, float $monto = 0): array|\stdClass
    {
        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['codigo_bis'] = $codigo_bis;
        $registro['descripcion'] = $descripcion;
        $registro['fecha_inicio'] = $fecha_inicio;
        $registro['fecha_fin'] = $fecha_fin;
        $registro['monto'] = $monto;

        $alta = (new im_uma($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

   public function alta_im_rcv(PDO $link, string $codigo = '1', string $codigo_bis = '1', string $descripcion = '1',
                                float $monto_inicial = 0.01, float $monto_final = 999999999,
                                int $id = 1, float $factor = 0): array|\stdClass
    {
        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['codigo_bis'] = $codigo_bis;
        $registro['descripcion'] = $descripcion;
        $registro['monto_inicial'] = $monto_inicial;
        $registro['monto_final'] = $monto_final;
        $registro['factor'] = $factor;

        $alta = (new im_rcv($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }



    public function alta_org_puesto(PDO $link): array|\stdClass
    {

        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_org_puesto($link);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

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

    public function del_com_cliente(PDO $link): array
    {
        $del = (new \gamboamartin\comercial\test\base_test())->del_com_cliente(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_em_clase_riesgo(PDO $link): array
    {
        $del = (new \gamboamartin\empleado\test\base_test())->del_em_clase_riesgo(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_em_empleado(PDO $link): array
    {
        $del = (new base_test())->del_im_movimiento($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar ', $del);

        }

        $del = (new \gamboamartin\empleado\test\base_test())->del_em_empleado(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_em_registro_patronal(PDO $link): array
    {
        $del = (new \gamboamartin\empleado\test\base_test())->del_em_registro_patronal(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_clase_riesgo(PDO $link): array
    {

        $del = $this->del_im_registro_patronal($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar ', $del);

        }

        $del = $this->del($link, 'gamboamartin\im_registro_patronal\models\im_clase_riesgo');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }



    public function del_im_conf_pres_empresa(PDO $link): array
    {
        $del = $this->del($link, 'im_conf_pres_empresa');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_movimiento(PDO $link): array
    {
        $del = $this->del($link, 'gamboamartin\\im_registro_patronal\\models\\im_movimiento');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_registro_patronal(PDO $link): array
    {

        $del = (new base_test())->del_em_empleado($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar ', $del);

        }

        $del = $this->del($link, 'gamboamartin\im_registro_patronal\models\im_registro_patronal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_tipo_movimiento(PDO $link): array
    {

        $del = (new base_test())->del_im_movimiento($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar ', $del);

        }

        $del = $this->del($link, 'gamboamartin\im_registro_patronal\models\im_tipo_movimiento');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_uma(PDO $link): array
    {
        $del = $this->del($link, 'gamboamartin\im_registro_patronal\models\im_uma');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_clasificacion_dep(PDO $link): array
    {
        $del = (new \gamboamartin\organigrama\tests\base_test())->del_org_clasificacion_dep($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

}
