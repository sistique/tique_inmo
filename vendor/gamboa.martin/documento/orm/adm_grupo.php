<?php
namespace gamboamartin\documento\models;
use PDO;



class adm_grupo extends \gamboamartin\administrador\models\adm_grupo {

    public function __construct(PDO $link, array $childrens = array(), array $columnas_extra = array())
    {
        $columnas_extra['adm_grupo_n_permisos_doc'] = /** @lang sql */
            "(SELECT COUNT(*) FROM doc_acl_tipo_documento WHERE doc_acl_tipo_documento.adm_grupo_id = adm_grupo.id)";
        parent::__construct(link: $link,childrens:  $childrens,columnas_extra:  $columnas_extra);
    }

}