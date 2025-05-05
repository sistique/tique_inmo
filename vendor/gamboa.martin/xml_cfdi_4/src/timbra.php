<?php
namespace gamboamartin\xml_cfdi_4;
use config\pac;
use gamboamartin\errores\errores;
use SoapClient;

use stdClass;
use Throwable;

class timbra{
    private errores $error;
    private validacion $valida;

    public function __construct(){
        $this->error = new errores();
        $this->valida = new validacion();

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Esta función valida e inicializa los elementos base para consulta y cancelación.
     * @param string $rfc_emisor es el RFC del emisor. No puede estar vacío.
     * @param string $rfc_receptor es el RFC del receptor. No puede estar vacío.
     * @param string $total es el total de la factura o CFDI. No puede estar vacío.
     * @param string $uuid es el Folio Fiscal. No puede estar vacío.
     * @return array|stdClass devuelve un objeto con los datos validados o un array con los detalles del error.
     * @version 3.2.0
     */
    private function datos_base(string $rfc_emisor, string $rfc_receptor, string $total, string $uuid): array|stdClass
    {
        $rfc_emisor = trim($rfc_emisor);
        if($rfc_emisor === ''){
            return $this->error->error(mensaje: 'Error rfc_emisor esta vacio',data: $rfc_emisor);
        }
        $rfc_receptor = trim($rfc_receptor);
        if($rfc_receptor === ''){
            return $this->error->error(mensaje: 'Error rfc_receptor esta vacio',data: $rfc_receptor);
        }
        $uuid = trim($uuid);
        if($uuid === ''){
            return $this->error->error(mensaje: 'Error uuid esta vacio',data: $uuid);
        }
        $total = trim($total);
        if($total === ''){
            return $this->error->error(mensaje: 'Error total esta vacio',data: $total);
        }

        $datos = new stdClass();
        $datos->rfc_emisor = $rfc_emisor;
        $datos->rfc_receptor = $rfc_receptor;
        $datos->uuid = $uuid;
        $datos->total = $total;

        return $datos;

    }

    final public function cancela(string $motivo_cancelacion, string $rfc_emisor, string $rfc_receptor, string $uuid,
                                  string $pass_csd = '', string $ruta_cer = '', string $ruta_key = '',
                                  string $total = '', $uuid_sustitucion = ''){



        $datos = $this->integra_datos_base(rfc_emisor: $rfc_emisor,rfc_receptor:  $rfc_receptor,total:  $total,uuid:  $uuid);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener datos base',data: $datos);
        }

        if($motivo_cancelacion === '01'){
            if($uuid_sustitucion === ''){
                return $this->error->error(mensaje: 'Error uuid_sustitucion debe existir',data: $uuid_sustitucion);
            }
        }

        $ws= $datos->pac->ruta_pac;
        $usuario_int = $datos->pac->usuario_integrador;


        $params = array();


        try {
            $client = new SoapClient($ws);
        }
        catch (Throwable $e){
            return $this->error->error('Error al cancelar',array($e,$params));
        }


        $csd = $this->get_data_csd(ruta_cer: $ruta_cer,ruta_key:  $ruta_key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar pems',data:  $csd);
        }

        if($uuid_sustitucion!=='') {

            $response = $client->cancelar($usuario_int, $csd->key, $csd->cer, $pass_csd, $datos->uuid, $datos->rfc_emisor,
                $datos->rfc_receptor, $datos->total, $motivo_cancelacion, $uuid_sustitucion);

        }
        else{
            $response = $client->cancelar($usuario_int, $csd->key, $csd->cer, $pass_csd, $datos->uuid,
                $datos->rfc_emisor, $datos->rfc_receptor, $datos->total, $motivo_cancelacion);

        }

        $tipo_resultado = $response->status;
        $cod_mensaje = $response->code;
        $mensaje = $response->message;
        $cod_error = $response->code;
        $mensaje_error = $response->message;
        $salida = $response->data;

        if(trim($tipo_resultado) !== 'success'){
            return $this->error->error(mensaje: $mensaje,data: $response);
        }

        $data_acuse = json_decode($response->data,TRUE);



        $data = new stdClass();
        $data->response = $response;
        $data->result = $response;
        $data->tipo_resultado = $tipo_resultado;
        $data->cod_mensaje = $cod_mensaje;
        $data->mensaje = $mensaje;
        $data->cod_error = $cod_error;
        $data->mensaje_error = $mensaje_error;
        $data->salida = $salida;
        $data->acuse = $data_acuse['acuse'];
        $data->uuid  = $data_acuse['uuid'];


        return $data;
    }

    final public function consulta_estado_sat(string $rfc_emisor, string $rfc_receptor, string $total, string $uuid){

        $datos = $this->integra_datos_base(rfc_emisor: $rfc_emisor,rfc_receptor:  $rfc_receptor,total:  $total,uuid:  $uuid);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener datos base',data: $datos);
        }


        $ws= $datos->pac->ruta_pac;
        $usuario_int = $datos->pac->usuario_integrador;
        $params = array();
        try {
            $client = new SoapClient($ws);
        }
        catch (Throwable $e){
            return $this->error->error('Error al cancelar',array($e,$params));
        }


