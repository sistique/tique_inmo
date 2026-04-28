<?php
namespace gamboamartin\inmuebles\html;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_reparacion;
use gamboamartin\system\html_controler;
use PDO;

class inm_reparacion_html extends html_controler {

    public function select_inm_reparacion_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                             bool $disabled = false, array $filtro = array()): array|string
    {
        $modelo = new inm_reparacion(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, filtro: $filtro, label: 'Reparacion', required: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}

