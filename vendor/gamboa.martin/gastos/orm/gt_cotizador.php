<?php

namespace gamboamartin\gastos\models;

use gamboamartin\errores\errores;
use PDO;


class gt_cotizador extends _base_transacciones
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_cotizador';
        $columnas = array($tabla => false, "em_empleado" => $tabla);
        $campos_obligatorios = array();

        $no_duplicados = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    protected function inicializa_campos(array $registros): array
    {
        if (!isset($registros['codigo'])){
            $registros['codigo'] = $this->get_codigo_aleatorio();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
            }
        }

        $init = parent::inicializa_campos($registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error inicializar campos', data: $init);
        }

        return $init;
    }
}