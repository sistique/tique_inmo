<?php
namespace html;

use gamboamartin\documento\models\doc_extension_permitido;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;

class doc_extension_permitido_html extends html_controler {

    public function select_doc_extension_permitido_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                                 bool $disabled = false): array|string
    {
        $modelo = new doc_extension_permitido(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: 'Extension Permitida', required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
