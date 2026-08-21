<?php
/**
 * @author Módulo Financiero
 * @version 1.0.0
 * @created 2026-04-15
 */
namespace gamboamartin\inmuebles\controllers;

use base\controller\init;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\html\inm_presupuesto_html;
use gamboamartin\inmuebles\models\inm_categoria_financiera;
use gamboamartin\inmuebles\models\inm_mov_real;
use gamboamartin\inmuebles\models\inm_presupuesto;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_inm_presupuesto extends _ctl_base {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new inm_presupuesto(link: $link);
        $html_ = new inm_presupuesto_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->lista_get_data = true;
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al inicializar alta',data:  $r_alta, header: $header,ws:  $ws);
        }
        $this->row_upd->inm_categoria_financiera_id = -1;
        $this->row_upd->anio = (int)date('Y');
        $this->row_upd->mes = (int)date('n');
        $this->row_upd->monto_proyectado = 0;
        $this->row_upd->es_ingreso = 'activo';
        $this->row_upd->observaciones = '';

        $keys_selects = array();

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(),
            key: 'inm_categoria_financiera_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_categoria_financiera_id,
            label: 'Categoria Financiera');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs',data:  $inputs, header: $header,ws:  $ws);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('descripcion','monto_proyectado','anio','mes','es_ingreso','observaciones');
        $keys->selects = array();

        $init_data = array();
        $init_data['inm_categoria_financiera'] = "gamboamartin\\inmuebles";

        $campos_view = $this->campos_view_base(init_data: $init_data,keys:  $keys);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar campo view',data:  $campos_view);
        }

        return $campos_view;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template',data:  $r_modifica,header: $header,ws: $ws);
        }

        $keys_selects = array();

        $keys_selects = $this->key_select(cols:12, con_registros: true,filtro:  array(),
            key: 'inm_categoria_financiera_id',
            keys_selects: $keys_selects, id_selected: $this->row_upd->inm_categoria_financiera_id,
            label: 'Categoria Financiera');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(),params_ajustados: array());
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al integrar base',data:  $base, header: $header,ws:  $ws);
        }

        return $r_modifica;
    }

    /**
     * Vista de comparativa presupuesto vs real
     */
    public function comparativa(bool $header, bool $ws = false): array|stdClass
    {
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : 0;

        $modelo_presupuesto = new inm_presupuesto(link: $this->link);
        $comparativo = $modelo_presupuesto->reporte_comparativo(anio: $anio, mes: $mes);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener comparativo',data:  $comparativo, header: $header,ws:  $ws);
        }

        $this->comparativo = $comparativo;
        $this->anio_filtro = $anio;
        $this->mes_filtro = $mes;

        return $comparativo;
    }

    /**
     * Vista del dashboard financiero
     */
    public function dashboard(bool $header, bool $ws = false): array|stdClass
    {
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');
        $mes_actual = (int)date('n');

        $modelo_presupuesto = new inm_presupuesto(link: $this->link);
        $modelo_mov_real = new inm_mov_real(link: $this->link);

        // Flujo proyectado del mes actual
        $flujo_proyectado = $modelo_presupuesto->flujo_proyectado(anio: $anio, mes: $mes_actual);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener flujo proyectado',data:  $flujo_proyectado, header: $header,ws:  $ws);
        }

        // Totales reales del mes actual
        $totales_reales = $modelo_mov_real->totales_mes(anio: $anio, mes: $mes_actual);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener totales reales',data:  $totales_reales, header: $header,ws:  $ws);
        }

        // Saldo acumulado hasta mes actual
        $saldo_acumulado = $modelo_mov_real->saldo_acumulado(anio: $anio, mes_hasta: $mes_actual);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener saldo acumulado',data:  $saldo_acumulado, header: $header,ws:  $ws);
        }

        // Comparativa completa del año
        $comparativo_anual = $modelo_presupuesto->reporte_comparativo(anio: $anio);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener comparativo anual',data:  $comparativo_anual, header: $header,ws:  $ws);
        }

        // Categorías para desglose
        $categorias = (new inm_categoria_financiera(link: $this->link))->registros();
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener categorias',data:  $categorias, header: $header,ws:  $ws);
        }

        // Construir datos mensuales para gráfica (12 meses)
        $datos_mensuales = array();
        for($m = 1; $m <= 12; $m++){
            $flujo_proy_m = $modelo_presupuesto->flujo_proyectado(anio: $anio, mes: $m);
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener flujo mes '.$m,data:  $flujo_proy_m, header: $header,ws:  $ws);
            }

            $totales_real_m = $modelo_mov_real->totales_mes(anio: $anio, mes: $m);
            if(errores::$error){
                return $this->retorno_error(
                    mensaje: 'Error al obtener real mes '.$m,data:  $totales_real_m, header: $header,ws:  $ws);
            }

            $datos_mensuales[] = array(
                'mes' => $m,
                'nombre_mes' => $this->nombre_mes($m),
                'ingresos_proyectados' => $flujo_proy_m->total_ingresos_proyectados,
                'egresos_proyectados' => $flujo_proy_m->total_egresos_proyectados,
                'flujo_proyectado' => $flujo_proy_m->flujo_neto_proyectado,
                'ingresos_reales' => $totales_real_m->total_ingresos,
                'egresos_reales' => $totales_real_m->total_egresos,
                'flujo_real' => $totales_real_m->flujo_neto
            );
        }

        // Presupuesto disponible: lo que queda por gastar en el mes actual
        $presupuesto_egresos_mes = $flujo_proyectado->total_egresos_proyectados;
        $egresos_reales_mes = $totales_reales->total_egresos;
        $disponible_mes = $presupuesto_egresos_mes - $egresos_reales_mes;

        $out = new stdClass();
        $out->anio = $anio;
        $out->mes_actual = $mes_actual;
        $out->nombre_mes_actual = $this->nombre_mes($mes_actual);
        $out->flujo_proyectado = $flujo_proyectado;
        $out->totales_reales = $totales_reales;
        $out->saldo_acumulado = $saldo_acumulado;
        $out->comparativo_anual = $comparativo_anual;
        $out->categorias = $categorias;
        $out->datos_mensuales = $datos_mensuales;
        $out->disponible_mes = $disponible_mes;

        $this->dashboard_data = $out;
        $this->anio_filtro = $anio;

        return $out;
    }

    /**
     * Devuelve el nombre del mes en español
     */
    private function nombre_mes(int $mes): string
    {
        $meses = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
            7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
        return $meses[$mes] ?? '';
    }

    private function init_datatable(): stdClass
    {
        $columns["inm_presupuesto_id"]["titulo"] = "Id";
        $columns["inm_presupuesto_descripcion"]["titulo"] = "Descripcion";
        $columns["inm_categoria_financiera_descripcion"]["titulo"] = "Categoria";
        $columns["inm_presupuesto_anio"]["titulo"] = "Año";
        $columns["inm_presupuesto_mes"]["titulo"] = "Mes";
        $columns["inm_presupuesto_es_ingreso"]["titulo"] = "Es Ingreso";
        $columns["inm_presupuesto_monto_proyectado"]["titulo"] = "Monto Proyectado";
        $columns["inm_presupuesto_monto_real"]["titulo"] = "Monto Real";
        $columns["inm_presupuesto_diferencia"]["titulo"] = "Diferencia";

        $filtro = array("inm_presupuesto.id","inm_presupuesto.descripcion",
            'inm_categoria_financiera.descripcion',
            'inm_presupuesto.anio','inm_presupuesto.mes',
            'inm_presupuesto.es_ingreso','inm_presupuesto.monto_proyectado');

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'descripcion',
            keys_selects:$keys_selects, place_holder: 'Descripcion');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'monto_proyectado',
            keys_selects:$keys_selects, place_holder: 'Monto Proyectado');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'anio',
            keys_selects:$keys_selects, place_holder: 'Año');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'mes',
            keys_selects:$keys_selects, place_holder: 'Mes');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4,key: 'es_ingreso',
            keys_selects:$keys_selects, place_holder: 'Es Ingreso (activo/inactivo)');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 12,key: 'observaciones',
            keys_selects:$keys_selects, place_holder: 'Observaciones');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

}

