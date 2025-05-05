<?php
namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent_sin_codigo;
use gamboamartin\errores\errores;
use PDO;


class gt_orden_compra_cotizacion extends _base_auto_soli {

    public function __construct(PDO $link){
        $tabla = 'gt_orden_compra_cotizacion';
        $columnas = array($tabla=>false, "gt_cotizacion" => $tabla, "gt_orden_compra" => $tabla, "gt_tipo_orden_compra" => "gt_orden_compra");
        $campos_obligatorios = array();

        $no_duplicados = array();


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    protected function inicializa_campos(array $registros): array
    {
        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }

        $registros['codigo'] .= $registros['gt_orden_compra_id'];
        $registros['descripcion'] = $registros['codigo'];

        return $registros;
    }


}