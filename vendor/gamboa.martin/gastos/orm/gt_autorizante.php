<?php

namespace gamboamartin\gastos\models;

use gamboamartin\errores\errores;
use gamboamartin\proceso\models\pr_proceso;
use PDO;
use PhpParser\Node\Expr\Array_;
use stdClass;

class gt_autorizante extends _base_transacciones
{
    public function __construct(PDO $link)
    {
        $tabla = 'gt_autorizante';
        $columnas = array($tabla => false, "em_empleado" => $tabla);
        $campos_obligatorios = array();

        $no_duplicados = array();

        $columnas_extra['em_empleado_nombre_completo'] = 'CONCAT (IFNULL(em_empleado.nombre,"")," ",IFNULL(em_empleado.ap, "")," ",IFNULL(em_empleado.am,""))';

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, no_duplicados: $no_duplicados);

        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $procesos_seleccionados = explode(",", $_POST['pr_procesos']);

        $campos = $this->integra_procesos(procesos: $procesos_seleccionados);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener procesos', data: $campos);
        }

        $this->registro = $this->limpia_campos_extras(registro: $this->registro,campos_limpiar: array('pr_procesos'));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos extras', data: $this->registro);
        }

        $this->registro = array_merge($this->registro, $campos);

        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar autorizante', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $procesos_seleccionados = explode(",", $_POST['pr_procesos']);

        $campos = $this->integra_procesos(procesos: $procesos_seleccionados);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener procesos', data: $campos);
        }

        $registro = $this->limpia_campos_extras(registro: $registro,campos_limpiar: array('pr_procesos'));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al limpiar campos extras', data: $registro);
        }

        $registro = array_merge($registro, $campos);

        $registro = $this->inicializa_campos(registros: $registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar campo base', data: $registro);
        }

        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva, $keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al modificar autorizante', data: $r_modifica_bd);
        }
        return $r_modifica_bd;
    }

    protected function inicializa_campos(array $registros): array
    {
        if (!isset($registros['codigo'])) {
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

    private function integra_procesos(array $procesos): array
    {
        $procesos = $this->verifica_procesos(procesos: $procesos);
        if (errores::$error) {
            return $this->error->error(mensaje: "Eror al verificar procesos", data: $procesos);
        }

        $campos = $this->genera_campo(procesos: $procesos);
        if (errores::$error) {
            return $this->error->error(mensaje: "Eror al generar campos", data: $campos);
        }

        return $campos;
    }

    private function verifica_procesos(array $procesos): array|stdClass
    {
        $salida = array();
        foreach ($procesos as $registro) {
            $proceso = (new pr_proceso($this->link))->registro(registro_id: $registro);
            if (errores::$error) {
                return $this->error->error(mensaje: "Eror el id $registro de proceso no existe", data: $proceso);
            }

            if (count($proceso) <= 0) {
                return $this->error->error(mensaje: "Error el id $registro de proceso no existe", data: $proceso);
            }
            array_push($salida, $proceso);
        }

        return $salida;
    }

    private function genera_campo(array $procesos)
    {
        $campos = array();
        $campos['puede_hacer_solicitudes'] = 0;
        $campos['puede_hacer_requisiciones'] = 0;
        $campos['puede_hacer_cotizaciones'] = 0;
        $campos['puede_hacer_ordenes'] = 0;

        foreach ($procesos as $registro) {

            switch ($registro['pr_proceso_descripcion']){
                case "SOLICITUD":
                    $campos['puede_hacer_solicitudes'] = 1;
                    break;
                case "REQUISICION":
                    $campos['puede_hacer_requisiciones'] = 1;
                    break;
                case "COTIZACION":
                    $campos['puede_hacer_cotizaciones'] = 1;
                    break;
                case "ORDEN COMPRA":
                    $campos['puede_hacer_ordenes'] = 1;
                    break;
            }
        }
        return $campos;
    }

    public function valida_permiso(int $gt_autorizante_id, ModeloConstantes $proceso): array|stdClass|bool
    {
        $pr_proceso = (new pr_proceso($this->link))->filtro_and(filtro: array('pr_proceso.descripcion' => $proceso->value));
        if (errores::$error) {
            return $this->error->error(mensaje: "Error al obtener proceso", data: $pr_proceso);
        }

        if ($pr_proceso->n_registros <= 0) {
            return $this->error->error(mensaje: "Error el proceso {$proceso->value} no existe", data: $pr_proceso);
        }

        $autorizante = $this->registro(registro_id: $gt_autorizante_id);
        if (errores::$error) {
            return $this->error->error(mensaje: "Error al obtener autorizante", data: $autorizante);
        }

        return match ($proceso) {
            ModeloConstantes::PR_PROCESO_SOLICITUD => $autorizante['gt_autorizante_puede_hacer_solicitudes'] == 1,
            ModeloConstantes::PR_PROCESO_REQUISICION => $autorizante['gt_autorizante_puede_hacer_requisiciones'] == 1,
            ModeloConstantes::PR_PROCESO_COTIZACION => $autorizante['gt_autorizante_puede_hacer_cotizaciones'] == 1,
            ModeloConstantes::PR_PROCESO_ORDEN_COMPRA => $autorizante['gt_autorizante_puede_hacer_ordenes'] == 1,
            default => $autorizante,
        };
    }
}