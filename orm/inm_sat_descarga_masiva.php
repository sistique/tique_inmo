<?php

namespace gamboamartin\inmuebles\models;

use gamboamartin\errores\errores;
use PDO;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\Fiel;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\FielRequestBuilder;
use PhpCfdi\SatWsDescargaMasiva\Services\Query\QueryParameters;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTimePeriod;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;
use PhpCfdi\SatWsDescargaMasiva\Shared\ServiceType;
use PhpCfdi\SatWsDescargaMasiva\WebClient\GuzzleWebClient;
use PhpCfdi\SatWsDescargaMasiva\Service;
use stdClass;

/**
 * Servicio para la Descarga Masiva de CFDI del SAT.
 *
 * Flujo:
 *   1. solicitar()   → genera la solicitud y devuelve el UUID de solicitud
 *   2. verificar()   → consulta el estado y obtiene los IDs de paquetes disponibles
 *   3. descargar()   → descarga cada paquete ZIP y guarda en disco
 */
class inm_sat_descarga_masiva
{
    private errores $error;
    private PDO $link;

    public function __construct(PDO $link)
    {
        $this->error = new errores();
        $this->link  = $link;
    }

    // -------------------------------------------------------------------------
    // Métodos públicos principales
    // -------------------------------------------------------------------------

    /**
     * Crea la autenticación FIEL a partir de un registro inm_sat_cer + inm_sat_key.
     *
     * @param int $inm_sat_cer_id  ID del certificado en BD
     * @param int $inm_sat_key_id  ID de la llave privada en BD
     * @return Fiel|array
     */
    public function fiel(int $inm_sat_cer_id, int $inm_sat_key_id): Fiel|array
    {
        if ($inm_sat_cer_id <= 0) {
            return $this->error->error(mensaje: 'inm_sat_cer_id debe ser mayor a 0', data: $inm_sat_cer_id);
        }
        if ($inm_sat_key_id <= 0) {
            return $this->error->error(mensaje: 'inm_sat_key_id debe ser mayor a 0', data: $inm_sat_key_id);
        }

        $cer_model = new inm_sat_cer(link: $this->link);
        $cer_r = $cer_model->registro(registro_id: $inm_sat_cer_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener certificado cer', data: $cer_r);
        }

        $key_model = new inm_sat_key(link: $this->link);
        $key_r = $key_model->registro(registro_id: $inm_sat_key_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener llave key', data: $key_r);
        }

        $ruta_cer = $cer_r['inm_sat_cer_ruta_certificado'] ?? '';
        $ruta_key = $key_r['inm_sat_key_ruta_llave'] ?? '';
        $contrasenia = $key_r['inm_sat_key_contrasenia'] ?? '';

        if (!file_exists($ruta_cer)) {
            return $this->error->error(mensaje: 'No existe el archivo .cer en: ' . $ruta_cer, data: $ruta_cer);
        }
        if (!file_exists($ruta_key)) {
            return $this->error->error(mensaje: 'No existe el archivo .key en: ' . $ruta_key, data: $ruta_key);
        }

        $credential = Credential::openFiles($ruta_cer, $ruta_key, $contrasenia);

        $fiel = new Fiel($credential);
        if (!$fiel->isValid()) {
            return $this->error->error(mensaje: 'La e.firma (FIEL) no es válida', data: []);
        }

        return $fiel;
    }

    /**
     * Realiza la solicitud de descarga masiva al SAT.
     *
     * @param int    $inm_sat_cer_id      ID del certificado
     * @param int    $inm_sat_key_id      ID de la llave privada
     * @param string $fecha_inicio        Fecha inicio periodo (YYYY-MM-DD)
     * @param string $fecha_fin           Fecha fin periodo (YYYY-MM-DD)
     * @param string $tipo_solicitud      'CFDI' o 'Metadata'
     * @param string $tipo_descarga       'Emitidos' o 'Recibidos'
     * @param string $rfc_tercero         RFC de tercero (vacío = propias)
     * @param string $tipo_comprobante    I, E, T, N, P (vacío = todos)
     * @return stdClass|array
     */
    public function solicitar(
        int    $inm_sat_cer_id,
        int    $inm_sat_key_id,
        string $fecha_inicio,
        string $fecha_fin,
        string $tipo_solicitud  = 'CFDI',
        string $tipo_descarga   = 'Recibidos',
        string $rfc_tercero     = '',
        string $tipo_comprobante = ''
    ): stdClass|array {

        $fiel = $this->fiel(inm_sat_cer_id: $inm_sat_cer_id, inm_sat_key_id: $inm_sat_key_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener FIEL', data: $fiel);
        }

        $service = $this->service(fiel: $fiel);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al crear servicio SAT', data: $service);
        }