        $response = $client->consultarEstadoSAT($usuario_int,  $datos->uuid, $datos->rfc_emisor, $datos->rfc_receptor,
            $datos->total);


        $tipo_resultado = $response->CodigoEstatus;
        $cod_mensaje = $response->Estado;
        $mensaje = $response->Estado;
        $cod_error = $response->CodigoEstatus;
        $mensaje_error = $response->Estado;
        $status_cancelacion = $response->EstatusCancelacion;

        if(trim($tipo_resultado) === '300' || trim($tipo_resultado) === 'N - 602: Comprobante no encontrado.'){
            return $this->error->error(mensaje: 'Error al obtener status',data: $response);
        }


        $data = new stdClass();
        $data->response = $response;
        $data->result = $response;
        $data->tipo_resultado = $tipo_resultado;
        $data->cod_mensaje = $cod_mensaje;
        $data->mensaje = $mensaje;
        $data->cod_error = $cod_error;
        $data->mensaje_error = $mensaje_error;

        return $data;


    }

    final public function consulta_cfdi_sat(string $uuid){

        $uuid = trim($uuid);
        if($uuid === ''){
            return $this->error->error(mensaje: 'Error uuid esta vacio',data: $uuid);
        }


        $pac = new pac();
        $keys = array('ruta_pac','usuario_integrador');
        $valida = $this->valida->valida_existencia_keys(keys: $keys,registro:  $pac);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar pac',data: $valida);
        }

        $ws= $pac->ruta_pac;
        $usuario_int = $pac->usuario_integrador;
        $params = array();
        try {
            $client = new SoapClient($ws);
        }
        catch (Throwable $e){
            return $this->error->error('Error al cancelar',array($e,$params));
        }

        $response = $client->consultarCFDI($usuario_int,  $uuid);

        $tipo_resultado = $response->code;
        $cod_mensaje = $response->message;
        $mensaje = $response->message;
        $cod_error = $response->code;
        $mensaje_error = $response->code;
        $xml = $response->data;

        if(trim($tipo_resultado) === '300' || trim($tipo_resultado) === 'N - 602: Comprobante no encontrado.'){
            return $this->error->error(mensaje: 'Error al obtener status',data: $response);
        }


        $data = new stdClass();
        $data->response = $response;
        $data->result = $response;
        $data->tipo_resultado = $tipo_resultado;
        $data->cod_mensaje = $cod_mensaje;
        $data->mensaje = $mensaje;
        $data->cod_error = $cod_error;
        $data->mensaje_error = $mensaje_error;
        $data->xml = $xml;

        return $data;


    }

    private function csd(string $ruta_cer, string $ruta_key): stdClass
    {
        $key = file_get_contents($ruta_key);
        $cer = file_get_contents($ruta_cer);

        $data = new stdClass();
        $data->key = base64_encode($key);
        $data->cer = base64_encode($cer);
        return $data;

    }

    private function get_data_csd(string $ruta_cer, string $ruta_key){
        $valida = $this->valida_ruta(file: $ruta_key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar ruta_key_pem',data:  $valida);
        }
        $valida = $this->valida_ruta(file: $ruta_cer);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar ruta_cer_pem',data:  $valida);
        }

        $csd = $this->csd(ruta_cer: $ruta_cer, ruta_key: $ruta_key);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar pems',data:  $csd);
        }
        return $csd;
    }

    private function get_data_pem(string $ruta_cer_pem, string $ruta_key_pem){
        $valida = $this->valida_ruta(file: $ruta_key_pem);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar ruta_key_pem',data:  $valida);
        }
        $valida = $this->valida_ruta(file: $ruta_cer_pem);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar ruta_cer_pem',data:  $valida);
        }

        $pems = $this->pems(ruta_cer_pem: $ruta_cer_pem, ruta_key_pem: $ruta_key_pem);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar pems',data:  $pems);
        }
        return $pems;
    }

    /**
     * Integra los elementos base para una llamada al PAC
     * @param string $rfc_emisor RFC de quien emite el CFDI
     * @param string $rfc_receptor RFC de quien recibe la factura
     * @param string $total Total de la factura
     * @param string $uuid Folio fiscal
     * @return array|stdClass
     * @version
     */
    private function integra_datos_base(string $rfc_emisor, string $rfc_receptor, string $total, string $uuid): array|stdClass
    {
        $datos = $this->datos_base(rfc_emisor: $rfc_emisor,rfc_receptor:  $rfc_receptor,total:  $total,uuid:  $uuid);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener datos base',data: $datos);
        }

        $pac = new pac();
        $keys = array('ruta_pac','usuario_integrador');
        $valida = $this->valida->valida_existencia_keys(keys: $keys,registro:  $pac);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar pac',data: $valida);
        }

        $datos->pac = $pac;
        return $datos;
    }

    private function pems(string $ruta_cer_pem, string $ruta_key_pem): stdClass
    {
        $key_pem = file_get_contents($ruta_key_pem);
        $cer_pem = file_get_contents($ruta_cer_pem);

        $data = new stdClass();
        $data->key = $key_pem;
        $data->cer = $cer_pem;
        return $data;

    }

    public function timbra(string $contenido_xml, string $id_comprobante = '', string $ruta_cer_pem = '',
                           string $ruta_key_pem = '', string $pac_prov=''): array|stdClass
    {

        $contenido_xml = trim($contenido_xml);
        if($contenido_xml === ''){
            return $this->error->error(mensaje: 'xml no puede venir vacio',data: $contenido_xml,es_final: true);
        }

        $pac = new pac();
        if($id_comprobante === ''){
            $id_comprobante = (string)time();
        }

        $keys = array('ruta_pac','usuario_integrador');
        $valida = $this->valida->valida_existencia_keys(keys: $keys,registro:  $pac);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar pac',data: $valida);
        }


        $ws= $pac->ruta_pac;
        $usuario_int = $pac->usuario_integrador;
        $timbra_rs = $pac->timbra_rs;
        $aplica_params = true;
        $tipo_entrada = 'xml';

        if($pac_prov!==''){
            $ws= $pac->pac->$pac_prov->ruta;
            $usuario_int = $pac->pac->$pac_prov->pass;
            $timbra_rs = $pac->pac->$pac_prov->timbra_rs;
            $aplica_params = $pac->pac->$pac_prov->aplica_params;
            $tipo_entrada = $pac->pac->$pac_prov->tipo_entrada;
        }

        $base64Comprobante = base64_encode($contenido_xml);


        try {
            if($aplica_params) {
                $params = array();
                $params['usuarioIntegrador'] = $usuario_int;
                $params['xmlComprobanteBase64'] = $base64Comprobante;
                $params['idComprobante'] = $id_comprobante;
                $client = new SoapClient($ws, $params);
            }
            else{
                $client = new SoapClient($ws);
            }
        }
        catch (Throwable $e){
            return $this->error->error(mensaje: 'Error al timbrar',data: array($e,htmlentities($contenido_xml)),
                es_final: true);
        }

        if($aplica_params){
            $response = $client->__soapCall($timbra_rs, array('parameters' => $params));

            $result = $response->TimbraCFDIResult->anyType;
        }
        else{

            $pems = $this->get_data_pem(ruta_cer_pem: $ruta_cer_pem,ruta_key_pem:  $ruta_key_pem);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar pems',data:  $pems);
            }


            $response = $client->timbrarJSON3($usuario_int, $base64Comprobante, $pems->key, $pems->cer);


            $result = (array)$response;

            $cod_error = 0;
            if((string)$result['code'] !== '200'){
                $cod_error = $result['code'];
                $result[0] = 'Error';
                $result[1] = $result['message'];
                $result[2] = $result['message'];
                $result[4] = $result['message'];
                $result[5] = $result['message'];
                $result[6] = $cod_error;
                $result[7] = $result['message'];
                $result[8] = $result['message'];

            }
            if((string)$cod_error === '307'){
                $cod_error = 0;

            }

            if((string)$cod_error === '0'){
                $data_json = json_decode($response->data);

                $result[0] = 'Exito';
                $result[1] = 'Exito';
                $result[2] = 'Exito';
                $result[6] = $cod_error;
                $result[7] = '';
                $result[8] = '';
                $result[4] = $data_json->CodigoQR;
                $result[5] = $data_json->CadenaOriginalSAT;
                $result[3] = $data_json->XML;

            }


        }




        $tipo_resultado = $result[0];
        $cod_mensaje = $result[1];
        $mensaje = $result[2];
        $cod_error = $result[6];
        $mensaje_error = $result[7];
        $salida = $result[8];
        $qr_code = $result[4];
        $txt = $result[5];

        if((string)$cod_error !=='0'){
            return $this->error->error(mensaje: 'Error al timbrar',data: $result,es_final: true);
        }

        $xml_sellado = $result[3];

        $lee_xml = (new xml())->get_datos_xml(xml_data: $xml_sellado);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener datos',data:  $lee_xml);
        }


        $uuid = $lee_xml['tfd']['UUID'];

        $data = new stdClass();
        $data->response = $response;
        $data->result = $result;
        $data->tipo_resultado = $tipo_resultado;
        $data->cod_mensaje = $cod_mensaje;
        $data->mensaje = $mensaje;
        $data->cod_error = $cod_error;
        $data->mensaje_error = $mensaje_error;
        $data->salida = $salida;
        $data->qr_code = $qr_code;
        $data->txt = $txt;
        $data->uuid = $uuid;
        $data->xml_sellado = $xml_sellado;


        return $data;


    }



    private function valida_ruta(string $file): bool|array
    {
        $file = trim($file);
        if($file === ''){
            return $this->error->error(mensaje: 'Error file esta vacio',data: $file, es_final: true);
        }
        if(!file_exists($file)){
            return $this->error->error(mensaje: 'Error file no existe',data: $file, es_final: true);
        }
        return true;
    }
}
