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
use gamboamartin\gastos\models\gt_proveedor;

class controlador_gt_proveedor extends \gamboamartin\gastos\controllers\controlador_gt_proveedor {

    public function leer_qr(bool $header, bool $ws = false): array
    {
        $registros = (new gt_proveedor($this->link))->leer_codigo_qr();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al leer el código QR del documento PDF', data: $registros,
                header: $header, ws: $ws);
        }

        $salida['draw'] = count($registros);
        $salida['recordsTotal'] = count($registros);
        $salida['recordsFiltered'] = count($registros);
        $salida['data'] = $registros;

        header('Content-Type: application/json');
        echo json_encode($salida);
        exit;
    }
}
