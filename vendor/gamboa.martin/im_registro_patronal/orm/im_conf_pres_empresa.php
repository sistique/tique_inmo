<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;
use gamboamartin\empleado\models\em_empleado;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_empresa;
use PDO;

class im_conf_pres_empresa extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_conf_pres_empresa";
        $columnas = array($tabla=>false, 'org_empresa'=>$tabla, 'im_conf_prestaciones'=>$tabla);
        $campos_obligatorios = array();

        $campos_view = array();
        $campos_view['org_empresa_id']['type'] = 'selects';
        $campos_view['org_empresa_id']['model'] = (new org_empresa($link));

        $campos_view['im_conf_prestaciones_id']['type'] = 'selects';
        $campos_view['im_conf_prestaciones_id']['model'] = (new im_conf_prestaciones($link));

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;

    }

    public function alta_bd(): array|\stdClass
    {
        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $this->registro['descripcion'];
        }

        if(!isset($this->registro['codigo_bis'])) {
            $this->registro['codigo_bis'] = $this->registro['codigo'];
        }

        if(!isset($this->registro['alias'])) {
            $this->registro['alias'] = $this->registro['codigo_bis'];
        }

        if(!isset($this->registro['descripcion_select'])) {
            $this->registro['descripcion_select'] = $this->registro['descripcion'];
        }

        $alta_bd = parent::alta_bd();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar movimiento', data: $alta_bd);
        }

        return $alta_bd;
    }

    public function obten_configuraciones_empresa(int $em_empleado_id){
        $org_empresa = (new em_empleado($this->link))->get_empresa(em_empleado_id: $em_empleado_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro empresa', data: $org_empresa);
        }

        $filtro['org_empresa.id'] = $org_empresa['org_empresa_id'];
        $r_conf_prestaciones = $this->filtro_and(filtro: $filtro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener registro configuracion', data: $r_conf_prestaciones);
        }

        return $r_conf_prestaciones->registros;
    }
}