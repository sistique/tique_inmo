<?php
namespace gamboamartin\notificaciones\html;

use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_mensaje_etapa;
use gamboamartin\system\html_controler;

use PDO;


class not_mensaje_etapa_html extends html_controler {
    public function select_not_rel_mensaje_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                         array $columns_ds = array('not_mensaje_etapa_descripcion'),
                                         bool $disabled = false, array $filtro = array(),
                                         array $registros = array()): array|string
    {
        $modelo = new not_mensaje_etapa(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, columns_ds: $columns_ds, disabled: $disabled, filtro: $filtro, label: 'Mensaje Etapa',
            registros: $registros, required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}