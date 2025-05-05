<?php
namespace html;

use gamboamartin\documento\models\doc_tipo_documento;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;

class doc_tipo_documento_html extends html_controler {

    /**
     * Genera un select con tipos de documento
     * @param int $cols
     * @param bool $con_registros
     * @param int $id_selected
     * @param PDO $link
     * @param bool $disabled
     * @param array $filtro
     * @param array $registros
     * @return array|string
     */
    public function select_doc_tipo_documento_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                                 bool $disabled = false, array $filtro = array(),
                                                 array $registros = array()): array|string
    {
        $modelo = new doc_tipo_documento(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, filtro: $filtro, label: 'Tipo Documento', registros: $registros,
            required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
