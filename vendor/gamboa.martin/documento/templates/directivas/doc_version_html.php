<?php
namespace html;

use gamboamartin\documento\models\doc_extension;
use gamboamartin\documento\models\doc_version;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;

class doc_version_html extends html_controler {

    public function select_doc_version_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                                 bool $disabled = false): array|string
    {
        $modelo = new doc_version(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: 'Version', required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
