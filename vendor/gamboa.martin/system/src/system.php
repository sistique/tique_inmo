<?php

namespace gamboamartin\system;

use base\controller\controlador_base;
use base\orm\modelo;
use config\generales;
use config\views;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\errores\errores;
use gamboamartin\plugins\exportador;
use gamboamartin\system\_importador\_campos;
use gamboamartin\system\_importador\_doc;
use gamboamartin\system\_importador\_exporta;
use gamboamartin\system\_importador\_importa;
use gamboamartin\system\_importador\_maquetacion;
use gamboamartin\system\_importador\_xls;
use gamboamartin\system\html_controler\params;
use gamboamartin\template\directivas;
use gamboamartin\template\html;
use PDO;
use stdClass;
use Throwable;

/**
 * @var int $total_items_sections Corresponde al numero de acciones a mostrar en un menu lateral
 * @var array $actions_number Corresponde a la configuracion de links de un menu lateral
 *          $this->actions_number['contacto']['item'] = 4;
 *          $this->actions_number['contacto']['etiqueta'] = 'Contacto';
 *
 * @var string $menu_lateral Es un html con la forma de un menu lateral con acciones e items definidos
 *
 */
class system extends controlador_base
{

    public html_controler $html;

    public stdClass|array $acciones;

    public links_menu $obj_link;
    public array $secciones = array();
    public array $keys_row_lista = array();
    public array $rows_lista = array('id', 'codigo', 'codigo_bis', 'descripcion', 'descripcion_select', 'alias');
    public array $columnas_lista_data_table = array();
    public array $columnas_lista_data_table_filter = array();
    public array $datatable = array();
    public array $datatables = array();
    public array $inputs_alta = array('codigo', 'codigo_bis', 'descripcion', 'descripcion_select', 'alias');
    public array $inputs_modifica = array('id', 'codigo', 'codigo_bis', 'descripcion', 'descripcion_select', 'alias');
    public string $forms_inputs_alta = '';
    public string $forms_inputs_modifica = '';
    public html $html_base;
    public array $btns = array();
    public string $include_menu_secciones = '';
    public int $total_items_sections = 0;
    public string $menu_lateral = '';
    public array $actions_number = array();
    public string $include_breadcrumb = '';
    public string $contenido_table = '';
    public bool $lista_get_data = false;
    public array $not_actions = array();

    public stdClass $adm_seccion_ejecucion;

    public array|stdClass $keys_selects = array();

    protected string $key_id_filter = '';
    protected string $key_id_row = '';

    public string $template_lista = "";

    public int $doc_tipo_documento_id = -1;

    protected modelo $modelo_doc_documento;

    /**
     * @param html_controler $html Html base
     * @param PDO $link Conexion a la base de datos
     * @param modelo $modelo
     * @param links_menu $obj_link
     * @param string $campo_busca
     * @param array $datatables_custom_cols
     * @param array $datatables_custom_cols_omite
     * @param stdClass $datatables
     * @param array $filtro_boton_lista
     * @param string $valor_busca_fault
     * @param stdClass $paths_conf
     */
    public function __construct(html_controler $html, PDO $link, modelo $modelo, links_menu $obj_link,
                                string         $campo_busca = 'registro_id', array $datatables_custom_cols = array(),
                                array          $datatables_custom_cols_omite = array(), stdClass $datatables = new stdClass(),
                                array          $filtro_boton_lista = array(), string $valor_busca_fault = '',
                                stdClass       $paths_conf = new stdClass())
    {
        $this->msj_con_html = false;
        parent::__construct(link: $link, modelo: $modelo, filtro_boton_lista: $filtro_boton_lista,
            campo_busca: $campo_busca, valor_busca_fault: $valor_busca_fault, paths_conf: $paths_conf);


        $seccion = $this->seccion;

        if ($seccion === '') {
            $error = $this->errores->error(mensaje: 'Error seccion esta vacia', data: $seccion);
            print_r($error);
            die('Error');
        }

        $this->html_base = $html->html_base;
        $init = (new init())->init_controller(controller: $this, html: $this->html_base);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar controller', data: $init);
            print_r($error);
            die('Error');
        }

        $this->secciones = (new generales())->secciones;

        $this->obj_link = $obj_link;
        $this->html = $html;


        $keys_row_lista = (new init())->keys_row_lista(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar $key_row_lista', data: $keys_row_lista);
            var_dump($error);
            die('Error');
        }

        $this->include_menu_secciones = "templates/$this->tabla/$this->accion/secciones.php";


        $include_breadcrumb_rs = (new init())->include_breadcrumb(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar include_breadcrumb_rs', data: $include_breadcrumb_rs);
            var_dump($error);
            die('Error');
        }

        foreach ($datatables_custom_cols_omite as $campo) {
            if (isset($datatables->columns[$campo])) {
                unset($datatables->columns[$campo]);
            }
        }