        $periodo = DateTimePeriod::createFromValues($fecha_inicio . 'T00:00:00', $fecha_fin . 'T23:59:59');

        $requestType = $tipo_solicitud === 'Metadata'
            ? RequestType::metadata()
            : RequestType::xml();

        $downloadType = $tipo_descarga === 'Emitidos'
            ? DownloadType::issued()
            : DownloadType::received();

        $params = QueryParameters::create(
            period: $periodo,
            downloadType: $downloadType,
            requestType: $requestType,
        );

        if ($tipo_comprobante !== '') {
            $params = $params->withVoucherType(\PhpCfdi\SatWsDescargaMasiva\Shared\ComprobanteType::create($tipo_comprobante));
        }

        if ($rfc_tercero !== '') {
            $params = $params->withRfcMatch(\PhpCfdi\Rfc\Rfc::create($rfc_tercero));
        }

        $result = $service->query($params);

        $out = new stdClass();
        $out->status_code    = $result->getStatus()->getCode();
        $out->status_message = $result->getStatus()->getMessage();
        $out->uuid_solicitud = $result->getRequestId();
        $out->acepted        = $result->getStatus()->isAccepted();

        // Persistir en BD
        if ($out->acepted && $out->uuid_solicitud !== '') {
            $cer_model = new inm_sat_cer(link: $this->link);
            $cer_r = $cer_model->registro(registro_id: $inm_sat_cer_id);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener RFC del certificado', data: $cer_r);
            }

            $solicitud_model = new inm_sat_solicitud(link: $this->link);
            $solicitud_model->registro = [
                'tipo_solicitud'       => $tipo_solicitud,
                'tipo_descarga'        => $tipo_descarga,
                'tipo_comprobante'     => $tipo_comprobante,
                'rfc_tercero'          => $rfc_tercero,
                'fecha_inicio_periodo' => $fecha_inicio,
                'fecha_fin_periodo'    => $fecha_fin,
                'uuid_solicitud'       => $out->uuid_solicitud,
                'estatus'              => 'Aceptada',
                'inm_sat_cer_id'       => $inm_sat_cer_id,
            ];

