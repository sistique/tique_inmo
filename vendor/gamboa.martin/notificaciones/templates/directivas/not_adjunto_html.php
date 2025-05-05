<?php
namespace gamboamartin\notificaciones\html;

use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_adjunto;
use gamboamartin\system\html_controler;

use PDO;


class not_adjunto_html extends html_controler {
    public function select_not_adjunto_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                         array $columns_ds = array('not_adjunto_descripcion'),
                                         bool $disabled = false, array $filtro = array(),
                                         array $registros = array()): array|string
    {
        $modelo = new not_adjunto(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, columns_ds: $columns_ds, disabled: $disabled, filtro: $filtro, label: 'Adjunto',
            registros: $registros, required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}