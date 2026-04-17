<?php
namespace gamboamartin\inmuebles\html;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_presupuesto;
use gamboamartin\system\html_controler;
use PDO;
use stdClass;

class inm_presupuesto_html extends html_controler {

    public function select_inm_presupuesto_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                      bool $disabled = false, array $filtro = array()): array|string
    {
        $modelo = new inm_presupuesto(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, filtro: $filtro, label: 'Presupuesto', required: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    /**
     * Genera input para monto_proyectado
     */
    public function input_monto_proyectado(int $cols, stdClass $row_upd, bool $value_vacio = false,
                                           string $value = ''): array|string
    {
        $html = $this->directiva->input_text_required(disabled: false, name: 'monto_proyectado',
            place_holder: 'Monto Proyectado', row_upd: $row_upd, value: $value, cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }
        return $html;
    }

}

