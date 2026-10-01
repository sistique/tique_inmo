<?php
namespace html;

use gamboamartin\comercial\models\com_agente;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;


class com_agente_html extends html_controler {


    public function select_com_agente_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                         array $columns_ds = array(), bool $disabled = false,
                                         array $filtro = array(), array $in = array(),
                                         string $label = 'Agente', string $name = 'com_agente_id'): array|string
    {
        $modelo = new com_agente(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, columns_ds: $columns_ds, disabled: $disabled, filtro: $filtro, label: $label,
            name: $name, in: $in, required: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
