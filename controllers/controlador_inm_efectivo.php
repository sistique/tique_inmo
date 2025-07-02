<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\inmuebles\controllers;

use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_efectivo_html;
use gamboamartin\inmuebles\models\inm_efectivo;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_efectivo extends _ctl_formato {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_efectivo(link: $link);
        $html_ = new inm_efectivo_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion');
        $keys->selects = array();

        $init_data = array();
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }


        return $campos_view;
    }

    public function elimina_bd(bool $header, bool $ws = false): array|stdClass
    {
        $_POST['params'] = array();
        if(isset($_GET['pestana_general_actual'])) {
            $_POST['params'] = array('pestana_general_actual' => $_GET['pestana_general_actual'],
                'pestana_actual' => $_GET['pestana_actual']);
        }

        $r_elimina_bd =  parent::elimina_bd($header, $ws);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $r_elimina_bd, header: $header,ws:  $ws);
        }

        return $r_elimina_bd;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_efectivo_id"]["titulo"] = "Id";
        $columns["inm_efectivo_nombre_beneficiario"]["titulo"] = "Fecha Status";
        $columns["inm_efectivo_monto"]["titulo"] = "Monto";

        $filtro = array("inm_efectivo.id",
            "inm_efectivo.nombre_beneficiario", "inm_efectivo.monto");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }


}
