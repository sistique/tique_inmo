<?php
namespace gamboamartin\inmuebles\controllers;

use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\inmuebles\models\inm_cheque;
use gamboamartin\inmuebles\models\inm_co_acreditado;
use gamboamartin\inmuebles\models\inm_comprador;
use gamboamartin\inmuebles\models\inm_rel_cliente_valuador;
use gamboamartin\js_base\valida;
use PDO;
use setasign\Fpdi\Fpdi;
use stdClass;
use Throwable;

class _pdf
{

    private errores $error;
    private Fpdi $pdf;

    public function __construct(Fpdi $pdf)
    {
        $this->error = new errores();
        $this->pdf = $pdf;
    }

    /**
     * Anexa el template para solicitudes
     * @param string $file_plantilla Ruta de archivo relativa de plantilla
     * @param int $page no de pagina de plantilla a integrar
     * @param string $path_base Path base de sistema
     * @param bool $plantilla_cargada Si la plantilla no esta cargada la carga por primera vez
     * @return array|Fpdi
     * @version 1.119.1
     */
    private function add_template(string $file_plantilla, int $page, string $path_base,
                                  bool   $plantilla_cargada): Fpdi|array
    {
        $valida = $this->valida_datos_plantilla(file_plantilla: $file_plantilla, page: $page, path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos de plantilla', data: $valida);
        }

        $this->pdf->AddPage();
        $tpl_idx = $this->tpl_idx(file_plantilla: $file_plantilla, page: $page, path_base: $path_base,
            plantilla_cargada: $plantilla_cargada);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al importar plantilla', data: $tpl_idx);
        }
        $this->pdf->useTemplate($tpl_idx, null, null, null, null, true);