            $alta = $solicitud_model->alta_bd();
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al guardar solicitud', data: $alta);
            }
            $out->inm_sat_solicitud_id = $alta['registros']['id'] ?? 0;
        }

        return $out;
    }

    /**
     * Verifica el estado de una solicitud y actualiza los paquetes disponibles.
     *
     * @param int    $inm_sat_cer_id        ID del certificado
     * @param int    $inm_sat_key_id        ID de la llave privada
     * @param int    $inm_sat_solicitud_id  ID de la solicitud en BD
     * @return stdClass|array
     */
    public function verificar(
        int $inm_sat_cer_id,
        int $inm_sat_key_id,
        int $inm_sat_solicitud_id
    ): stdClass|array {

        $fiel = $this->fiel(inm_sat_cer_id: $inm_sat_cer_id, inm_sat_key_id: $inm_sat_key_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener FIEL', data: $fiel);
        }

        $service = $this->service(fiel: $fiel);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al crear servicio SAT', data: $service);
        }

        $solicitud_model = new inm_sat_solicitud(link: $this->link);
        $solicitud_r = $solicitud_model->registro(registro_id: $inm_sat_solicitud_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener solicitud', data: $solicitud_r);
        }

        $uuid_solicitud = $solicitud_r['inm_sat_solicitud_uuid_solicitud'] ?? '';
        if ($uuid_solicitud === '') {
            return $this->error->error(mensaje: 'La solicitud no tiene uuid_solicitud', data: $solicitud_r);
        }

        $result = $service->verify($uuid_solicitud);

        $estatus = $result->getStatus()->getMessage();
        $estado  = $result->getStatusRequest()->getLabel();

        // Actualizar estatus en BD
        $solicitud_model->modifica_bd(
            registro: ['estatus' => $estado, 'total_solicitudes' => $result->getNumberCfdis()],
            id: $inm_sat_solicitud_id
        );
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al actualizar estatus de solicitud', data: $estatus);
        }

        // Guardar IDs de paquetes
        $paquetes_guardados = [];
        foreach ($result->getPackagesIds() as $id_paquete) {
            $paquete_model = new inm_sat_paquete(link: $this->link);
            $existe = $paquete_model->filtro_and(columnas_by_table: true, filtro: [
                'inm_sat_paquete.id_paquete'         => $id_paquete,
                'inm_sat_paquete.inm_sat_solicitud_id' => $inm_sat_solicitud_id,
            ]);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al verificar paquete existente', data: $existe);
            }

            if (empty($existe['registros'])) {
                $paquete_model->registro = [
                    'id_paquete'            => $id_paquete,
                    'inm_sat_solicitud_id'  => $inm_sat_solicitud_id,
                    'estatus'               => 'pendiente',
                    'total_cfdi'            => 0,
                ];
                $alta_paquete = $paquete_model->alta_bd();
                if (errores::$error) {
                    return $this->error->error(mensaje: 'Error al guardar paquete', data: $alta_paquete);
                }
                $paquetes_guardados[] = $id_paquete;
            }
        }

        $out = new stdClass();
        $out->estatus           = $estatus;
        $out->estado            = $estado;
        $out->total_cfdis       = $result->getNumberCfdis();
        $out->packages_ids      = $result->getPackagesIds();
        $out->paquetes_guardados = $paquetes_guardados;

        return $out;
    }

    /**
     * Descarga un paquete y lo guarda en disco.
     *
     * @param int    $inm_sat_cer_id       ID del certificado
     * @param int    $inm_sat_key_id       ID de la llave privada
     * @param int    $inm_sat_paquete_id   ID del paquete en BD
     * @param string $directorio_destino   Ruta de directorio donde guardar el ZIP
     * @return stdClass|array
     */
    public function descargar(
        int    $inm_sat_cer_id,
        int    $inm_sat_key_id,
        int    $inm_sat_paquete_id,
        string $directorio_destino = ''
    ): stdClass|array {

        $fiel = $this->fiel(inm_sat_cer_id: $inm_sat_cer_id, inm_sat_key_id: $inm_sat_key_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener FIEL', data: $fiel);
        }

        $service = $this->service(fiel: $fiel);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al crear servicio SAT', data: $service);
        }

        $paquete_model = new inm_sat_paquete(link: $this->link);
        $paquete_r = $paquete_model->registro(registro_id: $inm_sat_paquete_id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener paquete', data: $paquete_r);
        }

        $id_paquete = $paquete_r['inm_sat_paquete_id_paquete'] ?? '';
        if ($id_paquete === '') {
            return $this->error->error(mensaje: 'Paquete sin id_paquete', data: $paquete_r);
        }

        if ($directorio_destino === '') {
            $directorio_destino = __DIR__ . '/../archivos/sat_xml/';
        }

        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0755, true);
        }

        $result = $service->download($id_paquete);

        if (!$result->getStatus()->isAccepted()) {
            return $this->error->error(
                mensaje: 'Error al descargar paquete: ' . $result->getStatus()->getMessage(),
                data: $result->getStatus()->getCode()
            );
        }

        $ruta_archivo = $directorio_destino . $id_paquete . '.zip';
        file_put_contents($ruta_archivo, $result->getPackageContent());

        // Actualizar paquete en BD
        $paquete_model->modifica_bd(
            registro: ['ruta_archivo' => $ruta_archivo, 'estatus' => 'descargado'],
            id: $inm_sat_paquete_id
        );
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al actualizar paquete', data: $ruta_archivo);
        }

        $out = new stdClass();
        $out->ruta_archivo   = $ruta_archivo;
        $out->id_paquete     = $id_paquete;
        $out->status_code    = $result->getStatus()->getCode();
        $out->status_message = $result->getStatus()->getMessage();

        return $out;
    }

    // -------------------------------------------------------------------------
    // Métodos privados de apoyo
    // -------------------------------------------------------------------------

    /**
     * Crea la instancia del Service del SAT
     */
    private function service(Fiel $fiel): Service|array
    {
        $requestBuilder = new FielRequestBuilder($fiel);
        $webClient      = new GuzzleWebClient();

        return new Service(
            requestBuilder: $requestBuilder,
            webClient: $webClient
        );
    }
}

