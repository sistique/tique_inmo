<?php

namespace gamboamartin\gastos\models;

use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use gamboamartin\nomina\models\em_empleado;
use stdClass;

class _base_transacciones extends _modelo_parent
{

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $this->registro = $this->inicializa_campos($this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar campo base', data: $this->registro);
        }

        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar autorizante', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }

    protected function inicializa_campos(array $registros): array
    {
        $keys = array('em_empleado_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error validar campos', data: $valida);
        }

        $keys = array('codigo');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar codigo', data: $valida);
        }

        $empleado = (new em_empleado($this->link))->registro(registro_id: $registros['em_empleado_id'],
            columnas: array('em_empleado_nombre_completo'));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar registro', data: $empleado);
        }

        if (!array_key_exists('em_empleado_nombre_completo', $empleado)) {
            return $this->error->error(mensaje: 'Error no se encontro la key em_empleado_nombre_completo', data: $empleado);
        }

        $registros['descripcion'] = $empleado['em_empleado_nombre_completo'] . "- " . $registros['codigo'];

        return $registros;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
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

}