        $datatables = $this->aplica_limpia_filtro_omite(datatables: $datatables, datatables_custom_cols_omite: $datatables_custom_cols_omite);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al limpia_filtro_dt ', data: $datatables);
            print_r($error);
            die('Error');
        }


        foreach ($datatables_custom_cols as $key => $column) {
            $datatables->columns[$key] = $column;
        }

        $data_for_datable = (new datatables())->datatable_base_init(
            datatables: $datatables, link: $this->link, rows_lista: $this->rows_lista, seccion: $this->seccion,
            not_actions: $this->not_actions);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al maquetar datos para tables ', data: $data_for_datable);
            print_r($error);
            die('Error');
        }

        $this->datatable_init(columns: $data_for_datable->columns, filtro: $data_for_datable->filtro,
            multi_selects: $data_for_datable->multi_selects, menu_active: $data_for_datable->menu_active,
            type: $data_for_datable->type);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar columnDefs', data: $this->datatable);
            var_dump($error);
            die('Error');
        }

        $seccion_en_ejecucion = (new adm_seccion(link: $this->link))->seccion_by_descripcion(descripcion: $this->seccion);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener seccion_en_ejecucion', data: $seccion_en_ejecucion);
            print_r($error);
            die('Error');
        }

        $keys = array('adm_namespace_descripcion');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $seccion_en_ejecucion);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error  adm_namespace_descripcion esta vacio en seccion_en_ejecucion', data: $valida);
            print_r($error);
            die('Error');
        }

        $template = $this->load_template();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al cargar template lista', data: $template);
            print_r($error);
            die('Error');
        }


        $this->path_vendor_views = $seccion_en_ejecucion->adm_namespace_descripcion;

        if ($modelo->etiqueta !== '') {
            $this->seccion_titulo = $modelo->etiqueta;
        }

        $this->key_id_row = $this->tabla . '_id';

        $link_importa_previo = $this->obj_link->link_sin_id(accion: 'importa_previo', link: $link, seccion: $this->seccion);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al cargar link importa previo', data: $link_importa_previo);
            print_r($error);
            die('Error');
        }
        $this->link_importa_previo = $link_importa_previo;

        $link_importa_previo_muestra = $this->obj_link->link_sin_id(accion: 'importa_previo_muestra', link: $link, seccion: $this->seccion);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al cargar link importa_previo_muestra previo', data: $link_importa_previo_muestra);
            print_r($error);
            die('Error');
        }
        $this->link_importa_previo_muestra = $link_importa_previo_muestra;

        $link_importa_previo_muestra_bd = $this->obj_link->link_sin_id(accion: 'importa_previo_muestra_bd', link: $link, seccion: $this->seccion);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al cargar link link_importa_previo_muestra_bd previo', data: $link_importa_previo_muestra_bd);
            print_r($error);
            die('Error');
        }
        $this->link_importa_previo_muestra_bd = $link_importa_previo_muestra_bd;


        foreach ($this->acciones_visibles_permitidas as $indice => $accion_vis) {

            $button = $this->html->button_href(accion: $accion_vis->adm_accion_descripcion,
                etiqueta: $accion_vis->adm_accion_titulo, registro_id: $this->registro_id,
                seccion: $accion_vis->adm_seccion_descripcion, style: $accion_vis->adm_accion_css,
                css_extra: 'item-br', cols: -1);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al cargar button', data: $button);
                print_r($error);
                die('Error');
            }

            $this->acciones_visibles_permitidas[$indice]->boton = $button;

        }


    }

    public function acciones_permitidas(bool $header, bool $ws = false): array
    {
        $acciones_permitidas = (new datatables())->acciones_permitidas(
            link: $this->link, seccion: $this->tabla);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener data result', data: $acciones_permitidas,
                header: $header, ws: $ws);
        }

        $salida['draw'] = count($acciones_permitidas);
        $salida['recordsTotal'] = count($acciones_permitidas);
        $salida['recordsFiltered'] = count($acciones_permitidas);
        $salida['data'] = $acciones_permitidas;

        header('Content-Type: application/json');
        echo json_encode($salida);
        exit;
    }

    /**
     * Funcion que genera los inputs y templates base para un alta
     * @param bool $header Si header muestra resultado via http
     * @param bool $ws Muestra resultado via Json
     * @return array|string
     * @esfinal rev
     */
    public function alta(bool $header, bool $ws = false): array|string
    {

        $data = (new _ctl_referencias())->referencias_alta(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar data parents', data: $data, header: $header, ws: $ws);
        }

        $keys_select = $this->valores_default_alta(keys_selects: $this->keys_selects,
            valores_asignados_default: $this->valores_asignados_default);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar data key selects default', data: $data,
                header: $header, ws: $ws);
        }
        $this->keys_selects = $keys_select;

        $form_alta = $this->genera_form_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar form', data: $form_alta,
                header: $header, ws: $ws);
        }

        $this->forms_inputs_alta = $form_alta;

        $include_inputs_alta = $this->include_inputs_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar include', data: $include_inputs_alta,
                header: $header, ws: $ws);
        }

        $this->include_inputs_alta = $include_inputs_alta;

        return $this->forms_inputs_alta;
    }

    private function aplica_limpia_filtro_dt(string $campo, stdClass $datatables): array|stdClass
    {
        if (isset($datatables->filtro)) {
            $datatables = $this->limpia_filtros_dt(campo: $campo, datatables: $datatables);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al limpia_filtro_dt ', data: $datatables);
            }
        }
        return $datatables;
    }

    private function aplica_limpia_filtro_omite(stdClass $datatables, array $datatables_custom_cols_omite): array|stdClass
    {
        foreach ($datatables_custom_cols_omite as $campo) {
            $datatables = $this->aplica_limpia_filtro_dt(campo: $campo, datatables: $datatables);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al limpia_filtro_dt ', data: $datatables);
            }
        }
        return $datatables;
    }

    /**
     * @param bool $header Si header mostrara el resultado en el navegador
     * @param bool $ws Mostrara el resultado en forma de json
     * @return array|stdClass
     * @finalrev
     */
    public function alta_bd(bool $header, bool $ws = false): array|stdClass
    {

        $transaccion_previa = false;
        if ($this->link->inTransaction()) {
            $transaccion_previa = true;
        }
        if (!$transaccion_previa) {
            $this->link->beginTransaction();
        }

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            if (!$transaccion_previa) {
                $this->link->rollBack();
            }
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }
        $seccion_retorno = $this->tabla;
        if (isset($_POST['seccion_retorno'])) {
            $seccion_retorno = $_POST['seccion_retorno'];
            unset($_POST['seccion_retorno']);
        }

        $id_retorno = -1;
        if (isset($_POST['id_retorno'])) {
            $id_retorno = $_POST['id_retorno'];
            unset($_POST['id_retorno']);
        }

        $params = array();
        if (isset($_POST['params'])) {
            $params = $_POST['params'];
            unset($_POST['params']);
        }

        $valida = $this->validacion->valida_alta_bd(controler: $this);
        if (errores::$error) {
            if (!$transaccion_previa) {
                $this->link->rollBack();
            }
            return $this->retorno_error(mensaje: 'Error al validar datos', data: $valida, header: $header, ws: $ws,
                class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $r_alta_bd = parent::alta_bd(header: false, ws: false);
        if (errores::$error) {
            if (!$transaccion_previa) {
                $this->link->rollBack();
            }
            return $this->retorno_error(mensaje: 'Error al dar de alta registro', data: $r_alta_bd, header: $header,
                ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }
        if (!$transaccion_previa) {
            $this->link->commit();
        }

        $out = $this->out_alta(header: $header, id_retorno: $id_retorno, r_alta_bd: $r_alta_bd,
            seccion_retorno: $seccion_retorno, siguiente_view: $siguiente_view, ws: $ws,params: $params);
        if (errores::$error) {
            print_r($out);
            die('Error');
        }

        $r_alta_bd->siguiente_view = $siguiente_view;
        return $r_alta_bd;
    }

    /**
     * REG
     * Asigna propiedades a un identificador dentro del array `$keys_selects`.
     * Si el identificador no existe en `$keys_selects`, lo inicializa como un objeto `stdClass`.
     *
     * @param string $identificador Identificador único para la propiedad a asignar.
     * @param array $propiedades Array asociativo con las propiedades a asignar. Las claves son los nombres de las propiedades y los valores sus respectivos valores.
     *
     * @return array|stdClass Devuelve `$keys_selects` con los valores asignados o un objeto `stdClass` con las nuevas propiedades.
     *
     * @throws array Si el identificador está vacío, devuelve un error a través de `$this->errores->error()`.
     *
     * @example
     * Ejemplo 1: Asignación de una propiedad nueva
     * ```php
     * $system = new system();
     * $resultado = $system->asignar_propiedad('usuario', ['nombre' => 'Juan', 'edad' => 30]);
     * print_r($resultado);
     * ```
     * Salida esperada:
     * ```php
     * Array
     * (
     *     [usuario] => stdClass Object
     *         (
     *             [nombre] => Juan
     *             [edad] => 30
     *         )
     * )
     * ```
     *
     * @example
     * Ejemplo 2: Asignar propiedades a un identificador ya existente
     * ```php
     * $system = new system();
     * $system->asignar_propiedad('producto', ['precio' => 100]);
     * $resultado = $system->asignar_propiedad('producto', ['stock' => 50]);
     * print_r($resultado);
     * ```
     * Salida esperada:
     * ```php
     * Array
     * (
     *     [producto] => stdClass Object
     *         (
     *             [precio] => 100
     *             [stock] => 50
     *         )
     * )
     * ```
     *
     * @example
     * Ejemplo 3: Error al pasar un identificador vacío
     * ```php
     * $system = new system();
     * $resultado = $system->asignar_propiedad('', ['clave' => 'valor']);
     * print_r($resultado);
     * ```
     * Salida esperada (error):
     * ```php
     * Array
     * (
     *     [error] => Error identificador esta vacio
     *     [data] => ''
     * )
     * ```
     */
    public function asignar_propiedad(string $identificador, array $propiedades): array|stdClass
    {
        $identificador = trim($identificador);
        if ($identificador === '') {
            return $this->errores->error(mensaje: 'Error identificador esta vacio', data: $identificador);
        }

        if (!array_key_exists($identificador, $this->keys_selects)) {
            $this->keys_selects[$identificador] = new stdClass();
        }

        foreach ($propiedades as $key => $value) {
            $this->keys_selects[$identificador]->$key = $value;
        }
        return $this->keys_selects;
    }


    public function data_ajax(bool $header, bool $ws = false, array $not_actions = array())
    {

        $params = (new datatables())->params(datatable: $this->datatable);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener params', data: $params, header: $header, ws: $ws);
        }

        $params->extra_join = array();
        $params->columnas = array();


        if (isset($_GET['columns'])) {
            $params->columnas = $_GET['columns'];
        }

        if (isset($_GET['filtros'])) {
            $filtros = $_GET['filtros'];

            if (array_key_exists("filtro", $filtros)) {
                foreach ($filtros["filtro"] as $index => $filtro) {
                    $keys = array_keys($filtro);

                    if (!array_key_exists("key", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no exite la clave key', data: $filtro, header: $header,
                            ws: $ws);
                    }

                    if (!array_key_exists("valor", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no exite la clave valor', data: $filtro, header: $header,
                            ws: $ws);
                    }
                    $params->filtro[$filtro['key']] = $filtro['valor'];
                }
            }

            if (array_key_exists("filtro_especial", $filtros)) {
                foreach ($filtros["filtro_especial"] as $index => $filtro) {
                    $keys = array_keys($filtro);

                    if (!array_key_exists("key", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no exite la clave key', data: $filtro, header: $header,
                            ws: $ws);
                    }

                    if (!array_key_exists("valor", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no exite la clave valor', data: $filtro, header: $header,
                            ws: $ws);
                    }

                    if (!array_key_exists("operador", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no existe la clave operador', data: $filtro, header: $header,
                            ws: $ws);
                    }

                    if (!array_key_exists("comparacion", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no existe la clave comparacion', data: $filtro,
                            header: $header, ws: $ws);
                    }

                    if (trim($filtro['valor']) !== "") {
                        $params->filtro_especial[$index][$filtro['valor']]['operador'] = $filtro['operador'];
                        $params->filtro_especial[$index][$filtro['valor']]['valor'] = $filtro['key'];
                        $params->filtro_especial[$index][$filtro['valor']]['comparacion'] = $filtro['comparacion'];
                        $params->filtro_especial[$index][$filtro['valor']]['valor_es_campo'] = true;
                    }
                }
            }

            if (array_key_exists("extra_join", $filtros)) {
                foreach ($filtros["extra_join"] as $index => $filtro) {
                    $keys = array_keys($filtro);

                    if (!array_key_exists("entidad", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no existe la clave entidad', data: $filtro, header: $header,
                            ws: $ws);
                    }

                    if (!array_key_exists("key", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no existe la clave key', data: $filtro, header: $header,
                            ws: $ws);
                    }

                    if (!array_key_exists("enlace", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no existe la clave enlace', data: $filtro, header: $header,
                            ws: $ws);
                    }

                    if (!array_key_exists("key_enlace", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no existe la clave key_enlace', data: $filtro, header: $header,
                            ws: $ws);
                    }

                    if (!array_key_exists("renombre", $filtro)) {
                        return $this->retorno_error(mensaje: 'Error no exite la clave renombre', data: $filtro, header: $header,
                            ws: $ws);
                    }
                    $params->extra_join[$filtro['entidad']]['key'] = $filtro['key'];
                    $params->extra_join[$filtro['entidad']]['enlace'] = $filtro['enlace'];
                    $params->extra_join[$filtro['entidad']]['key_enlace'] = $filtro['key_enlace'];
                    $params->extra_join[$filtro['entidad']]['renombre'] = $filtro['renombre'];
                }
            }

        }

        $data_result = $this->modelo->get_data_lista(filtro: $params->filtro, columnas: $params->columnas,
            filtro_especial: $params->filtro_especial, n_rows_for_page: $params->n_rows_for_page, pagina: $params->pagina,
            extra_join: $params->extra_join);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener data result', data: $data_result, header: $header, ws: $ws);
        }

        $out = (new datatables())->out_result(data_result: $data_result, params: $params, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar out', data: $out, header: $header, ws: $ws);
        }

        return $out;
    }

    public function descarga_layout(bool $header, bool $ws = true): array|stdClass
    {

        $xls = (new _exporta())->layout(header: true, modelo: $this->modelo);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al exportar', data: $xls, header: $header,
                ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        return $xls;

    }


    /**
     * Genera los botones para modifica view base
     * @return array|stdClass
     * @version 7.65.3
     */
    private function buttons_upd(): array|stdClass
    {
        if (!isset($this->row_upd)) {
            $this->row_upd = new stdClass();
        }
        if (!isset($this->row_upd->status)) {
            $row_upd = $this->init_row_upd();
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al inicializar row', data: $row_upd);
            }
            $this->row_upd->status = 'inactivo';
        }


        if (!isset($this->inputs)) {
            $this->inputs = new stdClass();
        }
        if (is_array($this->inputs)) {
            $this->inputs = new stdClass();
        }


        $button_status = (new directivas(html: $this->html_base))->button_href_status(
            cols: 12, registro_id: $this->registro_id, seccion: $this->seccion, status: $this->row_upd->status);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar boton', data: $button_status);
        }
        $this->inputs->status = $button_status;

        return $this->inputs;
    }

    private function datatable_columnDefs_init(array $columns, array $columndefs): array
    {
        $index_header = array();

        foreach ($columndefs as $item) {

            $keys = array_keys($item);

            $valida = $this->datatable_validate_columnDefs(keys: $keys);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al validar columnDefs', data: $valida);
            }

            if (array_key_exists("visible", $item) && array_key_exists("targets", $item)) {

                /**
                 * REFACTOTRIZAR
                 */
                $column["type"] = "text";
                $column["targets"] = ($item['targets'][0]) - 1;
                $rendered[]["index"] = $columns[$column["targets"]];
                foreach ($item["targets"] as $target) {
                    $rendered[]["index"] = $columns[$target];
                    array_push($index_header, $target);
                }
                $column["rendered"] = $rendered;
                $this->datatable["columnDefs"][] = $column;
            }
        }
        return $index_header;
    }

    final public function datatable_init(array $columns, array $filtro = array(), string $identificador = ".datatable",
                                         array $data = array(), array $in = array(), bool $multi_selects = false,
                                         bool  $menu_active = false, string $type = "datatable"): array
    {
        $this->datatable["type"] = $type;
        $this->datatable["columns"] = $columns;
        $this->datatable["filtro"] = $filtro;
        $this->datatable["data"] = $data;
        $this->datatable["multi_selects"] = $multi_selects;

        if ($type === "datatable") {
            $datatable = (new datatables())->datatable(columns: $columns, filtro: $filtro, identificador: $identificador,
                data: $data, in: $in, multi_selects: $multi_selects, menu_active: $menu_active, type: $type);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al generar datatables base', data: $datatable);
            }

            $this->datatable = $datatable;
            $this->datatables[] = $this->datatable;
        }

        return $this->datatable;
    }

    private function datatable_validate_columnDefs(array $keys): array|bool
    {
        $propiedades = array("type", "visible", "targets", "rendered");

        foreach ($keys as $key) {
            if (!in_array($key, $propiedades)) {
                return $this->errores->error(mensaje: 'Error la propiedad no esta definida', data: $this->datatable);
            }
        }

        return true;
    }

    public function descarga_excel(bool $header, bool $ws = false): bool|array
    {
        if (isset($_GET['texto_busqueda'])) {
            $_GET['search']['value'] = $_GET['texto_busqueda'];
        }

        $params = (new datatables())->params(datatable: $this->datatable);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener params', data: $params, header: $header, ws: $ws);
        }

        if (count($params->order) === 0) {
            $params->order[$this->tabla . '.id'] = 'DESC';
        }

        $result = $this->modelo->filtro_and(filtro: $params->filtro, filtro_especial: $params->filtro_especial,
            in: $params->in, order: $params->order);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener registros', data: $result, header: $header, ws: $ws);
        }

        $out = array();
        $out['n_registros'] = $result->n_registros;
        $out['registros'] = $result->registros;
        $out['data_result'] = $result;

        $out = (new datatables())->out_result(data_result: $out, params: $params, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar out', data: $out, header: $header, ws: $ws);
        }

        $ths = array();
        foreach ($this->datatables[0]['columns'] as $columna) {
            $ths[] = array('etiqueta' => $columna->title, 'campo' => $columna->data);
        }

        $keys = array();
        foreach ($ths as $data_th) {
            if ($data_th['etiqueta'] !== 'Acciones')
                $keys[] = $data_th['campo'];
        }

        $nombre_hojas[] = 'Registros';
        $keys_hojas['Registros'] = new stdClass();
        $keys_hojas['Registros']->keys = $keys;
        $keys_hojas['Registros']->registros = $out['data'];

        $xls = (new exportador())->genera_xls(header: $header, name: $this->seccion, nombre_hojas: $nombre_hojas,
            keys_hojas: $keys_hojas, path_base: $this->path_base);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener xls', data: $xls, header: $header, ws: $ws);
        }

        return false;
    }

    public function elimina_bd(bool $header, bool $ws): array|stdClass
    {
        $transaccion_previa = false;
        if ($this->link->inTransaction()) {
            $transaccion_previa = true;
        }
        if (!$transaccion_previa) {
            $this->link->beginTransaction();
        }

        $r_del = parent::elimina_bd(header: false, ws: false); // TODO: Change the autogenerated stub
        if (errores::$error) {
            if (!$transaccion_previa) {
                $this->link->rollBack();
            }
            return $this->retorno_error(mensaje: 'Error al eliminar', data: $r_del, header: $header,
                ws: $ws);
        }
        if (!$transaccion_previa) {
            $this->link->commit();
        }


        if (isset($_GET['accion_retorno'])) {
            $siguiente_view = $_GET['accion_retorno'];
        } else {
            $siguiente_view = (new actions())->init_alta_bd(siguiente_view: 'lista');
            if (errores::$error) {

                return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                    header: $header, ws: $ws);
            }
        }

        $seccion_retorno = $this->tabla;
        if (isset($_GET['seccion_retorno'])) {
            $seccion_retorno = $_GET['seccion_retorno'];
        }

        $id_retorno = -1;
        if (isset($_GET['id_retorno'])) {
            $id_retorno = $_GET['id_retorno'];
        }

        $params = array();
        if (isset($_POST['params'])) {
            $params = $_POST['params'];
            unset($_POST['params']);
        }

        $header_retorno = $this->header_retorno(accion: $siguiente_view, seccion: $seccion_retorno,
            id_retorno: $id_retorno,params: $params);
        if (errores::$error) {

            return $this->retorno_error(mensaje: 'Error al maquetar retorno', data: $header_retorno,
                header: $header, ws: $ws);
        }

        if ($header) {
            header('Location:' . $header_retorno);
            exit;
        }
        if ($ws) {
            header('Content-Type: application/json');
            try {
                echo json_encode($r_del, JSON_THROW_ON_ERROR);
            } catch (Throwable $e) {
                $error = $this->errores->error(mensaje: 'Error al dar salida json', data: $e);
                print_r($error);
                exit;
            }
            exit;
        }
        $r_del->siguiente_view = $siguiente_view;

        return $r_del;
    }

    /**
     * Integra los inputs par aun form de tipo alta
     * @return string|array
     * @version 8.87.1
     */
    private function form_alta(): string|array
    {
        $form_alta = '';
        foreach ($this->inputs_alta as $input_alta) {
            $input_alta = trim($input_alta);
            if ($input_alta === '') {
                return $this->errores->error(mensaje: 'Error input alta esta vacio', data: $this->inputs_alta);
            }
            if (!isset($this->inputs->$input_alta)) {
                return $this->errores->error(mensaje: 'Error ' . $input_alta . ' No esta definido como input',
                    data: $this->inputs_alta);
            }
            $form_alta .= $this->inputs->$input_alta;
        }
        return $form_alta;
    }

    /**
     * Integra los datos de unb form para view modifica
     * @return string|array
     * @version 7.114.3
     */
    private function form_modifica(): string|array
    {
        $form_modifica = '';
        foreach ($this->inputs_modifica as $input_modifica) {

            $input_modifica = trim($input_modifica);
            if ($input_modifica === '') {
                return $this->errores->error(mensaje: 'Error input_modifica esta vacio', data: $input_modifica);
            }
            if (!is_object($this->inputs)) {
                $this->inputs = new stdClass();
            }
            if (!isset($this->inputs->$input_modifica)) {
                $this->inputs->$input_modifica = '';
            }

            $form_modifica .= $this->inputs->$input_modifica;
        }
        $this->forms_inputs_modifica = $form_modifica;
        return $this->forms_inputs_modifica;
    }

    /**
     * Genera un form alta en html
     * @return array|string
     * @version 8.89.1
     */
    private function genera_form_alta(): array|string
    {
        $this->inputs = new stdClass();

        $inputs = $this->html->alta(controler: $this);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar inputs', data: $inputs);
        }

        $form_alta = $this->form_alta();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar form', data: $form_alta);
        }
        return $form_alta;
    }

    /**
     * Integra los inputs para front alta y modifica
     * @param array $keys_selects Parametros inicializados
     * @return array|stdClass
     */
    final public function genera_inputs(array $keys_selects = array()): array|stdClass
    {
        if (!is_object($this->inputs)) {
            return $this->errores->error(
                mensaje: 'Error controlador->inputs debe se run objeto', data: $this->inputs);
        }

        $inputs = $this->html->init_alta2(row_upd: $this->row_upd, modelo: $this->modelo, keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar inputs', data: $inputs);
        }

        $inputs_asignados = $this->asigna_inputs(inputs: $inputs);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al asignar inputs', data: $inputs_asignados);
        }

        return $inputs_asignados;
    }

    public function get_data(bool $header, bool $ws = false, array $not_actions = array())
    {
        $params = (new datatables())->params(datatable: $this->datatable);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener params', data: $params, header: $header, ws: $ws);
        }

        $data_result = $this->modelo->get_data_lista(filtro: $params->filtro, filtro_especial: $params->filtro_especial,
            filtro_extra: $params->filtro_extra, filtro_rango: $params->filtro_rango,
            n_rows_for_page: $params->n_rows_for_page, pagina: $params->pagina, in: $params->in, order: $params->order);

        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener data result', data: $data_result,
                header: $header, ws: $ws);
        }

        $acciones_permitidas = (new datatables())->acciones_permitidas(
            link: $this->link, seccion: $this->tabla, not_actions: $not_actions);

        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener data result', data: $acciones_permitidas,
                header: $header, ws: $ws);
        }


        $data_result = (new datatables())->ajusta_data_result(acciones_permitidas: $acciones_permitidas,
            data_result: $data_result, html_base: $this->html_base, seccion: $this->seccion);

        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar data_result', data: $data_result,
                header: $header, ws: $ws);
        }

        $out = (new datatables())->out_result(data_result: $data_result, params: $params, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar out', data: $out, header: $header, ws: $ws);
        }

        return $out;
    }

    protected function header_retorno(string $accion, string $seccion, int $id_retorno = -1, array $params = array()): array|string
    {
        $accion = trim($accion);
        $seccion = trim($seccion);

        if ($accion === '') {
            return $this->errores->error(mensaje: 'Error accion esta vacia', data: $accion);
        }
        if ($seccion === '') {
            return $this->errores->error(mensaje: 'Error seccion esta vacia', data: $seccion);
        }

        $retornos = (new init())->retornos_get(accion: $accion, seccion: $seccion, id_retorno: $id_retorno);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener retornos data', data: $retornos);
        }

        $vars_get = (new links_menu(link: $this->link,registro_id: $id_retorno))->var_gets(params_get: $params);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar params get', data: $vars_get);
        }

        $header_retorno = "index.php?seccion=$retornos->next_seccion&accion=$retornos->next_accion&adm_menu_id=$retornos->adm_menu_id";
        $header_retorno .= "&session_id=$this->session_id&registro_id=$retornos->id_retorno$vars_get";
        return $header_retorno;
    }

    final public function importa(bool $header = true, bool $ws = false): array|stdClass
    {
        $this->inputs = new stdClass();
        $input_file = $this->html->input_file(cols: 12, name: 'doc_origen', row_upd: new stdClass(), value_vacio: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $input_file, header: $header, ws: $ws);
        }

        $this->inputs->input_file = $input_file;

        return $this->inputs;
    }

    final public function importa_previo(bool $header = true, bool $ws = false): array|stdClass
    {

        $doc = (new _doc())->doc_importa(doc_tipo_documento_id: $this->doc_tipo_documento_id,
            modelo_doc_documento: $this->modelo_doc_documento);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar documento', data: $doc, header: $header,
                ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $valida = (new _campos())->valida_doc_importa(extension: $doc->extension);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al validar documento', data: $valida, header: $header,
                ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $columnas_xls = (new _xls())->columnas_xls(ruta: $doc->ruta, html_controler: $this->html, link: $this->link, tabla: $this->tabla);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar columnas_xls', data: $columnas_xls, header: $header, ws: $ws,
                class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $this->columnas_calc = $columnas_xls;
        $this->link_importa_previo_muestra .= '&doc_documento_id=' . $doc->doc_documento_id;

        return $columnas_xls;
    }

    final public function importa_previo_muestra(bool $header = true, bool $ws = false): array|stdClass
    {

        $doc_documento = $this->modelo_doc_documento->registro(registro_id: $_GET['doc_documento_id'],
            columnas_en_bruto: true, retorno_obj: true);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener documento', data: $doc_documento,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        unset($_POST['btn_action_next']);


        $rows_final = (new _maquetacion())->genera_rows(controler: $this, ruta_absoluta: $doc_documento->ruta_absoluta);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar rows_final', data: $rows_final,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $input_params_importa = $this->html->hidden(name: 'params_importa', value: $this->params_importa);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar input', data: $input_params_importa,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $this->input_params_importa = $input_params_importa;


        $rows_final = (new _maquetacion())->integra_chks(rows_final: $rows_final);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar inputs', data: $rows_final,
                header: $header, ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }

        $headers = (new _importa())->headers(controler: $this, ruta_absoluta: $doc_documento->ruta_absoluta);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener adm_campos', data: $headers);
        }

        $this->registros = $rows_final;
        $this->ths = $headers;


        $this->link_importa_previo_muestra_bd .= '&doc_documento_id=' . $_GET['doc_documento_id'];

        return $this->inputs;
    }

    final public function importa_previo_muestra_bd(bool $header = true, bool $ws = false): array|stdClass
    {

        $doc_documento = $this->modelo_doc_documento->registro(registro_id: $_GET['doc_documento_id'],
            columnas_en_bruto: true, retorno_obj: true);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener documento', data: $doc_documento,
                header: $header, ws: $ws);
        }
        $rs = (new _importa())->importa_registros_xls(modelo: $this->modelo,
            ruta_absoluta: $doc_documento->ruta_absoluta);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inserta registro', data: $rs, header: $header,
                ws: $ws, class: __CLASS__, file: __FILE__, function: __FUNCTION__, line: __LINE__);
        }
        $data_rs = serialize($rs);
        $data_rs = base64_encode($data_rs);
        $_SESSION['rs_importa'] = $data_rs;


        $rs->registro_id = -1;
        $out = $this->out_alta(header: $header, id_retorno: -1, r_alta_bd: $rs,
            seccion_retorno: $this->seccion, siguiente_view: 'importa_result', ws: $ws);
        if (errores::$error) {
            print_r($out);
            die('Error');
        }

        return $rs;
    }

    final public function importa_result(bool $header = true, bool $ws = false): array|stdClass
    {
        $rs_importa = $_SESSION['rs_importa'];
        $rs_importa = base64_decode($rs_importa);
        $rs_importa = unserialize($rs_importa);

        $datos = $rs_importa->datos;

        $columnas_xls = $datos->columns;
        $rows_xls = $datos->rows;

        $this->registros['columnas_xls'] = $columnas_xls;
        $this->registros['rows_xls'] = $rows_xls;
        $this->registros['rows_a_importar_db'] = $rs_importa->rows_a_importar_db;
        $this->registros['transacciones'] = $rs_importa->altas;


        //print_r($this->registros['transacciones']);exit;

        return $this->registros;

    }

    /**
     * Obtiene los includes de templates alta
     * @return array|string
     * @version 8.91.1
     */
    private function include_inputs_alta(): array|string
    {
        $include_inputs_alta = (new generales())->path_base . "templates/inputs/$this->seccion/alta.php";
        if (!file_exists($include_inputs_alta)) {
            $include_inputs_alta = $this->include_inputs_alta_seccion();
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al generar include', data: $include_inputs_alta);
            }
        }
        return $include_inputs_alta;
    }

    /**
     * Integra los includes de datos para views
     * @return string
     * @version 8.90.1
     */
    private function include_inputs_alta_seccion(): string
    {
        $include_inputs_alta = (new views())->ruta_templates . "inputs/base/alta.php";

        $path_vendor_base = $this->path_base
            . "vendor/$this->path_vendor_views/templates/inputs/$this->seccion/$this->accion.php";

        if (file_exists($path_vendor_base)) {
            $include_inputs_alta = $path_vendor_base;
        }
        return $include_inputs_alta;
    }

    /**
     * Inicializa un row para upd
     * @return stdClass
     * @version 7.62.3
     */
    private function init_row_upd(): stdClass
    {
        if (!isset($this->row_upd)) {
            $this->row_upd = new stdClass();
        }
        if (!isset($this->row_upd->status)) {
            $this->row_upd->status = '';
        }
        return $this->row_upd;
    }

    /**
     * Integra un include para modifica
     * @return string
     * @version 8.14.0
     */
    private function include_inputs_modifica(): string
    {
        $include_inputs_modifica = (new generales())->path_base . "templates/inputs/$this->seccion/modifica.php";
        if (!file_exists($include_inputs_modifica)) {
            $include_inputs_modifica = (new views())->ruta_templates . "inputs/base/modifica.php";

            $path_vendor_base = $this->path_base . "vendor/$this->path_vendor_views/templates/inputs/$this->seccion/$this->accion.php";
            if (file_exists($path_vendor_base)) {
                $include_inputs_modifica = $path_vendor_base;
            }
        }
        $this->include_inputs_modifica = $include_inputs_modifica;
        return $this->include_inputs_modifica;
    }

    /**
     * Integra los inputs para front
     * @param array $keys_selects Parametros de inputs
     * @return array|stdClass
     */
    final public function inputs(array $keys_selects): array|stdClass
    {
        $keys_selects = $this->key_selects_txt(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $inputs = $this->genera_inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
        }
        return $inputs;
    }


    /**
     * Debe ser operable y sobreescrito en controller de ejecucion
     * @param array $keys_selects Conjunto de keys para select
     * @return array
     * @version 0.227.37
     * @finalrev
     * @por_doc true
     */
    protected function key_selects_txt(array $keys_selects): array
    {

        return $keys_selects;
    }

    /**
     * Limpia un filtro para datable
     * @param string $campo
     * @param string $campo_filtro
     * @param stdClass $datatables
     * @param int $indice
     * @return stdClass
     */
    private function limpia_filtro_dt(string $campo, string $campo_filtro, stdClass $datatables, int $indice): stdClass
    {
        if ($campo_filtro === $campo) {
            unset($datatables->filtro[$indice]);
        }
        return $datatables;
    }

    private function limpia_filtros_dt(string $campo, stdClass $datatables): array|stdClass
    {
        foreach ($datatables->filtro as $indice => $campo_filtro) {

            $datatables = $this->limpia_filtro_dt(
                campo: $campo, campo_filtro: $campo_filtro, datatables: $datatables, indice: $indice);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al limpia_filtro_dt ', data: $datatables);
            }
        }
        return $datatables;
    }

    /**
     * Genera la lista mostrable en la accion de cat_sat_tipo_persona / lista
     * @param bool $header if header se ejecuta en html
     * @param bool $ws retorna webservice
     * @return array
     */
    public function lista(bool $header, bool $ws = false): array
    {

        $this->registros = array();

        if (!$this->lista_get_data) {
            $registros_view = (new lista())->rows_view_lista(controler: $this);
            if (errores::$error) {
                return $this->retorno_error(
                    mensaje: 'Error al generar rows para lista en ' . $this->seccion, data: $registros_view,
                    header: $header, ws: $ws);
            }

            $this->registros = $registros_view;
            $n_registros = count($registros_view);
            $this->n_registros = $n_registros;
        }

        $include_lista_row = (new generales())->path_base . "templates/listas/$this->seccion/row.php";
        if (!file_exists($include_lista_row)) {
            $include_lista_row = (new views())->ruta_templates . "listas/row.php";
        }
        $this->include_lista_row = $include_lista_row;

        $include_lista_thead = (new generales())->path_base . "templates/listas/$this->seccion/thead.php";
        if (!file_exists($include_lista_thead)) {
            $include_lista_thead = (new views())->ruta_templates . "listas/thead.php";
        }

        $this->include_lista_thead = $include_lista_thead;

        return $this->registros;
    }

    public function load_table(bool $header, bool $ws = false, array $not_actions = array())
    {
        $response = array();
        $response['status'] = "Success";
        $response['message'] = "Se cargo correctamente los registros";

        $search = isset($_POST['search']) ? $_POST['search'] : '';
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 1;

        $cantidad = 10;
        $inicio = ($cantidad * $pagina) - $cantidad;

        if ($search !== '') {
            $cantidad = 0;
            $inicio = 0;
        }

        $total_registros = $this->modelo->cuenta();
        if (errores::$error) {
            $response['status'] = "Error";
            $response['message'] = "Error al obtener registros - " . $total_registros['mensaje_limpio'];
        }

        $filtro_especial = array();

        foreach ($this->datatable['filtro'] as $indice => $item) {
            $filtro_especial[$indice][$item]['operador'] = 'LIKE';
            $filtro_especial[$indice][$item]['valor'] = addslashes(trim("%$search%"));
            $filtro_especial[$indice][$item]['comparacion'] = "OR";
        }

        $data = $this->modelo->filtro_and(filtro_especial: $filtro_especial, limit: $cantidad, offset: $inicio,
            order: array($this->tabla . ".id" => "DESC"));
        if (errores::$error) {
            $response['status'] = "Error";
            $response['message'] = "Error al obtener registros - " . $data['mensaje_limpio'];
        }

        $registros = array();

        if (isset($data->registros)) {

            $acciones_permitidas = (new datatables())->acciones_permitidas(link: $this->link, seccion: $this->tabla,
                not_actions: $not_actions, columnas: array("adm_seccion_descripcion", "adm_accion_descripcion",
                    "adm_accion_titulo"));
            if (errores::$error) {
                $response['status'] = "Error";
                $response['message'] = "Error al obtener acciones - " . $acciones_permitidas['mensaje_limpio'];
            }
            $response['acciones'] = $acciones_permitidas;

            $registros['data'] = $data->registros;
            $registros['acciones'] = $acciones_permitidas;
        }

        $response['total_registros'] = $total_registros;

        if ($data->n_registros > 0 || $search === '') {
            $response['primer_registro'] = 1;
        } else if ($data->n_registros === 0 && $search === '') {
            $response['primer_registro'] = 5;
        } else {
            $response['primer_registro'] = 0;
        }


        $response['ultimo_registro'] = (($cantidad * $pagina) > $total_registros) ? $total_registros : ($data->n_registros * $pagina);
        $response['data'] = $data;

        ob_start();
        require_once((new views())->ruta_template_table . "template_table_append.php");
        $response['html'] = ob_get_clean();

        header('Content-type: application/json');
        echo json_encode($response);
        exit();
    }

    public function load_template(): string|array
    {
        if ($this->datatable['type'] === 'scroll') {
            if (!property_exists(new views(), "ruta_template_table")) {
                return $this->errores->error(mensaje: 'Error no existe ruta_template_table en config/views',
                    data: $this->datatable);
            }

            if (!file_exists((new views())->ruta_template_table . "template_table.php")) {
                return $this->errores->error(mensaje: 'Error no existe el archivo template_table.php',
                    data: (new views())->ruta_template_table);
            }

            ob_start();
            require_once((new views())->ruta_template_table . "template_table.php");
            $this->template_lista = ob_get_clean();
        }

        return $this->template_lista;
    }

    /**
     * Inicializa datos para vista modifica
     * @param bool $header Si header da salida en html
     * @param bool $ws Si ws da salida json
     * @return array|stdClass
     * @finalrev
     * @version 8.86.1
     */
    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        if ($this->registro_id <= 0) {
            return $this->retorno_error(mensaje: 'Error registro_id debe ser mayor a 0', data: $this->registro_id,
                header: $header, ws: $ws);
        }

        $r_modifica = parent::modifica(header: false, ws: $ws); // TODO: Change the autogenerated stub
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener template', data: $r_modifica,
                header: $header, ws: $ws);
        }

        $inputs = $this->html->modifica(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar inputs', data: $inputs,
                header: $header, ws: $ws);
        }

        $row_upd = $this->init_row_upd();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializa row upd', data: $row_upd,
                header: $header, ws: $ws);
        }

        $buttons = $this->buttons_upd();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar boton', data: $buttons, header: $header, ws: $ws);
        }

        $form = $this->form_modifica();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar form', data: $form, header: $header, ws: $ws);
        }

        $include_inputs_modifica = $this->include_inputs_modifica();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar include', data: $include_inputs_modifica,
                header: $header, ws: $ws);
        }

        $botones = (new _ctl_referencias())->integra_buttons_children(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar buttons', data: $botones, header: $header, ws: $ws);
        }

        $data = (new _ctl_referencias())->referencias_alta(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar data parents', data: $data, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function modifica_bd(bool $header, bool $ws): array|stdClass
    {
        $transaccion_previa = false;

        if ($this->link->inTransaction()) {
            $transaccion_previa = true;
        }
        if (!$transaccion_previa) {
            $this->link->beginTransaction();
        }

        if (isset($_POST['guarda'])) {
            unset($_POST['guarda']);
        }
        $r_modifica_bd = parent::modifica_bd(header: false, ws: false); // TODO: Change the autogenerated stub
        if (errores::$error) {
            if (!$transaccion_previa) {
                $this->link->rollBack();
            }
            return $this->retorno_error(mensaje: 'Error al modificar registro', data: $r_modifica_bd,
                header: $header, ws: $ws);
        }
        if (!$transaccion_previa) {
            $this->link->commit();
        }
        $_SESSION[$r_modifica_bd->salida][]['mensaje'] = $r_modifica_bd->mensaje . ' del id ' . $this->registro_id;
        $this->header_out(result: $r_modifica_bd, header: $header, ws: $ws);

        return $r_modifica_bd;
    }

    final protected function out_alta(bool   $header, int $id_retorno, stdClass $r_alta_bd, string $seccion_retorno,
                                      string $siguiente_view, bool $ws, $params = array()): true
    {
        if ($header) {
            if ($id_retorno === -1) {
                $id_retorno = $r_alta_bd->registro_id;
            }
            $this->retorno_base(registro_id: $id_retorno, result: $r_alta_bd, siguiente_view: $siguiente_view,
                ws: $ws, seccion_retorno: $seccion_retorno, valida_permiso: true, params: $params);
        }
        if ($ws) {
            header('Content-Type: application/json');
            try {
                echo json_encode($r_alta_bd, JSON_THROW_ON_ERROR);
            } catch (Throwable $e) {
                $error = (new errores())->error(mensaje: 'Error al maquetar JSON', data: $e);
                print_r($error);
            }
            exit;
        }
        return true;

    }

    function reemplazar_id_link($str, $start, $end, $replacement)
    {

        $replacement = $start . $replacement . $end;

        $start = preg_quote($start, '/');
        $end = preg_quote($end, '/');
        $regex = "/({$start})(.*?)({$end})/";

        return preg_replace($regex, $replacement, $str);
    }

    /**
     * REG
     * Ejecuta el retorno de una transacción después de una operación de alta o modificación en la base de datos.
     *
     * Esta función genera una URL de redirección basada en la vista siguiente y la sección de retorno. Si el `header`
     * está habilitado, redirige al usuario a la URL generada. Si ocurre un error, devuelve un mensaje de error con los
     * detalles de la operación.
     *
     * @param int $registro_id Identificador del registro procesado.
     * @param mixed $result Resultado de la operación previa (puede ser un objeto o array con los datos del proceso).
     * @param string $siguiente_view Vista a la que se redirigirá después de la operación.
     * @param bool $ws Si es `true`, la respuesta se enviará en formato JSON.
     * @param bool $header Si es `true`, realiza una redirección HTTP a la vista generada.
     * @param array $params Parámetros adicionales a incluir en la URL de redirección.
     * @param string $seccion_retorno Sección a la que se redirige después de la operación (por defecto, el valor de `$this->tabla`).
     * @param bool $valida_permiso Si es `true`, valida los permisos antes de permitir la redirección.
     *
     * @return bool|array Retorna `true` si la redirección se ejecuta correctamente. Si ocurre un error, devuelve un array con los detalles del error.
     *
     * @throws errores Si se produce un error en la generación del link de redirección.
     *
     * @example
     * // Ejemplo 1: Redirigir después de un alta a la vista "modifica"
     * $resultado = $this->retorno_base(
     *     registro_id: 15,
     *     result: $registro,
     *     siguiente_view: 'modifica',
     *     ws: false
     * );
     *
     * // Ejemplo 2: Retorno sin redirección, solo devuelve la URL
     * $resultado = $this->retorno_base(
     *     registro_id: 20,
     *     result: $registro,
     *     siguiente_view: 'detalle',
     *     ws: false,
     *     header: false
     * );
     *
     * @version 0.90.32
     */
    final public function retorno_base(int    $registro_id, mixed $result, string $siguiente_view, bool $ws,
                                       bool   $header = true, array $params = array(),
                                       string $seccion_retorno = '', bool $valida_permiso = false): bool|array
    {

        // Si no se proporciona una sección de retorno, se usa la tabla actual.
        if ($seccion_retorno === '') {
            $seccion_retorno = $this->tabla;
        }

        // Genera el enlace de redirección basado en los parámetros proporcionados.
        $retorno = (new actions())->retorno_alta_bd(
            link: $this->link,
            registro_id: $registro_id,
            seccion: $seccion_retorno,
            siguiente_view: $siguiente_view,
            params: $params,
            valida_permiso: $valida_permiso
        );

        // Si hay un error en la generación del enlace, devuelve un mensaje de error.
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al dar de alta registro',
                data: $result,
                header: $header,
                ws: $ws
            );
        }

        // Si `header` está habilitado, realiza la redirección HTTP.
        if ($header) {
            header('Location:' . $retorno);
            exit;
        }

        return true;
    }


    /**
     * Actualiza elementos por campo
     * @param string $key Elemento a actualizar
     * @return array|stdClass
     * @version 8.67.0
     * @finalrev
     */
    public function row_upd(string $key): array|stdClass
    {
        if ($this->registro_id <= 0) {
            return $this->errores->error(mensaje: 'Error this->registro_id debe ser mayor a 0',
                data: $this->registro_id);
        }
        $key = trim($key);
        if ($key === '') {
            return $this->errores->error(mensaje: 'Error key esta vacio', data: $key);
        }


        $row_upd = (new row())->integra_row_upd(key: $key, modelo: $this->modelo, registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener row upd', data: $row_upd);
        }

        $upd = $this->modelo->modifica_bd(registro: $row_upd, id: $this->registro_id);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al modificar adm_accion', data: $upd);
        }
        return $upd;
    }

    /**
     * Intregra las acciones a los registros
     * @param string $key_id Registro id
     * @param array $rows Conjunto de registros
     * @param string $seccion Seccion a integrar acciones
     * @param array $not_actions Acciones para omitir en lista
     * @param array $params Para anexar var get
     * @return array
     * @version 0.173.34
     */
    final protected function rows_con_permisos(
        string $key_id, array $rows, string $seccion, array $not_actions = array(), array $params = array()): array
    {

        if (!isset($_SESSION)) {
            return $this->errores->error(mensaje: 'Error no hay SESSION iniciada', data: array());
        }
        $keys = array('grupo_id');
        $valida = $this->validacion->valida_ids(keys: $keys, registro: $_SESSION);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al validar SESSION', data: $valida);
        }
        $seccion = trim($seccion);
        if ($seccion === '') {
            return $this->errores->error(mensaje: 'Error seccion esta vacia', data: $seccion);
        }

        $acciones_permitidas = (new datatables())->acciones_permitidas(link: $this->link, seccion: $seccion,
            not_actions: $not_actions);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener acciones', data: $acciones_permitidas);
        }
        $rows = (new out_permisos())->genera_buttons_permiso(
            acciones_permitidas: $acciones_permitidas, html: $this->html, key_id: $key_id, rows: $rows,
            params: $params);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar link', data: $rows);
        }
        return $rows;
    }

    /**
     * Inicializa valores default para keys selects
     * @param array $keys_selects Key donde estan los parametros de generacion de inputs
     * @param array $valores_asignados_default Valores default integrados
     * @return array
     * @version 10.1.0
     */
    private function valores_default_alta(array $keys_selects, array $valores_asignados_default): array
    {
        foreach ($valores_asignados_default as $campo => $valor) {
            if (!isset($keys_selects[$campo])) {
                $keys_selects[$campo] = new stdClass();
            }
            $keys_selects[$campo]->value_vacio = false;
            $keys_selects[$campo]->id_selected = $valor;
        }
        return $keys_selects;
    }


}
