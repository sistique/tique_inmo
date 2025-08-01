<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\inmuebles\controllers;

use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_periodo_asistencia_html;
use gamboamartin\inmuebles\models\inm_checada;
use gamboamartin\inmuebles\models\inm_cheque;
use gamboamartin\inmuebles\models\inm_periodo_asistencia;
use gamboamartin\plugins\exportador;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_periodo_asistencia extends _ctl_formato {

    public string $link_descarga_reporte_bd = '';
    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_periodo_asistencia(link: $link);
        $html_ = new inm_periodo_asistencia_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $init_links = $this->init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
            print_r($error);
            die('Error');
        }
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }
        $keys_selects = array();
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_horario_id',
            keys_selects: $keys_selects, id_selected: -1, label: 'Horario');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $this->row_upd->fecha_inicio = date('Y-m-d');
        $this->row_upd->fecha_fin = date('Y-m-d');

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false, name: "fecha_inicio",
            place_holder: 'Fecha Inicio', value: $this->row_upd->fecha_inicio);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha_inicio = $fecha;

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false, name: "fecha_fin",
            place_holder: 'Fecha Fin', value: $this->row_upd->fecha_fin);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha_fin = $fecha;

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion');
        $keys->selects = array();

        $init_data = array();
        $init_data['inm_horario'] = "gamboamartin\\inmuebles";
        $init_data['inm_empleado'] = "gamboamartin\\inmuebles";
        $init_data['inm_status_asistencia'] = "gamboamartin\\inmuebles";
        $init_data['inm_tipo_checada'] = "gamboamartin\\inmuebles";
        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    protected function init_links(): array|string
    {
        $links = $this->obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "inm_periodo_asistencia", accion: "descarga_reporte_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link modifica_direccion', data: $link);
            print_r($error);
            exit;
        }
        $this->link_descarga_reporte_bd = $link;

        return $link;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = $this->init_modifica(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $keys_selects = array();
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_horario_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_horario_id, label: 'Horario');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false, name: "fecha_inicio",
            place_holder: 'Fecha Inicio', value: $this->row_upd->fecha_inicio);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha_inicio = $fecha;

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false, name: "fecha_fin",
            place_holder: 'Fecha Fin', value: $this->row_upd->fecha_fin);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha_fin = $fecha;

        return $r_modifica;
    }

    /**
     * Inicializa los elementos mostrables para datatables
     * @return stdClass
     */
    private function init_datatable(): stdClass
    {
        $columns["inm_periodo_asistencia_id"]["titulo"] = "Id";
        $columns["inm_periodo_asistencia_descripcion"]["titulo"] = "Descripcion";

        $filtro = array("inm_periodo_asistencia.id","inm_periodo_asistencia.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    public function descarga_reporte(bool $header, bool $ws = false): array|stdClass
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }

        $keys_selects = array();
        $columns_ds = array('inm_empleado_razon_social');
        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(), key: 'inm_empleado_id',
            keys_selects:$keys_selects, id_selected:-1, label: 'Empleado',
            columns_ds : $columns_ds,required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'inm_status_asistencia_id',
            keys_selects:$keys_selects, id_selected:-1, label: 'Status Asistencia',required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $keys_selects = $this->key_select(cols:6, con_registros: true,filtro:  array(), key: 'inm_tipo_checada_id',
            keys_selects:$keys_selects, id_selected:-1, label: 'Tipo Checada',required: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects,
                header: $header,ws:  $ws);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        $this->row_upd->fecha_inicio = date('Y-m-d');
        $this->row_upd->fecha_fin = date('Y-m-d');

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false, name: "fecha_inicio",
            place_holder: 'Fecha Inicio', value: $this->row_upd->fecha_inicio);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha_inicio = $fecha;

        $fecha = $this->html->input_fecha(cols: 6, row_upd: $this->row_upd, value_vacio: false, name: "fecha_fin",
            place_holder: 'Fecha Fin', value: $this->row_upd->fecha_fin);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener input fecha',data:  $fecha, header: $header,ws:  $ws);
        }
        $this->inputs->fecha_fin = $fecha;

        return $inputs;
    }

    public function descarga_reporte_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $nombre_hojas = array('Checadas');

        $registros = $this->result_checadas();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inm_prospecto_ubicacion', data: $registros, header: $header,
                ws: $ws);
        }

        $ths[] = array('etiqueta'=>'Inicio Semana', 'campo'=>'inm_periodo_asistencia_fecha_inicio');
        $ths[] = array('etiqueta'=>'Fin Semana', 'campo'=>'inm_periodo_asistencia_fecha_fin');
        $ths[] = array('etiqueta'=>'Empleado', 'campo'=>'inm_empleado_razon_social');
        $ths[] = array('etiqueta'=>'Fecha', 'campo'=>'inm_checada_fecha');
        $ths[] = array('etiqueta'=>'Dia', 'campo'=>'inm_checada_dia');
        $ths[] = array('etiqueta'=>'Hora Esperada', 'campo'=>'inm_checada_hora_esperada');
        $ths[] = array('etiqueta'=>'Hora de Entrada', 'campo'=>'inm_checada_hora');
        $ths[] = array('etiqueta'=>'Minutos de Retraso', 'campo'=>'inm_checada_minutos_retraso');
        $ths[] = array('etiqueta'=>'Estatus', 'campo'=>'inm_status_asistencia_descripcion');

        $keys_hojas['Checadas'] = new stdClass();
        $keys_hojas['Checadas']->keys = $ths;
        $keys_hojas['Checadas']->registros = $registros->registros;

        $xls = (new exportador())->genera_xls(header: $header, name: $this->seccion, nombre_hojas: $nombre_hojas,
            keys_hojas: $keys_hojas, path_base: $this->path_base);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener xls', data: $xls, header: $header, ws: $ws);
        }

        $this->link->commit();

        $link_integra_relacion_bd = $this->obj_link->link_sin_id(accion: 'descarga_reporte', link: $this->link,
            seccion: 'inm_periodo_asistencia');
        if (errores::$error) {
            $this->retorno_error(mensaje: 'Error al generar link', data: $link_integra_relacion_bd, header: $header,
                ws: $ws);
        }

        if($header) {
            header('Location:' . $link_integra_relacion_bd);
            exit;
        }

        return $xls;
    }

    private function result_checadas(): array|stdClass
    {
        $table = 'inm_checada';

        $filtro_rango = array();
        if(!empty($_POST['fecha_inicio'])){
            $filtro_rango[$table.'.fecha']['valor1'] = $_POST['fecha_inicio'];
        }
        if(!empty($_POST['fecha_fin'])) {
            $filtro_rango[$table . '.fecha']['valor2'] = $_POST['fecha_fin'];
        }

        $filtro = array();

        if(!empty($_POST['inm_empleado_id'])){
            $filtro['inm_empleado.id'] = $_POST['inm_empleado_id'];
        }

        if(!empty($_POST['inm_status_asistencia_id'])){
            $filtro['inm_status_asistencia.id'] = $_POST['inm_status_asistencia_id'];
        }

        if(!empty($_POST['inm_tipo_checada_id'])){
            $filtro['inm_tipo_checada.id'] = $_POST['inm_tipo_checada_id'];
        }

        $result = (new inm_checada(link: $this->link))->filtro_and(filtro: $filtro, filtro_rango: $filtro_rango);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener prospecto_ubicacions', data: $result);
        }

        return $result;
    }

}