        return $this->pdf;
    }

    /**
     * Escribe en ele pdf los elementos del apartado 1 de la solicitud de infonavit
     * @param stdClass $data Datos de cliente
     * @return array|Fpdi
     * @version 1.123.1
     */
    private function apartado_1(stdClass $data): Fpdi|array
    {
        $valida = (new valida())->valida_existencia_keys(keys: array('inm_comprador'), registro: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        if (!is_array($data->inm_comprador)) {
            return $this->error->error(mensaje: 'Error $data->inm_comprador no es un array', data: $data);
        }

        $pdf = $this->entidades_infonavit(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        $pdf = $this->es_segundo_credito(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }
        return $pdf;
    }

    private function apartado_avaluo_1_fajardo(stdClass $data, modelo $modelo): Fpdi|array
    {
        $valida = (new valida())->valida_existencia_keys(keys: array('inm_comprador'), registro: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        if (!is_array($data->inm_comprador)) {
            return $this->error->error(mensaje: 'Error $data->inm_comprador no es un array', data: $data);
        }

        $this->pdf->SetFont('Arial', 'B', 10);

        $pdf = array();

        $keys_cliente = array();
        $keys_cliente[1] = array('x' => 25.5, 'y' => 31);
        $keys_cliente[3] = array('x' => 58.7, 'y' => 31);
        $keys_cliente[4] = array('x' => 92, 'y' => 31);
        $keys_cliente[5] = array('x' => 147.7, 'y' => 31);
        $keys_cliente[6] = array('x' => 92, 'y' => 31);
        $keys_cliente[7] = array('x' => 92, 'y' => 31);

        foreach ($keys_cliente as $key => $valor) {
            if ((int)$key === (int)$data->inm_comprador['inm_destino_credito_id']) {
                $pdf[] = $this->write(valor: 'X', x: $valor['x'], y: $valor['y']);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
                }
            }
        }

        $keys_comprador['inm_comprador_nss'] = array('x' => 20, 'y' => 59);
        $keys_comprador['inm_comprador_apellido_paterno'] = array('x' => 20, 'y' => 43);
        $keys_comprador['inm_comprador_apellido_materno'] = array('x' => 120, 'y' => 43);
        $keys_comprador['inm_comprador_nombre'] = array('x' => 20, 'y' => 51);
        $keys_comprador['inm_comprador_curp'] = array('x' => 120, 'y' => 59);
        $keys_comprador['inm_comprador_lada_com'] = array('x' => 20, 'y' => 91);
        $keys_comprador['inm_comprador_numero_com'] = array('x' => 30, 'y' => 91);
        foreach ($keys_comprador as $key => $valor) {
            $pdf[] = $this->write(valor: $data->inm_comprador[$key], x: $valor['x'], y: $valor['y']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
            }
        }

        $pdf_exe = $this->write(valor: $data->com_cliente['com_cliente_rfc'], x: 120, y: 51);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir rfc', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->com_cliente['com_cliente_calle'], x: 20, y: 67);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir calle', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->com_cliente['com_cliente_numero_exterior'], x: 120, y: 67);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->com_cliente['com_cliente_numero_interior'], x: 160, y: 67);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_colonia_descripcion'], x: 120, y: 75);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_municipio_descripcion'], x: 20, y: 83);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_estado_descripcion'], x: 120, y: 83);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_cp_descripcion'], x: 20, y: 75);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        /*$keys_comprador['org_empresa_razon_social']= array('x'=>17,'y'=>131);
        $keys_comprador['org_empresa_rfc']= array('x'=>23,'y'=>138);
        $write = $this->write_data(keys: $keys_comprador,row:  $data->inm_comprador);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }*/

        $keys_ubicacion['inm_ubicacion_razon_social'] = array('x' => 20, 'y' => 107);
        $keys_ubicacion['inm_ubicacion_rfc'] = array('x' => 20, 'y' => 115);
        $keys_ubicacion['inm_ubicacion_curp'] = array('x' => 120, 'y' => 115);

        $keys_ubicacion['inm_ubicacion_calle_domicilio'] = array('x' => 20, 'y' => 123);
        $keys_ubicacion['inm_ubicacion_numero_exterior_domicilio'] = array('x' => 120, 'y' => 123);
        $keys_ubicacion['inm_ubicacion_numero_interior_domicilio'] = array('x' => 160, 'y' => 123);
        $keys_ubicacion['dp_cp_domicilio_descripcion'] = array('x' => 20, 'y' => 131);
        $keys_ubicacion['dp_colonia_domicilio_descripcion'] = array('x' => 120, 'y' => 131);
        $keys_ubicacion['dp_estado_domicilio_descripcion'] = array('x' => 120, 'y' => 139);
        $keys_ubicacion['dp_municipio_domicilio_descripcion'] = array('x' => 20, 'y' => 139);

        /*$keys_ubicacion['inm_ubicacion_correo_com'] = array('x' => 20, 'y' => 154);
        $keys_ubicacion['inm_ubicacion_lada_com'] = array('x' => 120, 'y' => 174);
        $keys_ubicacion['inm_ubicacion_numero_com'] = array('x' => 130, 'y' => 174);*/

        $keys_ubicacion['inm_ubicacion_calle'] = array('x' => 20, 'y' => 174);
        $keys_ubicacion['inm_ubicacion_numero_exterior'] = array('x' => 20, 'y' => 182);
        $keys_ubicacion['inm_ubicacion_numero_interior'] = array('x' => 120, 'y' => 182);
        $keys_ubicacion['dp_colonia_descripcion'] = array('x' => 20, 'y' => 198);
        $keys_ubicacion['dp_estado_descripcion'] = array('x' => 120, 'y' => 206);
        $keys_ubicacion['dp_municipio_descripcion'] = array('x' => 20, 'y' => 206);
        $keys_ubicacion['dp_cp_descripcion'] = array('x' => 120, 'y' => 198);

        $keys_ubicacion['inm_ubicacion_entre_calle_1'] = array('x' => 20, 'y' => 190);
        $keys_ubicacion['inm_ubicacion_entre_calle_2'] = array('x' => 120, 'y' => 190);
        $keys_ubicacion['inm_ubicacion_lote'] = array('x' => 120, 'y' => 214);
        $keys_ubicacion['inm_ubicacion_nivel'] = array('x' => 142, 'y' => 214);
        $keys_ubicacion['inm_ubicacion_entrada'] = array('x' => 164, 'y' => 214);
        $keys_ubicacion['inm_ubicacion_manzana'] = array('x' => 20, 'y' => 222);
        $keys_ubicacion['inm_ubicacion_supermanzana'] = array('x' => 47, 'y' => 222);
        $keys_ubicacion['inm_ubicacion_edificio'] = array('x' => 80, 'y' => 222);
        $keys_ubicacion['inm_ubicacion_condominio'] = array('x' => 20, 'y' => 230);

        $keys_ubicacion['inm_ubicacion_numero_notaria'] = array('x' => 175, 'y' => 225);
        $keys_ubicacion['inm_ubicacion_nombre_notario'] = array('x' => 120, 'y' => 230);
        $keys_ubicacion['inm_ubicacion_plaza_notaria'] = array('x' => 20, 'y' => 237);
        $keys_ubicacion['inm_ubicacion_numero_escritura'] = array('x' => 120, 'y' => 237);
        $keys_ubicacion['inm_ubicacion_libro'] = array('x' => 142, 'y' => 237);
        $keys_ubicacion['inm_ubicacion_volumen'] = array('x' => 164, 'y' => 237);

        $write = $this->write_data(keys: $keys_ubicacion, row: $data->imp_rel_ubi_comp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        if (isset($data->imp_rel_ubi_comp['inm_tipo_vivienda_id']) &&
            trim($data->imp_rel_ubi_comp['inm_tipo_vivienda_id']) !== '') {
            $pdf_exe = $this->write(valor: 'X', x: $data->imp_rel_ubi_comp['inm_tipo_vivienda_x'],
                y: $data->imp_rel_ubi_comp['inm_tipo_vivienda_y']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
            }
        }

        $write = $this->write(valor: ((int)date('d')), x: 105, y: 258);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $mes_letra = $modelo->mes['espaniol'][date('m')]['nombre'];

        $write = $this->write(valor: $mes_letra, x: 128, y: 258);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->Rect(182, 254.8, 14, 5, 'F');

        $write = $this->write(valor: date('Y'), x: 182, y: 258);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        return $pdf;
    }

    private function apartado_avaluo_1_osvaldo(stdClass $data, modelo $modelo): Fpdi|array
    {
        $valida = (new valida())->valida_existencia_keys(keys: array('inm_comprador'), registro: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        if (!is_array($data->inm_comprador)) {
            return $this->error->error(mensaje: 'Error $data->inm_comprador no es un array', data: $data);
        }

        $this->pdf->SetFont('Arial', 'B', 10);

        $pdf = array();

        $keys_cliente = array();
        $keys_cliente[1] = array('x' => 75.4, 'y' => 38);
        $keys_cliente[3] = array('x' => 103.7, 'y' => 38);
        $keys_cliente[4] = array('x' => 152.8, 'y' => 38);
        $keys_cliente[5] = array('x' => 192.4, 'y' => 38);
        $keys_cliente[6] = array('x' => 75.5, 'y' => 38);
        $keys_cliente[7] = array('x' => 152.8, 'y' => 38);

        foreach ($keys_cliente as $key => $valor) {
            if ((int)$key === (int)$data->inm_comprador['inm_destino_credito_id']) {
                $pdf[] = $this->write(valor: 'X', x: $valor['x'], y: $valor['y']);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
                }
            }
        }

        $keys_comprador['inm_comprador_nss'] = array('x' => 90, 'y' => 52);
        $keys_comprador['inm_comprador_apellido_paterno'] = array('x' => 17, 'y' => 58);
        $keys_comprador['inm_comprador_apellido_materno'] = array('x' => 17, 'y' => 64);
        $keys_comprador['inm_comprador_nombre'] = array('x' => 17, 'y' => 69.5);
        $keys_comprador['inm_comprador_lada_com'] = array('x' => 125, 'y' => 90);
        $keys_comprador['inm_comprador_numero_com'] = array('x' => 138, 'y' => 90);
        foreach ($keys_comprador as $key => $valor) {
            $pdf[] = $this->write(valor: $data->inm_comprador[$key], x: $valor['x'], y: $valor['y']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
            }
        }

        $domicilio = $this->domicilio(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener domicilio', data: $domicilio);
        }

        $pdf_exe = $this->write(valor: $domicilio, x: 17, y: 78.5);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_colonia_descripcion'], x: 17, y: 84);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_municipio_descripcion'], x: 110, y: 84);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_estado_descripcion'], x: 17, y: 90);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_cp_descripcion'], x: 83, y: 90);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        /*$keys_comprador['org_empresa_razon_social']= array('x'=>17,'y'=>131);
        $keys_comprador['org_empresa_rfc']= array('x'=>23,'y'=>138);
        $write = $this->write_data(keys: $keys_comprador,row:  $data->inm_comprador);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }*/

        $keys_ubicacion['inm_ubicacion_apellido_paterno'] = array('x' => 17, 'y' => 105);
        $keys_ubicacion['inm_ubicacion_apellido_materno'] = array('x' => 17, 'y' => 111);
        $keys_ubicacion['inm_ubicacion_nombre'] = array('x' => 17, 'y' => 117);
        $keys_ubicacion['inm_ubicacion_rfc'] = array('x' => 23, 'y' => 123);
        $keys_ubicacion['inm_ubicacion_lada_com'] = array('x' => 125, 'y' => 133);
        $keys_ubicacion['inm_ubicacion_numero_com'] = array('x' => 138, 'y' => 133);

        $keys_ubicacion['dp_cp_domicilio_descripcion'] = array('x' => 173, 'y' => 125.5);
        $keys_ubicacion['dp_colonia_domicilio_descripcion'] = array('x' => 107, 'y' => 113.8);
        $keys_ubicacion['dp_estado_domicilio_descripcion'] = array('x' => 107, 'y' => 125.5);
        $keys_ubicacion['dp_municipio_domicilio_descripcion'] = array('x' => 107, 'y' => 119.5);

        $keys_ubicacion['inm_ubicacion_calle'] = array('x' => 17, 'y' => 158);
        $keys_ubicacion['inm_ubicacion_numero_exterior'] = array('x' => 17, 'y' => 164);
        $keys_ubicacion['inm_ubicacion_numero_interior'] = array('x' => 32.5, 'y' => 164);
        $keys_ubicacion['inm_ubicacion_lote'] = array('x' => 46, 'y' => 164);
        $keys_ubicacion['inm_ubicacion_manzana'] = array('x' => 63, 'y' => 164);
        $keys_ubicacion['dp_colonia_descripcion'] = array('x' => 82, 'y' => 164);
        $keys_ubicacion['dp_estado_descripcion'] = array('x' => 101, 'y' => 170);
        $keys_ubicacion['dp_municipio_descripcion'] = array('x' => 17, 'y' => 170);
        $keys_ubicacion['dp_cp_descripcion'] = array('x' => 174, 'y' => 170);

        $write = $this->write_data(keys: $keys_ubicacion, row: $data->imp_rel_ubi_comp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $domicilio_ubicacion = $this->domicilio_ubicacion(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener domicilio_ubicacion', data: $domicilio_ubicacion);
        }

        $write = $this->write(valor: $domicilio_ubicacion, x: 107, y: 108);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $ciudad = $this->ciudad(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener ciudad', data: $ciudad);
        }

        $write = $this->write(valor: $ciudad, x: 36, y: 229);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $write = $this->write(valor: ((int)date('d')), x: 115, y: 229);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $mes_letra = $modelo->mes['espaniol'][date('m')]['nombre'];

        $write = $this->write(valor: $mes_letra, x: 128, y: 229);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $year = $modelo->year['espaniol'][date('Y')]['abreviado'];

        $write = $this->write(valor: $year, x: 178.5, y: 229);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        return $pdf;
    }

    private function apartado_avaluo_1_damian(stdClass $data, modelo $modelo): Fpdi|array
    {
        $valida = (new valida())->valida_existencia_keys(keys: array('inm_comprador'), registro: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        if (!is_array($data->inm_comprador)) {
            return $this->error->error(mensaje: 'Error $data->inm_comprador no es un array', data: $data);
        }

        $this->pdf->SetFont('Arial', 'B', 10);

        $pdf = array();

        $keys_cliente = array();
        $keys_cliente[1] = array('x' => 78, 'y' => 32);
        $keys_cliente[2] = array('x' => 130.3, 'y' => 45);
        $keys_cliente[3] = array('x' => 107.3, 'y' => 32);
        $keys_cliente[4] = array('x' => 158.3, 'y' => 32);
        $keys_cliente[5] = array('x' => 107.7, 'y' => 39);
        $keys_cliente[6] = array('x' => 78, 'y' => 32);
        $keys_cliente[7] = array('x' => 165.6, 'y' => 39);

        foreach ($keys_cliente as $key => $valor) {
            if ((int)$key === (int)$data->inm_comprador['inm_destino_credito_id']) {
                $pdf[] = $this->write(valor: 'X', x: $valor['x'], y: $valor['y']);
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
                }
            }
        }

        $keys_comprador['inm_comprador_nss'] = array('x' => 90, 'y' => 57.5);
        $keys_comprador['inm_comprador_apellido_paterno'] = array('x' => 19, 'y' => 63);
        $keys_comprador['inm_comprador_apellido_materno'] = array('x' => 19, 'y' => 69);
        $keys_comprador['inm_comprador_nombre'] = array('x' => 19, 'y' => 74.5);
        $keys_comprador['inm_comprador_numero_com'] = array('x' => 130, 'y' => 95.5);
        foreach ($keys_comprador as $key => $valor) {
            $pdf[] = $this->write(valor: $data->inm_comprador[$key], x: $valor['x'], y: $valor['y']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
            }
        }

        $domicilio = $this->domicilio(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener domicilio', data: $domicilio);
        }

        $pdf_exe = $this->write(valor: $domicilio, x: 19, y: 83.5);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_colonia_descripcion'], x: 19, y: 89);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_municipio_descripcion'], x: 110, y: 89);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_estado_descripcion'], x: 19, y: 95);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        $pdf_exe = $this->write(valor: $data->inm_comprador['dp_cp_descripcion'], x: 85, y: 95);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }

        /*$keys_comprador['org_empresa_razon_social']= array('x'=>17,'y'=>131);
        $keys_comprador['org_empresa_rfc']= array('x'=>23,'y'=>138);
        $write = $this->write_data(keys: $keys_comprador,row:  $data->inm_comprador);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }*/

        $keys_ubicacion['inm_ubicacion_apellido_paterno'] = array('x' => 19, 'y' => 110);
        $keys_ubicacion['inm_ubicacion_apellido_materno'] = array('x' => 19, 'y' => 116);
        $keys_ubicacion['inm_ubicacion_nombre'] = array('x' => 19, 'y' => 122);
        $keys_ubicacion['inm_ubicacion_rfc'] = array('x' => 25, 'y' => 128);
        $keys_ubicacion['inm_ubicacion_numero_com'] = array('x' => 134, 'y' => 138);

        $keys_ubicacion['dp_cp_domicilio_descripcion'] = array('x' => 175, 'y' => 130.5);
        $keys_ubicacion['dp_colonia_domicilio_descripcion'] = array('x' => 109, 'y' => 118.8);
        $keys_ubicacion['dp_estado_domicilio_descripcion'] = array('x' => 109, 'y' => 130.5);
        $keys_ubicacion['dp_municipio_domicilio_descripcion'] = array('x' => 109, 'y' => 124.5);

        $keys_ubicacion['inm_ubicacion_calle'] = array('x' => 19, 'y' => 163.5);
        $keys_ubicacion['inm_ubicacion_numero_exterior'] = array('x' => 19, 'y' => 169);
        $keys_ubicacion['inm_ubicacion_numero_interior'] = array('x' => 34.5, 'y' => 169);
        $keys_ubicacion['inm_ubicacion_lote'] = array('x' => 49, 'y' => 169);
        $keys_ubicacion['inm_ubicacion_manzana'] = array('x' => 64, 'y' => 169);
        $keys_ubicacion['dp_colonia_descripcion'] = array('x' => 85, 'y' => 169);
        $keys_ubicacion['dp_estado_descripcion'] = array('x' => 103, 'y' => 175);
        $keys_ubicacion['dp_municipio_descripcion'] = array('x' => 19, 'y' => 175);
        $keys_ubicacion['dp_cp_descripcion'] = array('x' => 176, 'y' => 175);

        $write = $this->write_data(keys: $keys_ubicacion, row: $data->imp_rel_ubi_comp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $domicilio_ubicacion = $this->domicilio_ubicacion(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener domicilio_ubicacion', data: $domicilio_ubicacion);
        }

        $write = $this->write(valor: $domicilio_ubicacion, x: 109, y: 113);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $ciudad = $this->ciudad(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener ciudad', data: $ciudad);
        }

        $write = $this->write(valor: $ciudad, x: 36, y: 244.5);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $write = $this->write(valor: ((int)date('d')), x: 115, y: 244.5);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $mes_letra = $modelo->mes['espaniol'][date('m')]['nombre'];

        $write = $this->write(valor: $mes_letra, x: 128, y: 244.5);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $year = $modelo->year['espaniol'][date('Y')]['abreviado'];

        $write = $this->write(valor: $year, x: 178.5, y: 244.5);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        return $pdf;
    }

    private function apartado_gasto_1(stdClass $data, modelo $modelo): Fpdi|array
    {
        $this->pdf->SetFont('Arial', '', 10);

        $tipo_operacion = '';
        if ($_GET['seccion_retorno'] === 'inm_ubicacion') {
            $tipo_operacion = 'ADQUISICION';
        } else {
            $tipo_operacion = 'COMPRAVENTA';
        }

        if ($modelo->tabla === 'inm_cheque') {

            $this->pdf->SetXY(75.5, 46.5);
            $this->pdf->MultiCell(7, 5.5, "X", 0, 'C',);

            $texto = '$' . number_format($data->inm_cheque['inm_cheque_monto'], 2,
                    '.', ',');

            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetXY(44.5, 52.5);
            $this->pdf->MultiCell(38, 5, date("d-m-Y"), 0, 'C');

            $this->pdf->SetXY(44.5, 58);
            $this->pdf->MultiCell(160.5, 5,
                mb_convert_encoding((string)$data->inm_cheque['inm_cheque_nombre_beneficiario'], 'ISO-8859-1', 'UTF-8'),
                0, 'C');

            $this->pdf->SetXY(44.5, 63.5);
            $this->pdf->MultiCell(38, 5, $texto, 0, 'C');

            $this->pdf->SetXY(44.5, 70);
            $this->pdf->MultiCell(160.5, 5,
                mb_convert_encoding((string)$data->inm_cheque['inm_tipo_cheque_descripcion'] . " " . $tipo_operacion . " - " .
                    (string)$data->inm_cheque['inm_ubicacion_ubicacion'], 'ISO-8859-1', 'UTF-8'),
                0, 'C');

            $this->pdf->SetXY(164.7, 79);
            $this->pdf->MultiCell(40, 5, mb_convert_encoding("TITULACIÓN", 'ISO-8859-1', 'UTF-8'), 0, 'C');

            $this->pdf->SetFont('Arial', '', 7);

            $this->pdf->SetXY(11, 93.8);
            $y = $this->pdf->GetY();

            $this->pdf->SetFillColor(255, 255, 255);
            $this->pdf->MultiCell(71.43, 4.75,
                mb_convert_encoding((string)$data->inm_cheque['inm_ubicacion_ubicacion'], 'ISO-8859-1', 'UTF-8'),
                1, 'J', true);

            $altura_total = $this->pdf->GetY() - $y;

            $this->pdf->SetXY(82.5, 93.8);
            $this->pdf->MultiCell(20.8, $altura_total, "1", 1, 'C', true);

            $this->pdf->SetXY(103.35, 93.8);
            $this->pdf->MultiCell(37.7, $altura_total, $texto, 1, 'C', true);

            $this->pdf->SetXY(141.12, 93.8);
            $this->pdf->MultiCell(63.5, $altura_total,
                mb_convert_encoding((string)$data->inm_cheque['bn_cuenta_descripcion'], 'ISO-8859-1', 'UTF-8'),
                1, 'C', true);

            $this->pdf->SetXY(103.5, 127.1);
            $this->pdf->MultiCell(37.5, 4.5, $texto, 0, 'C');
        } else if ($modelo->tabla === 'inm_transferencia') {
            $this->pdf->SetXY(134, 46.5);
            $this->pdf->MultiCell(7, 5.5, "X", 0, 'C');

            $texto = '$' . number_format($data->inm_transferencia['inm_transferencia_monto'], 2,
                    '.', ',');

            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetXY(44.5, 52.5);
            $this->pdf->MultiCell(38, 5, date("d-m-Y"), 0, 'C');

            $this->pdf->SetXY(44.5, 58);
            $this->pdf->MultiCell(160.5, 5,
                mb_convert_encoding($tipo_operacion . " " . $data->inm_transferencia['inm_transferencia_nombre_beneficiario']
                    . " - " . $data->inm_transferencia['inm_ubicacion_ubicacion'], 'ISO-8859-1', 'UTF-8'),
                0, 'C');

            $this->pdf->SetXY(44.5, 63.5);
            $this->pdf->MultiCell(38, 5, $texto, 0, 'C');

            $this->pdf->SetXY(44.5, 70);
            $this->pdf->MultiCell(160.5, 5,
                mb_convert_encoding((string)$data->inm_transferencia['inm_transferencia_transferencia'], 'ISO-8859-1', 'UTF-8'),
                0, 'C');

            $this->pdf->SetXY(164.7, 79);
            $this->pdf->MultiCell(40, 5, mb_convert_encoding("TITULACIÓN", 'ISO-8859-1', 'UTF-8'), 0, 'C');

            $this->pdf->SetFont('Arial', '', 7);

            $this->pdf->SetXY(11, 93.8);
            $y = $this->pdf->GetY();

            $this->pdf->SetFillColor(255, 255, 255);
            $this->pdf->MultiCell(71.43, 4.75,
                mb_convert_encoding((string)$data->inm_transferencia['inm_ubicacion_ubicacion'], 'ISO-8859-1', 'UTF-8'),
                1, 'J', true);

            $altura_total = $this->pdf->GetY() - $y;

            $this->pdf->SetXY(82.5, 93.8);
            $this->pdf->MultiCell(20.8, $altura_total, "1", 1, 'C', true);

            $this->pdf->SetXY(103.35, 93.8);
            $this->pdf->MultiCell(37.7, $altura_total, $texto, 1, 'C', true);

            $this->pdf->SetXY(141.12, 93.8);
            $this->pdf->MultiCell(63.5, $altura_total,
                mb_convert_encoding((string)$data->inm_transferencia['bn_cuenta_descripcion'], 'ISO-8859-1', 'UTF-8'),
                1, 'C', true);

            $this->pdf->SetXY(103.5, 127.1);
            $this->pdf->MultiCell(37.5, 4.5, $texto, 0, 'C');
        } else {
            $this->pdf->SetXY(195.5, 46.5);
            $this->pdf->MultiCell(9.5, 5.5, "X", 0, 'C');

            $texto = '$' . number_format($data->inm_efectivo['inm_efectivo_monto'], 2,
                    '.', ',');

            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetXY(44.5, 52.5);
            $this->pdf->MultiCell(38, 5, date("d-m-Y"), 0, 'C');

            $this->pdf->SetXY(44.5, 58);
            $this->pdf->MultiCell(160.5, 5,
                mb_convert_encoding((string)$data->inm_efectivo['inm_efectivo_nombre_beneficiario'] . " - " .
                    $data->inm_efectivo['inm_ubicacion_ubicacion'], 'ISO-8859-1', 'UTF-8'),
                0, 'C');

            $this->pdf->SetXY(44.5, 63.5);
            $this->pdf->MultiCell(38, 5, $texto, 0, 'C');

            $this->pdf->SetXY(44.5, 70);
            $this->pdf->MultiCell(160.5, 5,
                mb_convert_encoding("", 'ISO-8859-1', 'UTF-8'),
                0, 'C');

            $this->pdf->SetXY(164.7, 79);
            $this->pdf->MultiCell(40, 5, mb_convert_encoding("TITULACIÓN", 'ISO-8859-1', 'UTF-8'), 0, 'C');

            $this->pdf->SetFont('Arial', '', 7);

            $this->pdf->SetXY(11, 93.8);
            $y = $this->pdf->GetY();

            $this->pdf->SetFillColor(255, 255, 255);
            $this->pdf->MultiCell(71.43, 4.75,
                mb_convert_encoding((string)$data->inm_efectivo['inm_ubicacion_ubicacion'], 'ISO-8859-1', 'UTF-8'),
                1, 'J', true);

            $altura_total = $this->pdf->GetY() - $y;

            $this->pdf->SetXY(82.5, 93.8);
            $this->pdf->MultiCell(20.8, $altura_total, "1", 1, 'C', true);

            $this->pdf->SetXY(103.35, 93.8);
            $this->pdf->MultiCell(37.7, $altura_total, $texto, 1, 'C', true);

            $this->pdf->SetXY(141.12, 93.8);
            $this->pdf->MultiCell(63.5, $altura_total,
                mb_convert_encoding("", 'ISO-8859-1', 'UTF-8'),
                1, 'C', true);

            $this->pdf->SetXY(103.5, 127.1);
            $this->pdf->MultiCell(37.5, 4.5, $texto, 0, 'C');
        }

        $this->pdf->SetFont('Arial', '', 8.5);

        $this->pdf->SetXY(34.5, 154);
        $this->pdf->MultiCell(50.5, 5, "ERIKA CRUZ", 0, 'C');

        $this->pdf->SetXY(132.5, 154);
        $this->pdf->MultiCell(50.5, 5, "MAURICIO HERNANDEZ", 0, 'C');

        return $this->pdf;
    }

    /**
     * Escribe los datos del apartado 2 de la solicitud de infonavit
     * @param stdClass $data datos de cliente
     * @return array
     * @version 1.125.1
     */
    private function apartado_2(stdClass $data): array
    {
        $valida = (new valida())->valida_existencia_keys(keys: array('inm_comprador'), registro: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        if (!is_array($data->inm_comprador)) {
            return $this->error->error(mensaje: 'Error $data->inm_comprador no es un array', data: $data);
        }

        $write = array();
        $row_condiciones['inm_comprador_descuento_pension_alimenticia_dh'] =
            array('x' => 77, 'y' => 117, 'value_compare' => 0.0);
        $row_condiciones['inm_comprador_descuento_pension_alimenticia_fc'] =
            array('x' => 115, 'y' => 117, 'value_compare' => 0.0);
        $row_condiciones['inm_comprador_monto_credito_solicitado_dh'] =
            array('x' => 79, 'y' => 131, 'value_compare' => 0.0);
        $row_condiciones['inm_comprador_monto_ahorro_voluntario'] =
            array('x' => 51.5, 'y' => 143, 'value_compare' => 0.0);

        foreach ($row_condiciones as $key => $row) {
            $pdf = $this->write_condicion(key: $key, row: $data->inm_comprador, value_compare: $row['value_compare'],
                x: $row['x'], y: $row['y']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
            }
            $write[] = $pdf;
        }
        return $write;
    }

    /**
     * Integra los elementos del apartado de de la solicitud
     * @param stdClass $data Datos de cliente
     * @return array|Fpdi
     * @version 1.139.1
     */
    private function apartado_3(stdClass $data): Fpdi|array
    {
        if (!isset($data->imp_rel_ubi_comp)) {
            return $this->error->error(mensaje: 'Error no existe $data->imp_rel_ubi_comp', data: $data);
        }
        if (!is_array($data->imp_rel_ubi_comp)) {
            return $this->error->error(mensaje: 'Error $data->imp_rel_ubi_comp no es un array', data: $data);
        }

        if (!isset($data->inm_comprador)) {
            return $this->error->error(mensaje: 'Error no existe $data->inm_comprador', data: $data);
        }
        if (!is_array($data->inm_comprador)) {
            return $this->error->error(mensaje: 'Error $data->inm_comprador no es un array', data: $data);
        }

        if (!isset($data->imp_rel_ubi_comp['inm_rel_ubi_comp_precio_operacion'])) {
            return $this->error->error(
                mensaje: 'Error no existe $data->imp_rel_ubi_comp[inm_rel_ubi_comp_precio_operacion]', data: $data);
        }
        if (trim($data->imp_rel_ubi_comp['inm_rel_ubi_comp_precio_operacion']) === '') {
            return $this->error->error(
                mensaje: 'Error $data->imp_rel_ubi_comp[inm_rel_ubi_comp_precio_operacion] esta vacio', data: $data);
        }

        $keys_ubicacion = $this->keys_ubicacion();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener keys_ubicacion', data: $keys_ubicacion);
        }

        $write = $this->write_data(keys: $keys_ubicacion, row: $data->imp_rel_ubi_comp);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $condiciones = array();
        $condiciones['SI'] = 84;

        $coord = $this->x_y_compare(condiciones: $condiciones, key: 'inm_comprador_con_discapacidad',
            row: $data->inm_comprador, x_init: 94.5, y_init: 190);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener coordenadas', data: $coord);
        }

        $pdf = $this->write(valor: 'X', x: $coord->x, y: $coord->y);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        $condiciones = array();
        $condiciones[3] = 67;
        $condiciones[4] = 114;
        $condiciones[5] = 163;

        $coord = $this->x_y_compare(condiciones: $condiciones, key: 'inm_destino_credito_id',
            row: $data->inm_comprador, x_init: 21.5, y_init: 224.5);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener coordenadas', data: $coord);
        }

        $pdf = $this->write(valor: $data->inm_avaluos['inm_avaluo_valor_avaluo'],
            x: $coord->x, y: $coord->y);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        return $pdf;
    }

    private function apartado_4(stdClass $data)
    {
        $keys_comprador = $this->keys_comprador();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener keys_ubicacion', data: $keys_comprador);
        }

        $write = $this->write_data(keys: $keys_comprador, row: $data->inm_comprador);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function apartado_5(stdClass $data)
    {
        $write = $this->write_comprador_hoja_3(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $pdf_exe = $this->write(valor: $data->com_cliente['com_cliente_rfc'], x: 132, y: 30);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        $pdf_exe = $this->write_domicilio(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }


        $write = $this->write_cliente_hoja_3(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }


        $write = $this->write_genero(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $write = $this->write_estado_civil(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        return $write;
    }


    private function ciudad(stdClass $data): string
    {
        $ciudad = strtoupper('ZAPOPAN');
        $ciudad .= ", " . strtoupper('JALISCO');
        return $ciudad;
    }

    private function domicilio(stdClass $data): string
    {
        $domicilio = $data->com_cliente['com_cliente_calle'] . ' ' . $data->com_cliente['com_cliente_numero_exterior'];
        $domicilio .= $data->com_cliente['com_cliente_numero_interior'];

        return $domicilio;
    }

    private function domicilio_ubicacion(stdClass $data): string
    {
        $domicilio = $data->imp_rel_ubi_comp['inm_ubicacion_calle_domicilio'] . ' ';
        $domicilio .= $data->imp_rel_ubi_comp['inm_ubicacion_numero_exterior_domicilio'] . ' ';
        $domicilio .= $data->imp_rel_ubi_comp['inm_ubicacion_numero_interior_domicilio'];

        return $domicilio;
    }

    /**
     * Escribe los datos de infonavit en el pdf
     * @param stdClass $data datos de cliente
     * @return array
     * @version 1.121.1
     *
     */
    private function entidades_infonavit(stdClass $data): array
    {
        $entidades_pdf = array('inm_producto_infonavit', 'inm_tipo_credito', 'inm_attr_tipo_credito',
            'inm_destino_credito', 'inm_plazo_credito_sc', 'inm_tipo_discapacidad', 'inm_persona_discapacidad');
        $writes = array();
        foreach ($entidades_pdf as $name_entidad) {
            $valida = (new valida())->valida_existencia_keys(keys: array('inm_comprador'), registro: $data);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
            }
            if (!is_array($data->inm_comprador)) {
                return $this->error->error(mensaje: 'Error $data->inm_comprador no es un array', data: $data);
            }

            $pdf = $this->write_x(name_entidad: $name_entidad, row: $data->inm_comprador);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
            }
            $writes[] = $pdf;
        }
        return $writes;
    }

    /**
     * Integra una x si es segundo credito en SI
     * @param stdClass $data Datos de cliente
     * @return array|Fpdi
     * @version 1.122.1
     */
    private function es_segundo_credito(stdClass $data): Fpdi|array
    {
        $valida = (new valida())->valida_existencia_keys(keys: array('inm_comprador'), registro: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        if (!is_array($data->inm_comprador)) {
            return $this->error->error(mensaje: 'Error $data->inm_comprador no es un array', data: $data);
        }
        $keys = array('inm_comprador_es_segundo_credito');
        $valida = (new valida())->valida_existencia_keys(keys: $keys, registro: $data->inm_comprador);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $x = 47.3;
        $y = 91.3;
        if ($data->inm_comprador['inm_comprador_es_segundo_credito'] === 'SI') {
            $x = 32.1;
        }

        $pdf = $this->write(valor: 'X', x: $x, y: $y);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }
        return $pdf;
    }


    private function genera_hoja_1(stdClass $data, string $path_base)
    {
        $pdf = $this->add_template(file_plantilla: 'templates/solicitud_infonavit.pdf', page: 1,
            path_base: $path_base, plantilla_cargada: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf);
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf_exe = $this->hoja_1(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        return $pdf;
    }

    private function genera_hoja_avaluo_1_fajardo(stdClass $data, modelo $modelo, string $path_base)
    {
        $pdf = $this->add_template(file_plantilla: 'templates/solicitud_avaluo_fajardo.pdf', page: 1,
            path_base: $path_base, plantilla_cargada: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf);
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf_exe = $this->hoja_avaluo_1_fajardo(data: $data, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        return $pdf;
    }

    private function genera_hoja_avaluo_1_osvaldo(stdClass $data, modelo $modelo, string $path_base)
    {
        $pdf = $this->add_template(file_plantilla: 'templates/solicitud_avaluo_osvaldo.pdf', page: 1,
            path_base: $path_base, plantilla_cargada: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf);
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf_exe = $this->hoja_avaluo_1_osvaldo(data: $data, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        return $pdf;
    }

    private function genera_hoja_avaluo_1_damian(stdClass $data, modelo $modelo, string $path_base)
    {
        $pdf = $this->add_template(file_plantilla: 'templates/solicitud_avaluo_damian.pdf', page: 1,
            path_base: $path_base, plantilla_cargada: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf);
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf_exe = $this->hoja_avaluo_1_damian(data: $data, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        return $pdf;
    }

    private function genera_hoja_gasto_1(stdClass $data, modelo $modelo, string $path_base)
    {
        $pdf = $this->add_template(file_plantilla: 'templates/solicitud_gasto.pdf', page: 1,
            path_base: $path_base, plantilla_cargada: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf);
        }

        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetTextColor(0, 0, 0);

        $pdf_exe = $this->hoja_gasto_1(data: $data, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        return $pdf;
    }

    private function genera_hoja_2(stdClass $data, PDO $link, string $path_base)
    {
        $pdf = $this->add_template(file_plantilla: 'templates/solicitud_infonavit.pdf', page: 2,
            path_base: $path_base, plantilla_cargada: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf);
        }

        $write = $this->hoja_2(data: $data, link: $link);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function genera_hoja_3(stdClass $data, modelo $modelo, string $path_base)
    {
        $pdf = $this->add_template(file_plantilla: 'templates/solicitud_infonavit.pdf', page: 3,
            path_base: $path_base, plantilla_cargada: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf);
        }


        $write = $this->hoja_3(data: $data, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function get_key_referencias(int $indice)
    {
        $keys_referencias = (new _keys_selects())->keys_referencias();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener keys_referencias', data: $keys_referencias);
        }
        if ($indice === 1) {
            $keys_referencias = (new _keys_selects())->keys_referencias_2();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener keys_referencias', data: $keys_referencias);
            }
        }
        return $keys_referencias;
    }

    /**
     * Obtiene el valor de x
     * @param array $condiciones Condiciones para x
     * @param string $key_id Key para obtener valor a comparar
     * @param array $row Registro en proceso
     * @param float $x_init Posicion inicial
     * @return float|array
     * @version 1.129.1
     */
    private function get_x_var(array $condiciones, string $key_id, array $row, float $x_init): float|array
    {
        $key_id = trim($key_id);
        if ($key_id === '') {
            return $this->error->error(mensaje: 'Error key_id esta vacio', data: $key_id);
        }
        $keys = array($key_id);
        $valida = (new valida())->valida_existencia_keys(keys: $keys, registro: $row);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar $row', data: $valida);
        }
        $x = $x_init;

        $key_compare = $row[$key_id];

        if (isset($condiciones[$key_compare])) {
            $x = $condiciones[$key_compare];
        }

        return $x;

    }

    private function hoja_avaluo_1_fajardo(stdClass $data, modelo $modelo)
    {
        $pdf = $this->apartado_avaluo_1_fajardo(data: $data, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        return $pdf;
    }

    private function hoja_avaluo_1_osvaldo(stdClass $data, modelo $modelo)
    {
        $pdf = $this->apartado_avaluo_1_osvaldo(data: $data, modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        return $pdf;
    }

    private function hoja_avaluo_1_damian(stdClass $data, modelo $modelo){
        $pdf = $this->apartado_avaluo_1_damian(data: $data,modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        return $pdf;
    }

    private function hoja_gasto_1(stdClass $data, modelo $modelo){
        $pdf = $this->apartado_gasto_1(data: $data,modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        return $pdf;
    }

    private function hoja_1(stdClass $data){
        /**
         * 1. CRÉDITO SOLICITADO
         */


        $pdf = $this->apartado_1(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        /**
         * 2. DATOS PARA DETERMINAR EL MONTO DE CRÉDITO
         */

        $pdf->SetFont('Arial', 'B', 10);

        $pdf = $this->apartado_2(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        /**
         * 3. DATOS DE LA VIVIENDA/TERRENO DESTINO DEL CRÉDITO
         */


        $pdf = $this->apartado_3(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }

        /**
         * 4. DATOS DE LA EMPRESA O PATRÓN
         */

        $pdf = $this->apartado_4(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
        }
        return $pdf;
    }

    private function hoja_2(stdClass $data, PDO $link){
        /**
         * 5. DATOS DE IDENTIFICACIÓN DEL (DE LA) DERECHOHABIENTE / DATOS QUE SERÁN VALIDADOS
         */

        $write = $this->apartado_5(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        /**
         * 6. DATOS DE IDENTIFICACIÓN QUE SERÁN VALIDADOS (OBLIGATORIOS EN CRÉDITO CONYUGAL, FAMILIAR O CORRESIDENCIAL)
         */

        $write = $this->write_co_acreditados(data: $data,link:  $link);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }


        /**
         * 7. REFERENCIAS FAMILIARES DEL (DE LA) DERECHOHABIENTE / DATOS QUE SERÁN VALIDADOS
         */
        $write = $this->write_referencias(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        return $write;
    }


    private function hoja_3(stdClass $data, modelo $modelo){
        $write = $this->write_comprador_a_8(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $write = $this->write(valor: $data->inm_comprador['org_empresa_razon_social'], x:16,y: 62);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $write = $this->write_cuidad(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }


        $write = $this->write_fecha(modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function hojas(stdClass $data, modelo $modelo, string $path_base){
        $pdf_exe = $this->genera_hoja_1(data: $data,path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }


        $pdf_exe = $this->genera_hoja_2(data: $data, link: $modelo->link, path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }


        $pdf_exe = $this->genera_hoja_3(data: $data,modelo:  $modelo,path_base:  $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        return $pdf_exe;
    }

    private function hojas_avaluo_fajardo(stdClass $data, modelo $modelo, string $path_base){
        $pdf_exe = $this->genera_hoja_avaluo_1_fajardo(data: $data,modelo: $modelo,path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        return $pdf_exe;
    }

    private function hojas_avaluo_osvaldo(stdClass $data, modelo $modelo, string $path_base){
        $pdf_exe = $this->genera_hoja_avaluo_1_osvaldo(data: $data,modelo: $modelo,path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        $pdf_exe = $this->add_template(file_plantilla: 'templates/solicitud_avaluo_osvaldo.pdf',page:  2,
            path_base:  $path_base,plantilla_cargada:  false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf_exe);
        }

        return $pdf_exe;
    }

    private function hojas_avaluo_damian(stdClass $data, modelo $modelo, string $path_base){
        $pdf_exe = $this->genera_hoja_avaluo_1_damian(data: $data,modelo: $modelo,path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        $pdf_exe = $this->add_template(file_plantilla: 'templates/solicitud_avaluo_damian.pdf',page:  2,
            path_base:  $path_base,plantilla_cargada:  false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al agregar template', data: $pdf_exe);
        }

        return $pdf_exe;
    }

    private function hojas_gasto(stdClass $data, modelo $modelo, string $path_base){
        $pdf_exe = $this->genera_hoja_gasto_1(data: $data,modelo: $modelo,path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        return $pdf_exe;
    }


    private function keys_cliente(): array
    {

        $keys_cliente['dp_colonia_descripcion']= array('x'=>16,'y'=>61);
        $keys_cliente['dp_estado_descripcion']= array('x'=>105,'y'=>61);
        $keys_cliente['dp_municipio_descripcion']= array('x'=>16,'y'=>68);
        $keys_cliente['dp_cp_descripcion']= array('x'=>82,'y'=>68);
        return $keys_cliente;
    }

    /**
     * Integra los keys de un comprador para pdf
     * @return array
     * @version 1.141.1
     */
    private function keys_comprador(): array
    {
        $keys_comprador['inm_comprador_nombre_empresa_patron']= array('x'=>16,'y'=>249);
        $keys_comprador['inm_comprador_nrp_nep']= array('x'=>140,'y'=>249);
        $keys_comprador['inm_comprador_lada_nep']= array('x'=>57,'y'=>256);
        $keys_comprador['inm_comprador_numero_nep']= array('x'=>70,'y'=>256);
        $keys_comprador['inm_comprador_extension_nep']= array('x'=>116,'y'=>256);
        return $keys_comprador;
    }

    private function keys_comprador_hoja_2(): array
    {

        $keys_comprador['inm_comprador_nss']= array('x'=>16,'y'=>30);
        $keys_comprador['inm_comprador_curp']= array('x'=>67,'y'=>30);
        $keys_comprador['inm_comprador_apellido_paterno']= array('x'=>16,'y'=>37);
        $keys_comprador['inm_comprador_apellido_materno']= array('x'=>106,'y'=>37);
        $keys_comprador['inm_comprador_nombre']= array('x'=>16,'y'=>44);
        $keys_comprador['inm_comprador_lada_com']= array('x'=>27,'y'=>76);
        $keys_comprador['inm_comprador_numero_com']= array('x'=>40,'y'=>76);
        $keys_comprador['inm_comprador_cel_com']= array('x'=>88,'y'=>76);
        $keys_comprador['inm_comprador_correo_com']= array('x'=>37.5,'y'=>85.5);
        return $keys_comprador;
    }

    private function keys_comprador_hoja_3(): array
    {
        $keys_comprador = array();
        $keys_comprador['org_empresa_razon_social']= array('x'=>16,'y'=>37);
        $keys_comprador['org_empresa_rfc']= array('x'=>22,'y'=>57);
        $keys_comprador['bn_cuenta_clabe']= array('x'=>16,'y'=>85);
        return $keys_comprador;
    }

    /**
     * Integra los parametros de coordenadas de los datos de la ubicacion
     * @return array
     * @version 1.126.1
     */
    private function keys_ubicacion(): array
    {
        $keys_ubicacion['inm_ubicacion_calle']= array('x'=>15.5,'y'=>164);
        $keys_ubicacion['inm_ubicacion_numero_exterior']= array('x'=>15.5,'y'=>170);
        $keys_ubicacion['inm_ubicacion_numero_interior']= array('x'=>31,'y'=>170);
        $keys_ubicacion['inm_ubicacion_lote']= array('x'=>46,'y'=>170);
        $keys_ubicacion['inm_ubicacion_manzana']= array('x'=>61,'y'=>170);
        $keys_ubicacion['dp_colonia_descripcion']= array('x'=>81,'y'=>170);
        $keys_ubicacion['dp_estado_descripcion']= array('x'=>15.5,'y'=>176);
        $keys_ubicacion['dp_municipio_descripcion']= array('x'=>100,'y'=>176);
        $keys_ubicacion['dp_cp_descripcion']= array('x'=>173,'y'=>176);
        return $keys_ubicacion;
    }

    final public function solicitud_infonavit(int $inm_comprador_id, string $path_base, modelo $modelo){

        $data = (new inm_comprador(link: $modelo->link))->data_pdf(inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos', data: $data);
        }

        $pdf_exe = $this->hojas(data: $data, modelo: $modelo, path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }
        $this->pdf->Output('solicitud.pdf', 'I');
        return $this->pdf;
    }

    final public function solicitud_avaluo(int $inm_comprador_id, string $path_base, modelo $modelo){

        $data = (new inm_comprador(link: $modelo->link))->data_solicitud_avaluo_pdf(
            inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos', data: $data);
        }

        $inm_rel_cliente_valuador = (new inm_rel_cliente_valuador(link: $modelo->link))
            ->inm_rel_cliente_valuador(inm_comprador_id: $inm_comprador_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener inm_rel_cliente_valuador',
                data: $inm_rel_cliente_valuador);
        }

        $data->inm_rel_cliente_valuador = $inm_rel_cliente_valuador;

        $funcion = 'hojas_avaluo_'.$data->inm_rel_cliente_valuador['inm_valuador_alias'];

        $pdf_exe = $this->$funcion(data: $data, modelo: $modelo, path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }
        $this->pdf->Output('solicitud_avaluo.pdf', 'I');

        return $this->pdf;
    }

    final public function solicitud_gasto(int $registro_id, string $path_base, modelo $modelo){

        $data = $modelo->data_pdf(registro_id: $registro_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos', data: $data);
        }

        $pdf_exe = $this->hojas_gasto(data: $data, modelo: $modelo, path_base: $path_base);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }

        $this->pdf->Output('D',$data->nombre_archivo);
        return $this->pdf;
    }

    /**
     * Carga la plantilla de un pdf existente
     * @param string $file_plantilla Archivo pdf de plantilla base vacio
     * @param int $page Pagina de plantilla a cargar
     * @param string $path_base Ruta base de sistema
     * @param bool $plantilla_cargada Si plantilla esta cargada ya no importa el documento base solo integra la pagina
     * @return array|string
     * @version 1.116.1
     */
    private function tpl_idx(string $file_plantilla, int $page, string $path_base, bool $plantilla_cargada): array|string
    {

        $valida = $this->valida_datos_plantilla(file_plantilla: $file_plantilla,page:  $page,path_base:  $path_base);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos de plantilla', data: $valida);
        }

        $ruta = trim($path_base . $file_plantilla);
        if(!file_exists($ruta)){
            return $this->error->error(mensaje: 'Error no existe la plantilla', data: $ruta);
        }

        try {
            if(!$plantilla_cargada) {
                $this->pdf->setSourceFile($path_base . $file_plantilla);
            }
            $tpl_idx = $this->pdf->importPage($page);
        } catch (Throwable $e) {
            return $this->error->error(mensaje: 'Error al obtener plantilla', data: $e);
        }

        return $tpl_idx;
    }

    /**
     * Valida que los datos de una plantilla pdf sean los correctos
     * @param string $file_plantilla Archivo de plantilla pdf
     * @param int $page Numero de pagina de plantilla
     * @param string $path_base Ruta base de sistema
     * @return bool|array
     * @version 1.118.1
     */
    private function valida_datos_plantilla(string $file_plantilla, int $page, string $path_base): bool|array
    {
        $file_plantilla = trim($file_plantilla);
        if($file_plantilla === ''){
            return $this->error->error(mensaje: 'Error file_plantilla esta vacio', data: $file_plantilla);
        }
        $path_base = trim($path_base);
        if($path_base === ''){
            return $this->error->error(mensaje: 'Error path_base esta vacio', data: $path_base);
        }
        if($page < 1){
            return $this->error->error(mensaje: 'Error page debe ser mayor a 0', data: $page);
        }

        $ruta = trim($path_base . $file_plantilla);
        if(!file_exists($ruta)){
            return $this->error->error(mensaje: 'Error no existe la plantilla', data: $ruta);
        }
        return true;
    }


    /**
     * Obtiene las coordenadas x y basado en elementos de comparacion
     * @param array $condiciones Condiciones para obtener coordenada
     * @param string $key Key a verificar
     * @param array $row Registro de tipo cliente
     * @param float $x_init x inicial
     * @param float $y_init y inicial
     * @return array|stdClass
     * @version 1.137.1
     */
    private function x_y_compare(array $condiciones, string $key, array $row, float $x_init, float $y_init): array|stdClass
    {
        $key = trim($key);
        if($key === ''){
            return $this->error->error(mensaje: 'Error key esta vacio', data: $key);
        }
        $keys = array($key);
        $valida = (new valida())->valida_existencia_keys(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar $row', data: $valida);
        }
        $x = $this->get_x_var(condiciones: $condiciones,key_id:  $key,
            row:  $row, x_init: $x_init);

        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener x', data: $x);
        }
        $y = $y_init;

        $data = new stdClass();
        $data->x = $x;
        $data->y = $y;

        return $data;
    }


    /**
     * Escribe un valor en un pdf
     * @param string|null $valor Valor a escribir
     * @param float $x Posicion en x
     * @param float $y Posicion en y
     * @return Fpdi|array
     * @version 1.119.1
     */
    private function write(string|null $valor,float $x, float $y): Fpdi|array
    {
        if($x < 0.0){
            return $this->error->error(mensaje: 'Error x debe ser mayor a 0', data: $x);
        }
        if($y < 0.0){
            return $this->error->error(mensaje: 'Error y debe ser mayor a 0', data: $y);
        }
        if(is_null($valor)){
            $valor = '';
        }
        $valor = trim($valor);

        $valor = str_replace('á', 'A', $valor);
        $valor = str_replace('é', 'E', $valor);
        $valor = str_replace('í', 'I', $valor);
        $valor = str_replace('ó', 'O', $valor);
        $valor = str_replace('ú', 'U', $valor);
        $valor = str_replace('ñ', 'Ñ', $valor);

        $valor = mb_convert_encoding($valor, 'ISO-8859-1');

        $valor = strtoupper($valor);
        try {
            $this->pdf->SetXY($x, $y);
            $this->pdf->Write(0, $valor);
        }
        catch (Throwable $e){
            return $this->error->error(mensaje: 'Error al escribir', data: $e);
        }
        return $this->pdf;
    }

    private function write_cuidad(stdClass $data){
        $ciudad = $this->ciudad(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener ciudad', data: $ciudad);
        }

        $write = $this->write(valor: $ciudad, x:36,y: 240);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_cliente_hoja_3(stdClass $data){
        $keys_cliente = $this->keys_cliente();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener keys_cliente', data: $keys_cliente);
        }

        $write = $this->write_data(keys: $keys_cliente,row:  $data->com_cliente);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_co_acreditado(int $inm_co_acreditado_id, PDO $link){
        $inm_co_acreditado = (new inm_co_acreditado(link: $link))->registro(registro_id: $inm_co_acreditado_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener inm_co_acreditado',data:  $inm_co_acreditado);
        }


        $keys_co_acreditado = (new _keys_selects())->keys_co_acreditado();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar keys', data: $keys_co_acreditado);
        }


        $write = $this->write_data(keys: $keys_co_acreditado,row:  $inm_co_acreditado);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }

        $write = $this->write_co_acreditado_genero(inm_co_acreditado: $inm_co_acreditado);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_co_acreditados(stdClass $data, PDO $link){
        $writes = array();
        foreach ($data->inm_rel_co_acreditados as $imp_rel_co_acred){
            $write = $this->write_co_acreditado($imp_rel_co_acred['inm_co_acreditado_id'],link:  $link);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
            }
            $writes[] = $write;
        }
        return $writes;
    }

    private function write_co_acreditado_genero(array $inm_co_acreditado): Fpdi
    {
        $x = 144;
        $y = 130;

        if($inm_co_acreditado['inm_co_acreditado_genero'] === 'F'){

            $x = 150.5;
        }

        $this->pdf->SetXY($x, $y);
        $this->pdf->Write(0, 'X');
        return $this->pdf;
    }

    private function write_comprador(stdClass $data){
        $keys_comprador = $this->keys_comprador_hoja_3();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener keys_comprador', data: $keys_comprador);
        }

        $write = $this->write_data(keys: $keys_comprador,row:  $data->inm_comprador);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_comprador_a_8(stdClass $data){
        $pdf = $this->write_x(name_entidad: 'inm_tipo_inmobiliaria',row:  $data->inm_conf_empresa);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al escribir en pdf',data:  $pdf);
        }
        $write = $this->write_comprador(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_comprador_hoja_3(stdClass $data){
        $keys_comprador = $this->keys_comprador_hoja_2();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener keys_comprador', data: $keys_comprador);
        }

        $write = $this->write_data(keys: $keys_comprador,row:  $data->inm_comprador);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    /**
     * Escribe un dato basado en condiciones
     * @param string $key Key a buscar
     * @param array $row registro en proceso
     * @param mixed $value_compare Valor de comparacion
     * @param float $x coordenadas en x
     * @param float $y coordenadas en y
     * @return array|bool
     * @version 1.124.1
     */
    private function write_condicion(string $key, array $row, mixed $value_compare, float $x, float $y): bool|array
    {
        $key = trim($key);
        if($key === ''){
            return $this->error->error(mensaje: 'Error key esta vacio', data: $key);
        }
        $valida = (new valida())->valida_existencia_keys(keys: array($key),registro:  $row);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar $row', data: $valida);
        }
        if(!is_numeric($row[$key])){
            return $this->error->error(mensaje: 'Error $row[key] debe ser un numero', data: $row);
        }
        if($x < 0.0){
            return $this->error->error(mensaje: 'Error x debe ser mayor a 0', data: $x);
        }
        if($y < 0.0){
            return $this->error->error(mensaje: 'Error y debe ser mayor a 0', data: $y);
        }

        $write = false;
        if (round($row[$key], 2) > $value_compare) {
            $pdf = $this->write( valor: $row[$key], x: $x, y: $y);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
            }
            $write = true;
        }
        return $write;
    }

    /**
     * Escribe datos en el pdf
     * @param array $keys Keys para obtener valores
     * @param array $row Registro de cliente
     * @return array
     * @version 1.127.1
     *
     */
    private function write_data(array $keys, array $row): array
    {
        $writes = array();
        foreach ($keys as $key=>$coordenadas){

            $key = trim($key);
            if($key === ''){
                return $this->error->error(mensaje: 'Error key esta vacio', data: $key);
            }
            if(is_numeric($key)){
                return $this->error->error(mensaje: 'Error key es un numero', data: $key);
            }

            if(!is_array($coordenadas)){
                return $this->error->error(mensaje: 'Error coordenadas debe ser un array', data: $coordenadas);
            }

            $keys_coord = array('x','y');
            $valida = (new valida())->valida_double_mayores_0(keys: $keys_coord,registro:  $coordenadas);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al validar coordenadas', data: $valida);
            }

            if(!isset($row[$key]) || trim($row[$key]) === 'PREDETERMINADO' || trim($row[$key]) === 'PRED'){
                $row[$key] = '';
            }

            $pdf = $this->write(valor: $row[$key], x: $coordenadas['x'], y: $coordenadas['y']);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf);
            }
            $writes[] = $pdf;
        }
        return $writes;
    }

    private function write_dia(){
        $write = $this->write(valor: ((int)date('d')), x:119,y: 240);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_domicilio(stdClass $data){
        $domicilio = $this->domicilio(data: $data);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener domicilio', data: $domicilio);
        }

        $pdf_exe = $this->write(valor: $domicilio,x: 16,y: 54);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir domicilio', data: $pdf_exe);
        }
        return $pdf_exe;
    }

    private function write_estado_civil(stdClass $data): Fpdi
    {
        $this->pdf->SetXY($data->inm_comprador['inm_estado_civil_x'], $data->inm_comprador['inm_estado_civil_y']);
        $this->pdf->Write(0, 'X');

        if((int)$data->inm_comprador['inm_estado_civil_id'] !==1){
            $this->pdf->SetXY(58.5, 90);
            $this->pdf->Write(0, 'X');
        }
        return $this->pdf;
    }

    private function write_fecha(modelo $modelo){
        $write = $this->write_dia();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }


        $write = $this->write_mes(modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }


        $write = $this->write_year(modelo: $modelo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_genero(stdClass $data){
        $x = 144.5;
        $y = 77;

        if($data->inm_comprador['inm_comprador_genero'] === 'F'){

            $x = 150.5;
        }

        $pdf_exe = $this->write( valor: 'X', x: $x, y: $y);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $pdf_exe);
        }
        return $pdf_exe;
    }

    private function write_mes(modelo $modelo){
        $mes_letra = $modelo->mes['espaniol'][date('m')]['nombre'];


        $write = $this->write(valor: $mes_letra, x:128,y: 240);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_referencia(array $inm_referencia, array $keys_referencias){

        $write = $this->write_data(keys: $keys_referencias,row:  $inm_referencia);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }

    private function write_referencias(stdClass $data){
        $writes = array();
        foreach ($data->inm_referencias as $indice=>$inm_referencia){
            $keys_referencias = $this->get_key_referencias(indice: $indice);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener keys_referencias', data: $keys_referencias);
            }

            $write = $this->write_referencia(inm_referencia: $inm_referencia, keys_referencias: $keys_referencias);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
            }
            $writes[] = $write;
        }
        return $writes;
    }

    /**
     * Escribe una X en el pdf
     * @param string $name_entidad Nombre de la entidad para obtener campo
     * @param array $row Registro en proceso
     * @return Fpdi|array
     * @version 1.120.1
     */
    private function write_x(string $name_entidad, array $row): Fpdi|array
    {
        $name_entidad = trim($name_entidad);
        if($name_entidad === ''){
            return $this->error->error(mensaje: 'Error name_entidad esta vacio',data:  $name_entidad);
        }
        $key_x = $name_entidad.'_x';
        $key_y = $name_entidad.'_y';

        $keys = array($key_x, $key_y);
        $valida = (new valida())->valida_double_mayores_0(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar row',data:  $valida);
        }

        $x = $row[$key_x];
        $y = $row[$key_y];

        $valor = 'X';
        if(!isset($row[$name_entidad.'_descripcion']) || trim($row[$name_entidad.'_descripcion']) === 'NO APLICA'){
            $valor = '';
        }

        $result = $this->write(valor: $valor,x: $x, y: $y);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al escribir en pdf',data:  $result);
        }

        return $result;
    }

    private function write_year(modelo $modelo){
        $year = $modelo->year['espaniol'][date('Y')]['abreviado'];

        $write = $this->write(valor: $year, x:178,y: 240);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al escribir en pdf', data: $write);
        }
        return $write;
    }



}

