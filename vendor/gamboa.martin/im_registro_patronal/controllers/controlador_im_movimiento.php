<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */

namespace gamboamartin\im_registro_patronal\controllers;

use base\controller\controler;
use gamboamartin\documento\models\doc_documento;
use gamboamartin\empleado\models\em_empleado;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use gamboamartin\plugins\Importador;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use html\im_movimiento_html;
use gamboamartin\im_registro_patronal\models\im_movimiento;
use gamboamartin\template\html;
use gamboamartin\im_registro_patronal\models\im_tipo_movimiento;
use PDO;
use stdClass;

class controlador_im_movimiento extends _ctl_base
{
    public string $link_im_movimiento_sube_archivo = '';

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new im_movimiento(link: $link);
        $html_ = new im_movimiento_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

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
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $this->row_upd->fecha = date('Y-m-d');
        $this->row_upd->salario_diario = 0;
        $this->row_upd->salario_diario_integrado = 0;
        $this->row_upd->salario_mixto = 0;
        $this->row_upd->salario_variable = 0;

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion', 'salario_diario', 'salario_diario_integrado', 'observaciones',
            'factor_integracion', 'salario_mixto', 'salario_variable');
        $keys->fechas = array('fecha');
        $keys->selects = array();

        $init_data = array();
        $init_data['em_empleado'] = "gamboamartin\\empleado";
        $init_data['im_tipo_movimiento'] = "gamboamartin\\im_registro_patronal";
        $init_data['em_registro_patronal'] = "gamboamartin\\empleado";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    protected function init_configuraciones(): controler
    {
        $this->seccion_titulo = 'Movimiento';
        $this->titulo_lista = 'Registro de Movimientos';

        $this->lista_get_data = true;

        return $this;
    }

    protected function init_datatable(): stdClass
    {
        $columns["im_movimiento_id"]["titulo"] = "Id";
        $columns["em_empleado_nss"]["titulo"] = "NSS";
        $columns["em_empleado_nombre"]["titulo"] = "Empleado";
        $columns["em_empleado_nombre"]["campos"] = array("em_empleado_ap", "em_empleado_am");
        $columns["em_registro_patronal_descripcion"]["titulo"] = "Registro Patronal";
        $columns["im_tipo_movimiento_descripcion"]["titulo"] = "Tipo Movimiento";
        $columns["im_movimiento_fecha"]["titulo"] = "Fecha";

        $filtro = array("im_movimiento.id", "em_empleado.nss", "em_empleado.nombre", "em_empleado.ap", "em_empleado.am",
            "em_registro_patronal.descripcion", "im_tipo_movimiento.descripcion", "im_movimiento.fecha");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;
        $datatables->menu_active = true;

        return $datatables;
    }

    protected function init_links(): array|string
    {
        $links = $this->obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "im_movimiento", accion: "sube_archivo");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link sube_archivo', data: $link);
            print_r($error);
            exit;
        }

        $this->link_im_movimiento_sube_archivo = $link;

        return $link;
    }

    protected function init_selects(array $keys_selects, string $key, string $label, int $id_selected = -1, int $cols = 6,
                                    bool  $con_registros = true, array $filtro = array()): array
    {
        $keys_selects = $this->key_select(cols: $cols, con_registros: $con_registros, filtro: $filtro, key: $key,
            keys_selects: $keys_selects, id_selected: $id_selected, label: $label);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function init_selects_inputs(): array
    {
        $keys_selects = $this->init_selects(keys_selects: array(), key: "em_empleado_id", label: "Empleado",cols: 12);
        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "im_tipo_movimiento_id", label: "Tipo Movimiento");
        return $this->init_selects(keys_selects: $keys_selects, key: "em_registro_patronal_id", label: "Registro Patronal");
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'descripcion',
            keys_selects: $keys_selects, place_holder: 'Descripción');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha',
            keys_selects: $keys_selects, place_holder: 'Fecha');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'salario_diario',
            keys_selects: $keys_selects, place_holder: 'Salario Diario');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'salario_diario_integrado',
            keys_selects: $keys_selects, place_holder: 'Salario Diario Integrado');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'observaciones',
            keys_selects: $keys_selects, place_holder: 'Observaciones', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'factor_integracion',
            keys_selects: $keys_selects, place_holder: 'Factor de Integracion', required: true);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'salario_mixto',
            keys_selects: $keys_selects, place_holder: 'Salario Mixto', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'salario_variable',
            keys_selects: $keys_selects, place_holder: 'Salario Variable', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function lee_archivo(bool $header, bool $ws = false)
    {
        $doc_documento_modelo = new doc_documento($this->link);
        $doc_documento_modelo->registro['descripcion'] = rand();
        $doc_documento_modelo->registro['descripcion_select'] = rand();
        $doc_documento_modelo->registro['doc_tipo_documento_id'] = 1;
        $doc_documento = $doc_documento_modelo->alta_bd(file: $_FILES['archivo']);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al dar de alta el documento', data: $doc_documento);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        $columnas = array("empresa", "registro_patronal", "tipo_movimiento", "nss", "nombre", "ap", "am", "sd", "fi",
            "sdi", "sm", "sv", "fecha");
        $fechas = array("fecha");

        $movimientos_excel = Importador::getInstance()
            ->leer_registros(ruta_absoluta: $doc_documento->registro['doc_documento_ruta_absoluta'], columnas: $columnas,
                fechas: $fechas);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error obtener movimientos', data: $movimientos_excel);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }


        foreach ($movimientos_excel as $movimiento) {

            $filtro_rp['em_registro_patronal.descripcion'] = $movimiento->registro_patronal;
            $em_registro_patronal = (new em_registro_patronal($this->link))->filtro_and(filtro: $filtro_rp);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error obtener registros patronales', data: $em_registro_patronal);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            if ($em_registro_patronal->n_registros <= 0) {
                $error = $this->errores->error(mensaje: "Error: no existe el registro patronal $movimiento->registro_patronal 
                en em_registro_patronal", data: $movimiento);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $filtro_tipo_movimiento['im_tipo_movimiento.descripcion'] = $movimiento->tipo_movimiento;
            $im_tipo_movimiento = (new im_tipo_movimiento($this->link))->filtro_and(filtro: $filtro_tipo_movimiento);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error obtener tipo movimiento', data: $im_tipo_movimiento);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            if ($im_tipo_movimiento->n_registros <= 0) {
                $error = $this->errores->error(mensaje: "Error: no existe el tipo de moviento $movimiento->tipo_movimiento",
                    data: $movimiento);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $filtro_emp['em_empleado.nombre'] = $movimiento->nombre;
            $filtro_emp['em_empleado.ap'] = $movimiento->ap;
            $filtro_emp['em_empleado.am'] = $movimiento->am;
            if (isset($movimiento->nss)) {
                $filtro_emp['em_empleado.nss'] = $movimiento->nss;
            }
            $em_empleado = (new em_empleado($this->link))->filtro_and(filtro: $filtro_emp);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error obtener empleado', data: $em_empleado);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            if ($em_empleado->n_registros <= 0) {
                $error = $this->errores->error(mensaje: "Error: no existe el empleado $movimiento->nombre $movimiento->ap 
                $movimiento->am con NSS $movimiento->nss",
                    data: $movimiento);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $registro['im_tipo_movimiento_id'] = $im_tipo_movimiento->registros[0]['im_tipo_movimiento_id'];
            $registro['em_registro_patronal_id'] = $em_registro_patronal->registros[0]['em_registro_patronal_id'];
            $registro['em_empleado_id'] = $em_empleado->registros[0]['em_empleado_id'];
            $registro['salario_diario'] = $movimiento->sd ?? 0;
            $registro['salario_diario_integrado'] = $movimiento->sdi ?? 0;
            $registro['factor_integracion'] = $movimiento->fi ?? 0;
            $registro['salario_mixto'] = $movimiento->sm ?? 0;
            $registro['salario_variable'] = $movimiento->sv ?? 0;
            $registro['fecha'] = $movimiento->fecha;

            $alta_im_movimiento = (new im_movimiento($this->link))->alta_registro(registro: $registro);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al dar de alta registro', data: $alta_im_movimiento);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }
        }

        header('Location:' . $this->link_lista);
        exit;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $keys_selects['em_empleado_id']->id_selected = $this->registro['em_empleado_id'];
        $keys_selects['im_tipo_movimiento_id']->id_selected = $this->registro['im_tipo_movimiento_id'];
        $keys_selects['em_registro_patronal_id']->id_selected = $this->registro['em_registro_patronal_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function sube_archivo(bool $header, bool $ws = false)
    {
        $r_alta = parent::alta(header: false, ws: false); // TODO: Change the autogenerated stub
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar template', data: $r_alta);
        }

        return $r_alta;
    }
}
