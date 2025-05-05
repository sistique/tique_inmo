<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\cat_sat\controllers;

use base\controller\controler;
use base\controller\salida_data;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use gamboamartin\errores\errores;

use gamboamartin\template\html;
use html\cat_sat_regimen_fiscal_html;
use links\secciones\link_cat_sat_regimen_fiscal;
use PDO;
use stdClass;

class controlador_cat_sat_regimen_fiscal extends _cat_sat_base {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new cat_sat_regimen_fiscal(link: $link);
        $html_ = new cat_sat_regimen_fiscal_html(html: $html);
        $obj_link = new link_cat_sat_regimen_fiscal(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $configuraciones = $this->init_configuraciones();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar configuraciones', data: $configuraciones);
            print_r($error);
            die('Error');
        }
    }

    public function get_regimen_fiscal(bool $header, bool $ws = true): array|stdClass
    {
        $filtro['cat_sat_regimen_fiscal.descripcion'] = $_GET['regimen_fiscal'];
        $r_modelo = $this->modelo->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener datos',data:  $r_modelo,header: $header,ws: $ws);
        }

        if($r_modelo->n_registros <= 0){
            return $this->retorno_error(mensaje: 'Error no hay tipo persona',data:  $r_modelo,
                header:  $header,ws:  $ws);
        }

        if($header){
            $retorno = $_SERVER['HTTP_REFERER'];
            header('Location:'.$retorno);
            exit;
        }
        if($ws){
            header('Content-Type: application/json');
            try {
                echo json_encode($r_modelo->registros[0], JSON_THROW_ON_ERROR);
            }
            catch (Throwable $e){
                return $this->errores->error(mensaje: 'Error al maquetar estados',data:  $e);
            }
            exit;
        }

        return $r_modelo;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Regímenes  Fiscales';

        $this->lista_get_data = true;

        return $this;
    }

    private function init_datatable(): stdClass
    {
        $columns["cat_sat_regimen_fiscal_id"]["titulo"] = "Id";
        $columns["cat_sat_regimen_fiscal_codigo"]["titulo"] = "Código";
        $columns["cat_sat_regimen_fiscal_descripcion"]["titulo"] = "Régimen Fiscal";

        $filtro = array("cat_sat_regimen_fiscal.id","cat_sat_regimen_fiscal.codigo","cat_sat_regimen_fiscal.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Código');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 8, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Régimen Fiscal');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $base = $this->base_upd(keys_selects: array(), params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }
}
