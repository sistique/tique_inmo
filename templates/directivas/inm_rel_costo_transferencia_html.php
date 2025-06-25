<?php
namespace gamboamartin\inmuebles\html;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_bitacora_status_ubicacion;
use gamboamartin\inmuebles\models\inm_rel_costo_transferencia;
use gamboamartin\system\html_controler;
use PDO;
use stdClass;

class inm_rel_costo_transferencia_html extends html_controler {

    public function select_inm_rel_costo_transferencia_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                      bool $disabled = false, array $filtro = array()): array|string
    {
        $modelo = new inm_rel_costo_transferencia(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, filtro: $filtro, label: 'transferencia', required: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
