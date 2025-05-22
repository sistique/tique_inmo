<?php

namespace gamboamartin\inmuebles\models;

use base\orm\modelo;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_adjunto;
use gamboamartin\notificaciones\models\not_emisor;
use gamboamartin\notificaciones\models\not_mensaje;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\notificaciones\models\not_rel_mensaje;
use gamboamartin\validacion\validacion;
use PDO;
use stdClass;

class _dropbox
{
    public const string UPLOAD = "https://content.dropboxapi.com/2/files/upload";
    public const string DOWNLOAD = "https://content.dropboxapi.com/2/files/download";
    public const string PREVIEW = "https://api.dropboxapi.com/2/files/get_temporary_link";
    public const string DELETE = "https://api.dropboxapi.com/2/files/delete_v2";
    public const string REFRESH = "https://api.dropboxapi.com/oauth2/token";

    public PDO $link;

    public function __construct(PDO $link){
        $this->link = $link;
    }

    public function upload(string $archivo_drop, string $archivo_local = '', string $archivo_file = '', string $mode = 'add', bool $autorename = false): bool|string
    {
        $token = $this->obten_token();
        if (errores::$error) {
            $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $token);
            print_r($error);
            exit;
        }

        $generales = new generales();
        $ruta_base = $generales->ruta_base_dropbox;
        $path_base = $generales->path_base;

        $dropboxPath = $ruta_base.$archivo_drop;
        $localFilePath = $archivo_file;
        if($archivo_file === '' || $archivo_local !== ''){
            $localFilePath = $path_base.$archivo_local;

            if (!file_exists($localFilePath)) {
                die("❌ Archivo no encontrado: $localFilePath\n");
            }
        }

