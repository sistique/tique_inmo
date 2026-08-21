<?php

namespace gamboamartin\inmuebles\controllers;

use stdClass;

/**
 * Trait para restaurar los valores de selects y row_upd desde
 * $_SESSION['registro_en_proceso'] cuando el formulario es re-mostrado
 * tras un error en alta_bd.
 *
 * El framework guarda $_POST en $_SESSION['registro_en_proceso'][$seccion]
 * durante alta_bd, pero system::alta() sobreescribe controlador_base::alta()
 * sin llamar a parent, por lo que los valores del proceso nunca se restauran
 * automáticamente a los selects (id_selected).
 *
 * Uso:
 *  - Llama init_keys_selects_desde_proceso() al inicio de alta() para
 *    pre-poblar $keys_selects con id_selected desde sesión.
 *  - Llama init_row_upd_desde_proceso() antes de init_alta() para
 *    poblar $this->row_upd con los valores previos del formulario.
 */
trait _registro_proceso
{
    /**
     * Pre-pobla $keys_selects con valores de ID provenientes de
     * $_SESSION['registro_en_proceso'][$this->seccion].
     *
     * key_select() ya respeta valores pre-asignados en $keys_selects[$key]->id_selected,
     * por lo que llamar este método antes de cualquier key_select() garantiza que
     * el select mostrará la opción que el usuario tenía seleccionada.
     *
     * Solo aplica campos con valor entero > 0 (IDs de catálogo).
     *
     * @param array $keys_selects Array de keys selects a pre-poblar (puede venir vacío)
     * @return array $keys_selects con id_selected pre-asignados desde sesión
     */
    protected function init_keys_selects_desde_proceso(array $keys_selects = []): array
    {
        $registro = $_SESSION['registro_en_proceso'][$this->seccion] ?? [];
        foreach ($registro as $key => $value) {
            if (is_numeric($value) && (int)$value > 0) {
                if (!isset($keys_selects[$key])) {
                    $keys_selects[$key] = new stdClass();
                }
                // Solo asignar si no fue ya establecido por lógica de negocio previa
                if (!isset($keys_selects[$key]->id_selected)) {
                    $keys_selects[$key]->id_selected = (int)$value;
                }
            }
        }
        return $keys_selects;
    }

    /**
     * Popula $this->row_upd con los valores guardados en
     * $_SESSION['registro_en_proceso'][$this->seccion].
     *
     * Solo asigna propiedades que NO estén ya presentes en $this->row_upd,
     * para no sobreescribir valores asignados por lógica de negocio posterior.
     *
     * Llamar ANTES de init_alta() para que los inputs de texto también
     * muestren los valores previos del formulario.
     */
    protected function init_row_upd_desde_proceso(): void
    {
        if (!isset($this->row_upd)) {
            $this->row_upd = new stdClass();
        }
        $registro = $_SESSION['registro_en_proceso'][$this->seccion] ?? [];
        foreach ($registro as $key => $value) {
            if (!isset($this->row_upd->$key)) {
                $this->row_upd->$key = $value;
            }
        }
    }
}

