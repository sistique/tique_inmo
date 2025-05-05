<?php
namespace gamboamartin\facturacion\html;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_relacion;
use PDO;

class fc_relacion_html extends _relacion_html {

    public function select_fc_relacion_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                         array $columns_ds = array('fc_factura_descripcion_select'),
                                         bool $disabled = false, array $filtro = array(),
                                         array $registros = array()): array|string
    {
        $modelo = new fc_relacion(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, columns_ds: $columns_ds, disabled: $disabled, filtro: $filtro, label: 'Relacion',
            registros: $registros, required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