        $fileContent = file_get_contents($localFilePath);

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode([
                'path' => $dropboxPath,
                'mode' => $mode,
                'autorename' => $autorename
            ], JSON_UNESCAPED_SLASHES)
        ];

        $ch = curl_init(self::UPLOAD);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $id_dropbox = '';
        if (curl_errno($ch)) {
            echo "❌ Error cURL: " . curl_error($ch);
        } else {
            if($httpCode !== 200){
                $error = (new errores())->error(mensaje: 'Error de resultado dropbox', data: $response);
                print_r($error);
                exit;
            }
            $json = json_decode($response, true);
            $id_dropbox = $json['id'];

            echo "✅ Código HTTP: $httpCode\n";
            echo "📥 Respuesta: $response\n";
        }

        curl_close($ch);

        return $id_dropbox;
    }

    public function download(string $dropbox_id, string $archivo_local): bool|string
    {
        $token = $this->obten_token();
        if (errores::$error) {
            $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $token);
            print_r($error);
            exit;
        }

        $arguments = [
            'path' => $dropbox_id,
        ];

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode(
                $arguments
            )
        ];

        $ch = curl_init(self::DOWNLOAD);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error de cURL: ' . curl_error($ch);

            curl_close($ch);
            $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $token);
            print_r($error);
            exit;
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode === 200) {
                //file_put_contents($archivo_local, $response);
                echo "✅ Archivo descargado correctamente a: $archivo_local\n";

                if (ob_get_level() > 0) {
                    ob_end_clean();
                }

                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=\"$archivo_local\"");
                header("Content-Type: application/octet-stream");
                header("Content-Transfer-Encoding: binary");
                echo $response;
            } else {
                echo "❌ Error al descargar. Código HTTP: $httpCode\n";
                echo "Respuesta: $response\n";
                curl_close($ch);
                $error = (new errores())->error(mensaje: 'Error al descargar archivo', data: $token);
                print_r($error);
                exit;
            }
        }

        curl_close($ch);

        return $response;
    }

    public function preview(string $dropbox_id): bool|string
    {
        $generales = new generales();
        $path_base = $generales->path_base;

        $archivo_local = $path_base.'archivos/temporales/'.$dropbox_id.'.pdf';

        $token = $this->obten_token();
        if (errores::$error) {
            $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $token);
            print_r($error);
            exit;
        }

        $arguments = [
            'path' => $dropbox_id,
        ];

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode(
                $arguments
            )
        ];

        $ch = curl_init(self::DOWNLOAD);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error de cURL: ' . curl_error($ch);
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode === 200) {
                file_put_contents($archivo_local, $response);
                $ruta_mostrar = $generales->url_base.'archivos/temporales/'.$dropbox_id.'.pdf';
                echo "<iframe src=\"$ruta_mostrar\" width=\"100%\" height=\"600px\"></iframe>";
            } else {
                echo "❌ Error al descargar. Código HTTP: $httpCode\n";
                echo "Respuesta: $response\n";
            }
        }

        curl_close($ch);

        return $response;

        /*$token = $this->obten_token();
        if (errores::$error) {
            $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $token);
            print_r($error);
            exit;
        }

        $data = [
            'path' => $dropbox_id
        ];

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];

        $ch = curl_init(self::PREVIEW);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        curl_close($ch);

        if ($response) {
            $decoded = json_decode($response, true);
            $link = $decoded['link'] ?? null;

            if ($link) {
                echo "✅ Link temporal obtenido:\n$link\n";
                echo "<iframe src=\"$link\" width=\"100%\" height=\"600px\"></iframe>";
            } else {
                echo "❌ Error: no se pudo obtener el enlace\n$response";
            }
        } else {
            echo "❌ Error de conexión con la API de Dropbox.";
        }

        return $response;*/
    }

    public function delete(string $dropbox_id): bool{
        $token = $this->obten_token();
        if (errores::$error) {
            $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $token);
            print_r($error);
            exit;
        }

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        $data = json_encode([
            'path' => $dropbox_id
        ]);

        $ch = curl_init(self::DELETE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $decoded = json_decode($response, true);
            if (isset($decoded['metadata'])) {
                echo "✅ Archivo eliminado correctamente: " . $decoded['metadata']['name'];
            }else if(isset($decoded['error']['path_lookup']['.tag']) && $decoded['error']['path_lookup']['.tag'] === 'not_found'){
                echo "Documento no encontrado";
            } else {
                echo "❌ Error: $response";
                $error = (new errores())->error(mensaje: 'Error al eliminar archivo', data: $token);
                print_r($error);
                exit;
            }
        } else {
            echo "❌ Error al conectar con Dropbox.";
            $error = (new errores())->error(mensaje: 'Error al eliminar archivo', data: $token);
            print_r($error);
            exit;
        }

        return $response;
    }

    public function overwrite(string $dropbox_id, string $archivo_local)
    {
        $token = $this->obten_token();
        if (errores::$error) {
            $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $token);
            print_r($error);
            exit;
        }

        $file = fopen($archivo_local, 'rb');
        $fileSize = filesize($archivo_local);

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode([
                'path' => $dropbox_id,
                'mode' => 'overwrite',     // ⚠️ Esto indica que se sobrescriba
                'autorename' => false,
                'mute' => false
            ])
        ];

        $ch = curl_init(self::UPLOAD);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_INFILE, $file);
        curl_setopt($ch, CURLOPT_INFILESIZE, $fileSize);

        $response = curl_exec($ch);
        curl_close($ch);
        fclose($file);

        if ($response) {
            $decoded = json_decode($response, true);
            if (isset($decoded['name'])) {
                echo "✅ Archivo sobrescrito correctamente: " . $decoded['name'];
            } else {
                echo "❌ Error: $response";
            }
        } else {
            echo "❌ Error al conectar con Dropbox.";
        }

        return $response;
    }

    public function refresh(): bool|string
    {
        $newAccessToken = '';

        $appKey = (new generales())->app_key;
        $appSecret = (new generales())->app_secret;
        $refreshToken = (new generales())->refresh_token;

        $ch = curl_init(self::REFRESH);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_USERPWD, $appKey . ':' . $appSecret); // Autenticación básica

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken
        ]));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error de cURL: ' . curl_error($ch);
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode === 200) {
                $json = json_decode($response, true);
                $newAccessToken = $json['access_token'];
                echo $newAccessToken;
            } else {
                echo "❌ Error. Código HTTP: $httpCode\n";
                echo "Respuesta: $response";
            }
        }

        curl_close($ch);

        if($newAccessToken !== '') {
            $modelo_token_dropbox = new inm_token_dropbox(link: $this->link);
            $filtro_token['inm_token_dropbox.status'] = 'activo';
            $r_token_dropbox = $modelo_token_dropbox->filtro_and(filtro: $filtro_token);
            if (errores::$error) {
                $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $r_token_dropbox);
                print_r($error);
                exit;
            }

            $registro['codigo'] = $newAccessToken;
            $registro['descripcion'] = $newAccessToken;
            $registro['token'] = $newAccessToken;
            $registro['status'] = 'activo';
            if ($r_token_dropbox->n_registros <= 0) {
                $r_token = $modelo_token_dropbox->alta_registro(registro: $registro);
                if (errores::$error) {
                    $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $r_token);
                    print_r($error);
                    exit;
                }
            } else {
                $id = $r_token_dropbox->registros[0]['inm_token_dropbox_id'];
                $r_token = $modelo_token_dropbox->modifica_bd(registro: $registro, id: $id);
                if (errores::$error) {
                    $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $r_token);
                    print_r($error);
                    exit;
                }
            }
        }

        return $response;
    }

    public function obten_refresh_token(){
        $client_id = (new generales())->app_key;
        $client_secret = (new generales())->app_secret;
        $authorization_code = (new generales())->code_dropbox;
        $redirect_uri = 'http://localhost/callback.php';

        $postData = http_build_query([
            'code' => $authorization_code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirect_uri,
        ]);

        $headers = [
            'Authorization: Basic ' . base64_encode("$client_id:$client_secret"),
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $ch = curl_init(self::REFRESH);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        echo "Respuesta: " . $response;

    }

    public function obten_token(){
        $modelo_token_dropbox = new inm_token_dropbox(link: $this->link);
        $filtro_token['inm_token_dropbox.status'] = 'activo';
        $r_token_dropbox = $modelo_token_dropbox->filtro_and(filtro: $filtro_token);
        if (errores::$error) {
            $error = (new errores())->error(mensaje: 'Error al obtener registro token', data: $r_token_dropbox);
            print_r($error);
            exit;
        }

        if($r_token_dropbox->n_registros <= 0){
            $token = (new generales())->token;
        }else{
            $token = $r_token_dropbox->registros[0]['inm_token_dropbox_token'];
        }

        return $token;
    }
}